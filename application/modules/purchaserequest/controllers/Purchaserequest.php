<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Purchaserequest extends Admin_Controller
{
    //Permission
    protected $viewPermission = 'Purchaserequest.View';
    protected $addPermission = 'Purchaserequest.Add';
    protected $managePermission = 'Purchaserequest.Manage';
    protected $deletePermission = 'Purchaserequest.Delete';

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf', 'upload', 'Image_lib'));
        $this->load->model(array('Purchaserequest/Purchaserequest_model',
                                              'Purchaserequest/Detailpurchaserequest_model',
                                              'Purchaserequest/Purchaserequesttmp_model',
                                              'Purchaserequest/Purchaserequest_pusat_model',
                                  'Aktifitas/aktifitas_model',
                                ));
        $this->template->title('Purchase Request');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set('Asia/Bangkok');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        if ($this->auth->user_cab() == "100") {
          $data = $this->Purchaserequest_model
          ->select('*,trans_pr_header.no_pr AS nopr')
          ->join('cabang', 'trans_pr_header.kdcab = cabang.kdcab', 'left')
          ->where_in('trans_pr_header.proses_po', array('Proses', 'REVISI'))
          ->order_by('trans_pr_header.no_pr', 'ASC')->find_all();
        }else {
          $data = $this->Purchaserequest_model
          ->select('*,trans_pr_header.no_pr AS nopr')
          ->join('cabang', 'trans_pr_header.kdcab = cabang.kdcab', 'left')
          ->where(array('trans_pr_header.kdcab'=>$this->auth->user_cab()))
          ->where_in('trans_pr_header.proses_po', array('Proses', 'REVISI'))
          ->order_by('trans_pr_header.no_pr', 'ASC')->find_all();
        }
        //$data = $this->db->query("SELECT * FROM trans_pr_header LEFT JOIN barang_master ON trans_pr_header.id_barang = barang_master.id_barang order by no_pr asc")->result();
        $this->template->set('results', $data);
        $this->template->title('Purchase Request');
        $this->template->render('index');
    }

    public function create_pr()
    {
        $session = $this->session->userdata('app_session');
        if ($this->uri->segment(3) == '') {
            $itembarang = $this->Purchaserequest_model->pilih_item($session['kdcab'])->result();
        } else {
            $this->db->select('*');
            $this->db->from('supplier_barang');
            $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
            $this->db->where('supplier_barang.id_supplier', $this->uri->segment(3));
            $itembarang = $this->db->get()->result();
        }

        $this->db->select('*');
        $this->db->from('supplier_cbm');
        $this->db->join('cbm', 'cbm.id_cbm = supplier_cbm.id_cbm', 'left');
        $this->db->where('supplier_cbm.id_supplier', $this->uri->segment(3));
        $cbm_sup = $this->db->get()->result();

        //$supplier = $this->Purchaserequest_model->get_data('1=1','supplier');
        $supplier = $this->Purchaserequest_model->get_data('1=1', 'supplier');
        $cabang = $this->Purchaserequest_model->find_all_by(array('kdcab' => $session['kdcab']));
        $this->template->set('itembarang', $itembarang);
        $this->template->set('supplier', $supplier);
        $this->template->set('cabang', $cabang);
        $this->template->set('cbm_sup', $cbm_sup);
        $this->template->title('Input Purchase Request');
        $this->template->render('pr_form_new');
    }

    public function save_new()
    {
        $session = $this->session->userdata('app_session');
        $tgl = $this->input->post('tglpr');
        $nopr = $this->Purchaserequest_model->generate_nopr($session['kdcab'],$tgl);
        $headerpr = array(
            'no_pr' => $nopr,
            'tgl_pr' => $this->input->post('tglpr'),
            'kdcab' => $session['kdcab'],
            'plan_delivery_date' => $this->input->post('tglpr'),
            'id_cbm' => $this->input->post('radio-group'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'total_cbm' => $this->input->post('cbm_tot'),
            'proses_po' => 'Proses',
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
        );

        $this->db->trans_begin();
        $this->db->select('*');
        $this->db->from('supplier_barang');
        $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
        $this->db->where('supplier_barang.id_supplier', $this->input->post('idsupplier'));
        $itembarang = $this->db->get()->result();

        $jumlah = count($_POST['qtyt']);

        for ($i = 0; $i < $jumlah; ++$i) {
            $komponen = $_POST['komponen'][$i];
            $datasm = array(
                                'no_pr' => $nopr,
                                'id_barang' => $_POST['barang_t'][$i],
                                'nm_komponen' => $_POST['komponen'][$i],
                                'qty' => $_POST['qtyt'][$i],
                            );

            if (!empty($_POST['qtyt'][$i])) {
                $this->Purchaserequest_model->insert_trans_pr_tambahan($datasm);
            }
        }

        $noo = 0;
        foreach ($itembarang as $kc => $val) {
            ++$noo;
            $detailpr = array(
                    'no_pr' => $nopr,
                    'id_barang' => $val->id_barang,
                    'nm_barang' => $val->nm_barang,
                    'satuan' => $val->satuan,
                    'qty_pr' => $this->input->post("qty$noo"),
                    'harga_satuan' => $val->harga,
                    'sub_total_pr' => 0,
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => date('Y-m-d H:i:s'),
                    );
            if ($this->input->post("qty$noo") != 0) {
                $this->db->insert('trans_pr_detail', $detailpr);
            }
        }

        $this->db->insert('trans_pr_header', $headerpr);

        //Update counter NO_pr
        $counter = $this->Purchaserequest_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
        $this->db->where(array('kdcab' => $session['kdcab']));
        $this->db->update('cabang', array('no_pr' => $counter->no_pr + 1));
        //END Update counter NO_pr

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $param = array(
                'save' => 0,
                'msg' => 'GAGAL, tambah item barang..!!!',
                );
        } else {
            $this->db->trans_commit();
            $param = array(
                'save' => 1,
                'msg' => 'SUKSES, tambah item barang..!!!',
                );
        }

        echo json_encode($param);
    }

    public function revisi()
    {
        $sup = $this->uri->segment(3);
        $no = $this->uri->segment(4);
        $session = $this->session->userdata('app_session');
        $this->db->select('*');
        $this->db->from('supplier_barang');
        $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
        $this->db->where('supplier_barang.id_supplier', $sup);
        $itembarang = $this->db->get()->result();

        $this->db->select('*');
        $this->db->from('supplier_cbm');
        $this->db->join('cbm', 'cbm.id_cbm = supplier_cbm.id_cbm', 'left');
        $this->db->where('supplier_cbm.id_supplier', $sup);
        $cbm_sup = $this->db->get()->result();

        $pr_hader = $query = $this->db->query("SELECT * FROM `revisi_pr_header` where no_pr='$no' and koreksi='Pusat' and status='2'")->row();
        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");

        $this->template->set('supplier', $sup);
        $this->template->set('no_pr', $no);
        $this->template->set('pr_tambahan', $query_pr_tambahan);
        $this->template->set('pr_hader', $pr_hader);
        $this->template->set('cbm_sup', $cbm_sup);
        $this->template->set('itembarang', $itembarang);
        $this->template->title('Revisi Purchase Request');
        $this->template->render('revisi');
    }

    public function revisi_save()
    {
        $session = $this->session->userdata('app_session');
        $nopr = $this->input->post('no_pr');

        $headerpr = array(
            'id_cbm' => $this->input->post('radio-group'),
            'total_cbm' => $this->input->post('cbm_tot'),
        );

        $query_id = $this->db->query("SHOW TABLE STATUS LIKE 'revisi_pr_header' ");
        $row_id = $query_id->row();
        $id_revisi = $row_id->Auto_increment;

        $query_pr = $this->db->query("SELECT * FROM `trans_pr_header` WHERE no_pr='$nopr'");
        $rowx = $query_pr->row();
        $headerprx = array(
                'no_pr' => $nopr,
                'tgl_pr' => $rowx->tgl_pr,
                'kdcab' => $rowx->kdcab,
                'id_supplier' => $rowx->id_supplier,
                'total_cbm' => $this->input->post('cbm_tot'),
                'id_cbm' => $this->input->post('radio-group'),
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $session['id_user'],
                'koreksi' => 'Cabang',
                'keterangan' => 'Pembaharuan revisi',
                'status' => '0',
            );
        $this->db->trans_begin();
        $this->Purchaserequest_pusat_model->insert_revisi_pr_header($headerprx);

        $this->Purchaserequest_model->delete_pr_tambahan($nopr);

        $jumlah = count($_POST['qtyt']);

        for ($i = 0; $i < $jumlah; ++$i) {
            $datasmx = array(
                                'no_pr' => $nopr,
                                'id_barang' => $_POST['barang_t'][$i],
                                'nm_komponen' => $_POST['komponen'][$i],
                                'qty' => $_POST['qtyt'][$i],
                            );

            if (!empty($_POST['qtyt'][$i])) {
                $this->Purchaserequest_model->insert_trans_pr_tambahan($datasmx);
            }

            $komponen = $_POST['komponen'][$i];
            $datasm = array(
                                'id_revisi_pr' => "$id_revisi",
                                'id_barang' => $_POST['barang_t'][$i],
                                'nm_komponen' => $_POST['komponen'][$i],
                                'qty' => $_POST['qtyt'][$i],
                            );

            if (!empty($_POST['qtyt'][$i])) {
                $this->Purchaserequest_pusat_model->insert_revisi_pr_tambahan($datasm);
            }
        }

        $this->db->select('*');
        $this->db->from('supplier_barang');
        $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
        $this->db->where('supplier_barang.id_supplier', $this->input->post('idsupplier'));
        $itembarang = $this->db->get()->result();
        $this->Purchaserequest_model->delete_pr_detail($nopr);
        $noo = 0;
        foreach ($itembarang as $kc => $val) {
            ++$noo;
            $detailpr = array(
                    'id_revisi_pr' => "$id_revisi",
                    'id_barang' => $val->id_barang,
                    'nm_barang' => $val->nm_barang,
                    'qty' => $this->input->post("qty_c$noo"),
                    );
            if ($this->input->post("qty$noo") != 0) {
                $this->Purchaserequest_pusat_model->insert_revisi_pr_detail($detailpr);
            }

            $detailprx = array(
                    'no_pr' => $nopr,
                    'id_barang' => $val->id_barang,
                    'nm_barang' => $val->nm_barang,
                    'satuan' => $val->satuan,
                    'qty_pr' => $this->input->post("qty_c$noo"),
                    'harga_satuan' => $val->harga,
                    'sub_total_pr' => 0,
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => date('Y-m-d H:i:s'),
                    );
            if ($this->input->post("qty$noo") != 0) {
                $this->db->insert('trans_pr_detail', $detailprx);
            }
        }
        $id_rev = $this->input->post('id_revisi');

        $this->db->query("UPDATE `revisi_pr_header` SET `status` = '3' WHERE `revisi_pr_header`.`id` = '$id_rev';");
        $this->Purchaserequest_model->update_pr_header($nopr, $headerpr);

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

    public function list_pr_json()
    {
        $requestData = $_REQUEST;
        $fetch = $this->Purchaserequest_model->fetch_data_pr($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);

        $totalData = $fetch['totalData'];
        $totalFiltered = $fetch['totalFiltered'];
        $query = $fetch['query'];

        $data = array();
        $no = 0;
        foreach ($query->result_array() as $row) {
            ++$no;
            $nestedData = array();
            $id = '"'.$row['no_pr'].'"';

            $nestedData[] = $no;
            $nestedData[] = $row['no_pr'];
            $nestedData[] = $row['kdcab'].' / '.$row['namacabang'];
            $nestedData[] = date('d/m/Y', strtotime($row['tgl_pr']));
            $nestedData[] = date('d/m/Y', strtotime($row['plan_delivery_date']));
            $nestedData[] = $row['id_supplier'];
            $nestedData[] = "<a href='#dialog-popup' data-toggle='modal' onclick='PreviewPdf(".$id.")'><span class='glyphicon glyphicon-print'></span></a>";

            $data[] = $nestedData;
        }

        $json_data = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data,
            );

        echo json_encode($json_data);
    }

    public function hapus_pr()
    {
        $no_pr = $this->uri->segment(3);

        $this->db->trans_begin();
        $this->Purchaserequest_model->delete_pr_header($no_pr);
        $this->Purchaserequest_model->delete_pr_detail($no_pr);
        $this->Purchaserequest_model->delete_pr_tambahan($no_pr);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $param = array(
                'delete' => 0,
                'msg' => 'GAGAL, hapus..!!!',
                );
        } else {
            $this->db->trans_commit();
            $param = array(
                'delete' => 1,
                'msg' => 'SUKSES, hapus data..!!!',
                );
        }

        echo json_encode($param);
    }

    public function edit($sup, $no)
    {
        $session = $this->session->userdata('app_session');
        $this->db->select('*');
        $this->db->from('supplier_barang');
        $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
        $this->db->where('supplier_barang.id_supplier', $this->uri->segment(3));
        $itembarang = $this->db->get()->result();

        $this->db->select('*');
        $this->db->from('supplier_cbm');
        $this->db->join('cbm', 'cbm.id_cbm = supplier_cbm.id_cbm', 'left');
        $this->db->where('supplier_cbm.id_supplier', $this->uri->segment(3));
        $cbm_sup = $this->db->get()->result();

        $pr_hader = $query = $this->db->query("SELECT * FROM `trans_pr_header` where no_pr='$no'")->row();
        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");

        $this->template->set('supplier', $sup);
        $this->template->set('no_pr', $no);
        $this->template->set('pr_tambahan', $query_pr_tambahan);
        $this->template->set('pr_hader', $pr_hader);
        $this->template->set('cbm_sup', $cbm_sup);
        $this->template->set('itembarang', $itembarang);
        $this->template->title('Edit Purchase Request');
        $this->template->render('pr_edit');
    }

    public function edit_save()
    {
        $session = $this->session->userdata('app_session');
        $no_pr = $this->input->post('no_pr');
        $headerpr = array(
            'id_cbm' => $this->input->post('radio-group'),
            'total_cbm' => $this->input->post('cbm_tot'),
        );

        $this->db->trans_begin();
        $jumlah = count($_POST['qtyt']);
        $this->Purchaserequest_model->delete_pr_tambahan($no_pr);
        for ($i = 0; $i < $jumlah; ++$i) {
            $komponen = $_POST['komponen'][$i];
            $datasm = array(
                                'no_pr' => $no_pr,
                                'id_barang' => $_POST['barang_t'][$i],
                                'nm_komponen' => $_POST['komponen'][$i],
                                'qty' => $_POST['qtyt'][$i],
                            );

            if (!empty($_POST['qtyt'][$i])) {
                $this->Purchaserequest_model->insert_trans_pr_tambahan($datasm);
            }
        }

        $this->db->select('*');
        $this->db->from('supplier_barang');
        $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
        $this->db->where('supplier_barang.id_supplier', $this->input->post('idsupplier'));
        $itembarang = $this->db->get()->result();

        $this->Purchaserequest_model->delete_pr_detail($no_pr);
        $noo = 0;
        foreach ($itembarang as $kc => $val) {
            ++$noo;
            $detailpr = array(
                    'no_pr' => $no_pr,
                    'id_barang' => $val->id_barang,
                    'nm_barang' => $val->nm_barang,
                    'satuan' => $val->satuan,
                    'qty_pr' => $this->input->post("qty$noo"),
                    'harga_satuan' => $val->harga,
                    'sub_total_pr' => 0,
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => date('Y-m-d H:i:s'),
                    );
            if ($this->input->post("qty$noo") != 0) {
                $this->db->insert('trans_pr_detail', $detailpr);
            }
        }

        $this->Purchaserequest_model->update_pr_header($no_pr, $headerpr);

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





    public function save_new_create()
    {
        $session = $this->session->userdata('app_session');
        $nopr = $this->Purchaserequest_model->generate_nopr($session['kdcab']);
        $headerpr = array(
            'no_pr' => $nopr,
            'tgl_pr' => $this->input->post('tglpr'),
            'kdcab' => $session['kdcab'],
            'plan_delivery_date' => $this->input->post('plandeliverypr'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'total_cbm' => $this->input->post('cbm_tot'),
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
        );

        $this->db->trans_begin();

        $itembarang = $this->Purchaserequest_model->pilih_item_sup($session['kdcab'], $this->input->post('idsupplier'))->result();
        $noo = 0;
        foreach ($itembarang as $kc => $val) {
            ++$noo;
            $detailpr = array(
                    'no_pr' => $nopr,
                    'id_barang' => $val->id_barang,
                    'nm_barang' => $val->nm_barang,
                    'satuan' => $val->satuan,
                    'qty_pr' => $this->input->post("qty$noo"),
                    'harga_satuan' => $val->harga,
                    'sub_total_pr' => 0,
                    'created_on' => date('Y-m-d H:i:s'),
                    'created_by' => date('Y-m-d H:i:s'),
                    );
            if ($this->input->post("qty$noo") != 0) {
                $this->db->insert('trans_pr_detail', $detailpr);
            }
        }

        $this->db->insert('trans_pr_header', $headerpr);
        //Update counter NO_pr
        $counter = $this->Purchaserequest_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
        $this->db->where(array('kdcab' => $session['kdcab']));
        $this->db->update('cabang', array('no_pr' => $counter->no_pr + 1));
        //Update counter NO_pr
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $param = array(
                'save' => 0,
                'msg' => 'GAGAL, tambah item barang..!!!',
                );
        } else {
            $this->db->trans_commit();
            $param = array(
                'save' => 1,
                'msg' => 'SUKSES, tambah item barang..!!!',
                );
        }

        echo json_encode($param);
    }

    //Create New PO
    public function create()
    {
        $session = $this->session->userdata('app_session');
        if ($this->uri->segment(3) == '') {
            $itembarang = $this->Purchaserequest_model->pilih_item($session['kdcab'])->result();
        } else {
            $itembarang = $this->Purchaserequest_model
           ->pilih_item_sup($session['kdcab'], $this->uri->segment(3))->result();
        }

        //$supplier = $this->Purchaserequest_model->get_data('1=1','supplier');
        $supplier = $this->Purchaserequest_model->get_data('1=1', 'supplier');
        $cabang = $this->Purchaserequest_model->find_all_by(array('kdcab' => $session['kdcab']));
        $prtemp = $this->Purchaserequesttmp_model
        ->join('barang_master', 'trans_pr_detail_tmp.id_barang = barang_master.id_barang', 'left')
        ->join('supplier', 'supplier.id_supplier = barang_master.id_supplier', 'inner')
        ->find_all_by(array('trans_pr_detail_tmp.created_by' => $session['id_user']));
        $this->template->set('itembarang', $itembarang);
        $this->template->set('detailprtmp', $prtemp);
        $this->template->set('supplier', $supplier);
        $this->template->set('cabang', $cabang);
        $this->template->title('Input Purchase Request');
        $this->template->render('pr_form');
    }

    public function get_supplier()
    {
        $idsup = $_GET['idsup'];
        $supplier = $this->Purchaserequest_model->get_supplier($idsup)->row();

        echo json_encode($supplier);
    }

    public function savedetailpr()
    {
        $session = $this->session->userdata('app_session');
        $nopr = $this->Purchaserequest_model->generate_nopr($session['kdcab']);
        $dataitempr = array(
            'no_pr' => $nopr,
            'id_barang' => $this->input->post('item_brg_pr'),
            'nm_barang' => $this->input->post('nmbarang'),
            'satuan' => $this->input->post('satuan'),
            //'harga_satuan' => $this->input->post('harga'),
            'qty_pr' => $this->input->post('qty_pr'),
            //'sub_total_pr' => $this->input->post('qty_pr')*$this->input->post('harga'),
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
            );

        $key = array(
            'no_pr' => $nopr,
            'id_barang' => $this->input->post('item_brg_pr'),
            'created_by' => $session['id_user'],
            );
        $cekdata = $this->Purchaserequest_model->cek_data($key, 'trans_pr_detail_tmp');

        if (!$cekdata) {
            $this->db->trans_begin();
            $this->db->insert('trans_pr_detail_tmp', $dataitempr);
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
        } else {
            $param = array(
                'save' => 0,
                'msg' => 'GAGAL, Item sudah terdaftar..!!!',
                );
        }
        echo json_encode($param);
    }

    public function hapus_item_pr()
    {
        $id = $this->input->post('ID');
        $key = array('id_detail_pr' => $id);
        if (!empty($id)) {
            $result = $this->Purchaserequesttmp_model->delete_where($key);
            $param['delete'] = 1;
        } else {
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    public function saveheaderpr()
    {
        $session = $this->session->userdata('app_session');
        $nopr = $this->Purchaserequest_model->generate_nopr($session['kdcab']);
        $headerpr = array(
            'no_pr' => $nopr,
            'tgl_pr' => $this->input->post('tglpr'),
        'kdcab' => $session['kdcab'],
            'plan_delivery_date' => $this->input->post('plandeliverypr'),
            //'real_delivery_date' => $this->input->post('realdeliverypr'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'total_cbm' => $this->input->post('cbm_tot'),
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
        );
        $this->db->trans_begin();
        $key = array('no_pr' => $nopr, 'created_by' => $session['id_user']);
        $data_tmp = $this->Purchaserequesttmp_model->find_all_by($key);
        if ($data_tmp) {
            foreach ($data_tmp as $key => $val) {
                $detailpr = array(
                    'no_pr' => $val->no_pr,
                    'id_barang' => $val->id_barang,
                    'nm_barang' => $val->nm_barang,
                    'satuan' => $val->satuan,
                    'qty_pr' => $val->qty_pr,
                    'harga_satuan' => $val->harga_satuan,
                    'sub_total_pr' => $val->sub_total_pr,
                    'created_on' => $val->created_on,
                    'created_by' => $val->created_by,
                    );
                $this->db->insert('trans_pr_detail', $detailpr);
            }
            $this->db->insert('trans_pr_header', $headerpr);
            //Update counter NO_pr
            $counter = $this->Purchaserequest_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
            $this->db->where(array('kdcab' => $session['kdcab']));
            $this->db->update('cabang', array('no_pr' => $counter->no_pr + 1));
            //Update counter NO_pr
            $this->db->truncate('trans_pr_detail_tmp');
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $param = array(
                'save' => 0,
                'msg' => 'GAGAL, tambah item barang..!!!',
                );
            } else {
                $this->db->trans_commit();
                $param = array(
                'save' => 1,
                'msg' => 'SUKSES, tambah item barang..!!!',
                );
            }
        } else {
            $param = array(
                'save' => 0,
                'msg' => 'PERINGATAN, belum ada data..!!!',
                );
        }
        echo json_encode($param);
    }

    public function print_request($tipe, $nopr)
    {
        $mpdf = new mPDF('', '', '', '', '', '', '', '', '', '');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        //$pr_data = $this->Purchaserequest_model->find_data('trans_pr_header',$nopr,'no_pr');
        $pr_data = $this->Purchaserequest_model->join('cabang', 'trans_pr_header.kdcab = cabang.kdcab', 'left')->find_data('trans_pr_header', $nopr, 'no_pr');
        //$pr_data = $this->Purchaserequest_model->query("SELECT * FROM trans_pr_header LEFT JOIN cabang ON trans_pr_header.kdcab = cabang.kdcab WHERE trans_pr_header.no_pr = '$nopr'")->result();
        //$detail = $this->Detailpurchaserequest_model->find_all_by(array('no_pr' => $nopr));
        $detail = $this->db->query("SELECT * FROM trans_pr_detail INNER JOIN barang_master ON trans_pr_detail.id_barang = barang_master.id_barang WHERE no_pr = '$nopr'")->result();
        $this->template->set('pr_data', $pr_data);
        $this->template->set('detail', $detail);
        $this->template->set('tipe', $tipe);
        $show = $this->template->load_view('print_data', $data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
}
