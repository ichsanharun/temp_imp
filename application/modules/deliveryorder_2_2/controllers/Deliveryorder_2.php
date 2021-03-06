<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Deliveryorder_2 extends Admin_Controller {

    //Permission

    protected $viewPermission   = "Deliveryorder.View";
    protected $addPermission    = "Deliveryorder.Add";
    protected $managePermission = "Deliveryorder.Manage";
    protected $deletePermission = "Deliveryorder.Delete";

    public function __construct(){
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array('Deliveryorder_2/Deliveryorder_model',
                                 'Deliveryorder_2/Detaildeliveryorder_model',
                                 'Salesorder/Salesorder_model',
                                 'Salesorder/Detailsalesorder_model',
                                 'Pendingso/Pendingso_model',
                                 'Pendingso/Detailpendingso_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model',
                                 'Trans_stock/Trans_stock_model'
                                ));

        $this->template->title('Delivery Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index(){
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Deliveryorder_model->order_by('no_do','DESC')->find_all_by(array('LEFT(no_do,3)'=>$session['kdcab']));
        $this->template->set('results', $data);
        $this->template->title('Delivery Order');
        $this->template->render('list');
    }

    //Create New Delivery Order
    public function create(){
      $session = $this->session->userdata('app_session');
        //$this->auth->restrict($this->addPermission);
        /*
        $nodo = $this->Deliveryorder_model->generate_nodo($session['kdcab']);

        $marketing = $this->Deliveryorder_model->pilih_marketing()->result();
        $getitemdo = $this->Detaildeliveryorder_model->find_all_by(array('no_do'=>$nodo));

        $this->template->set('marketing',$marketing);
        $this->template->set('detaildo',$getitemdo);
        */
        $customer = $this->Customer_model->find_all_by(array('deleted'=>0,'kdcab'=>$session['kdcab']));
        $this->template->set('customer',$customer);

        if($this->uri->segment(3) == ""){

            $data = $this->Salesorder_model->get_salesorder_open("AND LEFT(h.no_so,3)='".$session['kdcab']."' AND do_supplied IS NULL");
            //$data = $this->Salesorder_model->order_by('no_so','ASC')->find_all_by(array('total !='=>0));
        }else{
            $data = $this->Salesorder_model->get_salesorder_open("AND id_customer ='".$this->uri->segment(3)."' ");
        }
        $this->template->set('results', $data);

        $this->template->title('Input Delivery Order');
        $this->template->render('list_so');
    }

    public function createpending(){
        $session = $this->session->userdata('app_session');
        $customer = $this->Customer_model->find_all_by(array('deleted'=>0));
        $this->template->set('customer',$customer);
        if($this->uri->segment(3) == ""){
            $data = $this->Pendingso_model->where("LEFT(trans_so_pending_header.no_so,3)='".$session['kdcab']."' ")->order_by('no_so','ASC')->find_all();
        }else{
            $data = $this->Pendingso_model->where("LEFT(trans_so_pending_header.no_so,3)='".$session['kdcab']."' ")->order_by('no_so','ASC')->find_all_by(array('id_customer'=>$this->uri->segment(3)));
        }
        $this->template->set('results', $data);
        $this->template->title('Input Delivery Order From Pending');
        $this->template->render('list_so_pending');
    }

    //Create New Delivery Order
    public function proses(){
      $session = $this->session->userdata('app_session');
          $getparam = explode(";",$_GET['param']);
          $getso = $this->Detailsalesorder_model->get_where_in('no_so',$getparam,'trans_so_header');

          $and = " proses_do IS NULL ";
          $getitemso = $this->Detailsalesorder_model->get_where_in_and('no_so',$getparam,$and,'trans_so_detail');

          $driver    = $this->Deliveryorder_model->pilih_driver($session['kdcab'])->result();
  		$Arr_Driver	= array();
  		if($driver){
  			foreach($driver as $keyD=>$valD){
  				$Kode_Driver		= $valD->id_karyawan;
  				$Name_Driver		= $valD->nama_karyawan;
  				$Arr_Driver[$Kode_Driver]	= $Name_Driver;
  			}
  			unset($driver);
  		}
          $kendaraan = $this->Deliveryorder_model->pilih_kendaraan($session['kdcab'])->result();
          $Arr_Kendaraan = array();
          if($kendaraan){
              foreach($kendaraan as $keyK=>$valK){
                  $Id_kendaraan        = $valK->id_kendaraan;
                  $Nama_kendaraan        = $valK->nm_kendaraan;
                  $Arr_Kendaraan[$Id_kendaraan]   = $Nama_kendaraan;
              }
              unset($kendaraan);
          }
          $this->template->set('param',$getparam);
          $this->template->set('headerso',$getso);
          $this->template->set('getitemso',$getitemso);
          $this->template->set('arr_driver',$Arr_Driver);
          $this->template->set('kendaraan',$Arr_Kendaraan);
          $this->template->title('Input Delivery Order');
          $this->template->render('deliveryorder_form');
    }

    //Create New Delivery Order
    public function prosesdopending(){
      $session = $this->session->userdata('app_session');
          $getparam = explode(";",$_GET['param']);
          $getso = $this->Detailpendingso_model->get_where_in('no_so_pending',$getparam,'trans_so_pending_header');
          $getitemso = $this->Detailpendingso_model->get_where_in('no_so_pending',$getparam,'trans_so_pending_detail');
          $driver = $this->Deliveryorder_model->pilih_driver($session['kdcab'])->result();
  		$Arr_Driver	= array();
  		if($driver){
  			foreach($driver as $keyD=>$valD){
  				$Kode_Driver		= $valD->id_karyawan;
  				$Name_Driver		= $valD->nama_karyawan;
  				$Arr_Driver[$Kode_Driver]	= $Name_Driver;
  			}
  			unset($driver);
  		}
          $kendaraan = $this->Deliveryorder_model->pilih_kendaraan($session['kdcab'])->result();
          $Arr_Kendaraan = array();
          if($kendaraan){
              foreach($kendaraan as $keyK=>$valK){
                  $Id_kendaraan        = $valK->id_kendaraan;
                  $Nama_kendaraan        = $valK->nm_kendaraan;
                  $Arr_Kendaraan[$Id_kendaraan]   = $Nama_kendaraan;
              }
              unset($kendaraan);
          }
          $this->template->set('param',$getparam);
          $this->template->set('headerso',$getso);
          $this->template->set('getitemso',$getitemso);
          $this->template->set('arr_driver',$Arr_Driver);
          $this->template->set('kendaraan',$Arr_Kendaraan);
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
        $nodo 	 = $this->Deliveryorder_model->generate_nodo($session['kdcab']);
       // $supir = $this->Deliveryorder_model->cek_data(array('id_karyawan'=>$this->input->post('supir_do')),'karyawan');
        $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$this->input->post('idcustomer_do')),'customer');
        $Kode_Driver    = $Name_Driver  ='-';
  		  $Kode_Kendaraan	= $Nama_Kendaraan	='-';
  		  $Tipe_Kirim		= $this->input->post('tipekirim');
  		  if(strtolower($Tipe_Kirim)=='sendiri'){
        			$Pecah_Driver	= explode('^_^',$this->input->post('supir_do'));
        			$Kode_Driver	= $Pecah_Driver[0];
        			$Name_Driver	= $Pecah_Driver[1];

              $Pecah_Kendaraan   = explode('^_^',$this->input->post('kendaraan_do'));
              $Kode_Kendaraan    = $Pecah_Kendaraan[0];
              $Nama_Kendaraan    = $Pecah_Kendaraan[1];
  		  }else{
              $Name_Driver    = strtoupper($this->input->post('supir_do'));
  			      $Nama_Kendaraan	= strtoupper($this->input->post('kendaraan_do'));
  		  }
          $dataheaderdo = array(
              'no_do' => $nodo,
              //'nd' => $this->input->post('nd'),
              'id_customer' => $this->input->post('idcustomer_do'),
              'nm_customer' => $this->input->post('nmcustomer_do'),
              'alamat_customer' => $customer->alamat,
              'id_salesman' => $this->input->post('id_salesman'),
              'nm_salesman' => $this->input->post('nm_salesman'),
              'tgl_do' => $this->input->post('tgl_do'),
              'tipe_pengiriman' => $this->input->post('tipekirim'),
              'id_supir' => $Kode_Driver,
              'nm_supir' => $Name_Driver,
              'id_kendaraan' => $Kode_Kendaraan,
              'ket_kendaraan' => $Nama_Kendaraan,
              'nm_helper' => $this->input->post('helper_do'),
              'status' => $this->input->post('status_do'),
              'created_on'=>date("Y-m-d H:i:s"),
              'created_by'=>$session['id_user']
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
          $x = 0;
          for($i=0;$i < count($detail['noso_todo']);$i++){
            $x++;
              $key = array(
              'no_so' => $_POST['noso_todo'][$i],
              'id_barang' => $_POST['id_barang'][$i]
              );
              $getitemso = $this->Detailsalesorder_model->find_by($key);
              $getitemsopending = $this->Detailpendingso_model->find_by($key);

              $dataitem_do = array(
                  'no_do' => $this->Deliveryorder_model->generate_nodo($session['kdcab']),
                  //'nd' => $this->input->post('nd'),
                  'no_so' => $_POST['noso_todo'][$i],
                  'id_barang' => $getitemso->id_barang,
                  'nm_barang' => $getitemso->nm_barang,
                  'satuan' => $getitemso->satuan,
                  'qty_order' => $getitemso->qty_order,
                  'qty_supply' => $_POST['qty_supply'][$i]
              );
              if ($_POST['qty_supply'][$i] == 0) {

              }else {
                $this->db->insert('trans_do_detail',$dataitem_do);
              }

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
                      $this->db->update('trans_so_detail',array('proses_do'=>'DO','qty_supply'=>$newqty));//Detail SO sudah semua
                      $cek_close +=1;
                      $this->db->where(array('no_so' => $_POST['noso_todo'][$i]));
                      $this->db->update('trans_so_header',array('do_supplied'=>$cek_close));//Detail SO sudah semua



                      //END Update CLose
                  }else{
                      $this->db->where($keyclose_so);
                      $this->db->update('trans_so_detail',array('proses_do'=>'PENDING','qty_supply'=>$newqty));//Jika masih ada sisa qty
                      //$this->db->update('trans_so_header',array('proses_do'=>'PENDING','qty_supply'=>$newqty));//Jika masih ada sisa qty
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
              $qty_stock_awal = $count->qty_stock;
              $qty_avl_awal = $count->qty_avl;
              $this->db->where(array('id_barang'=>$getitemso->id_barang,'kdcab'=>$session['kdcab']));
              $this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock-$_POST['qty_supply'][$i]));
              $qty_stock_akhir = $count->qty_stock-$_POST['qty_supply'][$i];
              $qty_avl_akhir = $count->qty_avl;

              $id_st = $this->Trans_stock_model->gen_st($this->auth->user_cab()).$x;
              $data_adj_trans = array(
                          'id_st'=>$id_st,
                          'tipe'=>'OUT',
                          'jenis_trans'=>'OUT_Pembelian',
                          'noreff'=>$this->Deliveryorder_model->generate_nodo($session['kdcab']),
                          'id_barang'=>$getitemso->id_barang,
                          'nm_barang'=>$getitemso->nm_barang,
                          'kdcab'=>$this->auth->user_cab(),
                          'date_stock'=>date('Y-m-d H:i:s'),
                          'qty'=>$_POST['qty_supply'][$i],
                          'nilai_barang'=>$getitemso->harga_normal,
                          'notes'=>'DO',
                          'qty_stock_awal' => $qty_stock_awal,
                          'qty_avl_awal' => $qty_avl_awal,
                          'qty_stock_akhir' => $qty_stock_akhir,
                          'qty_avl_akhir' => $qty_avl_akhir
                          );
                          $this->Trans_stock_model->insert($data_adj_trans);
              //Update STOK REAL
          }
          //$cek_close=0;
          for($i=0;$i < count($detail['noso_todo']);$i++){
            $getkeyso = $this->db->where(array('no_so' => $_POST['noso_todo'][$i]))
                                 ->from('trans_so_detail')
                                 ->count_all_results();
            $getsosupplied = $this->Salesorder_model->cek_data(array('no_so' => $_POST['noso_todo'][$i]),'trans_so_header');

            //$ckeyso = count($getkeyso->no_so);
            $csosupplied = $getsosupplied->do_supplied;
            if ($getkeyso == $csosupplied) {
              $this->db->where(array('no_so' => $_POST['noso_todo'][$i]));
              $this->db->update('trans_so_header',array('stsorder'=>'CLOSE'));
            }
            else {
              $this->db->where(array('no_so' => $_POST['noso_todo'][$i]));
              $this->db->update('trans_so_header',array('pending_counter'=>$getkeyso.$csosupplied));
            }
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

                //Update QTY SUPPLY SO
                $qtysuppso = $this->Salesorder_model->cek_data(array('id_barang'=>$v->id_barang,'no_so'=>$v->no_so),'trans_so_detail');
                $this->db->where(array('id_barang'=>$v->id_barang,'no_so'=>$v->no_so));
                $this->db->update('trans_so_detail',array('proses_do'=>NULL,'qty_supply'=>$qtysuppso->qty_supply - $v->qty_supply - $v->return_do - $v->return_do_rusak - $v->return_do_hilang));
                $this->db->where(array('no_so'=>$v->no_so));
                $this->db->update('trans_so_header',array('do_supplied'=>NULL));
                //Update QTY SUPPLY SO

                $this->db->where(array('no_so'=>$v->no_so));
                $this->db->update('trans_so_header',array('stsorder'=>'OPEN'));
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

    function print_request_old($nodo){
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

    function print_request_coba($nodo){
        $mpdf=new mPDF('utf-8', array(210,148), 10 ,'Arial', 5, 5, 200, 20, 5, 4);
        //$mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
        $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$do_data->id_customer),'customer');
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $nodo,'qty_supply >'=>0));

        $ar = array();
        foreach(@$detail as $kds=>$vds){
          $ar=array($vds->no_so);
        }
        $arr = array_unique($ar);
        $noco="";
        foreach($arr as $k){
          $noco=$k;
        }

        $this->template->set('do_data', $do_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);

        //$show = $this->template->load_view('print_data_custom',$data);
        $show = $this->template->load_view('p01',$data);

        //$this->mpdf->AddPage('L');

        $tglprint = date("d-m-Y H:i:s");
        $header = '<table width="100%" border="0" id="header-tabel">
                    <tr>
                        <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
                        <th style="border-right: none;">DELIVERY ORDER (DO)<br>'.@$do_data->no_do.'</th>
                        <th colspan="3" style="border-left: none;"></th>
                    </tr>
                    <tr>
                        <td width="15%">No. Confirm Order</td>
                        <td width="1%">:</td>
                        <td colspan="2">'.$noco.'</td>';

            $header .= '
                        <td width="15%">Yogyakarta</td>
                        <td width="1%">,</td>
                        <td>'.date('d-M-Y').'</td>
                    </tr>
                    <tr>
                        <td width="10%">SALES</td>
                        <td width="1%">:</td>
                        <td colspan="2">'.strtoupper(@$do_data->nm_salesman).'</td>
                        <td width="8%">Kepada Yth,</td>
                        <td width="1%"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="10%" valign="top">KETERANGAN</td>
                        <td width="1%" valign="top">:</td>
                        <td colspan="2"></td>
                        <td width="10%" colspan="3">'.strtoupper(@$do_data->nm_customer).'<br>'.@$customer->alamat.'</td>
                    </tr>';
                    if('{PAGENO}' == 1){
                        $header .= '<tr>
                                    <td colspan="7">
                                        Dengan Hormat,<br>
                                        Kami kirimkan barang-barang sebagai berikut ini :
                                    </tr>';
                    }

            $header .= '</table>';

        $this->mpdf->SetHTMLHeader($header,'0',true);
        $this->mpdf->SetHTMLFooter('
            <table width="100%" border="0" style="font-size:12pt !important">
            <tr>
                <td colspan="4">
                    Keterangan :<br>
                    Mohon setelah barang diterima surat jalan & lembar copy an wajib dicap toko, tanda tangan & cantumkan nama penerima barang
                </td>
            </tr>
            <tr>
                <td width="30%"><center>Dibuat Oleh,</center></td>
                <td width="40%"><center>Diperiksa 1 Oleh,</center></td>
                <td width="30%"><center>Diperiksa 2 Oleh,</center></td>
                <td width="30%"><center>Diterima Oleh,</center></td>
            </tr>
            <tr>
                <td width="15%" colspan="4" style="height: 50px;"></td>
            </tr>
            <tr>
                <td width="15%"><center>( Sales Planning & Support )</center></td>
                <td width="15%"><center>( KA.Gudang )</center></td>
                <td width="15%"><center>( Sopir )</center></td>
                <td width="15%"><center>( TTD & CAP TOKO )</center></td>
            </tr>
        </table>
        <hr />
        <div id="footer">
        <table>
            <tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($userData->nm_lengkap) ." On ". $tglprint.'</td></tr>
        </table>
        </div>');
        $this->mpdf->AddPageByArray([
                'orientation' => 'P',
                'sheet-size'=> [210,148],
                'margin-top' => 40,
                'margin-bottom' => 50,
                'margin-left' => 5,
                'margin-right' => 15
            ]);
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
    function print_request($nodo){
      $uk1 = 9;
      $ukk = 17;
      $ukkk = 18;
        $mpdf=new mPDF('utf-8', array(210,140), 10 ,'Roman', 5, 5, 16, 16, 1, 4, 'P');
        //$mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        //$mpdf->RestartDocTemplate();
        $session = $this->session->userdata('app_session');
        $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
        $cabang = $this->Deliveryorder_model->find_data('cabang',$session['kdcab'],'kdcab');
        $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$do_data->id_customer),'customer');
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $nodo,'qty_supply >'=>0));
        $det_do = $this->Deliveryorder_model->find_data('trans_do_detail',$nodo,'no_do');

        $this->template->set('do_data', $do_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);

        //$show = $this->template->load_view('print_data_custom',$data);
        $show = $this->template->load_view('print_data_custom',$data);

        //$this->mpdf->AddPage('L');

        $tglprint = date("d-m-Y H:i:s");
        $header = '<table width="100%" border="0" id="header-tabel" style="font-family: Arial, Helvetica, sans-serif">
                    <tr>
                      <th width="30%" style="text-align: left;">
                        <img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
                      </th>
                      <th colspan="3" style="border-right: none;text-align: center;padding-left:0% !important;margin-left:-10px !important" width="100%">DELIVERY ORDER (DO)<br>'.@$do_data->no_do.'</th>
                      <th colspan="4" style="border-left: none;"></th>
                    </tr>
                    </table>
                    <hr style="padding:0;margin:0">
                    <table width="100%" border="0" id="header-tabel" style="font-family: Arial, Helvetica, sans-serif">
                    <tr>
                        <td width="15%">No. Sales Order</td>
                        <td width="1%">:'.@$det_do->no_so.'</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td width="15%">'.@$cabang->namacabang.',</td>
                        <td>'.date('d-M-Y').'</td>
                    </tr>
                    <tr>
                        <td width="10%">SALES</td>
                        <td width="1%">:'.strtoupper(@$do_data->nm_salesman).'</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td width="8%">Kepada Yth,</td>

                    </tr>
                    <tr>
                        <td width="10%" valign="top">KETERANGAN</td>
                        <td width="1%" valign="top">:'.strtoupper(@$do_data->keterangan).'</td>
                        <td colspan="2"></td>
                        <td></td>
                        <td width="10%" colspan="3">'.strtoupper(@$do_data->nm_customer).'<br>'.@$customer->alamat.'</td>
                    </tr>';
                    if('{PAGENO}' == 1){
                        $header .= '<tr>
                                    <td colspan="7">
                                        Dengan Hormat,<br>
                                        Kami kirimkan barang-barang sebagai berikut ini :
                                    </tr>';
                    }

            $header .= '</table>
            ';

        $this->mpdf->SetHTMLHeader($header,'0',true);
        $this->mpdf->SetHTMLFooter('
            <table width="100%" border="0" style="font-size: '.$ukk.'px !important;">
            <tr>
                <td colspan="4" style="font-size: '.$ukk.'px !important;font-weight:bold">
                    Keterangan :<br>
                    Mohon setelah barang diterima surat jalan & lembar copy an wajib dicap toko, tanda tangan & cantumkan nama penerima barang
                </td>
            </tr>
            <tr>
                <td width="35%"><center>Dibuat Oleh,</center></td>
                <td width="40%"><center>Diperiksa 1 Oleh,</center></td>
                <td width="30%"><center>Diperiksa 2 Oleh,</center></td>
                <td width="30%"><center>Diterima Oleh,</center></td>
            </tr>
            <tr>
                <td style="text-align:right">
                  <img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
                </td>
                <td width="15%" colspan="3" style="height: 50px;"></td>
            </tr>
            <tr>
                <td width="17%"><center>( Admin Gudang )</center></td>
                <td width="15%"><center>( KA.Gudang )</center></td>
                <td width="15%"><center>( Sopir )</center></td>
                <td width="15%"><center>( TTD & CAP TOKO )</center></td>
            </tr>
        </table>
        <hr />
        <div id="footer">
        <table>
            <tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($userData->nm_lengkap) ." On ". $tglprint.'</td></tr>
        </table>
        </div>');
        $this->mpdf->AddPageByArray([
                'orientation' => 'P',
                'sheet-size'=> [210,150],
                'margin-top' => 40,
                'margin-bottom' => 50,
                'margin-left' => 5,
                'margin-right' => 10,
                'margin-header' => 1,
                'margin-footer' => 0,
            ]);
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function getdatado(){
        $session = $this->session->userdata('app_session');
        $nodo = $this->input->post('NODO');
        $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
        $driver = $this->Deliveryorder_model->pilih_driver($session['kdcab'])->result();
        $kendaraan = $this->Deliveryorder_model->pilih_kendaraan($session['kdcab'])->result();
        $this->template->set('do_data', $do_data);
        $this->template->set('kendaraan', $kendaraan);
        $this->template->set('driver', $driver);
        $this->template->render('edit_header_do');
    }

    function edit_header_do(){
        /*
        {"no_do_edit":"101-SJ-18I00077","tipekirim_edit":"SENDIRI","supir_do_edit":"0000000056|SURYO","kendaraan_do_edit":"000005|D 8872 XE","helper_do_edit":"ASSSS"}
        */

        if($this->input->post('tipekirim_edit') == 'SENDIRI'){
            $supir = explode('|',$this->input->post('supir_do_edit'));
            $mobil = explode('|',$this->input->post('kendaraan_do_edit'));
            $idsupir = $supir[0];
            $nmsupir = $supir[1];
            $idmobil = $mobil[0];
            $ketmobil = $mobil[1];
        }else{
            $idsupir = '-';
            $nmsupir = $this->input->post('supir_do_edit');
            $idmobil = '-';
            $ketmobil = $this->input->post('kendaraan_do_edit');
        }

        $dataEdit = array(
            'id_supir' => $idsupir,
            'nm_supir' => $nmsupir,
            'nm_helper' => $this->input->post('helper_do_edit'),
            'id_kendaraan' => $idmobil,
            'ket_kendaraan' => $ketmobil
            );
        $this->db->trans_begin();
        $this->db->where(array('no_do'=>$this->input->post('no_do_edit')));
        $this->db->update('trans_do_header',$dataEdit);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $param = array(
            'edit' => 0,
            'msg' => "GAGAL, update data..!!!"
            );
        }else{
            $this->db->trans_commit();
            $param = array(
            'edit' => 1,
            'msg' => "SUKSES, update data..!!!"
            );
        }
        echo json_encode($param);
    }

    function print_proforma($nodo){
      $mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
      $mpdf->SetImportUse();
      $session = $this->session->userdata('app_session');
      $cabang = $this->Deliveryorder_model->find_data('cabang',$session['kdcab'],'kdcab');
        $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
        $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$do_data->id_customer),'customer');
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $nodo,'qty_supply >'=>0));
        $det_do = $this->Detaildeliveryorder_model->find_by(array('no_do'=>$nodo));
        $header_so = $this->Deliveryorder_model->find_data('trans_so_header',$det_do->no_so,'no_so');
        $det_so = $this->Deliveryorder_model->find_data('trans_so_detail',$det_do->no_so,'no_so');

        foreach($detail as $k=>$v){
          $qty_supply			= $v->qty_supply;
          $kunci 		= array('no_so'=>$v->no_so,'id_barang'=>$v->id_barang);
          $detailso	= $this->Deliveryorder_model->cek_data($kunci,'trans_so_detail');
          $headerso	= $this->Deliveryorder_model->cek_data(array('no_so'=>$v->no_so),'trans_so_header');

            $no=$n++;

            $harga_normal       = $detailso->harga_normal;

            if ($headerso->ppn > 0) {
              //$harga  = $detailso->harga_normal/110*100;
              $ppn    = $harga_normal - $harga;
              $ppn_all = $ppn*$qty_supply;
            }else {
              $harga = $harga_normal;
            }
            $harga              = $harga_normal;
            $diskon_std_persen  = $detailso->diskon_persen;
            $diskon_std_rp      = $diskon_std_persen/100*$harga_normal;
            $harga_setelah_diskon_std = $harga_normal - $diskon_std_rp;

            $diskon_promo_persen= $detailso->diskon_promo_persen;
            $diskon_promo_rp    = $detailso->diskon_promo_persen/100*$harga_setelah_diskon_std;
            $harga_setelah_diskon_promo = $harga_setelah_diskon_std - $diskon_promo_rp;

            $diskon_so = $detailso->diskon_so;
            $tipe_diskon_so = $detailso->tipe_diskon_so;
            if ($tipe_diskon_so == "rupiah_tambah") {
              $harga_setelah_diskon_so = $harga_setelah_diskon_promo + $diskon_so;
              $tampil_diskon_so = "+Rp ".number_format($diskon_so);
            }elseif ($tipe_diskon_so == "rupiah_kurang") {
              $harga_setelah_diskon_so = $harga_setelah_diskon_promo - $diskon_so;
              $tampil_diskon_so = "-Rp ".number_format($diskon_so);
            }else {
              $harga_setelah_diskon_so = $harga_setelah_diskon_promo*(100-$diskon_so)/100;
              $tampil_diskon_so = $diskon_so." %";
            }
            //-------------------------END OF HARGA------------------------//
            $diskon_toko        = $headerso->persen_diskon_toko;
            $diskon_toko_rp     = $diskon_toko/100*$harga_setelah_diskon_so;
            $diskon_toko_rp_all = $diskon_toko_rp*$qty_supply;
            $harga_setelah_diskon_toko = $harga_setelah_diskon_so - $diskon_toko_rp;

            $diskon_cash        = $headerso->persen_diskon_cash;
            $diskon_cash_rp     = $diskon_cash/100*$harga_setelah_diskon_toko;
            $diskon_cash_rp_all = $diskon_cash_rp*$qty_supply;
            $harga_setelah_diskon_cash = $harga_setelah_diskon_toko - $diskon_cash_rp;

            $hargajualbefdis += $harga*$qty_supply;
            $hargajualafterdistoko += $harga_setelah_diskon_toko*$qty_supply;
            $dpp_sebelum += $harga_setelah_diskon_so*$qty_supply;

            $dpp_barang			= $qty_supply * $harga_setelah_diskon_cash;
            $diskon_barang		= $diskon_so;
            //$diskon_barang		= $qty_supply * $discount_satuan;
            $harga_bersih		= $dpp_barang - $diskon_barang;
            //$grand 				+= $harga_bersih;
            $grand_diskon_toko +=$diskon_toko_rp_all;
            $grand_diskon_cash +=$diskon_cash_rp_all;
            $grand_ppn += $ppn_all;
            $grand_setelah_toko += $harga_setelah_diskon_toko*$qty_supply;
            $grand 				+= $dpp_barang;
            $grand = ceil($grand);
        }
        //$qty_supply = $det_do->qty_supply;



        $this->template->set('do_data', $do_data);
        //$this->template->set('header_so', $header_so);
        //$this->template->set('det_so', $det_so);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);

        $show = $this->template->load_view('print_proforma',$data);

        $tglprint = date("d-m-Y H:i:s");
        $header = '
        <div style="display: inline-block; position:relative;width:100%;display: none;">
          <div style="width:25%">
            <img src="assets/img/logo.JPG">
          </div>
          <div style="position: absolute;text-align: center;margin-left:15% !important" width="75%">PROFORMA INVOICE<br>NO. SURAT JALAN : '.$do_data->no_do.'</div>
        </div>
        	<table width="100%" border="0" id="header-tabel">
  	      	<tr>
  	      		<th width="30%" style="text-align: left;">
  	      			<img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
  	      		</th>
  	      		<th colspan="4" style="border-right: none;text-align: center;padding:0 !important;margin:0px !important" width="75%">PROFORMA INVOICE<br>NO. SURAT JALAN : '.@$do_data->no_do.'</th>
              	<th colspan="3" style="border-left: none;"></th>
  	      	</tr>
        	</table>
          <table width="100%" border="0" id="header-tabel">

            <tr>
                <td width="5%">NO. SJ</td>

                <td colspan="3" width="50%">: '. @$do_data->no_do.'</td>
                <td>'. @$cabang->namacabang.',</td>
                <td width="1%"> </td>
                <td>'.date('d M Y',strtotime(@$do_data->tgl_do)).'</td>
            </tr>
            <tr>
                <td width="5%">SALES</td>

                <td colspan="3">: '. @$do_data->nm_salesman.'</td>
                <td width="15%">Kepada Yth,</td>
                <td width="1%"></td>
                <td></td>
            </tr>
            <tr>
                <td width="5%">TOP</td>

                <td colspan="3">:
                    '.@$header_so->top.' HARI  &nbsp;&nbsp;&nbsp; TGL JATUH TEMPO : '.date('d/m/Y',strtotime('+'.@$header_so->top.' days', strtotime(@$do_data->tgl_do))).'
                </td>
                <td width="15%" colspan="3" style="font-size:9pt !important;">
                    '.@$do_data->nm_customer.'
                </td>
            </tr>
            <tr>
                <td width="5%">KETERANGAN</td>

                <td colspan="3">:</td>
                <td width="15%" colspan="3" style="font-size:9pt !important;">
                    '.@$do_data->alamatcustomer.'
                </td>
            </tr>
          </table>';

        $this->mpdf->SetHTMLHeader($header,'0',true);



        $this->mpdf->SetHTMLFooter('
        <hr style="padding:0 !important">
        <table width="100%" border="0" style="font-size:8pt; padding:0 !important">

            <tr>
                <td colspan="3">
                  <i>TERBILANG : '.ucwords(ynz_terbilang_format($grand)).'</i>
                </td>
                <td width="15%">JUMLAH NOMINAL</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($dpp_sebelum).'</td>
                <!--<td width="10%"></td>-->

            </tr>

            <tr>
                <td colspan="3">

                </td>
                <td width="15%">DISKON TOKO &nbsp;&nbsp;&nbsp;'.$header_so->persen_diskon_toko.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;"> '.formatnomor($grand_diskon_toko).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3"></td>
                <td width="15%">DISKON CASH &nbsp;&nbsp;&nbsp;'.$header_so->persen_diskon_cash.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($grand_diskon_cash).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3" style="height: 40px;">
                  <center>(BM/SPV)</center>
                </td>

                <td width="15%"><b>GRAND TOTAL</b></td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($grand).'</td>
                <!--<td width="10%"></td>-->
            </tr>

            <tr>
                <td colspan="6">
                    Catatan : Pembayaran dengan cek/giro dianggap lunas apabila sudah dicairkan.
                </td>
            </tr>
        </table>
        <hr />
        <div id="footer">
        <table>
            <tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($session["nm_lengkap"]).' On '.$tglprint.'</td></tr>
        </table>
        </div>');
        $this->mpdf->AddPageByArray([
                'orientation' => 'P',
                'sheet-size'=> [210,148],
                'margin-top' => 45,
                'margin-bottom' => 40,
                'margin-left' => 5,
                'margin-right' => 10,
                'margin-header' => 1,
                'margin-footer' => 0,
            ]);
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

}

?>
