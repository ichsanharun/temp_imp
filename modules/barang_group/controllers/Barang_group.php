<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 * 
 * This is controller for Barang_group
 */

class Barang_group extends Admin_Controller {
    
    /**
     * Load the models, library, etc
     *
     * 
     */
    //Permission
    protected $viewPermission   = "Barang_group.View";
    protected $addPermission    = "Barang_group.Add";
    protected $managePermission = "Barang_group.Manage";
    protected $deletePermission = "Barang_group.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang_group/Barang_group_model',
                                 'Koli/Barang_Koli_model',                                 
                                 'Supplier/Supplier_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Master Group Produk');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = $this->Barang_group_model->where('deleted',0)->order_by('id_group','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Group Produk');
        $this->template->render('list'); 
    }

   	//Create New barang
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        $suppl_barang = $this->Supplier_model->pilih_supplier()->result();

        $this->template->set('group_barang',$group_barang);
        $this->template->set('suppl_barang',$suppl_barang);
        $this->template->title('Produk Group');
		$this->template->render('barang_group_form');
   	}

   	//Edit barang
   	public function edit()
   	{
   		
  		$this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);
        $this->template->set('data', $this->Barang_group_model->find($id));
        $this->template->title('Produk Group');
        $this->template->render('barang_group_form');
   	}

    //Save using ajax
    public function save_data_ajax(){

        $id_group       = $this->input->post("id_group");        
        $type           = $this->input->post("type");
        $nm_group       = $this->input->post("nm_group");
        $budget_margin  = $this->input->post("budget_margin");                       
        $sts_aktif      = $this->input->post("sts_aktif");

        if(empty($id_group) || $id_group==""){
            $query   = $this->Barang_group_model->get_id_group('G'); 
            if(empty($query)){
                return 'Error';
            }else{
                $id_group=$query;
            }
        }else{
            $id_group = $id_group;
        }
        
        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_group!="")
            {
                $data = array(
                            array(
                                'id_group'=>$id_group,
                                'nm_group'=>$nm_group,
                                'budget_margin'=>$budget_margin,
                                'sts_aktif'=>$sts_aktif,  
                            )
                        );

                //Update data
                $result = $this->Barang_group_model->update_batch($data,'id_group');

                $keterangan     = "SUKSES, Edit data Produk Group ".$id_group.", atas Nama : ".$nm_group;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_group;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $id_group       = $id_group;
            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Produk Group ".$id_group.", atas Nama : ".$nm_group;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_group;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_group'=>$id_group,
                        'nm_group'=>$nm_group,
                        'budget_margin'=>$budget_margin,
                        'sts_aktif'=>$sts_aktif,
                        );

            //Add Data
            $id = $this->Barang_group_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah Produk Group ".$id_group.", atas Nama : ".$nm_group;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result         = TRUE;
                $id_group       = $id_group;
            }
            else
            {
                $keterangan     = "GAGAL, tambah Produk Group ".$id_group.", atas Nama : ".$nm_group;
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
                'id_group'=> $id_group,
                'save' => $result
                );

        echo json_encode($param);
    }

    
    function hapus_group()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if($id!=''){

            $result = $this->Barang_group_model->delete($id);

            $keterangan     = "SUKSES, Delete data Group Produk ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Group Produk ".$id;
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

    function print_request($id){
        $id_group = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $group_data      =  $this->Barang_group_model->find_data('barang_group',$id_group,'id_group');        
        $this->template->set('group_data', $group_data);
        $show = $this->template->load_view('print_data',$group_data);

        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }
}
?>