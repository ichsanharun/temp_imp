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
    	$session = $this->session->userdata('app_session');
        $itembarang  = $this->Purchaseorder_model->pilih_item($session['kdcab'])->result();
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');

        $this->template->set('itembarang',$itembarang);
        $this->template->set('supplier',$supplier);
        $this->template->title('Input Purchase Order');
        $this->template->render('po_form');
    }

    function get_supplier(){
        $idsup = $_GET['idsup'];
        $supplier = $this->Purchaseorder_model->get_supplier($idsup)->row();

        echo json_encode($supplier);
    }

}

?>
