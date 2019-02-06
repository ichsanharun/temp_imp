<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Receiving_complain extends Admin_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Receiving/Receiving_model',
                                 'Receiving/Detailreceiving_model',
                                 'Trans_stock/Trans_stock_model',
                                 'Barang_stock/Barang_stock_model',
                                 'Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Complain');
        $this->template->page_icon('fa fa-file');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        
        $this->template->title('Data Complain');
        $this->template->render('complain/list');
    }
    
    public function pusat()
    {
        
        $this->template->title('Data Complain');
        $this->template->render('complain/list_pusat');
    }
    
    
    public function receiving()
    {
        $nopo    =$this->uri->segment(4);
        $session = $this->session->userdata('app_session');
        
        $rec = $this->db->query("SELECT * FROM `trans_receive` WHERE po_no='$nopo'");
        $barang = $this->db->query("SELECT * FROM `receive_detail_barang` WHERE no_po='$nopo' AND rusak !='0'");
        $po = $this->db->query("SELECT * FROM `trans_po_header` WHERE no_po='$nopo'");
        
        $this->template->set('nopo', $nopo);
        $this->template->set('rec', $rec->row());
        $this->template->set('barang', $barang);
        
        $this->template->title('Konfirmasi Receiving');
        $this->template->render('complain/receiving');
    }
    
    public function receiving_save()
    {
        $session    = $this->session->userdata('app_session');
        $nopo=$this->input->post('no_po');
        $this->db->trans_begin();
        $jumlah = count($_POST["rusak"]);

        for($i=0; $i < $jumlah; $i++)
        {
                $koli   = array(
                    'no_po'             => $nopo,
                    'id_barang'         => $_POST["id_barang"][$i],
                    'id_koli'           => $_POST["id_koli"][$i],
                    'nama_koli'         => $_POST["nama_koli"][$i],
                    'qty'               => $_POST["qty"][$i],
                    'bagus'             => $_POST["bagus"][$i],
                    'rusak'             => $_POST["rusak"][$i],
                    'keterangan'        => $_POST["keterangan"][$i],
                );
               $this->db->insert('trans_complain_koli_receiving',$koli);
               $id_b=$_POST["id_barang"][$i];
               if ($_POST["qty"][$i]==$_POST["bagus"][$i]) {
                   $this->db->query("UPDATE `receive_detail_barang` SET `status` = '2' WHERE no_po='$nopo' and id_barang='$id_b'");
               } else {
                   $this->db->query("UPDATE `receive_detail_barang` SET `status` = '3' WHERE no_po='$nopo' and id_barang='$id_b'");
               }
               
                
        } 
        
        $com   = array(
            'no_po'         => $nopo,
            'no_pengiriman' => $this->input->post('no_pengiriman'),
            'tanggal'       => date('Y-m-d'),
            'created_on'    => date('Y-m-d H:i:s'),
            'created_by'    => $session['id_user'],
        );
        $this->db->insert('trans_complain_receiving',$com);
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
    
    public function konfrimasi()
    {
        $nopo    =$this->uri->segment(4);
        $session = $this->session->userdata('app_session');
        
        $rec = $this->db->query("SELECT * FROM `trans_receive` WHERE po_no='$nopo'");
        $barang = $this->db->query("SELECT * FROM `receive_detail_barang` WHERE no_po='$nopo' AND rusak !='0'");
        $po = $this->db->query("SELECT * FROM `trans_po_header` WHERE no_po='$nopo'");
        
        $this->template->set('nopo', $nopo);
        $this->template->set('rec', $rec->row());
        $this->template->set('barang', $barang);
        
        $this->template->title('Konfirmasi Komplain');
        $this->template->render('complain/konfirmasi');
    }
    
    public function konfrimasi_save()
    {
        $session    = $this->session->userdata('app_session');
        $nopo=$this->input->post('no_po');
        $this->db->trans_begin();
        $jumlah = count($_POST["rusak"]);

        for($i=0; $i < $jumlah; $i++)
        {
                $koli   = array(
                    'no_po'             => $nopo,
                    'id_barang'         => $_POST["id_barang"][$i],
                    'id_koli'           => $_POST["id_koli"][$i],
                    'nama_koli'         => $_POST["nama_koli"][$i],
                    'rusak'             => $_POST["rusak"][$i],
                    'complain'          => $_POST["complain"][$i],
                );
               $this->db->insert('trans_complain_koli',$koli);
               $id_b=$_POST["id_barang"][$i];
                $this->db->query("UPDATE `receive_detail_barang` SET `status` = '1' WHERE no_po='$nopo' and id_barang='$id_b'");
        } 
        
        $com   = array(
            'no_po'           => $nopo,
            'tanggal'         => date('Y-m-d'),
            'created_on'    => date('Y-m-d H:i:s'),
            'created_by'    => $session['id_user'],
        );
        $this->db->insert('trans_complain',$com);
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
    
    function print_complain($nopo){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $rec = $this->db->query("SELECT * FROM `trans_receive` WHERE po_no='$nopo'");
        $barang = $this->db->query("SELECT * FROM `receive_detail_barang` WHERE no_po='$nopo' AND rusak !='0'");
        $po = $this->db->query("SELECT * FROM `trans_po_header` WHERE no_po='$nopo'");
        
        $this->template->set('nopo', $nopo);
        $this->template->set('rec', $rec->row());
        $this->template->set('barang', $barang);
        $show = $this->template->load_view('complain/print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    

}