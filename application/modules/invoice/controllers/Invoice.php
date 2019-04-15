<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Invoice extends Admin_Controller {

    //Permission
    /*
    protected $viewPermission   = "Deliveryorder.View";
    protected $addPermission    = "Deliveryorder.Add";
    protected $managePermission = "Deliveryorder.Manage";
    protected $deletePermission = "Deliveryorder.Delete";
    */
    public function __construct(){
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Customer/Customer_model',
                                 'Deliveryorder_2/Deliveryorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Invoice');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index(){
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Invoice_model->order_by('no_invoice','DESC')->find_all_by(array('LEFT(no_invoice,3)'=>$session['kdcab']));
        $this->template->set('results', $data);
        $this->template->title('Invoice');
        $this->template->render('list');
    }

    //Create New Invoice
    public function create(){
        $session = $this->session->userdata('app_session');
        $customer = $this->Customer_model->find_all_by(array('kdcab'=>$session{'kdcab'}));
        $this->template->set('customer',$customer);
        $kode_customer		= '';

  		$Arr_Where			= array(
  			'status'		=> 'DO',
        'konfirm_do'  => 'SUDAH',
        'LEFT(no_do,3)'=>$session['kdcab']
  		);
  		if($this->input->post()){
  			$kode_customer		= $this->input->post('idcustomer');
  			if(!empty($kode_customer)){
  				$Arr_Where['id_customer']	= $kode_customer;
  			}
  		}

        //$data = $this->Deliveryorder_model->order_by('no_do','ASC')->find_all();
        $data = $this->Deliveryorder_model->order_by('no_do','DESC')->find_all_by($Arr_Where);

        $this->template->set('kode_customer', $kode_customer);
        $this->template->set('results', $data);
        $this->template->title('Input Invoice');
        $this->template->render('list_do');
    }

     //Create New Invoice
    public function proses(){
    	if($this->input->post()){
    			$customer		      	= $this->input->post('cekcustomer');
    			$getparamdo 		  	= explode(";",$this->input->post('cekcus'));
    			$data_customer			= $this->Customer_model->find_data('customer',$customer,'id_customer');
  			  //$header		= $this->db->get_where('trans_do_header',array('no_do'=>$param))->result();
    			$Arr_Data		      	= array();
  			$this->db->where_in('no_do',$getparamdo);
    			$headerdo         		= $this->db->get('trans_do_header')->result_array();
    			$customer_all    		= $this->Customer_model->find_all_by(array('deleted'=>0));
    			$Faktur_header	  		= $this->Invoice_model->getFakturMaster();

    			foreach($headerdo as $key=>$vals){
    				$Arr_Data[$key]					= $vals;
    				$details						= $this->db->join("trans_so_header","trans_so_header.no_so = trans_do_detail.no_so","left")->get_where('trans_do_detail',array('no_do'=>$vals['no_do'],'qty_supply != '=>0))->result_array();
    				$Arr_Data[$key]['detail_data']	= $details;
    			}

    			$this->template->set('data_cust', $data_customer);
  			  $this->template->set('customer', $customer_all);
    			$this->template->set('faktur', $Faktur_header);
    			$this->template->set('records', $Arr_Data);
  			  //$this->template->set('header', $header);
    			$this->template->title('Input Invoice');
    			$this->template->render('invoice_form');
    	}else{
    			$this->template->render('list_do');
  		}

    }

    function get_customer(){
        $idcus = $_GET['idcus'];
        $customer = $this->Invoice_model->get_customer($idcus)->row();

        echo json_encode($customer);
    }

  	function jatuhtempo(){
  	  $tglnow   = $_GET['idcus'];
      $jangka_waktu = strtotime('+45 days', strtotime($tglnow));// jangka waktu + 365 hari
  	  $jthtempo=date("Y-m-d",$jangka_waktu);//tanggal expired

  	   $expired[tgl]  = $jthtempo;
       echo json_encode($expired);
    }
    function saveheaderinvoice(){
		if($this->input->post()){
			$nd       		  	= $this->input->post('nd');
			$detail_do		  	= $this->input->post('det_do');
			$custid				= $this->input->post('idcustomer_do');
			$custname			= $this->input->post('nmcustomer_do');
			$alamat			    = $this->input->post('alamat');
			$npwp			   	= $this->input->post('npwp');
			$alamat_npwp	 	= $this->input->post('alamat_npwp');

			$tgl_expired		= $this->input->post('tgljthtempo');
			$kefak				= $this->input->post('kode_faktur');
			$sts_materai		= $this->input->post('materai');
			$id_salesman		= $this->input->post('id_salesman');
			$nm_salesman		= $this->input->post('nm_salesman');
			$nofaktur			= '';
			$OK					= 1;

  			$faktur_data	  	= $this->Invoice_model->getFakturAktif();

  			if($faktur_data['hasil'] != '1'){
  				$OK			= 0;
  			}else{
  				$Kode_Gen		= $faktur_data['data']['idgen'];
  				$fakturid		= $faktur_data['data']['no_faktur'];
  			}

  			if($OK==1){
  				$Tgl_Invoice			= $this->input->post('tanggal_invoice');
				$Bulan_Invoice			= date('n',strtotime($Tgl_Invoice));
				$Tahun_Invoice			= date('Y',strtotime($Tgl_Invoice));
  				$session 				= $this->session->userdata('app_session');
				$no_invoice 			= $this->Invoice_model->generate_noinv($session['kdcab'],$Tgl_Invoice);
  				$customer 				= $this->Invoice_model->cek_data(array('id_customer'=>$custid),'customer');


  				$nofaktur			= $kefak.'.'.$fakturid;
  				$Arr_Update			= array(
  					'sts'				=> '1',
  					'nofaktur'			=> $nofaktur,
  					'tglfaktur'			=> $this->input->post('tanggal_invoice'),
  					'tglinvoice'		=> $this->input->post('tanggal_invoice'),
  					'noinvoice'			=> $no_invoice,
  					'tanggal_generate'	=> date('Y-m-d H:i:s')
  				);

  				$Biaya_Materai			= 0;
  				if($sts_materai=='Y'){
  					$Biaya_Materai		= 6000;
  				}

				if($this->input->post('n_ppn') > 0){
  					$faktur_pajak		= $nofaktur;
  				}else{
  					$faktur_pajak		= '';
  				}
  				$Arr_detail				= array();
  				//$Total_DPP	      = $Total_Diskon	= $Total_After	= $PPN	= $Total_landed = $Harga_tahap_1 = $Harga_tahap_2 = $Harga_tahap_3 = $Total_Diskon_item= 0;
  				//$DO_Detail			  = $this->Invoice_model->get_where_in('no_do',$detail_do,'trans_do_detail');
  				$Awal					    =  0;
				$detail_inv_post = $this->input->post('id');
				for ($i = 0; $i<count($detail_inv_post); $i++) {
					$dbdo 				= $this->Invoice_model->cek_data(array('id_barang'=>$this->input->post('id_barang')[$i]),'barang_master');
					$landed_stock		= $this->Invoice_model->cek_data(array('id_barang'=>$this->input->post('id_barang')[$i]),'barang_stock');
					$landed_cost		= $this->input->post('jumlah')[$i] * $landed_stock->landed_cost;
					$Total_landed		+= $landed_cost;
					$Awal++;
					$Arr_detail[$Awal]['no_invoice']					= $no_invoice;
					$Arr_detail[$Awal]['id_barang']					    = $this->input->post('id_barang')[$i];
					$Arr_detail[$Awal]['nm_barang']					  	= $this->input->post('nm_barang')[$i];
					$Arr_detail[$Awal]['jumlah']						= $this->input->post('jumlah')[$i];
					$Arr_detail[$Awal]['satuan']					    = $dbdo->satuan;
					$Arr_detail[$Awal]['hargajual']					    = $this->input->post('hargajual')[$i];
					$Arr_detail[$Awal]['persen_diskon_stdr']		    = $this->input->post('persen_diskon_stdr')[$i];
					$Arr_detail[$Awal]['harga_after_diskon_stdr']	   	= $this->input->post('harga_after_diskon_stdr')[$i];
					$Arr_detail[$Awal]['diskon_promo_persen']		    = $this->input->post('diskon_promo_persen')[$i];
					$Arr_detail[$Awal]['diskon_promo_persen_rpnya']	 	= $this->input->post('diskon_promo_persen_rpnya')[$i];
					$Arr_detail[$Awal]['harga_nett_dari_so']		    = $this->input->post('harga_nett_dari_so')[$i];
					$Arr_detail[$Awal]['harga_nett']				    = $this->input->post('harga_nett')[$i];


					$Arr_detail[$Awal]['subtot_bef_diskon']			    = $this->input->post('subtot_bef_diskon')[$i];
					$Arr_detail[$Awal]['subtot_after_diskon']		    = $this->input->post('subtot_after_diskon')[$i];

					$Arr_detail[$Awal]['tgljual']				    	= $this->input->post('tgljual')[$i];
					$Arr_detail[$Awal]['ppn']						    = $this->input->post('ppn')[$i];
					$Arr_detail[$Awal]['no_do']					        = $this->input->post('no_do')[$i];
					$Arr_detail[$Awal]['diskon_so']			            = $this->input->post('diskon_so')[$i];
					$Arr_detail[$Awal]['tipe_diskon_so']       			= $this->input->post('tipe_diskon_so')[$i];
					$Arr_detail[$Awal]['hargalanded']				    = $this->input->post('hargalanded')[$i];
					//$Arr_detail[$Awal]['diskon']						= $discount_satuan;
					//$Arr_detail[$Awal]['bonus']				        = $this->input->post('bonus')[$key];
				}

  				$headerinv = array(
  					'no_invoice' 		     	=> $no_invoice,
  					'kdcab' 			      	=> $session['kdcab'],
  					'id_customer'	 	      	=> $this->input->post('id_customer'),
  					'nm_customer' 		      	=> $this->input->post('nm_customer'),
  					'tanggal_invoice'      		=> $this->input->post('tanggal_invoice'),
  					'id_salesman'	         	=> $this->input->post('id_salesman'),
  					'nm_salesman' 		     	=> $this->input->post('nm_salesman'),
  					'nofakturpajak' 	      	=> $faktur_pajak,
  					'tglfakturpajak' 	      	=> $this->input->post('tanggal_invoice'),
  					'tgljatuhtempo' 	      	=> $this->input->post('tgljatuhtempo'),
  					'alamatcustomer' 	      	=> $this->input->post('alamatcustomer'),
  					'npwpcustomer' 		      	=> $this->input->post('npwpcustomer'),
  					'diskon_toko_persen'    	=> $this->input->post('diskon_toko_persen'),
  					'diskon_toko_rp'	      	=> $this->input->post('dpp')*$this->input->post('diskon_toko_persen')/100,
  					'diskon_cash_persen'    	=> $this->input->post('diskon_cash_persen'),
  					'diskon_cash_rp'        	=> $this->input->post('dpp')*$this->input->post('diskon_cash_persen')/100,
  					'hargajualbefdis'	      	=> $this->input->post('hargajualbefdis'),
  					'hargajualafterdis'	    	=> $this->input->post('hargajualafterdis'),
  					'hargajualafterdistoko'		=> $this->input->post('hargajualafterdistoko'),
  					'hargajualafterdiscash'		=> $this->input->post('hargajualafterdiscash'),
  					'diskon_stdr_rp'	      	=> $this->input->post('diskon_stdr_rp'),
  					//'diskontotal'		        => $this->input->post('diskontotal'),
  					'dpp'				        => $this->input->post('dpp'),
  					'ppn'				        => $this->input->post('n_ppn'),
  					'meterai'			        => $Biaya_Materai,
  					'hargajualtotal'	      	=> $this->input->post('hargajualtotal'),
  					'piutang'			        => $this->input->post('hargajualtotal'),
  					'hargalandedtotal'     		=> $Total_landed,
  				);


				## ACCOUNT RECEIVABLE ##
				$Total_Inv				= $this->input->post('hargajualtotal');
				$Bulan_Sekarang			= date('n');
				$Tahun_Sekarang			= date('Y');
				$Beda_Bulan				= (($Tahun_Sekarang - $Tahun_Invoice) * 12) + ($Bulan_Sekarang - $Bulan_Invoice);
				if($Beda_Bulan < 1){
					$Beda_Bulan			= 0;
				}
				$dataAR					= array();
				$intL				   	= 0;
				$Saldo_Awal			= 0;
				$Kredit					= 0;
				$Debet					= $Total_Inv;
				$Saldo_Akhir		= $Total_Inv;
				for($x=0;$x<=$Beda_Bulan;$x++){
					$intL++;
					$Bulan_Proses		= date('n',mktime(0,0,0,$Bulan_Invoice + $x,1,$Tahun_Invoice));
					$Tahun_Proses		= date('Y',mktime(0,0,0,$Bulan_Invoice + $x,1,$Tahun_Invoice));
					if($intL > 1){
						$Debet			= 0;
						$Saldo_Awal		= $Total_Inv;
					}
					$dataAR[$x] 	= array(
						'no_invoice' 		=> $no_invoice,
						'tgl_invoice'		=> $Tgl_Invoice,
						'customer_code'	=> $this->input->post('id_customer'),
						'customer' 			=> $this->input->post('nm_customer'),
						'bln'				    => $Bulan_Proses,
						'thn'				    => $Tahun_Invoice,
						'saldo_awal' 		=> $Saldo_Awal, //nilai invoice
						'debet'				  => $Debet,
						'kredit'			  => $Kredit,
						'saldo_akhir'		=> $Saldo_Akhir, //nilai invoice
						'kdcab'				  => $session['kdcab']
  					);
				}



				## NOMOR JV ##
				$Nomor_JV				= $this->Invoice_model->get_Nomor_Jurnal_Sales($session['kdcab'],$Tgl_Invoice);

				$Total_DPP				= $Total_Inv;
				$Keterangan_INV		= 'PENJUALAN A/N '.$this->input->post('nm_customer').' INV NO. '.$no_invoice;
				$COA_Sales				= '4201-01-01';
				$dataJVhead = array(
  					'nomor' 	    	=> $Nomor_JV,
  					'tgl'	         	=> $this->input->post('tanggal_invoice'),
  					'jml'	          => $Total_Inv,
  					'koreksi_no'		=> '',
  					'kdcab'				  => $session['kdcab'],
  					'jenis'			    => 'V',
  					'keterangan' 		=> $Keterangan_INV,
					'bulan'				    => $Bulan_Invoice,
  					'tahun'				  => $Tahun_Invoice,
  					'user_id'			  => $session['id_user'],
  					'memo'			    => '',
  					'tgl_jvkoreksi'	=> $Tgl_Invoice,
  					'ho_valid'			=> ''
  				);

				$det_Jurnal				= array();
				$det_Jurnal[]			= array(
					  'nomor'         => $Nomor_JV,
					  'tanggal'       => $Tgl_Invoice,
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '1104-01-01',
					  'keterangan'    => $Keterangan_INV,
					  'no_reff'       => $no_invoice,
					  'debet'         => $Total_Inv,
					  'kredit'        => 0

				);
				if($this->input->post('n_ppn') > 0){
					$Total_DPP				= $Total_Inv - $this->input->post('n_ppn');
					$COA_Sales				= '4101-01-01';
					$det_Jurnal[]			= array(
						  'nomor'         => $Nomor_JV,
						  'tanggal'       => $Tgl_Invoice,
						  'tipe'          => 'JV',
						  'no_perkiraan'  => '2107-04-01',
						  'keterangan'    => 'PPN A/N '.$this->input->post('nm_customer').' INV NO. '.$no_invoice,
						  'no_reff'       => $no_invoice,
						  'debet'         => 0,
						  'kredit'        => $this->input->post('n_ppn')

					);
				}
				$det_Jurnal[]			  = array(
					  'nomor'         => $Nomor_JV,
					  'tanggal'       => $Tgl_Invoice,
					  'tipe'          => 'JV',
					  'no_perkiraan'  => $COA_Sales,
					  'keterangan'    => $Keterangan_INV,
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $Total_DPP

				);

				/*

				if($this->input->post('n_ppn') == 0){
					$datajurnal_2 = array(
					  'nomor'         => $this->Invoice_model->generate_nojv($session['kdcab']),
					  'tanggal'       => $this->input->post('tanggal_invoice'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '4201-01-01',
					  'keterangan'    => 'Penjualan Kotor Non PPN #'.$no_invoice.'#'.$this->input->post('nm_customer'),
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $this->input->post('hargajualtotal')
					);
  				}else {
					$datajurnal_2 = array(
					  'nomor'         => $this->Invoice_model->generate_nojv($session['kdcab']),
					  'tanggal'       => $this->input->post('tanggal_invoice'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '4101-01-01',
					  'keterangan'    => 'Penjualan Invoice #'.$no_invoice.'#'.$this->input->post('nm_customer'),
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $this->input->post('dpp')
					);
					$datajurnal_3 = array(
					  'nomor'         => $this->Invoice_model->generate_nojv($session['kdcab']),
					  'tanggal'       => $this->input->post('tanggal_invoice'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '2107-04-01',
					  'keterangan'    => 'PPN K Penjualan Invoice #'.$no_invoice.'#'.$this->input->post('nm_customer'),
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $this->input->post('n_ppn')
					);

				}
				*/


  				$Kode_Proses			   = implode("','",$detail_do);

  				$Qry_Update_DO			 = "UPDATE trans_do_header SET status='INV',no_invoice='$no_invoice' WHERE no_do IN ('".$Kode_Proses."')";
  				$Qry_Update_Cabang	 = "UPDATE cabang SET no_invoice=no_invoice + 1 WHERE kdcab='".$session['kdcab']."'";

  				$this->db->trans_begin();
  				$this->db->query($Qry_Update_Cabang);
  				$this->db->query($Qry_Update_DO);
  				if($this->input->post('ppn') > 0){
  					$this->db->update('faktur_detail',$Arr_Update,array('idgen'=>$Kode_Gen,'fakturid'=>$fakturid));
  				}
  				$this->db->insert_batch('trans_invoice_detail',$Arr_detail);
  				$this->db->insert('trans_invoice_header',$headerinv);


				## INSERT JURNAL ##
				$this->db->insert('javh',$dataJVhead);
				$this->db->insert_batch('jurnal',$det_Jurnal);

				## INSERT ACCOUNT RECEIVABLE  ##
				$this->db->insert_batch('ar',$dataAR);

				$Qry_Update_Cabang_acc	 = "UPDATE pastibisa_tb_cabang SET noJS=noJS + 1 WHERE nocab='".$session['kdcab']."'";
				$this->db->query($Qry_Update_Cabang_acc);

  				if($this->db->trans_status() === FALSE){
  					 $this->db->trans_rollback();
  					 $Arr_Return		= array(
  							'status'		=> 2,
  							 'pesan'		=> 'Gagal simpan data'
  					   );
  				}else{
  					 $this->db->trans_commit();
  					 $Arr_Return		= array(
  						'status'		=> 1,
  						 'pesan'			=> 'Sukses simpan data'
  				   );
  				}
  			}else{
  				$Arr_Return		= array(
  					'status'		=> 2,
  					'pesan'			=> $faktur_data['pesan']
  			   );
  			}
  		}else{
  			$Arr_Return		= array(
  				'status'		=> 3,
  				'pesan'			=> 'No Record Was Found To Process. Please Try Again...'
  		   );
  		}

          echo json_encode($Arr_Return);


    }
    function saveheaderinvoice_old(){
		if($this->input->post()){
			$nd       		  	= $this->input->post('nd');
			$detail_do		  	= $this->input->post('det_do');
			$custid				= $this->input->post('idcustomer_do');
			$custname			= $this->input->post('nmcustomer_do');
			$alamat			    = $this->input->post('alamat');
			$npwp			   	= $this->input->post('npwp');
			$alamat_npwp	 	= $this->input->post('alamat_npwp');

			$tgl_expired		= $this->input->post('tgljthtempo');
			$kefak				= $this->input->post('kode_faktur');
			$sts_materai		= $this->input->post('materai');
			$id_salesman		= $this->input->post('id_salesman');
			$nm_salesman		= $this->input->post('nm_salesman');
			$nofaktur			= '';
			$OK					= 1;

  			$faktur_data	  	= $this->Invoice_model->getFakturAktif();

  			if($faktur_data['hasil'] != '1'){
  				$OK			= 0;
  			}else{
  				$Kode_Gen		= $faktur_data['data']['idgen'];
  				$fakturid		= $faktur_data['data']['no_faktur'];
  			}

  			if($OK==1){
  				$Tgl_Invoice			= $this->input->post('tanggal_invoice');
				$Bulan_Invoice			= date('n',strtotime($Tgl_Invoice));
				$Tahun_Invoice			= date('Y',strtotime($Tgl_Invoice));
  				$session 				= $this->session->userdata('app_session');
				$no_invoice 			= $this->Invoice_model->generate_noinv($session['kdcab']);
  				$customer 				= $this->Invoice_model->cek_data(array('id_customer'=>$custid),'customer');


  				$nofaktur			= $kefak.'.'.$fakturid;
  				$Arr_Update			= array(
  					'sts'				=> '1',
  					'nofaktur'			=> $nofaktur,
  					'tglfaktur'			=> $this->input->post('tanggal_invoice'),
  					'tglinvoice'		=> $this->input->post('tanggal_invoice'),
  					'noinvoice'			=> $no_invoice,
  					'tanggal_generate'	=> date('Y-m-d H:i:s')
  				);

  				$Biaya_Materai			= 0;
  				if($sts_materai=='Y'){
  					$Biaya_Materai		= 6000;
  				}

				if($this->input->post('n_ppn') > 0){
  					$faktur_pajak		= $nofaktur;
  				}else{
  					$faktur_pajak		= '';
  				}
  				$Arr_detail				= array();
  				//$Total_DPP	      = $Total_Diskon	= $Total_After	= $PPN	= $Total_landed = $Harga_tahap_1 = $Harga_tahap_2 = $Harga_tahap_3 = $Total_Diskon_item= 0;
  				//$DO_Detail			  = $this->Invoice_model->get_where_in('no_do',$detail_do,'trans_do_detail');
  				$Awal					    =  0;
				$detail_inv_post = $this->input->post('id');
				for ($i = 0; $i<count($detail_inv_post); $i++) {
					$dbdo 				= $this->Invoice_model->cek_data(array('id_barang'=>$this->input->post('id_barang')[$i]),'barang_master');
					$landed_stock		= $this->Invoice_model->cek_data(array('id_barang'=>$this->input->post('id_barang')[$i]),'barang_stock');
					$landed_cost		= $this->input->post('jumlah')[$i] * $landed_stock->landed_cost;
					$Total_landed		+= $landed_cost;
					$Awal++;
					$Arr_detail[$Awal]['no_invoice']					= $no_invoice;
					$Arr_detail[$Awal]['id_barang']					    = $this->input->post('id_barang')[$i];
					$Arr_detail[$Awal]['nm_barang']					  	= $this->input->post('nm_barang')[$i];
					$Arr_detail[$Awal]['jumlah']						= $this->input->post('jumlah')[$i];
					$Arr_detail[$Awal]['satuan']					    = $dbdo->satuan;
					$Arr_detail[$Awal]['hargajual']					    = $this->input->post('hargajual')[$i];
					$Arr_detail[$Awal]['persen_diskon_stdr']		    = $this->input->post('persen_diskon_stdr')[$i];
					$Arr_detail[$Awal]['harga_after_diskon_stdr']	   	= $this->input->post('harga_after_diskon_stdr')[$i];
					$Arr_detail[$Awal]['diskon_promo_persen']		    = $this->input->post('diskon_promo_persen')[$i];
					$Arr_detail[$Awal]['diskon_promo_persen_rpnya']	 	= $this->input->post('diskon_promo_persen_rpnya')[$i];
					$Arr_detail[$Awal]['harga_nett_dari_so']		    = $this->input->post('harga_nett_dari_so')[$i];
					$Arr_detail[$Awal]['harga_nett']				    = $this->input->post('harga_nett')[$i];


					$Arr_detail[$Awal]['subtot_bef_diskon']			    = $this->input->post('subtot_bef_diskon')[$i];
					$Arr_detail[$Awal]['subtot_after_diskon']		    = $this->input->post('subtot_after_diskon')[$i];

					$Arr_detail[$Awal]['tgljual']				    	= $this->input->post('tgljual')[$i];
					$Arr_detail[$Awal]['ppn']						    = $this->input->post('ppn')[$i];
					$Arr_detail[$Awal]['no_do']					        = $this->input->post('no_do')[$i];
					$Arr_detail[$Awal]['diskon_so']			            = $this->input->post('diskon_so')[$i];
					$Arr_detail[$Awal]['tipe_diskon_so']       			= $this->input->post('tipe_diskon_so')[$i];
					$Arr_detail[$Awal]['hargalanded']				    = $this->input->post('hargalanded')[$i];
					//$Arr_detail[$Awal]['diskon']						= $discount_satuan;
					//$Arr_detail[$Awal]['bonus']				        = $this->input->post('bonus')[$key];
				}

  				$headerinv = array(
  					'no_invoice' 		     	=> $no_invoice,
  					'kdcab' 			      	=> $session['kdcab'],
  					'id_customer'	 	      	=> $this->input->post('id_customer'),
  					'nm_customer' 		      	=> $this->input->post('nm_customer'),
  					'tanggal_invoice'      		=> $this->input->post('tanggal_invoice'),
  					'id_salesman'	         	=> $this->input->post('id_salesman'),
  					'nm_salesman' 		     	=> $this->input->post('nm_salesman'),
  					'nofakturpajak' 	      	=> $faktur_pajak,
  					'tglfakturpajak' 	      	=> $this->input->post('tanggal_invoice'),
  					'tgljatuhtempo' 	      	=> $this->input->post('tgljatuhtempo'),
  					'alamatcustomer' 	      	=> $this->input->post('alamatcustomer'),
  					'npwpcustomer' 		      	=> $this->input->post('npwpcustomer'),
  					'diskon_toko_persen'    	=> $this->input->post('diskon_toko_persen'),
  					'diskon_toko_rp'	      	=> $this->input->post('dpp')*$this->input->post('diskon_toko_persen')/100,
  					'diskon_cash_persen'    	=> $this->input->post('diskon_cash_persen'),
  					'diskon_cash_rp'        	=> $this->input->post('dpp')*$this->input->post('diskon_cash_persen')/100,
  					'hargajualbefdis'	      	=> $this->input->post('hargajualbefdis'),
  					'hargajualafterdis'	    	=> $this->input->post('hargajualafterdis'),
  					'hargajualafterdistoko'		=> $this->input->post('hargajualafterdistoko'),
  					'hargajualafterdiscash'		=> $this->input->post('hargajualafterdiscash'),
  					'diskon_stdr_rp'	      	=> $this->input->post('diskon_stdr_rp'),
  					//'diskontotal'		        => $this->input->post('diskontotal'),
  					'dpp'				        => $this->input->post('dpp'),
  					'ppn'				        => $this->input->post('n_ppn'),
  					'meterai'			        => $Biaya_Materai,
  					'hargajualtotal'	      	=> $this->input->post('hargajualtotal'),
  					'piutang'			        => $this->input->post('hargajualtotal'),
  					'hargalandedtotal'     		=> $Total_landed,
  				);


				## ACCOUNT RECEIVABLE ##
				$Total_Inv				= $this->input->post('hargajualtotal');
				$Bulan_Sekarang			= date('n');
				$Tahun_Sekarang			= date('Y');
				$Beda_Bulan				= (($Tahun_Sekarang - $Tahun_Invoice) * 12) + ($Bulan_Sekarang - $Bulan_Invoice);
				if($Beda_Bulan < 1){
					$Beda_Bulan			= 0;
				}
				$dataAR					= array();
				$intL				   	= 0;
				$Saldo_Awal			= 0;
				$Kredit					= 0;
				$Debet					= $Total_Inv;
				$Saldo_Akhir		= $Total_Inv;
				for($x=0;$x<=$Beda_Bulan;$x++){
					$intL++;
					$Bulan_Proses		= date('n',mktime(0,0,0,$Bulan_Invoice + $x,1,$Tahun_Invoice));
					$Tahun_Proses		= date('Y',mktime(0,0,0,$Bulan_Invoice + $x,1,$Tahun_Invoice));
					if($intL > 1){
						$Debet			= 0;
						$Saldo_Awal		= $Total_Inv;
					}
					$dataAR[$x] 	= array(
						'no_invoice' 		=> $no_invoice,
						'tgl_invoice'		=> $Tgl_Invoice,
						'customer_code'	=> $this->input->post('id_customer'),
						'customer' 			=> $this->input->post('nm_customer'),
						'bln'				    => $Bulan_Proses,
						'thn'				    => $Tahun_Invoice,
						'saldo_awal' 		=> $Saldo_Awal, //nilai invoice
						'debet'				  => $Debet,
						'kredit'			  => $Kredit,
						'saldo_akhir'		=> $Saldo_Akhir, //nilai invoice
						'kdcab'				  => $session['kdcab']
  					);
				}



				## NOMOR JV ##
				$Nomor_JV				= $this->Invoice_model->get_Nomor_Jurnal_Sales($session['kdcab'],$Tgl_Invoice);

				$Total_DPP				= $Total_Inv;
				$Keterangan_INV		= 'PENJUALAN A/N '.$this->input->post('nm_customer').' INV NO. '.$no_invoice;
				$COA_Sales				= '4201-01-01';
				$dataJVhead = array(
  					'nomor' 	    	=> $Nomor_JV,
  					'tgl'	         	=> $this->input->post('tanggal_invoice'),
  					'jml'	          => $Total_Inv,
  					'koreksi_no'		=> '',
  					'kdcab'				  => $session['kdcab'],
  					'jenis'			    => 'V',
  					'keterangan' 		=> $Keterangan_INV,
					'bulan'				    => $Bulan_Invoice,
  					'tahun'				  => $Tahun_Invoice,
  					'user_id'			  => $session['id_user'],
  					'memo'			    => '',
  					'tgl_jvkoreksi'	=> $Tgl_Invoice,
  					'ho_valid'			=> ''
  				);

				$det_Jurnal				= array();
				$det_Jurnal[]			= array(
					  'nomor'         => $Nomor_JV,
					  'tanggal'       => $Tgl_Invoice,
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '1104-01-01',
					  'keterangan'    => $Keterangan_INV,
					  'no_reff'       => $no_invoice,
					  'debet'         => $Total_Inv,
					  'kredit'        => 0

				);
				if($this->input->post('n_ppn') > 0){
					$Total_DPP				= $Total_Inv - $this->input->post('n_ppn');
					$COA_Sales				= '4101-01-01';
					$det_Jurnal[]			= array(
						  'nomor'         => $Nomor_JV,
						  'tanggal'       => $Tgl_Invoice,
						  'tipe'          => 'JV',
						  'no_perkiraan'  => '2107-04-01',
						  'keterangan'    => 'PPN A/N '.$this->input->post('nm_customer').' INV NO. '.$no_invoice,
						  'no_reff'       => $no_invoice,
						  'debet'         => 0,
						  'kredit'        => $this->input->post('n_ppn')

					);
				}
				$det_Jurnal[]			  = array(
					  'nomor'         => $Nomor_JV,
					  'tanggal'       => $Tgl_Invoice,
					  'tipe'          => 'JV',
					  'no_perkiraan'  => $COA_Sales,
					  'keterangan'    => $Keterangan_INV,
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $Total_DPP

				);

				/*

				if($this->input->post('n_ppn') == 0){
					$datajurnal_2 = array(
					  'nomor'         => $this->Invoice_model->generate_nojv($session['kdcab']),
					  'tanggal'       => $this->input->post('tanggal_invoice'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '4201-01-01',
					  'keterangan'    => 'Penjualan Kotor Non PPN #'.$no_invoice.'#'.$this->input->post('nm_customer'),
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $this->input->post('hargajualtotal')
					);
  				}else {
					$datajurnal_2 = array(
					  'nomor'         => $this->Invoice_model->generate_nojv($session['kdcab']),
					  'tanggal'       => $this->input->post('tanggal_invoice'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '4101-01-01',
					  'keterangan'    => 'Penjualan Invoice #'.$no_invoice.'#'.$this->input->post('nm_customer'),
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $this->input->post('dpp')
					);
					$datajurnal_3 = array(
					  'nomor'         => $this->Invoice_model->generate_nojv($session['kdcab']),
					  'tanggal'       => $this->input->post('tanggal_invoice'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '2107-04-01',
					  'keterangan'    => 'PPN K Penjualan Invoice #'.$no_invoice.'#'.$this->input->post('nm_customer'),
					  'no_reff'       => $no_invoice,
					  'debet'         => 0,
					  'kredit'        => $this->input->post('n_ppn')
					);

				}
				*/


  				$Kode_Proses			   = implode("','",$detail_do);

  				$Qry_Update_DO			 = "UPDATE trans_do_header SET status='INV',no_invoice='$no_invoice' WHERE no_do IN ('".$Kode_Proses."')";
  				$Qry_Update_Cabang	 = "UPDATE cabang SET no_invoice=no_invoice + 1 WHERE kdcab='".$session['kdcab']."'";

  				$this->db->trans_begin();
  				$this->db->query($Qry_Update_Cabang);
  				$this->db->query($Qry_Update_DO);
  				if($this->input->post('ppn') > 0){
  					$this->db->update('faktur_detail',$Arr_Update,array('idgen'=>$Kode_Gen,'fakturid'=>$fakturid));
  				}
  				$this->db->insert_batch('trans_invoice_detail',$Arr_detail);
  				$this->db->insert('trans_invoice_header',$headerinv);


				## INSERT JURNAL ##
				$this->db->insert('javh',$dataJVhead);
				$this->db->insert_batch('jurnal',$det_Jurnal);

				## INSERT ACCOUNT RECEIVABLE  ##
				$this->db->insert_batch('ar',$dataAR);

				$Qry_Update_Cabang_acc	 = "UPDATE pastibisa_tb_cabang SET noJS=noJS + 1 WHERE nocab='".$session['kdcab']."'";
				$this->db->query($Qry_Update_Cabang_acc);

  				if($this->db->trans_status() === FALSE){
  					 $this->db->trans_rollback();
  					 $Arr_Return		= array(
  							'status'		=> 2,
  							 'pesan'		=> 'Gagal simpan data'
  					   );
  				}else{
  					 $this->db->trans_commit();
  					 $Arr_Return		= array(
  						'status'		=> 1,
  						 'pesan'			=> 'Sukses simpan data'
  				   );
  				}
  			}else{
  				$Arr_Return		= array(
  					'status'		=> 2,
  					'pesan'			=> $faktur_data['pesan']
  			   );
  			}
  		}else{
  			$Arr_Return		= array(
  				'status'		=> 3,
  				'pesan'			=> 'No Record Was Found To Process. Please Try Again...'
  		   );
  		}

          echo json_encode($Arr_Return);


    }

    function print_custom($noinv){
      $mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
      $mpdf->SetImportUse();

        $inv_data = $this->Invoice_model->find_by(array('no_invoice' => $noinv));
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('inv_data', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        //$this->template->set('set_custom', $cusdiskon);

        $show = $this->template->load_view('print_data_custom',$data);

        $tglprint = date("d-m-Y H:i:s");
        $dt1 = new DateTime(@$inv_data->tanggal_invoice);
        $dt2 = new DateTime(@$inv_data->tgljatuhtempo);
        $telat = $dt1->diff($dt2);
        $header = '
        <div style="display: inline-block; position:relative;width:100%;display: none;">
          <div style="width:25%">
            <img src="assets/img/logo.JPG">
          </div>

        </div>
        	<table width="100%" border="0" id="header-tabel">
  	      	<tr>
  	      		<th width="30%" style="text-align: left;">
  	      			<img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
  	      		</th>
  	      		<th colspan="4" style="border-right: none;text-align: center;padding:0 !important;margin:-10px !important" width="65%">INVOICE (FAKTUR)<br>NO. : '.@$inv_data->no_invoice.'</th>
              	<th colspan="3" style="border-left: none;"></th>
  	      	</tr>
        	</table>
          <table width="100%" border="0" id="header-tabel">
          <tr>
              <td width="5%">NO. SO</td>

              <td colspan="3" width="50%">: '.@$detail->no_do.'</td>
              <td width="15%">Yogyakarta</td>
              <td width="1%">,</td>
              <td>'.date('d/m/Y',strtotime(@$inv_data->tanggal_invoice)).'</td>
          </tr>
          <tr>
              <td width="5%">SALES</td>

              <td colspan="3">: '. @$inv_data->nm_salesman.'</td>
              <td width="15%">Kepada Yth,</td>
              <td width="1%"></td>
              <td></td>
          </tr>
          <tr>
              <td width="5%">TOP</td>

              <td colspan="3">:
                  '.$telat->days.' HARI &nbsp;&nbsp;&nbsp; TGL JATUH TEMPO : '.date('d/m/Y',strtotime(@$inv_data->tgljatuhtempo)).'
              </td>
              <td width="15%" colspan="3" style="font-size:9pt !important;">
                  '.@$inv_data->nm_customer.'
              </td>
          </tr>
          <tr>
              <td width="5%">KETERANGAN</td>

              <td colspan="3">:</td>
              <td width="15%" colspan="3" style="font-size:9pt !important;">
                  '.@$inv_data->alamatcustomer.'
              </td>
          </tr>
      </table>';

        $this->mpdf->SetHTMLHeader($header,'0',true);
        $session = $this->session->userdata('app_session');
        $total_nominal = @$inv_data->hargajualafterdis;
        $diskon_stdr_persen = '-';
        $diskon_stdr_rp = 0;
        $diskon_toko_persen = '-';
        $diskon_toko_rp = 0;
        $diskon_cash_persen = '-';
        $grand_total_view = @$inv_data->hargajualafterdis;
        //$diskon_stdr   = @$inv_data->
        if(@$set_custom != ""){
            $total_nominal = @$inv_data->hargajualbefdis;
            $diskon_stdr_persen = 30;
            $diskon_toko_persen = @$inv_data->diskon_toko_persen;
            $diskon_cash_persen = @$inv_data->diskon_cash_persen;
            $diskon_stdr_rp = @$inv_data->diskon_stdr_rp;
            $diskon_toko_rp = @$inv_data->diskon_toko_rp;
            $grand_total_view = @$inv_data->hargajualbefdis-$diskon_stdr_rp-$diskon_toko_rp;
        }

        $this->mpdf->SetHTMLFooter('
        <hr>
        <table width="100%" border="0" style="font-size:9pt">

            <tr>
                <td colspan="3">
                    <i>TERBILANG : '.ucwords(ynz_terbilang_format(@$inv_data->hargajualtotal)).'</i>
                </td>
                <td width="19%"></td>
                <td width="1%"></td>
                <td width="15%" style="text-align: right;"></td>
                <!--<td width="10%"></td>-->

            </tr>

            <tr>
                <td colspan="3">
                    <center>Hormat Kami,</center>
                </td>
                <td width="19%">JUMLAH NOMINAL</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor(@$inv_data->hargajualafterdis).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3"></td>
                <td width="15%">DISKON TOKO '.@$inv_data->diskon_toko_persen.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;"> '.formatnomor(ceil(@$inv_data->diskon_toko_rp)).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3">
                  <center><img src="assets/img/logo.JPG" style="height: 50px;width: auto;"></center>
                </td>

                <td width="15%">DISKON CASH '.@$inv_data->diskon_cash_persen.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor(ceil(@$inv_data->diskon_cash_rp)).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3">
                    <center>(BM/SPV)</center>
                    <td width="15%">GRAND TOTAL</td>
                    <td width="1%">:</td>
                    <td width="15%" style="text-align: right;">'.formatnomor(@$inv_data->hargajualtotal).'</td>
                </td>
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
            <tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($userData->nm_lengkap).' On '.$tglprint.'</td></tr>
        </table>
        </div>');
        $this->mpdf->AddPageByArray([
                'orientation' => 'P',
                'sheet-size'=> [210,148],
                'margin-top' => 45,
                'margin-bottom' => 50,
                'margin-left' => 5,
                'margin-right' => 10,
                'margin-header' => 1,
                'margin-footer' => 0,
            ]);
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }



	 function cancel_invoice($noinv=''){
		 if($this->input->post()){
			 //echo"<pre>";print_r($this->input->post());exit;
			 $No_Inv		= $this->input->post('no_invoice');
			 $Alasan		= $this->input->post('cancel_reason');
			 $Tgl_Batal		= date('Y-m-d H:i:s');
			 $session 		= $this->session->userdata('app_session');
			 $Faktur		= $this->input->post('faktur_pajak');
			 $Arr_Faktur	= array();

			$detail_INV 	= $this->Invoice_model->find_data('trans_invoice_header',$No_Inv,'no_invoice');
			$Custid			= $detail_INV->id_customer;
			$Custname		= $detail_INV->nm_customer;
			$Periode_Invoice	= date('Ym',strtotime($detail_INV->tanggal_invoice));
			$Harga_Total	= $detail_INV->hargajualtotal;
			$Inv_PPN		= $detail_INV->ppn;
			$Inv_Materai	= $detail_INV->meterai;
			$Bulan_Sekarang	= date('n');
			$Tahun_Sekarang	= date('Y');
			$Periode_Sekarang= date('Ym');

			$Nomor_JV				= $this->Invoice_model->get_Nomor_Jurnal_Memorial($detail_INV->kdcab,date('Y-m-d'));
			$Total_DPP				= $Harga_Total;
			$Keterangan_INV			= 'PEMBT. PENJUALAN A/N '.$Custname.' INV NO. '.$No_Inv;
			$COA_Sales				= '4201-01-01';
			$dataJVhead = array(
				'nomor' 	    	=> $Nomor_JV,
				'tgl'	         	=> date('Y-m-d'),
				'jml'	         	=> $Harga_Total,
				'koreksi_no'		=> '',
				'kdcab'			  	=> $detail_INV->kdcab,
				'jenis'			    => 'V',
				'keterangan' 		=> $Keterangan_INV,
				'bulan'			  	=> $Bulan_Sekarang,
				'tahun'		   		=> $Tahun_Sekarang,
				'user_id'		   	=> $session['id_user'],
				'memo'			    => '',
				'tgl_jvkoreksi'	=> date('Y-m-d'),
				'ho_valid'			=> ''
			);

			$det_Jurnal				= array();
			$det_Jurnal[]			= array(
				  'nomor'         => $Nomor_JV,
				  'tanggal'       => date('Y-m-d'),
				  'tipe'          => 'JV',
				  'no_perkiraan'  => '1104-01-01',
				  'keterangan'    => $Keterangan_INV,
				  'no_reff'       => $No_Inv,
				  'debet'         => 0,
				  'kredit'        => $Harga_Total

			);
			if($Inv_PPN > 0){
				$Total_DPP				= $Harga_Total - $Inv_PPN;
				$COA_Sales				= '4101-01-01';
				$det_Jurnal[]			= array(
					  'nomor'         => $Nomor_JV,
					  'tanggal'       => date('Y-m-d'),
					  'tipe'          => 'JV',
					  'no_perkiraan'  => '2107-04-01',
					  'keterangan'    => 'PEMBT. PPN A/N '.$Custname.' INV NO. '.$No_Inv,
					  'no_reff'       => $No_Inv,
					  'debet'         => $Inv_PPN,
					  'kredit'        => 0

				);
			}
			$det_Jurnal[]			= array(
				  'nomor'         => $Nomor_JV,
				  'tanggal'       => date('Y-m-d'),
				  'tipe'          => 'JV',
				  'no_perkiraan'  => $COA_Sales,
				  'keterangan'    => $Keterangan_INV,
				  'no_reff'       => $No_Inv,
				  'debet'         => $Total_DPP,
				  'kredit'        => 0

			);

			 $Cek_Faktur	= $this->db->query("SELECT * FROM faktur_detail WHERE noinvoice='".$No_Inv."'")->num_rows();
			 $Upd_Invoice	= array(
				'hargajualbefdis'	=> 0,
				'diskontotal'		=> 0,
				'dpp'				=> 0,
				'ppn'				=> 0,
				'meterai'			=> 0,
				'hargajualtotal'	=> 0,
				'flag_cancel'		=> 'Y',
				'cancel_reason'		=> $Alasan,
				'cancel_date'		=> $Tgl_Batal,
				'cancel_by'			=> $session['username']
			 );

			 $Upd_Delivery	= array(
				'status'		=> 'DO',
				'no_invoice'	=> ''
			 );
			 $Upd_Detail	= array(
				'hargajual'	=> 0,
				'diskon'	=> 0,
				'ppn'		=> 0
			 );
       $Upd_Ar	= array(
				'saldo_awal'	=> 0,
				'saldo_akhir'	=> 0,
				'debet'			=> 0,
				'kredit'		=> 0
			 );
			 if($Cek_Faktur > 0 && $Faktur=='O'){
				 $Upd_Invoice['nofakturpajak']	='';
				 $Arr_Faktur			= array(
					'sts'				=> '0',
					'nofaktur'			=> '',
					'tglfaktur'			=> '',
					'tglinvoice'		=> '',
					'noinvoice'			=> '',
					'tanggal_generate'	=> ''
				);
			 }
			$this->db->trans_begin();
			if($Arr_Faktur){
				$this->db->update('faktur_detail',$Arr_Faktur,array('noinvoice'=>$No_Inv));
			}
			//update Delivery
			$this->db->update('trans_do_header',$Upd_Delivery,array('no_invoice'=>$No_Inv));

			## UPDATE AR ##
			if($Periode_Invoice==$Periode_Sekarang){
				$this->db->update('ar',$Upd_Ar,array('no_invoice'=>$No_Inv));
			}else{
				$Qry_AR			= "UPDATE ar SET kredit=kredit + ".$Harga_Total.", saldo_akhir = saldo_akhir - ".$Harga_Total." WHERE no_invoice='$No_Inv' AND bln='$Bulan_Sekarang' AND thn='$Tahun_Sekarang'";
				$this->db->query($Qry_AR);
			}


			// Update Detail
			$this->db->update('trans_invoice_detail',$Upd_Detail,array('no_invoice'=>$No_Inv));

			// Update Header
			$this->db->update('trans_invoice_header',$Upd_Invoice,array('no_invoice'=>$No_Inv));

			## INSERT JURNAL ##
			$this->db->insert('javh',$dataJVhead);
			$this->db->insert_batch('jurnal',$det_Jurnal);


			if($this->db->trans_status() === FALSE){
				 $this->db->trans_rollback();
				 $Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Cancel Process Failed. Please Try Again...'
				   );
			}else{
				 $this->db->trans_commit();
				 $Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Cancel Process Success. Thank You & Have A Nice Day...'
			   );
			}
			echo json_encode($Arr_Return);
		 }else{
			$inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
			$detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

			$this->template->set('row_header', $inv_data);
			$this->template->set('row_detail', $detail);

			$this->template->title('Batal Invoice');
			$this->template->render('cancel');
		 }

    }

}

?>
