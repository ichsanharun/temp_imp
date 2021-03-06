<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Receiving extends Admin_Controller {
    
    //Permission
    /*
    protected $viewPermission   = "Deliveryorder.View";
    protected $addPermission    = "Deliveryorder.Add";
    protected $managePermission = "Deliveryorder.Manage";
    protected $deletePermission = "Deliveryorder.Delete";
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Receiving/Receiving_model',
                                 'Receiving/Detailreceiving_model',
                                 'Trans_stock/Trans_stock_model',
                                 'Barang_stock/Barang_stock_model',
                                 'Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Receiving');
        $this->template->page_icon('fa fa-file');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Receiving_model->order_by('no_receiving','ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Receiving');
        $this->template->render('list');
    }
    
    public function konfrimasi()
    {
        $sup    =$this->uri->segment(3);
        $no     =$this->uri->segment(4);
        $session = $this->session->userdata('app_session');
        $this->db->select('*');
        $this->db->from('trans_po_detail');
        $this->db->where('trans_po_detail.no_po', $no);
        $itembarang  =$this->db->get()->result();
        
        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");
        
        $this->template->set('supplier',$sup);
        $this->template->set('no_pr',$no);
        $this->template->set('pr_tambahan',$query_pr_tambahan);
        $this->template->set('itembarang',$itembarang);
        $this->template->title('Receiving');
        $this->template->render('konfirmasi');
    }
    
    public function konfrimasi_save()
    {
        $session    = $this->session->userdata('app_session');
        $nopo       = $this->input->post('no_pr');
        
        //Update counter NO_DO
        $count = $this->Receiving_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_receive'=>$count->no_receive+1));
        //Update counter NO_DO
        
        $norec = $this->Receiving_model->generate_noreceive($session['kdcab']);
        $dataheader = array(
            'no_receiving' => $norec,
            'tglreceive' => $this->input->post('tglreceive'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'tgl_sjsupplier' => $this->input->post('tgldosupp'),
            'no_sjsupplier' => $this->input->post('no_do_supplier'),
            'noinvoice' => $norec,
            'tglinvoice' => $this->input->post('tglreceive'),
            'container_no'   => $this->input->post('container_no'),
            'date_unloading'   => $this->input->post('date_unloading'),
            'date_check' => $this->input->post('date_check'),
            'administrator' => $this->input->post('administrator'),
            'head' => $this->input->post('head'),
            'branch' => $this->input->post('branch')
            );
        $this->db->trans_begin();
        $this->db->insert('trans_receive',$dataheader);
        $jumlah_barang = count($_POST["idet_barang"]);

        for($b=0; $b < $jumlah_barang; $b++)
        {
                $detil   = array(
                    'no_po'             => $nopo,
                    'id_barang'         => $_POST["idet_barang"][$b],
                    'nama_barang'       => $_POST["nm_barangb"][$b],
                    'qty_i'             => $_POST["qty_ib"][$b],
                    'qty_pl'            => $_POST["qty_plb"][$b],
                    'bagus'             => $_POST["qty_bagus_t"][$b],
                    'rusak'             => $_POST["qty_rusak_t"][$b],
                );
                $this->Receiving_model->insert_receive_detail_barang($detil);
           
        } 
        
        $jumlah = count($_POST["qty_bagus"]);

        for($i=0; $i < $jumlah; $i++)
        {
                $detil   = array(
                    'no_po'             => $nopo,
                    'id_barang'         => $_POST["id_barangc"][$i],
                    'id_koli'           => $_POST["id_koli"][$i],
                    'nama_koli'         => $_POST["nama_koli"][$i],
                    'qty_i'             => $_POST["qty_i"][$i],
                    'qty_pl'            => $_POST["qty_pl"][$i],
                    'bagus'             => $_POST["qty_bagus"][$i],
                    'rusak'             => $_POST["qty_rusak"][$i],
                    'keterangan'        => $_POST["keterangan"][$i],
                );
                $this->Receiving_model->insert_receive_detail_koli($detil);
           
        } 
        $this->db->query("UPDATE `trans_po_header` SET `status` = 'RECEIVING' WHERE `trans_po_header`.`no_po` ='$nopo';");
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $param = array(
                'save' => 0,
                'msg' => "GAGAL, perubahan..!!!"
                );
            }
            else
            {
                $this->db->trans_commit();
                $param = array(
                'save' => 1,
                'msg' => "SUKSES, melakukan perubahaan..!!!"
                );
            }
        
        echo json_encode($param);
    }
    //Create New Receiving
    public function create()
    {   
        if($this->uri->segment(3) == ""){
            $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all_by(array('status'=>'INVOICE'));
        }else{
            $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all_by(array('id_supplier'=>$this->uri->segment(3), 'status'=>'PO'));
        }
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');
        
        $this->template->set('supplier',$supplier);
        $this->template->set('results', $data);

        $this->template->title('Proses Receiving');
        $this->template->render('list_po');
    }

    //Proses Receiving
    public function proses(){
        $session = $this->session->userdata('app_session');
        $query = $this->db->query("SELECT * FROM `cabang` where kdcab='$session[kdcab]'");
        $row = $query->row();
        
        $getparam = explode(";",$_GET['param']);
        //$and = " proses_po !='1' ";
        $header = $this->Detailpurchaseorder_model->get_where_in('no_po',$getparam,'trans_po_header');
        $detail = $this->Detailpurchaseorder_model->get_where_in('no_po',$getparam,'trans_po_detail');
        $this->template->set('cabang', $row);
        $this->template->set('headerpo', $header);
        $this->template->set('detailpo', $detail);
        $this->template->title('Input Data Receiving');
        $this->template->render('receiving_form');
    }

    public function saveheaderreceiving(){
        $session = $this->session->userdata('app_session');
        $norec = $this->Receiving_model->generate_noreceive($session['kdcab']);
        $dataheader = array(
            'no_receiving' => $norec,
            'tglreceive' => $this->input->post('tglreceive'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'tgl_sjsupplier' => $this->input->post('tgldosupp'),
            'no_sjsupplier' => $this->input->post('no_do_supplier'),
            'noinvoice' => $norec,
            'tglinvoice' => $this->input->post('tglreceive'),
            'container_no'   => $this->input->post('container_no'),
            'date_unloading'   => $this->input->post('date_unloading'),
            'date_check' => $this->input->post('date_check'),
            'administrator' => $this->input->post('administrator'),
            'head' => $this->input->post('head'),
            'branch' => $this->input->post('branch')
            );

        $detail = array(
            'id_detail_po'=>$_POST['id_po_to_received']
            );

        $this->db->trans_begin();
        for($i=0;$i < count($detail['id_detail_po']);$i++){
            $key = array(
            'id_detail_po' => $_POST['id_po_to_received'][$i]
            );
            $getitempo = $this->Detailpurchaseorder_model->find_by($key);
            
            $detbarang = $this->Barang_stock_model->find_by(array('id_barang' => $getitempo->id_barang));
              $id_barang      = @$detbarang->id_barang;
              $nm_barang      = @$detbarang->nm_barang;
              $kategori       = @$detbarang->kategori;
              $jenis          = @$detbarang->jenis;
              $brand          = @$detbarang->brand;
              $satuan         = @$detbarang->satuan;
              $qty            = $_POST['qty_received'][$i]+$_POST['qty_broken'][$i];
              $qty_stock      = @$detbarang->qty_stock;
              $qty_avl        = @$detbarang->qty_avl;
              $nilai_barang   = $getitempo->harga_satuan;
              $qty_po         = $getitempo->qty_po;
              $tipe_adjusment = "IN";
              $date           = date('Y-m-d');
              
              $id_st = $this->Trans_stock_model->gen_st($this->auth->user_cab()).$i;
              
              $tipe           = 'IN';
              $jenis_trans    = 'IN_Pembelian';
              $qty_stock_new  = $qty_stock + $qty;
              $qty_avl_new    = $qty_avl + $qty;
              
              
              $data_adj_trans = array(
                        'id_st'=>$id_st,
                        'tipe'=>$tipe,
                        'jenis_trans'=>$jenis_trans,
                        'noreff'=>$norec,
                        'id_barang'=>$id_barang,
                        'nm_barang'=>$nm_barang,
                        'jenis'=>$jenis,
                        'kategori'=>$kategori,
                        'brand'=>$brand,
                        'satuan'=>$satuan,
                        'kdcab'=>$this->auth->user_cab(),
                        'date_stock'=>date('Y-m-d H:i:s'),
                        'qty'=>$qty,
                        'qty_stock_awal'=>$qty_stock,
                        'qty_avl_awal'=>$qty_avl,
                        'qty_stock_akhir'=>$qty_stock_new,
                        'qty_avl_akhir'=>$qty_avl_new,
                        'nilai_barang'=>$nilai_barang,
                        'notes'=>"",
                        );
              $this->Trans_stock_model->insert($data_adj_trans); //modules trans_stok

            $detail_receive = array(
                'nolpb' => $norec,
                'po_no' => $getitempo->no_po,
                'kodebarang' => $getitempo->id_barang,
                'namabarang' => $getitempo->nm_barang,
                'hargabeli' => $getitempo->harga_satuan,
                'jumlah' => $_POST['qty_received'][$i],
                'namabarang' => $getitempo->nm_barang,
                'Satuan' => $getitempo->satuan,
                'tglreceive' => $this->input->post('tglreceive'),
                'user' => $session['id_user'],
                'noinvoice' => $norec,
                'tglinvoice' => $this->input->post('tglreceive'),
                'no_sjsupplier' => $this->input->post('no_do_supplier'),
                'tgl_sjsupplier' => $this->input->post('tgldosupp'),
                'barang_rusak' => $_POST['qty_broken'][$i]
                );
            $this->db->insert('receive_detail',$detail_receive);

            //Update STOK REAL dan AVL
            $count = $this->Receiving_model->cek_data(array('id_barang'=>$getitempo->id_barang,'kdcab'=>$session['kdcab']),'barang_stock');
            $this->db->where(array('id_barang'=>$getitempo->id_barang,'kdcab'=>$session['kdcab']));
            $this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock+$_POST['qty_received'][$i],'qty_avl'=>$count->qty_avl+$_POST['qty_received'][$i],'qty_rusak'=>$count->qty_rusak+$_POST['qty_broken'][$i]));
            //Update STOK REAL
            
            if ($qty_po==$qty) {
                $detail_po = array(
                    'qty_acc'   => $qty,
                    'proses_po' => 1
                    );
            } else {
                $detail_po = array(
                    'qty_acc'   => $qty,
                    'proses_po' => "pending"
                    );
            }
            
            $this->Receiving_model->update_po_detail($getitempo->id_detail_po, $detail_po);
            //$statuz="RCV";
           // $this->Receiving_model->update_po_status($getitempo->no_po, $statuz);
              
        }

        $group = array();
        $group_pr = array();
        for($iz=0;$iz < count($detail['id_detail_po']);$iz++){
            $group_pr[$_POST['no_po'][$iz]] = $group_pr[$_POST['no_po'][$iz]] + $this->input->post('qty_po')[$iz];
            $group[$_POST['no_po'][$iz]] = $group[$_POST['no_po'][$iz]] + ($_POST['qty_received'][$iz]+$_POST['qty_broken'][$iz]);
        }
        
        foreach($group as $type=>$total){
            if ($group["$type"]==$group_pr["$type"]) {
                $this->db->where(array('no_po' => $type))
                ->update('trans_po_header',array('status'=>'RCV'));
            } else {
                $this->db->where(array('no_po' => $type))
                ->update('trans_po_header',array('status'=>"PO-PENDING"));
            }
            
            
        }

        //Update counter NO_DO
        $count = $this->Receiving_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_receive'=>$count->no_receive+1));
        //Update counter NO_DO
        $this->db->insert('trans_receive',$dataheader);
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

    function print_request($norec){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $rec_data = $this->Receiving_model->find_data('trans_receive',$norec,'no_receiving');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailreceiving_model->find_all_by(array('nolpb' => $norec));

        $this->template->set('header', $rec_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

}

?>
