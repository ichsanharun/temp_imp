<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Returndo_claim extends Admin_Controller {

    //Permission

    protected $viewPermission   = "Returndo_claim.View";
    protected $addPermission    = "Returndo_claim.Add";
    protected $managePermission = "Returndo_claim.Manage";
    protected $deletePermission = "Returndo_claim.Delete";

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array('Deliveryorder_2/Deliveryorder_model',
                                 'Deliveryorder_2/Detaildeliveryorder_model',
                                 'Returndo_claim/Returklaim_model',
                                 'Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Salesorder/Salesorder_model',
                                 'Salesorder/Detailsalesorder_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model',
                                 'Trans_stock/Trans_stock_model',
                                 'Jurnal_nomor/Jurnal_model'
                                ));

        $this->template->title('Delivery Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index_old()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Deliveryorder_model->order_by('no_do','ASC')->find_all_by(array('konfirm_do' => "SUDAH", 'status'=>'INV'));

        $this->template->set('results', $data);
        $this->template->title('Return DO Claim');
        $this->template->render('list');
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Invoice_model->order_by('no_invoice','ASC')->find_all_by(array('kdcab' => $this->auth->user_cab(), 'flag_cancel'=>'N'));
        $rk = $this->Returklaim_model->order_by('no_retur','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->set('rk', $rk);
        $this->template->title('Return DO Claim');
        $this->template->render('index');
    }


    public function viewkonfirmasido(){
        $header = $this->Deliveryorder_model->order_by('no_do','ASC')->find_by(array('no_do' => $this->input->post('NODO')));
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $this->input->post('NODO')));
        $this->template->set('header', $header);
        $this->template->set('detail', $detail);
        $this->template->render('cekdetaildo');
    }

    public function setkonfirmasi(){
        $header = $this->Invoice_model->order_by('no_invoice','ASC')
        ->find_by(array('no_invoice' => $this->input->post('NODO')));
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $this->input->post('NODO')));
        $this->template->set('header', $header);
        $this->template->set('detail', $detail);
        $this->template->render('getdetaildo');
    }

    public function xsavekonfirmdo_old(){
      $session = $this->session->userdata('app_session');
        $kosong = 0;
        for($i=0;$i < count($this->input->post('konfirm_do'));$i++){
            if($this->input->post('konfirm_do')[$i] == ""){
                $kosong++;
            }

        }
        if($kosong > 0){
            $result['type'] = "error";
            $result['pesan'] = "Pastikan data konfirmasi lengkap";
        }else{
            $this->db->trans_begin();
            for($i=0;$i < count($this->input->post('konfirm_do'));$i++){
                $dataKonfirm = array(
                    'konfirm_do_detail' => $this->input->post('konfirm_do')[$i],
                    'return_do' => $this->input->post('return_do')[$i]
                    );
                $key = array(
                    'no_do'=>$this->input->post('no_do_konfirm'),
                    'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]
                    );
                $this->db->where($key)
                ->update('trans_do_detail',$dataKonfirm);
                if ($this->input->post('return_do')[$i] > 0) {

                  $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]);
                  $stok_avl = $this->Deliveryorder_model->cek_data($keycek,'barang_stock');
                  if ($this->input->post('konfirm_do')[$i] == "RETURN BAGUS") {
                    $this->db->where($keycek);
                    $this->db->update('barang_stock',
                      array(
                        'qty_avl'=>$stok_avl->qty_avl+$this->input->post('return_do')[$i],
                        'qty_stock'=>$stok_avl->qty_stock+$this->input->post('return_do')[$i],
                      )
                    );
                  }elseif ($this->input->post('konfirm_do')[$i] == "RETURN RUSAK") {
                    $this->db->where($keycek);
                    $this->db->update('barang_stock',
                      array(
                        'qty_barang_rusak'=>$stok_avl->qty_barang_rusak+$this->input->post('return_do')[$i],
                      )
                    );
                  }elseif ($this->input->post('konfirm_do')[$i] == "RETURN HILANG") {
                    $this->db->where($keycek);
                    $this->db->update('barang_stock',
                      array(
                        'qty_barang_hilang'=>$stok_avl->qty_barang_hilang+$this->input->post('return_do')[$i],
                      )
                    );
                  }
                }
            }
            $this->db->update('trans_do_header',array('konfirm_do'=>'SUDAH'),array('no_do'=>$this->input->post('no_do_konfirm')));
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $result['type'] = "error";
                $result['pesan'] = "Gagal simpan data";
            }else{
                $this->db->trans_commit();
                $result['type'] = "success";
                $result['pesan'] = "Sukses simpan data";
            }
        }

        echo json_encode($result);
    }

    public function xsavekonfirmdo(){
      $session = $this->session->userdata('app_session');
        $kosong = 0;
        $kosong_a = 0;
        for($i=0;$i < count($this->input->post('id_barang_do_konfirm'));$i++){
            if($this->input->post('return_do_rusak')[$i] == ""){
                $kosong++;
            }
            if($this->input->post('return_do_hilang')[$i] == ""){
                $kosong_a++;
            }

        }
        if($kosong > 0 && $kosong_a > 0){
            $result['type'] = "error";
            $result['pesan'] = "Pastikan data konfirmasi lengkap";
        }else{
            $this->db->trans_begin();
            for($i=0;$i < count($this->input->post('id_barang_do_konfirm'));$i++){
              //for($j=0;$j < count($this->input->post('konfirm_do')[$i]);$j++){}
                $dataKonfirm = array(
                    'return_claim' => $this->input->post('return_do_rusak')[$i]
                    );
                $key = array(
                    'no_do'=>$this->input->post('no_do_konfirm'),
                    'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]
                    );
                $this->db->where($key)
                ->update('trans_do_detail',$dataKonfirm);
                if ($this->input->post('return_do_rusak')[$i] > 0) {

                  $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]);

                  $stok_avl = $this->Deliveryorder_model->cek_data($keycek,'barang_stock');
                  $qty_avl = $stok_avl->qty_avl-$this->input->post('return_do_rusak')[$i];
                  $qty_stock = $stok_avl->qty_stock-$this->input->post('return_do_rusak')[$i];
                  $qty_rusak = $stok_avl->qty_rusak+$this->input->post('return_do_rusak')[$i];
                  $this->db->where($keycek);
                  $this->db->update('barang_stock',
                  array(
                    'qty_avl'=>$stok_avl->qty_avl-$this->input->post('return_do_rusak')[$i],
                    'qty_stock'=>$stok_avl->qty_stock-$this->input->post('return_do_rusak')[$i],
                    'qty_rusak'=>$stok_avl->qty_rusak+$this->input->post('return_do_rusak')[$i],
                    )
                  );

                }
            }
            $this->db->update('trans_do_header',array('konfirm_do'=>'SUDAH'),array('no_do'=>$this->input->post('no_do_konfirm')));
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $result['type'] = "error";
                $result['pesan'] = "Gagal simpan data";
            }else{
                $this->db->trans_commit();
                $result['type'] = "success";
                $result['pesan'] = "Sukses simpan data";
            }
        }

        echo json_encode($result);
    }

    public function savekonfirmdo(){
      $session = $this->session->userdata('app_session');
      $no_invoice = $this->input->post("no_invoice");
      $header = $this->Invoice_model->order_by('no_invoice','ASC')
      ->find_by(array('no_invoice' => $no_invoice));
      $cekclose = $this->input->post("cekclose");
      $id_barang_arr = $this->input->post("id_barang_do_konfirm");
      $ganti = $this->input->post("ganti");
      $qty = $this->input->post("qty");
      $uang = $this->input->post("uang");
      $no_retur = $this->Returklaim_model->generate_noretur($this->auth->user_cab(),date("Y-m-d"));
      $this->db->trans_begin();
        //$kosong = 0;
        //$kosong_a = 0;
        $header_retur = array(
          'no_retur' => $no_retur,
          'tgl_retur' => date("Y-m-d"),
          'status' => "RETUR KLAIM",
          'keterangan' => "-",
          'no_invoice' => $no_invoice,
          'id_customer' => $header->id_customer,
          'nm_customer' => $header->nm_customer,
          'alamat_customer' => $header->alamatcustomer,
          'id_salesman' => $header->id_salesman,
          'nm_salesman' => $header->nm_salesman,
          'created_by' => $this->auth->user_id(),
          'created_on' => date("Y-m-d H:i:s")
        );

        $detail_retur = array();
        $detail_barang = array();
        $data_adj_trans = array();
        $where_barang = array();
        $update_barang = array();
        $total_nilai_ganti_barang = 0;
        $total_nilai_ganti_uang = 0;
        $total_nilai_jurnal = 0;
        for($i=0;$i < count($id_barang_arr);$i++){
          //AMBIL DATA BARANG
          $ambil_barang = $this->db->where(array('id_barang'=>$id_barang_arr[$i],'kdcab'=>$this->auth->user_cab()))->get('barang_stock')->row();

          //START PROSES
            if(empty($cekclose[$i])){
              $ganti_input = $ganti[$i];
              $qty_input = $qty[$i];
              $uang_input = $uang[$i];
              $detail_retur[$i] = array(
                'no_retur' => $no_retur,
                'id_barang' => $id_barang_arr[$i],
                'nm_barang' => $this->input->post("nm_barang_do_konfirm")[$i],
                'qty_retur' => ($ganti[$i]+$qty[$i]),
                'harga_landed' => $ambil_barang->landed_cost,
                'ganti' => $ganti[$i],
                'qty_uang' => $qty[$i],
                'uang' => $uang[$i],
                'qty_total' => $ganti[$i]+$qty[$i],
              );

              /*

              //UPDATE BARANG DATANG RUSAK
              $where_barang 	    = array(
                'id_barang'			  => $id_barang_arr[$i],
                'kdcab'			  	  => $this->auth->user_cab(),
              );
              $update_barang 	= array(
                'qty_rusak' => $ambil_barang->qty_rusak+$ganti[$i]+$qty[$i]
              );
              $this->db->where($where_barang)->update('barang_stock',$update_barang);

              //JIKA ADA BARANG YANG DIGANTI
              if ($ganti[$i]>0) {
                //CREATE HISTORI BARANG STOK KE KARTU STOK
                $id_st 			= $this->Trans_stock_model->gen_st($this->auth->user_cab()).$i;
                $data_adj_trans[$i] 	= array(
                  'id_st'				    => $id_st,
                  'tipe'				    => 'OUT',
                  'jenis_trans'		  => 'OUT_RETUR',
                  'noreff'		  	  => $no_retur,
                  'id_barang'			  => $id_barang_arr[$i],
                  'nm_barang'			  => $this->input->post("nm_barang_do_konfirm")[$i],
                  'kdcab'			  	  => $this->auth->user_cab(),
                  'date_stock'		  => date('Y-m-d H:i:s'),
                  'qty'			    	  => $ganti[$i],
                  'nilai_barang'	  => $ambil_barang->landed_cost,
                  'notes'			  	  => 'RK',
                  'qty_stock_awal'	=> $ambil_barang->qty_stock,
                  'qty_avl_awal' 		=> $ambil_barang->qty_avl,
                  'qty_stock_akhir'	=> $ambil_barang->qty_stock-$ganti[$i],
                  'qty_avl_akhir' 	=> $ambil_barang->qty_avl-$ganti[$i],
                );

                $where_barang 	    = array(
                  'id_barang'			  => $id_barang_arr[$i],
                  'kdcab'			  	  => $this->auth->user_cab(),
                );
                $update_barang 	= array(
                  'qty_stock'	=> $ambil_barang->qty_stock-$ganti[$i],
                  'qty_avl' 	=> $ambil_barang->qty_avl-$ganti[$i],
                );
                $this->db->where($where_barang)->update('barang_stock',$update_barang);*/

                //HITUNG TOTAL NILAI BARANG RUSAK MASUK DARI GANTI BARANG
                $total_nilai_ganti_barang += ($ganti[$i]*$ambil_barang->landed_cost);
              }
              elseif (!empty($cekclose[$i])) {
                // code...
              }

        }


        $this->db->insert('trans_retur_header',$header_retur);
  			$this->db->insert_batch('trans_retur_detail',$detail_retur);
        //$this->db->insert_batch('barang_stock_transaksi',$data_adj_trans);

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $result['type'] = "error";
                $result['pesan'] = "Gagal simpan data";
            }else{
                $this->db->trans_commit();
                $result['type'] = "success";
                $result['pesan'] = "Sukses simpan data";
                //if ($total_nilai_jurnal>0) {
                  $result['no_retur'] = $no_retur;
                //}
            }


        echo json_encode($result);
    }

    public function saveheaderrk(){
      $session = $this->session->userdata('app_session');
      $no_retur = $this->input->post("no_rk");
      $tgl_retur = $this->input->post("tgl_retur");
      $tgl_kirim = $this->input->post("tgl_kirim");
      $header = $this->db->where(array('no_retur'=>$no_retur))->get('trans_retur_header')->row();
      $detail = $this->db->where(array('no_retur'=>$no_retur))->get('trans_retur_detail')->result();
      $this->db->trans_begin();
      $this->db->where(array('no_retur'=>$no_retur))->update('trans_retur_header',array('tgl_retur'=>$tgl_retur,'tgl_kirim'=>$tgl_kirim));
        $detail_retur = array();
        $detail_barang = array();
        $data_adj_trans = array();
        $where_barang = array();
        $update_barang = array();
        $total_nilai_ganti_barang = 0;
        $total_nilai_ganti_uang = 0;
        $total_nilai_jurnal = 0;
        $i = 0;
        foreach ($detail as $key => $value) {
          $ambil_barang = $this->db->where(array('id_barang'=>$value->id_barang,'kdcab'=>$this->auth->user_cab()))->get('barang_stock')->row();
          //UPDATE BARANG DATANG RUSAK
          $where_barang 	    = array(
            'id_barang'			  => $value->id_barang,
            'kdcab'			  	  => $this->auth->user_cab(),
          );
          $update_barang 	= array(
            'qty_rusak' => $ambil_barang->qty_rusak+$value->ganti+$value->qty_uang
          );
          $this->db->where($where_barang)->update('barang_stock',$update_barang);

          //JIKA ADA BARANG YANG DIGANTI
          if ($value->ganti > 0) {
            //CREATE HISTORI BARANG STOK KE KARTU STOK
            $id_st 			= $this->Trans_stock_model->gen_st($this->auth->user_cab()).$i;
            $data_adj_trans[$i] 	= array(
              'id_st'				    => $id_st,
              'tipe'				    => 'OUT',
              'jenis_trans'		  => 'OUT_RETUR',
              'noreff'		  	  => $value->no_retur,
              'id_barang'			  => $value->id_barang,
              'nm_barang'			  => $value->nm_barang,
              'kdcab'			  	  => $this->auth->user_cab(),
              'date_stock'		  => date('Y-m-d H:i:s'),
              'qty'			    	  => $value->ganti,
              'nilai_barang'	  => $ambil_barang->landed_cost,
              'notes'			  	  => 'RK',
              'qty_stock_awal'	=> $ambil_barang->qty_stock,
              'qty_avl_awal' 		=> $ambil_barang->qty_avl,
              'qty_stock_akhir'	=> $ambil_barang->qty_stock-$value->ganti,
              'qty_avl_akhir' 	=> $ambil_barang->qty_avl-$value->ganti,
            );

            $where_barang 	    = array(
              'id_barang'			  => $id_barang_arr[$i],
              'kdcab'			  	  => $this->auth->user_cab(),
            );
            $update_barang 	= array(
              'qty_stock'	=> $ambil_barang->qty_stock-$value->ganti,
              'qty_avl' 	=> $ambil_barang->qty_avl-$value->ganti,
            );
            $this->db->where($where_barang)->update('barang_stock',$update_barang);

            //HITUNG TOTAL NILAI BARANG RUSAK MASUK DARI GANTI BARANG
            $total_nilai_ganti_barang += ($value->ganti*$ambil_barang->landed_cost);
            if ($value->qty_uang > 0) {
              $total_nilai_ganti_uang += ($value->qty_uang*$value->uang);
            }
            $total_retur += (($value->ganti+$value->qty_uang)*$ambil_barang->landed_cost);
            $total_nilai_jurnal += $total_nilai_ganti_barang + $total_nilai_ganti_uang;
          }
          $i++;
        }



        //DATA JURNAL HEADER
        $Nomor_JV				= $this->Jurnal_model->get_Nomor_Jurnal_Memorial($this->auth->user_cab(),date('Y-m-d'));
    		$Keterangan_JV	= 'Retur#INV '.$no_retur.'#'.$header->nm_customer;

    		$dataJVhead = array(
    			'nomor' 	    	=> $Nomor_JV,
    			'tgl'	         	=> date('Y-m-d'),
    			'jml'	          => $total_nilai_jurnal,
    			'koreksi_no'		=> '',
    			'kdcab'				  => $this->auth->user_cab(),
    			'jenis'			    => 'V',
    			'keterangan' 		=> $Keterangan_JV,
    			'bulan'				  => date('n'),
    			'tahun'				  => date('Y'),
    			'user_id'			  => $this->auth->user_id(),
    			'memo'			    => '',
    			'tgl_jvkoreksi'	=> date('Y-m-d'),
    			'ho_valid'			=> ''
    		);

    		$det_Jurnal				  = array();
    		$det_Jurnal[0]			= array(
    			  'nomor'         => $Nomor_JV,
    			  'tanggal'       => date('Y-m-d'),
    			  'tipe'          => 'JV',
    			  'no_perkiraan'  => '1105-01-01',
    			  'keterangan'    => $Keterangan_JV,
    			  'no_reff'       => $value->no_retur,
    			  //'debet'         => $total_nilai_ganti_barang,
    			  //'kredit'        => 0

    		);
    		$det_Jurnal[1]			= array(
    			  'nomor'         => $Nomor_JV,
    			  'tanggal'       => $Tgl_Jurnal,
    			  'tipe'          => 'JV',
    			  'no_perkiraan'  => '5201-01-01',
    			  'keterangan'    => $Keterangan_JV,
    			  'no_reff'       => $value->no_retur,
    			  //'debet'         => 0,
    			  //'kredit'        => $total_nilai_ganti_barang

    		);

        if ($total_retur < $total_nilai_jurnal) {
          $det_Jurnal[0]['kredit'] = $total_nilai_jurnal - $total_retur;
          $det_Jurnal[0]['debet'] = 0;
          $det_Jurnal[1]['debet'] = $total_nilai_jurnal - $total_retur;
          $det_Jurnal[1]['kredit'] = 0;
        }else {
          $det_Jurnal[1]['kredit'] = $total_retur - $total_nilai_jurnal;
          $det_Jurnal[1]['debet'] = 0;
          $det_Jurnal[0]['debet'] = $total_retur - $total_nilai_jurnal;
          $det_Jurnal[0]['kredit'] = 0;
        }

    		$this->db->insert('javh',$dataJVhead);
    		$this->db->insert_batch('jurnal',$det_Jurnal);
  		  $Update_JV = $this->Jurnal_model->update_Nomor_Jurnal($this->auth->user_cab(),'JM');
        if ($value->ganti > 0) {
          $this->db->insert_batch('barang_stock_transaksi',$data_adj_trans);
        }

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $result['type'] = "error";
                $result['pesan'] = "Gagal simpan data";
            }else{
                $this->db->trans_commit();
                $result['type'] = "success";
                $result['status'] = 1;
                $result['pesan'] = "Sukses simpan data SJR";
                if ($total_nilai_jurnal>0) {
                  $result['no_retur'] = $no_retur;
                }
            }


        echo json_encode($result);
    }

    public function cancel($n){
      $session = $this->session->userdata('app_session');
      $no_retur = $n;
      $this->db->delete('trans_retur_header',array('no_retur'=>$no_retur));
      $this->db->delete('trans_retur_detail',array('no_retur'=>$no_retur));

      $data = $this->Invoice_model->order_by('no_invoice','ASC')->find_all_by(array('kdcab' => $this->auth->user_cab(), 'flag_cancel'=>'N'));

      $this->template->set('results', $data);
      $this->template->title('Return DO Claim');
      $this->template->render('index');
    }

    public function csjr(){
      $session = $this->session->userdata('app_session');
      $no_retur = $this->input->get("n");
      $ambil_rk_head = $this->db->where(array('no_retur'=>$no_retur))->get("trans_retur_header")->row();
      $ambil_rk_detail = $this->db->where(array('no_retur'=>$no_retur))->get("trans_retur_detail")->result();


        $this->template->set('ambil_rk_head',$ambil_rk_head);
        $this->template->set('ambil_rk_detail',$ambil_rk_detail);


        $this->template->title('Input Surat Jalan Retur');
        $this->template->render('SJR_RETUR');
    }

    function print_sj($noso){
      $uk1 = 9;
      $ukk = 17;
      $ukkk = 11;
      $no_so = $noso;
      $mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
      $mpdf->SetImportUse();

        $head = $this->db->where(array('no_retur'=>$noso))->get('trans_retur_header')->row();
        $cabang = $this->db->where(array('kdcab'=>$this->auth->user_cab()))->get('cabang')->row();
        $detail = $this->db->where(array('no_retur'=>$noso))->get('trans_retur_detail')->result();

        $this->template->set('head', $head);
        $this->template->set('cabang', $cabang);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data_custom',$data);


        $tglprint = date("d-m-Y H:i:s");
        $header = '<table width="100%" border="0" id="header-tabel">
                    <tr>
                      <th width="30%" style="text-align: left;">
                        <img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
                      </th>
                      <th colspan="3" style="border-right: none;text-align: center;padding-left:0% !important;margin-left:-10px !important" width="100%">SURAT JALAN RETUR (SJR)<br>'.@$head->no_retur.'</th>
                      <th colspan="4" style="border-left: none;"></th>
                    </tr>
                    </table>
                    <hr style="padding:0;margin:0">
                    <table width="100%" border="0" id="header-tabel" style="font-size:10.5pt">
                    <tr>
                        <td width="10%">SALES</td>
                        <td width="10%">: '.strtoupper(@$head->nm_salesman).'</td>
                        <td colspan="2"></td>

                        <td width="15%">'.@$cabang->namacabang.',</td>
                        <td colspan="2">'.date('d-M-Y',strtotime(@$head->tgl_kirim)).'</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td colspan="2"></td>

                        <td colspan="2" width="8%">Kepada Yth,</td>

                    </tr>
                    <tr>
                        <td width="10%" valign="top">KETERANGAN</td>
                        <td width="1%" valign="top">:'.strtoupper(@$head->keterangan).'</td>
                        <td colspan="2"></td>
                        <td width="10%" colspan="4">'.strtoupper(@$head->nm_customer).'<br>'.@$customer->alamat.'</td>
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
                'sheet-size'=> [210,148],
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


    /*
    public function savekonfirmdo(){
        $dataKonfirm = array(
            'konfirm_do' => $this->input->post('STKF'),
            'jumlah_return' => $this->input->post('RET')
            );
        $this->db->trans_begin();
        $this->db->update('trans_do_header',$dataKonfirm,array('no_do'=>$this->input->post('NODO')));
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $result['type'] = "error";
            $result['pesan'] = "Gagal simpan data";
        }else{
            $this->db->trans_commit();
            $result['type'] = "success";
            $result['pesan'] = "Sukses simpan data";
        }
        echo json_encode($result);
    }
    */

    function print_request_old($nodo){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
        $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$do_data->id_customer),'customer');
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $nodo));

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
