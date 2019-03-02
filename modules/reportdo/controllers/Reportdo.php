<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Reportdo
 */

class Reportdo extends Admin_Controller {

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

      $data = $this->Deliveryorder_model->order_by('no_do','DESC')->find_all();
      $this->template->set('results', $data);
      $this->template->title('Delivery Order');
      $this->template->render('list');
  }

  //Create New Delivery Order
  public function filter()
  {
    $data = $this->Deliveryorder_model
    ->where("tgl_do between '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."'")
    ->order_by('no_do','DESC')->find_all();
    $this->template->set('results', $data);
    $this->template->title('Delivery Order');
    $this->template->render('list');
  }

  public function createpending()
  {
      $customer = $this->Customer_model->find_all_by(array('deleted'=>0));
      $this->template->set('customer',$customer);
      if($this->uri->segment(3) == ""){
          $data = $this->Pendingso_model->order_by('no_so','ASC')->find_all();
      }else{
          $data = $this->Pendingso_model->order_by('no_so','ASC')->find_all_by(array('id_customer'=>$this->uri->segment(3)));
      }
      $this->template->set('results', $data);
      $this->template->title('Input Delivery Order From Pending');
      $this->template->render('list_so_pending');
  }

  //Create New Delivery Order
  public function proses()
  {
      $getparam = explode(";",$_GET['param']);
      $getso = $this->Detailsalesorder_model->get_where_in('no_so',$getparam,'trans_so_header');

      $and = " proses_do IS NULL ";
      $getitemso = $this->Detailsalesorder_model->get_where_in_and('no_so',$getparam,$and,'trans_so_detail');
      $driver = $this->Deliveryorder_model->pilih_driver()->result();
      $kendaraan = $this->Deliveryorder_model->pilih_kendaraan()->result();
      $this->template->set('param',$getparam);
      $this->template->set('headerso',$getso);
      $this->template->set('getitemso',$getitemso);
      $this->template->set('driver',$driver);
      $this->template->set('kendaraan',$kendaraan);
      $this->template->title('Input Delivery Order');
      $this->template->render('deliveryorder_form');
  }

  //Create New Delivery Order
  public function prosesdopending()
  {
      $getparam = explode(";",$_GET['param']);
      $getso = $this->Detailpendingso_model->get_where_in('no_so_pending',$getparam,'trans_so_pending_header');
      $getitemso = $this->Detailpendingso_model->get_where_in('no_so_pending',$getparam,'trans_so_pending_detail');
      $driver = $this->Deliveryorder_model->pilih_driver()->result();
      $kendaraan = $this->Deliveryorder_model->pilih_kendaraan()->result();
      $this->template->set('param',$getparam);
      $this->template->set('headerso',$getso);
      $this->template->set('getitemso',$getitemso);
      $this->template->set('driver',$driver);
      $this->template->set('kendaraan',$kendaraan);
      $this->template->title('Input Delivery Order');
      $this->template->render('deliveryorder_form_pending');
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

  public function get_itemsobycus(){
      $idcustomer = $this->input->post('idcus');
      $getso = $this->Salesorder_model->find_all_by(array('id_customer'=>$idcustomer,'stsorder'=>0));
      //$getitemso = $this->Detailsalesorder_model->find_all_by(array('no_so'=>$getso->no_so));
      $data['so'] = $getso;
      $data['customer'] = $this->Customer_model->find_by(array('id_customer'=>$idcustomer));;
      //$data['itemso'] = $getitemso;
      $this->load->view('ajax/get_itemsobycus',$data);
  }

  public function set_itemdo(){
      $session = $this->session->userdata('app_session');
      $noso = $this->input->post('NOSO');
      $idbrg = $this->input->post('IDBRG');
      $cus = $this->input->post('CUS');
      $by = $this->input->post('BY');
      $key = array(
          'no_so' => $noso,
          'id_barang' => $idbrg,
          'createdby' => $by
          );
      $getitemso = $this->Detailsalesorder_model->find_by($key);

      $dataitem_do = array(
          'no_do' => $this->Deliveryorder_model->generate_nodo($session['kdcab']),
          'id_barang' => $getitemso->id_barang,
          'nm_barang' => $getitemso->nm_barang,
          'satuan' => $getitemso->satuan,
          'qty_order' => $getitemso->qty_order,
          'qty_supply' => $getitemso->qty_supply
          );
      $this->db->trans_start();
      $this->db->insert('trans_do_detail',$dataitem_do);
      //$this->db->where($key);
      //$this->db->update('trans_so_detail',array('proses_do'=>1));
      $this->db->trans_complete();
      if ($this->db->trans_status() === FALSE){
          $result['type'] = "error";
          $result['pesan'] = "Data gagal disimpan !";
      }else{
          $result['type'] = "success";
          $result['pesan'] = "Data sukses disimpan.";
      }
      echo json_encode($result);
  }

  public function hapus_item_do(){
      $id=$this->input->post('ID');
      if(!empty($id)){
         $result = $this->Detaildeliveryorder_model->delete_where(array('id'=>$id));
         $param['delete'] = 1;
      }else{
          $param['delete'] = 0;
      }
      echo json_encode($param);
  }

  function hapus_header_do(){
      $nodo = $this->input->post('NO_DO');
      if(!empty($nodo)){
         $result = $this->Deliveryorder_model->delete($noso);
         $param['delete'] = 1;
      }else{
          $param['delete'] = 0;
      }
      echo json_encode($param);
  }

  function saveheaderdo(){
      $session = $this->session->userdata('app_session');
      $nodo = $this->Deliveryorder_model->generate_nodo($session['kdcab']);
      $supir = $this->Deliveryorder_model->cek_data(array('id_karyawan'=>$this->input->post('supir_do')),'karyawan');
      $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$this->input->post('idcustomer_do')),'customer');

      $dataheaderdo = array(
          'no_do' => $nodo,
          'id_customer' => $this->input->post('idcustomer_do'),
          'nm_customer' => $this->input->post('nmcustomer_do'),
          'alamat_customer' => $customer->alamat,
          'id_salesman' => $this->input->post('id_salesman'),
          'nm_salesman' => $this->input->post('nm_salesman'),
          'tgl_do' => date('Y-m-d'),
          'tipe_pengiriman' => $this->input->post('tipekirim'),
          'id_supir' => $this->input->post('supir_do'),
          'nm_supir' => $supir->nama_karyawan,
          'id_kendaraan' => $this->input->post('kendaraan_do'),
          'status' => $this->input->post('status_do')
      );

      $detail = array(
          'noso_todo'=>$_POST['noso_todo'],
          'id_barang'=>$_POST['id_barang'],
          //'qty_supply'=>$_POST['qty_supply']
          );
      //$counttodo = $this->Deliveryorder_model->cek_data(,'barang_stock');

      //print_r($_POST['noso_todo']);
      //echo count($detail['noso_todo']);die();

      $this->db->trans_begin();

      for($i=0;$i < count($detail['noso_todo']);$i++){
          $key = array(
          'no_so' => $_POST['noso_todo'][$i],
          'id_barang' => $_POST['id_barang'][$i]
          );
          $getitemso = $this->Detailsalesorder_model->find_by($key);
          $getitemsopending = $this->Detailpendingso_model->find_by($key);

          $dataitem_do = array(
              'no_do' => $this->Deliveryorder_model->generate_nodo($session['kdcab']),
              'no_so' => $_POST['noso_todo'][$i],
              'id_barang' => $getitemso->id_barang,
              'nm_barang' => $getitemso->nm_barang,
              'satuan' => $getitemso->satuan,
              'qty_order' => $getitemso->qty_order,
              'qty_supply' => $_POST['qty_supply'][$i]
          );
          $this->db->insert('trans_do_detail',$dataitem_do);

          $keyclose_so = array(
              'no_so' => $_POST['noso_todo'][$i],
              'id_barang' => $getitemso->id_barang
              );
          if($this->input->post('status_do') == "DO"){ // ini berarti proses DO dari SO biasa
              //$newqty = $this->input->post('qty_confirm')[$i]-$this->input->post('qty_supply')[$i];
              $newqty = $getitemso->qty_supply+$this->input->post('qty_supply')[$i];
              if($this->input->post('qty_supply')[$i] == $this->input->post('qty_confirm')[$i]){
                  //berarti SO CLOSE
                  $this->db->where($keyclose_so);
                  $this->db->update('trans_so_detail',array('proses_do'=>1,'qty_supply'=>$newqty));//Detail SO sudah semua

                  //Untuk update so_header CLOSE
                  $key_open = array('no_so' => $_POST['noso_todo'][$i],'proses_do'=>0); // 0 = detail SO masih open
                  $cek_ada_open = $this->Salesorder_model->get_data($key_open,'trans_so_detail');
                  if(!$cek_ada_open){ //Tidak ada yang OPEN berarti Header update jadi CLS
                    $this->db->where(array('no_so' => $_POST['noso_todo'][$i]));
                    $this->db->update('trans_so_header',array('stsorder'=>'CLOSE'));
                  }
                  //END Update CLose
              }else{
                  $this->db->where($keyclose_so);
                  $this->db->update('trans_so_detail',array('qty_supply'=>$newqty));//Jika masih ada sisa qty
              }
          }else{
              $newqtypending = $getitemsopending->qty_supply+$this->input->post('qty_supply')[$i];
             if($this->input->post('qty_supply')[$i] == $this->input->post('qty_confirm')[$i]){
                  //berarti SO CLOSE
                  $this->db->where($keyclose_so);
                  $this->db->update('trans_so_pending_detail',array('proses_do'=>1,'qty_supply'=>$newqtypending));//Detail SO sudah semua
              }else{
                  $this->db->where($keyclose_so);
                  $this->db->update('trans_so_pending_detail',array('qty_supply'=>$newqtypending));//Jika masih ada sisa qty
              }
          }
          //Update STOK REAL
          $count = $this->Deliveryorder_model->cek_data(array('id_barang'=>$getitemso->id_barang,'kdcab'=>$session['kdcab']),'barang_stock');
          $this->db->where(array('id_barang'=>$getitemso->id_barang,'kdcab'=>$session['kdcab']));
          $this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock-$_POST['qty_supply'][$i]));
          //Update STOK REAL
      }

      //Update counter NO_DO
      $count = $this->Deliveryorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
      $this->db->where(array('kdcab'=>$session['kdcab']));
      $this->db->update('cabang',array('no_suratjalan'=>$count->no_suratjalan+1));
      //Update counter NO_DO
      $this->db->insert('trans_do_header',$dataheaderdo);
      if ($this->db->trans_status() === FALSE)
      {
          $this->db->trans_rollback();
          $param = array(
          'save' => 0,
          'msg' => "GAGAL, simpan data..!!!"
          );
      }
      else
      {
          $this->db->trans_commit();
          $param = array(
          'save' => 1,
          'msg' => "SUKSES, simpan data..!!!"
          );
      }
      echo json_encode($param);
  }

  function set_cancel_do(){
      $nodo = $this->input->post('NO_DO');
      if(!empty($nodo)){
          $kdcab = substr($nodo,0,3);
          $session = $this->session->userdata('app_session');
          $this->db->trans_begin();
         $getitemdo = $this->Salesorder_model->get_data(array('no_do'=>$nodo),'trans_do_detail');
         foreach($getitemdo as $k=>$v){
              //Update STOK REAL
              $count = $this->Deliveryorder_model->cek_data(array('id_barang'=>$v->id_barang,'kdcab'=>$kdcab),'barang_stock');
              $this->db->where(array('id_barang'=>$v->id_barang,'kdcab'=>$kdcab));
              $this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock+$v->qty_supply));
              //Update STOK REAL

              //Update QTY SUPPLY SO DAN OPEN SO LAGI
              $qtysuppso = $this->Salesorder_model->cek_data(array('id_barang'=>$v->id_barang,'no_so'=>$v->no_so),'trans_so_detail');
              $this->db->where(array('id_barang'=>$v->id_barang,'no_so'=>$v->no_so));
              $this->db->update('trans_so_detail',array('stsorder'=>'OPEN','qty_supply'=>$qtysuppso->qty_supply+$v->qty_supply));
              //Update QTY SUPPLY SO
         }
         $this->db->where(array('no_do'=>$nodo));
         $this->db->update('trans_do_header',array('status'=>'CCL'));
          if ($this->db->trans_status() === FALSE){
              $this->db->trans_rollback();
              $param['cancel'] = 0;
          }else{
              $this->db->trans_commit();
              $param['cancel'] = 1;
          }
      }
      echo json_encode($param);
  }

  function print_request($nodo){
      $mpdf=new mPDF('','','','','','','','','','');
      $mpdf->SetImportUse();
      $mpdf->RestartDocTemplate();

      $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
      $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$do_data->id_customer),'customer');
      $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $nodo,'qty_supply >'=>0));

      $this->template->set('do_data', $do_data);
      $this->template->set('customer', $customer);
      $this->template->set('detail', $detail);

      $show = $this->template->load_view('print_data',$data);

      $this->mpdf->AddPage('L');
      $this->mpdf->WriteHTML($show);
      $this->mpdf->Output();
  }

}

?>
