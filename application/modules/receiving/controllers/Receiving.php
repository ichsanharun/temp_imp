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

class Receiving extends Admin_Controller
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
        $this->load->model(array('Receiving/Receiving_model',
                                 'Receiving/Detailreceiving_model',
                                 'Trans_stock/Trans_stock_model',
                                 'Barang_stock/Barang_stock_model',
                                 'Invoice/Invoice_model',
                                 'Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Aktifitas/aktifitas_model',
                                 'Purchaserequest/Purchaserequest_model',
                                ));
        $this->template->title('Receiving');
        $this->template->page_icon('fa fa-file');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Receiving_model->order_by('no_receiving', 'ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Receiving');
        $this->template->render('list');
    }

    public function konfrimasi()
    {
        $sup = $this->uri->segment(3);
        $no = $this->uri->segment(4);
        $session = $this->session->userdata('app_session');
        $this->db->select('*');
        $this->db->from('trans_po_detail');
        $this->db->where('trans_po_detail.no_po', $no);
        $itembarang = $this->db->get()->result();

        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");

        $this->template->set('supplier', $sup);
        $this->template->set('no_pr', $no);
        $this->template->set('pr_tambahan', $query_pr_tambahan);
        $this->template->set('itembarang', $itembarang);
        $this->template->title('Receiving');
        $this->template->render('konfirmasi');
    }

    public function konfrimasi_save()
    {
		    //echo"<pre>";print_r($this->input->post());exit;
        $session = $this->session->userdata('app_session');
        $nopo 	= $this->input->post('no_pr');
        $kdcab 	= $this->input->post('kdcab');
        //Update counter NO_DO
        $count = $this->Receiving_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
        $this->db->where(array('kdcab' => $session['kdcab']));
        $this->db->update('cabang', array('no_receive' => $count->no_receive + 1));
        //Update counter NO_DO

        $norec = $this->Receiving_model->generate_noreceive($session['kdcab']);
        $dataheader = array(
            'no_receiving' => $norec,
            'po_no' => $nopo,
            'kdcab' => $this->input->post('kdcab'),
            'namacabang' => $this->input->post('namacabang'),
            'tglreceive' => $this->input->post('tglreceive'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'tgl_sjsupplier' => $this->input->post('tgldosupp'),
            'no_sjsupplier' => $this->input->post('no_do_supplier'),
            'noinvoice' => $norec,
            'tglinvoice' => $this->input->post('tglreceive'),
            'container_no' => $this->input->post('container_no'),
            'date_unloading' => $this->input->post('date_unloading'),
            'date_check' => $this->input->post('date_check'),
            'administrator' => $this->input->post('administrator'),
            'head' => $this->input->post('head'),
            'branch' => $this->input->post('branch'),
            );
        $this->db->trans_begin();
        $this->db->insert('trans_receive', $dataheader);
        $jumlah_barang = count($_POST['idet_barang']);
        $i = 0;

  		## ALI 2019-03-09 ##
  		## PARAMETER UNTUK JURNAL * NEED CONFIRMATION * ##
  		$Data_Header		= $this->db->get_where('trans_po_header',array('no_po'=>$nopo))->result();
  		$Cabang_PO			= $Data_Header[0]->kdcab;
  		$Cabang_Pusat		= '100';
  		$Jumlah_Persediaan	= 0;
  		$Jumlah_Lebih		= 0;
  		$Jumlah_Hutang		= 0;
  		$Jumlah_Hut_Supplier= 0;

		  ## END PARAMETER ##

        for ($b = 0; $b < $jumlah_barang; ++$b) {
			## ALI 2019-03-09 ##
			$Qty_Acc		= $_POST['qty_ib'][$b];
			$Qty_Pick		= $_POST['qty_plb'][$b];
			$harga_barang 	= $_POST['hargab'][$b];
			$Harga_UP		= round(1.25 * $harga_barang);
			$Qty_Lebih		=  0;
			if($Qty_Pick > $Qty_Acc){
				$Qty_Lebih	= $Qty_Pick - $Qty_Acc;
			}
			$Jumlah_Persediaan	+=($Qty_Pick * $Harga_UP);
			$Jumlah_Hutang		+=(($Qty_Pick - $Qty_Lebih) * $Harga_UP);
			$Jumlah_Lebih		+=($Qty_Lebih * $Harga_UP);
			$Jumlah_Hut_Supplier+=(($Qty_Pick - $Qty_Lebih) * $harga_barang);

			## END ALI ##
            $detil = array(
                    'no_po' => $nopo,
                    'id_barang' => $_POST['idet_barang'][$b],
                    'nama_barang' => $_POST['nm_barangb'][$b],
                    'qty_i' => $_POST['qty_ib'][$b],
                    'qty_pl' => $_POST['qty_plb'][$b],
                    'bagus' => $_POST['qty_bagus_t'][$b],
                    'rusak' => $_POST['qty_rusak_t'][$b],
                );
            $this->Receiving_model->insert_receive_detail_barang($detil);

            //Update STOK REAL dan AVL
			/*
			## ORIGINAL ##
            $count = $this->Receiving_model->cek_data(array('id_barang' => $_POST['idet_barang'][$b], 'kdcab' => $session['kdcab']), 'barang_stock');
            if (!empty($count->landed_cost)) {
                $hrg = ($harga_barang * $_POST['qty_bagus_t'][$b]) + ($count->qty_stock * $count->landed_cost);
                $landed_cost = $hrg / ($count->qty_stock + $_POST['qty_bagus_t'][$b]);
            } else {
                $landed_cost = "$harga_barang";
            }
			*/

			## ALI 2019-03-09 ##
			$count = $this->Receiving_model->cek_data(array('id_barang' => $_POST['idet_barang'][$b], 'kdcab' => $session['kdcab']), 'barang_stock');
            if (!empty($count->landed_cost)) {
                $hrg = ($Harga_UP * $_POST['qty_bagus_t'][$b]) + ($count->qty_stock * $count->landed_cost);
                $landed_cost = $hrg / ($count->qty_stock + $_POST['qty_bagus_t'][$b]);
            } else {
                $landed_cost = "$Harga_UP";
            }
			## END ALI ##
            $id_st = $this->Trans_stock_model->gen_st($this->auth->user_cab()).$i;

            $tipe = 'IN';
            $jenis_trans = 'IN_Pembelian';
            $qty_stock_new = $qty_stock + $qty;
            $qty_avl_new = $qty_avl + $qty;

            $data_adj_trans = array(
                          'id_st' => $id_st,
                          'tipe' => $tipe,
                          'jenis_trans' => $jenis_trans,
                          'noreff' => $norec,
                          'id_barang' => $_POST['idet_barang'][$b],
                          'nm_barang' => $_POST['nm_barangb'][$b],
                          'jenis' => substr($_POST['idet_barang'][$b], 0, 2),
                          'kategori' => $kategori,
                          'brand' => 'IMPORTA',
                          'satuan' => 'SET',
                          'kdcab' => $this->auth->user_cab(),
                          'date_stock' => date('Y-m-d H:i:s'),
                          'qty' => $_POST['qty_bagus_t'][$b],
                          'qty_rusak' => $_POST['qty_rusak_t'][$b],
                          'qty_stock_awal' => $count->qty_stock,
                          'qty_avl_awal' => $count->qty_avl,
                          'qty_stock_akhir' => $count->qty_stock + $_POST['qty_bagus_t'][$b],
                          'qty_avl_akhir' => $count->qty_avl + $_POST['qty_bagus_t'][$b],
                          'nilai_barang' => $landed_cost,
                          'notes' => '',
                        );
            $this->Trans_stock_model->insert($data_adj_trans); //modules trans_stok
            ++$i;

            $this->db->where(array('id_barang' => $_POST['idet_barang'][$b], 'kdcab' => $session['kdcab']));
            $this->db->update('barang_stock', array('landed_cost' => $landed_cost, 'qty_stock' => $count->qty_stock + $_POST['qty_bagus_t'][$b], 'qty_avl' => $count->qty_avl + $_POST['qty_bagus_t'][$b], 'qty_rusak' => $count->qty_rusak + $_POST['qty_rusak_t'][$b]));

            //Update STOK REAL
        }

        $jumlah = count($_POST['qty_bagus']);

        for ($i = 0; $i < $jumlah; ++$i) {
            $detil = array(
                    'no_po' => $nopo,
                    'id_barang' => $_POST['id_barangc'][$i],
                    'id_koli' => $_POST['id_koli'][$i],
                    'nama_koli' => $_POST['nama_koli'][$i],
                    'qty_i' => $_POST['qty_i'][$i],
                    'qty_pl' => $_POST['qty_pl'][$i],
                    'bagus' => $_POST['qty_bagus'][$i],
                    'rusak' => $_POST['qty_rusak'][$i],
                    'keterangan' => $_POST['keterangan'][$i],
                );
            $this->Receiving_model->insert_receive_detail_koli($detil);

            $kdql = $_POST['id_koli'][$i];
            $queryqol = $this->db->query("SELECT * FROM `barang_stock` WHERE id_barang='$kdql'");
            $rowqly = $queryqol->row();

            $qty_stock = $rowqly->qty_stock + $_POST['qty_bagus'][$i];
            $qty_avl = $rowqly->qty_avl + $_POST['qty_bagus'][$i];
            $qty_rusak = $rowqly->qty_rusak + $_POST['qty_rusak'][$i];

            $this->db->query("UPDATE `barang_stock` SET `qty_stock` = '$qty_stock', qty_avl='$qty_avl', qty_rusak='$qty_rusak'  WHERE id_barang ='$kdql' and kdcab='$kdcab';");
        }
        $this->db->query("UPDATE `trans_po_header` SET `status` = 'RECEIVING' WHERE `trans_po_header`.`no_po` ='$nopo';");

        $thb = date('m').substr(date('Y'), 2, 2);
        $querypo = $this->db->query("SELECT * FROM `trans_po_header` WHERE no_po='$nopo' ");
        $rowpo = $querypo->row();
        $inv = get_invoice($nopo);

        $querycek_j = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `jurnal` WHERE nomor LIKE '%$kdcab-A-JP$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek_j->num_rows() > 0) {
            $row = $querycek_j->row();
            $kodej = $row->kode + 1;
        } else {
            $kodej = 1;
        }


  		## ORIGINAL ##
          /*
  		$bikin_kode_j = str_pad($kodej, 4, '0', STR_PAD_LEFT);
          $kode_jadi_j = "$kdcab-A-JP$thb$bikin_kode_j";

          $cek_open = $this->db->query("SELECT * FROM `trans_po_payment` WHERE no_po='$nopo' AND status='open'");
          if ($cek_open->num_rows() > 0) {
              $total_close = 0;
              $clsoee = $this->db->query("SELECT * FROM `trans_po_payment` WHERE no_po='$nopo' AND status='close' ");
              foreach ($clsoee->result() as $rowclsoe) {
                  $uangmuka = $rowclsoe->persen * $rowpo->rupiah_total / 100;
                  $total_close += $uangmuka;
              }

              $jurnal_d = array(
                  'tipe' => 'JV',
                  'nomor' => $kode_jadi_j,
                  'tanggal' => date('Y-m-d'),
                  'no_perkiraan' => '1105-01-01',
                  'keterangan' => "Persediaan#$nopo#$inv#$rowpo->nm_supplier",
                  'no_reff' => $inv,
                  'debet' => $rowpo->rupiah_total,
                  'kredit' => 0,
              );

              $jurnal_k = array(
                  'tipe' => 'JV',
                  'nomor' => $kode_jadi_j,
                  'tanggal' => date('Y-m-d'),
                  'no_perkiraan' => '1108-01-01',
                  'keterangan' => "Uang Muka $nopo#$inv#$rowpo->nm_supplier",
                  'no_reff' => $inv,
                  'debet' => 0,
                  'kredit' => $total_close,
              );

              $jurnal_ka = array(
                  'tipe' => 'JV',
                  'nomor' => $kode_jadi_j,
                  'tanggal' => date('Y-m-d'),
                  'no_perkiraan' => '2101-01-01',
                  'keterangan' => "Hutang $nopo#$inv#$rowpo->nm_supplier",
                  'no_reff' => $inv,
                  'debet' => 0,
                  'kredit' => $rowpo->rupiah_total - $total_close,
              );

          } else {
              $jurnal_d = array(
                  'tipe' => 'JV',
                  'nomor' => $kode_jadi_j,
                  'tanggal' => date('Y-m-d'),
                  'no_perkiraan' => '1105-01-01',
                  'keterangan' => "Persediaan#$nopo#$inv#$rowpo->nm_supplier",
                  'no_reff' => $inv,
                  'debet' => $rowpo->rupiah_total,
                  'kredit' => 0,
              );

              $jurnal_k = array(
                  'tipe' => 'JV',
                  'nomor' => $kode_jadi_j,
                  'tanggal' => date('Y-m-d'),
                  'no_perkiraan' => '2101-01-01',
                  'keterangan' => "Pembelian $nopo#$inv#$rowpo->nm_supplier",
                  'no_reff' => $inv,
                  'debet' => 0,
                  'kredit' => $rowpo->rupiah_total,
              );
          }

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
              'jml' => $rowpo->rupiah_total,
              'kdcab' => $rowpo->kdcab,
              'jenis' => 'V',
              'keterangan' => "Uang muka #$inv#$nopo#$rowpo->nm_supplier",
              'bulan' => date('m'),
              'tahun' => date('Y'),
              'user_id' => $session['id_user'],
          );

          $this->db->insert('javh', $javh);

          $this->db->insert('jurnal', $jurnal_d);

  		$this->db->insert('jurnal', $jurnal_ka);

          $this->db->insert('jurnal', $jurnal_k);
  		*/

  		## ALI 2019-03-09 ##
  		## JURNAL CABANG ##
  		$session 				= $this->session->userdata('app_session');
  		$Tgl_Jurnal				= date('Y-m-d');
  		$Nomor_JV				= $this->Invoice_model->get_Nomor_Jurnal_Memorial($Cabang_PO,$Tgl_Jurnal);
  		$Keterangan_JV			= 'Persediaan#PO '.$nopo.'#'.$this->input->post('nmsupplier');

  		$dataJVhead = array(
  			'nomor' 	    	=> $Nomor_JV,
  			'tgl'	         	=> $Tgl_Jurnal,
  			'jml'	          	=> $Jumlah_Persediaan,
  			'koreksi_no'		=> '',
  			'kdcab'				=> $Cabang_PO,
  			'jenis'			    => 'V',
  			'keterangan' 		=> $Keterangan_JV,
  			'bulan'				=> date('n'),
  			'tahun'				=> date('Y'),
  			'user_id'			=> $session['id_user'],
  			'memo'			    => '',
  			'tgl_jvkoreksi'		=> $Tgl_Jurnal,
  			'ho_valid'			=> ''
  		);

  		$det_Jurnal				= array();
  		$det_Jurnal[0]			= array(
  			  'nomor'         => $Nomor_JV,
  			  'tanggal'       => $Tgl_Jurnal,
  			  'tipe'          => 'JV',
  			  'no_perkiraan'  => '1105-01-01',
  			  'keterangan'    => $Keterangan_JV,
  			  'no_reff'       => $nopo,
  			  'debet'         => $Jumlah_Persediaan,
  			  'kredit'        => 0

  		);
  		$det_Jurnal[1]			= array(
  			  'nomor'         => $Nomor_JV,
  			  'tanggal'       => $Tgl_Jurnal,
  			  'tipe'          => 'JV',
  			  'no_perkiraan'  => '2101-01-01',
  			  'keterangan'    => $Keterangan_JV,
  			  'no_reff'       => $nopo,
  			  'debet'         => 0,
  			  'kredit'        => $Jumlah_Hutang

  		);
  		if($Jumlah_Lebih > 0){
  			$det_Jurnal[2]			= array(
  				  'nomor'         => $Nomor_JV,
  				  'tanggal'       => $Tgl_Jurnal,
  				  'tipe'          => 'JV',
  				  'no_perkiraan'  => '7101-02-01',
  				  'keterangan'    => $Keterangan_JV,
  				  'no_reff'       => $nopo,
  				  'debet'         => 0,
  				  'kredit'        => $Jumlah_Lebih

  			);
  		}

  		$this->db->insert('javh',$dataJVhead);
  		$this->db->insert_batch('jurnal',$det_Jurnal);
  		## END JURNAL  CABANG##

  		## JURNAL HO ##
  		$Nomor_JV				= $this->Invoice_model->get_Nomor_Jurnal_Memorial($Cabang_Pusat,$Tgl_Jurnal);
  		$Keterangan_JV			= 'Piutang Cabang#PO '.$nopo.'#'.$this->input->post('nmsupplier');

  		$dataJVhead = array(
  			'nomor' 	    	=> $Nomor_JV,
  			'tgl'	         	=> $Tgl_Jurnal,
  			'jml'	          	=> $Jumlah_Hutang,
  			'koreksi_no'		=> '',
  			'kdcab'				=> $Cabang_Pusat,
  			'jenis'			    => 'V',
  			'keterangan' 		=> $Keterangan_JV,
  			'bulan'				=> date('n'),
  			'tahun'				=> date('Y'),
  			'user_id'			=> $session['id_user'],
  			'memo'			    => '',
  			'tgl_jvkoreksi'		=> $Tgl_Jurnal,
  			'ho_valid'			=> ''
  		);
  		if($Cabang_PO=='101'){
  			$Coa_Piutang	='1104-01-04';
  		}else if($Cabang_PO=='102'){
  			$Coa_Piutang	='1104-01-02';
  		}else if($Cabang_PO=='103'){
  			$Coa_Piutang	='1104-01-03';
  		}else if($Cabang_PO=='104'){
  			$Coa_Piutang	='1104-01-05';
  		}
  		$Profit				    = $Jumlah_Hutang - $Jumlah_Hut_Supplier;
  		$det_Jurnal				= array();
  		$det_Jurnal[0]			= array(
  			  'nomor'         => $Nomor_JV,
  			  'tanggal'       => $Tgl_Jurnal,
  			  'tipe'          => 'JV',
  			  'no_perkiraan'  => $Coa_Piutang,
  			  'keterangan'    => $Keterangan_JV,
  			  'no_reff'       => $nopo,
  			  'debet'         => $Jumlah_Hutang,
  			  'kredit'        => 0

  		);
  		$det_Jurnal[1]			= array(
  			  'nomor'         => $Nomor_JV,
  			  'tanggal'       => $Tgl_Jurnal,
  			  'tipe'          => 'JV',
  			  'no_perkiraan'  => '2101-01-01',
  			  'keterangan'    => 'Hutang Supplier#PO '.$nopo.'#'.$this->input->post('nmsupplier'),
  			  'no_reff'       => $nopo,
  			  'debet'         => 0,
  			  'kredit'        => $Jumlah_Hut_Supplier

  		);
  		$det_Jurnal[2]			= array(
  			  'nomor'         => $Nomor_JV,
  			  'tanggal'       => $Tgl_Jurnal,
  			  'tipe'          => 'JV',
  			  'no_perkiraan'  => '4102-01-01',
  			  'keterangan'    => 'Persediaan#PO '.$nopo.'#'.$this->input->post('nmsupplier'),
  			  'no_reff'       => $nopo,
  			  'debet'         => 0,
  			  'kredit'        => $Profit

  		);
  		$this->db->insert('javh',$dataJVhead);
  		$this->db->insert_batch('jurnal',$det_Jurnal);

  		## END JURNAL HO ##

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

    public function konfrimasi_save_lamx()
    {
        $session = $this->session->userdata('app_session');
        $nopo = $this->input->post('no_pr');

        //Update counter NO_DO
        $count = $this->Receiving_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
        $this->db->where(array('kdcab' => $session['kdcab']));
        $this->db->update('cabang', array('no_receive' => $count->no_receive + 1));
        //Update counter NO_DO

        $norec = $this->Receiving_model->generate_noreceive($session['kdcab']);
        $dataheader = array(
            'no_receiving' => $norec,
            'po_no' => $nopo,
            'kdcab' => $this->input->post('kdcab'),
            'namacabang' => $this->input->post('namacabang'),
            'tglreceive' => $this->input->post('tglreceive'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'tgl_sjsupplier' => $this->input->post('tgldosupp'),
            'no_sjsupplier' => $this->input->post('no_do_supplier'),
            'noinvoice' => $norec,
            'tglinvoice' => $this->input->post('tglreceive'),
            'container_no' => $this->input->post('container_no'),
            'date_unloading' => $this->input->post('date_unloading'),
            'date_check' => $this->input->post('date_check'),
            'administrator' => $this->input->post('administrator'),
            'head' => $this->input->post('head'),
            'branch' => $this->input->post('branch'),
            );
        $this->db->trans_begin();
        $this->db->insert('trans_receive', $dataheader);
        $jumlah_barang = count($_POST['idet_barang']);

        for ($b = 0; $b < $jumlah_barang; ++$b) {
            $detil = array(
                    'no_po' => $nopo,
                    'id_barang' => $_POST['idet_barang'][$b],
                    'nama_barang' => $_POST['nm_barangb'][$b],
                    'qty_i' => $_POST['qty_ib'][$b],
                    'qty_pl' => $_POST['qty_plb'][$b],
                    'bagus' => $_POST['qty_bagus_t'][$b],
                    'rusak' => $_POST['qty_rusak_t'][$b],
                );
            $this->Receiving_model->insert_receive_detail_barang($detil);
        }

        $jumlah = count($_POST['qty_bagus']);

        for ($i = 0; $i < $jumlah; ++$i) {
            $detil = array(
                    'no_po' => $nopo,
                    'id_barang' => $_POST['id_barangc'][$i],
                    'id_koli' => $_POST['id_koli'][$i],
                    'nama_koli' => $_POST['nama_koli'][$i],
                    'qty_i' => $_POST['qty_i'][$i],
                    'qty_pl' => $_POST['qty_pl'][$i],
                    'bagus' => $_POST['qty_bagus'][$i],
                    'rusak' => $_POST['qty_rusak'][$i],
                    'keterangan' => $_POST['keterangan'][$i],
                );
            $this->Receiving_model->insert_receive_detail_koli($detil);
        }
        $this->db->query("UPDATE `trans_po_header` SET `status` = 'RECEIVING' WHERE `trans_po_header`.`no_po` ='$nopo';");
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

    //Create New Receiving
    public function create()
    {
        if ($this->uri->segment(3) == '') {
            $data = $this->Purchaseorder_model->order_by('no_po', 'ASC')->find_all_by(array('status' => 'INVOICE'));
        } else {
            $data = $this->Purchaseorder_model->order_by('no_po', 'ASC')->find_all_by(array('id_supplier' => $this->uri->segment(3), 'status' => 'PO'));
        }
        $supplier = $this->Purchaseorder_model->get_data('1=1', 'supplier');

        $this->template->set('supplier', $supplier);
        $this->template->set('results', $data);

        $this->template->title('Proses Receiving');
        $this->template->render('list_po');
    }

    //Proses Receiving
    public function proses()
    {
        $session = $this->session->userdata('app_session');
        $query = $this->db->query("SELECT * FROM `cabang` where kdcab='$session[kdcab]'");
        $row = $query->row();

        $getparam = explode(';', $_GET['param']);
        //$and = " proses_po !='1' ";
        $header = $this->Detailpurchaseorder_model->get_where_in('no_po', $getparam, 'trans_po_header');
        $detail = $this->Detailpurchaseorder_model->get_where_in('no_po', $getparam, 'trans_po_detail');
        $this->template->set('cabang', $row);
        $this->template->set('headerpo', $header);
        $this->template->set('detailpo', $detail);
        $this->template->title('Input Data Receiving');
        $this->template->render('receiving_form');
    }

    public function saveheaderreceiving()
    {
        $session = $this->session->userdata('app_session');
        $norec = $this->Receiving_model->generate_noreceive($session['kdcab']);
        $dataheader = array(
            'no_receiving' => $norec,
            'tglreceive' => $this->input->post('tglreceive'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'tgl_sjsupplier' => $this->input->post('tgldosupp'),
            'no_sjsupplier' => $this->input->post('no_do_supplier'),
            'noinvoice' => $norec,
            'tglinvoice' => $this->input->post('tglreceive'),
            'container_no' => $this->input->post('container_no'),
            'date_unloading' => $this->input->post('date_unloading'),
            'date_check' => $this->input->post('date_check'),
            'administrator' => $this->input->post('administrator'),
            'head' => $this->input->post('head'),
            'branch' => $this->input->post('branch'),
            );

        $detail = array(
            'id_detail_po' => $_POST['id_po_to_received'],
            );

        $this->db->trans_begin();
        for ($i = 0; $i < count($detail['id_detail_po']); ++$i) {
            $key = array(
            'id_detail_po' => $_POST['id_po_to_received'][$i],
            );
            $getitempo = $this->Detailpurchaseorder_model->find_by($key);

            $detbarang = $this->Barang_stock_model->find_by(array('id_barang' => $getitempo->id_barang));
            $id_barang = @$detbarang->id_barang;
            $nm_barang = @$detbarang->nm_barang;
            $kategori = @$detbarang->kategori;
            $jenis = @$detbarang->jenis;
            $brand = @$detbarang->brand;
            $satuan = @$detbarang->satuan;
            $qty = $_POST['qty_received'][$i] + $_POST['qty_broken'][$i];
            $qty_stock = @$detbarang->qty_stock;
            $qty_avl = @$detbarang->qty_avl;
            $nilai_barang = $getitempo->harga_satuan;
            $qty_po = $getitempo->qty_po;
            $tipe_adjusment = 'IN';
            $date = date('Y-m-d');

            $id_st = $this->Trans_stock_model->gen_st($this->auth->user_cab()).$i;

            $tipe = 'IN';
            $jenis_trans = 'IN_Pembelian';
            $qty_stock_new = $qty_stock + $qty;
            $qty_avl_new = $qty_avl + $qty;

            $data_adj_trans = array(
                        'id_st' => $id_st,
                        'tipe' => $tipe,
                        'jenis_trans' => $jenis_trans,
                        'noreff' => $norec,
                        'id_barang' => $id_barang,
                        'nm_barang' => $nm_barang,
                        'jenis' => $jenis,
                        'kategori' => $kategori,
                        'brand' => $brand,
                        'satuan' => $satuan,
                        'kdcab' => $this->auth->user_cab(),
                        'date_stock' => date('Y-m-d H:i:s'),
                        'qty' => $qty,
                        'qty_stock_awal' => $qty_stock,
                        'qty_avl_awal' => $qty_avl,
                        'qty_stock_akhir' => $qty_stock_new,
                        'qty_avl_akhir' => $qty_avl_new,
                        'nilai_barang' => $nilai_barang,
                        'notes' => '',
                        );
            $this->Trans_stock_model->insert($data_adj_trans); //modules trans_stok

            $detail_receive = array(
                'nolpb' => $norec,
                'po_no' => $getitempo->no_po,
                'kodebarang' => $getitempo->id_barang,
                'namabarang' => $getitempo->nm_barang,
                'hargabeli' => $getitempo->harga_satuan,
                'jumlah' => $_POST['qty_received'][$i],
                'namabarang' => $getitempo->nm_barang,
                'Satuan' => $getitempo->satuan,
                'tglreceive' => $this->input->post('tglreceive'),
                'user' => $session['id_user'],
                'noinvoice' => $norec,
                'tglinvoice' => $this->input->post('tglreceive'),
                'no_sjsupplier' => $this->input->post('no_do_supplier'),
                'tgl_sjsupplier' => $this->input->post('tgldosupp'),
                'barang_rusak' => $_POST['qty_broken'][$i],
                );
            $this->db->insert('receive_detail', $detail_receive);

            //Update STOK REAL dan AVL
            $count = $this->Receiving_model->cek_data(array('id_barang' => $getitempo->id_barang, 'kdcab' => $session['kdcab']), 'barang_stock');
            $this->db->where(array('id_barang' => $getitempo->id_barang, 'kdcab' => $session['kdcab']));
            $this->db->update('barang_stock', array('qty_stock' => $count->qty_stock + $_POST['qty_received'][$i], 'qty_avl' => $count->qty_avl + $_POST['qty_received'][$i], 'qty_rusak' => $count->qty_rusak + $_POST['qty_broken'][$i]));
            //Update STOK REAL

            if ($qty_po == $qty) {
                $detail_po = array(
                    'qty_acc' => $qty,
                    'proses_po' => 1,
                    );
            } else {
                $detail_po = array(
                    'qty_acc' => $qty,
                    'proses_po' => 'pending',
                    );
            }

            $this->Receiving_model->update_po_detail($getitempo->id_detail_po, $detail_po);
            //$statuz="RCV";
           // $this->Receiving_model->update_po_status($getitempo->no_po, $statuz);
        }

        $group = array();
        $group_pr = array();
        for ($iz = 0; $iz < count($detail['id_detail_po']); ++$iz) {
            $group_pr[$_POST['no_po'][$iz]] = $group_pr[$_POST['no_po'][$iz]] + $this->input->post('qty_po')[$iz];
            $group[$_POST['no_po'][$iz]] = $group[$_POST['no_po'][$iz]] + ($_POST['qty_received'][$iz] + $_POST['qty_broken'][$iz]);
        }

        foreach ($group as $type => $total) {
            if ($group["$type"] == $group_pr["$type"]) {
                $this->db->where(array('no_po' => $type))
                ->update('trans_po_header', array('status' => 'RCV'));
            } else {
                $this->db->where(array('no_po' => $type))
                ->update('trans_po_header', array('status' => 'PO-PENDING'));
            }
        }

        //Update counter NO_DO
        $count = $this->Receiving_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
        $this->db->where(array('kdcab' => $session['kdcab']));
        $this->db->update('cabang', array('no_receive' => $count->no_receive + 1));
        //Update counter NO_DO
        $this->db->insert('trans_receive', $dataheader);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => 'GAGAL, simpan data..!!!',
            );
        } else {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => 'SUKSES, simpan data..!!!',
            );
        }
        echo json_encode($param);
    }

    public function print_request($norec)
    {
        $mpdf = new mPDF('', '', '', '', '', '', '', '', '', '');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $rec_data = $this->Receiving_model->find_data('trans_receive', $norec, 'po_no');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        //$detail = $this->Detailreceiving_model->find_all_by(array('nolpb' => $norec));

        $this->template->set('header', $rec_data);
        $this->template->set('no_po', $norec);
        //$this->template->set('detail', $detail);

        $show = $this->template->load_view('print_data', $data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
}
