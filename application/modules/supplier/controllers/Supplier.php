<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Supplier
 */

class Supplier extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Supplier.View";
    protected $addPermission    = "Supplier.Add";
    protected $managePermission = "Supplier.Manage";
    protected $deletePermission = "Supplier.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Supplier/Supplier_model',
                                 'Aktifitas/aktifitas_model',
                                 'Supplier/Matuang_model',
                                 'Supplier/Negara_model'
                                ));
        $this->template->title('Manage Data Supplier');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        //$data = $this->Supplier_model->where('deleted',0)->order_by('nm_supplier','ASC')->find_all();
        $data = $this->Supplier_model->select("supplier.id_supplier,
                                            supplier.nm_supplier,
                                            supplier.id_negara,
                                            supplier.id_prov,
                                            supplier.id_kab,
                                            supplier.mata_uang,
                                            supplier.alamat,
                                            supplier.telpon,
                                            supplier.fax,
                                            supplier.email,
                                            supplier.cp,
                                            supplier.hp_cp,
                                            supplier.id_webchat,
                                            supplier.npwp,
                                            supplier.alamat_npwp,
                                            supplier.keterangan,
                                            supplier.sts_aktif,
                                            negara.nm_negara")
                                            ->join("negara","negara.id_negara = supplier.id_negara")
                                            ->where('supplier.deleted',0)
                                            ->order_by('id_supplier','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Supplier');
        $this->template->render('list');
    }

    //Create New Customer
    public function create()
    {
        $this->auth->restrict($this->addPermission);
        $datmatu    = $this->Matuang_model->pilih_matu()->result();
        $datnegara  = $this->Negara_model->pilih_negara()->result();
        $datprov    = $this->Supplier_model->pilih_provinsi()->result();

        $this->template->set('datmatu',$datmatu);
        $this->template->set('datnegara',$datnegara);
        $this->template->set('datprov',$datprov);
        $this->template->title('Input Master Supplier');
        $this->template->render('supplier_form');
    }

    function add_matu()
    {
        $kode       = $this->input->post("kode");
        $mata_uang  = $this->input->post("mata_uang");
        $negara     = $this->input->post("negara");

        $this->auth->restrict($this->addPermission);

            $data = array(
                        'kode'=> $kode,
                        'mata_uang'=> $mata_uang,
                        'negara'=> $negara,
                        );

            //Add Data
            $id = $this->Matuang_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah data mata_uang atas Nama : ".$kode;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result = TRUE;
            }
            else
            {
                $keterangan     = "GAGAL, tambah data mata_uang atas Nama : ".$kode;
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

    function get_matu(){
        $rmatu = $this->Matuang_model->pilih_matu()->result();
        //echo $result;
        echo "<select id='mata_uang' name='mata_uang' class='form-control pil_matu select2-hidden-accessible'>";
        echo "<option value=''></option>";
                foreach ($rmatu as $key => $st) :
                    echo "<option value='$st->id' set_select('mata_uang', $st->id, isset($data->mata_uang) && $data->mata_uang == $st->id)>$st->kode - $st->mata_uang - $st->negara
                    </option>";
                endforeach;
        echo "</select>";
    }

    //Edit Mitra
    public function edit()
    {
        $this->auth->restrict($this->managePermission);

        $id = $this->uri->segment(3);
        $data  = $this->Supplier_model->find_by(array('id_supplier' => $id));
        if(!$data)
        {
            $this->template->set_message("Invalid ID", 'error');
            redirect('Supplier');
        }

        $datmatu    = $this->Matuang_model->pilih_matu()->result();        
        $datnegara  = $this->Negara_model->pilih_negara()->result();
        $datprov    = $this->Supplier_model->pilih_provinsi()->result();
        $prov       = $this->Supplier_model->get_prov($id);
        $datkota    = $this->Supplier_model->pilih_kota($prov)->result();

        $this->template->set('datmatu',$datmatu);
        $this->template->set('datnegara',$datnegara);
        $this->template->set('datprov',$datprov);
        $this->template->set('datkota',$datkota);
        $this->template->set('data', $data);
        $this->template->title('Edit Data Supplier');
        $this->template->render('supplier_form');
    }

    //Save customer ajax
    public function save_data_supplier(){

        $type           = $this->input->post("type");
        $id_supplier    = $this->input->post("id_supplier");
        $nm_supplier    = strtoupper($this->input->post("nm_supplier"));        
        $alamat         = strtoupper($this->input->post("alamat"));        
        $telpon         = $this->input->post('telpon');
        $fax            = $this->input->post('fax');
        $npwp           = $this->input->post('npwp');
        $alamat_npwp    = strtoupper($this->input->post('alamat_npwp'));
        $cp             = strtoupper($this->input->post('cp'));
        $hp_cp          = $this->input->post('hp_cp');
        $email          = strtoupper($this->input->post('email'));        
        $keterangan_sup     = strtoupper($this->input->post('keterangan'));
        $sts_aktif      = $this->input->post('sts_aktif');
        $id_webchat     = strtoupper($this->input->post('id_webchat'));

        $id_negara      = $this->input->post('id_negara');
        $id_prov        = $this->input->post('id_prov');
        $id_kab         = $this->input->post('id_kab');

        $mata_uang      = $this->input->post('mata_uang');
        $matu        = array();
        if(!empty($mata_uang)) {
            foreach ($mata_uang as $mat_uang) {
                array_push($matu, $mat_uang);
            }            
            $mat_uang   = serialize($matu); 
        }

        if($id_supplier==""){
            $id_supplier = $this->Supplier_model->generate_id($id_negara);
        }else{
            $id_supplier = $id_supplier;
        }
        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_supplier!="")
            {
                $data = array(
                            array(
                                'id_supplier'=>$id_supplier,
                                'nm_supplier'=>$nm_supplier,
                                'alamat'=>$alamat,
                                'telpon'=>$telpon,
                                'fax'=>$fax,
                                'email'=>$email,
                                'cp'=>$cp,
                                'hp_cp'=>$hp_cp,
                                'npwp'=>$npwp,
                                'alamat_npwp'=>$alamat_npwp,
                                'mata_uang'=>$mat_uang,
                                'sts_aktif'=>$sts_aktif,
                                'id_negara'=>$id_negara,
                                'id_prov'=>$id_prov,
                                'id_kab'=>$id_kab,
                                'id_webchat'=>$id_webchat,
                                'keterangan'=>$keterangan_sup,
                            )
                        );

                //Update data
                $result = $this->Supplier_model->update_batch($data,'id_supplier');

                $keterangan     = "SUKSES, Edit data Supplier ".$id_supplier.", atas Nama : ".$nm_supplier;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_supplier;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $supplier       = $id_supplier;
            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Supplier ".$id_supplier.", atas Nama : ".$nm_supplier;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_customer;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_supplier'=>$id_supplier,
                        'nm_supplier'=>$nm_supplier,
                        'alamat'=>$alamat,
                        'telpon'=>$telpon,
                        'fax'=>$fax,
                        'email'=>$email,
                        'cp'=>$cp,
                        'hp_cp'=>$hp_cp,
                        'npwp'=>$npwp,
                        'alamat_npwp'=>$alamat_npwp,
                        'mata_uang'=>$mat_uang,
                        'sts_aktif'=>$sts_aktif,
                        'id_negara'=>$id_negara,
                        'id_prov'=>$id_prov,
                        'id_kab'=>$id_kab,
                        'id_webchat'=>$id_webchat,
                        'keterangan'=>$keterangan_sup,
                        );

            //Add Data
            $id = $this->Supplier_model->insert($data);

            if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah data Supplier ".$id_supplier.", atas Nama : ".$nm_supplier;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result         = TRUE;
                $customer       = $id_customer;
            }
            else
            {
                $keterangan     = "GAGAL, tambah data Supplier ".$id_supplier.", atas Nama : ".$nm_supplier;
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
                'supplier'=> $supplier,
                'save' => $result
                );

        echo json_encode($param);
    }

    function hapus_supplier()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if($id!=''){

            $result = $this->Supplier_model->delete($id);

            $keterangan     = "SUKSES, Delete data Supplier ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Supplier ".$id;
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
        $id_supplier = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $sup_data   =  $this->Supplier_model->print_data_supplier($id_supplier);
        
        $this->template->set('sup_data', $sup_data);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('P');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

    function print_rekap(){       
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();
        
        $rekap = $this->Supplier_model->rekap_data()->result_array();

        $this->template->set('rekap', $rekap);

        $show = $this->template->load_view('print_rekap',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);        
        $this->mpdf->Output();
    }

    function downloadExcel()
    {
        $rekap = $this->Supplier_model->rekap_data()->result_array();

        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(17);
        
        $objPHPExcel->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle(2)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
        
        $header = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'name' => 'Verdana'
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle("A1:J2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Rekap Data Supplier')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'ID Supplier')
            ->setCellValue('C3', 'Nama Supplier')
            ->setCellValue('D3', 'Negara')
            ->setCellValue('E3', 'Alamat')
            ->setCellValue('F3', 'No Telpon /  Fax')
            ->setCellValue('G3', 'Kontak Person')
            ->setCellValue('H3', 'Hp Kontak Person / WeChat ID')
            ->setCellValue('I3', 'Email')
            ->setCellValue('J3', 'Status');
        
        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 4;
        foreach ($rekap as $row):
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row['id_supplier']));
            $ex->setCellValue('C'.$counter, $row['nm_supplier']);
            $ex->setCellValue('D'.$counter, strtoupper($row['nm_negara']));
            $ex->setCellValue('E'.$counter, $row['alamat']);
            $ex->setCellValue('F'.$counter, $row['telpon']." / ".$row['fax']);
            $ex->setCellValue('G'.$counter, $row['cp']);
            $ex->setCellValue('H'.$counter, $row['hp_cp']." / ".$row['id_webchat']);
            $ex->setCellValue('I'.$counter, $row['email']);
            $ex->setCellValue('J'.$counter, $row['sts_aktif']);
                        
        $counter = $counter+1;
        endforeach;
        
        $objPHPExcel->getProperties()->setCreator("Yunaz Fandy")
            ->setLastModifiedBy("Yunaz Fandy")
            ->setTitle("Export Rekap Data Supplier")
            ->setSubject("Export Rekap Data Supplier")
            ->setDescription("Rekap Data Supplier for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Data Supplier');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportRekapSupplier'. date('Ymd') .'.xls"');
        
        $objWriter->save('php://output');
 
    }
}

?>
