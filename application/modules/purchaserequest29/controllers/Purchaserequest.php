<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Purchaserequest extends Admin_Controller {

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
        $this->template->title('Purchase Request');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $data = $this->Purchaserequest_model
        ->select("*,trans_pr_header.no_pr AS nopr")
        ->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')
        ->order_by('trans_pr_header.no_pr','ASC')->find_all();
        //$data = $this->db->query("SELECT * FROM trans_pr_header LEFT JOIN barang_master ON trans_pr_header.id_barang = barang_master.id_barang order by no_pr asc")->result();
        $this->template->set('results', $data);
        $this->template->title('Purchase Request');
        $this->template->render('list');
    }

    //Create New PO
    public function create()
    {
    	 $session = $this->session->userdata('app_session');
       if($this->uri->segment(3) == ""){

           $itembarang  = $this->Purchaserequest_model->pilih_item($session['kdcab'])->result();
       }else{
           $itembarang  = $this->Purchaserequest_model
           ->pilih_item_sup($session['kdcab'],$this->uri->segment(3))->result();
       }

        //$supplier = $this->Purchaserequest_model->get_data('1=1','supplier');
        $supplier = $this->Purchaserequest_model->get_data('1=1','supplier');
        $cabang = $this->Purchaserequest_model->find_all_by(array('kdcab'=>$session['kdcab']));
        $prtemp = $this->Purchaserequesttmp_model
        ->join('barang_master','trans_pr_detail_tmp.id_barang = barang_master.id_barang','left')
        ->join('supplier','supplier.id_supplier = barang_master.id_supplier','inner')
        ->find_all_by(array('trans_pr_detail_tmp.created_by'=>$session['id_user']));
        $this->template->set('itembarang',$itembarang);
        $this->template->set('detailprtmp',$prtemp);
        $this->template->set('supplier',$supplier);
        $this->template->set('cabang',$cabang);
        $this->template->title('Input Purchase Request');
        $this->template->render('pr_form');
    }

    function get_supplier(){
        $idsup = $_GET['idsup'];
        $supplier = $this->Purchaserequest_model->get_supplier($idsup)->row();

        echo json_encode($supplier);
    }

    function savedetailpr(){
    	$session = $this->session->userdata('app_session');
    	$nopr = $this->Purchaserequest_model->generate_nopr($session['kdcab']);
    	$dataitempr = array(
    		'no_pr' => $nopr,
    		'id_barang' => $this->input->post('item_brg_pr'),
    		'nm_barang' => $this->input->post('nmbarang'),
    		'satuan' => $this->input->post('satuan'),
    		//'harga_satuan' => $this->input->post('harga'),
    		'qty_pr' => $this->input->post('qty_pr'),
    		//'sub_total_pr' => $this->input->post('qty_pr')*$this->input->post('harga'),
    		'created_on' => date('Y-m-d H:i:s'),
    		'created_by' => $session['id_user']
    		);

    	$key = array(
    		'no_pr' => $nopr,
    		'id_barang' => $this->input->post('item_brg_pr'),
    		'created_by' => $session['id_user']
    		);
    	$cekdata = $this->Purchaserequest_model->cek_data($key,'trans_pr_detail_tmp');

    	if(!$cekdata){
    		$this->db->trans_begin();
    		$this->db->insert('trans_pr_detail_tmp',$dataitempr);
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

    function hapus_item_pr(){
        $id = $this->input->post('ID');
        $key = array('id_detail_pr'=>$id);
        if(!empty($id)){
           $result = $this->Purchaserequesttmp_model->delete_where($key);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function saveheaderpr(){
    	$session = $this->session->userdata('app_session');
    	$nopr = $this->Purchaserequest_model->generate_nopr($session['kdcab']);
    	$headerpr = array(
    		'no_pr' => $nopr,
    		'tgl_pr' => $this->input->post('tglpr'),
        'kdcab' => $session['kdcab'],
    		'plan_delivery_date' => $this->input->post('plandeliverypr'),
    		//'real_delivery_date' => $this->input->post('realdeliverypr'),
    		'id_supplier' => $this->input->post('idsupplier'),
    		'nm_supplier' => $this->input->post('nmsupplier'),
    		'total_cbm' => $this->input->post('cbm_tot'),
    		'created_on' => date('Y-m-d H:i:s'),
    		'created_by' => $session['id_user']
		);
		$this->db->trans_begin();
		$key = array('no_pr'=>$nopr,'created_by' => $session['id_user']);
		$data_tmp = $this->Purchaserequesttmp_model->find_all_by($key);
		if($data_tmp){
	        foreach($data_tmp as $key=>$val){
	        	$detailpr = array(
	        		'no_pr' => $val->no_pr,
	        		'id_barang' => $val->id_barang,
	        		'nm_barang' => $val->nm_barang,
	        		'satuan' => $val->satuan,
	        		'qty_pr' => $val->qty_pr,
	        		'harga_satuan' => $val->harga_satuan,
	        		'sub_total_pr' => $val->sub_total_pr,
	        		'created_on' => $val->created_on,
	        		'created_by' => $val->created_by
	        		);
	        	$this->db->insert('trans_pr_detail',$detailpr);
	        }
	        $this->db->insert('trans_pr_header',$headerpr);
		    //Update counter NO_pr
		    $counter = $this->Purchaserequest_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
		    $this->db->where(array('kdcab'=>$session['kdcab']));
		    $this->db->update('cabang',array('no_pr'=>$counter->no_pr+1));
		   	//Update counter NO_pr
		   	$this->db->truncate('trans_pr_detail_tmp');
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

    function print_request($nopr){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        //$pr_data = $this->Purchaserequest_model->find_data('trans_pr_header',$nopr,'no_pr');
        $pr_data = $this->Purchaserequest_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->find_data('trans_pr_header',$nopr,'no_pr');
        //$pr_data = $this->Purchaserequest_model->query("SELECT * FROM trans_pr_header LEFT JOIN cabang ON trans_pr_header.kdcab = cabang.kdcab WHERE trans_pr_header.no_pr = '$nopr'")->result();
        //$detail = $this->Detailpurchaserequest_model->find_all_by(array('no_pr' => $nopr));
        $detail = $this->db->query("SELECT * FROM trans_pr_detail INNER JOIN barang_master ON trans_pr_detail.id_barang = barang_master.id_barang WHERE no_pr = '$nopr'")->result();
        $this->template->set('pr_data', $pr_data);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

}

?>
