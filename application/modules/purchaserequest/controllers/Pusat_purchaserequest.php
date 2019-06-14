<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pusat_purchaserequest extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array( 'Purchaserequest/Purchaserequest_model',
                                  'Purchaserequest/Detailpurchaserequest_model',
                                  'Purchaserequest/Purchaserequesttmp_model',
                                  'Purchaserequest/Purchaserequest_pusat_model',
                                  'Purchaseorder/Purchaseorder_model',
                                  'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Purchase Request');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $data = $this->Purchaserequest_model
        ->select("*,trans_pr_header.no_pr AS nopr")
        ->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')
        ->where_in('trans_pr_header.proses_po',  array('ACC'))
        ->order_by('trans_pr_header.no_pr','ASC')->find_all();
        $datan = $this->Purchaserequest_model
        ->select("*,trans_pr_header.no_pr AS nopr")
        ->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')
        ->where_in('trans_pr_header.proses_po',  array('Proses','REVISI'))
        ->order_by('trans_pr_header.no_pr','ASC')->find_all();
        //$data = $this->db->query("SELECT * FROM trans_pr_header LEFT JOIN barang_master ON trans_pr_header.id_barang = barang_master.id_barang order by no_pr asc")->result();
        $this->template->set('results', $data);
        $this->template->set('non', $datan);
        $this->template->title('Purchase Request');
        $this->template->render('pusat/index');
    }

    public function konfirmasi()
    {
        $sup    =$this->uri->segment(4);
        $no     =$this->uri->segment(5);
        $session = $this->session->userdata('app_session');
        $this->db->select('*');
        $this->db->from('supplier_barang');
        $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
        $this->db->where('supplier_barang.id_supplier', $sup);
        $itembarang  =$this->db->get()->result();

        $this->db->select('*');
        $this->db->from('supplier_cbm');
        $this->db->join('cbm', 'cbm.id_cbm = supplier_cbm.id_cbm', 'left');
        $this->db->where('supplier_cbm.id_supplier', $sup);
        $cbm_sup  =$this->db->get()->result();

        $pr_hader=$query = $this->db->query("SELECT * FROM `trans_pr_header` where no_pr='$no'")->row();
        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");

        $this->template->set('supplier',$sup);
        $this->template->set('no_pr',$no);
        $this->template->set('pr_tambahan',$query_pr_tambahan);
        $this->template->set('pr_hader',$pr_hader);
        $this->template->set('cbm_sup',$cbm_sup);
        $this->template->set('itembarang',$itembarang);
        $this->template->title('Konfirmasi Purchase Request');
        $this->template->render('pusat/konfirmasi');
    }

    public function konfirmasi_save(){
        $session    = $this->session->userdata('app_session');
        $nopr       = $this->input->post('no_pr');

        if ($this->input->post('revisi_pil')=="REVISI") {

        $query = $this->db->query("SELECT * FROM `revisi_pr_header` WHERE no_pr='$nopr'");
        if ($query->num_rows() < 1)
        {
            $query_id   = $this->db->query("SHOW TABLE STATUS LIKE 'revisi_pr_header' ");
            $row_id     = $query_id->row();
            $id_revisi  = $row_id->Auto_increment;

            $query_pr = $this->db->query("SELECT * FROM `trans_pr_header` WHERE no_pr='$nopr'");
            $row = $query_pr->row();
            $headerpr   = array(
                'no_pr'         => $nopr,
                'tgl_pr'        => $row->tgl_pr,
                'kdcab'         => $row->kdcab,
                'id_supplier'   => $row->id_supplier,
                'total_cbm'     => $row->total_cbm,
                'id_cbm'        => $row->id_cbm,
                'created_on'    => $row->created_on,
                'created_by'    => $row->created_by,
                'koreksi'       => "Cabang",
                'keterangan'    => "PR awal",
                'status'        => "1",
            );

            $this->Purchaserequest_pusat_model->insert_revisi_pr_header($headerpr);


            $query_det = $this->db->query("SELECT * FROM `trans_pr_detail` WHERE no_pr='$nopr'");

            if ($query_det->num_rows() > 0)
            {
               foreach ($query_det->result() as $row_det)
               {
                    $detil   = array(
                        'id_revisi_pr'     => "$id_revisi",
                        'id_barang'        => $row_det->id_barang,
                        'nm_barang'        => $row_det->nm_barang,
                        'qty'              => $row_det->qty_pr,
                    );

                    $this->Purchaserequest_pusat_model->insert_revisi_pr_detail($detil);
               }
            }

            $query_tam = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$nopr'");

            if ($query_tam->num_rows() > 0)
            {
               foreach ($query_tam->result() as $row_tam)
               {
                    $detil   = array(
                        'id_revisi_pr'     => "$id_revisi",
                        'id_barang'        => $row_tam->id_barang,
                        'nm_komponen'      => $row_tam->nm_komponen,
                        'qty'              => $row_tam->qty,
                    );

                    $this->Purchaserequest_pusat_model->insert_revisi_pr_tambahan($detil);
               }
            }
        }

        $query_id   = $this->db->query("SHOW TABLE STATUS LIKE 'revisi_pr_header' ");
        $row_id     = $query_id->row();
        $id_revisi  =$row_id->Auto_increment;

        $query_pr = $this->db->query("SELECT * FROM `trans_pr_header` WHERE no_pr='$nopr'");
            $rowx = $query_pr->row();
            $headerprx   = array(
                'no_pr'         => $nopr,
                'tgl_pr'        => $rowx->tgl_pr,
                'kdcab'         => $rowx->kdcab,
                'id_supplier'   => $rowx->id_supplier,
                'total_cbm'     => $this->input->post('cbm_tot'),
                'id_cbm'        => $this->input->post('radio-group'),
                'created_on'    => date('Y-m-d H:i:s'),
                'created_by'    => $session['id_user'],
                'koreksi'       => "Pusat",
                'keterangan'    => $this->input->post('keterangan_revisi'),
                'status'        => "2",
            );

            $this->Purchaserequest_pusat_model->insert_revisi_pr_header($headerprx);

            $jumlah = count($_POST["qtyt"]);

            for($i=0; $i < $jumlah; $i++)
            {
                $komponen=$_POST["komponen"][$i];
                $datasm = array(
                                'id_revisi_pr'  => "$id_revisi",
                                'id_barang'     =>$_POST["barang_t"][$i],
                                'nm_komponen'   =>$_POST["komponen"][$i],
                                'qty'           =>$_POST["qtyt"][$i],
                            );

                 if (!empty($_POST["qtyt"][$i])) {
                     $this->Purchaserequest_pusat_model->insert_revisi_pr_tambahan($datasm);
                 }

            }


            $this->db->select('*');
            $this->db->from('supplier_barang');
            $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
            $this->db->where('supplier_barang.id_supplier', $this->input->post('idsupplier'));
            $itembarang  =$this->db->get()->result();

            $noo=0;
            foreach($itembarang as $kc=>$val){
                $noo++;
                $detailpr = array(
                    'id_revisi_pr'     => "$id_revisi",
                    'id_barang'        => $val->id_barang,
                    'nm_barang'        => $val->nm_barang,
                    'qty'              => $this->input->post("qty_c$noo"),
                    );
                 if ($this->input->post("qty$noo") != 0) {
                     $this->Purchaserequest_pusat_model->insert_revisi_pr_detail($detailpr);
                 }

            }

            $this->db->query("UPDATE `trans_pr_header` SET `proses_po` = 'REVISI' WHERE `trans_pr_header`.`id` = '$row->id';");

        }else {
          $tgl = $this->input->post('tglpo');
            $nopo = $this->Purchaseorder_model->generate_nopo($session['kdcab'],$tgl);
            $query_pr = $this->db->query("SELECT * FROM `trans_pr_header` WHERE no_pr='$nopr'");
            $rowx = $query_pr->row();
            $headerprx   = array(
                'no_po'         => $nopo,
                'tgl_po'        => $this->input->post('tglpo'),
                'kdcab'         => $rowx->kdcab,
                'nm_cabang'     => $this->input->post('namacabang'),
                'id_supplier'   => $rowx->id_supplier,
                'nm_supplier'   => $this->input->post('nm_supplier'),
                'total_po'      => $this->input->post('cbm_tot'),
                'id_cbm'        => $this->input->post('radio-group'),
                'created_on'    => date('Y-m-d H:i:s'),
                'created_by'    => $session['id_user'],
                'status'        => "ACC",
            );

            $this->Purchaserequest_pusat_model->insert_po_header($headerprx);

            $this->db->select('*');
            $this->db->from('supplier_barang');
            $this->db->join('barang_master', 'barang_master.id_barang = supplier_barang.id_barang', 'left');
            $this->db->where('supplier_barang.id_supplier', $this->input->post('idsupplier'));
            $itembarang  =$this->db->get()->result();

            $noo=0;
            foreach($itembarang as $kc=>$val){
                $noo++;
                $detailpr = array(
                    'no_po'            => $nopo,
                    'no_pr'            => $nopr,
                    'id_barang'        => $val->id_barang,
                    'nm_barang'        => $val->nm_barang,
                    'qty_po'           => $this->input->post("qty$noo"),
                    'qty_acc'          => $this->input->post("qty$noo"),
                    'created_on'       => date('Y-m-d H:i:s'),
                    'created_by'       => $session['id_user'],
                    );
                 if ($this->input->post("qty$noo") != 0) {
                     $this->Purchaserequest_pusat_model->insert_po_detail($detailpr);
                 }

            }

            $jumlah = count($_POST["qtyt"]);

            for($i=0; $i < $jumlah; $i++)
            {
                $komponen=$_POST["komponen"][$i];
                $datasm = array(
                                'no_po'         => "$nopo",
                                'no_pr'         => "$nopr",
                                'id_barang'     =>$_POST["barang_t"][$i],
                                'nm_komponen'   =>$_POST["komponen"][$i],
                                'qty'           =>$_POST["qtyt"][$i],
                            );

                 if (!empty($_POST["qtyt"][$i])) {
                     $this->Purchaserequest_pusat_model->insert_po_tambahan($datasm);
                 }

            }

            $this->db->query("UPDATE `trans_pr_header` SET `proses_po` = 'ACC' WHERE `trans_pr_header`.`id` = '$rowx->id';");
            //Update counter NO_PO
            $counter = $this->Purchaseorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');

            $this->db->where(array('kdcab'=>$session['kdcab']));
                $this->db->update('cabang',array('no_po'=>$counter->no_po+1));
            //Update counter NO_PO
        }

        $param = array(
                'save' => 1,
                'msg' => "Berhasil.. Silahkan lanjutkan ke Pembayaran!"
                );

                echo json_encode($param);

    }
}
