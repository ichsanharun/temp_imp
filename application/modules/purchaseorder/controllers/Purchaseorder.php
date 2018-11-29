<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Purchaseorder
 */

class Purchaseorder extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Purchaseorder.View";
    protected $addPermission    = "Purchaseorder.Add";
    protected $managePermission = "Purchaseorder.Manage";
    protected $deletePermission = "Purchaseorder.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array('Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Purchaserequest/Purchaserequest_model',
                                 'Purchaserequest/Detailpurchaserequest_model',
                                 'Purchaserequest/Purchaserequestpending_model',
                                 'Purchaserequest/Detailpurchaserequestpending_model',
                                 //'Pendingpr/Pendingpr_model',
                                 //'Pendingpr/Detailpendingpr_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        $this->template->title('Purchase Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Purchaseorder_model->order_by('no_po','DESC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Purchase Order');
        $this->template->render('list');
    }

    //Create New Purchase Order
    public function create()
    {

        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');
        $this->template->set('cabang',$cabang);
        $this->template->set('supplier',$supplier);

        if($this->uri->segment(3) == ""){

            $data = $this->Purchaserequest_model
            ->join("cabang","trans_pr_header.kdcab=cabang.kdcab","inner")
            ->get_data("proses_po = 'Pending' OR proses_po IS NULL","trans_pr_header");
            //$data = $this->Purchaserequest_model->order_by('no_pr','ASC')->find_all_by(array('proses_po'=>0));
        }else{
            $data = $this->Purchaserequest_model
            ->select("*,trans_pr_header.no_pr as nopr")
            ->join("cabang","trans_pr_header.kdcab=cabang.kdcab","inner")
            ->get_data("id_supplier ='".$this->uri->segment(3)."' AND proses_po = 'Pending' OR id_supplier ='".$this->uri->segment(3)."' AND proses_po IS NULL","trans_pr_header");
        }
        $this->template->set('results', $data);

        $this->template->title('Input Purchase Order');
        $this->template->render('po_form');
    }

    public function createpending()
    {
        $cabang = $this->Cabang_model->find_all_by(array('deleted'=>0));
        $this->template->set('cabang',$cabang);
        if($this->uri->segment(3) == ""){
            $data = $this->Pendingpr_model->order_by('no_pr','ASC')->find_all();
        }else{
            $data = $this->Pendingpr_model->order_by('no_pr','ASC')->find_all_by(array('kdcab'=>$this->uri->segment(3)));
        }
        $this->template->set('results', $data);
        $this->template->title('Input Purchase Order From Pending');
        $this->template->render('list_pr_pending');
    }

    //Create New Purchase Order
    public function proses()
    {
        $getparam = explode(";",$_GET['param']);
        $getpr = $this->Detailpurchaserequest_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->get_where_in('trans_pr_header.no_pr',$getparam,'trans_pr_header');

        $and = " proses_po IS NULL ";
        $getitempr = $this->Detailpurchaserequest_model->get_where_in_and('no_pr',$getparam,$and,'trans_pr_detail');
        $pajak = $this->Purchaseorder_model->get_data('ppn IS NOT NULL','parameter');
        $this->template->set('pajak', $pajak);
        $this->template->set('param',$getparam);
        $this->template->set('headerpr',$getpr);
        $this->template->set('getitempr',$getitempr);
        $this->template->title('Input Purchase Order');
        $this->template->render('proses_po_form');
    }

    //Create New Purchase Order
    public function prosespopending()
    {
        $getparam = explode(";",$_GET['param']);
        $getpr = $this->Detailpendingpr_model->get_where_in('no_pr_pending',$getparam,'trans_pr_pending_header');
        $getitempr = $this->Detailpendingpr_model->get_where_in('no_pr_pending',$getparam,'trans_pr_pending_detail');
        $driver = $this->Purchaseorder_model->pilih_driver()->result();
        $kendaraan = $this->Purchaseorder_model->pilih_kendaraan()->result();
        $this->template->set('param',$getparam);
        $this->template->set('headerpr',$getpr);
        $this->template->set('getitempr',$getitempr);
        $this->template->set('driver',$driver);
        $this->template->set('kendaraan',$kendaraan);
        $this->template->title('Input Purchase Order');
        $this->template->render('deliveryorder_form_pending');
    }


    //Get detail Cabang
    function get_cabang(){
        $idcab = $_GET['idcab'];
        $cabang = $this->Purchaserequest_model->get_cabang($idcab)->row();

        echo json_encode($cabang);
    }

    public function get_itemprbycab(){
        $kdcab = $this->input->post('idcab');
        $getpr = $this->Purchaserequest_model->find_all_by(array('kdcab'=>$kdcab,'stprrder'=>0));
        //$getitempr = $this->Detailpurchaserequest_model->find_all_by(array('no_pr'=>$getpr->no_pr));
        $data['pr'] = $getpr;
        $data['cabang'] = $this->Cabang_model->find_by(array('kdcab'=>$kdcab));;
        //$data['itempr'] = $getitempr;
        $this->load->view('ajax/get_itemprbycab',$data);
    }

    public function hapus_item_po(){
        $id=$this->input->post('ID');
        if(!empty($id)){
           $result = $this->Detailpurchaseorder_model->delete_where(array('id'=>$id));
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function hapus_header_po(){
        $nopo = $this->input->post('NO_PO');
        if(!empty($nopo)){
           $result = $this->Purchaseorder_model->delete($nopr);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function saveheaderpo(){
        $session = $this->session->userdata('app_session');
        $nopo = $this->Purchaseorder_model->generate_nopo($session['kdcab']);
        $cabang = $this->Purchaseorder_model->cek_data(array('kdcab'=>$this->input->post('kdcab_po')),'cabang');
        $pajak = $this->Purchaseorder_model->get_data('ppn IS NOT NULL','parameter');

        $dataheaderpo = array(
            'no_po' => $nopo,
            'kdcab' => $this->input->post('kdcab_po'),
            'nm_cabang' => $this->input->post('nmcabang_po'),
            'plan_delivery_date' => $this->input->post('tglpo'),
            'real_delivery_date' => $this->input->post('tglpo_real'),
            'fiskal' => $this->input->post('fiskal_total'),
            'non_fiskal' => $this->input->post('non_fiskal_total'),
            'total_po' => $this->input->post('harga_total'),
            'pph' => $pajak->pph,
            'ppn' => $pajak->ppn,
            'created_on' => date('Y-m-d H:i:s'),
            'id_supplier' => $this->input->post('id_supplier'),
            'nm_supplier' => $this->input->post('nm_supplier'),
            'tgl_po' => date('Y-m-d'),
            'status' => $this->input->post('status_po'),
            'created_by' => $session['id_user']
        );

        $detail = array(
            'nopr_topo'=>$_POST['nopr_topo'],
            'id_barang'=>$_POST['id_barang'],
            //'qty_supply'=>$_POST['qty_supply']
            );
        //$counttopo = $this->Purchaseorder_model->cek_data(,'barang_stock');

        //print_r($_POST['nopr_topo']);
        //echo count($detail['nopr_topo']);die();

        $this->db->trans_begin();

        for($i=0;$i < count($detail['nopr_topo']);$i++){
            $key = array(
            'no_pr' => $_POST['nopr_topo'][$i],
            'id_barang' => $_POST['id_barang'][$i]
            );
            $getitempr = $this->Detailpurchaserequest_model->find_by($key);

            $dataitem_po = array(
                'no_po' => $this->Purchaseorder_model->generate_nopo($session['kdcab']),
                'no_pr' => $_POST['nopr_topo'][$i],
                'id_barang' => $getitempr->id_barang,
                'nm_barang' => $getitempr->nm_barang,
                'satuan' => $getitempr->satuan,
                'qty_po' => $getitempr->qty_pr,
                'fiskal' => $_POST['fiskal'][$i],
                'non_fiskal' => $_POST['non_fiskal'][$i],
                'harga_satuan' => $_POST['harga_beli'][$i],
                'sub_total_po' => $_POST['subtotal'][$i],
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $session['id_user']
            );
            $this->db->insert('trans_po_detail',$dataitem_po);

            $keyclose_pr = array(
                'no_pr' => $_POST['nopr_topo'][$i],
                'id_barang' => $getitempr->id_barang
                );

            $checkqty = $this->input->post('qty_po')[$i]+$this->input->post('qty_supply')[$i];
            $newqty = $this->input->post('qty_supply')[$i];
            if($checkqty == $this->input->post('qty_pr')[$i]){
                //berarti PR CLOSE
                $this->db->where($keyclose_pr);
                $this->db->update('trans_pr_detail',array('proses_po'=>1,'qty_po'=>$checkqty));//Detail PO sudah semua
                $this->db->where(array('no_pr' => $_POST['nopr_topo'][$i]))
                ->update('trans_pr_header',array('proses_po'=>1));//Detail PO sudah semua
            }else{
                $this->db->where($keyclose_pr);
                $this->db->update('trans_pr_detail',array('qty_po'=>$checkqty));//Jika masih ada sisa qty
                $this->db->where(array('no_pr' => $_POST['nopr_topo'][$i]))
                ->update('trans_pr_header',array('proses_po'=>'Pending'));//Detail PO sudah semua
            }
            /*
            if($this->input->post('status_po') == "PO"){ // ini berarti proses DO dari SO biasa
                $newqty = $this->input->post('qty_pr')[$i]-$this->input->post('qty_supply')[$i];
                if($this->input->post('qty_supply')[$i] == $this->input->post('qty_pr')[$i]){
                    //berarti PR CLOSE
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail',array('proses_po'=>1,'qty_pr'=>$newqty));//Detail SO sudah semua
                }else{
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail',array('qty_pr'=>$newqty));//Jika masih ada sisa qty
                }
            }else{
                 $newqtypending = $this->input->post('qty_pr')[$i]-$this->input->post('qty_supply')[$i];
               if($this->input->post('qty_supply')[$i] == $this->input->post('qty_pr')[$i]){
                    //berarti SO CLOSE
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_pending_detail',array('proses_po'=>1,'qty_pr'=>$newqtypending));//Detail SO sudah semua
                }else{
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_pending_detail',array('qty_pr'=>$newqtypending));//Jika masih ada sisa qty
                }
            }

            $keyclose_pr = array(
                'no_pr' => $_POST['nopr_topo'][$i],
                'id_barang' => $getitempr->id_barang
                );
            if($this->input->post('status_po') == "PO"){ // ini berarti proses DO dari SO biasa
                $newqty = $this->input->post('qty_pr')[$i]-$this->input->post('qty_supply')[$i];
                if($this->input->post('qty_supply')[$i] == $this->input->post('qty_pr')[$i]){
                    //berarti PR CLOSE
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail',array('proses_po'=>1,'qty_pr'=>$newqty));//Detail SO sudah semua
                }else{
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail',array('qty_pr'=>$newqty));//Jika masih ada sisa qty
                }
            }else{
                 $newqtypending = $this->input->post('qty_pr')[$i]-$this->input->post('qty_supply')[$i];
               if($this->input->post('qty_supply')[$i] == $this->input->post('qty_pr')[$i]){
                    //berarti SO CLOSE
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_pending_detail',array('proses_po'=>1,'qty_pr'=>$newqtypending));//Detail SO sudah semua
                }else{
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_pending_detail',array('qty_pr'=>$newqtypending));//Jika masih ada sisa qty
                }
            }

            //Update STOK REAL
            //$count = $this->Purchaseorder_model->cek_data(array('id_barang'=>$getitempr->id_barang,'kdcab'=>$session['kdcab']),'barang_stock');
            //$this->db->where(array('id_barang'=>$getitempr->id_barang,'kdcab'=>$session['kdcab']));
            //$this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock-$_POST['qty_supply'][$i]));
            //Update STOK REAL
            */
        }


        //Update counter NO_PO
        $counter = $this->Purchaseorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        //$this->db->where(array('kdcab'=>$session['kdcab']));
        //$this->db->update('cabang',array('no_suratjalan'=>$count->no_suratjalan+1));
        $this->db->where(array('kdcab'=>$session['kdcab']));
		    $this->db->update('cabang',array('no_po'=>$counter->no_po+1));
        //Update counter NO_PO

        $this->db->insert('trans_po_header',$dataheaderpo);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, simpan data..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, simpan data..!!!"
            );
        }
        echo json_encode($param);
    }

    function print_request($nopo){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $po_data = $this->Purchaseorder_model->find_data('trans_po_header',$nopo,'no_po');
        $cabang = $this->Purchaseorder_model->cek_data(array('kdcab'=>$po_data->kdcab),'cabang');
        $detail = $this->Detailpurchaseorder_model->find_all_by(array('no_po' => $nopo));

        $this->template->set('po_data', $po_data);
        $this->template->set('cabang', $cabang);
        $this->template->set('detail', $detail);

        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
}

?>
