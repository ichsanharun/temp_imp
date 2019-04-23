<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Reportdo
 */

class Reportdo extends Admin_Controller {

  //Permission

  protected $viewPermission   = "Reportdo.View";
  protected $addPermission    = "Reportdo.Add";
  protected $managePermission = "Reportdo.Manage";
  protected $deletePermission = "Reportdo.Delete";

  public function __construct()
  {
      parent::__construct();
      $this->load->library(array('Mpdf','upload','Image_lib'));

      $this->load->model(array('Deliveryorder_2/Deliveryorder_model',
                               'Deliveryorder_2/Detaildeliveryorder_model',
                               'Salesorder/Salesorder_model',
                               'Salesorder/Detailsalesorder_model',
                               'Pendingso/Pendingso_model',
                               'Pendingso/Detailpendingso_model',
                               'Customer/Customer_model',
                               'Aktifitas/aktifitas_model'
                              ));

      $this->template->title('Delivery Order');
      $this->template->page_icon('fa fa-table');

      date_default_timezone_set("Asia/Bangkok");
  }

  public function index()
  {
      //$this->auth->restrict($this->viewPermission);
      $session = $this->session->userdata('app_session');
      $kdcab = $session['kdcab'];
      $data = $this->Deliveryorder_model->where(array('LEFT(trans_do_header.no_do,3)'=>$kdcab))->order_by('no_do','DESC')->find_all();
      if ($kdcab == "100") {
        $data = $this->Deliveryorder_model->order_by('no_do','DESC')->find_all();
      }
      $this->template->set('results', $data);
      $this->template->title('Delivery Order');
      $this->template->render('list');
  }

  function downloadExcel_old()
  {
    $session = $this->session->userdata('app_session');
    if ($this->uri->segment(3) == "All") {
      $data_so = $this->Deliveryorder_model
      ->join("trans_do_detail", "trans_do_detail.no_do = trans_do_header.no_do", "left")
      ->join("barang_jenis", "LEFT(trans_do_detail.id_barang,2) = barang_jenis.id_jenis", "left")
      ->join("barang_group", "MID(trans_do_detail.id_barang,3,2) = barang_group.id_group", "left")
      ->get_data("LEFT(trans_do_header.no_do,3) = '".$session['kdcab']."'","trans_do_header");
      }else {

        $data_so = $this->Deliveryorder_model
        ->join("trans_do_detail", "trans_do_detail.no_do = trans_do_header.no_do", "left")
        ->join("barang_jenis", "LEFT(trans_do_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_do_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->get_data("LEFT(trans_do_header.no_do,3) = '".$session['kdcab']."' AND trans_do_header.tgl_do like '%".$this->uri->segment(3)."%'","trans_do_header");
      }



    $data = array(
      'title2'		=> 'Report',
      'results'	=> $data_so
    );
    /*$this->template->set('results', $data_so);
    $this->template->set('head', $sts);
    $this->template->title('Report SO');*/
    $this->load->view('view_report',$data);


  }

  //Create New Delivery Order
  public function filter()
  {
    $session = $this->session->userdata('app_session');
      $kdcab = $session['kdcab'];
    $data = $this->Deliveryorder_model
    ->where("tgl_do between '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND LEFT(trans_do_header.no_do,3)='".$kdcab."'")
    ->order_by('no_do','DESC')->find_all();

    if ($kdcab == "100") {
      $data = $this->Deliveryorder_model->where("tgl_do between '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."'")->order_by('no_do','DESC')->find_all();
    }
    $this->template->set('results', $data);
    $this->template->title('Delivery Order');
    $this->template->render('list');
  }



  //Get detail Customer
  function get_customer(){
      $idcus = $_GET['idcus'];
      $customer = $this->Salesorder_model->get_customer($idcus)->row();

      echo json_encode($customer);
  }

  //Get detail Sales
  function get_salesman(){
      $idsales = $_GET['idsales'];
      $salesman = $this->Salesorder_model->get_marketing($idsales)->row();

      echo json_encode($salesman);
  }


}

?>
