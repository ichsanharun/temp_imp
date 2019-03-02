<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Invoice extends Admin_Controller {
    
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
        $this->load->model(array('Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Customer/Customer_model',
                                 'Deliveryorder_2/Deliveryorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Invoice');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Invoice_model->order_by('no_invoice','ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Invoice');
        $this->template->render('list');
    }

    //Create New Invoice
    public function create()
    {
        $customer = $this->Customer_model->find_all();
        $this->template->set('customer',$customer);
        
        if($this->uri->segment(3) == ""){
            $data = $this->Deliveryorder_model->order_by('no_do','ASC')->find_all();
        }else{
            $data = $this->Deliveryorder_model->order_by('no_do','ASC')->find_all_by(array('id_customer'=>$this->uri->segment(3)));
        }
        $this->template->set('results', $data);
        $this->template->title('Input Invoice');
        $this->template->render('list_do');
    }

     //Create New Invoice
    public function proses()
    {
        $getparam = explode(";",$this->input->get('param'));
        $headerdo = $this->Invoice_model->get_where_in('no_do',$getparam,'trans_do_header');
        $detaildo = $this->Invoice_model->get_where_in('no_do',$getparam,'trans_do_detail');

        $this->template->set('headerdo', $headerdo);
        $this->template->set('detaildo', $detaildo);
        $this->template->title('Input Invoice');
        $this->template->render('invoice_form');
    }

    function saveheaderinvoice(){
        $session = $this->session->userdata('app_session');
        $no_invoice = $this->Invoice_model->generate_noinv($session['kdcab']);
        $customer = $this->Invoice_model->cek_data(array('id_customer'=>$this->input->post('idcustomer_do')),'customer');
        $headerinv = array(
            'no_invoice' => $no_invoice,
            'id_customer' => $this->input->post('idcustomer_do'),
            'nm_customer' => $this->input->post('nmcustomer_do'),
            'tanggal_invoice' => date('Y-m-d'),
            'id_salesman' => $this->input->post('id_salesman'),
            'nm_salesman' => $this->input->post('nm_salesman'),
            'nofakturpajak' => $no_invoice,
            'tglfakturpajak' => date('Y-m-d'),
            'tgljatuhtempo' => $this->input->post('tgljthtempo'),
            'alamatcustomer' => $customer->alamat,
            'npwpcustomer' => $customer->npwp
            );

        $key_no_do = explode(";",$this->input->post('param_do'));

        $this->db->trans_begin();
        $grand = 0;
        //foreach($key_no_do as $k){
            $detail_do = $this->Invoice_model->get_where_in('no_do',$key_no_do,'trans_do_detail');
            foreach($detail_do as $kd=>$vd){
                $key_so = array('no_so' => $vd->no_so,'id_barang' => $vd->id_barang);
                $detailso = $this->Invoice_model->cek_data($key_so,'trans_so_detail');
                $grand += $detailso->harga*$vd->qty_supply; 
                $detail_inv = array(
                    'no_invoice' => $no_invoice,
                    'id_barang' => $vd->id_barang,
                    'nm_barang' => $vd->nm_barang,
                    'satuan' => $vd->satuan,
                    'jumlah' => $vd->qty_supply,
                    'nofaktur' => $no_invoice,
                    'hargajual' => $detailso->harga,
                    'diskon' => $detailso->diskon,
                    'tgljual' => $detailso->tanggal
                    );
                $this->db->insert('trans_invoice_detail',$detail_inv); 
            } 
            
        //}
        $headerinv['hargajualtotal'] = $grand;
        //Update counter NO_DO
        $count = $this->Invoice_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_invoice'=>$count->no_invoice+1));
        //Update counter NO_DO
        $this->db->insert('trans_invoice_header',$headerinv);
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

    function print_request($noinv){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('header', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

}

?>
