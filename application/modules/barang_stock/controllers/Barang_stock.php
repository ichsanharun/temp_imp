<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Barang_stock
 */

class Barang_stock extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Barang_stock.View";
    protected $addPermission    = "Barang_stock.Add";
    protected $managePermission = "Barang_stock.Manage";
    protected $deletePermission = "Barang_stock.Delete";

    public function __construct(){
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Barang_stock/Barang_stock_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Barang Stock');
        $this->template->page_icon('fa fa-table');
    }

    public function index(){
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Barang_stock_model->find_all_by(array('kdcab'=>$session['kdcab'], 'deleted'=>0) );
        $this->template->set('results', $data);
        $this->template->render('list');
    }

   	//Create New barang
   	public function create(){

        $this->auth->restrict($this->addPermission);

        $barang = $this->Barang_model->pilih_barang()->result();

        $this->template->set('barang',$barang);
        $this->template->title('Stock');
		    $this->template->render('setup_stock_form');
   	}

   	//Edit barang
   	public function edit(){
  		  $this->auth->restrict($this->managePermission);
        $id = $this->input->post('ID');
        $barang_data = $this->Barang_stock_model->find_by(array('id_barang'=>$id,'kdcab'=>$this->auth->user_cab()));

        $this->template->set('data', $barang_data);
        $this->template->title('Edit');
        $this->template->render('edit');
   	}

    function savebarang_diskon($id){
        $session = $this->session->userdata('app_session');
        //$id = $this->input->post('id_brg');
         //if($this->input->post('poin_per_item') != ""){
           $databarang = array(

               'poin_per_item'         => $this->input->post('poin_per_item'),
               'diskon_promo_rp'       => $this->input->post('diskon_promo_rp'),
               'diskon_jika_qty'       => $this->input->post('diskon_jika_qty'),
               'diskon_promo_persen'   => $this->input->post('diskon_promo_persen'),
               'diskon_qty_gratis'     => $this->input->post('diskon_qty_gratis'),
               'diskon_standar_persen' => $this->input->post('diskon_persen'),
               'sts_aktif'             => $this->input->post('sts_aktif'),
               );
         //}else {

         //}

        $this->db->trans_begin();
        $this->db->where(array('id_barang'=>$id, 'kdcab'=>$session['kdcab']));
        $this->db->update('barang_stock',$databarang);


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, simpan diskon barang..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'msg' => "SUKSES, simpan data..!!!"
            //'msg' => $poin
            );
        }
        echo json_encode($param);
    }
    //Save using ajax
    public function save_data_ajax(){

        $id_barang      = $this->input->post("id_barang");
        $type           = $this->input->post("type");
        $jenis          = $this->input->post("jenis");
        $nm_barang      = strtoupper($this->input->post("nm_barang"));
        $brand          = strtoupper($this->input->post("brand"));
        $kategori       = $this->input->post("kategori");
        $satuan         = $this->input->post("satuan");
        $sts_aktif      = $this->input->post("sts_aktif");
        $kdcab          = $this->auth->user_cab();

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
                        'kdcab'=>$kdcab,
                        'nm_barang'=>$nm_barang,
                        'brand'=>$brand,
                        'jenis'=>$jenis,
                        'kategori'=>$kategori,
                        'satuan'=>$satuan,
                        'sts_aktif'=>$sts_aktif,
                        );

            //Add Data
            //$db_debug = $this->db->db_debug;
            $this->db->trans_begin();
            $this->db->db_debug = TRUE;
            $this->db->insert('barang_stock',$data);



            if ($this->db->trans_status === FALSE)
            {
                    $this->db->trans_rollback();
                    $keterangan     = "GAGAL, tambah data Barang Stok Barang ".$id_barang.", atas Nama : ".$nm_barang.", BARANG SUDAH ADA ";
                    $status         = 0;
                    $nm_hak_akses   = $this->addPermission;
                    $kode_universal = 'NewData';
                    $jumlah         = 1;
                    $sql            = $this->db->last_query();
                    $result = 0;
            }
            else
            {
                    $this->db->trans_commit();
                    $keterangan     = "SUKSES, tambah Barang Stok Barang ".$id_barang.", atas Nama : ".$nm_barang;
                    $status         = 1;
                    $nm_hak_akses   = $this->addPermission;
                    $kode_universal = 'NewData';
                    $jumlah         = 1;
                    $sql            = $this->db->last_query();

                    $result         = 1;
                    $barang       = $id_barang;
            }
            /*if(is_numeric($id))
            {
                $keterangan     = "SUKSES, tambah Barang Stok Barang ".$id_barang.", atas Nama : ".$nm_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $result         = 1;
                $barang       = $id_barang;
            }
            else
            {
                $keterangan     = "GAGAL, tambah data Barang Stok Barang ".$id_barang.", atas Nama : ".$nm_barang;
                $status         = 0;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();
                $result = 0;
            }*/
            //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }

        $param = array(
                'barang'=> $barang,
                'series'=> $series,
                'save' => $result,
                'msg' => $keterangan
                );

        echo json_encode($param);
    }

    function hapus_barang(){
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);
        $wheres = array(
            'id_barang'    => $id,
            'kdcab' => $this->auth->user_cab()
        );

        if($id!=''){

            $result = $this->Barang_stock_model->delete_where($wheres);

            $keterangan     = "SUKSES, Delete data Barang Stok Barang ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Barang stok Barang ".$id;
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
            $detail  = $this->Barang_model->find_by(array('id_barang'=>$id_barang));
        }
        echo json_encode($detail);
    }

    function print_request($id){
        $id_barang = $id;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $brg_data      =  $this->Barang_stock_model->find_by( array('kdcab'=>$this->auth->user_cab(), 'id_barang'=>$id_barang) );

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

        $stok_data = $this->Barang_stock_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

        $this->template->set('stok_data', $stok_data);

        $show = $this->template->load_view('print_rekap',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function downloadExcel(){
        $stok_data = $this->Barang_stock_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(17);

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
        $objPHPExcel->getActiveSheet()->getStyle("A1:N2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:N2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Rekap Data Stok')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'Kode Produk')
            ->setCellValue('C3', 'Nama Set')
            ->setCellValue('D3', 'Jenis Produk')
            ->setCellValue('E3', 'Satuan')
            ->setCellValue('F3', 'Qty Stock')
            ->setCellValue('G3', 'Qty Available')
            ->setCellValue('H3', 'Qty Rusak')
            ->setCellValue('I3', 'Landed Cost')
            ->setCellValue('J3', 'Harga')
            ->setCellValue('K3', 'Status');

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 4;
        foreach ($stok_data as $row):
            if($row->satuan==''){
                $satuan = $row->setpcs;
            }else{
                $satuan = $row->satuan;
            }

            if($row->sts_aktif == 'aktif'){
                $status = "Aktif";
            }else{
                $status = "Aktif";
            }

            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row->id_barang));
            $ex->setCellValue('C'.$counter, strtoupper($row->nm_barang));
            $ex->setCellValue('D'.$counter, strtoupper($row->jenis));
            $ex->setCellValue('E'.$counter, $satuan);
            $ex->setCellValue('F'.$counter, $row->qty_stock);
            $ex->setCellValue('G'.$counter, $row->qty_avl);
            $ex->setCellValue('H'.$counter, $row->qty_rusak);
            $ex->setCellValue('I'.$counter, number_format($row->landed_cost));
            $ex->setCellValue('J'.$counter, number_format($row->harga));
            $ex->setCellValue('K'.$counter, $status);

        $counter = $counter+1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator("Yunaz Fandy")
            ->setLastModifiedBy("Yunaz Fandy")
            ->setTitle("Export Rekap Data Produk")
            ->setSubject("Export Rekap Data Produk")
            ->setDescription("Rekap Data Produk for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Data Stok');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportRekapStok'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }
}
?>
