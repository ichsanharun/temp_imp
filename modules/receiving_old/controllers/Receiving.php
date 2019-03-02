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
        $this->load->model(array('Purchaseorder/Purchaseorder_model',
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

        //$data = $this->Invoice_model->order_by('no_invoice','ASC')->find_all();
        //$this->template->set('results', $data);
        $this->template->title('Receiving');
        $this->template->render('list');
    }

    //Create New Receiving
    public function create()
    {   
        if($this->uri->segment(3) == ""){
            $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all();
        }else{
            $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all_by(array('id_supplier'=>$this->uri->segment(3)));
        }
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');
        
        $this->template->set('supplier',$supplier);
        $this->template->set('results', $data);

        $this->template->title('Input Receiving');
        $this->template->render('list_po');
    }

    function print_request($noinv){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('header', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

}

?>
