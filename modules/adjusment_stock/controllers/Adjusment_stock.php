<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Adjusment_stock
 */

class Adjusment_stock extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Adjusment_stock.View";
    protected $addPermission    = "Adjusment_stock.Add";
    protected $managePermission = "Adjusment_stock.Manage";
    protected $deletePermission = "Adjusment_stock.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Trans_stock/Trans_stock_model',
                                 'Adjusment_stock/Adjusment_stock_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Adjusment Stock');
        $this->template->page_icon('fa fa-calculator');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $data = $this->Adjusment_stock_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );
        $this->template->set('results', $data);
        $this->template->render('list');
    }

   	//Create New
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $barang = $this->Adjusment_stock_model->pilih_barang($this->auth->user_cab())->result();

        $this->template->set('barang',$barang);
		$this->template->render('adjus_stock_form');
   	}

   	//Edit
   	public function edit()
   	{

  		$this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);
        $barang = $this->Adjusment_stock_model->pilih_barang($this->auth->user_cab())->result();

        $this->template->set('barang',$barang);
        $this->template->render('adjus_stock_form');
        $this->template->set('data', $this->Adjusment_stock_model->find($id));
   	}

    //Save using ajax
    public function save_data_ajax(){
        //print_r($this->input->post());die();
        //Adjusment Stok
        $id_barang      = $this->input->post("id_barang");
        $nm_barang      = $this->input->post("nm_barang");
        $kategori       = $this->input->post("kategori");
        $jenis          = $this->input->post("jenis");
        $brand          = $this->input->post("brand");
        $satuan         = $this->input->post("satuan");
        $type           = $this->input->post("type");
        $qty            = $this->input->post("qty");
        $qty_stock      = $this->input->post("qty_stock");
        $qty_avl        = $this->input->post("qty_avl");
        $noreff         = $this->input->post("noreff");
        $nilai_barang   = $this->input->post("nilai_barang");
        $notes          = $this->input->post("notes");
        $tipe_adjusment = $this->input->post("tipe_adjusment");
        $date           = date('Y-m-d');

        if($id_adjusment==''){
            $id_adjusment   = $this->Adjusment_stock_model->get_kode_adj($this->auth->user_cab());
        }else{
            $id_adjusment   = $id_adjusment;
        }

        //Trans_stock
        $id_st = $this->Trans_stock_model->gen_st($this->auth->user_cab());
        if($tipe_adjusment=='IN'){
            $tipe           = 'IN';
            $jenis_trans    = 'IN_Adjusment';
            $qty_stock_new  = $qty_stock + $qty;
            $qty_avl_new    = $qty_avl + $qty;
        }else{
            $tipe           = 'OUT';
            $jenis_trans    = 'OUT_Adjusment';
            $qty_stock_new  = $qty_stock - $qty;
            $qty_avl_new    = $qty_avl - $qty;
        }

        if($type=="edit")
        {
            $this->auth->restrict($this->managePermission);

            if($id_adjusment!="")
            {
                $data = array(
                            array(
                                'id_adjusment'=>$id_adjusment,
                                'tipe_adjusment'=>$tipe_adjusment,
                                'date'=>$date,
                                'id_barang'=>$id_barang,
                                'nm_barang'=>$nm_barang,
                                'jenis'=>$jenis,
                                'kategori'=>$kategori,
                                'brand'=>$brand,
                                'satuan'=>$satuan,
                                'kdcab'=>$this->auth->user_cab(),
                                'qty'=>$qty,
                                'nilai_barang'=>$nilai_barang,
                                'noreff'=>$noreff,
                                'notes'=>$notes,
                            )
                        );

                //Update data
                $result = $this->Adjusment_stock_model->update_batch($data,'id_adjusment');

                $keterangan     = "SUKSES, Edit data Adjusment Barang ".$id_adjusment.", atas barang : ".$id_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_adjusment;
                $jumlah         = 1;
                $sql            = $this->db->last_query();

                $barang       = $id_barang;
            }
            else
            {
                $result = FALSE;

                $keterangan     = "GAGAL, Edit data Adjusment Barang ".$id_adjusment.", atas barang : ".$id_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = $id_adjusment;
                $jumlah         = 1;
                $sql            = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        }
        else //Add New
        {
            $this->auth->restrict($this->addPermission);
            $data_adj = array(
                        'id_adjusment'=>$id_adjusment,
                        'tipe_adjusment'=>$tipe_adjusment,
                        'date'=>$date,
                        'id_barang'=>$id_barang,
                        'nm_barang'=>$nm_barang,
                        'jenis'=>$jenis,
                        'kategori'=>$kategori,
                        'brand'=>$brand,
                        'satuan'=>$satuan,
                        'kdcab'=>$this->auth->user_cab(),
                        'qty'=>$qty,
                        'nilai_barang'=>$nilai_barang,
                        'noreff'=>$noreff,
                        'notes'=>$notes,
                        );

            $data_adj_trans = array(
                        'id_st'=>$id_st,
                        'tipe'=>$tipe,
                        'jenis_trans'=>$jenis_trans,
                        'noreff'=>$id_adjusment,
                        'id_barang'=>$id_barang,
                        'nm_barang'=>$nm_barang,
                        'jenis'=>$jenis,
                        'kategori'=>$kategori,
                        'brand'=>$brand,
                        'satuan'=>$satuan,
                        'kdcab'=>$this->auth->user_cab(),
                        'date_stock'=>date('Y-m-d H:i:s'),
                        'qty'=>$qty,
                        'nilai_barang'=>$nilai_barang,
                        'notes'=>$notes,
                        );
            //print_r($data_adj);print_r($data_adj_trans);die();
            //Add Data
            $this->db->trans_begin();
            $this->auth->restrict($this->viewPermission);
            if ($qty_stock == '') {
              $data_stock = array(
                'qty_stock'=>$qty_stock,
                'qty_avl'=>$qty_avl,
                'harga'=>$nilai_barang,
                'modified_on'=>date("Y-m-d H:i:s"),
                'modified_by'=>$session['id_user']
              );
              $this->db->where(array('id_barang'=>$id_barang,'kdcab'=>$this->auth->user_cab()));
              $this->db->update('barang_stock',$data_stock);
            }
            //$data = $this->Adjusment_stock_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );
            //$this->db->where(array('id_barang'=>$id_barang,'kdcab'=>$this->auth->user_cab()));
            //$this->db->update('barang_stock',array('qty_avl'=>$qty_avl_new,'qty_stock'=>$qty_stock_new));

            $this->Adjusment_stock_model->insert($data_adj);
            $this->Trans_stock_model->insert($data_adj_trans);
            if($this->db->trans_status()===FALSE){
                $keterangan     = "GAGAL, tambah data Adjusment Barang ".$id_adjusment.", atas barang : ".$id_barang;
                $status         = 0;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();
                $result         = FALSE;
                $this->db->trans_rollback();
            }else{
                $keterangan     = "SUKSES, tambah data Adjusment Barang ".$id_adjusment.", atas barang : ".$id_barang;
                $status         = 1;
                $nm_hak_akses   = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah         = 1;
                $sql            = $this->db->last_query();
                $result         = TRUE;
                $this->db->trans_commit();
            }

            //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        }

        $param = array(
                'save' => $result
                );

        echo json_encode($param);
    }

    function hapus_barang()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);
        $wheres = array(
            'id_adjusment' =>$id,
            'kdcab' => $this->auth->user_cab()
        );

        if($id!=''){

            $result = $this->Adjusment_stock_model->delete_where($wheres);

            $keterangan     = "SUKSES, Delete data Adjusment Stock ".$id;
            $status         = 1;
            $nm_hak_akses   = $this->addPermission;
            $kode_universal = $id;
            $jumlah         = 1;
            $sql            = $this->db->last_query();

        }
        else
        {
            $result = 0;
            $keterangan     = "GAGAL, Delete data Adjusment Stock ".$id;
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
            $detail  = $this->Barang_model
            ->join("barang_stock", "barang_stock.id_barang = barang_master.id_barang", "left")
            ->find($id_barang);
        }
        echo json_encode($detail);
    }

    function print_request($id){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $brg_data      =  $this->Adjusment_stock_model->find_by( array('kdcab'=>$this->auth->user_cab(), 'id_adjusment'=>$id) );

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

        $data_adj = $this->Adjusment_stock_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

        $this->template->set('data_adj', $data_adj);

        $show = $this->template->load_view('print_rekap',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

     function downloadExcel()
    {
        $data_adj = $this->Adjusment_stock_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

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
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
       //// $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(17);

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
            ->setCellValue('A1', 'Rekap Data Produk')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'ID Produk')
            ->setCellValue('C3', 'Jenis Produk')
            ->setCellValue('D3', 'Group Produk')
            ->setCellValue('E3', 'Nama Set')
            ->setCellValue('F3', 'Satuan')
            ->setCellValue('G3', 'ID Colly')
            ->setCellValue('H3', 'Nama Colly')
            ->setCellValue('I3', 'Qty')
            ->setCellValue('J3', 'Satuan')
            ->setCellValue('K3', 'ID Komponen')
            ->setCellValue('L3', 'Nama Komponen')
            ->setCellValue('M3', 'Qty')
            ->setCellValue('N3', 'Satuan');

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 4;
        foreach ($brg_data as $row):
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row['id_barang']));
            $ex->setCellValue('C'.$counter, strtoupper($row['nm_jenis']));
            $ex->setCellValue('D'.$counter, strtoupper($row['nm_group']));
            $ex->setCellValue('E'.$counter, $row['nm_barang']);
            $ex->setCellValue('F'.$counter, $row['satuan']);
            foreach($kol_data as $key => $y) {
                //$counter
                if($row['id_barang'] == $y['id_barang']){
                    $ex->setCellValue('G'.$counter, strtoupper($y['id_koli']));
                    $ex->setCellValue('H'.$counter, $y['nm_koli']);
                    $ex->setCellValue('I'.$counter, $y['qty']);
                    $ex->setCellValue('J'.$counter, $y['satuan']);
                    foreach($kom_data as $key => $xy) {
                        if($y['id_koli'] == $xy['id_koli']  && $row['id_barang'] == $y['id_barang']){
                            $ex->setCellValue('K'.$counter, strtoupper($xy['id_komponen']));
                            $ex->setCellValue('L'.$counter, strtoupper($xy['nm_komponen']));
                            $ex->setCellValue('M'.$counter, $xy['qty']);
                            $ex->setCellValue('N'.$counter, $xy['satuan']);
                            $counter = $counter+1;
                        }else{
                            $counter = $counter;
                        }
                    }
                    $counter = $counter+1;
                }else{
                    $ex->setCellValue('G'.$counter, '');
                    $ex->setCellValue('H'.$counter, '');
                    $ex->setCellValue('I'.$counter, '');
                    $ex->setCellValue('J'.$counter, '');
                    $ex->setCellValue('K'.$counter, '');
                    $ex->setCellValue('L'.$counter, '');
                    $ex->setCellValue('M'.$counter, '');
                    $ex->setCellValue('N'.$counter, '');
                    $counter = $counter;
                }

            }
        $counter = $counter+1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator("Yunaz Fandy")
            ->setLastModifiedBy("Yunaz Fandy")
            ->setTitle("Export Rekap Data Produk")
            ->setSubject("Export Rekap Data Produk")
            ->setDescription("Rekap Data Produk for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Data Produk');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportRekapProduk'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }
}
?>
