<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Purchaseorder
 */

class Purchaseorder_pusat extends Admin_Controller
{
    //Permission
    protected $viewPermission = 'Purchaseorder.View';
    protected $addPermission = 'Purchaseorder.Add';
    protected $managePermission = 'Purchaseorder.Manage';
    protected $deletePermission = 'Purchaseorder.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf', 'upload', 'Image_lib'));

        $this->load->model(array('Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Purchaseorder/Purchaseorder_pusat_model',
                                 'Purchaserequest/Purchaserequest_model',
                                 'Purchaserequest/Detailpurchaserequest_model',
                                 'Purchaserequest/Purchaserequestpending_model',
                                 'Purchaserequest/Detailpurchaserequestpending_model',
                                 //'Pendingpr/Pendingpr_model',
                                 //'Pendingpr/Detailpendingpr_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model',
                                ));

        $this->template->title('Purchase Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Purchaseorder_model->order_by('no_po', 'DESC')->find_all_by(array('kdcab'=>$this->auth->user_cab()));
        $this->template->set('results', $data);
        $this->template->title('Purchase Order');
        $this->template->render('pusat/list');
    }

    public function konfirmasi()
    {
        $sup = $this->uri->segment(4);
        $no = $this->uri->segment(5);
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
        $this->template->title('Konfirmasi Purchase Order Ke Profram Invoice');
        $this->template->render('pusat/konfirmasi');
    }

    public function konfirmasi_save()
    {
		//echo"<pre>";print_r($this->input->post());exit;
        $session = $this->session->userdata('app_session');
        $nopo = $this->input->post('no_pr');

        $headerpr = array(
            'fiskal' 			=> str_replace(',','',$this->input->post('total_fiskal')),
            'non_fiskal' 		=> str_replace(',','',$this->input->post('total_nofiskal')),
            'ppn' 				=> str_replace(',','',$this->input->post('total_ppn')),
            'dollar' 			=> str_replace(',','',$this->input->post('kurs_usd')),
            'rupiah' 			=> str_replace(',','',$this->input->post('kurs_rp')),
            'rupiah_total' 		=> str_replace(',','',$this->input->post('total_rupiah')),
            'no_pi' 			=> $this->input->post('no_pi'),
            'status' 			=> 'PI',
            'shipping' 			=> $this->input->post('shipping'),
            'status_cabang' 	=> 'HUTANG'
        );

        $this->db->trans_begin();

        $this->Purchaseorder_pusat_model->update_po_header($nopo, $headerpr);

        $jumlah = count($_POST['idet']);

        for ($i = 0; $i < $jumlah; ++$i) {
            ++$no;
            if (str_replace(',','',$this->input->post('kurs_usd')) == 0) {
                $hrg_satuan = str_replace(',','',$_POST['usd'][$i]);
            } else {
                $hrg_satuan = str_replace(',','',$_POST['harga_satuan'][$i]);
            }
            $detil = array(
					'qty_acc' 		=> str_replace(',','',$_POST['qty_acc'][$i]),
					'harga_satuan' 	=> $hrg_satuan,
					'persen_fiskal' => str_replace(',','',$_POST['fiskal'][$i]),
					'fiskal' 		=> str_replace(',','',$_POST['subtotal'][$i]),
					'non_fiskal' 	=> str_replace(',','',$_POST['subtotal_no'][$i]),
					'ppn' 			=> str_replace(',','',$_POST['subtotal_ppn'][$i]),
					'harga_rp' 		=> str_replace(',','',$_POST['rupiah'][$i]),
				);
            $iddet = $_POST['idet'][$i];
            $this->Purchaseorder_pusat_model->update_po_detail($iddet, $detil);
        }

        $jumlahx = count($_POST['pembayaran']);

        if ($_POST['opsi_top'] == 'persen') {
          for ($i = 0; $i < $jumlahx; ++$i) {
            ++$no;
            $detil = array(
              'no_po' 			=> "$nopo",
              'persen' 			=> $_POST['pembayaran'][$i],
              'perkiraan_bayar' => $_POST['perkiraan_bayar'][$i],
              'status' 			=> 'open',
              'tipe_payment'	=>'persen'
            );
            $this->Purchaseorder_pusat_model->insert_po_payment($detil);
          }
        }else {
          for ($i = 0; $i < $jumlahx; ++$i) {
            ++$no;
            $pers = $_POST['pembayaran'][$i]/str_replace(',','',$this->input->post('total_rupiah'))*100;
            $detil = array(
              'no_po' 			=> "$nopo",
              'persen' 			=> $pers,
              'nominal' 		=> $_POST['pembayaran'][$i],
              'perkiraan_bayar' => $_POST['perkiraan_bayar'][$i],
              'status' 			=> 'open',
              'tipe_payment'	=> 'nominal'
            );
            $this->Purchaseorder_pusat_model->insert_po_payment($detil);
          }
        }

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
