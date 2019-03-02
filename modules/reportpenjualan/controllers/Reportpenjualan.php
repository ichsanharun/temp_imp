<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportpenjualan extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    /*
    protected $viewPermission   = "Reportstok.View";
    protected $addPermission    = "Reportstok.Add";
    protected $managePermission = "Reportstok.Manage";
    protected $deletePermission = "Reportstok.Delete";
    */
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Penjualan');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        /*
        $this->auth->restrict($this->viewPermission);
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $data = $this->Reportstok_model
        ->join("cabang","barang_stock.kdcab = cabang.kdcab","left")
        ->find_all();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        */
        $data = $this->Invoice_model->order_by('no_invoice','DESC')->find_all();
        $this->template->title('Report Penjualan');
        $this->template->set('results', $data);
        $this->template->render('list');
    }

    public function filter()
      {
        $data = $this->Invoice_model
        ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."'")
        ->order_by('no_invoice','DESC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Report Penjualan');
        $this->template->render('list');
      }

    function getfilterby(){
        $filter = $this->input->post('FILTER');
        echo $filter;
    }
    function print_rekap(){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $stok_data = $this->Reportstok_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

        $this->template->set('stok_data', $stok_data);

        $show = $this->template->load_view('print_rekap',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

     function downloadExcel()
    {
        $stok_data = $this->Reportstok_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

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
