<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 * 
 * This is controller for Komponen
 */

class Komponen extends Admin_Controller {
    
    /**
     * Load the models, library, etc
     *
     * 
     */
    //Permission
    protected $viewPermission   = "Komponen.View";
    protected $addPermission    = "Komponen.Add";
    protected $managePermission = "Komponen.Manage";
    protected $deletePermission = "Komponen.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Koli/Barang_koli_model',
                                 'Komponen/Barang_komponen_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Master Komponen');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = $this->Barang_komponen_model->where('deleted',0)->order_by('nm_komponen','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Data Komponen');
        $this->template->render('list'); 
    }

   	//Create New Koli
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $koli = $this->Barang_koli_model->pilih_koli()->result();

        $this->template->set('koli',$koli);
        $this->template->title('Komponen');
		$this->template->render('komponen_form');
   	}

   	//Edit barang
   	public function edit()
   	{
   		
  		$this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);         
        $koli = $this->Barang_koli_model->pilih_koli()->result();

        $this->template->set('koli',$koli);
        $this->template->set('data', $this->Barang_komponen_model->find($id));
        $this->template->title("Komponen");
        $this->template->render('komponen_form');
   	}

    //Save using ajax
    public function save_data_ajax(){

        $type       = $this->input->post("type");
        $id_komponen    = $this->input->post("id_komponen");                
        $nm_komponen    = $this->input->post("nm_komponen");
        $id_koli    = $this->input->post("id_koli");  
        $keterangan = $this->input->post("keterangan");  
        $sts_aktif  = $this->input->post("sts_aktif");

        if(empty($id_komponen) || $id_komponen==""){
            $query = $this->Barang_komponen_model->get_id_komponen($id_koli); 
            if(empty($query)){
                return 'Error';
            }else{
                $id_komponen=$query;
            }
        }else{
            $id_komponen = $id_komponen;
        }
        
        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_komponen!="")
            {
                $data = array(
                            array(
                                'id_komponen'=>$id_komponen,
                                'nm_komponen'=>$nm_komponen,     
                                'id_koli'=>$id_koli,
                                'keterangan'=>$keterangan,
                                'sts_aktif'=>$sts_aktif,
                            )
                        );

                //Update data
                $result = $this->Barang_komponen_model->update_batch($data,'id_komponen');

                $keterangan     = "SUKSES, Edit data Komponen ".$id_komponen.", atas Nama : ".$nm_komponen;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_komponen;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Komponen ".$id_komponen.", atas Nama : ".$nm_komponen;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_komponen;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_komponen'=>$id_komponen,
                        'nm_komponen'=>$nm_komponen,     
                        'id_koli'=>$id_koli,
                        'keterangan'=>$keterangan,
                        'sts_aktif'=>$sts_aktif,
                        );

            //Add Data
            $id = $this->Barang_komponen_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah Komponen ".$id_komponen.", atas Nama : ".$nm_komponen;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result         = TRUE;
                $komponen       = $id_komponen;
            }
            else
            {
                $keterangan     = "GAGAL, tambah data Komponen ".$id_komponen.", atas Nama : ".$nm_komponen;
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
                'save' => $result
                );

        echo json_encode($param);
    }

    function hapus_komponen()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if($id!=''){

            $result = $this->Barang_komponen_model->delete($id);

            $keterangan     = "SUKSES, Delete data Komponen ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Komponen".$id;
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

    function get_barang(){
        $id_barang = $_GET['id_barang'];
        $datbarang = $this->Barang_model->get_barang($id_barang);
        $param = array(
                'nm_barang' => $datbarang
                );
        echo json_encode($param);     
    }

    function print_request($id){
        $id_komponen= $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $kom_data      =  $this->Barang_komponen_model->find_data('barang_komponen',$id_komponen,'id_komponen');        
        $this->template->set('kom_data', $kom_data);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }
}
?>