<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Profroma_invoice extends Admin_Controller
{
    protected $viewPermission = 'Cbm.View';
    protected $addPermission = 'Cbm.Add';
    protected $managePermission = 'Cbm.Manage';
    protected $deletePermission = 'Cbm.Delete';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_profroma_invoice', 'pi');

        $this->template->title('Manage Data PI');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $pi = $this->pi->all();

        $this->template->set('results', $pi);
        $this->template->title('Data Profroma Invoice');
        $this->template->render('index');
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

        $this->db->select('*');
        $this->db->from('supplier_cbm');
        $this->db->join('cbm', 'cbm.id_cbm = supplier_cbm.id_cbm', 'left');
        $this->db->where('supplier_cbm.id_supplier', $sup);
        $cbm_sup = $this->db->get()->result();

        $pr_hader = $query = $this->db->query("SELECT * FROM `trans_pr_header` where no_pr='$no'")->row();
        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");

        $this->template->set('supplier', $sup);
        $this->template->set('no_pr', $no);
        $this->template->set('pr_tambahan', $query_pr_tambahan);
        $this->template->set('pr_hader', $pr_hader);
        $this->template->set('cbm_sup', $cbm_sup);
        $this->template->set('itembarang', $itembarang);
        $this->template->title('Konfirmasi Invoice');
        $this->template->render('konfirmasi');
    }

    public function konfrimasi_save()
    {
        $session = $this->session->userdata('app_session');
        $nopo = $this->input->post('no_pr');
        $inv = $this->input->post('no_invoice');
        $headerpr = array(
            'no_po' => $this->input->post('no_pr'),
            'no_invoice' => $this->input->post('no_invoice'),
            'start_produksi' => $this->input->post('start_produksi'),
            'finish_produksi' => $this->input->post('finish_produksi'),
            'proses_shipping' => $this->input->post('proses_shipping'),
            'shipping' => $this->input->post('shipping'),
            'eta' => $this->input->post('eta'),
            'tgl_invoice' => date('Y-m-d'),
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
        );
        $thb = date('m').substr(date('Y'), 2, 2);
        $querypo = $this->db->query("SELECT * FROM `trans_po_header` WHERE no_po='$nopo' ");

        $rowpo = $querypo->row();
        $querycek_j = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `jurnal` WHERE nomor LIKE '%$rowpo->kdcab-A-JP$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek_j->num_rows() > 0) {
            $row = $querycek_j->row();
            $kodej = $row->kode + 1;
        } else {
            $kodej = 1;
        }

        $bikin_kode_j = str_pad($kodej, 4, '0', STR_PAD_LEFT);
        $kode_jadi_j = "$rowpo->kdcab-A-JP$thb$bikin_kode_j";

        $ap = array(
            'no_invoice' => $this->input->post('no_invoice'),
            'tgl_invoice' => date('Y-m-d'),
            'no_po' => $this->input->post('no_pr'),
            'id_supplier' => $rowpo->id_supplier,
            'nm_supplier' => $rowpo->nm_supplier,
            'bln' => date('m'),
            'thn' => date('Y'),
            'saldo_awal' => $rowpo->rupiah_total,
            'debet' => 0,
            'kredit' => 0,
            'saldo_akhir' => $rowpo->rupiah_total,
            'kdcab' => $rowpo->kdcab,
        );

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
            'keterangan' => "Hutang#$nopo#$inv#$rowpo->nm_supplier",
            'no_reff' => $inv,
            'debet' => 0,
            'kredit' => $rowpo->rupiah_total,
        );

        $querycek = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `javh` WHERE nomor LIKE '%$rowpo->kdcab-A-JS$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek->num_rows() > 0) {
            $row = $querycek->row();
            $kode = $row->kode + 1;
        } else {
            $kode = 1;
        }

        $bikin_kode = str_pad($kode, 4, '0', STR_PAD_LEFT);
        $kode_jadi = "$rowpo->kdcab-A-JS$thb$bikin_kode";

        $javh = array(
            'nomor' => $kode_jadi,
            'tgl' => date('Y-m-d'),
            'jml' => $rowpo->rupiah_total,
            'kdcab' => $rowpo->kdcab,
            'jenis' => 'V',
            'keterangan' => "Hutang Invoice #$inv#$nopo#$rowpo->nm_supplier",
            'bulan' => date('m'),
            'tahun' => date('Y'),
            'user_id' => $session['id_user'],
        );

        $this->db->trans_begin();
        $this->pi->insert($headerpr);

        $this->db->insert('ap', $ap);

        // $this->db->insert('javh',$javh);

        // $this->db->insert('jurnal',$jurnal_d);

        // $this->db->insert('jurnal',$jurnal_k);

        $jumlah = count($_POST['idet']);

        for ($i = 0; $i < $jumlah; ++$i) {
            $detil = array(
                        'qty_i' => $_POST['qty_i'][$i],
                    );
            $iddet = $_POST['idet'][$i];
            $this->pi->update($iddet, $detil);
        }

        $this->db->query("UPDATE `trans_po_header` SET `status` = 'INVOICE' WHERE `trans_po_header`.`no_po` ='$nopo';");
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

    public function konfrimasi_save_lama()
    {
        $session = $this->session->userdata('app_session');
        $nopo = $this->input->post('no_pr');

        $headerpr = array(
            'no_po' => $this->input->post('no_pr'),
            'no_invoice' => $this->input->post('no_invoice'),
            'start_produksi' => $this->input->post('start_produksi'),
            'finish_produksi' => $this->input->post('finish_produksi'),
            'proses_shipping' => $this->input->post('proses_shipping'),
            'shipping' => $this->input->post('shipping'),
            'eta' => $this->input->post('eta'),
            'tgl_invoice' => date('Y-m-d'),
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
        );
        $this->db->trans_begin();
        $this->pi->insert($headerpr);

        $jumlah = count($_POST['idet']);

        for ($i = 0; $i < $jumlah; ++$i) {
            $detil = array(
                        'qty_i' => $_POST['qty_i'][$i],
                    );
            $iddet = $_POST['idet'][$i];
            $this->pi->update($iddet, $detil);
        }

        $this->db->query("UPDATE `trans_po_header` SET `status` = 'INVOICE' WHERE `trans_po_header`.`no_po` ='$nopo';");
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
