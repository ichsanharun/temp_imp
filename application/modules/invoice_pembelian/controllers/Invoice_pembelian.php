<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_pembelian extends Admin_Controller {

	protected $viewPermission   = "Cbm.View";
    protected $addPermission    = "Cbm.Add";
    protected $managePermission = "Cbm.Manage";
    protected $deletePermission = "Cbm.Delete";
	public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        
        $this->load->model('Model_invoice_pembelian', 'ip');
        
        $this->template->title('Manage Data PI');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
        
    }

	public function index()
	{
        $pi= $this->ip->all();
       
        $this->template->set('results', $pi);
        $this->template->title('Data Packing List');
        $this->template->render('index');
    }

    function print_invoice($nopo){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->SetDisplayMode(190);
        $mpdf->RestartDocTemplate();

        $inv = $this->db->query("SELECT * FROM `trans_po_invoice` WHERE no_po='$nopo'");
        $detail = $this->db->query("SELECT * FROM `trans_po_detail` WHERE no_po='$nopo'");
        $po = $this->db->query("SELECT * FROM `trans_po_header` WHERE no_po='$nopo'");
        
        $this->template->set('inv', $inv->row());
        $this->template->set('detail', $detail);
        $this->template->set('po', $po->row());
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

}
