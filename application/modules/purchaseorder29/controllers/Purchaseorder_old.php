<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
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
                    						 'Purchaseorder/Purchaseordertmp_model',
                                 'Purchaserequest/Purchaserequest_model',
                                 'Purchaserequest/Detailpurchaserequest_model',
                                 'Purchaserequest/Purchaserequesttmp_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Purchase Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Purchase Order');
        $this->template->render('list');
    }

    //Create New PO
    public function create()
    {

      $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
      $this->template->set('cabang',$cabang);

      if($this->uri->segment(3) == ""){

          //$data = $this->Salesorder_model->get_salesorder_open();
          $data = $this->Purchaserequest_model->order_by('no_pr','ASC')->find_all();
      }else{
          $data = $this->Purchaserequest_model->get_data("kdcab ='".$this->uri->segment(3)."' ","trans_pr_header");
      }
      $this->template->set('results', $data);


    	//$session = $this->session->userdata('app_session');
      /*  $itembarang  = $this->Purchaseorder_model->pilih_item($this->uri->segment(3))->result();
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');
        $potemp = $this->Purchaseordertmp_model->find_all_by(array('created_by'=>$session['id_user']));

        $this->template->set('itembarang',$itembarang);
        $this->template->set('detailpotmp',$potemp);
        $this->template->set('supplier',$supplier);*/
        $this->template->title('Input Purchase Order');
        $this->template->render('po_form');
    }
    public function proses()
    {
        $getparam = explode(";",$_GET['param']);
        $getpr = $this->Detailpurchaserequest_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->get_where_in('trans_pr_header.no_pr',$getparam,'trans_pr_header');

        $and = " proses_po IS NULL ";
        $getitempr = $this->Detailpurchaserequest_model->join('barang_master','trans_pr_detail.id_barang = barang_master.id_barang','left')->get_where_in_and('trans_pr_detail.no_pr',$getparam,$and,'trans_pr_detail');
        //$driver = $this->Purchaseorder_model->pilih_driver()->result();
        //$kendaraan = $this->Purchaseorder_model->pilih_kendaraan()->result();
        $this->template->set('param',$getparam);
        $this->template->set('headerpr',$getpr);
        $this->template->set('getitempr',$getitempr);
        //$this->template->set('driver',$driver);
        //$this->template->set('kendaraan',$kendaraan);
        $this->template->title('Confirm Purchase Order');
        $this->template->render('purchaseorder_form');
    }
    /*
    function get_supplier(){
        $idsup = $_GET['idsup'];
        $supplier = $this->Purchaseorder_model->get_supplier($idsup)->row();

        echo json_encode($supplier);
    }

    function savedetailpo(){
    	$session = $this->session->userdata('app_session');
    	$nopo = $this->Purchaseorder_model->generate_nopo($session['kdcab']);
    	$dataitempo = array(
    		'no_po' => $nopo,
    		'id_barang' => $this->input->post('item_brg_po'),
    		'nm_barang' => $this->input->post('nmbarang'),
    		'satuan' => $this->input->post('satuan'),
    		'harga_satuan' => $this->input->post('harga'),
    		'qty_po' => $this->input->post('qty_po'),
    		'sub_total_po' => $this->input->post('qty_po')*$this->input->post('harga'),
    		'created_on' => date('Y-m-d H:i:s'),
    		'created_by' => $session['id_user']
    		);

    	$key = array(
    		'no_po' => $nopo,
    		'id_barang' => $this->input->post('item_brg_po'),
    		'created_by' => $session['id_user']
    		);
    	$cekdata = $this->Purchaseorder_model->cek_data($key,'trans_po_detail_tmp');

    	if(!$cekdata){
    		$this->db->trans_begin();
    		$this->db->insert('trans_po_detail_tmp',$dataitempo);
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
	    }else{
	    	$param = array(
	            'save' => 0,
	            'msg' => "GAGAL, Item sudah terdaftar..!!!"
	            );
	    }
        echo json_encode($param);
    }

    function hapus_item_po(){
        $id = $this->input->post('ID');
        $key = array('id_detail_po'=>$id);
        if(!empty($id)){
           $result = $this->Purchaseordertmp_model->delete_where($key);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }
    */
    function saveheaderpo(){
        $session = $this->session->userdata('app_session');
        $nopo = $this->Purchaseorder_model->generate_nopo($session['kdcab']);
        //$supir = $this->Purchaseorder_model->cek_data(array('id_karyawan'=>$this->input->post('supir_po')),'karyawan');
        $cabang = $this->Purchaseorder_model->cek_data(array('kdcab'=>$this->input->post('kdcab_po')),'cabang');

        $dataheaderpo = array(
            'no_po' => $nopo,
            'kdcab' => $this->input->post('kdcab_po'),
            //'nm_customer' => $this->input->post('nmcustomer_po'),
            //'alamat_customer' => $customer->alamat,
            'id_supplier' => $this->input->post('id_supplier'),
            'nm_supplier' => $this->input->post('nm_supplier'),
            'tgl_po' => date('Y-m-d'),
            'plan_delivery_date' => $this->input->post('tglpo_plan'),
            'real_delivery_date' => $this->input->post('tglpo_real'),
            //'nm_supir' => $supir->nama_karyawan,
            //'id_kendaraan' => $this->input->post('kendaraan_po'),
            'created_on' => date('Y-m-d H:i:s'),
        		'created_by' => $session['id_user']
            //'status' => $this->input->post('status_po')
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
                'qty_order' => $getitempr->qty_pr,
                'qty_supply' => $_POST['qty_supply'][$i]
            );
            $this->db->insert('trans_po_detail',$dataitem_po);

            $keyclose_pr = array(
                'no_pr' => $_POST['nopr_topo'][$i],
                'id_barang' => $getitempr->id_barang
                );
            if($this->input->post('status_po') == "PO"){ // ini berarti proses PO dari PR biasa
                $newqty = $this->input->post('qty_confirm')[$i]-$this->input->post('qty_supply')[$i];
                if($this->input->post('qty_supply')[$i] == $this->input->post('qty_confirm')[$i]){
                    //berarti PO CLOSE
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail',array('proses_po'=>1,'qty_supply'=>$newqty));//Detail SO sudah semua
                }else{
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail',array('qty_supply'=>$newqty));//Jika masih ada sisa qty
                }
            }else{
                 $newqtypending = $this->input->post('qty_confirm')[$i]-$this->input->post('qty_supply')[$i];
               if($this->input->post('qty_supply')[$i] == $this->input->post('qty_confirm')[$i]){
                    //berarti SO CLOSE
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail_pending',array('proses_po'=>1,'qty_confirm'=>$newqty));//Detail SO sudah semua
                }else{
                    $this->db->where($keyclose_pr);
                    $this->db->update('trans_pr_detail_pending',array('qty_confirm'=>$newqty));//Jika masih ada sisa qty
                }
            }
            //Update STOK REAL
            //$count = $this->Purchaseorder_model->cek_data(array('id_barang'=>$getitempr->id_barang,'kdcab'=>$session['kdcab']),'barang_stock');
            //$this->db->where(array('id_barang'=>$getitempr->id_barang,'kdcab'=>$session['kdcab']));
            //$this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock-$_POST['qty_supply'][$i]));
            //Update STOK REAL
        }

        //Update counter NO_DO
        $count = $this->Purchaseorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        //$this->db->update('cabang',array('no_suratjalan'=>$count->no_suratjalan+1));

        //Update counter NO_DO
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

    /*function saveheaderpo(){
    	$session = $this->session->userdata('app_session');
    	$nopo = $this->Purchaseorder_model->generate_nopo($session['kdcab']);
    	$headerpo = array(
    		'no_po' => $nopo,
    		'tgl_po' => $this->input->post('tglpo'),
    		'plan_delivery_date' => $this->input->post('plandeliverypo'),
    		//'real_delivery_date' => $this->input->post('realdeliverypo'),
    		'id_supplier' => $this->input->post('idsupplier'),
    		'nm_supplier' => $this->input->post('nmsupplier'),
    		'total_po' => $this->input->post('totalpo'),
    		'created_on' => date('Y-m-d H:i:s'),
    		'created_by' => $session['id_user']
		);
		$this->db->trans_begin();
		$key = array('no_po'=>$nopo,'created_by' => $session['id_user']);
		$data_tmp = $this->Purchaseordertmp_model->find_all_by($key);
		if($data_tmp && !empty($this->input->post('idsupplier'))){
	        foreach($data_tmp as $key=>$val){
	        	$detailpo = array(
	        		'no_po' => $val->no_po,
	        		'id_barang' => $val->id_barang,
	        		'nm_barang' => $val->nm_barang,
	        		'satuan' => $val->satuan,
	        		'qty_po' => $val->qty_po,
	        		'harga_satuan' => $val->harga_satuan,
	        		'sub_total_po' => $val->sub_total_po,
	        		'created_on' => $val->created_on,
	        		'created_by' => $val->created_by
	        		);
	        	$this->db->insert('trans_po_detail',$detailpo);
	        }
	        $this->db->insert('trans_po_header',$headerpo);
		    //Update counter NO_PO
		    $counter = $this->Purchaseorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
		    $this->db->where(array('kdcab'=>$session['kdcab']));
		    $this->db->update('cabang',array('no_po'=>$counter->no_po+1));
		   	//Update counter NO_PO
		   	$this->db->truncate('trans_po_detail_tmp');
	    	if ($this->db->trans_status() === FALSE)
	        {
	            $this->db->trans_rollback();
	            $param = array(
	            'save' => 0,
	            'msg' => "GAGAL, tambah item barang..!!!"
	            );
	        }
	        else
	        {
	            $this->db->trans_commit();
	            $param = array(
	            'save' => 1,
	            'msg' => "SUKSES, tambah item barang..!!!"
	            );
	        }
    	}else{
    		$param = array(
	            'save' => 0,
	            'msg' => "PERINGATAN, belum ada data..!!!"
	            );
    	}
        echo json_encode($param);
    }


    function print_request($nopo){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $po_data = $this->Purchaseorder_model->find_data('trans_po_header',$nopo,'no_po');
        $detail = $this->Detailpurchaseorder_model->find_all_by(array('no_po' => $nopo));

        $this->template->set('po_data', $po_data);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
    */
}

?>
