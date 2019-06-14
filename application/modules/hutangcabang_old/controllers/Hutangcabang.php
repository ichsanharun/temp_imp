<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hutangcabang extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf', 'upload', 'Image_lib'));
        $this->load->model(array(
				'Receiving/Receiving_model',
				'Hutang/Model_hutang',
				'Jurnal_nomor/Jurnal_model',
        'Purchaseorder/Purchaseorder_model',
        'Purchaseorder/Detailpurchaseorder_model',
        'Purchaserequest/Purchaserequest_model',
        'Purchaserequest/Detailpurchaserequest_model',
			)
		);

        $this->template->title('Manage Data Hutang');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {

        $pi = $this->db->query('SELECT * FROM trans_po_header WHERE status_cabang != "LUNAS" AND kdcab = '.$this->auth->user_cab())->result();

        $this->template->set('results', $pi);
        $this->template->title('Data Pelunasan Hutang Pusat');
        $this->template->render('index');
    }

    public function print_request_po_conf($nopo)
    {
        $mpdf = new mPDF('', '', '', '', '', '', '', '', '', '');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $po_data = $this->Purchaseorder_model->find_data('trans_po_header', $nopo, 'no_po');
        $cabang = $this->Purchaseorder_model->cek_data(array('kdcab' => $po_data->kdcab), 'cabang');
        $detail = $this->Detailpurchaseorder_model->find_all_by(array('no_po' => $nopo));

        $barang = $this->db->query("SELECT * FROM trans_po_detail INNER JOIN barang_master ON trans_po_detail.id_barang = barang_master.id_barang WHERE no_po = '$nopo'")->result();

        $this->template->set('po_data', $po_data);
        $this->template->set('cabang', $cabang);
        $this->template->set('detail', $detail);

        $this->template->set('barang', $barang);

        $show = $this->template->load_view('print_request_po_conf', $data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    public function print_request_po($nopo)
    {
        $mpdf = new mPDF('', '', '', '', '', '', '', '', '', '');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $po_data = $this->Purchaseorder_model->find_data('trans_po_header', $nopo, 'no_po');
        $cabang = $this->Purchaseorder_model->cek_data(array('kdcab' => $po_data->kdcab), 'cabang');
        $detail = $this->Detailpurchaseorder_model->find_all_by(array('no_po' => $nopo));

        $barang = $this->db->query("SELECT * FROM trans_po_detail INNER JOIN barang_master ON trans_po_detail.id_barang = barang_master.id_barang WHERE no_po = '$nopo'")->result();

        $this->template->set('po_data', $po_data);
        $this->template->set('cabang', $cabang);
        $this->template->set('detail', $detail);

        $this->template->set('barang', $barang);

        $show = $this->template->load_view('print_request_po', $data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    public function add_ajax_prodi($id_fak)
    {
        $Bulan_Now 		= date('n', strtotime($id_fak));
        $Tahun_Now 		= date('Y', strtotime($id_fak));
        $session 		= $this->session->userdata('app_session');
        $kdcab 			= $session['kdcab'];
  		$Bulan_Lalu		= 1;
  		$Tahun_Lalu		= date('Y');
  		if($Bulan_Now==1){
  			$Bulan_Lalu	= 12;
  			$Tahun_Lalu	= date('Y')-1;
  		}
  		$Query_Bank	= "SELECT * FROM `coa` WHERE kdcab='".$kdcab."-A' AND `level`='5' AND `no_perkiraan` LIKE '1102-%' AND `bln`='".$Bulan_Now."' AND `thn`='".$Tahun_Now."'";
  		$Num_Bank	= $this->db->query($Query_Bank)->num_rows();
  		if($Num_Bank > 0){
  			$det_Bank		= $this->db->query($Query_Bank)->result();
  		}else{
  			$Query_Bank	= "SELECT * FROM `coa` WHERE kdcab='".$kdcab."-A' AND `level`='5' AND `no_perkiraan` LIKE '1102-%' AND `bln`='".$Bulan_Lalu."' AND `thn`='".$Tahun_Lalu."'";
  			$det_Bank	= $this->db->query($Query_Bank)->result();
  		}
  		//echo"<pre>"; print_r($det_Bank);exit;

  		if($det_Bank){
  			$data	= "<option value=''>-- Pilih Bank --";
  			foreach($det_Bank as $key=>$vals){
  				 $data .= "<option value='".$vals->no_perkiraan."'>".$vals->nama;
  			}
  		}else{
  			$data	= "<option value=''>Empty List</option>";
  		}
  		//echo $data;
        echo json_encode($data);
    }

    public function bayar_form()
    {
        $id = $this->uri->segment(3);

        $this->template->set('id', $id);

        $this->template->title('Bayar Hutang Cabang ke Pusat');
        $this->template->render('bayar_form');
    }

    public function bayar_save()
    {
        $session 	= $this->session->userdata('app_session');
        $id 		= $this->input->post('id');
        $nopo 		= $this->input->post('no_po');
        $inv 		= $this->input->post('no_invoice');
        $supp 		= $this->input->post('supplier');
        $ket 		= $this->input->post('keterangan');
        $jumlah 	= $this->input->post('jumlah');
        $kdcab 		= $session['kdcab'];
        $tgl 		= date('Y-m-d');
        $thb 		= date('m').substr(date('Y'), 2, 2);
		$dataHeader	= $this->db->get_where('trans_po_payment',array('id'=>$id))->result();



		## Mulai ##
		$Cabang_Pusat	= '100';
    $cabang       = $this->auth->user_cab();
		$Jumlah_Bayar	= $this->input->post('jumlah');
		$Persen_Bayar	= $dataHeader[0]->persen;
		$Jenis_Bayar	= $this->input->post('myRadios');
		$Tgl_Jurnal		= $this->input->post('tgl_bayar');
    $Coa_Hutang			= '2101-01-01';
		if($Jenis_Bayar==1){
			$Jenis_Pay	= 'KAS';
			$Tipe_Bayar	= 'Cash';
			$No_COA		= '1101-01-02'; // KAS TRANSIT //
		}else{
			$Jenis_Pay	= 'BANK';
			$Tipe_Bayar	= 'Transfer';
			$No_COA		= $this->input->post('no_perkiraan');
		}


		$this->db->trans_begin();
    /*
		if($Persen_Bayar >= 100 && $Num_Receive > 0){
			$Keterangan_BUK		= 'Pelunasan#'.$inv.'#'.$nopo;
			//$Sisa_Hutang		= $Total_PO - $Jumlah_Bayar;
			//$Selisih_Bayar		= $Jumlah_Bayar_Old - $Sisa_Hutang;
			$Nomor_Jurnal_CN	= $this->Jurnal_model->get_Nomor_Jurnal_Pembelian($Cabang_Pusat,$Tgl_Jurnal);

			$Keterangan_CN		= 'Penyelesaian Hutang atas Cabang ke Pusat';
			$dataCNHead		= array(
				'nomor' 	    	=> $Nomor_Jurnal_CN,
				'tgl'	         	=> $Tgl_Jurnal,
				'jml'	          => $Jumlah_Bayar_Old,
				'koreksi_no'		=> '',
				'kdcab'			  	=> $Cabang_Pusat,
				'jenis'			    => 'V',
				'keterangan' 		=> $Keterangan_CN,
				'bulan'				  => date('n',strtotime($Tgl_Jurnal)),
				'tahun'				  => date('Y',strtotime($Tgl_Jurnal)),
				'user_id'			  => $session['id_user'],
				'memo'			    => '',
				'tgl_jvkoreksi'	=> $Tgl_Jurnal,
				'ho_valid'			=> ''
			);


			$detail_CN		= array();
			$detail_CN[0]	= array(
				'nomor'         => $Nomor_Jurnal_CN,
				'tanggal'       => $Tgl_Jurnal,
				'tipe'          => 'JV',
				'no_perkiraan'  => '2101-01-01',
				'keterangan'    => 'Penyelesaian hutang#PO '.$nopo.'#'.$supp,
				'no_reff'       => $nopo,
				'debet'         => $Sisa_Hutang,
				'kredit'        => 0
			);

			$detail_CN[1]	= array(
				'nomor'         => $Nomor_Jurnal_CN,
				'tanggal'       => $Tgl_Jurnal,
				'tipe'          => 'JV',
				'no_perkiraan'  => '1108-01-01',
				'keterangan'    => 'Penyelesaian hutang#PO '.$nopo.'#'.$supp,
				'no_reff'       => $nopo,
				'debet'         => 0,
				'kredit'        => $Jumlah_Bayar_Old
			);



			$this->db->insert('javh',$dataCNHead);
			$this->db->insert_batch('jurnal',$detail_CN);
			$Update_JV = $this->Jurnal_model->update_Nomor_Jurnal($Cabang_Pusat,'JP');

		}else{
			$Coa_Hutang		= '1108-01-01';
			$Keterangan_BUK	= 'UangMuka#'.$inv.'#'.$nopo;
		}*/
		$Nomor_BUK		= $this->Jurnal_model->get_Nomor_Jurnal_BUK($cabang,$Tgl_Jurnal,$Jenis_Pay);
		$Header_BUK		= array(
			'nomor'			=> $Nomor_BUK,
			'tgl'		    => $Tgl_Jurnal,
			'jml'			  => $Jumlah_Bayar,
			'kdcab'			=> $cabang,
			'jenis_reff'=> $Tipe_Bayar,
			'no_reff'		=> $nopo,
			'bayar_kepada'=> 'Importa Pusat',
			'jenis_ap'		=> 'V'
		);

		$Detail_BUK			= array();
		$Detail_BUK[0]		= array(
			  'nomor'         => $Nomor_BUK,
			  'tanggal'       => $Tgl_Jurnal,
			  'tipe'          => 'BUK',
			  'no_perkiraan'  => $Coa_Hutang,
			  'keterangan'    => $Keterangan_BUK,
			  'no_reff'       => $nopo,
			  'debet'         => $Jumlah_Bayar,
			  'kredit'        => 0

		);

		$Detail_BUK[1]		= array(
			  'nomor'         => $Nomor_BUK,
			  'tanggal'       => $Tgl_Jurnal,
			  'tipe'          => 'BUK',
			  'no_perkiraan'  => $No_COA,
			  'keterangan'    => $Keterangan_BUK,
			  'no_reff'       => $nopo,
			  'debet'         => 0,
			  'kredit'        => $Jumlah_Bayar

		);

		$this->db->insert('japh',$Header_BUK);
		$this->db->insert_batch('jurnal',$Detail_BUK);
		$Update_BUK = $this->Jurnal_model->update_Nomor_Jurnal_BUK($Cabang_Pusat,$Jenis_Pay);

    $this->db->query("UPDATE `trans_po_header` SET `status_cabang` = 'LUNAS' WHERE no_po='$nopo';");

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $param = array(
                'save' => 0,
                'msg' => 'GAGAL, perubahan..!!!',
                );
        } else {
            $this->db->trans_commit();
            $param = array(
                'save' => 1,
                'msg' => 'SUKSES, melakukan perubahaan..!!!',
                );
        }




        echo json_encode($param);
    }
}
