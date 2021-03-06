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
        $session = $this->session->userdata('app_session');
        $nopo = $this->input->post('no_pr');
        $kdcab = $this->input->post('kdcab');

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
            $harga_barang = $_POST['hargab'][$b];
            //Update STOK REAL dan AVL
            $count = $this->Receiving_model->cek_data(array('id_barang' => $_POST['idet_barang'][$b], 'kdcab' => $session['kdcab']), 'barang_stock');
            if (!empty($count->landed_cost)) {
                $hrg = ($harga_barang * $_POST['qty_bagus_t'][$b]) + ($count->qty_stock * $count->landed_cost);
                $landed_cost = $hrg / ($count->qty_stock + $_POST['qty_bagus_t'][$b]);
            } else {
                $landed_cost = "$harga_barang";
            }

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

        /* INPUT JAVH */
        $querycek = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `javh` WHERE nomor LIKE '%$kdcab-A-JP$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek->num_rows() > 0) {
            $row = $querycek->row();
            $kode = $row->kode + 1;
        } else {
            $kode = 1;
        }

        $bikin_kode = str_pad($kode, 4, '0', STR_PAD_LEFT);
        $kode_jadi = "$kdcab-A-JP$thb$bikin_kode";

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

        /* INPUT JURNAL */
        $querycek_j = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `jurnal` WHERE nomor LIKE '%$kdcab-A-JP$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek_j->num_rows() > 0) {
            $row = $querycek_j->row();
            $kodej = $row->kode + 1;
        } else {
            $kodej = 1;
        }

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

            $this->db->insert('jurnal', $jurnal_d);

    		    $this->db->insert('jurnal', $jurnal_ka);

            $this->db->insert('jurnal', $jurnal_k);

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

            $this->db->insert('jurnal', $jurnal_d);

            $this->db->insert('jurnal', $jurnal_k);
        }

        //Update counter NO_REC
        $count = $this->Receiving_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
        $this->db->where(array('kdcab' => $session['kdcab']));
        $this->db->update('cabang', array('no_receive' => $count->no_receive + 1));
        //Update counter NO_REC



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
                'msg' => 'SUKSES,