<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Koli
 */

class Koli extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Koli.View";
    protected $addPermission    = "Koli.Add";
    protected $managePermission = "Koli.Manage";
    protected $deletePermission = "Koli.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Koli/Barang_koli_model',
                                 'Barang/Barang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Master Koli');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = $this->Barang_koli_model
        ->SELECT("barang_koli.*,barang_koli_model.koli_model,barang_koli_warna.koli_warna,barang_koli_varian.koli_varian")
        ->where(array('deleted'=>0,'barang_koli.sts_aktif'=>'aktif'))
        ->join("barang_koli_model","barang_koli.id_koli_model = barang_koli_model.id_koli_model","left")
        ->join("barang_koli_warna","barang_koli.id_koli_warna = barang_koli_warna.id_koli_warna","left")
        ->join("barang_koli_varian","barang_koli.id_koli_varian = barang_koli_varian.id_koli_varian","left")
        ->order_by('nm_koli','ASC')->find_all();

        $model = $this->Barang_koli_model->koli_model()->result();
        $warna = $this->Barang_koli_model->koli_warna()->result();
        $varian = $this->Barang_koli_model->koli_varian()->result();

        $this->template->set('results', $data);
        $this->template->set('model', $model);
        $this->template->set('warna', $warna);
        $this->template->set('varian', $varian);
        $this->template->title('Data Koli');
        $this->template->render('list');
    }

   	//Create New barang
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $barang = $this->Barang_model->pilih_barang()->result();
        $model = $this->Barang_koli_model->koli_model()->result();
        $warna = $this->Barang_koli_model->koli_warna()->result();
        $varian = $this->Barang_koli_model->koli_varian()->result();

        $this->template->set('barang',$barang);
        $this->template->set('model', $model);
        $this->template->set('warna', $warna);
        $this->template->set('varian', $varian);
        $this->template->title('Koli');
		      $this->template->render('koli_form');
   	}

    public function create_anak($anak)
   	{

        $this->auth->restrict($this->addPermission);
        if ($anak == 'model') {
          $id = $this->Barang_koli_model->get_id_anak('model');
        }elseif ($anak == 'warna') {
          $id = $this->Barang_koli_model->get_id_anak('warna');
        }else {
          $id = $this->Barang_koli_model->get_id_anak('varian');
        }


        $this->template->set('id',$id);
        $this->template->set('tipe',$anak);

        $this->template->title('Koli');
		    $this->template->render('anak_form');
   	}

   	//Edit barang
   	public function edit()
   	{

  		$this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);
        $barang = $this->Barang_model->pilih_barang()->result();
        $model = $this->Barang_koli_model->koli_model()->result();
        $warna = $this->Barang_koli_model->koli_warna()->result();
        $varian = $this->Barang_koli_model->koli_varian()->result();

        $this->template->set('barang',$barang);
        $this->template->set('model', $model);
        $this->template->set('warna', $warna);
        $this->template->set('varian', $varian);
        $this->template->set('data', $this->Barang_koli_model->SELECT("barang_koli.*,barang_koli_model.koli_model,barang_koli_warna.koli_warna,barang_koli_varian.koli_varian")

        ->join("barang_koli_model","barang_koli.id_koli_model = barang_koli_model.id_koli_model","left")
        ->join("barang_koli_warna","barang_koli.id_koli_warna = barang_koli_warna.id_koli_warna","left")
        ->join("barang_koli_varian","barang_koli.id_koli_varian = barang_koli_varian.id_koli_varian","left")->find($id));
        $this->template->title("Koli");
        $this->template->render('koli_form');
   	}

    public function edit_anak($anak,$act)
   	{

        $this->auth->restrict($this->addPermission);
        if ($anak == 'model') {
          $data = $this->Barang_koli_model->get_data_anak('model',$act)->result();
          $nama_anak = $data[0]->koli_model;
        }elseif ($anak == 'warna') {
          $data = $this->Barang_koli_model->get_data_anak('warna',$act)->result();
          $nama_anak = $data[0]->koli_warna;
        }else {
          $data = $this->Barang_koli_model->get_data_anak('varian',$act)->result();
          $nama_anak = $data[0]->koli_varian;
        }


        $this->template->set('data',$data);
        $this->template->set('id',$data[0]->id);
        $this->template->set('nama_anak',$nama_anak);
        $this->template->set('tipe',$anak);

        $this->template->title('Koli');
		    $this->template->render('anak_form');
   	}

    //Save using ajax
    public function save_data_ajax(){

        $id_koli    = $this->input->post("id_koli");
        $type       = $this->input->post("type");
        $nm_koli    = $this->input->post("nm_koli");
        $id_barang  = $this->input->post("id_barang");
        $nm_barang  = $this->input->post("nm_barang");
        $id_koli_model   = $this->input->post("id_koli_model");
        $id_koli_warna   = $this->input->post("id_koli_warna");
        $id_koli_varian  = $this->input->post("id_koli_varian");
        $keterangan = $this->input->post("keterangan");
        $sts_aktif  = $this->input->post("sts_aktif");

        if(empty($id_koli) || $id_koli==""){
            $query = $this->Barang_koli_model->get_id_koli('K');
            if(empty($query)){
                return 'Error';
            }else{
                $id_koli=$query;
            }
        }else{
            $id_koli = $id_koli;
        }

        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_koli!="")
            {
                $data = array(
                            array(
                                'id_koli'=>$id_koli,
                                'nm_koli'=>$nm_koli,
                                'id_barang'=>$id_barang,
                                'nm_barang'=>$nm_barang,
                                'keterangan'=>$keterangan,
                                'id_koli_model'=>$id_koli_model,
                                'id_koli_warna'=>$id_koli_warna,
                                'id_koli_varian'=>$id_koli_varian,
                                'sts_aktif'=>$sts_aktif,
                            )
                        );

                //Update data
                $result = $this->Barang_koli_model->update_batch($data,'id_koli');

                $keterangan     = "SUKSES, Edit data Koli ".$id_koli.", atas Nama : ".$nm_koli;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_barang;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Koli ".$id_koli.", atas Nama : ".$nm_koli;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_koli;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_koli'=>$id_koli,
                        'nm_koli'=>$nm_koli,
                        'id_barang'=>$id_barang,
                        'nm_barang'=>$nm_barang,
                        'keterangan'=>$keterangan,
                        'id_koli_model'=>$id_koli_model,
                        'id_koli_warna'=>$id_koli_warna,
                        'id_koli_varian'=>$id_koli_varian,
                        'sts_aktif'=>$sts_aktif,
                        );

            //Add Data
            $id = $this->Barang_koli_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah Koli ".$id_koli.", atas Nama : ".$nm_koli;
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
                $keterangan     = "GAGAL, tambah data Koli ".$id_koli.", atas Nama : ".$nm_koli;
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

    public function save_data_anak(){

        $type       = $this->input->post("type_form");
        $tipe       = $this->input->post("tipe");
        $id_koli    = $this->input->post("id_koli_".$tipe);
        $nm_koli    = $this->input->post("koli_".$tipe);
        $sts_aktif  = $this->input->post("sts_aktif");

        if(empty($id_koli) || $id_koli==""){
            $query = $this->Barang_koli_model->get_id_koli('K');
            if(empty($query)){
                return 'Error';
            }else{
                $id_koli=$query;
            }
        }else{
            $id_koli = $id_koli;
        }

        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_koli!="")
            {
                $data = array(
                          'id_koli_'.$tipe=>$id_koli,
                          'koli_'.$tipe=>$nm_koli,
                          'sts_aktif'=>$sts_aktif,
                        );

                //Update data
                $result = $this->db->where(array('id_koli_'.$tipe => $id_koli))->update('barang_koli_'.$tipe,$data);


                $keterangan     = "SUKSES, Edit data Koli ".$id_koli.", atas Nama : ".$nm_koli;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Koli ".$id_koli.", atas Nama : ".$nm_koli;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_koli;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);

            $data = array(
                      'id_koli_'.$tipe=>$id_koli,
                      'koli_'.$tipe=>$nm_koli,
                      'sts_aktif'=>$sts_aktif,
                    );

            //Add Data
            //$id = $this->db->insert('barang_koli_'.$tipe,$data);

            if($this->db->insert('barang_koli_'.$tipe,$data))
            {
                $keterangan     = "SUKSES, tambah Koli ".$id_koli.", atas Nama : ".$nm_koli;
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
                $keterangan     = "GAGAL, tambah data Koli ".$id_koli.", atas Nama : ".$nm_koli;
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

    function hapus_koli()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);
        $tipe = $this->uri->segment(4);

        if($id!=''){

            $result = $this->Barang_koli_model->delete($id,$tipe);

            $keterangan     = "SUKSES, Delete data Koli ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Koli".$id;
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
        $id_koli = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $brg_data      =  $this->Barang_koli_model->find_data('barang_koli',$id_koli,'id_koli');
        $this->template->set('brg_data', $brg_data);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
}
?>
