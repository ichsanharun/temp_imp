<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Pending_so extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Salesorder.View";
    protected $addPermission    = "Salesorder.Add";
    protected $managePermission = "Salesorder.Manage";
    protected $deletePermission = "Salesorder.Delete";

    public function __construct(){
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Pending_so/Salesorder_model',
                                 'Pending_so/Detailsalesorder_model',
                                 'Pending_so/Detailsalesordertmp_model',
                                 'Pending_so/Detailsoptmp_model',
                                 'Pending_so/Detailsoedittmp_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Pending SO');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index(){
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Salesorder_model->order_by('no_so','DESC')->find_all_by(array('total !='=>0,'stsorder'=>'PENDING', 'LEFT(no_so,3)'=>$session['kdcab']));
        $disc_cash = $this->Salesorder_model->get_data(array('diskon'=>'CASH'),'diskon');
        //$this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $this->template->set('results', $data);
        $this->template->set('disc_cash', $disc_cash);
        $this->template->title('Pending SO');
        $this->template->render('list');
    }

    public function getitemsotemp(){
        $this->template->render('getitemsotemp');
    }

    public function create(){
        $this->auth->restrict($this->addPermission);

        $session = $this->session->userdata('app_session');
        $itembarang    = $this->Salesorder_model
        ->pilih_item($session['kdcab'])
        ->result();
        $itembarang_bonus    = $itembarang;
        //$diskontoko = $this->Salesorder_model->get_data(array('deleted'=>'0'),'customer');
        $listitembarang = $this->Detailsalesordertmp_model->find_all_by(array('createdby'=>$session['id_user']));
        if(!@$listitembarang){
            $this->session->unset_userdata('header_so');
        }
        //$customer = $this->Salesorder_model->get_data(array('deleted'=>'0'),'customer');
        $customer = $this->Customer_model->find_all_by(array('deleted'=>0));
        $marketing = $this->Salesorder_model->pilih_marketing()->result();

        $this->template->set('itembarang',$itembarang);
        $this->template->set('listitembarang',$listitembarang);
        $this->template->set('customer',$customer);
        $this->template->set('marketing',$marketing);
        $this->template->title('Input Sales Order');
        $this->template->render('salesorder_form');
    }

    public function filter()
    {
      $data = $this->Salesorder_model
      ->where("stsorder = 'PENDING' AND tanggal between '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."'")
      ->order_by('no_so','DESC')->find_all();
      $disc_cash = $this->Salesorder_model->get_data(array('diskon'=>'CASH'),'diskon');
      //$this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
      $this->template->set('results', $data);
      $this->template->set('disc_cash', $disc_cash);
      $this->template->title('Sales Order');
      $this->template->render('list');
    }

    //Edit Sales Order
    public function edit(){
        //$this->auth->restrict($this->addPermission);
        $session = $this->session->userdata('app_session');
        $noso= $this->uri->segment(3);
        $gethead = $this->Salesorder_model->find_by(array('no_so'=>$noso));
        $getdetail = $this->Detailsalesorder_model->find_all_by(array('no_so'=>$noso));
        $this->db->trans_begin();
        if ($getdetail) {

          foreach ($getdetail as $key => $valued) {
            $keycek = array(
              'no_so' => $valued->no_so,
              'id_barang' => $valued->id_barang ,
              //'createdby'=> $valued->created_by
            );
            $data_tmp = array(
              'no_so' => $valued->no_so,
              'id_barang' => $valued->id_barang ,
              'nm_barang' => $valued->nm_barang ,
              'satuan' => $valued->satuan ,
              'jenis' => $valued->jenis ,
              'qty_order_awal' => $valued->qty_order ,
              'qty_supply' => $valued->qty_supply ,
              'qty_booked_awal' => $valued->qty_booked ,
              'qty_cancel_awal' => $valued->qty_cancel ,
              'qty_pending_awal' => $valued->qty_pending ,
              'stok_avl_awal' => $valued->stok_avl ,
              'qty_order' => $valued->qty_order ,

              'qty_booked' => $valued->qty_booked ,
              'qty_cancel' => $valued->qty_cancel ,
              'qty_pending' => $valued->qty_pending ,
              'stok_avl' => $valued->stok_avl ,
              'ukuran' => $valued->ukuran ,
              'harga' => $valued->harga ,
              'harga_normal' => $valued->harga_normal ,
              'diskon' => $valued->diskon ,
              'diskon_persen' => $valued->diskon_persen ,
              'diskon_standar' => $valued->diskon_standar ,
              'diskon_promo_rp' => $valued->diskon_promo_rp ,
              'diskon_promo_persen' => $valued->diskon_promo_persen ,
              'qty_bonus' => $valued->qty_bonus ,
              'subtotal' => $valued->subtotal ,
              'tgl_order'=> $valued->tgl_order,
              'createdby'=> $session['id_user'],
              'tipe_tmp'=> 'edit',
            );
            $count_data = $this->db->where($keycek)
            ->from('trans_so_edit_detail_tmp')
            ->count_all_results();
            if ($count_data == 0) {
              $this->db->insert('trans_so_edit_detail_tmp',$data_tmp);
            }else {
              $this->db->where($keycek);
              $this->db->update('trans_so_edit_detail_tmp',array('tipe_tmp'=> 'edit','createdby'=>$session['id_user']));
            }
          }
        }
        else {
          $getdetail = array();
        }

        $header  = $this->Salesorder_model->find_by(array('no_so' => $noso));
        $detail  = $this->Detailsoedittmp_model->find_all_by(array('no_so' => $noso,'tipe_tmp'=>'edit'));

        $itembarang    = $this->Salesorder_model->pilih_item($session['kdcab'])->result();
        $listitembarang = $this->Detailsalesorder_model->find_all_by(array('no_so' => $noso));
        $customer = $this->Customer_model->find_all();
        $marketing = $this->Salesorder_model->pilih_marketing()->result();
        $pic = $this->Salesorder_model->get_pic_customer($header->id_customer)->result();

        $this->template->set('itembarang',$itembarang);
        $this->template->set('data',$header);
        $this->template->set('listitembarang',$listitembarang);
        $this->template->set('detail',$detail);
        $this->template->set('customer',$customer);
        $this->template->set('marketing',$marketing);
        $this->template->set('pic',$pic);
        $this->template->title('Edit Sales Order');
        $this->template->render('salesorder_form_edit');
    }

    public function create_pso(){
        //$this->auth->restrict($this->addPermission);
        $session = $this->session->userdata('app_session');
        $noso= $this->uri->segment(3);
        $gethead = $this->Salesorder_model->find_by(array('no_so'=>$noso));
        $getdetail = $this->Detailsalesorder_model->find_all_by(array('no_so'=>$noso));

        $this->db->trans_begin();
        /*
        $dataheader = array(
            'no_so' => $gethead->no_so,
            'idcustomer' => $gethead->idcustomer,
            'nmcustomer' => $gethead->nmcustomer,
            'pic' => $gethead->pic,
            'idsalesman' => $gethead->idsalesman,
            'nmsalesman' => $gethead->nmsalesman,
            'tglso' => $gethead->tglso,
            'dppso' => $gethead->dppso,
            'totalso' => $gethead->totalso,
            'ppnso' => $gethead->ppnso,
            'nilaippn' => $gethead->nilaippn,
            'persen_diskon_toko' => $gethead->persen_diskon_toko,
            'persen_diskon_cash' => $gethead->persen_diskon_cash,
            'diskon_toko' => $gethead->diskon_toko,
            'diskoncash' => $gethead->diskoncash,
            'top' => $gethead->top,
            'keterangan' => $gethead->keterangan
            );
        $this->session->set_userdata('header_so',$dataheader);
        */
        foreach ($getdetail as $key => $valued) {
          $keycek = array(
            'no_so' => $valued->no_so,
            'id_barang' => $valued->id_barang ,
            'created_by'=> $valued->created_by
          );
          $data_tmp = array(
                      'no_so' => $valued->no_so,
                      'id_barang' => $valued->id_barang ,
                      'nm_barang' => $valued->nm_barang ,
                      'satuan' => $valued->satuan ,
                      'jenis' => $valued->jenis ,
                      'qty_order' => $valued->qty_order ,
                      'qty_supply' => $valued->qty_supply ,
                      'qty_booked' => $valued->qty_booked ,
                      'qty_cancel' => $valued->qty_cancel ,
                      'qty_pending' => $valued->qty_pending ,
                      'stok_avl' => $valued->stok_avl ,
                      'ukuran' => $valued->ukuran ,
                      'harga' => $valued->harga ,
                      'harga_normal' => $valued->harga_normal ,
                      'diskon' => $valued->diskon ,
                      'diskon_persen' => $valued->diskon_persen ,
                      'diskon_standar' => $valued->diskon_standar ,
                      'diskon_promo_rp' => $valued->diskon_promo_rp ,
                      'diskon_promo_persen' => $valued->diskon_promo_persen ,
                      'qty_bonus' => $valued->qty_bonus ,
                      'subtotal' => $valued->subtotal ,
                      'tgl_order'=> $valued->tgl_order,
                      'created_by'=> $valued->created_by,
          );
          $count_data = $this->db->where($keycek)
                                 ->from('trans_so_pending_detail_tmp')
                                 ->count_all_results();
          if ($count_data == 0) {
            $this->db->insert('trans_so_pending_detail_tmp',$data_tmp);
          }else {
            //$this->db->where($keycek);
            //$this->db->update('trans_so_pending_detail_tmp',$data_tmp);
          }
        }

        $header  = $this->Salesorder_model->find_by(array('no_so' => $noso));
        $detail  = $this->Detailsoptmp_model->find_all_by(array('no_so' => $noso));

        $itembarang    = $this->Salesorder_model->pilih_item($session['kdcab'])->result();
        $listitembarang = $this->Detailsalesorder_model->find_all_by(array('no_so' => $noso));
        $customer = $this->Customer_model->find_all();
        $marketing = $this->Salesorder_model->pilih_marketing()->result();
        $pic = $this->Salesorder_model->get_pic_customer($header->id_customer)->result();

        $this->template->set('itembarang',$itembarang);
        $this->template->set('data',$header);
        $this->template->set('listitembarang',$listitembarang);
        $this->template->set('detail',$detail);
        $this->template->set('customer',$customer);
        $this->template->set('marketing',$marketing);
        $this->template->set('pic',$pic);
        $this->template->title('Buat Sales Order dari Pending');
        $this->template->render('salesorder_form_pso');
    }

    public function edit_pso(){
        $noso = $this->uri->segment(3);
        $session = $this->session->userdata('app_session');
        $getparam = explode(";",$_GET['param']);
        //$getbr = $this->Detailsalesorder_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->get_where_in('trans_pr_header.no_pr',$getparam,'trans_pr_header');

        $and = " no_so = '".$noso."' ";
        $itembarang    = $this->Salesorder_model->pilih_item($session['kdcab'])->result();
        $getitempr = $this->Detailsalesorder_model
        ->select( '*,
                  trans_so_pending_detail_tmp.harga AS harga_so'
                )
        ->join("barang_stock", "barang_stock.id_barang = trans_so_pending_detail_tmp.id_barang", "left")
        ->get_where_in_and('trans_so_pending_detail_tmp.id_barang',$getparam,$and,'trans_so_pending_detail_tmp');
        //$pajak = $this->Purchaseorder_model->get_data('ppn IS NOT NULL','parameter');
        $this->template->set('param',$getparam);
        $this->template->set('itembarang',$itembarang);
        $this->template->set('getitemso',$getitempr);
        $this->template->title('Edit Item SO Pending');
        $this->template->render('salesorder_edit');
        //$this->template->load_view('salesorder_edit', $data);

        /*
        $detail = $this->Salesorder_model->get_data(array('no_so'=>$noso,'qty_pending !='=>0),'trans_so_detail');
        $this->template->set('detail', $detail);
        $this->template->title('Proses Pending SO');
        $this->template->render('prosespendingso');*/
    }

    public function edit_barang_so(){
        $noso = $this->uri->segment(3);
        $session = $this->session->userdata('app_session');
        $getparam = explode(";",$_GET['param']);
        //$getbr = $this->Detailsalesorder_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->get_where_in('trans_pr_header.no_pr',$getparam,'trans_pr_header');

        $and = " no_so = '".$noso."' ";
        $itembarang    = $this->Salesorder_model->pilih_item($session['kdcab'])->result();
        $getitempr = $this->Detailsalesorder_model
        ->select( '*,
                  trans_so_edit_detail_tmp.harga AS harga_so'
                )
        ->join("barang_stock", "barang_stock.id_barang = trans_so_edit_detail_tmp.id_barang", "left")
        ->get_where_in_and('trans_so_edit_detail_tmp.id_barang',$getparam,$and,'trans_so_edit_detail_tmp');
        //$pajak = $this->Purchaseorder_model->get_data('ppn IS NOT NULL','parameter');
        $this->template->set('param',$getparam);
        $this->template->set('itembarang',$itembarang);
        $this->template->set('getitemso',$getitempr);
        $this->template->title('Edit Item SO Pending');
        $this->template->render('salesorder_edit');
        //$this->template->load_view('salesorder_edit', $data);

        /*
        $detail = $this->Salesorder_model->get_data(array('no_so'=>$noso,'qty_pending !='=>0),'trans_so_detail');
        $this->template->set('detail', $detail);
        $this->template->title('Proses Pending SO');
        $this->template->render('prosespendingso');*/
    }

    function get_detail_so(){
        $noso = $this->input->post('NO_SO');
        $id = $this->input->post('ID');
        $key = array('no_so'=>$noso,'id_barang'=>$id);
        if(!empty($noso) && !empty($id)){
            $detail  = $this->Detailsalesorder_model->find_by($key);
        }
        echo json_encode($detail);
    }

    //Get detail item barang
    function get_item_barang(){
        $idbarang = $_GET['idbarang'];
        $datbarang = $this->Salesorder_model->get_item_barang($idbarang)->row();

        echo json_encode($datbarang);
    }

    //Get detail Customer
    function get_customer(){
        $idcus = $_GET['idcus'];
        $customer = $this->Salesorder_model->get_customer($idcus)->row();

        echo json_encode($customer);
    }

    //Get PIC Customer
    function get_pic_customer(){
        $idcus = $_GET['idcus'];
        $customer = $this->Salesorder_model->get_pic_customer($idcus)->result();
        $pichtml = '';
        if($customer){
            //$pichtml = '<select name="pic" id="pic" class="form-control input-sm select2">';
            foreach($customer as $k=>$v){
                if($v->divisi != "" && $v->jabatan != ""){
                    $pichtml .= '<option value="'.$v->id_pic.'">'.$v->nm_pic.' - '.$v->divisi.' ('.$v->jabatan.')</option>';
                }else{
                    $pichtml .= '<option value="'.$v->id_pic.'">'.$v->nm_pic.'</option>';
                }
            }
            //$pichtml .= '</select>';
        }else{
            $pichtml = '';
        }

        echo $pichtml;
    }

  	function get_pic_customer_new(){
  		$arr_Data		= array();
  		$kode_cust		= $_GET['company'];
  		$nam_cust		= $_GET['term'];
  		if($kode_cust !=''){
  			$Query	  ="SELECT * FROM customer_pic WHERE id_customer='".$kode_cust."' AND nm_pic LIKE '%".$nam_cust."%'";
  			 $customer = $this->db->query($Query)->result_array();
  			 if($customer){
  				 $loop	=0;
  				 foreach($customer as $key=>$vals){
  					 $loop++;
  					 $kode_Pic	= $vals['id_pic'];
  					 $arr_Data[$Key]	= array(
  						'label'				=> $vals['nm_pic'],
  						'value'				=> $vals['nm_pic'],
  						'id'				=> $kode_Pic
  					 );

  				 }
  				 unset($customer);
  			 }
  		}
         echo json_encode($arr_Data);
    }

    //Get detail Sales
    function get_salesman(){
        $idsales = $_GET['idsales'];
        $salesman = $this->Salesorder_model->get_marketing($idsales)->row();
        echo json_encode($salesman);
    }

    function saveitemso(){
        $dataheader = array(
            'idcustomer' => $this->input->post('idcustomer'),
            'ns' => $this->input->post('ns'),
            'nmcustomer' => $this->input->post('nmcustomer'),
            'pic' => $this->input->post('pic'),
            'idsalesman' => $this->input->post('idsalesman'),
            'nmsalesman' => $this->input->post('nmsalesman'),
            'tglso' => $this->input->post('tglso'),
            'dppso' => $this->input->post('dppso'),
            'totalso' => $this->input->post('totalso'),
            'ppnso' => $this->input->post('ppnso'),
            'nilaippn' => $this->input->post('nilaippn'),
            'persen_diskon_toko' => $this->input->post('persen_diskon_toko'),
            'persen_diskon_cash' => $this->input->post('persen_diskon_cash'),
            'diskon_toko' => $this->input->post('diskon_toko'),
            'diskoncash' => $this->input->post('diskoncash'),
            'top' => $this->input->post('top'),
            'keterangan' => $this->input->post('keterangan')
            );
        $this->session->set_userdata('header_so',$dataheader);

        $session = $this->session->userdata('app_session');
        $noso = $this->Salesorder_model->generate_noso($session['kdcab']);
        $ns = $this->input->post('ns');
        $idbarang = $this->input->post('item_brg_so');
        $nmbarang = $this->input->post('nama_barang');
        $satuan = $this->input->post('satuan');
        $jenis = $this->input->post('jenis');
        $qtyorder = $this->input->post('qty_order');
        $qtyavl = $this->input->post('qty_avl');
        $qtysupply = $this->input->post('qty_supply');//==> qty confirm
        $qtypending = $this->input->post('qty_pending');
        $qtycancel = $this->input->post('qty_cancel');
        $diskon_persen = $this->input->post('diskon_standar_persen');
        $diskon_standar = $this->input->post('diskon_standar_persen') * $this->input->post('qty_supply') * $this->input->post('harga_normal') / 100;
        $diskon_promo_rp = $this->input->post('diskon_promo_rp');
        $diskon_promo_persen = $this->input->post('diskon_promo_persen');
        $qty_bonus = $this->input->post('qty_bonus');
        $harga = $this->input->post('harga');
        $harga_normal = $this->input->post('harga_normal');
        $diskon = $diskon_standar + $diskon_promo_rp + ($diskon_promo_persen * $harga_normal * $qtysupply / 100);

        $total = $this->input->post('total');
        //$this->auth->restrict($this->addPermission);


        /*
        if($qtyavl <= $qtyorder){
            $qtybooked = $qtyavl;
        }else{
            $qtybooked = $qtysupply;
        }
        */

        $dataso = array(
            'no_so' => $noso,
            'ns' => $ns,//qty_supply default nol
            'id_barang' => $idbarang,
            'nm_barang' => $nmbarang,
            'satuan' => $satuan,
            'jenis' => $jenis,
            'qty_order' => $qtyorder,
            //'qty_supply' => $qtysupply,//qty_supply default nol
            'qty_pending' => $qtypending,
            'qty_cancel' => $qtycancel,
            'qty_booked' => $qtysupply,//qty_confirm
            'stok_avl' => $qtyavl,
            'ukuran' => '',
            'harga' => $harga,
            'harga_normal' => $harga_normal,
            'diskon' => $diskon,
            'diskon_persen' => $diskon_persen,
            'diskon_standar' => $diskon_standar,
            'diskon_promo_rp' => $diskon_promo_rp,
            'diskon_promo_persen' => $diskon_promo_persen,
            'qty_bonus' => $qty_bonus,
            'subtotal' => $total,
            'createdby' => $session['id_user']
            );

        //print_r($dataso);die();

        $this->db->trans_begin();
        $this->db->insert('trans_so_detail_tmp',$dataso);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, tambah item barang..!!!",
            'header' => $dataheader
            );
        }
        echo json_encode($param);
    }

    function saveitemso_pending(){
      $keyhead = array('no_so' => $this->input->post('no_so_pending'));
      $session = $this->session->userdata('app_session');
        $dataheader = array(
            'no_so' => $this->input->post('no_so_pending'),
            'id_customer' => $this->input->post('idcustomer'),
            'nm_customer' => $this->input->post('nmcustomer'),
            'pic' => $this->input->post('pic'),
            'id_salesman' => $this->input->post('idsalesman'),
            'nm_salesman' => $this->input->post('nmsalesman'),
            'tanggal' => $this->input->post('tglso'),
            'dpp' => $this->input->post('dppso'),
            'total' => $this->input->post('totalso'),
            'ppn' => $this->input->post('ppnso'),
            'flag_ppn' => $this->input->post('nilaippn'),
            'persen_diskon_toko' => $this->input->post('persen_diskon_toko'),
            'persen_diskon_cash' => $this->input->post('persen_diskon_cash'),
            'diskon_toko' => $this->input->post('diskon_toko'),
            'diskon_cash' => $this->input->post('diskoncash'),
            'top' => $this->input->post('top'),
            'keterangan' => $this->input->post('keterangan'),
            'modified_on'=>date("Y-m-d H:i:s"),
            'modified_by'=>$session['id_user']
            );
        $this->db->where($keyhead);
        $this->db->update('trans_so_header',$dataheader);


        $noso = $this->input->post('no_so_pending');
        $idbarang = $this->input->post('item_brg_so');
        $nmbarang = $this->input->post('nama_barang');
        $satuan = $this->input->post('satuan');
        $jenis = $this->input->post('jenis');
        $qtyorder = $this->input->post('qty_order');
        $qtyavl = $this->input->post('qty_avl');
        $qtysupply = $this->input->post('qty_supply');//==> qty confirm
        $qtypending = $this->input->post('qty_pending');
        $qtycancel = $this->input->post('qty_cancel');
        $diskon_persen = $this->input->post('diskon_standar_persen');
        $diskon_standar = $this->input->post('diskon_standar_persen') * $this->input->post('qty_supply') * $this->input->post('harga_normal') / 100;
        $diskon_promo_rp = $this->input->post('diskon_promo_rp');
        $diskon_promo_persen = $this->input->post('diskon_promo_persen');
        $qty_bonus = $this->input->post('qty_bonus');
        $harga = $this->input->post('harga');
        $harga_normal = $this->input->post('harga_normal');
        $diskon = $diskon_standar + $diskon_promo_rp + ($diskon_promo_persen * $harga_normal * $qtysupply / 100);
        $total = $this->input->post('total');


        $dataso = array(
            'no_so' => $noso,
            'id_barang' => $idbarang,
            'nm_barang' => $nmbarang,
            'satuan' => $satuan,
            'jenis' => $jenis,
            'qty_order' => $qtyorder,
            'qty_pending' => $qtypending,
            'qty_cancel' => $qtycancel,
            'qty_booked' => $qtysupply,//qty_confirm
            'stok_avl' => $qtyavl,
            'ukuran' => '',
            'harga' => $harga,
            'harga_normal' => $harga_normal,
            'diskon' => $diskon,
            'diskon_persen' => $diskon_persen,
            'diskon_standar' => $diskon_standar,
            'diskon_promo_rp' => $diskon_promo_rp,
            'diskon_promo_persen' => $diskon_promo_persen,
            'qty_bonus' => $qty_bonus,
            'subtotal' => $total,
            'created_by' => $session['id_user'],
            'tgl_order' => date("Y-m-d")
            );



        $keycek = array(
          'no_so' => $noso,
          'id_barang' => $idbarang
        );
        $count_data = $this->db->where($keycek)
                               ->from('trans_so_pending_detail_tmp')
                               ->count_all_results();
        if ($count_data == 0) {
          $this->db->trans_begin();
          $this->db->insert('trans_so_pending_detail_tmp',$dataso);

        }else {
          $this->db->trans_commit();
          $param = array(
          'save' => 0,
          'msg' => "GAGAL, item barang sudah ada..!!!"
          );
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, tambah item barang..!!!",
            'header' => $dataheader
            );
        }
        //$this->db->insert('trans_so_pending_detail_tmp',$dataso);

        echo json_encode($param);
    }

    function saveitemso_edit(){
      $keyhead = array('no_so' => $this->input->post('no_so_pending'));
      $session = $this->session->userdata('app_session');
        $dataheader = array(
            'no_so' => $this->input->post('no_so_pending'),
            'id_customer' => $this->input->post('idcustomer'),
            'nm_customer' => $this->input->post('nmcustomer'),
            'pic' => $this->input->post('pic'),
            'id_salesman' => $this->input->post('idsalesman'),
            'nm_salesman' => $this->input->post('nmsalesman'),
            'tanggal' => $this->input->post('tglso'),
            'dpp' => $this->input->post('dppso'),
            'total' => $this->input->post('totalso'),
            'ppn' => $this->input->post('ppnso'),
            'flag_ppn' => $this->input->post('nilaippn'),
            'persen_diskon_toko' => $this->input->post('persen_diskon_toko'),
            'persen_diskon_cash' => $this->input->post('persen_diskon_cash'),
            'diskon_toko' => $this->input->post('diskon_toko'),
            'diskon_cash' => $this->input->post('diskoncash'),
            'top' => $this->input->post('top'),
            'keterangan' => $this->input->post('keterangan'),
            'modified_on'=>date("Y-m-d H:i:s"),
            'modified_by'=>$session['id_user']
            );
        $this->db->where($keyhead);
        $this->db->update('trans_so_header',$dataheader);


        $noso = $this->input->post('no_so_pending');
        $idbarang = $this->input->post('item_brg_so');
        $nmbarang = $this->input->post('nama_barang');
        $satuan = $this->input->post('satuan');
        $jenis = $this->input->post('jenis');
        $qtyorder = $this->input->post('qty_order');
        $qtyavl = $this->input->post('qty_avl');
        $qtysupply = $this->input->post('qty_supply');//==> qty confirm
        $qtypending = $this->input->post('qty_pending');
        $qtycancel = $this->input->post('qty_cancel');
        $diskon_persen = $this->input->post('diskon_standar_persen');
        $diskon_standar = $this->input->post('diskon_standar_persen') * $this->input->post('qty_supply') * $this->input->post('harga_normal') / 100;
        $diskon_promo_rp = $this->input->post('diskon_promo_rp');
        $diskon_promo_persen = $this->input->post('diskon_promo_persen');
        $qty_bonus = $this->input->post('qty_bonus');
        $harga = $this->input->post('harga');
        $harga_normal = $this->input->post('harga_normal');
        $diskon = $diskon_standar + $diskon_promo_rp + ($diskon_promo_persen * $harga_normal * $qtysupply / 100);
        $total = $this->input->post('total');


        $dataso = array(
            'no_so' => $noso,
            'id_barang' => $idbarang,
            'nm_barang' => $nmbarang,
            'satuan' => $satuan,
            'jenis' => $jenis,
            'qty_order_awal' => 0,
            'qty_pending_awal' => 0,
            'qty_cancel_awal' => 0,
            'qty_booked_awal' => 0,//qty_confirm
            'stok_avl_awal' => 0,

            'qty_order' => $qtyorder,
            'qty_pending' => $qtypending,
            'qty_cancel' => $qtycancel,
            'qty_booked' => $qtysupply,//qty_confirm
            'stok_avl' => $qtyavl,
            'ukuran' => '',
            'harga' => $harga,
            'harga_normal' => $harga_normal,
            'diskon' => $diskon,
            'diskon_persen' => $diskon_persen,
            'diskon_standar' => $diskon_standar,
            'diskon_promo_rp' => $diskon_promo_rp,
            'diskon_promo_persen' => $diskon_promo_persen,
            'qty_bonus' => $qty_bonus,
            'subtotal' => $total,
            'createdby' => $session['id_user'],
            'tgl_order' => date("Y-m-d"),
            'tipe_tmp' => 'edit',
            );



        $keycek = array(
          'no_so' => $noso,
          'id_barang' => $idbarang
        );
        $count_data = $this->db->where($keycek)
                               ->from('trans_so_edit_detail_tmp')
                               ->count_all_results();
        if ($count_data == 0) {
          $this->db->trans_begin();
          $this->db->insert('trans_so_edit_detail_tmp',$dataso);

        }else {
          $this->db->trans_commit();
          $param = array(
          'save' => 0,
          'msg' => "GAGAL, item barang sudah ada..!!!"
          );
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, tambah item barang..!!!",
            'header' => $dataheader
            );
        }
        //$this->db->insert('trans_so_pending_detail_tmp',$dataso);

        echo json_encode($param);
    }

    function save_edit_so_pending(){

        $session = $this->session->userdata('app_session');
        $noso = $this->input->post('noso');
        $detail = array(
        'no_so' => $_POST['noso'],
        'id_barang' => $_POST['id_barang']
        );
        $count = count($noso);
        $this->db->trans_begin();

        for($i=1;$i <= $count;$i++){
            $key = array(
            'no_so' => $this->input->post('noso')[$i],
            'id_barang' => $this->input->post('id_barang')[$i],
            'created_by' => $session['id_user'],
            );


            $dataitem_pso = array(
                'harga'           => $this->input->post('harga')[$i],
                'subtotal'        => $this->input->post('qty_confirm')[$i] * $this->input->post('harga')[$i],
                'diskon'          => ($this->input->post('harga_normal')[$i] - $this->input->post('harga')[$i] ) * $this->input->post('qty_confirm')[$i],
                'diskon_standar'  => ($this->input->post('diskon_persen')[$i] * $this->input->post('harga_normal')[$i]/100 ),
                'qty_order'       => $this->input->post('qty_order')[$i],
                'qty_pending'     => $this->input->post('pending_again')[$i],
                'qty_cancel'      => $this->input->post('cancel_again')[$i],
                'qty_booked'      => $this->input->post('qty_confirm')[$i],
                'stok_avl'        => $this->input->post('qty_avl')[$i],
            );

              $this->db->where($key)
              ->update('trans_so_pending_detail_tmp',$dataitem_pso);


        }

        /*

        $idbarang = $this->input->post('item_brg_so');
        $nmbarang = $this->input->post('nama_barang');
        $satuan = $this->input->post('satuan');
        $jenis = $this->input->post('jenis');
        $qtyorder = $this->input->post('qty_order');
        $qtyavl = $this->input->post('qty_avl');
        $qtysupply = $this->input->post('qty_supply');//==> qty confirm
        $qtypending = $this->input->post('qty_pending');
        $qtycancel = $this->input->post('qty_cancel');
        $diskon_persen = $this->input->post('diskon_standar_persen');
        $diskon_standar = $this->input->post('diskon_standar_persen') * $this->input->post('qty_supply') * $this->input->post('harga_normal') / 100;
        $diskon_promo_rp = $this->input->post('diskon_promo_rp');
        $diskon_promo_persen = $this->input->post('diskon_promo_persen');
        $qty_bonus = $this->input->post('qty_bonus');
        $harga = $this->input->post('harga');
        $harga_normal = $this->input->post('harga_normal');
        $diskon = $diskon_standar + $diskon_promo_rp + ($diskon_promo_persen * $harga_normal * $qtysupply / 100);
        $total = $this->input->post('total');


        $dataso = array(
            'no_so' => $noso,
            'id_barang' => $idbarang,
            'nm_barang' => $nmbarang,
            'satuan' => $satuan,
            'jenis' => $jenis,
            'qty_order' => $qtyorder,
            'qty_pending' => $qtypending,
            'qty_cancel' => $qtycancel,
            'qty_booked' => $qtysupply,//qty_confirm
            'stok_avl' => $qtyavl,
            'ukuran' => '',
            'harga' => $harga,
            'harga_normal' => $harga_normal,
            'diskon' => $diskon,
            'diskon_persen' => $diskon_persen,
            'diskon_standar' => $diskon_standar,
            'diskon_promo_rp' => $diskon_promo_rp,
            'diskon_promo_persen' => $diskon_promo_persen,
            'qty_bonus' => $qty_bonus,
            'subtotal' => $total,
            'created_by' => $session['id_user'],
            'tgl_order' => date("Y-m-d")
            );



        $keycek = array(
          'no_so' => $noso,
          'id_barang' => $idbarang
        );
        $count_data = $this->db->where($keycek)
                               ->from('trans_so_pending_detail_tmp')
                               ->count_all_results();
        if ($count_data == 0) {
          $this->db->trans_begin();
          $this->db->insert('trans_so_pending_detail_tmp',$dataso);

        }else {
          $this->db->trans_commit();
          $param = array(
          'save' => 0,
          'msg' => "GAGAL, item barang sudah ada..!!!"
          );
        }*/
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, simpan data item barang..!!!",
            'header' => $dataheader
            );
        }
        //$this->db->insert('trans_so_pending_detail_tmp',$dataso);

        echo json_encode($param);
    }

    function save_edit_so(){

        $session = $this->session->userdata('app_session');
        $noso = $this->input->post('noso');
        $detail = array(
        'no_so' => $_POST['noso'],
        'id_barang' => $_POST['id_barang']
        );
        $count = count($noso);
        $this->db->trans_begin();

        for($i=1;$i <= $count;$i++){
            $key = array(
            'no_so' => $this->input->post('noso')[$i],
            'id_barang' => $this->input->post('id_barang')[$i],
            'createdby' => $session['id_user'],
            );


            $dataitem_pso = array(
                'harga'           => $this->input->post('harga')[$i],
                'subtotal'        => $this->input->post('qty_confirm')[$i] * $this->input->post('harga')[$i],
                'diskon'          => ($this->input->post('harga_normal')[$i] - $this->input->post('harga')[$i] ) * $this->input->post('qty_confirm')[$i],
                'diskon_standar'  => ($this->input->post('diskon_persen')[$i] * $this->input->post('harga_normal')[$i]/100 ),
                'qty_order'       => $this->input->post('qty_order')[$i],
                'qty_pending'     => $this->input->post('pending_again')[$i],
                'qty_cancel'      => $this->input->post('cancel_again')[$i],
                'qty_booked'      => $this->input->post('qty_confirm')[$i],
                'stok_avl'        => $this->input->post('qty_avl_barang')[$i],
            );
            $qty_avl_fix = $this->input->post('qty_avl_barang')[$i] - $this->input->post('qty_confirm')[$i];
              $this->db->where($key)
              ->update('trans_so_edit_detail_tmp',$dataitem_pso);
              /*$keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('id_barang')[$i]);
              $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
              $this->db->where($keycek);
              $this->db->update('barang_stock',array('qty_avl'=>$qty_avl_fix));*/

        }


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => $POST['qty_order'][0],
            'header' => $dataheader
            );
        }
        //$this->db->insert('trans_so_pending_detail_tmp',$dataso);

        echo json_encode($param);
    }

    function ajaxdetailso(){
        $this->load->view('salesorder/ajax/ajaxdetailsossss');
    }

    function saveheaderso(){
        $session = $this->session->userdata('app_session');
        $noso = $this->Salesorder_model->generate_noso($session['kdcab']);
        $noso_pending = $this->Salesorder_model->generate_no_pending($noso);
        $no_pickinglist = $this->Salesorder_model->generate_no_pl($session['kdcab']);
        $ns = $this->input->post('ns');
        $idcustomer = $this->input->post('idcustomer');
        $nmcustomer = $this->input->post('nmcustomer');
        $tglso = $this->input->post('tglso');
        $idsalesman = $this->input->post('idsalesman');
        $nmsalesman = $this->input->post('nmsalesman');
        $picso = $this->input->post('pic');
        $waktu = date('Y-m-d H:i:s');
        $statusso = '';
        $dppso = $this->input->post('dppso');
        $ppnso = $this->input->post('ppnso');
        $flagppn = $this->input->post('nilaippn');
        $totalso = $this->input->post('totalso');
        $persen_diskon_toko = $this->input->post('persen_diskon_toko');
        $persen_diskon_cash = $this->input->post('persen_diskon_cash');
        $diskon_toko = $this->input->post('diskon_toko');
        $diskon_cash = $this->input->post('diskon_cash');
        $keterangan = $this->input->post('keterangan');
        $top = $this->input->post('top');

        $dataheaderso = array(
            'no_so' => $noso,
            'ns' => $ns,
            'no_picking_list' => $no_pickinglist,
            'id_customer' => $idcustomer,
            'nm_customer' => $nmcustomer,
            'tanggal' => $tglso,
            'top' => $top,
            'id_salesman' => $idsalesman,
            'nm_salesman' => $nmsalesman,
            'pic' => $picso,
            'waktu' => $waktu,
            'dpp' => $dppso,
            'ppn' => $ppnso,
            'flag_ppn' => $flagppn,
            'total' => $totalso,
            'diskon_toko' => $diskon_toko,
            'persen_diskon_toko' => $persen_diskon_toko,
            'persen_diskon_cash' => $persen_diskon_cash,
            'diskon_cash' => $diskon_cash,
            'keterangan' => $keterangan,
            'created_on'=>date("Y-m-d H:i:s"),
            'created_by'=>$session['id_user']
            );
        $this->db->trans_begin();
        $this->db->insert('trans_so_header',$dataheaderso);
        $data_tmp = $this->Detailsalesordertmp_model->find_all_by(array('createdby'=>$session['id_user']));
        $dataitem_pending = array();
        foreach($data_tmp as $key=>$val){
          if ($val->qty_booked < $val->qty_order) {
            $qty_supply_pending = '1';
            $qty_booked = $val->qty_order-$val->qty_pending-$val->qty_cancel;
            $qty_pending = $val->qty_order-$val->qty_booked-$val->qty_cancel;
          }
          else {
            $qty_booked = $val->qty_booked;
            $qty_pending = $val->qty_pending;
          }
            $dataitem = array(
                'no_so' => $noso,
                'ns' => $val->ns,
                'id_barang' => $val->id_barang,
                'nm_barang' => $val->nm_barang,
                'satuan' => $val->satuan,
                'jenis' => '',
                'qty_order' => $val->qty_booked,
                'qty_supply' => $val->qty_supply,
                'qty_booked' => $qty_booked,
                'qty_cancel' => $val->qty_cancel,
                'qty_pending' => $qty_pending,
                'stok_avl' => $val->stok_avl,
                'ukuran' => '',
                'harga' => $val->harga,
                'harga_normal' => $val->harga_normal,
                'diskon' => $val->diskon,
                'diskon_persen' => $val->diskon_persen,
                'diskon_standar' => $val->diskon_standar,
                'diskon_promo_rp' => $val->diskon_promo_rp,
                'diskon_promo_persen' => $val->diskon_promo_persen,
                'qty_bonus' => $val->qty_bonus,
                'subtotal' => $val->subtotal,
                'tgl_order'=>date("Y-m-d"),
                'created_by'=>$session['id_user']
            );

              $this->db->insert('trans_so_detail',$dataitem);

            //Update QTY_AVL
            $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
            $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
            $this->db->where($keycek);
            $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$val->qty_booked));
            //Update QTY_AVL
        }



        //Update counter NO_SO
        $counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $pl = 1;
        if(date('y') == $counter->th_picking_list){
            $pl = $counter->no_picking_list+1;
        }
        $data_update = array(
            'no_so'=>$counter->no_so+1,
            'th_picking_list' => date('y'),
            'no_picking_list' => $pl
            );
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',$data_update);
        //Update counter NO_SO
        if (isset($qty_supply_pending)) {
          $noso_next = $this->Salesorder_model->generate_noso($session['kdcab']);
          $nopl_next = $this->Salesorder_model->generate_no_pl($session['kdcab']);
          foreach($data_tmp as $key=>$val){
            $qty_order = $val->qty_order-$val->qty_booked;
            $qty_booked = $val->qty_order-$val->qty_pending-$val->qty_booked;
            $dataitempending = array(
              'no_so' => $noso_pending,
              'ns' => $ns,
              'id_barang' => $val->id_barang,
              'nm_barang' => $val->nm_barang,
              'satuan' => $val->satuan,
              'jenis' => '',
              'qty_order' => $qty_order,
              'qty_supply' => $val->qty_supply,
              'qty_booked' => $qty_booked,
              'qty_cancel' => $val->qty_cancel,
              'qty_pending' => $val->qty_pending,
              'stok_avl' => $val->stok_avl,
              'ukuran' => '',
              'harga' => $val->harga,
              'harga_normal' => $val->harga_normal,
              'diskon' => $val->diskon,
              'diskon_persen' => $val->diskon_persen,
              'diskon_standar' => $val->diskon_standar,
              'diskon_promo_rp' => $val->diskon_promo_rp,
              'diskon_promo_persen' => $val->diskon_promo_persen,
              'qty_bonus' => $val->qty_bonus,
              'subtotal' => $val->harga*$qty_order,
              'tgl_order'=>date("Y-m-d"),
              'created_by'=>$session['id_user']
            );
            if ($val->qty_booked < $val->qty_order) {
              $this->db->insert('trans_so_detail',$dataitempending);
              $totalso_pending = $totalso_pending + $qty_order*$val->harga;
            }
            else {
              //$dataitem_pending = array_merge($dataitem_pending, $dataitem);
            }

          }
          $dataheadersopending = array(
                'no_so' => $noso_pending,
                'ns' => $ns,
                'no_picking_list' => $nopl_next,
                'id_customer' => $idcustomer,
                'nm_customer' => $nmcustomer,
                'tanggal' => $tglso,
                'id_salesman' => $idsalesman,
                'nm_salesman' => $nmsalesman,
                'pic' => $picso,
                'waktu' => $waktu,
                'dpp' => $totalso_pending,
                'ppn' => 0,
                'flag_ppn' => 0,
                'total' => $totalso_pending-($totalso_pending*$this->input->post('persen_diskon_toko')/100),
                'persen_diskon_toko' => $this->input->post('persen_diskon_toko'),
                'persen_diskon_cash' => $this->input->post('persen_diskon_cash'),
                'diskon_toko' => $this->input->post('persen_diskon_toko')*$totalso_pending/100,
                'diskon_cash' => $diskon_cash,
                'top' => $top,
                'stsorder' => 'PENDING',
                'keterangan' => $keterangan,
                'created_on'=>date("Y-m-d H:i:s"),
                'created_by'=>$session['id_user'],
              );
              $this->db->insert('trans_so_header',$dataheadersopending);

              /*
              $counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
              $pl = 1;
              if(date('y') == $counter->th_picking_list){
                  $pl = $counter->no_picking_list+1;
              }
              $data_update = array(
                  'no_so'=>$counter->no_so+1,
                  'th_picking_list' => date('y'),
                  'no_picking_list' => $pl
                  );
              $this->db->where(array('kdcab'=>$session['kdcab']));
              $this->db->update('cabang',$data_update);*/
        }

        $this->db->delete('trans_so_detail_tmp', array('createdby' => $session['id_user']));
        //$this->db->truncate('trans_so_detail_tmp');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
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

    function saveheaderpso(){
        $session = $this->session->userdata('app_session');
        $noso = $this->Salesorder_model->generate_noso($session['kdcab']);
        $noso_pending = $this->Salesorder_model->generate_no_pending($noso);
        $noso_pending_lama = $this->input->post('no_so_pending');
        $no_pickinglist = $this->Salesorder_model->generate_no_pl($session['kdcab']);
        $idcustomer = $this->input->post('idcustomer');
        $nmcustomer = $this->input->post('nmcustomer');
        $tglso = $this->input->post('tglso');
        $idsalesman = $this->input->post('idsalesman');
        $nmsalesman = $this->input->post('nmsalesman');
        $picso = $this->input->post('pic');
        $waktu = date('Y-m-d H:i:s');
        $statusso = '';
        $dppso = $this->input->post('dppso');
        $ppnso = $this->input->post('ppnso');
        $flagppn = $this->input->post('nilaippn');
        $totalso = $this->input->post('totalso');
        $persen_diskon_toko = $this->input->post('persen_diskon_toko');
        $persen_diskon_cash = $this->input->post('persen_diskon_cash');
        $diskon_toko = $this->input->post('diskon_toko');
        $diskon_cash = $this->input->post('diskon_cash');
        $keterangan = $this->input->post('keterangan');
        $top = $this->input->post('top');

        $dataheaderso = array(
            'no_so' => $noso,
            'no_picking_list' => $no_pickinglist,
            'id_customer' => $idcustomer,
            'nm_customer' => $nmcustomer,
            'tanggal' => $tglso,
            'top' => $top,
            'id_salesman' => $idsalesman,
            'nm_salesman' => $nmsalesman,
            'pic' => $picso,
            'waktu' => $waktu,
            'dpp' => $dppso,
            'ppn' => $ppnso,
            'flag_ppn' => $flagppn,
            'total' => $totalso,
            'diskon_toko' => $diskon_toko,
            'persen_diskon_toko' => $persen_diskon_toko,
            'persen_diskon_cash' => $persen_diskon_cash,
            'diskon_cash' => $diskon_cash,
            'keterangan' => $keterangan,
            'created_on'=>date("Y-m-d H:i:s"),
            'created_by'=>$session['id_user']
            );
        $this->db->trans_begin();
        $this->db->insert('trans_so_header',$dataheaderso);
        $data_tmp = $this->Detailsoptmp_model->find_all_by(array('created_by'=>$session['id_user'],'no_so'=>$noso_pending_lama));
        $dataitem_pending = array();
        foreach($data_tmp as $key=>$val){
          if ($val->qty_booked < $val->qty_order) {
            $qty_supply_pending = '1';
            $qty_booked = $val->qty_order-$val->qty_pending-$val->qty_cancel;
            $qty_pending = $val->qty_order-$val->qty_booked-$val->qty_cancel;
          }
          else {
            $qty_booked = $val->qty_booked;
            $qty_pending = $val->qty_pending;
          }
            $dataitem = array(
                'no_so' => $noso,
                'id_barang' => $val->id_barang,
                'nm_barang' => $val->nm_barang,
                'satuan' => $val->satuan,
                'jenis' => '',
                'qty_order' => $val->qty_booked,
                'qty_supply' => $val->qty_supply,
                'qty_booked' => $qty_booked,
                'qty_cancel' => $val->qty_cancel,
                'qty_pending' => $qty_pending,
                'stok_avl' => $val->stok_avl,
                'ukuran' => '',
                'harga' => $val->harga,
                'harga_normal' => $val->harga_normal,
                'diskon' => $val->diskon,
                'diskon_persen' => $val->diskon_persen,
                'diskon_standar' => $val->diskon_standar,
                'diskon_promo_rp' => $val->diskon_promo_rp,
                'diskon_promo_persen' => $val->diskon_promo_persen,
                'qty_bonus' => $val->qty_bonus,
                'subtotal' => $val->subtotal,
                'tgl_order'=>date("Y-m-d"),
                'created_by'=>$session['id_user']
            );

              $this->db->insert('trans_so_detail',$dataitem);

            //Update QTY_AVL
            $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
            $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
            $this->db->where($keycek);
            $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$val->qty_booked));
            //Update QTY_AVL
        }



        //Update counter NO_SO
        $counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $pl = 1;
        if(date('y') == $counter->th_picking_list){
            $pl = $counter->no_picking_list+1;
        }
        $data_update = array(
            'no_so'=>$counter->no_so+1,
            'th_picking_list' => date('y'),
            'no_picking_list' => $pl
            );
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',$data_update);
        //Update counter NO_SO
        if (isset($qty_supply_pending)) {
          $noso_next = $this->Salesorder_model->generate_noso($session['kdcab']);
          $nopl_next = $this->Salesorder_model->generate_no_pl($session['kdcab']);
          foreach($data_tmp as $key=>$val){
            $qty_order = $val->qty_order-$val->qty_booked;
            $qty_booked = $val->qty_order-$val->qty_pending-$val->qty_booked;
            $dataitempending = array(
              'no_so' => $noso_pending,
              'id_barang' => $val->id_barang,
              'nm_barang' => $val->nm_barang,
              'satuan' => $val->satuan,
              'jenis' => '',
              'qty_order' => $qty_order,
              'qty_supply' => $val->qty_supply,
              'qty_booked' => $qty_booked,
              'qty_cancel' => $val->qty_cancel,
              'qty_pending' => $val->qty_pending,
              'stok_avl' => $val->stok_avl,
              'ukuran' => '',
              'harga' => $val->harga,
              'harga_normal' => $val->harga_normal,
              'diskon' => $val->diskon,
              'diskon_persen' => $val->diskon_persen,
              'diskon_standar' => $val->diskon_standar,
              'diskon_promo_rp' => $val->diskon_promo_rp,
              'diskon_promo_persen' => $val->diskon_promo_persen,
              'qty_bonus' => $val->qty_bonus,
              'subtotal' => $val->harga*$qty_order,
              'tgl_order'=>date("Y-m-d"),
              'created_by'=>$session['id_user']
            );
            if ($val->qty_booked < $val->qty_order) {
              $this->db->insert('trans_so_detail',$dataitempending);
              $totalso_pending = $totalso_pending + $qty_order*$val->harga;
            }
            else {
              //$dataitem_pending = array_merge($dataitem_pending, $dataitem);
            }

          }
          $dataheadersopending = array(
                'no_so' => $noso_pending,
                'no_picking_list' => $nopl_next,
                'id_customer' => $idcustomer,
                'nm_customer' => $nmcustomer,
                'tanggal' => $tglso,
                'id_salesman' => $idsalesman,
                'nm_salesman' => $nmsalesman,
                'pic' => $picso,
                'waktu' => $waktu,
                'dpp' => $totalso_pending,
                'ppn' => 0,
                'flag_ppn' => 0,
                'total' => $totalso_pending-($totalso_pending*$this->input->post('persen_diskon_toko')/100),
                'persen_diskon_toko' => $this->input->post('persen_diskon_toko'),
                'persen_diskon_cash' => $this->input->post('persen_diskon_cash'),
                'diskon_toko' => $this->input->post('persen_diskon_toko')*$totalso_pending/100,
                'diskon_cash' => $diskon_cash,
                'top' => $top,
                'stsorder' => 'PENDING',
                'keterangan' => $keterangan,
                'created_on'=>date("Y-m-d H:i:s"),
                'created_by'=>$session['id_user'],
              );
              $this->db->insert('trans_so_header',$dataheadersopending);

              /*
              $counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
              $pl = 1;
              if(date('y') == $counter->th_picking_list){
                  $pl = $counter->no_picking_list+1;
              }
              $data_update = array(
                  'no_so'=>$counter->no_so+1,
                  'th_picking_list' => date('y'),
                  'no_picking_list' => $pl
                  );
              $this->db->where(array('kdcab'=>$session['kdcab']));
              $this->db->update('cabang',$data_update);*/
        }

        $this->db->delete('trans_so_pending_detail_tmp', array('created_by' => $session['id_user'],'no_so'=>$noso_pending_lama));
        $this->db->where(array('no_so'=>$noso_pending_lama));
        $this->db->update('trans_so_header', array('stsorder' => 'CLS PENDING'));
        //$this->db->truncate('trans_so_detail_tmp');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
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

    function saveheaderso_edit(){
        $session = $this->session->userdata('app_session');
        $noso = $this->input->post('no_so_pending');
        //$noso_pending = $this->Salesorder_model->generate_no_pending($noso);
        //$noso_pending_lama = $this->input->post('no_so_pending');
        //$no_pickinglist = $this->Salesorder_model->generate_no_pl($session['kdcab']);
        $idcustomer = $this->input->post('idcustomer');
        $nmcustomer = $this->input->post('nmcustomer');
        $tglso = $this->input->post('tglso');
        $idsalesman = $this->input->post('idsalesman');
        $nmsalesman = $this->input->post('nmsalesman');
        $picso = $this->input->post('pic');
        $waktu = date('Y-m-d H:i:s');
        $statusso = '';
        $dppso = $this->input->post('dppso');
        $ppnso = $this->input->post('ppnso');
        $flagppn = $this->input->post('nilaippn');
        $totalso = $this->input->post('totalso');
        $persen_diskon_toko = $this->input->post('persen_diskon_toko');
        $persen_diskon_cash = $this->input->post('persen_diskon_cash');
        $diskon_toko = $this->input->post('diskon_toko');
        $diskon_cash = $this->input->post('diskon_cash');
        $keterangan = $this->input->post('keterangan');
        $top = $this->input->post('top');

        $dataheaderso = array(
            'no_so' => $noso,
            //'no_picking_list' => $no_pickinglist,
            'id_customer' => $idcustomer,
            'nm_customer' => $nmcustomer,
            'tanggal' => $tglso,
            'top' => $top,
            'id_salesman' => $idsalesman,
            'nm_salesman' => $nmsalesman,
            'pic' => $picso,
            'waktu' => $waktu,
            'dpp' => $dppso,
            'ppn' => $ppnso,
            'flag_ppn' => $flagppn,
            'total' => $totalso,
            'diskon_toko' => $diskon_toko,
            'persen_diskon_toko' => $persen_diskon_toko,
            'persen_diskon_cash' => $persen_diskon_cash,
            'diskon_cash' => $diskon_cash,
            'keterangan' => $keterangan,
            'modified_on'=>date("Y-m-d H:i:s"),
            'modified_by'=>$session['id_user']
            );
        $this->db->trans_begin();
        $this->db->where(array('no_so' => $noso))
        ->update('trans_so_header',$dataheaderso);
        $data_tmp = $this->Detailsoedittmp_model->find_all_by(array('createdby'=>$session['id_user'],'no_so'=>$noso));
        $data_so = $this->Detailsalesorder_model->find_all_by(array('created_by'=>$session['id_user'],'no_so'=>$noso));
        $dataitem_pending = array();
        foreach($data_tmp as $key=>$val){
          /*if ($val->qty_booked < $val->qty_order) {
            $qty_supply_pending = '1';
            $qty_booked = $val->qty_order-$val->qty_pending-$val->qty_cancel;
            $qty_pending = $val->qty_order-$val->qty_booked-$val->qty_cancel;
          }
          else {
            $qty_booked = $val->qty_booked;
            $qty_pending = $val->qty_pending;
          }*/
          $key_so = array(
            'no_so' => $val->no_so,
            'id_barang' => $val->id_barang
            //'created_by' => $val->createdby,
          );

            $dataitem = array(
                'no_so' => $noso,
                'id_barang' => $val->id_barang,
                'nm_barang' => $val->nm_barang,
                'satuan' => $val->satuan,
                'jenis' => '',
                'qty_order' => $val->qty_order,
                'qty_supply' => $val->qty_supply,
                'qty_booked' => $val->qty_booked,
                'qty_cancel' => $val->qty_cancel,
                'qty_pending' => $val->qty_pending,
                'stok_avl' => $val->stok_avl,
                'ukuran' => '',
                'harga' => $val->harga,
                'harga_normal' => $val->harga_normal,
                'diskon' => $val->diskon,
                'diskon_persen' => $val->diskon_persen,
                'diskon_standar' => $val->diskon_standar,
                'diskon_promo_rp' => $val->diskon_promo_rp,
                'diskon_promo_persen' => $val->diskon_promo_persen,
                'qty_bonus' => $val->qty_bonus,
                'subtotal' => $val->subtotal,
                'tgl_order'=>date("Y-m-d"),
                'modified_by'=>$session['id_user'],
                'modified_on'=>date("Y-m-d H:i:s"),
            );

            $count_data = $this->db->where($key_so)
                                   ->from('trans_so_detail')
                                   ->count_all_results();
            if ($count_data == 0) {
              $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
              $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
              if ($val->qty_booked>$val->qty_booked_awal) {
                $qty_confirm_add = $val->qty_booked_awal - $val->qty_booked_awal;
                if ($stok_avl->qty_avl < $qty_confirm_add){
                  $param_data = array(
                    'save' => 0,
                    'msg' => "GAGAL Simpan data".$val->nm_barang.", Qty Available telah berkurang dari transaksi lain dan tidak mencukupi untuk transaksi ini..!!!"
                  );
                }else {
                  $this->db->insert('trans_so_detail',$dataitem);
                  //Update QTY_AVL
                  $this->db->where($keycek);
                  $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$qty_confirm_add));
                  //Update QTY_AVL
                }
              }else {
                $this->db->insert('trans_so_detail',$dataitem);
                //Update QTY_AVL
                $this->db->where($keycek);
                $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$qty_confirm_add));
                //Update QTY_AVL
              }
            }else {
              //$data = $this->Salesorder_model->cek_data($key_so,'trans_so_detail');
              $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
              $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');

              if ($val->qty_booked > $val->qty_booked_awal) {
                $qty_confirm_add = $val->qty_booked - $val->qty_booked_awal;
                //$test_avl = $stok_avl->qty_avl-$qty_confirm_add;
                if ($stok_avl->qty_avl < $qty_confirm_add){
                  $param_data = array(
                    'save' => 0,
                    'msg' => "GAGAL Simpan data".$val->nm_barang.", Qty Available telah berkurang dari transaksi lain dan tidak mencukupi untuk transaksi ini..!!!"
                  );
                }else {
                  $this->db->where($key_so)
                  ->update('trans_so_detail',$dataitem);

                  //Update QTY_AVL
                  $this->db->where($keycek);
                  $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$qty_confirm_add));
                  //Update QTY_AVL
                }
              }else {
                $qty_confirm_add = $val->qty_booked_awal - $val->qty_booked;
                //$test_avl = $stok_avl->qty_avl-$qty_confirm_add;

                  $this->db->where($key_so)
                  ->update('trans_so_detail',$dataitem);

                  //Update QTY_AVL
                  $this->db->where($keycek);
                  $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl+$qty_confirm_add));
                  //Update QTY_AVL

              }
              /*
              if ($val->qty_booked > $data->qty_booked) {
                $qty_conf = $val->qty_booked - $data->qty_booked;

                $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
                $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
                $this->db->where($keycek);
                $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$qty_conf));
              }else {
                $qty_conf = $data->qty_booked - $val->qty_booked;

                $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
                $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
                $this->db->where($keycek);
                $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl+$qty_conf));
              }*/



            }



        }
        $this->db->delete('trans_so_edit_detail_tmp', array('createdby' => $session['id_user'],'no_so'=>$noso));


        //Update counter NO_SO
        /*$counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $pl = 1;
        if(date('y') == $counter->th_picking_list){
            $pl = $counter->no_picking_list+1;
        }
        $data_update = array(
            'no_so'=>$counter->no_so+1,
            'th_picking_list' => date('y'),
            'no_picking_list' => $pl
            );
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',$data_update);
        //Update counter NO_SO
        if (isset($qty_supply_pending)) {
          $noso_next = $this->Salesorder_model->generate_noso($session['kdcab']);
          $nopl_next = $this->Salesorder_model->generate_no_pl($session['kdcab']);
          foreach($data_tmp as $key=>$val){
            $qty_order = $val->qty_order-$val->qty_booked;
            $qty_booked = $val->qty_order-$val->qty_pending-$val->qty_booked;
            $dataitempending = array(
              'no_so' => $noso_pending,
              'id_barang' => $val->id_barang,
              'nm_barang' => $val->nm_barang,
              'satuan' => $val->satuan,
              'jenis' => '',
              'qty_order' => $qty_order,
              'qty_supply' => $val->qty_supply,
              'qty_booked' => $qty_booked,
              'qty_cancel' => $val->qty_cancel,
              'qty_pending' => $val->qty_pending,
              'stok_avl' => $val->stok_avl,
              'ukuran' => '',
              'harga' => $val->harga,
              'harga_normal' => $val->harga_normal,
              'diskon' => $val->diskon,
              'diskon_persen' => $val->diskon_persen,
              'diskon_standar' => $val->diskon_standar,
              'diskon_promo_rp' => $val->diskon_promo_rp,
              'diskon_promo_persen' => $val->diskon_promo_persen,
              'qty_bonus' => $val->qty_bonus,
              'subtotal' => $val->harga*$qty_order,
              'tgl_order'=>date("Y-m-d"),
              'created_by'=>$session['id_user']
            );
            if ($val->qty_booked < $val->qty_order) {
              $this->db->insert('trans_so_detail',$dataitempending);
              $totalso_pending = $totalso_pending + $qty_order*$val->harga;
            }
            else {
              //$dataitem_pending = array_merge($dataitem_pending, $dataitem);
            }

          }
          $dataheadersopending = array(
                'no_so' => $noso_pending,
                'no_picking_list' => $nopl_next,
                'id_customer' => $idcustomer,
                'nm_customer' => $nmcustomer,
                'tanggal' => $tglso,
                'id_salesman' => $idsalesman,
                'nm_salesman' => $nmsalesman,
                'pic' => $picso,
                'waktu' => $waktu,
                'dpp' => $totalso_pending,
                'ppn' => 0,
                'flag_ppn' => 0,
                'total' => $totalso_pending-($totalso_pending*$this->input->post('persen_diskon_toko')/100),
                'persen_diskon_toko' => $this->input->post('persen_diskon_toko'),
                'persen_diskon_cash' => $this->input->post('persen_diskon_cash'),
                'diskon_toko' => $this->input->post('persen_diskon_toko')*$totalso_pending/100,
                'diskon_cash' => $diskon_cash,
                'top' => $top,
                'stsorder' => 'PENDING',
                'keterangan' => $keterangan,
                'created_on'=>date("Y-m-d H:i:s"),
                'created_by'=>$session['id_user'],
              );
              $this->db->insert('trans_so_header',$dataheadersopending);

              /*
              $counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
              $pl = 1;
              if(date('y') == $counter->th_picking_list){
                  $pl = $counter->no_picking_list+1;
              }
              $data_update = array(
                  'no_so'=>$counter->no_so+1,
                  'th_picking_list' => date('y'),
                  'no_picking_list' => $pl
                  );
              $this->db->where(array('kdcab'=>$session['kdcab']));
              $this->db->update('cabang',$data_update);* /
        }

        $this->db->delete('trans_so_pending_detail_tmp', array('created_by' => $session['id_user'],'no_so'=>$noso_pending_lama));
        $this->db->where(array('no_so'=>$noso_pending_lama));
        $this->db->update('trans_so_header', array('stsorder' => 'CLS PENDING'));
        //$this->db->truncate('trans_so_detail_tmp');*/
        if (isset($param_data)) {
          $param = $param_data;
        }
        else {

          if ($this->db->trans_status() === FALSE)
          {
            $this->db->trans_rollback();
            $param = array(
              'save' => 0,
              'msg' => "GAGAL, tambah item barang..!!!"
            );
          }
          else
          {
            $this->db->trans_commit();
            $param = array(
              'save' => 1,
              //'msg' => "SUKSES, simpan data..!!!"
              'msg' => $test_avl
            );
          }
        }
        echo json_encode($param);
    }

    function hapus_item_so(){
        $session = $this->session->userdata('app_session');
        $noso = $this->input->post('NO_SO');
        $id = $this->input->post('ID');
        $key = array('no_so'=>$noso,'id_barang'=>$id,'createdby'=>$session['id_user']);
        if(!empty($noso) && !empty($id)){
           $result = $this->Detailsalesordertmp_model->delete_where($key);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function hapus_item_so_pending(){
        $session = $this->session->userdata('app_session');
        $noso = $this->input->post('NO_SO');
        $id = $this->input->post('ID');
        $key = array('no_so'=>$noso,'id_barang'=>$id,'created_by'=>$session['id_user']);
        if(!empty($noso) && !empty($id)){
           $result = $this->Detailsalesorder_model->delete_where($key);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function hapus_header_so(){
        $noso = $this->input->post('NO_SO');
        if(!empty($noso)){
           $result = $this->Salesorder_model->delete($noso);
           $param['delete'] = 1;
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function set_cancel_so(){
        $noso = $this->input->post('NO_SO');
        if(!empty($noso)){
            $kdcab = substr($noso,0,3);
            $session = $this->session->userdata('app_session');
           $this->db->trans_begin();
           $getitemso = $this->Salesorder_model->get_data(array('no_so'=>$noso),'trans_so_detail');
           foreach($getitemso as $k=>$v){
                //Update QTY_AVL
                $keycek = array('kdcab'=>$kdcab,'id_barang'=>$v->id_barang);
                $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
                $this->db->where($keycek);
                $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl+$v->qty_booked));
                //Update QTY_AVL
           }
           $this->db->where(array('no_so'=>$noso));
           $this->db->update('trans_so_header',array('stsorder'=>'CANCEL'));
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

    function print_request($noso){
        $no_so = $noso;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so));

        $this->template->set('so_data', $so_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function print_picking_list($noso){
        $no_so = $noso;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so));

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
