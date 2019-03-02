<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Pickinglistwaitingdo extends Admin_Controller {
    
    //Permission
    protected $viewPermission   = "Salesorder.View";
    protected $addPermission    = "Salesorder.Add";
    protected $managePermission = "Salesorder.Manage";
    protected $deletePermission = "Salesorder.Delete";
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Salesorder/Salesorder_model',
                                 'Salesorder/Detailsalesorder_model',
                                 'Salesorder/Detailsalesordertmp_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Picking List Waiting DO');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = $this->Salesorder_model->order_by('no_so','DESC')->find_all_by(array('total !='=>0,'stsorder'=>'OPN'));

        $this->template->set('results', $data);
        $this->template->title('Picking List Waiting DO');
        $this->template->render('list');
    }

    public function getitemsotemp(){
        $this->template->render('getitemsotemp');
    }


    function print_picking_list($noso){
        $no_so = $noso;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so,'proses_do !='=>1));

        $this->template->set('so_data', $so_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_picking_list',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

}

?>
