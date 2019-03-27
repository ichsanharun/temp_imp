<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Returndo_claim extends Admin_Controller {

    //Permission

    protected $viewPermission   = "Returndo_claim.View";
    protected $addPermission    = "Returndo_claim.Add";
    protected $managePermission = "Returndo_claim.Manage";
    protected $deletePermission = "Returndo_claim.Delete";

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array('Deliveryorder_2/Deliveryorder_model',
                                 'Deliveryorder_2/Detaildeliveryorder_model',
                                 'Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Salesorder/Salesorder_model',
                                 'Salesorder/Detailsalesorder_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        $this->template->title('Delivery Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index_old()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Deliveryorder_model->order_by('no_do','ASC')->find_all_by(array('konfirm_do' => "SUDAH", 'status'=>'INV'));

        $this->template->set('results', $data);
        $this->template->title('Return DO Claim');
        $this->template->render('list');
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Invoice_model->order_by('no_invoice','ASC')->find_all_by(array('kdcab' => $this->auth->user_cab(), 'flag_cancel'=>'N'));

        $this->template->set('results', $data);
        $this->template->title('Return DO Claim');
        $this->template->render('index');
    }


    public function viewkonfirmasido(){
        $header = $this->Deliveryorder_model->order_by('no_do','ASC')->find_by(array('no_do' => $this->input->post('NODO')));
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $this->input->post('NODO')));
        $this->template->set('header', $header);
        $this->template->set('detail', $detail);
        $this->template->render('cekdetaildo');
    }

    public function setkonfirmasido(){
        $header = $this->Invoice_model->order_by('no_invoice','ASC')
        ->find_by(array('no_invoice' => $this->input->post('NODO')));
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $this->input->post('NODO')));
        $this->template->set('header', $header);
        $this->template->set('detail', $detail);
        $this->template->render('getdetaildo');
    }

    public function savekonfirmdo_old(){
      $session = $this->session->userdata('app_session');
        $kosong = 0;
        for($i=0;$i < count($this->input->post('konfirm_do'));$i++){
            if($this->input->post('konfirm_do')[$i] == ""){
                $kosong++;
            }

        }
        if($kosong > 0){
            $result['type'] = "error";
            $result['pesan'] = "Pastikan data konfirmasi lengkap";
        }else{
            $this->db->trans_begin();
            for($i=0;$i < count($this->input->post('konfirm_do'));$i++){
                $dataKonfirm = array(
                    'konfirm_do_detail' => $this->input->post('konfirm_do')[$i],
                    'return_do' => $this->input->post('return_do')[$i]
                    );
                $key = array(
                    'no_do'=>$this->input->post('no_do_konfirm'),
                    'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]
                    );
                $this->db->where($key)
                ->update('trans_do_detail',$dataKonfirm);
                if ($this->input->post('return_do')[$i] > 0) {

                  $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]);
                  $stok_avl = $this->Deliveryorder_model->cek_data($keycek,'barang_stock');
                  if ($this->input->post('konfirm_do')[$i] == "RETURN BAGUS") {
                    $this->db->where($keycek);
                    $this->db->update('barang_stock',
                      array(
                        'qty_avl'=>$stok_avl->qty_avl+$this->input->post('return_do')[$i],
                        'qty_stock'=>$stok_avl->qty_stock+$this->input->post('return_do')[$i],
                      )
                    );
                  }elseif ($this->input->post('konfirm_do')[$i] == "RETURN RUSAK") {
                    $this->db->where($keycek);
                    $this->db->update('barang_stock',
                      array(
                        'qty_barang_rusak'=>$stok_avl->qty_barang_rusak+$this->input->post('return_do')[$i],
                      )
                    );
                  }elseif ($this->input->post('konfirm_do')[$i] == "RETURN HILANG") {
                    $this->db->where($keycek);
                    $this->db->update('barang_stock',
                      array(
                        'qty_barang_hilang'=>$stok_avl->qty_barang_hilang+$this->input->post('return_do')[$i],
                      )
                    );
                  }
                }
            }
            $this->db->update('trans_do_header',array('konfirm_do'=>'SUDAH'),array('no_do'=>$this->input->post('no_do_konfirm')));
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $result['type'] = "error";
                $result['pesan'] = "Gagal simpan data";
            }else{
                $this->db->trans_commit();
                $result['type'] = "success";
                $result['pesan'] = "Sukses simpan data";
            }
        }

        echo json_encode($result);
    }

    public function savekonfirmdo(){
      $session = $this->session->userdata('app_session');
        $kosong = 0;
        $kosong_a = 0;
        for($i=0;$i < count($this->input->post('id_barang_do_konfirm'));$i++){
            if($this->input->post('return_do_rusak')[$i] == ""){
                $kosong++;
            }
            if($this->input->post('return_do_hilang')[$i] == ""){
                $kosong_a++;
            }

        }
        if($kosong > 0 && $kosong_a > 0){
            $result['type'] = "error";
            $result['pesan'] = "Pastikan data konfirmasi lengkap";
        }else{
            $this->db->trans_begin();
            for($i=0;$i < count($this->input->post('id_barang_do_konfirm'));$i++){
              //for($j=0;$j < count($this->input->post('konfirm_do')[$i]);$j++){}
                $dataKonfirm = array(
                    'return_claim' => $this->input->post('return_do_rusak')[$i]
                    );
                $key = array(
                    'no_do'=>$this->input->post('no_do_konfirm'),
                    'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]
                    );
                $this->db->where($key)
                ->update('trans_do_detail',$dataKonfirm);
                if ($this->input->post('return_do_rusak')[$i] > 0) {

                  $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('id_barang_do_konfirm')[$i]);

                  $stok_avl = $this->Deliveryorder_model->cek_data($keycek,'barang_stock');
                  $qty_avl = $stok_avl->qty_avl-$this->input->post('return_do_rusak')[$i];
                  $qty_stock = $stok_avl->qty_stock-$this->input->post('return_do_rusak')[$i];
                  $qty_rusak = $stok_avl->qty_rusak+$this->input->post('return_do_rusak')[$i];
                  $this->db->where($keycek);
                  $this->db->update('barang_stock',
                  array(
                    'qty_avl'=>$stok_avl->qty_avl-$this->input->post('return_do_rusak')[$i],
                    'qty_stock'=>$stok_avl->qty_stock-$this->input->post('return_do_rusak')[$i],
                    'qty_rusak'=>$stok_avl->qty_rusak+$this->input->post('return_do_rusak')[$i],
                    )
                  );

                }
            }
            $this->db->update('trans_do_header',array('konfirm_do'=>'SUDAH'),array('no_do'=>$this->input->post('no_do_konfirm')));
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $result['type'] = "error";
                $result['pesan'] = "Gagal simpan data";
            }else{
                $this->db->trans_commit();
                $result['type'] = "success";
                $result['pesan'] = "Sukses simpan data";
            }
        }

        echo json_encode($result);
    }
    /*
    public function savekonfirmdo(){
        $dataKonfirm = array(
            'konfirm_do' => $this->input->post('STKF'),
            'jumlah_return' => $this->input->post('RET')
            );
        $this->db->trans_begin();
        $this->db->update('trans_do_header',$dataKonfirm,array('no_do'=>$this->input->post('NODO')));
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $result['type'] = "error";
            $result['pesan'] = "Gagal simpan data";
        }else{
            $this->db->trans_commit();
            $result['type'] = "success";
            $result['pesan'] = "Sukses simpan data";
        }
        echo json_encode($result);
    }
    */

    function print_request($nodo){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $do_data = $this->Deliveryorder_model->find_data('trans_do_header',$nodo,'no_do');
        $customer = $this->Deliveryorder_model->cek_data(array('id_customer'=>$do_data->id_customer),'customer');
        $detail = $this->Detaildeliveryorder_model->find_all_by(array('no_do' => $nodo));

        $this->template->set('do_data', $do_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);

        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

}

?>
