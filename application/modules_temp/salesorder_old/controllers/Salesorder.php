<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Salesorder extends Admin_Controller {
    
    //Permission
    protected $viewPermission   = "Salesorder.View";
    protected $addPermission    = "Salesorder.Add";
    protected $managePermission = "Salesorder.Manage";
    protected $deletePermission = "Salesorder.Delete";
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Salesorder/Salesorder_model',
                                 'Salesorder/Detailsalesorder_model',
                                 'Salesorder/Detailsalesordertmp_model',
                                 'Customer/Customer_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Sales Order');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = $this->Salesorder_model->order_by('no_so','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Sales Order');
        $this->template->render('list');
    }

    //Create New Sales Order
    public function create()
    {
        $this->auth->restrict($this->addPermission);

        $itembarang    = $this->Salesorder_model->pilih_item()->result();
        $listitembarang = $this->Detailsalesordertmp_model->find_all();
        $customer = $this->Customer_model->find_all();
        $marketing = $this->Salesorder_model->pilih_marketing()->result();

        $this->template->set('itembarang',$itembarang);
        $this->template->set('listitembarang',$listitembarang);
        $this->template->set('customer',$customer);
        $this->template->set('marketing',$marketing);
        $this->template->title('Input Sales Order');
        $this->template->render('salesorder_form');
    }

    //Edit Sales Order
    public function edit()
    {
        //$this->auth->restrict($this->addPermission);
        $noso= $this->uri->segment(3);
        $header  = $this->Salesorder_model->find_by(array('no_so' => $noso));
        //$detail  = $this->Detailsalesorder_model->find_all(array('no_so' => $noso));

        $itembarang    = $this->Salesorder_model->pilih_item()->result();
        $listitembarang = $this->Detailsalesorder_model->find_all_by(array('no_so' => $noso));
        $customer = $this->Customer_model->find_all();
        $marketing = $this->Salesorder_model->pilih_marketing()->result();

        $this->template->set('itembarang',$itembarang);
        $this->template->set('data',$header);
        $this->template->set('listitembarang',$listitembarang);
        $this->template->set('customer',$customer);
        $this->template->set('marketing',$marketing);
        $this->template->title('Edit Sales Order');
        $this->template->render('salesorder_form_edit');
    }

    function get_detail_so(){
        $noso = $this->input->post('NO_SO');
        $id = $this->input->post('ID');
        $key = array('no_so'=>$noso,'id_barang'=>$id);
        if(!empty($noso) && !empty($id)){
            $detail  = $this->Detailsalesorder_model->find_by($key);
        }
        echo json_encode($detail);
    }

    //Get detail item barang
    function get_item_barang(){
        $idbarang = $_GET['idbarang'];
        $datbarang = $this->Salesorder_model->get_item_barang($idbarang)->row();

        echo json_encode($datbarang);
    }

    //Get detail Customer
    function get_customer(){
        $idcus = $_GET['idcus'];
        $customer = $this->Salesorder_model->get_customer($idcus)->row();

        echo json_encode($customer);
    }

    //Get detail Sales
    function get_salesman(){
        $idsales = $_GET['idsales'];
        $salesman = $this->Salesorder_model->get_marketing($idsales)->row();

        echo json_encode($salesman);
    }

    function saveitemso(){
        $session = $this->session->userdata('app_session');
        $noso = $this->Salesorder_model->generate_noso($session['kdcab']);
        $idbarang = $this->input->post('item_brg_so');
        $nmbarang = $this->input->post('nama_barang');
        $satuan = $this->input->post('satuan');
        $qtyorder = $this->input->post('qty_order');
        $qtyavl = $this->input->post('qty_avl');
        $harga = $this->input->post('harga');
        $diskon = $this->input->post('diskon');
        $total = $this->input->post('total');
        //$this->auth->restrict($this->addPermission);

        $dataso = array(
            'no_so' => $noso,
            'id_barang' => $idbarang,
            'nm_barang' => $nmbarang,
            'satuan' => $satuan,
            'jenis' => '',
            'qty_order' => $qtyorder,
            'qty_supply' => $qtyavl,
            'ukuran' => '',
            'harga' => $harga,
            'diskon' => $diskon
            );
        $this->db->trans_begin();
        $this->db->insert('trans_so_detail_tmp',$dataso);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, tambah item barang..!!!"
            );
        }
        echo json_encode($param);
    }

    function ajaxdetailso(){
        $this->load->view('salesorder/ajax/ajaxdetailsossss');
    }

    function saveheaderso(){
        $session = $this->session->userdata('app_session');
        $noso = $this->Salesorder_model->generate_noso($session['kdcab']);
        $idcustomer = $this->input->post('idcustomer');
        $nmcustomer = $this->input->post('nmcustomer');
        $tglso = $this->input->post('tglso');
        $idsalesman = $this->input->post('idsalesman');
        $nmsalesman = $this->input->post('nmsalesman');
        $picso = $this->input->post('pic');
        $waktu = date('Y-m-d H:i:s');
        $statusso = '';
        $dppso = $this->input->post('dppso');
        $ppnso = $this->input->post('ppnso');
        $flagppn = $this->input->post('nilaippn');
        $totalso = $this->input->post('totalso');

        $dataheaderso = array(
            'no_so' => $noso,
            'id_customer' => $idcustomer,
            'nm_customer' => $nmcustomer,
            'tanggal' => $tglso,
            'id_salesman' => $idsalesman,
            'nm_salesman' => $nmsalesman,
            'pic' => $picso,
            'waktu' => $waktu,
            'dpp' => $dppso,
            'ppn' => $ppnso,
            'flag_ppn' => $flagppn,
            'total' => $totalso
            );
        $this->db->trans_begin();
        $data_tmp = $this->Detailsalesordertmp_model->find_all();
        foreach($data_tmp as $key=>$val){
            $dataitem = array(
                'no_so' => $val->no_so,
                'id_barang' => $val->id_barang,
                'nm_barang' => $val->nm_barang,
                'satuan' => $val->satuan,
                'jenis' => '',
                'qty_order' => $val->qty_order,
                'qty_supply' => $val->qty_supply,
                'ukuran' => '',
                'harga' => $val->harga,
                'diskon' => $val->diskon
            );
            $this->db->insert('trans_so_detail',$dataitem);
            //Update QTY_AVL
            $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$val->id_barang);
            $stok_avl = $this->Salesorder_model->cek_data($keycek,'barang_stock');
            $this->db->where($keycek);
            $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$val->qty_order));
            //Update QTY_AVL
        }
        $this->db->insert('trans_so_header',$dataheaderso);

        //Update counter NO_SO
        $counter = $this->Salesorder_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_so'=>$counter->no_so+1));
        //Update counter NO_SO

        $this->db->truncate('trans_so_detail_tmp');

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, tambah item barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, tambah item barang..!!!"
            );
        }
        echo json_encode($param);
    }

    function hapus_item_so(){
        $noso = $this->input->post('NO_SO');
        $id = $this->input->post('ID');
        $key = array('no_so'=>$noso,'id_barang'=>$id);
        if(!empty($noso) && !empty($id)){
           $result = $this->Detailsalesordertmp_model->delete_where($key);
           $param['delete'] = 1; 
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }

    function hapus_header_so(){
        $noso = $this->input->post('NO_SO');
        if(!empty($noso)){
           $result = $this->Salesorder_model->delete($noso);
           $param['delete'] = 1; 
        }else{
            $param['delete'] = 0;
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

}

?>
