<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Efaktur extends Admin_Controller {
    
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
		$this->load->model('Efaktur/Efaktur_model');
		$this->load->database();
        $this->template->title('Efaktur');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index(){
        //$this->auth->restrict($this->viewPermission);
		
		if($this->input->post()){
			$tgl_awal		= $this->input->post('tgl_awal');
			$tgl_akhir		= $this->input->post('tgl_akhir');
		}else{
			$tgl_awal		= date('Y-m-d',mktime(0,0,0,date('m')-1,1,date('Y')));
			$tgl_akhir		= date('Y-m-d');
		}
		$Qry_Data			= "SELECT * FROM view_export_efaktur WHERE (date_export BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."')";
        $data 				= $this->db->query($Qry_Data)->result_array();
        $this->template->set('results', $data);
		$this->template->set('tgl_awal', $tgl_awal);
		$this->template->set('tgl_akhir', $tgl_akhir);
        $this->template->title('Efaktur');
        $this->template->render('list');
    }
	
	 public function list_outstanding(){
        
        $data 				= $this->Efaktur_model->getArray('view_outstanding_export_efaktur');
        $this->template->set('results', $data);
        $this->template->title('E-faktur');
        $this->template->render('list_out');
    }
	
	
	public function proses(){
		if($this->input->post()){					
			$getparam 		= $this->input->post('set_choose_invoice');
			$Arr_Data		= array();
			$this->db->where_in('no_invoice',$getparam);
			$Arr_Data		= $this->db->get('view_outstanding_export_efaktur')->result_array();
			$this->template->set('records', $Arr_Data);
			$this->template->title('Export E-faktur');
			$this->template->render('proses');
		}else{
			 $this->template->render('list_out');
		}
       
    }
	
    public function add(){
       if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$detail_do			= $this->input->post('det_do');
			$Nomor				= date('ymdHi');
			$Tanggal			= date('Y-m-d');
			$Jam				= date('H:i:s');
			$OK					= 1;
			$Kode_Proses		= implode("','",$detail_do);
			$Arr_Detail			= array();
			if($detail_do){
				$intI			=0;
				foreach($detail_do as $key=>$vals){
					$intI++;
					$Arr_Ins			= array(
						'id_export'			=> $Nomor,
						'date_export'		=> $Tanggal,
						'time_export'		=> $Jam,
						'invoice_no'		=> $vals
					);
					$Arr_Detail[$intI]	= $Arr_Ins;
				}
				unset($detail_do);
			}
			
			$Qry_Update_Inv			= "UPDATE trans_invoice_header SET sts_faktur='Y' WHERE no_invoice IN ('".$Kode_Proses."')";
			
			$this->db->trans_begin();
			$this->db->query($Qry_Update_Inv);
			$this->db->insert_batch('faktur_e_logs',$Arr_Detail);
			
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
				'status'		=> 3,
				'pesan'			=> 'No Record Was Found To Process. Please Try Again...'
		   );
	   }
	   echo json_encode($Arr_Return);
    }
	
	function export_csv($kode=''){
		$output = 'FK;KD_JENIS_TRANSAKSI;FG_PENGGANTI;NOMOR_FAKTUR;MASA_PAJAK;TAHUN_PAJAK;TANGGAL_FAKTUR;NPWP;NAMA;ALAMAT_LENGKAP;JUMLAH_DPP;JUMLAH_PPN;JUMLAH_PPNBM;ID_KETERANGAN_TAMBAHAN;FG_UANG_MUKA;UANG_MUKA_DPP;UANG_MUKA_PPN;UANG_MUKA_PPNBM;REFERENSI';
		$output .="\n";
		$output .="LT;NPWP;NAMA;JALAN;BLOK;NOMOR;RT;RW;KECAMATAN;KELURAHAN,KABUPATEN;PROPINSI,KODE_POS;NOMOR_TELEPON";
		$output .="\n";
		$output .="OF;KODE_OBJEK;NAMA;HARGA_SATUAN;JUMLAH_BARANG;HARGA_TOTAL;DISKON;DPP;PPN;TARIF_PPNBM;PPNBM";
		$output .="\n";
		$Data_Header	= $this->Efaktur_model->getArray('view_export_efaktur_detail',array('id_export'=>$kode));
		foreach($Data_Header as $key=>$values){
			$Data_Customer		= $this->Efaktur_model->getArray('customer',array('id_customer'=>$values['id_customer']));
			$kd_trans 			= substr($values['nofakturpajak'],0,2);
			$mp 				= substr($values['tanggal_invoice'],5,2);
			$tp 				= substr($values['tanggal_invoice'],0,4);
			$hp 				= substr($values['tanggal_invoice'],8,2);
			$tglv 				= $hp."/".$mp."/".$tp;
			$nofaktur1 			= substr($values['nofakturpajak'],2,1);
			$nofaktur2 			= substr($values['nofakturpajak'],4,3);
			$nofaktur3 			= substr($values['nofakturpajak'],8,2);
			$nofaktur4 			= substr($values['nofakturpajak'],11,8);
			$nofaktur 			= $nofaktur2.$nofaktur3.$nofaktur4;
			if(!empty($Data_Customer[0]['npwp'])){
				$nonpwp1 	= substr($Data_Customer[0]['npwp'],0,2);
				$nonpwp2 	= substr($Data_Customer[0]['npwp'],3,3);
				$nonpwp3 	= substr($Data_Customer[0]['npwp'],7,3);
				$nonpwp4 	= substr($Data_Customer[0]['npwp'],11,1);
				$nonpwp5 	= substr($Data_Customer[0]['npwp'],13,3);
				$nonpwp6 	= substr($Data_Customer[0]['npwp'],17,3);
				$nonpwp 	= $nonpwp1.$nonpwp2.$nonpwp3.$nonpwp4.$nonpwp5.$nonpwp6;
			}else{
				$nonpwp		= '000000000000000';
			}
			$cust		= trim($Data_Customer[0]['nm_customer']);
		
			$addr 		= (isset($Data_Customer[0]['alamat_npwp']) &&$Data_Customer[0]['alamat_npwp'])?$Data_Customer[0]['alamat_npwp']:$values['alamatcustomer'];
			$noinv 		= $values['no_invoice'];
			
			$jmldpp 	= $values['dpp'];
			$jmlppn 	= $values['ppn'];			
			
			if ($kd_trans=="07") {
				$output .= 'FK;'.$kd_trans.';0;'.$nofaktur.';'.$mp.';'.$tp.';'.$tglv.';'.$nonpwp.';'.$cust.';'.$addr.';'.$jmldpp.';'.$jmlppn.';0;1;0;0;0;0;'.$noinv;
			} else {
				$output .= 'FK;'.$kd_trans.';0;'.$nofaktur.';'.$mp.';'.$tp.';'.$tglv.';'.$nonpwp.';'.$cust.';'.$addr.';'.$jmldpp.';'.$jmlppn.';0;0;0;0;0;0;'.$noinv;			
			}
			
			$output .="\n";	
			
			$Data_Detail		= $this->Efaktur_model->getArray('trans_invoice_detail',array('no_invoice'=>$values['no_invoice']));
			foreach($Data_Detail as $keyD=>$valD){
				$disc 			= $valD['diskon'];
				$total_diskon	= $valD['jumlah'] * $disc;
				$Total_Harga	= $valD['jumlah'] * $valD['hargajual'];
				
				if ($kd_trans=="04") {
					$dtotal = 0;
					$dpp 	= 0;
					$dppn 	= 0;
				} else {
					$dtotal = $Total_Harga - $total_diskon;
					$dpp 	= $valD['hargajual'];
					
					$dppn 	= $valD['ppn'];
					
					
				}
				
				$output .='OF;;'.$valD['nm_barang'].';'.$valD['hargajual'].';'.$valD['jumlah'].';'.$Total_Harga.';'.$total_diskon.';'.$dtotal.';'.$dppn.';0;0;0;;;;;;;';
				$output .="\n";
			}
		}
		$filename = "efaktur-".date("ymdHis").".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;

		exit;
	}
    
    public function view($kode){
		$header				= $this->Efaktur_model->getArray('view_export_efaktur',array('id_export'=>$kode));
		$details			= $this->Efaktur_model->getArray('view_export_efaktur_detail',array('id_export'=>$kode));
		//echo"<pre>";print_r($details);
		$this->template->set('row_header', $header);
        //$this->template->set('customer', $customer);
        $this->template->set('row_detail', $details);
        
        $this->template->render('view');
		
	  
    }


}

?>
