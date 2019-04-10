<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hutang extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
				'Receiving/Receiving_model',
				'Hutang/Model_hutang',
				'Jurnal_nomor/Jurnal_model'
			)
		);

        $this->template->title('Manage Data Hutang');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {

        $pi = $this->db->query('SELECT a.id as idsss, a.`status` as st, a.*, b.*  FROM `trans_po_payment` as a, trans_po_header as b WHERE a.no_po=b.no_po AND b.kdcab = '.$this->auth->user_cab().' AND b.status = "INVOICE"');

        $this->template->set('results', $pi);
        $this->template->title('Data Pelunasan Pembelian');
        $this->template->render('index');
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
  			$data	= "<option value=''>Empty List";
  		}
		    //echo $data;
        echo json_encode($data);
    }

    public function bayar_form()
    {
        $id = $this->uri->segment(3);

        $this->template->set('id', $id);

        $this->template->title('Bayar Pelunasan Pembelian');
        $this->template->render('bayar_form');
    }

    public function bayar_save()
    {
        $session 	= $this->session->userdata('app_session');
        $id 		  = $this->input->post('id');
        $nopo 		= $this->input->post('no_po');
        $inv 	  	= $this->input->post('no_invoice');
        $supp 		= $this->input->post('supplier');
        $ket 	  	= $this->input->post('keterangan');
        $jumlah 	= $this->input->post('jumlah');
        $kdcab 		= $session['kdcab'];
        $tgl 	  	= date('Y-m-d');
        $thb 	  	= date('m').substr(date('Y'), 2, 2);
		$dataHeader 	= $this->db->get_where('trans_po_payment',array('id'=>$id))->result();

		/*
		if ($this->input->post('myRadios') == '1') {
            $bln = date('n');
            $thn = date('Y');
            $query = $this->db->query("SELECT * FROM `coa` WHERE `kdcab` LIKE '%$kdcab-A%' AND `level` LIKE '%5%' AND `no_perkiraan` LIKE '%1101%' AND `bln` LIKE '%$bln%' AND `thn` LIKE '%$thn%' ORDER BY `no_perkiraan` DESC LIMIT 1");
            $keterangan = 'Kas';
            $row = $query->row();
            $no_perkiraan = "$row->no_perkiraan";
            $bukti = '';

            $querycek_j = $this->db->query("SELECT RIGHT(nomor,5) AS kode FROM `jurnal` WHERE nomor LIKE '%$kdcab-AKK$thb%' ORDER BY nomor DESC LIMIT 1 ");
            if ($querycek_j->num_rows() > 0) {
                $row = $querycek_j->row();
                $kodej = $row->kode + 1;
            } else {
                $kodej = 1;
            }

            $bikin_kode_j = str_pad($kodej, 5, '0', STR_PAD_LEFT);
            $kode_jadi_j = "$kdcab-AKK$thb$bikin_kode_j";
        } else {
            $no_perkiraan = $this->input->post('no_perkiraan');
            $keterangan = 'Bank';
            $bukti = 'bukti '.$this->input->post('bukti');
            $querycek_j = $this->db->query("SELECT RIGHT(nomor,5) AS kode FROM `jurnal` WHERE nomor LIKE '%$kdcab-ABK$thb%' ORDER BY nomor DESC LIMIT 1 ");
            if ($querycek_j->num_rows() > 0) {
                $row = $querycek_j->row();
                $kodej = $row->kode + 1;
            } else {
                $kodej = 1;
            }

            $bikin_kode_j = str_pad($kodej, 5, '0', STR_PAD_LEFT);
            $kode_jadi_j = "$kdcab-ABK$thb$bikin_kode_j";
        }


        $jurnal_d = array(
            'tipe' => 'BUK',
            'nomor' => $kode_jadi_j,
            'tanggal' => date('Y-m-d'),
            'no_perkiraan' => '1108-01-01',
            'keterangan' => "$ket#$nopo#$inv#$supp#$bukti",
            'no_reff' => $inv,
            'debet' => $jumlah,
            'kredit' => 0,
        );

        $jurnal_k = array(
            'tipe' => 'BUK',
            'nomor' => $kode_jadi_j,
            'tanggal' => date('Y-m-d'),
            'no_perkiraan' => "$no_perkiraan",
            'keterangan' => "$keterangan#$nopo#$inv#$supp",
            'no_reff' => $inv,
            'debet' => 0,
            'kredit' => $jumlah,
        );

        $querycek = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `javh` WHERE nomor LIKE '%$kdcab-A-JS$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek->num_rows() > 0) {
            $row = $querycek->row();
            $kode = $row->kode + 1;
        } else {
            $kode = 1;
        }

        $bikin_kode = str_pad($kode, 4, '0', STR_PAD_LEFT);
        $kode_jadi = "$kdcab-A-JS$thb$bikin_kode";
        $javh = array(
            'nomor' => $kode_jadi,
            'tgl' => date('Y-m-d'),
            'jml' => $jumlah,
            'kdcab' => $kdcab,
            'jenis' => 'V',
            'keterangan' => "$keterangan #$inv#$nopo#$supp",
            'bulan' => date('m'),
            'tahun' => date('Y'),
            'user_id' => $session['id_user'],
        );
*/



		## ALI 2019-03-11 ##
		$Cabang_Pusat	= '100';
		$Jumlah_Bayar	= $this->input->post('jumlah');
		$Persen_Bayar	= $dataHeader[0]->persen;
		$Jenis_Bayar	= $this->input->post('myRadios');
		$Tgl_Jurnal		= $this->input->post('tgl_bayar');
		if($Jenis_Bayar==1){
			$Jenis_Pay	= 'KAS';
			$Tipe_Bayar	= 'Cash';
			$No_COA		= '1101-01-02'; // KAS TRANSIT //
		}else{
			$Jenis_Pay	= 'BANK';
			$Tipe_Bayar	= 'Transfer';
			$No_COA		= $this->input->post('no_perkiraan');
		}

		## CEK DATA PEMBAYARAN YANG CLOSE ##
		$Jumlah_Bayar_Old	= 0;
		$Pros_Bayar		= $this->db->get_where('trans_po_payment',array('LOWER(status)'=>'close','no_po'=>$nopo));
		$num_Bayar		= $Pros_Bayar->num_rows();
		if($num_Bayar > 0){
			$det_Bayar		= $Pros_Bayar->result();
			foreach($det_Bayar as $keyB=>$valB){
				$Persen_Bayar		+=floatval($valB->persen);
				$Jumlah_Bayar_Old	+=floatval($valB->bayar);
			}
		}

		## CEK STATUS RECEIVED ##
		## ANTIPASI BUAT JIKA BARANG SUDAH RECEIVE SEBELUM BAYAR FULL ##
		$Pros_Received	= $this->db->get_where('trans_receive',array('po_no'=>$nopo));
		$Num_Receive	= $Pros_Received->num_rows();
		if($Num_Receive > 0){
			$Total_Hutang	= 0;
			$Query_Detail	= "SELECT
									SUM(
										IF (
											det_barang.qty_pl > det_barang.qty_i,
											det_barang.qty_i,
											det_barang.qty_pl
										) * (
											det_po.harga_rp / det_po.qty_acc
										)
									) AS total_hutang,
									SUM(
										det_barang.qty_i
										* (
											det_po.harga_rp / det_po.qty_acc
										)
									) AS hutang_po
								FROM
									receive_detail_barang det_barang
								INNER JOIN trans_po_detail det_po ON det_barang.no_po = det_po.no_po
								AND det_barang.id_barang = det_po.id_barang
								WHERE
									det_barang.no_po = '".$nopo."'";
			$det_Hutang		= $this->db->query($Query_Detail)->result();
			if($det_Hutang){
				$Total_Hutang	= $det_Hutang[0]->total_hutang;
				$Total_PO		= $det_Hutang[0]->hutang_po;
			}
		}
		$this->db->trans_begin();
		if($Persen_Bayar >= 100 && $Num_Receive > 0){
			$Coa_Hutang			= '2101-01-01';
			$Keterangan_BUK		= 'Pelunasan#'.$inv.'#'.$nopo;
			$Sisa_Hutang		= $Total_PO - $Jumlah_Bayar;
			$Selisih_Bayar		= $Jumlah_Bayar_Old - $Sisa_Hutang;
			$Nomor_Jurnal_CN	= $this->Jurnal_model->get_Nomor_Jurnal_Pembelian($Cabang_Pusat,$Tgl_Jurnal);

			$Keterangan_CN		= 'Penyelesaian Hutang atas '.$supp;
			$dataCNHead		= array(
				'nomor' 	    	=> $Nomor_Jurnal_CN,
				'tgl'	         	=> $Tgl_Jurnal,
				'jml'	          	=> $Jumlah_Bayar_Old,
				'koreksi_no'		=> '',
				'kdcab'				=> $Cabang_Pusat,
				'jenis'			    => 'V',
				'keterangan' 		=> $Keterangan_CN,
				'bulan'				=> date('n',strtotime($Tgl_Jurnal)),
				'tahun'				=> date('Y',strtotime($Tgl_Jurnal)),
				'user_id'			=> $session['id_user'],
				'memo'			    => '',
				'tgl_jvkoreksi'		=> $Tgl_Jurnal,
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


			//COA LABA RUGI SELISIH KURS
			## MOHON DI KONFIRMASI LAGI NOMOR COA ##

			$COA_Kurs		= '6812-01-01';
			if($Selisih_Bayar < 0){
				$Jumlah_Kurs	= $Selisih_Bayar * -1;
				$detail_CN[2]	= array(
					'nomor'         => $Nomor_Jurnal_CN,
					'tanggal'       => $Tgl_Jurnal,
					'tipe'          => 'JV',
					'no_perkiraan'  => $COA_Kurs,
					'keterangan'    => 'Penyelesaian hutang#PO '.$nopo.'#'.$supp,
					'no_reff'       => $nopo,
					'debet'         => 0,
					'kredit'        => $Jumlah_Kurs
				);
			}else if($Selisih_Bayar > 0){
				$detail_CN[2]	= array(
					'nomor'         => $Nomor_Jurnal_CN,
					'tanggal'       => $Tgl_Jurnal,
					'tipe'          => 'JV',
					'no_perkiraan'  => $COA_Kurs,
					'keterangan'    => 'Penyelesaian hutang#PO '.$nopo.'#'.$supp,
					'no_reff'       => $nopo,
					'debet'         => $Selisih_Bayar,
					'kredit'        => 0
				);
			}
			$this->db->insert('javh',$dataCNHead);
			$this->db->insert_batch('jurnal',$detail_CN);
			$Update_JV = $this->Jurnal_model->update_Nomor_Jurnal($Cabang_Pusat,'JP');

		}else{
			$Coa_Hutang		= '1108-01-01';
			$Keterangan_BUK	= 'UangMuka#'.$inv.'#'.$nopo;
		}
		$Nomor_BUK		= $this->Jurnal_model->get_Nomor_Jurnal_BUK($Cabang_Pusat,$Tgl_Jurnal,$Jenis_Pay);
		$Header_BUK		= array(
			'nomor'			=> $Nomor_BUK,
			'tgl'		    => $Tgl_Jurnal,
			'jml'			  => $Jumlah_Bayar,
			'kdcab'			=> $Cabang_Pusat,
			'jenis_reff'=> $Tipe_Bayar,
			'no_reff'		=> $nopo,
			'bayar_kepada'=> $supp,
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
		## END ALI ##



		/*
        $this->db->insert('javh', $javh);

        $this->db->insert('jurnal', $jurnal_d);

        $this->db->insert('jurnal', $jurnal_k);
		*/
		$querycek_ap = $this->db->query("SELECT * FROM `ap` WHERE no_po='$nopo' ");
		$row_ap = $querycek_ap->row();
		$debit = $jumlah + $row_ap->debet;

		$saldo_akhir = $row_ap->saldo_awal - $debit;
        $this->db->query("UPDATE `trans_po_payment` SET `status` = 'close',tgl_bayar='$tgl', bayar='$jumlah' WHERE id='$id';");

        $this->db->query("UPDATE `ap` SET `debet` = '$debit',saldo_akhir='$saldo_akhir' WHERE no_po='$nopo';");

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
