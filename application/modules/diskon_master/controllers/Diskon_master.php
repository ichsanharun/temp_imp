<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Mohammad Ichsan
 * @copyright Copyright (c) 2018, Mohammad Ichsan
 *
 * This is model class for table "Diskon Master"
 */

class Diskon_master extends Admin_Controller {
  protected $viewPermission   = "Diskonmaster.View";
  protected $addPermission    = "Diskonmaster.Add";
  protected $managePermission = "Diskonmaster.Manage";
  protected $deletePermission = "Diskonmaster.Delete";

  public function __construct(){
      parent::__construct();
      $this->load->library(array('Mpdf','upload','Image_lib'));
      $this->load->model(array('Barang_stock/Barang_stock_model',
                               'Diskon/Diskon_model',
                               'Customer/Customer_model',
                               'Bidus/Bidus_model',
                               'Aktifitas/aktifitas_model'
                              ));
      $this->template->title('Diskon Master');
      $this->template->page_icon('fa fa-table');

      date_default_timezone_set("Asia/Bangkok");
  }

  public function index(){
      $this->auth->restrict($this->viewPermission);
      $session = $this->session->userdata('app_session');
      $data_barang = $this->Barang_stock_model->order_by('barang_stock.nm_barang','ASC')->find_all_by(array('barang_stock.deleted'=>0,'kdcab'=>$session['kdcab'],'barang_stock.sts_aktif'=>'aktif'));
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

  public function edit(){
      $this->auth->restrict($this->managePermission);
      $id = $this->input->post('ID');
      $data = $this->input->post('data');
      if ($data == "barang") {
        $data_dis = $this->Barang_stock_model->find_by(array('id_barang'=>$id,'kdcab'=>$this->auth->user_cab()));
        // code...
      }elseif ($data == "customer") {
        $data_dis = $this->Customer_model->find_by(array('id_customer'=>$id,'kdcab'=>$this->auth->user_cab()));
        $datbidus   = $this->Bidus_model->pilih_bidus()->result();
        $this->template->set('bidus', $datbidus);
      }else {
        $data_dis = $this->Diskon_model->find_by(array('id_diskon'=>$id,'kdcab'=>$this->auth->user_cab()));
      }
      $this->template->set('tipe', $data);
      $this->template->set('data', $data_dis);
      $this->template->title('Edit');
      $this->template->render('edit');
  }

  public function savediskon(){
    $tipe_data = $this->input->post('tipe');
    $id = $this->input->post('id');
    $session = $this->session->userdata('app_session');
    if ($tipe_data == "barang_stock") {
      $datakey = array(
        'id_barang' =>  $id,
        'kdcab'     =>  $session['kdcab']
      );
      $datainsert = array(
        'diskon_standar_persen' => $this->input->post('diskon_standar_persen'),
        'diskon_promo_persen' => $this->input->post('diskon_promo_persen')
      );
    }elseif ($tipe_data == "customer") {
      $datakey = array(
        'id_customer' => $id
      );
      $datainsert = array(
        'bidang_usaha' => $this->input->post('bidang_usaha'),
        'diskon_toko' => $this->input->post('diskon_toko')
      );
    }else {
      $datakey = array(
        'id_diskon' => $id
      );
      $datainsert = array(
        'diskon' => $this->input->post('diskon'),
        'persen' => $this->input->post('persen')
      );
    }
      if($this->input->post('id')){
        $this->db->trans_begin();
        $this->db->where($datakey);
        $this->db->update($tipe_data,$datainsert);
      }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
                'save' => 0,
                'msg' => "GAGAL, tambah diskon..!!!"
                );
        }
        else
        {
           $this->db->trans_commit();
             $param = array(
              'save' => 1,
              'msg' => "SUKSES, simpan data diskon..!!!"
              );
        }


      echo json_encode($param);
  }

}
