<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 * 
 * This is controller for Barang
 */

class Barang extends Admin_Controller {
    
    /**
     * Load the models, library, etc
     *
     * 
     */
    //Permission
    protected $viewPermission   = "Barang.View";
    protected $addPermission    = "Barang.Add";
    protected $managePermission = "Barang.Manage";
    protected $deletePermission = "Barang.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Barang/Barang_group_model',
                                 'Koli/Barang_koli_model', 
                                 'Komponen/Barang_komponen_model',                                
                                 'Supplier/Supplier_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Master Produk');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = $this->Barang_model->select("barang_master.id_barang,
                                            barang_master.id_group,
                                            barang_master.nm_barang,
                                            barang_master.jenis,
                                            barang_master.brand,
                                            barang_master.model,
                                            barang_master.satuan,
                                            barang_master.sts_aktif,
                                            barang_group.nm_group")
                                            ->join("barang_group","barang_group.id_group = barang_master.id_group")->where('barang_master.deleted',0)
                                            ->order_by('nm_barang','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Produk');
        $this->template->render('list'); 
    }

   	//Create New barang
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        $suppl_barang = $this->Supplier_model->pilih_supplier()->result();
        $koli = $this->Barang_koli_model->pilih_koli()->result();

        $this->template->set('koli',$koli);
        $this->template->set('group_barang',$group_barang);
        $this->template->set('suppl_barang',$suppl_barang);
        $this->template->title('Barang');
		$this->template->render('barang_form');
   	}

   	//Edit barang
   	public function edit()
   	{
   		
  		$this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);         
        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        $suppl_barang = $this->Supplier_model->pilih_supplier()->result();
                
        $this->template->set('group_barang',$group_barang);
        $this->template->set('suppl_barang',$suppl_barang);
        $this->template->set('data', $this->Barang_model->find($id));
        $this->template->title(lang("barang_title_edit"));
        $this->template->render('barang_form');
   	}

    //Save using ajax
    public function save_data_ajax(){

        $id_barang      = $this->input->post("id_barang");        
        $type           = $this->input->post("type");
        $id_group       = $this->input->post("id_group");               
        $nm_barang      = $this->input->post("nm_barang");
        $brand          = $this->input->post("brand");
        $model          = $this->input->post("model");
        $satuan         = $this->input->post("satuan");    
        $spesifikasi    = $this->input->post("spesifikasi"); 
        $id_supplier    = $this->input->post("id_supplier");     
        $sts_aktif      = $this->input->post("sts_aktif");

        if(empty($id_barang) || $id_barang==""){
            $query = $this->Barang_model->get_kode_barang($id_group); 
            if(empty($query)){
                return 'Error';
            }else{
                $id_barang=$query;
            }
        }else{
            $id_barang = $id_barang;
        }
        
        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_supplier!="")
            {
                $data = array(
                            array(
                                'id_barang'=>$id_barang,
                                'id_group'=>$id_group,
                                'nm_barang'=>$nm_barang,
                                'brand'=>$brand,                                    
                                'model'=>$model,
                                'satuan'=>$satuan,
                                'spesifikasi'=>$spesifikasi,
                                'id_supplier'=>$id_supplier,
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
                        'id_group'=>$id_group,
                        'id_barang'=>$id_barang,
                        'nm_barang'=>$nm_barang,
                        'brand'=>$brand,                                    
                        'model'=>$model,
                        'satuan'=>$satuan,
                        'spesifikasi'=>$spesifikasi,
                        'id_supplier'=>$id_supplier,
                        'sts_aktif'=>$sts_aktif,  
                        );

            //Add Data
            $id = $this->Barang_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambahBarang ".$id_barang.", atas Nama : ".$nm_barang;
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
                $keterangan     = "GAGAL, tambah data Barang ".$id_barang.", atas Nama : ".$nm_barang;
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
                'barang_nm'=> $nm_barang,
                'save' => $result
                );

        echo json_encode($param);
    }

    //Save using ajax
    public function save_data_koli(){

        $id_koli    = $this->input->post("id_koli");        
        $type       = $this->input->post("type1");
        $nm_koli    = $this->input->post("nm_koli");
        $id_barang  = $this->input->post("barang");               
        $nm_barang  = $this->input->post("barang_nm");
        $qty        = $this->input->post("qty");
        $keterangan = $this->input->post("keterangan");  
        $sts_aktif  = $this->input->post("sts_aktif");

        if(empty($id_koli) || $id_koli==""){
            $query = $this->Barang_koli_model->get_id_koli($id_barang); 
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
                                'qty'=>$qty,
                                'keterangan'=>$keterangan,
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
                        'qty'=>$qty,
                        'keterangan'=>$keterangan,
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
                'koli' => $id_koli,
                'barang' => $id_barang,
                'save' => $result
                );

        echo json_encode($param);
    }    

    //Save using ajax
    public function save_data_komponen(){

        $type       = $this->input->post("type2");
        $id_komponen    = $this->input->post("id_komponen");                
        $nm_komponen    = $this->input->post("nm_komponen");
        $id_koli    = $this->input->post("id_koli");
        $qty        = $this->input->post("qty_kom");  
        $barang     = substr($id_koli,0,10);
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
                                'qty'=>$qty,
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
                        'qty'=>$qty,
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
                'barang' => $barang,
                'save' => $result
                );

        echo json_encode($param);
    }

    function add_gb()
    {
        $nm_group   = $this->input->post("nm_group");
        $id_group   = $this->Barang_group_model->get_id_group('G');  
        $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_group'=> $id_group,
                        'nm_group'=> $nm_group,
                        );

            //Add Data
            $id = $this->Barang_group_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah data Group Barang atas Nama : ".$nm_group;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result = TRUE;
            }
            else
            {
                $keterangan     = "GAGAL, tambah data Group Barang atas Nama : ".$nm_group;
                $status         = 0;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();
                $result = FALSE;
            }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(            
            'save' => $result
        );

        echo json_encode($param);
    }

    function get_gb(){
        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        //echo $result;
        echo "<select id='id_group' name='id_group' class='form-control pil_gb select2-hidden-accessible'>";
        echo "<option value=''></option>";
                foreach ($group_barang as $key => $st) :
                    echo "<option value='$st->id_group' set_select('id_group', $st->id_group, isset($data->id_group) && $data->id_group == $st->id_group)>$st->nm_group
                    </option>";
                endforeach;
        echo "</select>";
    }
    
    function get_koli(){
        $id   = $_GET['id_barang'];        
        $koli = $this->Barang_koli_model->tampil_koli($id)->result();
        //echo $result;
        echo "<select id='id_koli' name='id_koli' class='form-control pil_koli select2-hidden-accessible'>";
        echo "<option value=''></option>";
                foreach ($koli as $key => $st) :
                    echo "<option value='$st->id_koli' set_select('id_koli', $st->id_koli, isset($data->id_koli) && $data->id_koli == $st->id_koli)>$st->nm_koli
                    </option>";
                endforeach;
        echo "</select>";
    }

    function hapus_barang()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if($id!=''){

            $result = $this->Barang_model->delete($id);

            $keterangan     = "SUKSES, Delete data Barang ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Setup Barang ".$id;
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

    function hapus_koli()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if($id!=''){

            $result = $this->Barang_koli_model->delete($id);

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
            $keterangan     = "GAGAL, Delete data Koli ".$id;
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
                'id'=>$id
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
            $keterangan     = "GAGAL, Delete data Komponen ".$id;
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
                'id'=>$id
                );

        echo json_encode($param);
    }

    
    function load_koli(){
        $id_barang = $_GET['id_barang'];
        echo "<div class='box-body'><B>Data Koli</B><table id='lis_koli' class='table table-bordered table-striped'>
        <thead>
        <tr>
            <th width='50'>#</th>
            <th>Kode Koli</th>
            <th>Nama Koli</th>
            <th>Qty</th>
            <th>Keterangan</th>
            <th width='25'>Hapus</th>
        </tr>
        </thead>";
        $no=1;
        $data=  $this->Barang_koli_model->tampil_koli($id_barang)->result();
        foreach ($data as $d){
            echo "<tr id='dataku$d->id_koli'>
                <td>$no</td>
                <td>$d->id_koli</td>
                <td>$d->nm_koli</td>
                <td>$d->qty</td>
                <td>$d->keterangan</td>                
                <td>
                 <a class='text-black' href='javascript:void(0)' title='Hapus' onclick=\"hapus_koli('".$d->id_koli."');\"><i class='fa fa-trash'></i>
                </a>
                </td>
                </tr>";
            $no++;
        }
        echo "<tfoot>
        <tr>
            <th width='50'>#</th>
            <th>Kode Koli</th>
            <th>Nama Koli</th>
            <th>Keterangan</th>
            <th width='25'>Hapus</th>
        </tr>
        </tfoot>";
    echo"</table></div>";
    }

    function load_komponen(){
        $id_barang = $_GET['id_barang'];
        echo "<div class='box-body'><B>Data Komponen</B><table id='lis_komponen' class='table table-bordered table-striped'>
        <thead>
        <tr>
            <th width='50'>#</th>
            <th>Kode Komponen</th>
            <th>Nama Komponen</th>
            <th>Qty</th>
            <th>Keterangan</th>
            <th width='25'>Hapus</th>
        </tr>
        </thead>";
        $no=1;
        $data=  $this->Barang_komponen_model->tampil_komponen($id_barang)->result();
        foreach ($data as $d){
            echo "<tr id='dataku$d->id_komponen'>
                <td>$no</td>
                <td>$d->id_komponen</td>
                <td>$d->nm_komponen</td>
                <td>$d->qty</td>
                <td>$d->keterangan</td>                
                <td>
                 <a class='text-black' href='javascript:void(0)' title='Hapus' onclick=\"hapus_komponen('".$d->id_komponen."');\"><i class='fa fa-trash'></i>
                </a>
                </td>
                </tr>";
            $no++;
        }
        echo "<tfoot>
        <tr>
            <th width='50'>#</th>
            <th>Kode Komponen</th>
            <th>Nama Komponen</th>
            <th>Qty</th>
            <th>Keterangan</th>
            <th width='25'>Hapus</th>
        </tr>
        </tfoot>";
    echo"</table></div>";
    }


    function print_request($id){
        $id_barang = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $brg_data      =  $this->Barang_model->find_data('barang_master',$id_barang,'id_barang');        
        $kol_data      =  $this->Barang_koli_model->tampil_koli($id_barang)->result();
        $kom_data      =  $this->Barang_komponen_model->tampil_komponen($id_barang)->result();

        $this->template->set('brg_data', $brg_data);
        $this->template->set('kol_data', $kol_data);
        $this->template->set('kom_data', $kom_data);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }
}
?>