<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportpembayaran extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission

    protected $viewPermission   = "Reportpembayaran.View";
    protected $addPermission    = "Reportpembayaran.Add";
    protected $managePermission = "Reportpembayaran.Manage";
    protected $deletePermission = "Reportpembayaran.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Reportpembayaran/Reportpembayaran_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Pembayaran');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {

        $data = array();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $data = $this->Reportpembayaran_model
        ->join("trans_invoice_header","trans_invoice_header.no_invoice=pembayaran_piutang.no_invoice","left")
        ->order_by('kd_pembayaran','ASC')->find_all_by(array('kd_pembayaran IS NOT NULL','pembayaran_piutang.kdcab'=>$this->auth->user_cab(),'trans_invoice_header.kdcab'=>$this->auth->user_cab()));
        $this->template->title('Report Pembayaran Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
        $this->template->render('list');
    }

    public function get_filter($id)
    {

        $data = array();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $data = $this->Reportpembayaran_model
        ->join("trans_invoice_header","trans_invoice_header.no_invoice=pembayaran_piutang.no_invoice","left")
        ->order_by('kd_pembayaran','ASC')->find_all_by(array('kd_pembayaran IS NOT NULL','pembayaran_piutang.kdcab'=>$this->auth->user_cab(),'trans_invoice_header.kdcab'=>$this->auth->user_cab(),'trans_invoice_header.id_customer'=>$id));
        $this->template->title('Report Pembayaran Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
        $this->template->render('list');
    }

    public function filter()
      {
        if ($this->uri->segment(4) == "All") {
          $per = $this->uri->segment(5)."-";
        }else {
          $per = $this->uri->segment(5)."-".$this->uri->segment(4);
        }
        $data = $this->Reportpembayaran_model
        ->join("trans_invoice_header","trans_invoice_header.no_invoice=pembayaran_piutang.no_invoice","left")
        ->order_by('kd_pembayaran','ASC')->find_all_by(array('kd_pembayaran IS NOT NULL','pembayaran_piutang.kdcab'=>$this->auth->user_cab(),'tgl_pembayaran LIKE'=> "%".$per."%"));

        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->title('Report Pembayaran Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
        $this->template->render('list');
      }

    function getfilterby(){
        $filter = $this->input->post('FILTER');
        echo $filter;
    }
    function print_request(){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $data_inv = $this->Invoice_model->where(array('piutang >'=>0,'no_invoice'=>$this->uri->segment(3)))->order_by('no_invoice','DESC')->find_all();

        $this->template->set('header',$data_inv);

        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

     function downloadExcel()
    {
        if(!empty($this->input->get('idcabang'))){
            $data = $this->Reportar_model->where("kdcab='".$this->input->get('idcabang')."'")->order_by('no_invoice','DESC')->find_all();
        }else{
            $data = $this->Reportar_model->order_by('no_invoice','DESC')->find_all();
        }

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
        $objPHPExcel->getActiveSheet()->getStyle("A1:I2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan AR')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NO. Invoice')
            ->setCellValue('C3', 'Customer')
            ->setCellValue('D3', 'Bulan')
            ->setCellValue('E3', 'Tahun')
            ->setCellValue('F3', 'Saldo Awal')
            ->setCellValue('G3', 'Debet')
            ->setCellValue('H3', 'Kredit')
            ->setCellValue('I3', 'Saldo Akhir');

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $n = 1;
        $counter = 4;
        foreach ($data as $row):
            $no = $n++;

            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row->no_invoice));
            $ex->setCellValue('C'.$counter, strtoupper($row->customer_code.', '.$row->customer));
            $ex->setCellValue('D'.$counter, the_bulan($row->bln));
            $ex->setCellValue('E'.$counter, $row->thn);
            $ex->setCellValue('F'.$counter, $row->saldo_awal);
            $ex->setCellValue('G'.$counter, $row->debet);
            $ex->setCellValue('H'.$counter, $row->kredit);
            $ex->setCellValue('I'.$counter, $row->saldo_akhir);
        $counter = $counter+1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator("Importa")
            ->setLastModifiedBy("Importa")
            ->setTitle("Export Laporan AR")
            ->setSubject("Export Laporan AR")
            ->setDescription("Laporan AR for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Laporan AR');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportLaporanAR'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }

    function downloadExcel_old()
    {
      $session = $this->session->userdata('app_session');
      $kdcab = $session['kdcab'];
      $filter = $this->input->get('filter');
      $param = $this->input->get('param');
      $where ='';
      if ($this->uri->segment(4) == "All") {
        $per = $this->uri->segment(5)."-";
      }else {
        $per = $this->uri->segment(5)."-".$this->uri->segment(4);
      }
      if(!empty($this->uri->segment(5))){
        $data = $this->Reportpembayaran_model
        ->join("trans_invoice_header","trans_invoice_header.no_invoice=pembayaran_piutang.no_invoice","left")
        ->order_by('kd_pembayaran','ASC')->find_all_by(array('kd_pembayaran IS NOT NULL','pembayaran_piutang.kdcab'=>$this->auth->user_cab(),'tgl_pembayaran LIKE'=> "%".$per."%"));
      }else {
        $data = $this->Reportpembayaran_model
        ->join("trans_invoice_header","trans_invoice_header.no_invoice=pembayaran_piutang.no_invoice","left")
        ->order_by('kd_pembayaran','ASC')->find_all_by(array('kd_pembayaran IS NOT NULL','pembayaran_piutang.kdcab'=>$this->auth->user_cab()));
      }



      $data = array(
  			'title2'		     => 'Report',
  			'results'	       => $data
  		);
      /*$this->template->set('results', $data_so);
      $this->template->set('head', $sts);
      $this->template->title('Report SO');*/
      $this->load->view('view_report',$data);


    }
}
?>
