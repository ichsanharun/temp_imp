<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hutang extends Admin_Controller {


	public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_hutang', 'hutang');
        
        $this->template->title('Manage Data Hutang');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
        
    }

	public function index()
	{
        $pi= $this->db->query("SELECT a.*,b.id_supplier,b.nm_supplier, (a.persen*b.rupiah_total)/100 as hutang, (a.persen*b.rupiah_total/100)/b.rupiah as dollar FROM `trans_po_payment` as a, trans_po_header as b WHERE a.no_po=b.no_po ");
       
        $this->template->set('results', $pi);
        $this->template->title('Data Pelunasan Pembelian');
        $this->template->render('index');
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
        $session    = $this->session->userdata('app_session');
        $id         = $this->input->post('id');
        $nopo       = $this->input->post('no_po');
        $inv        =$this->input->post('no_invoice');
        $supp       =$this->input->post('supplier');
        $ket        =$this->input->post('keterangan');
        $jumlah     =$this->input->post('jumlah');
        $kdcab      =$session['kdcab'];
        $tgl        =date('Y-m-d');
        $thb=date('m').substr(date('Y'), 2,2);
        if ($this->input->post('myRadios')=="1") {
            $bln=date('n');
            $thn=date('Y');
            $query = $this->db->query("SELECT * FROM `coa` WHERE `kdcab` LIKE '%$kdcab-A%' AND `level` LIKE '%5%' AND `no_perkiraan` LIKE '%1101%' AND `bln` LIKE '%$bln%' AND `thn` LIKE '%$thn%' ORDER BY `no_perkiraan` DESC LIMIT 1");
            $keterangan="Kas";
            $row = $query->row();
            $no_perkiraan="$row->no_perkiraan";
            $bukti  ="";
            
            $querycek_j = $this->db->query("SELECT RIGHT(nomor,5) AS kode FROM `jurnal` WHERE nomor LIKE '%$kdcab-AKK$thb%' ORDER BY nomor DESC LIMIT 1 ");
            if ($querycek_j->num_rows() > 0)
            {
                $row = $querycek_j->row();
                $kodej=$row->kode+1;
            }else {
                $kodej = 1;
            }
            
            $bikin_kode_j = str_pad($kodej, 5, "0", STR_PAD_LEFT);
            $kode_jadi_j = "$kdcab-AKK$thb$bikin_kode_j";
            
        } else {
            $no_perkiraan=$this->input->post('no_perkiraan');
            $keterangan="Bank";
            $bukti  ="bukti ".$this->input->post('bukti');
            $querycek_j = $this->db->query("SELECT RIGHT(nomor,5) AS kode FROM `jurnal` WHERE nomor LIKE '%$kdcab-ABK$thb%' ORDER BY nomor DESC LIMIT 1 ");
            if ($querycek_j->num_rows() > 0)
            {
                $row = $querycek_j->row();
                $kodej=$row->kode+1;
            }else {
                $kodej = 1;
            }
            
            $bikin_kode_j = str_pad($kodej, 5, "0", STR_PAD_LEFT);
            $kode_jadi_j = "$kdcab-ABK$thb$bikin_kode_j";
        }
       
        $jurnal_d   = array(
            'tipe'          => "BUK",
            'nomor'         => $kode_jadi_j,
            'tanggal'       => date('Y-m-d'),
            'no_perkiraan'  => "1108-01-01",
            'keterangan'    => "$ket#$nopo#$inv#$supp#$bukti",
            'no_reff'       => $inv,
            'debet'         => $jumlah,
            'kredit'        => 0,
        );
        
        $jurnal_k   = array(
            'tipe'          => "BUK",
            'nomor'         => $kode_jadi_j,
            'tanggal'       => date('Y-m-d'),
            'no_perkiraan'  => "$no_perkiraan",
            'keterangan'    => "$keterangan#$nopo#$inv#$supp",
            'no_reff'       => $inv,
            'debet'         => 0,
            'kredit'        => $jumlah,
        );
        
        
        $querycek = $this->db->query("SELECT RIGHT(nomor,4) AS kode FROM `javh` WHERE nomor LIKE '%$kdcab-A-JS$thb%' ORDER BY nomor DESC LIMIT 1 ");
        if ($querycek->num_rows() > 0)
        {
            $row = $querycek->row();
            $kode=$row->kode+1;
        }else {
            $kode = 1;
        }
        
        $bikin_kode = str_pad($kode, 4, "0", STR_PAD_LEFT);
        $kode_jadi = "$kdcab-A-JS$thb$bikin_kode";
        $javh   = array(
            'nomor'     => $kode_jadi,
            'tgl'       => date('Y-m-d'),
            'jml'       => $jumlah,
            'kdcab'     => $kdcab,
            'jenis'     => "V",
            'keterangan'=> "$keterangan #$inv#$nopo#$supp",
            'bulan'     => date('m'),
            'tahun'     => date('Y'),
            'user_id'   => $session['id_user'],
        );
        
         $querycek_ap = $this->db->query("SELECT * FROM `ap` WHERE no_po='$nopo' ");
         $row_ap = $querycek_ap->row();
         $debit=$jumlah+$row_ap->debet;
         
         $saldo_akhir=$row_ap->saldo_awal-$debit;
         
         $this->db->trans_begin();
            $this->db->insert('javh',$javh);
            
            $this->db->insert('jurnal',$jurnal_d);
            
            $this->db->insert('jurnal',$jurnal_k);
            
            $this->db->query("UPDATE `trans_po_payment` SET `status` = 'close',tgl_bayar='$tgl', bayar='$jumlah' WHERE id='$id';");
            
            $this->db->query("UPDATE `ap` SET `debet` = '$debit',saldo_akhir='$saldo_akhir' WHERE no_po='$nopo';");
            
             if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $param = array(
                'save' => 0,
                'msg' => "GAGAL, perubahan..!!!"
                );
            }
            else
            {
                $this->db->trans_commit();
                $param = array(
                'save' => 1,
                'msg' => "SUKSES, melakukan perubahaan..!!!"
                );
            }
        
        echo json_encode($param);
        
    }

}
