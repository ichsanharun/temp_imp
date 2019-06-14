<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Piutang_card extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Piutang_card.View";
    /*
    protected $addPermission    = "Reportstok.Add";
    protected $managePermission = "Reportstok.Manage";
    protected $deletePermission = "Reportstok.Delete";
    */
    public function __construct()
    {
        parent::__construct();

        
        $this->load->model(array(
                                 'Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Cabang/Cabang_model',
                                 'Customer/Customer_model',
								 'Piutang_card/Piutang_card_model',
                                 'Salesorder/Salesorder_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Kartu Piutang');
        $this->template->page_icon('fa fa-table');
    }
	
	
	function index(){
		$session 	= $this->session->userdata('app_session');
        $cab_user	= $session['kdcab'];
		if($this->input->post()){
			$Customer_Pilih	= $this->input->post('pelanggan');
			$kdcab			= $this->input->post('kdcab');
			if(strtolower($Customer_Pilih)=='all')$Customer_Pilih='';
		}else{
			
			$Customer_Pilih		= '';
			$kdcab 			= $session['kdcab'];
		}
		$WHERE		= "(flag_cancel IS NULL OR flag_cancel='' OR flag_cancel='N')";
		if($kdcab){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="kdcab='".$kdcab."'";
		}
		
		
		
		if($Customer_Pilih){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="id_customer='".$Customer_Pilih."'";
		}
		$Query_Data	= "SELECT * FROM trans_invoice_header WHERE  ".$WHERE." ORDER BY nm_customer,tanggal_invoice ASC";
		$data		= $this->db->query($Query_Data)->result();		
		$cabang 	= $this->Piutang_card_model->get_data_Cabang();
		$customer 	= $this->get_card_customer($kdcab);
        $marketing 	= $this->Salesorder_model->pilih_marketing($kdcab)->result();
		
        $this->template->title('Kartu Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('customer', $customer);
        $this->template->set('marketing', $marketing);
        $this->template->set('results', $data);
		$this->template->set('rows_cab_user', $cab_user);
		$this->template->set('cab_pilih', $kdcab);
		$this->template->set('cust_pilih', $Customer_Pilih);
        $this->template->render('list');
	}
	
	function display_jurnal($Kode_Jurnal,$Tipe_Jurnal){
		if(strtolower($Tipe_Jurnal)=='bum'){
			$Table	= 'jarh';
		}else{
			$Table	= 'javh';
		}
		$row_header	= $this->db->get_where($Table,array('nomor'=>$Kode_Jurnal))->result();
		$row_detail	= $this->db->get_where('jurnal',array('nomor'=>$Kode_Jurnal,'tipe'=>$Tipe_Jurnal))->result();
		
		$this->template->set('rows_header', $row_header);
		$this->template->set('rows_detail', $row_detail);
		$this->template->set('type_jurnal', $Tipe_Jurnal);
		$this->template->render('preview_jurnal');
		
		
	}
	
	function print_kartu($Cabang,$Customer){
		if(strtolower($Customer)=='all')$Customer='';
		$WHERE		= "(flag_cancel IS NULL OR flag_cancel='' OR flag_cancel='N')";
		if($Cabang){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="kdcab='".$Cabang."'";
		}
		if($Customer){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="id_customer='".$Customer."'";
		}
		$Query_Data	= "SELECT * FROM trans_invoice_header WHERE  ".$WHERE." ORDER BY nm_customer,tanggal_invoice ASC";
		$data		= $this->db->query($Query_Data)->result();
		
		
		$this->template->set('rows_header', $data);
		$this->template->render('print_card');
		
		
	}
	
   function get_card_customer($kdcab='',$flag_json='N'){
	   $customer 		= $this->Customer_model->find_all_by(array('deleted'=>0,'kdcab'=>$kdcab));
	   $Arr_Customer	= array();
	   if($customer){
		   foreach($customer as $key=>$vals){
			   $Kode_Cust					= $vals->id_customer;
			   $Name_Cust					= $Kode_Cust.' '.$vals->nm_customer;
			   $Arr_Customer[$Kode_Cust]	= $Name_Cust;
		   }
	   }
	   if($flag_json=='Y'){
		   echo json_encode($Arr_Customer);
	   }else{
		   return $Arr_Customer;
	   }
   }
}
?>
