<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Mohammad Ichsan
 * @copyright Copyright (c) 2018, Mohammad Ichsan
 *
 * This is model class for table "Diskon Master"
 */

class Diskon_master extends Admin_Controller {
  // protected $viewPermission   = "Diskonmaster.View";
  // protected $addPermission    = "Diskonmaster.Add";
  // protected $managePermission = "Diskonmaster.Manage";
  // protected $deletePermission = "Diskonmaster.Delete";

  public function __construct(){
      parent::__construct();
      $this->load->library(array('Mpdf','upload','Image_lib'));
      $this->load->model(array('Barang_stock/Barang_stock_model',
                               'Diskon/Diskon_model',
                               'Customer/Customer_model',
                               'Aktifitas/aktifitas_model'
                              ));
      $this->template->title('Diskon Master');
      $this->template->page_icon('fa fa-table');

      date_default_timezone_set("Asia/Bangkok");
  }

  public function index(){
      $this->auth->restrict($this->viewPermission);
      $session = $this->session->userdata('app_session');
      $data_barang = $this->Barang_stock_model->order_by('nm_barang','ASC')->join("barang_master","barang_stock.id_barang = barang_master.id_barang","left")->find_all_by(array('deleted'=>0,'kdcab'=>$session['kdcab'],'sts_aktif'=>'aktif'));
      $data_customer = $this->Customer_model->order_by('nm_customer','ASC')->find_all_by(array('deleted'=>0,'kdcab'=>$session['kdcab'],'sts_aktif'=>'aktif'));
      $data_diskon = $this->Diskon_model->order_by('diskon','ASC')->find_all_by(array('kdcab'=>$session['kdcab'],'sts_aktif'=>'aktif'));
      //$disc_cash = $this->Salesorder_model->get_data(array('diskon'=>'CASH'),'diskon');
      //$this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
      $this->template->set('data_barang', $data_barang);
      $this->template->set('data_customer', $data_customer);
      $this->template->set('data_diskon', $data_diskon);
      $this->template->title('Diskon Master');
      $this->template->render('list');
  }

}
