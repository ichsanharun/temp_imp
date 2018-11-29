<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Receivingmutasi extends Admin_Controller {
    
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
        $session = $this->session->userdata('app_session');
        $kdcabtujuan = $session['kdcab'];
        //$kdcabtujuan = '102';
        $data = $this->Mutasi_model->where(array('kdcab_tujuan'=>$kdcabtujuan))->order_by('no_mutasi','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Receiving Mutasi Produk');
        $this->template->render('list');
    }

    public function getdetailmutasi(){
        $no_mutasi = $this->input->post('NO_MT');
        $header = $this->Mutasi_model->find_data('trans_mutasi_header',$no_mutasi,'no_mutasi');
        $detail = $this->Detailmutasi_model->find_all_by(array('no_mutasi' => $no_mutasi));

        $this->template->set('header', $header);
        $this->template->set('detail', $detail);
        $this->template->render('getdetailmutasi');
        //$this->template->load_view('getdetailmutasi',$data);
    }

    public function savereceivingmutasi(){
        //echo '<pre>';print_r($this->input->post());exit;
        $session = $this->session->userdata('app_session');
        $no_mutasi = $this->input->post('no_mutasi');
        $kdcab_asal = $this->input->post('kdcab_asal');
        $kdcab_tujuan = $this->input->post('kdcab_tujuan');

        $dataheader = array(
            'id_penerima' => $session['id_user'],
            'status_mutasi' => 'REC'
            );
        $this->db->trans_begin();
        for($i=0;$i < count($this->input->post('id_barang_rec_mutasi'));$i++){
            $datadetail = array(
                'qty_received'    => $this->input->post('qty_rec_mutasi')[$i],
                'received_on'    => date('Y-m-d H:i:s'),
                'received_by'    => $session['id_user'],
                'status_det_mutasi' => $this->input->post('sts_rec_mutasi')[$i]
                );
            $this->db->where(array('no_mutasi'=>$no_mutasi,'id_barang'=>$this->input->post('id_barang_rec_mutasi')[$i]));
            $this->db->update('trans_mutasi_detail',$datadetail);

            //Update STOK REAL CABANG ASAL (BERKURANG)
            $count = $this->Mutasi_model->cek_data(array('id_barang'=>$this->input->post('id_barang_rec_mutasi')[$i],'kdcab'=>$kdcab_asal),'barang_stock');
            $this->db->where(array('id_barang'=>$this->input->post('id_barang_rec_mutasi')[$i],'kdcab'=>$kdcab_asal));
            $this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock-$this->input->post('qty_rec_mutasi')[$i]));
            //Update STOK REAL

            //Update STOK REAL & AVL CABANG TUJUAN (BERTAMBAH)
            $keytujuan = array(
                'id_barang'=>$this->input->post('id_barang_rec_mutasi')[$i],
                'kdcab'=>$kdcab_tujuan
                );
            $count7 = $this->Mutasi_model->cek_data($keytujuan,'barang_stock');
            $datatujuan = array(
                'qty_stock'=>$count7->qty_stock+$this->input->post('qty_rec_mutasi')[$i],
                'qty_avl'=>$count7->qty_avl+$this->input->post('qty_rec_mutasi')[$i]
                );
            $this->db->where(array('id_barang'=>$this->input->post('id_barang_rec_mutasi')[$i],'kdcab'=>$kdcab_tujuan));
            $this->db->update('barang_stock',$datatujuan);
            //Update STOK REAL
        }
        $this->db->where(array('no_mutasi'=>$no_mutasi));
        $this->db->update('trans_mutasi_header',$dataheader);
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
