<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Purchaserequest
 */

class Pendingpo extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Purchaserequest.View";
    protected $addPermission    = "Purchaserequest.Add";
    protected $managePermission = "Purchaserequest.Manage";
    protected $deletePermission = "Purchaserequest.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array( 'Purchaserequest/Purchaserequest_model',
        						              'Purchaserequest/Detailpurchaserequest_model',
        						              'Purchaserequest/Purchaserequesttmp_model',
                                  'Aktifitas/aktifitas_model'
                                ));

        $this->template->title('Purchase Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Purchaserequest_model->get_pending_po();
        $this->template->set('results', $data);
        $this->template->title('Pending Purchase Order');
        $this->template->render('list');
    }

    //Create New Purchase Order
    public function create()
    {

        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $supplier = $this->Purchaserequestpending_model->get_data('1=1','supplier');
        $this->template->set('cabang',$cabang);
        $this->template->set('supplier',$supplier);

        if($this->uri->segment(3) == ""){

            $data = $this->Purchaserequest_model
            ->join("cabang","trans_pr_header.kdcab=cabang.kdcab","inner")
            ->get_data("proses_po IS NULL","trans_pr_header");
            //$data = $this->Purchaserequest_model->order_by('no_pr','ASC')->find_all_by(array('proses_po'=>0));
        }else{
            $data = $this->Purchaserequest_model
            ->select("*,trans_pr_header.no_pr as nopr")
            ->join("cabang","trans_pr_header.kdcab=cabang.kdcab","inner")
            ->get_data("id_supplier ='".$this->uri->segment(3)."' AND proses_po IS NULL","trans_pr_header");
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
        $getpr = $this->Detailpurchaserequestpending_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->get_where_in('trans_pr_header.no_pr',$getparam,'trans_pr_header');

        $and = " proses_po IS NULL ";
        $getitempr = $this->Detailpurchaserequestpending_model->get_where_in_and('no_pr',$getparam,$and,'trans_pr_detail');
        $pajak = $this->Purchaserequestpending_model->get_data('ppn IS NOT NULL','parameter');
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
        $driver = $this->Purchaserequestpending_model->pilih_driver()->result();
        $kendaraan = $this->Purchaserequestpending_model->pilih_kendaraan()->result();
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

    //Get detail Sales
    /*
    function get_salesman(){
        $idsales = $_GET['idsales'];
        $salesman = $this->Purchaserequest_model->get_marketing($idsales)->row();

        echo json_encode($salesman);
    }*/

    public function get_itemprbycab(){
        $kdcab = $this->input->post('idcab');
        $getpr = $this->Purchaserequest_model->find_all_by(array('kdcab'=>$kdcab,'stprrder'=>0));
        //$getitempr = $this->Detailpurchaserequestpending_model->find_all_by(array('no_pr'=>$getpr->no_pr));
        $data['pr'] = $getpr;
        $data['cabang'] = $this->Cabang_model->find_by(array('kdcab'=>$kdcab));;
        //$data['itempr'] = $getitempr;
        $this->load->view('ajax/get_itemprbycab',$data);
    }
    /*
    public function set_itempo(){
        $session = $this->session->userdata('app_session');
        $nopr = $this->input->post('NOSO');
        $idbrg = $this->input->post('IDBRG');
        $cab = $this->input->post('CUS');
        $by = $this->input->post('BY');
        $key = array(
            'no_pr' => $nopr,
            'id_barang' => $idbrg,
            'createdby' => $by
            );
        $getitempr = $this->Detailpurchaserequestpending_model->find_by($key);

        $dataitem_po = array(
            'no_po' => $this->Purchaserequestpending_model->generate_nopo($session['kdcab']),
            'id_barang' => $getitempr->id_barang,
            'nm_barang' => $getitempr->nm_barang,
            'satuan' => $getitempr->satuan,
            'qty_order' => $getitempr->qty_order,
            'qty_supply' => $getitempr->qty_supply
            );
        $this->db->trans_start();
        $this->db->insert('trans_po_detail',$dataitem_po);
        //$this->db->where($key);
        //$this->db->update('trans_pr_detail',array('proses_po'=>1));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE){
            $result['type'] = "error";
            $result['pesan'] = "Data gagal disimpan !";
        }else{
            $result['type'] = "success";
            $result['pesan'] = "Data sukses disimpan.";
        }
        echo json_encode($result);
    }*/

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
           $result = $this->Purchaserequestpending_model->delete($nopr);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function saveheaderpo(){
        $session = $this->session->userdata('app_session');
        $nopo = $this->Purchaserequestpending_model->generate_nopo($session['kdcab']);
        $cabang = $this->Purchaserequestpending_model->cek_data(array('kdcab'=>$this->input->post('kdcab_po')),'cabang');

        $dataheaderpo = array(
            'no_po' => $nopo,
            'kdcab' => $this->input->post('kdcab_po'),
            'nm_cabang' => $this->input->post('nmcabang_po'),
            'plan_delivery_date' => $this->input->post('tglpo'),
            'real_delivery_date' => $this->input->post('tglpo_real'),
            'total_po' => $this->input->post('total'),
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
        //$counttopo = $this->Purchaserequestpending_model->cek_data(,'barang_stock');

        //print_r($_POST['nopr_topo']);
        //echo count($detail['nopr_topo']);die();

        $this->db->trans_begin();

        for($i=0;$i < count($detail['nopr_topo']);$i++){
            $key = array(
            'no_pr' => $_POST['nopr_topo'][$i],
            'id_barang' => $_POST['id_barang'][$i]
            );
            $getitempr = $this->Detailpurchaserequestpending_model->find_by($key);

            $dataitem_po = array(
                'no_po' => $this->Purchaserequestpending_model->generate_nopo($session['kdcab']),
                'no_pr' => $_POST['nopr_topo'][$i],
                'id_barang' => $getitempr->id_barang,
                'nm_barang' => $getitempr->nm_barang,
                'satuan' => $getitempr->satuan,
                'qty_po' => $getitempr->qty_pr,
                'qty_acc' => $_POST['qty_supply'][$i],
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
            //$count = $this->Purchaserequestpending_model->cek_data(array('id_barang'=>$getitempr->id_barang,'kdcab'=>$session['kdcab']),'barang_stock');
            //$this->db->where(array('id_barang'=>$getitempr->id_barang,'kdcab'=>$session['kdcab']));
            //$this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock-$_POST['qty_supply'][$i]));
            //Update STOK REAL
        }

        //Update counter NO_PO
        //$count = $this->Purchaserequestpending_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        //$this->db->where(array('kdcab'=>$session['kdcab']));
        //$this->db->update('cabang',array('no_suratjalan'=>$count->no_suratjalan+1));
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

        $po_data = $this->Purchaserequestpending_model->find_data('trans_po_header',$nopo,'no_po');
        $cabang = $this->Purchaserequestpending_model->cek_data(array('kdcab'=>$po_data->kdcab),'cabang');
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
