<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Productcosting extends Admin_Controller {
    /*
    //Permission
    protected $viewPermission   = "Customer.View";
    protected $addPermission    = "Customer.Add";
    protected $managePermission = "Customer.Manage";
    protected $deletePermission = "Customer.Delete";
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Productcosting/Productcosting_model','Kurs/Kurs_model'));
        $this->template->title('Product Costing');
        $this->template->page_icon('fa fa-money');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $barang = $this->Productcosting_model->get_barang()->result();
        $cabang = $this->Productcosting_model->get_cabang()->result();
        $kursyuan = $this->Kurs_model->get_kurs(38)->row();//id di tabel mata_uang
        $kursusd = $this->Kurs_model->get_kurs(6)->row();//CHINA id 38 dan USA id 6
        
        $this->template->set('barang', $barang);
        $this->template->set('cabang', $cabang);
        $this->template->set('yuan', $kursyuan);
        $this->template->set('usd', $kursusd);
        $this->template->title('Product Costing');
        $this->template->render('costing');
    }

    public function setdata(){
        $barang = $this->Productcosting_model->get_barang()->result();
        $cabang = $this->Productcosting_model->get_cabang()->result();
        $kursyuan = $this->Kurs_model->get_kurs(38)->row();//id di tabel mata_uang
        $kursusd = $this->Kurs_model->get_kurs(6)->row();//CHINA id 38 dan USA id 6
        $costing = $this->Productcosting_model->cek_data(array('id_barang'=>$this->uri->segment(3)),'product_costing');
        
        $this->template->set('costing', $costing);
        $this->template->set('barang', $barang);
        $this->template->set('cabang', $cabang);
        $this->template->set('yuan', $kursyuan);
        $this->template->set('usd', $kursusd);
        $this->template->title('Product Costing');
        $this->template->render('costing');
    }

    public function costingcabang(){
        $key = array('id_barang'=>$this->uri->segment(3),'kdcab' => $this->uri->segment(4));
        $cekcabang = $this->Productcosting_model->cek_data($key,'product_costing_cabang');
        $cekbarang = $this->Productcosting_model->cek_data(array('id_barang'=>$this->uri->segment(3)),'barang_master');
        $groupbarang = $this->Productcosting_model->cek_data(array('id_group'=>$cekbarang->id_group),'barang_group');

        $data['profit'] = $this->Productcosting_model->get_group_profit()->result();
        $data['group'] = $groupbarang;
        $data['cabang'] = $this->Productcosting_model->get_cabang_by($this->uri->segment(4))->row();
        $data['costcabang'] = $this->Productcosting_model->cek_data($key,'product_costing_cabang');
        $this->load->view('ajax/costingcabang',$data);
    }

    public function savecosting(){
        $costheader = array(
            'id_barang' => $this->input->post('idbarang'),
            'mata_uang' => $this->input->post('matauang'),
            'harga_beli_rp' => $this->input->post('hargabelirealidr'),
            'harga_beli_invoice' => $this->input->post('hargabeliinvc'),
            'ppn' => $this->input->post('ppnprodcost'),
            'pph' => $this->input->post('pphprodcost'),
            'total_pajak' => $this->input->post('ppnprodcost')+$this->input->post('pphprodcost'),
            'total_harga_beli' => $this->input->post('totalhargabeli'),
            'log_biaya_kapal_usd' => $this->input->post('biayakapal'),
            'log_biaya_kapal' => $this->input->post('biayakapalidr'),
            'log_biaya_pengapalan_usd' => $this->input->post('biayapengkapalan'),
            'log_biaya_pengapalan' => $this->input->post('biayapengkapalanidr'),
            'log_fee_agent_china_usd' => $this->input->post('biayafeeagent'),
            'log_fee_agent_china' => $this->input->post('biayafeeagentidr'),
            'log_ppjk' => $this->input->post('biayappjk'),
            'log_total_invoice' => $this->input->post('totalrealinvoice'),
            'jenis_tak_terduga' => $this->input->post('jenisbiayatt'),
            'persen_tak_terduga' => $this->input->post('persenbiayatt'),
            'log_biaya_tdk_terduga' => $this->input->post('biayatakterduga'),
            'logistic_cost' => $this->input->post('totalbiayalog'),
            'log_cbm_1_container' => $this->input->post('cbm1container'),
            'log_cost_per_m3' => $this->input->post('biayalogm3'),
            //'22' => $this->input->post('biayalogm3next'),
            'volume_produk_cbm' => $this->input->post('volumeprodukcbm'),
            'log_cost_pcs' => $this->input->post('biayalogpcs')
            
            );
        if($this->input->post('matauang') == "RMB"){
            $costheader['harga_beli_yuan'] = $this->input->post('hargabelireal');
        }else{
            $costheader['harga_beli_us'] = $this->input->post('hargabelireal');
        }

        $cek = $this->Productcosting_model->find_by(array('id_barang'=>$this->input->post('idbarang')));
        if(!$cek){
            $this->db->insert('product_costing',$costheader);
        }

        $costcabang = array(
            'id_barang' => $this->input->post('idbarang'),
            'kdcab' => $this->input->post('kdcab'),
            'log_trucking_lokal' => $this->input->post('biayaloglokal'),
            'log_lokal_per_m3' => $this->input->post('biayalogm3lokal'),
            'log_lokal_per_pcs' => $this->input->post('cbmlokalpcs'),
            'total_log_lokal' => $this->input->post('totallogistik'),
            'hpp' => $this->input->post('hppcost'),
            'margin_profit' => $this->input->post('persenprofit'),
            'profit' => $this->input->post('profit'),
            'harga_product' => $this->input->post('hargaproduk'),
            'harga_product_adj' => $this->input->post('hargaprodukadj'),
            'persen_diskon_toko' => $this->input->post('persendiskontoko'),
            'diskon_toko' => $this->input->post('diskontoko'),
            'harga_pricelist' => $this->input->post('hargapricelist')
            );

        $key = array('id_barang'=>$this->input->post('idbarangcabang'),'kdcab' => $this->input->post('kdcab'));
        $cekcabang = $this->Productcosting_model->cek_data($key,'product_costing_cabang');
        if($cekcabang){
            $result['type'] = 'error';
            $result['pesan'] = 'Produk & Cabang sudah diset';
        }else{
            $this->db->insert('product_costing_cabang',$costcabang);
            $result['type'] = 'success';
            $result['pesan'] = 'Produk sukses diset';
        }

        echo json_encode($result);
    }

}

?>
