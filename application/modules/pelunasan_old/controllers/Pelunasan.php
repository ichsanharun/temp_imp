<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 * 
 * This is controller for Koli
 */

class Pelunasan extends Admin_Controller {
    
    /**
     * Load the models, library, etc
     *
     * 
     */
    //Permission
    protected $viewPermission   = "Koli.View";
    protected $addPermission    = "Koli.Add";
    protected $managePermission = "Koli.Manage";
    protected $deletePermission = "Koli.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Pelunasan/Pelunasan_model',
                                 'Invoice/Invoice_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Pelunasan Pembayaran Piutang');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $key = array(
            'kdcab'=>$session['kdcab'],
            'is_cancel' => 'N',
            'jenis_reff' => 'BG',
            'status_bayar' => 'GIRO'
            );
        $data = $this->Pelunasan_model->where($key)->order_by('tgl_pembayaran','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Pelunasan Pembayaran Piutang');
        $this->template->render('list'); 
    }

    function pelunasanpiutang(){
        $kode = $this->input->post('KD');
        $no_inv = $this->input->post('NO_INV');
        $invoice = $this->Invoice_model->cek_data(array("no_invoice"=>$no_inv),'trans_invoice_header');
        $pembayaran = $this->Invoice_model->cek_data(array("no_invoice"=>$no_inv,'kd_pembayaran'=>$kode),'pembayaran_piutang');
        $lunas = $this->Invoice_model->get_data(array("no_invoice"=>$no_inv,'status_bayar'=>'LUNAS'),'pembayaran_piutang');
        $this->template->set('pembayaran', $pembayaran);
        $this->template->set('lunas', $lunas);
        $this->template->set('invoice', $invoice);
        $this->template->render('setpelunasan');
    }

    function prosespelunasan(){
        $kdbyar = $this->input->post('KD');
        $inv = $this->input->post('INV');
        $piutang = $this->input->post('PTG');
        $nominal = $this->input->post('NML');

        $this->db->trans_begin();
        //UPDATE PIUTANG
        $newpiutang = $piutang-$nominal;
        $this->db->where(array('no_invoice'=>$inv));
        $this->db->update('trans_invoice_header',array('piutang'=>$newpiutang));
        //-----//

        //UPDATE STATUS pembayaran LUNAS
        $key = array('kd_pembayaran'=>$kdbyar,'no_invoice'=>$inv);
        $byr = $this->Invoice_model->cek_data($key,'pembayaran_piutang');
        $this->db->where($key);
        $this->db->update('pembayaran_piutang',array('status_bayar'=>'LUNAS'));
        //======//

        //UPDATE STATUS GIRO CAIR
        $this->db->where(array('no_giro'=>$byr->no_reff));
        $this->db->update('giro',array('status'=>'CAIR'));
        //======//

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, simpan data..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, simpan data..!!!"
            );
        }
        echo json_encode($param);
    }

    function print_request($id){
        $id_koli = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $brg_data      =  $this->Barang_koli_model->find_data('barang_koli',$id_koli,'id_koli');        
        $this->template->set('brg_data', $brg_data);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }
}
?>