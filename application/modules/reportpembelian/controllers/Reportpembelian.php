<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportpembelian extends Admin_Controller {

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
                                 'Purchaseorder/Purchaseorder_model',
                                 'Purchaseorder/Detailpurchaseorder_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Pembelian');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $data = $this->Purchaseorder_model->order_by('no_po','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->title('Report Pembelian');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
        $this->template->render('list');
    }

    public function filter()
      {

        $data = $this->Purchaseorder_model
        ->where("tgl_po BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$this->uri->segment(5)."'")
        ->order_by('no_po','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->title('Report Pembelian');
        $this->template->render('list');
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
        if(!empty($this->input->get('tglawal')) && !empty($this->input->get('tglakhir')) && !empty($this->input->get('idcabang'))){
            $data = $this->Purchaseorder_model
        ->where("tgl_po BETWEEN '".$this->input->get('tglawal')."' AND '".$this->input->get('tglakhir')."' AND kdcab='".$this->input->get('idcabang')."'")
        ->order_by('no_po','DESC')->find_all();
        }else{
            $data = $this->Purchaseorder_model->order_by('no_po','DESC')->find_all();
        }

        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);

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
        $objPHPExcel->getActiveSheet()->getStyle("A1:F1")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');

        $objPHPExcel->getActiveSheet()->getStyle("A2:F2")
                ->applyFromArray($header)
                ->getFont()->setSize(12);
        $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'LAPORAN PEMBELIAN')
            ->setCellValue('A2', 'PERIODE : '.$this->input->get('tglawal').' s/d '.$this->input->get('tglakhir'))
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NO. PO')
            ->setCellValue('C3', 'Tgl PO')
            ->setCellValue('D3', 'Supplier')
            ->setCellValue('E3', 'Cabang')
            ->setCellValue('F3', 'Total PO');

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $n = 1;
        $counter = 4;
        foreach ($data as $row):
            $no = $n++;
        	$detail = $this->Detailpurchaseorder_model->where(array('no_po'=>$row->no_po))->find_all();
            $ex->setCellValue('A'.$counter, $no);
            $ex->setCellValue('B'.$counter, strtoupper($row->no_po));
            $ex->setCellValue('C'.$counter, strtoupper($row->tgl_po));
            $ex->setCellValue('D'.$counter, strtoupper($row->nm_supplier));
            $ex->setCellValue('E'.$counter, $row->nm_cabang);
            $ex->setCellValue('F'.$counter, $row->total_po);
            $nd = 1;
            foreach($detail as $kd=>$vd){
            	$ndd = $nd++;
            	$counter = $counter+1;
            	$ex->setCellValue('B'.$counter, $no.'-'.$ndd.'. '.$vd->id_barang.', '.$vd->nm_barang)->mergeCells('B'.$counter.':C'.$counter);
            	$ex->setCellValue('D'.$counter, $vd->qty_po.' '.$vd->satuan.', @Rp. '.$vd->harga_satuan);
            	$ex->setCellValue('E'.$counter, $vd->sub_total_po);
            }
            $counter = $counter+count($detail)+1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator("Importa")
            ->setLastModifiedBy("Importa")
            ->setTitle("Export Laporan Pembelian")
            ->setSubject("Export Laporan Pembelian")
            ->setDescription("Laporan Pembelian for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Pembelian');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="LaporanPembelian'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }
}
?>
