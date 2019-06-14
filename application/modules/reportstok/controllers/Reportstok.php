<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportstok extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Reportstok.View";
    protected $addPermission    = "Reportstok.Add";
    protected $managePermission = "Reportstok.Manage";
    protected $deletePermission = "Reportstok.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Reportstok/Reportstok_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Stock');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $data = $this->Reportstok_model
        ->join("cabang","barang_stock.kdcab = cabang.kdcab","left")
        ->where(array('barang_stock.kdcab'=>$this->auth->user_cab()))->find_all();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->render('list');
    }

   	//Create New barang
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $barang = $this->Barang_model->pilih_barang()->result();

        $this->template->set('barang',$barang);
        $this->template->title('Stock');
		      $this->template->render('report_stok_form');
   	}

   	//Edit barang
   	public function edit()
   	{

  		  $this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);
        $jenis_barang = $this->Barang_jenis_model->pilih_jb()->result();
        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        $suppl_barang = $this->Supplier_model->pilih_supplier()->result();
        $cp_barang = $this->Barang_cp_model->pilih_cp()->result();

        $this->template->set('cp_barang',$cp_barang);
        $this->template->set('jenis_barang',$jenis_barang);
        $this->template->set('group_barang',$group_barang);
        $this->template->set('suppl_barang',$suppl_barang);
        $this->template->set('data', $this->Barang_model->find($id));
        $this->template->title('Produk Group');
        $this->template->render('barang_form');
   	}

    //Save using ajax
    public function save_data_ajax(){

        $id_barang      = $this->input->post("id_barang");
        $type           = $this->input->post("type");
        $jenis          = $this->input->post("jenis");
        $nm_barang      = strtoupper($this->input->post("nm_barang"));
        $brand          = strtoupper($this->input->post("brand"));
        $kategori         = $this->input->post("kategori");
        $satuan         = $this->input->post("satuan");
        $sts_aktif      = $this->input->post("sts_aktif");

        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_barang!="")
            {
                $data = array(
                            array(
                                'id_barang'=>$id_barang,
                                'nm_barang'=>$nm_barang,
                                'brand'=>$brand,
                                'jenis'=>$jenis,
                                'kategori'=>$kategori,
                                'satuan'=>$satuan,
                                'kdcab'=>$this->auth->user_cab(),
                                'sts_aktif'=>$sts_aktif,
                            )
                        );

                //Update data
                $result = $this->Barang_model->update_batch($data,'id_barang');

                $keterangan     = "SUKSES, Edit data Barang ".$id_barang.", atas Nama : ".$nm_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_barang;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $barang       = $id_barang;
            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Barang ".$id_barang.", atas Nama : ".$nm_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_barang;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_barang'=>$id_barang,
                        'nm_barang'=>$nm_barang,
                        'brand'=>$brand,
                        'jenis'=>$jenis,
                        'kategori'=>$kategori,
                        'satuan'=>$satuan,
                        'kdcab'=>$this->auth->user_cab(),
                        'sts_aktif'=>$sts_aktif,
                        );

            //Add Data
            $id = $this->Reportstok_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah Setup Stok Barang ".$id_barang.", atas Nama : ".$nm_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result         = TRUE;
                $barang       = $id_barang;
            }
            else
            {
                $keterangan     = "GAGAL, tambah data Setup Stok Barang ".$id_barang.", atas Nama : ".$nm_barang;
                $status         = 0;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();
                $result = FALSE;
            }
            //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }

        $param = array(
                'barang'=> $barang,
                'series'=> $series,
                'save' => $result
                );

        echo json_encode($param);
    }

    function hapus_barang()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);
        $wheres = array(
            'id_barang'    => $id,
            'kdcab' => $this->auth->user_cab()
        );

        if($id!=''){

            $result = $this->Reportstok_model->delete_where($wheres);

            $keterangan     = "SUKSES, Delete data Setup Stok Barang ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Setup stok Barang ".$id;
            $status         = 0;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }

        //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
                'delete' => $result,
                'idx'=>$id
                );

        echo json_encode($param);
    }

    function get_data(){
        $id_barang = $this->input->post('id_barang');
        if(!empty($id_barang)){
            $detail  = $this->Barang_model->find($id_barang);
        }
        echo json_encode($detail);
    }

    function print_request($id){
        $id_barang = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $brg_data      =  $this->Reportstok_model->find_by( array('kdcab'=>$this->auth->user_cab(), 'id_barang'=>$id_barang) );

        $this->template->set('brg_data', $brg_data);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('P');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function print_rekap(){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $stok_data = $this->Reportstok_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0, 'kategori'=>'set') );

        $this->template->set('stok_data', $stok_data);

        $show = $this->template->load_view('print_rekap',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function print_rekap_group(){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $group_data = $this->Reportstok_model->get_data('1=1','barang_group');

        $this->template->set('group_data', $group_data);

        $show = $this->template->load_view('print_rekap_group',$data);

        $this->mpdf->AddPage('P');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function downloadExcel()
    {
      $session = $this->session->userdata('app_session');
      $kdcab = $session['kdcab'];


      $dataexcel = $this->Reportstok_model
      ->join("cabang","barang_stock.kdcab = cabang.kdcab","left")
      ->where(array('barang_stock.kdcab'=>$this->auth->user_cab(),'kategori'=>'set','barang_stock.deleted'=>0))->find_all();


      $data = array(
       'title2'		     => 'Report',
        'data_jenis'		 => $data_jenis,
       'results'	       => $dataexcel
     );

      $this->load->view('export_excel',$data);


    }
}
?>
