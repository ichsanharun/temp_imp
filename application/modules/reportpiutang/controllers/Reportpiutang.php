<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportpiutang extends Admin_Controller {

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
                                 'Customer/Customer_model',
                                 'Salesorder/Salesorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Piutang');
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
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $data = $this->Invoice_model->where(array('piutang >'=>0))->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $customer = $this->Customer_model->find_all_by(array('deleted'=>0));
        $marketing = $this->Salesorder_model->pilih_marketing($kdcab)->result();

        $this->template->title('Report Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('customer', $customer);
        $this->template->set('marketing', $marketing);
        $this->template->set('results', $data);
        $this->template->render('list');
    }

    public function filter()
      {
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
         $filter = array(
            'piutang >'=>0,
            'kdcab'=>$this->uri->segment(3),
            'id_salesman'=>$this->uri->segment(4)
            );
        if($this->uri->segment(5) != ''){
            $filter['id_customer'] = $this->uri->segment(5);
        }
        $data = $this->Invoice_model
        ->where($filter)
        ->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $customer = $this->Customer_model->find_all_by(array('deleted'=>0));
        $marketing = $this->Salesorder_model->pilih_marketing($kdcab)->result();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->set('customer', $customer);
        $this->template->set('marketing', $marketing);
        $this->template->title('Report Piutang');
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

        $filter = array(
            'piutang >'=>0,
            'kdcab'=>$this->uri->segment(3),
            'id_salesman'=>$this->uri->segment(4)
            //'id_customer'=>$this->uri->segment(5)
            );
        if($this->uri->segment(5) != ''){
            $filter['id_customer'] = $this->uri->segment(5);
        }
        $data_inv = $this->Invoice_model->where($filter)->order_by('no_invoice','DESC')->find_all();

        $this->template->set('header',$data_inv);

        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

     function downloadExcel()
    {
        if(!empty($this->input->get('tglawal')) && !empty($this->input->get('tglakhir')) && !empty($this->input->get('idcabang'))){
            $data = $this->Invoice_model
        ->where("tanggal_invoice BETWEEN '".$this->input->get('tglawal')."' AND '".$this->input->get('tglakhir')."' AND kdcab='".$this->input->get('idcabang')."'")
        ->order_by('no_invoice','DESC')->find_all();
        }else{
            $data = $this->Invoice_model->order_by('no_invoice','DESC')->find_all();
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:J2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Penjualan')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NO. Invoice')
            ->setCellValue('C3', 'Customer')
            ->setCellValue('D3', 'Salesman')
            ->setCellValue('E3', 'Tgl. Invoice')
            ->setCellValue('F3', 'HPP')
            ->setCellValue('G3', 'Omset')
            ->setCellValue('H3', 'Laba Kotor')
            ->setCellValue('I3', 'Margin (%)');

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 4;
        foreach ($data as $row):
            $no = $n++;
            $hpp = $row->hargalandedtotal;
            $omset = $row->hargajualtotal;
            $laba = $omset-$hpp;
            $margin = $laba/$omset*100;

            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row->no_invoice));
            $ex->setCellValue('C'.$counter, strtoupper($row->nm_customer));
            $ex->setCellValue('D'.$counter, strtoupper($row->nm_salesman));
            $ex->setCellValue('E'.$counter, $row->tanggal_invoice);
            $ex->setCellValue('F'.$counter, $hpp);
            $ex->setCellValue('G'.$counter, $omset);
            $ex->setCellValue('H'.$counter, $laba);
            $ex->setCellValue('I'.$counter, $margin);
        $counter = $counter+1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator("Importa")
            ->setLastModifiedBy("Importa")
            ->setTitle("Export Laporan Penjualan")
            ->setSubject("Export Laporan Penjualan")
            ->setDescription("Laporan Penjualan for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Penjualan');
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
