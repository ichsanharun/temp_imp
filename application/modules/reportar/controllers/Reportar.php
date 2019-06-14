<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportar extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission

    protected $viewPermission   = "Reportar.View";
    protected $addPermission    = "Reportar.Add";
    protected $managePermission = "Reportar.Manage";
    protected $deletePermission = "Reportar.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Reportar/Reportar_model',
                                 'Cabang/Cabang_model',
								 'Piutang_cabang/Piutang_cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report AR');
        $this->template->page_icon('fa fa-table');
    }
	/*
	## ORIGINAL ##
    public function index()
    {

        $data = $this->Reportar_model->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->title('Report AR');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
        $this->template->render('list');
    }
	*/
	function index(){
		$session 	= $this->session->userdata('app_session');
        $cab_user	= $session['kdcab'];
		if($this->input->post()){
			$Bulan		= $this->input->post('bulan');
			$Tahun		= $this->input->post('tahun');
			$kdcab		= $this->input->post('kdcab');
		}else{
			$Bulan		= date('n');
			$Tahun		= date('Y');
			$kdcab 		= $session['kdcab'];
		}
		$data			= $this->db->get_where('ar',array('kdcab'=>$kdcab,'bln'=>$Bulan,'thn'=>$Tahun))->result();
		$cabang 		= $this->Piutang_cabang_model->get_data_Cabang();
        $this->template->title('Report AR');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
		$this->template->set('rows_cab_user', $cab_user);
		$this->template->set('cab_pilih', $kdcab);
		$this->template->set('bulan_pilih', $Bulan);
		$this->template->set('tahun_pilih', $Tahun);
        $this->template->render('list');
	}

	function excel_piutang($kdcab,$Bulan,$Tahun){
		$Judul		= "LAPORAN PIUTANG ";
		$Month		= date('F',mktime(0,0,0,$Bulan,1,date('Y')));
		$data			= $this->db->get_where('ar',array('kdcab'=>$kdcab,'bln'=>$Bulan,'thn'=>$Tahun))->result();
		$cabang 		= $this->Piutang_cabang_model->get_data_Cabang();
		$Judul		.=strtoupper($cabang[$kdcab])." ".strtoupper($Month)." ".$Tahun;
		
        $this->template->set('results', $data);
		$this->template->set('judul', $Judul);
        $this->template->render('excel_piutang');
	}

    public function filter()
      {

        $data = $this->Reportar_model
        ->where("kdcab='".$this->uri->segment(3)."' AND bln='".$this->uri->segment(4)."' AND thn='".$this->uri->segment(5)."'")
        ->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->title('Report AR');
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
      $session = $this->session->userdata('app_session');
      $kdcab = $this->input->get('idcabang');
      $bln = $this->input->get('bln');
      $thn = $this->input->get('thn');
      if (!empty($bln) && empty($thn)) {
        $thn = date("Y");
      }
      if(!empty($kdcab)){
        if (!empty($bln)) {
          $data = $this->Reportar_model
          ->where("kdcab='".$kdcab."' AND bln='".$bln."' AND thn='".$thn."'")
          ->order_by('no_invoice','DESC')->find_all();
        }else {
          $data = $this->Reportar_model->where("kdcab='".$this->input->get('idcabang')."'")->order_by('no_invoice','DESC')->find_all();
        }
      }else{
        $data = $this->Reportar_model->order_by('no_invoice','DESC')->find_all();
      }





      $data = array(
        'title2'		=> 'Report',
        'results'	=> $data
      );
      /*$this->template->set('results', $data_so);
      $this->template->set('head', $sts);
      $this->template->title('Report SO');*/
      $this->load->view('view_report',$data);


    }

     function downloadExcel_old()
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
            $ex->setCellValue('D'.$counter, "a");
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
}
?>
