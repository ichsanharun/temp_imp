<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportstokcolly extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Reportstokcolly.View";
    /*
    protected $addPermission    = "Reportstokcolly.Add";
    protected $managePermission = "Reportstokcolly.Manage";
    protected $deletePermission = "Reportstokcolly.Delete";
    */

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Reportstokcolly/Reportstokcolly_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Stock Colly');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $data = $this->Reportstokcolly_model
        ->join("cabang","barang_stock.kdcab = cabang.kdcab","left")
        ->find_all();
        if(!empty($this->input->get('idcabang'))){
            $data = $this->Reportstokcolly_model
            ->join("cabang","barang_stock.kdcab = cabang.kdcab","left")
            ->find_all_by(array('cabang.kdcab'=>$this->input->get('idcabang')));
        }
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->render('list');
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

    function print_stok_colly(){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $stok_data = $this->Reportstokcolly_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

        $this->template->set('stok_data', $stok_data);

        $show = $this->template->load_view('print_rekap',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function downloadExcel(){
        $data['stok_data'] = $this->Reportstokcolly_model->find_all_by(array('kdcab'=>$this->input->get('idcabang')));
        //$this->template->set('stok_data', $stok_data);
        $this->load->view('print_rekap_excel',$data);
    }


    function downloadExcels()
    {
        $stok_data = $this->Reportstokcolly_model->find_all_by( array('kdcab'=>$this->auth->user_cab(), 'deleted'=>0) );

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
        $objPHPExcel->getActiveSheet()->getStyle("A1:C2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Rekap Data Stok Colly')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'Kode Produk')
            ->setCellValue('C3', 'Nama Set');
            /*
            ->setCellValue('C3', 'Nama Set')
            ->setCellValue('D3', 'Jenis Produk')
            ->setCellValue('E3', 'Satuan')
            ->setCellValue('F3', 'Qty Stock')
            ->setCellValue('G3', 'Colly Produk')
            ->setCellValue('H3', 'Qty');
            */

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 4;
        $counterkoli = 4;
        foreach ($stok_data as $row):
            if($row->satuan==''){
                $satuan = $row->setpcs;
            }else{
                $satuan = $row->satuan;
            }
            $colly = $this->Reportstokcolly_model->get_data(array('id_barang' => $row->id_barang),'barang_koli');
            $rs = count($colly);//karena mulai dari 4 
            $merge = $rs-1;
            $brg = strtoupper($row->id_barang)."A".$counter.":A".$merge+$counter;
            /*
            if($colly){
                $ex->setCellValue('A'.$counter, $no++)->mergeCells('A'.$counter.':A'.$rs);
                $ex->setCellValue('B'.$counter, strtoupper($row->id_barang))->mergeCells('B'.$counter.':B'.$rs);
                $ex->setCellValue('C'.$counter, strtoupper($row->nm_barang))->mergeCells('C'.$counter.':C'.$rs);
                $ex->setCellValue('D'.$counter, strtoupper($row->jenis))->mergeCells('D'.$counter.':D'.$rs);
                $ex->setCellValue('E'.$counter, $satuan)->mergeCells('E'.$counter.':E'.$rs);
                $ex->setCellValue('F'.$counter, $row->qty_stock)->mergeCells('F'.$counter.':F'.$rs);
            }else{
                */
                $ex->setCellValue('A'.$counter, $no++);//->mergeCells('A'..':A'.);
                $ex->setCellValue('B'.$counter, $brg);
                $ex->setCellValue('C'.$counter, 'COLLY :'.$rs);
                /*
                $ex->setCellValue('C'.$counter, strtoupper($row->nm_barang));
                $ex->setCellValue('D'.$counter, strtoupper($row->jenis));
                $ex->setCellValue('E'.$counter, $satuan);
                $ex->setCellValue('F'.$counter, $row->qty_stock);
                */
            //}
            if($colly){
                foreach($colly as $kc=>$vc) {
                    $ex->setCellValue('D'.$counterkoli, $vc->nm_koli);
                    $ex->setCellValue('E'.$counterkoli, $vc->qty);
                }
            }else{
                $ex->setCellValue('D'.$counterkoli, '-');
                $ex->setCellValue('E'.$counterkoli, '-');
            }
            
        $counter = $counter+$rs;
        $counterkoli = $counterkoli+1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator("Yunaz Fandy")
            ->setLastModifiedBy("Yunaz Fandy")
            ->setTitle("Export Rekap Data Produk")
            ->setSubject("Export Rekap Data Produk")
            ->setDescription("Rekap Data Produk for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Data Stok Colly');
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
