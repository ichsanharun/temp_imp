<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class laporan extends Admin_Controller {

	protected $viewPermission   = "Cbm.View";
    protected $addPermission    = "Cbm.Add";
    protected $managePermission = "Cbm.Manage";
    protected $deletePermission = "Cbm.Delete";
	public function __construct()
    {
        parent::__construct();
        //$this->load->model('model_cbm', 'cbm');
        
        $this->template->title('Manage Data Laporan');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
        
    }

	public function laporan_laba_kotor()
	{
        //$cbm= $this->cbm->all();
        
        //$this->template->set('results', $cbm);
        $this->template->title('Laporan Laba Kotor');
        $this->template->render('laporan_laba_kotor');
    }

    

    
	

}
