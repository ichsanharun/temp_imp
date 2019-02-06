<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Koli
 */

class Giro extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission

    protected $viewPermission   = "Giro.View";
    protected $addPermission    = "Giro.Add";
    protected $managePermission = "Giro.Manage";
    protected $deletePermission = "Giro.Delete";


    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Giro/Giro_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Data Giro');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Giro_model->where(array('kdcab'=>$session['kdcab']))->order_by('tgl_jth_tempo','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Data Giro');
        $this->template->render('list');
    }

    public function filter()
    {
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Giro_model->where(array('kdcab'=>$session['kdcab'],'status'=>$this->input->get('status')))->order_by('tgl_jth_tempo','ASC')->find_all();

        $this->template->set('filter_status', $this->input->get('status'));
        $this->template->set('results', $data);
        $this->template->title('Data Giro');
        $this->template->render('list');
    }


   	//Create New barang
   	public function create()
   	{

        //$this->auth->restrict($this->addPermission);

        //$barang = $this->Barang_model->pilih_barang()->result();

        $session = $this->session->userdata('app_session');
        $bank = $this->Giro_model->get_data('1=1','bank');
        $customer = $this->Giro_model->get_data(array('kdcab'=>$session['kdcab']),'customer');
        $this->template->set('bank', $bank);
        $this->template->set('customer', $customer);
        $this->template->title('Tambah Data Giro');
		$this->template->render('giro_form');
   	}

    function simpandatagiro(){
        $session = $this->session->userdata('app_session');

        $kdbank = '';
        $nmbank = '';
        $idcus = '';
        $nmcus = '';
        if(empty($this->input->post('girobank')) || empty($this->input->post('customer_giro')) || empty($this->input->post('tgl_transaksi_giro')) || empty($this->input->post('nilai_fisik_giro')) || empty($this->input->post('nilai_fisik_giro')) || empty($this->input->post('tgl_jth_tempo_giro')) ){
            $param = array(
            'save' => 0,
            'msg' => "Data harus lengkap..!!!"
            );
        }else{
        if(!empty($this->input->post('girobank'))){
            $bank = explode('|',$this->input->post('girobank'));
            $kdbank = $bank[0];
            $nmbank = $bank[1];
        }

        if(!empty($this->input->post('customer_giro'))){
            $cus = explode('|',$this->input->post('customer_giro'));
            $idcus = $cus[0];
            $nmcus = $cus[1];
        }

        $datagiro = array(
            'no_giro' => $this->input->post('no_giro'),
            'kdcab' => $session['kdcab'],
            'id_bank' => $kdbank,
            'nm_bank' => $nmbank,
            'id_customer' => $idcus,
            'nm_customer' => $nmcus,
            'tgl_giro' => $this->input->post('tgl_transaksi_giro'),
            'nilai_fisik' => $this->input->post('nilai_fisik_giro'),
            'tgl_jth_tempo' => $this->input->post('tgl_jth_tempo_giro'),
            'status_giro' => 'N',
            'status' => 'OPEN',
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user']
            );

        $this->db->trans_begin();
        $this->db->insert('giro',$datagiro);
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
        }//tutup IF empty
        echo json_encode($param);
    }


   	//Edit barang
   	public function edit()
   	{

  		$this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);
        $barang = $this->Barang_model->pilih_barang()->result();

        $this->template->set('barang',$barang);
        $this->template->set('data', $this->Barang_koli_model->find($id));
        $this->template->title("Koli");
        $this->template->render('koli_form');
   	}

    function print_request(){
        $status = $this->uri->segment(4);
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $session = $this->session->userdata('app_session');
        $giro = $this->Giro_model->where(array('kdcab'=>$session['kdcab'],'status'=>$status))->order_by('tgl_jth_tempo','ASC')->find_all();
        $this->template->set('giro', $giro);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
}
?>
