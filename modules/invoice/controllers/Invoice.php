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
    public function __construct()
    {
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

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Invoice_model->order_by('no_invoice','DESC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Invoice');
        $this->template->render('list');
    }

    //Create New Invoice
    public function create()
    {
        $customer = $this->Customer_model->find_all();
        $this->template->set('customer',$customer);
        $kode_customer		= '';
        /*
		$Arr_Where			= array(
			'status'		=> 'DO',
            'status'		=> 'DO-PENDING'
		);
		*/
		$Arr_Where			= array(
			'status !='		=> 'INV'
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
    public function proses()
    {
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$customer		= $this->input->post('idcustomer');
			$getparam 		= $this->input->post('set_choose_invoice');
			$data_Cust		= $this->db->get_where('customer',array('id_customer'=>$customer))->result();
			$Arr_Data		= array();
			$this->db->where_in('no_do',$getparam);
			$headerdo		= $this->db->get('trans_do_header')->result_array();
			foreach($headerdo as $key=>$vals){
				$Arr_Data[$key]					= $vals;
				$details						= $this->db->get_where('trans_do_detail',array('no_do'=>$vals['no_do'],'qty_supply != '=>0))->result_array();
				$Arr_Data[$key]['detail_data']	= $details;
			}
			
			$Faktur_header	= $this->Invoice_model->getFakturMaster();
			
			$this->template->set('data_cust', $data_Cust);
			$this->template->set('faktur', $Faktur_header);
			$this->template->set('records', $Arr_Data);
			$this->template->title('Input Invoice');
			$this->template->render('invoice_form');
		}else{
			 $this->template->render('list_do');
		}
       
    }

    function saveheaderinvoice(){
		if($this->input->post()){
			$detail_do			= $this->input->post('det_do');
			$custid				= $this->input->post('idcustomer_do');
			$custname			= $this->input->post('nmcustomer_do');
			$tgl_expired		= $this->input->post('tgljthtempo');
			$kefak				= $this->input->post('kode_faktur');		
			$sts_materai		= $this->input->post('materai');
			$id_salesman		= $this->input->post('id_salesman');
			$nm_salesman		= $this->input->post('nm_salesman');
			$nofaktur			= '';
			$OK					= 1;
			
			$faktur_data	= $this->Invoice_model->getFakturAktif();
			//echo "<pre>";print_r($faktur_data);exit;
			if($faktur_data['hasil'] != '1'){
				$OK			= 0;
			}else{				 
				$Kode_Gen		= $faktur_data['data']['idgen'];
				$fakturid		= $faktur_data['data']['no_faktur'];
			}
			
			/*
			By : MUHEMIN -> Edit tgl invoice post dari form toleransi hari ini dan besok;
			*/

			if($OK==1){
				$Tgl_Invoice			= $this->input->post('tgl_inv');
				$session 				= $this->session->userdata('app_session');
				$no_invoice 			= $this->Invoice_model->generate_noinv($session['kdcab']);
				$customer 				= $this->Invoice_model->cek_data(array('id_customer'=>$custid),'customer');
				
				
				$nofaktur			= $kefak.'.'.$fakturid;
				$Arr_Update			= array(
					'sts'				=> '1',
					'nofaktur'			=> $nofaktur,
					'tglfaktur'			=> $Tgl_Invoice,
					'tglinvoice'		=> $Tgl_Invoice,
					'noinvoice'			=> $no_invoice,
					'tanggal_generate'	=> date('Y-m-d H:i:s')
				);
				
				$Biaya_Materai			= 0;
				if($sts_materai=='Y'){
					$Biaya_Materai		= 6000;
				}
				$Arr_detail				= array();
				$Total_DPP	= $Total_Diskon	= $Total_After	= $PPN = $Total_landed = 0;
				$DO_Detail				= $this->Invoice_model->get_where_in('no_do',$detail_do,'trans_do_detail');
				$Awal					=  0;
				foreach($DO_Detail as $keys=>$values){
					$Awal++;
					$noso				= $values->no_so;
					$kode_barang		= $values->id_barang;
					$nama_barang		= $values->nm_barang;
					
					
					$header_so			= $this->Invoice_model->cek_data(array('no_so'=>$noso),'trans_so_header');
					$detail_so			= $this->Invoice_model->cek_data(array('no_so'=>$noso,'id_barang'=>$kode_barang),'trans_so_detail');
					$landed_stock		= $this->Invoice_model->cek_data(array('id_barang'=>$kode_barang),'barang_stock');
					$harga_so			= $detail_so->harga;
					$qty_so				= $detail_so->qty_supply;
					$diskon_so			= $detail_so->diskon;
					$qty_bonus			= $detail_so->qty_bonus;

					$qty_supply			= $values->qty_supply-$qty_bonus;
					
					$discount_satuan	= 0;
					if($diskon_so > 0){
						$discount_satuan	= round($diskon_so / $qty_so);
					}
					
					$dpp_barang			= $qty_supply * $harga_so;
					$diskon_barang		= $qty_supply * $discount_satuan;
					$harga_bersih		= $dpp_barang - $diskon_barang;
					$landed_cost		= $qty_supply * $landed_stock->landed_cost;
					
					$sts_ppn			= $header_so->ppn;
					$nil_ppn			= 0;
					if($sts_ppn > 0){
						$nil_ppn		= floor($harga_bersih * 0.1);
					}
					$Total_DPP			+= $dpp_barang;
					$Total_Diskon		+= $diskon_barang;
					$Total_After		+= $harga_bersih;
					$PPN				+= $nil_ppn;
					$Total_landed		+= $landed_cost;
					
					$Arr_detail[$Awal]['no_invoice']		= $no_invoice;
					$Arr_detail[$Awal]['id_barang']			= $kode_barang;
					$Arr_detail[$Awal]['nm_barang']			= $nama_barang;
					$Arr_detail[$Awal]['jumlah']			= $qty_supply;
					$Arr_detail[$Awal]['satuan']			= $values->satuan;
					$Arr_detail[$Awal]['hargajual']			= $harga_so;
					$Arr_detail[$Awal]['hargalanded']		= $landed_cost;
					$Arr_detail[$Awal]['diskon']			= $discount_satuan;
					$Arr_detail[$Awal]['tgljual']			= $detail_so->tanggal;
					$Arr_detail[$Awal]['ppn']				= $nil_ppn;
					$Arr_detail[$Awal]['no_do']				= $values->no_do;
					$Arr_detail[$Awal]['bonus']				= $qty_bonus;
				}
				if($PPN > 0){
					$faktur_pajak		= $nofaktur;
				}else{
					$faktur_pajak		= '';
					
				}
				$Grand_Total			= $Total_After + $PPN + $Biaya_Materai;
				
				$headerinv = array(
					'no_invoice' 		=> $no_invoice,
					'kdcab'				=> $session['kdcab'],
					'id_customer'	 	=> $custid,
					'nm_customer' 		=> $custname,
					'tanggal_invoice' 	=> $Tgl_Invoice,
					'id_salesman'		=> $id_salesman,
					'nm_salesman' 		=> $nm_salesman,
					'nofakturpajak' 	=> $faktur_pajak,
					'tglfakturpajak' 	=> $Tgl_Invoice,
					'tgljatuhtempo' 	=> $tgl_expired,
					'alamatcustomer' 	=> $customer->alamat,
					'npwpcustomer' 		=> $customer->npwp,
					'hargajualbefdis'	=> $Total_DPP,
					'diskontotal'		=> $Total_Diskon,
					'dpp'				=> $Total_After,
					'ppn'				=> $PPN,
					'meterai'			=> $Biaya_Materai,
					'hargajualtotal'	=> $Grand_Total,
					'piutang'			=> $Grand_Total,
					'hargalandedtotal'	=> $Total_landed
				);
				//echo"<pre> ono bro : ";print_r($Arr_detail);exit;
				$Kode_Proses			= implode("','",$detail_do);
				
				$Qry_Update_DO			= "UPDATE trans_do_header SET status='INV',no_invoice='$no_invoice' WHERE no_do IN ('".$Kode_Proses."')";
				$Qry_Update_Cabang		= "UPDATE cabang SET no_invoice=no_invoice + 1 WHERE kdcab='".$session['kdcab']."'";
				$this->db->trans_begin();
				$this->db->query($Qry_Update_Cabang);
				$this->db->query($Qry_Update_DO);
				if($PPN > 0){
					$this->db->update('faktur_detail',$Arr_Update,array('idgen'=>$Kode_Gen,'fakturid'=>$fakturid));
				}
				$this->db->insert_batch('trans_invoice_detail',$Arr_detail);
				$this->db->insert('trans_invoice_header',$headerinv);
				if($this->db->trans_status() === FALSE){
					 $this->db->trans_rollback();
					 $Arr_Return		= array(
							'status'		=> 2,
							'pesan'			=> 'Save Process Failed. Please Try Again...'
					   );
				}else{
					 $this->db->trans_commit();
					 $Arr_Return		= array(
						'status'		=> 1,
						'pesan'			=> 'Save Process Success. Thank You & Have A Nice Day...'
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

    function print_request($noinv){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('header', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
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
			
			// Update Detail
			$this->db->update('trans_invoice_detail',$Upd_Detail,array('no_invoice'=>$No_Inv));
			
			// Update Header
			$this->db->update('trans_invoice_header',$Upd_Invoice,array('no_invoice'=>$No_Inv));
			
			
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
