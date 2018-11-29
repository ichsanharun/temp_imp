<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Mutasi extends Admin_Controller {
    
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
        $this->load->library(array('Mpdf','upload','Image_lib'));
        
        $this->load->model(array('Mutasi/Mutasi_model',
                                 'Mutasi/Detailmutasi_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        
        $this->template->title('Mutasi Produk');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data = $this->Mutasi_model->order_by('no_mutasi','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Data Mutasi Produk');
        $this->template->render('list');
    }

    public function create()
    {
        $session = $this->session->userdata('app_session');
        $driver = $this->Mutasi_model->pilih_driver()->result();
        $kendaraan = $this->Mutasi_model->pilih_kendaraan()->result();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $stok_cabang = $this->Mutasi_model->get_data(array('kdcab'=>$session['kdcab']),'barang_stock');
        $cabang_now = $this->Mutasi_model->get_cabang($session['kdcab']);

        $this->template->set('driver',$driver);
        $this->template->set('kendaraan',$kendaraan);
        $this->template->set('cabang',$cabang);
        $this->template->set('kdcab',$cabang_now);
        $this->template->set('stok_cabang',$stok_cabang);
        $this->template->title('Create Mutasi Produk');
        $this->template->render('mutasi_form');
    }

    public function savemutasi(){
        $session = $this->session->userdata('app_session');
        $cabang_asal = explode('|',$this->input->post('cabang_asal'));
        $cabang_tujuan = explode('|',$this->input->post('cabang_tujuan'));
        $supir = explode('|',$this->input->post('supir_mutasi'));
        $mobil = explode('|',$this->input->post('kendaraan_mutasi'));
        $Id_supir = $supir[0];
        $Nm_supir = $supir[1];
        $Id_kendaraan = $mobil[0];
        $Ket_kendaraan = $mobil[1];

        $dataheader = array(
            'no_mutasi'         => $this->Mutasi_model->generate_no_mutasi($session['kdcab']),
            'tgl_mutasi'        => date('Y-m-d'),
            'kdcab_asal'        => $cabang_asal[0],
            'cabang_asal'       => $cabang_asal[1],
            'kdcab_tujuan'      => $cabang_tujuan[0],
            'cabang_tujuan'     => $cabang_tujuan[1],
            'id_supir'          => $Id_supir,
            'nm_supir'          => $Nm_supir,
            'id_kendaraan'      => $Id_kendaraan,
            'ket_kendaraan'     => $Ket_kendaraan,
            'status_mutasi'     => 'IT',
            'created_on'        => date('Y-m-d H:i:s'),
            'created_by'        => $session['id_user']
            );
        $this->db->trans_begin();
        for($i=0;$i < count($this->input->post('kode_produk'));$i++){
            $datadetail = array(
                'no_mutasi'     => $this->Mutasi_model->generate_no_mutasi($session['kdcab']),
                'id_barang'     => $this->input->post('kode_produk')[$i],
                'nm_barang'     => $this->input->post('nama_produk')[$i],
                'qty_mutasi'    => $this->input->post('qty_mutasi')[$i],
                'created_on'    => date('Y-m-d H:i:s'),
                'created_by'    => $session['id_user']
                );
             $this->db->insert('trans_mutasi_detail',$datadetail);
             //Update QTY_AVL
             $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('kode_produk')[$i]);
             $stok_avl = $this->Mutasi_model->cek_data($keycek,'barang_stock');
             $this->db->where($keycek);
             $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$this->input->post('qty_mutasi')[$i]));
             //Update QTY_AVL
        }
        //Update counter NO_MUTASI
        $count = $this->Mutasi_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_mutasi'=>$count->no_mutasi+1));
        //Update counter NO_MUTASI
        $this->db->insert('trans_mutasi_header',$dataheader);
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

    function print_request($mutasi){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $header = $this->Mutasi_model->find_data('trans_mutasi_header',$mutasi,'no_mutasi');
        $detail = $this->Detailmutasi_model->find_all_by(array('no_mutasi' => $mutasi));

        $this->template->set('header', $header);
        $this->template->set('detail', $detail);
        
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

}

?>
