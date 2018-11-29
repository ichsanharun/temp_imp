<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {
/*
 * @author Yunaz
 * @copyright Copyright (c) 2016, Yunaz
 * 
 * This is controller for Penerimaan
 */
	public function __construct()
	{
		parent::__construct();

        $this->load->model('dashboard/dashboard_model');
              
        $this->template->page_icon('fa fa-dashboard');
	}

	public function index()
	{
		$this->template->title('Dashboard');
		//$data = $this->dashboard_model->monitor_eoq();
		/*
		$data = $this->dashboard_model->where('qty<=minstok')->find_all();
		$sum_keluar = $this->dashboard_model->barang_keluar();
		$sum_masuk = $this->dashboard_model->barang_masuk();
		$sum_pening = $this->dashboard_model->pengajuan_pending();
		$sum_penacc = $this->dashboard_model->pengajuan_acc();
        $this->template->set('results', $data);
        $this->template->set('sum_keluar', $sum_keluar);
        $this->template->set('sum_masuk', $sum_masuk);
        $this->template->set('sum_pening', $sum_pening);
        $this->template->set('sum_penacc', $sum_penacc);*/
		$this->template->render('list');
		
	}
}
