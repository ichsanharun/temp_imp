<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Receiving extends Admin_Controller {
    
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
        $this->load->model(array('Receiving/Receiving_model',
                                 'Receiving/Detailreceiving_model',
                                 'Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Receiving');
        $this->template->page_icon('fa fa-file');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);

        $data = $this->Receiving_model->order_by('no_receiving','ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Receiving');
        $this->template->render('list');
    }

    //Create New Receiving
    public function create()
    {   
        if($this->uri->segment(3) == ""){
            $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all();
        }else{
            $data = $this->Purchaseorder_model->order_by('no_po','ASC')->find_all_by(array('id_supplier'=>$this->uri->segment(3)));
        }
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');
        
        $this->template->set('supplier',$supplier);
        $this->template->set('results', $data);

        $this->template->title('Proses Receiving');
        $this->template->render('list_po');
    }

    //Proses Receiving
    public function proses(){
        $getparam = explode(";",$_GET['param']);
        $header = $this->Detailpurchaseorder_model->get_where_in('no_po',$getparam,'trans_po_header');
        $detail = $this->Detailpurchaseorder_model->get_where_in('no_po',$getparam,'trans_po_detail');
        $this->template->set('headerpo', $header);
        $this->template->set('detailpo', $detail);
        $this->template->title('Input Data Receiving');
        $this->template->render('receiving_form');
    }

    public function saveheaderreceiving(){
        $session = $this->session->userdata('app_session');
        $norec = $this->Receiving_model->generate_noreceive($session['kdcab']);
        $dataheader = array(
            'no_receiving' => $norec,
            'tglreceive' => $this->input->post('tglreceive'),
            'id_supplier' => $this->input->post('idsupplier'),
            'nm_supplier' => $this->input->post('nmsupplier'),
            'tgl_sjsupplier' => $this->input->post('tgldosupp'),
            'no_sjsupplier' => $this->input->post('no_do_supplier'),
            'noinvoice' => $norec,
            'tglinvoice' => $this->input->post('tglreceive')
            );

        $detail = array(
            'id_detail_po'=>$_POST['id_po_to_received']
            );

        $this->db->trans_begin();
        for($i=0;$i < count($detail['id_detail_po']);$i++){
            $key = array(
            'id_detail_po' => $_POST['id_po_to_received'][$i]
            );
            $getitempo = $this->Detailpurchaseorder_model->find_by($key);

            $detail_receive = array(
                'nolpb' => $norec,
                'po_no' => $getitempo->no_po,
                'kodebarang' => $getitempo->id_barang,
                'namabarang' => $getitempo->nm_barang,
                'hargabeli' => $getitempo->harga_satuan,
                'jumlah' => $_POST['qty_received'][$i],
                'namabarang' => $getitempo->nm_barang,
                'Satuan' => $getitempo->satuan,
                'tglreceive' => $this->input->post('tglreceive'),
                'user' => $session['id_user'],
                'noinvoice' => $norec,
                'tglinvoice' => $this->input->post('tglreceive'),
                'no_sjsupplier' => $this->input->post('no_do_supplier'),
                'tgl_sjsupplier' => $this->input->post('tgldosupp')
                );
            $this->db->insert('receive_detail',$detail_receive);

            //Update STOK REAL dan AVL
            $count = $this->Receiving_model->cek_data(array('id_barang'=>$getitempo->id_barang,'kdcab'=>$session['kdcab']),'barang_stock');
            $this->db->where(array('id_barang'=>$getitempo->id_barang,'kdcab'=>$session['kdcab']));
            $this->db->update('barang_stock',array('qty_stock'=>$count->qty_stock+$_POST['qty_received'][$i],'qty_avl'=>$count->qty_avl+$_POST['qty_received'][$i]));
            //Update STOK REAL
        }

        //Update counter NO_DO
        $count = $this->Receiving_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_receive'=>$count->no_receive+1));
        //Update counter NO_DO
        $this->db->insert('trans_receive',$dataheader);
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

    function print_request($norec){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $rec_data = $this->Receiving_model->find_data('trans_receive',$norec,'no_receiving');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailreceiving_model->find_all_by(array('nolpb' => $norec));

        $this->template->set('header', $rec_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

}

?>
