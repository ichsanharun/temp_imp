<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Piutang_cabang extends Admin_Controller
{
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
        $this->load->library(array('Mpdf', 'upload', 'Image_lib'));
        $this->load->model(array('Piutang_cabang/Piutang_cabang_model',
                                 'Jurnal_nomor/Jurnal_model'
                                ));
        $this->template->title('Hutang Cabang');
        $this->template->page_icon('fa fa-file');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        //echo"<pre>";print_r($this->db->database);exit;
		$session 		= $this->session->userdata('app_session');		
        $data_cabang 	= $this->Piutang_cabang_model->get_data_Cabang();
        $this->template->set('rows_cabang', $data_cabang);		
		$this->template->set('user_cabang', $session['kdcab']);
        $this->template->title('Hutang Cabang');
        $this->template->render('list_bayar');
    }
	 public function daftar_piutang()
    {
        //$this->auth->restrict($this->viewPermission);
		$session 	= $this->session->userdata('app_session');		
        $data 		= $this->db->get_where('ar_cabang',array('kdcab'=>$session['kdcab'],'saldo_akhir !='=>'0','bln'=>date('n'),'thn'=>date('Y')))->result();
        $this->template->set('results', $data);
        $this->template->title('Hutang Cabang');
        $this->template->render('list_piutang');
    }
	function get_data_display(){
		include APPPATH.'helpers/extend_helper.php';
		$det_Akses	= akses_server_side();
		$session 	= $this->session->userdata('app_session');
		$WHERE		="";
		if($session['kdcab'] !='100'){
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="kdcab='".$session['kdcab']."'";
		}
		if($_POST['datet']){
			$asr_date		= $_POST['datet'];
			$ArrBulan		= array(1=>'January','February','March','April','May','June','July','August','September','October','November','December');
			$bulanP			= explode(" ",$asr_date);
			$YearL			= $bulanP[1];
			$MonthL			= array_search($bulanP[0],$ArrBulan);
			$bulanCek		= date('Y-m',mktime(0,0,0,$MonthL,1,$YearL));
			
			if(!empty($WHERE))$WHERE	.=" AND ";
			$WHERE	.="datet LIKE '".$bulanCek."-%'";
		}
		
		
		
		$table 		= 'ar_cabang_payment';
		$primaryKey = 'jurnalid';
		$columns 	= array(
			array( 'db' => 'jurnalid', 'dt' => 'jurnalid'),
			 array(
				'db' => 'jurnalid',
				'dt' => 'DT_RowId'
			),
			array( 'db' => 'kdcab', 'dt' => 'kdcab'),
			array( 'db' => 'no_perkiraan', 'dt' => 'no_perkiraan'),
			array( 'db' => 'descr', 'dt' => 'descr'),
			array( 'db' => 'flag_batal', 'dt' => 'flag_batal'),
			array( 'db' => 'bum_ho', 'dt' => 'bum_ho'),
			array( 
				'db' => 'datet', 
				'dt'=> 'datet',
				'formatter' => function($d,$row){
					return date('d M Y',strtotime($d));
				}
			),
			
			array( 
				'db' => 'total', 
				'dt'=> 'total',
				'formatter' => function($d,$row){
					return number_format($d);
				}
			),
			
			array( 
				'db' => 'jurnalid', 
				'dt'=> 'action',
				'formatter' => function($d,$row){
					return '';
				}
			)
			
		);
	
	
		$sql_details = array(
			'user' => $det_Akses['hostuser'],
			'pass' => $det_Akses['hostpass'],
			'db'   => $det_Akses['hostdb'],
			'host' => $det_Akses['hostname']
		);
		include( 'ssp.class.php' );
		
		
		echo json_encode(
			SSP::complex ($_POST, $sql_details, $table, $primaryKey, $columns,null, $WHERE)
		);
	}
    public function proses(){
  		if($this->input->post()){
  			$Data_Ambil		      	= $this->input->post('dataPilih');
			$session 				= $this->session->userdata('app_session');
			$Cabang_Bayar			= $session['kdcab'];
			
			$det_Coa				= $this->Piutang_cabang_model->get_Coa_Kas_Bank($Cabang_Bayar);
			$det_Cabang				= $this->db->get_where('pastibisa_tb_cabang',array('nocab'=>'100'))->result();
			$this->db->where_in('id',$Data_Ambil);
			$this->db->where(array('kdcab'=>$Cabang_Bayar));
			$det_Detail				= $this->db->get('ar_cabang')->result();
  			//echo"<pre>";print_r($det_Detail);exit;
  			$this->template->set('rows_data', $det_Detail);
			$this->template->set('rows_coa', $det_Coa);
  			$this->template->set('rows_cabang', $det_Cabang);
  			$this->template->set('records', $Arr_Data);
			//$this->template->set('header', $header);
  			$this->template->title('Bayar Hutang');
  			$this->template->render('bayar_form');
  		}else{
  			 $this->template->render('list_piutang');
		}

    }
	
	function save_jurnal(){
		$Arr_Return	= array();
		if($this->input->post()){
			//echo"<pre>";print_r($this->input->post());exit;
			$session 				= $this->session->userdata('app_session');
			//echo"<pre>";print_r($session);exit;
			$Bayar_Ke				= $this->input->post('bayar_kepada');
			$No_COA					= $this->input->post('no_perkiraan');
			$Keterangan				= strtoupper($this->input->post('descr'));
			$dataDet				= $this->input->post('dataDet');
			$Grand_Total			= str_replace(',','',$this->input->post('grand_tot'));
			$Tgl_Jurnal				= date('Y-m-d');
			$Cabang_Bayar			= $session['kdcab'];
			$Cabang_Pusat			= '100';
			$this->db->trans_begin();
			## JURNAL BUK ##
			$Tipe_Bayar		= 'Transfer';
			if(substr($No_COA,0,4)=='1101'){
				$Jenis_Pay	= 'KAS';
			}else{
				$Jenis_Pay	= 'BANK';
			}
			$Nomor_BUK		= $this->Jurnal_model->get_Nomor_Jurnal_BUK($Cabang_Bayar,$Tgl_Jurnal,$Jenis_Pay);
			$Update_BUK 	= $this->Jurnal_model->update_Nomor_Jurnal_BUK($Cabang_Bayar,$Jenis_Pay);
			$Nomor_BUM		= $this->Jurnal_model->get_Nomor_Jurnal_BUM($Cabang_Pusat,$Tgl_Jurnal);
			$Update_BUM 	= $this->Jurnal_model->update_Nomor_Jurnal($Cabang_Pusat,'BUM');
			
			## COA ##
			$Coa_Piutang	= $this->Jurnal_model->get_COA_Piutang($Cabang_Bayar);
			$Coa_Hutang		= '2101-01-01';
			$Coa_Bank_Pusat	= '1101-01-02';
			$det_Cabang		= $this->db->get_where('pastibisa_tb_cabang',array('nocab'=>$Cabang_Bayar))->result();
		
			$Header_Payment	= array(
				'jurnalid'		=> $Nomor_BUK,
				'datet'			=> $Tgl_Jurnal,
				'kdcab'			=> $Cabang_Bayar,
				'no_perkiraan'	=> $No_COA,
				'total'			=> $Grand_Total,
				'descr'			=> $Keterangan,
				'bum_ho'		=> $Nomor_BUM,
				'created_date'	=> date('Y-m-d H:i:s'),
				'created_by'	=> $session['id_user']
			);
			
			$Header_BUK		= array(
				'nomor'			=> $Nomor_BUK,
				'tgl'			=> $Tgl_Jurnal,
				'jml'			=> $Grand_Total,
				'kdcab'			=> $Cabang_Bayar,
				'jenis_reff'	=> $Tipe_Bayar,
				'no_reff'		=> '-',
				'bayar_kepada'	=> $Bayar_Ke,
				'jenis_ap'		=> 'V'
			);
			$Header_BUM = array(
				'nomor'         => $Nomor_BUM,
				'kd_pembayaran' => $Nomor_BUK,
				'tgl'           => $Tgl_Jurnal,
				'jml'           => $Grand_Total,
				'kdcab'         => $Cabang_Pusat,
				'jenis_reff'    => 'TRANSFER',
				'no_reff'       => '-',
				'terima_dari'   => $det_Cabang[0]->cabang,
				'valid'         => 1,
				'tgl_valid'     => $Tgl_Jurnal,
				'user_id'       => $session['id_user']
			);
			$Detail_BUK			= array();
			$Detail_BUM			= array();
			$Detail_BUM[0] 		= array(
				'nomor'         => $Nomor_BUM,
				'tanggal'       => $Tgl_Jurnal,
				'tipe'          => 'BUM',
				'no_perkiraan'  => $Coa_Bank_Pusat,
				'keterangan'    => 'Pembayaran Hutang Cabang#'.$det_Cabang[0]->cabang,
				'no_reff'       => '-',
				'debet'         => $Grand_Total,
				'kredit'        => 0
			);	
			$intL				= 0;
			if($dataDet){
				foreach($dataDet as $key=>$vals){
					$intL++;
					$Bayar_Nil			= str_replace(',','',$vals['total_baru']);
					$Detail_BUK[$intL]		= array(
						  'nomor'         => $Nomor_BUK,
						  'tanggal'       => $Tgl_Jurnal,
						  'tipe'          => 'BUK',
						  'no_perkiraan'  => $Coa_Hutang,
						  'keterangan'    => 'Hutang#'.$vals['no_po'].'#'.$vals['supplier'],
						  'no_reff'       => $vals['no_po'],
						  'debet'         => $Bayar_Nil,
						  'kredit'        => 0

					);
					$Detail_BUM[$intL] 		= array(
						'nomor'         => $Nomor_BUM,
						'tanggal'       => $Tgl_Jurnal,
						'tipe'          => 'BUM',
						'no_perkiraan'  => $Coa_Piutang,
						'keterangan'    => 'Pembayaran Hutang Cabang#'.$det_Cabang[0]->cabang.'#'.$vals['no_po'],
						'no_reff'       => $vals['no_po'],
						'debet'         => 0,
						'kredit'        => $Bayar_Nil
					);
					
					$Cek_Data			= $this->db->get_where('ar_cabang',array('id'=>$vals['kode']))->result();
					if($Cek_Data){
						$Saldo_Awal		= $Cek_Data[0]->saldo_awal;
						$Debet			= $Cek_Data[0]->debet;
						$Kredit			= $Cek_Data[0]->kredit + $Bayar_Nil;
						$Saldo_Akhir	= $Saldo_Awal + $Debet - $Kredit;
						$this->db->update('ar_cabang',array('kredit'=>$Kredit,'saldo_akhir'=>$Saldo_Akhir),array('id'=>$vals['kode']));
					}
				}
			}
			
			
			$Detail_BUK[0]		= array(
				  'nomor'         => $Nomor_BUK,
				  'tanggal'       => $Tgl_Jurnal,
				  'tipe'          => 'BUK',
				  'no_perkiraan'  => $No_COA,
				  'keterangan'    => $Keterangan,
				  'no_reff'       => '-',
				  'debet'         => 0,
				  'kredit'        => $Grand_Total

			);
			
			
			## BUK  ##
			$this->db->insert('japh',$Header_BUK);
			$this->db->insert_batch('jurnal',$Detail_BUK);
			## BUM ##
			$this->db->insert('jarh',$Header_BUM);
			$this->db->insert_batch('jurnal',$Detail_BUM);
			
			## PAYMENT
			$this->db->insert('ar_cabang_payment',$Header_Payment);
			$this->db->trans_complete();
			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				$Arr_Return		= array(
					'status'		=> 2,
					'pesan'			=> 'Save Process Failed. Please Try Again...'
			   );
			} else {
				$this->db->trans_commit();
				$Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Save Process Success. Thank You & Have A Nice Day....'
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
    
	function print_buk($jurnal){
		$uk1 	= 9;
		$ukk 	= 17;
		$ukkk 	= 11;
		$mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
			//$mpdf=new mPDF('','','','','','','','','','');
		$mpdf->SetImportUse();
		$session 		= $this->session->userdata('app_session');
		$Jurnal_Header 	= $this->db->get_where('japh',array('nomor'=>$jurnal))->result();
		//echo"<pre>";print_r($Jurnal_Header);exit;
		$AR_Bayar		= $this->db->get_where('ar_cabang_payment',array('jurnalid'=>$jurnal))->result();
		$cabang 		= $this->db->get_where('pastibisa_tb_cabang',array('nocab'=>$AR_Bayar[0]->kdcab))->result();
		$det_jurnal		= $this->db->get_where('jurnal',array('nomor'=>$jurnal))->result();
		$det_Coa		= $this->Piutang_cabang_model->get_Coa_Kas_Bank($AR_Bayar[0]->kdcab);
		$this->template->set('rows_data', $det_jurnal);
		$this->template->set('rows_cabang', $cabang);
		$this->template->set('detail', $detail);
		$show 	= $this->template->load_view('print_buk');
		$Jenis_Bayar	= $det_Coa[$AR_Bayar[0]->no_perkiraan];
		$Exp_COA		= explode(' - ',$Jenis_Bayar);
			//$this->mpdf->AddPage('L');
		//echo $show;exit;
			$header = '<table width="100%" border="0" id="header-tabel">
						<tr>
						  <th width="30%" style="text-align: left;">
							<img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
						  </th>
						  <th colspan="3" style="border-right: none;text-align: center;padding-left:0% !important;margin-left:-10px !important" width="100%">BUKTI UANG KELUAR (DO)<br>'.@$Jurnal_Header[0]->nomor.'</th>
						  <th colspan="4" style="border-left: none;"></th>
						</tr>
						</table>
						<hr style="padding:0;margin:0">
						<table width="100%" border="0" id="header-tabel">
						<tr>
							<td width="15%">KAS/BANK. </td>
							<td width="30%">:'.@$Exp_COA[1].'</td>
							<td width="5%"></td>
							<td width="15%">'.@$cabang[0]->cabang.',</td>
							<td>'.date('d-M-Y',strtotime($AR_Bayar[0]->datet)).'</td>
						</tr>
						<tr>
							<td width="10%">Keterangan</td>
							<td width="1%">:'.strtoupper(@$AR_Bayar[0]->descr).'</td>
							<td colspan="2"></td>
							<td></td>
							<td width="8%"></td>

						</tr>
					</table>';
			$this->mpdf->SetHTMLHeader($header,'0',true);
			$this->mpdf->SetHTMLFooter('
				<table width="100%" border="0" style="font-size: '.$ukk.'px !important;">
				
				<tr>
					<td width="30%"><center>Dibuat Oleh,</center></td>
					<td width="40%"><center>Disetujui Oleh,</center></td>
					<td width="30%"><center>Dibukukan Oleh,</center></td>
				</tr>
				<tr>					
					<td width="15%" colspan="3" style="height: 50px;"></td>
				</tr>
				<tr>
					<td width="30%"><center>(...................)</center></td>
					<td width="40%"><center>(...................)</center></td>
					<td width="30%"><center>(...................)</center></td>
				</tr>
			</table>
			<hr />
			<div id="footer">
			<table>
				<tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($session['nm_lengkap']) ." On ".date('d-m-Y H:is').'</td></tr>
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
	

    
}
