<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Pendingso extends Admin_Controller {
    
    //Permission
    /*
    protected $viewPermission   = "Salesorder.View";
    protected $addPermission    = "Salesorder.Add";
    protected $managePermission = "Salesorder.Manage";
    protected $deletePermission = "Salesorder.Delete";
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Salesorder/Salesorder_model',
                                 'Salesorder/Detailsalesorder_model',
                                 'Salesorder/Detailsalesordertmp_model',
                                 'Pendingso/Pendingso_model',
                                 'Pendingso/Detailpendingso_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Pending Sales Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index(){
        $data = $this->Pendingso_model->order_by('no_so','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Pending Sales Order');
        $this->template->render('pendingsolist');
    }

    public function newpendingso()
    {
        //$this->auth->restrict($this->viewPermission);
        $data = $this->Salesorder_model->get_pending_so();
        //$data = $this->Salesorder_model->order_by('no_so','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Pending Sales Order');
        $this->template->render('list');
    }

    public function proses(){
        $noso = $this->uri->segment(3);
        $detail = $this->Salesorder_model->get_data(array('no_so'=>$noso,'qty_pending !='=>0),'trans_so_detail');
        $this->template->set('detail', $detail);
        $this->template->title('Proses Pending SO');
        $this->template->render('prosespendingso');
    }

    function generate_no_pending($param){
        $data = $this->Salesorder_model->get_data(array('no_so'=>$param),'trans_so_pending_header');
        $no_akhir = count($data);
        $next_nomor = $no_akhir+1;
        $next_kode = str_pad($next_nomor, 2, "0", STR_PAD_LEFT);
        return $param.'-'.$next_kode;
    }

    public function savependingso(){
        $session = $this->session->userdata('app_session');
        /*
        {"id_barang":["HPDCCN001002","HECTCN001001"],"qty_pending":["2","1"],"qty_confirm":["",""],"pending_again":["",""],"cancel_again":["",""]}
        */
        $noso = $this->uri->segment(3);
        $header_so = $this->Salesorder_model->cek_data(array('no_so'=>$noso),'trans_so_header');
        $header_pending_so = array(
            'no_so_pending' => $this->generate_no_pending($noso),
            'no_so' => $noso,
            'no_picking_list' => $this->Salesorder_model->generate_no_pl($session['kdcab']),
            'id_customer' => $header_so->id_customer,
            'nm_customer' => $header_so->nm_customer,
            'id_salesman' => $header_so->id_salesman,
            'tanggal' => date('Y-m-d'),
            'nm_salesman' => $header_so->nm_salesman,
            'pic' => $header_so->pic,
            'waktu' => date('Y-m-d H:i:s'),
            'create_by' => $session['id_user'],
            'create_on' => date('Y-m-d H:i:s')
            );

        $this->db->trans_begin();
        for($i=0;$i < count($this->input->post('id_barang'));$i++){
            $key = array(
            'no_so' => $this->input->post('no_so_pending')[$i],
            'id_barang' => $this->input->post('id_barang')[$i]
            );
            $getitemso_to_pending = $this->Detailsalesorder_model->find_by($key);
            $subtotal = $getitemso_to_pending->harga*$this->input->post('qty_confirm')[$i];
            $data_pending = array(
                'no_so_pending' => $this->generate_no_pending($getitemso_to_pending->no_so),
                'no_so' => $this->input->post('no_so_pending')[$i],
                'id_barang' => $this->input->post('id_barang')[$i],
                'nm_barang' => $getitemso_to_pending->nm_barang,
                'satuan' => $getitemso_to_pending->satuan,
                'jenis' => $getitemso_to_pending->jenis,
                'qty_confirm' => $this->input->post('qty_confirm')[$i],
                'qty_pending_again' => $this->input->post('pending_again')[$i],
                'qty_cancel' => $this->input->post('cancel_again')[$i],
                'stok_avl' => $this->input->post('stok_avl')[$i],
                'ukuran' => $getitemso_to_pending->ukuran,
                'harga' => $getitemso_to_pending->harga,
                'diskon' => $getitemso_to_pending->diskon,
                'subtotal' => $subtotal,
                'createdby' => $session['id_user']
                );
            $this->db->insert('trans_so_pending_detail',$data_pending);

            //Update SO AWAL//
            $data_update_so_awal = array(
                'qty_pending' => $this->input->post('pending_again')[$i],
                'qty_supply' => $getitemso_to_pending->qty_supply+$this->input->post('qty_confirm')[$i]
                );
            $this->db->where($key);
            $this->db->update('trans_so_detail',$data_update_so_awal);
            //END Update SO AWAL//

            //Update QTY_AVL
            $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('id_barang')[$i]);
            $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
            $this->db->where($keycek);
            $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$this->input->post('qty_confirm')[$i]));
            //Update QTY_AVL
        }
        $this->db->insert('trans_so_pending_header',$header_pending_so);
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

    function print_request($noso){
        $no_so = $noso;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so));

        $this->template->set('so_data', $so_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

    function print_picking_list($no_so_pending){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Pendingso_model->find_data('trans_so_pending_header',$no_so_pending,'no_so_pending');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailpendingso_model->find_all_by(array('no_so_pending' => $no_so_pending));

        $this->template->set('so_data', $so_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_picking_list',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }
}

?>
