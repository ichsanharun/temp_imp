<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Pickinglistdop
 */

class Pickinglistdop extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Pickinglistdop.View";
    protected $addPermission    = "Pickinglistdop.Add";
    protected $managePermission = "Pickinglistdop.Manage";
    protected $deletePermission = "Pickinglistdop.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array( 'Salesorder/Salesorder_model',
                                  'Salesorder/Detailsalesorder_model',
                                  'Pendingso/Pendingso_model',
                                  'Pendingso/Detailpendingso_model',
                                  'Aktifitas/aktifitas_model'
                                ));

        $this->template->title('Picking List DO Pending');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $type = array(
                      ['kdtype' => 'OPN', 'nmtype' => 'OPEN'],
                      ['kdtype' => 'CLS', 'nmtype' => 'CLOSE']
                    );

        $data = $this->Salesorder_model->order_by('no_so','DESC')->find_all();
        if($this->uri->segment(3) == ""){

            $data = $this->Salesorder_model->order_by('no_so','DESC')->find_all();
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->order_by('trans_so_header.no_so','ASC')
            ->group_by('trans_so_header.no_so')
            ->find_all_by('qty_booked > qty_supply');
        }else{
            $data = $this->Salesorder_model->get_data("stsorder ='".$this->uri->segment(3)."' ","trans_so_header");
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->order_by('trans_so_header.no_so','ASC')->get_data("stsorder ='".$this->uri->segment(3)."' ","trans_so_header");
        }
        $this->template->set('results', $data);
        $this->template->set('type', $type);
        $this->template->set('detail', $detail);
        $this->template->title('Picking List DO Pending');
        $this->template->render('list');
    }

    public function filter()
    {
        //$this->auth->restrict($this->viewPermission);
        $type = array(
                      ['kdtype' => 'OPN', 'nmtype' => 'OPEN'],
                      ['kdtype' => 'CLS', 'nmtype' => 'CLOSE']
                    );

        $data = $this->Salesorder_model->order_by('no_so','DESC')->find_all();
        if($this->uri->segment(3) == ""){

            $data = $this->Salesorder_model->order_by('no_so','DESC')->find_all();
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->order_by('trans_so_header.no_so','ASC')->find_all();
            //$data = $this->Purchaserequest_model->order_by('no_pr','ASC')->find_all_by(array('proses_po'=>0));
        }else{
            $data = $this->Salesorder_model->get_data("stsorder ='".$this->uri->segment(3)."' ","trans_so_header");
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->order_by('trans_so_header.no_so','ASC')->get_data("stsorder ='".$this->uri->segment(3)."' ","trans_so_header");
        }
        $this->template->set('results', $data);
        $this->template->set('type', $type);
        $this->template->set('detail', $detail);
        $this->template->title('Picking List DO Pending');
        $this->template->render('list');
    }

    function print_request($noso){
        $no_so = $noso;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so));

        $this->template->set('so_data', $so_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function downloadExcel()
    {
      $data_so = $this->Salesorder_model
      ->where(array(
          'stsorder' => $this->uri->segment(3)
          ))
      ->get_data("tanggal like '%".$this->uri->segment(4)."%'","trans_so_header");
      if ($this->uri->segment(3) == "CLS") {
        $sts = " CLOSE PERIODE ".date("M Y", strtotime($this->uri->segment(4)));
      }
      else {
        $sts = " OPEN PERIODE ".date("M Y", strtotime($this->uri->segment(4)));
      }
        //$data_kar = $this->Salesorder_model->rekap_data()->result_array();
        /*$data_kar = $this->Salesorder_model->join('trans_so_detail','trans_so_header.no_so = trans_so_detail.no_so', 'left')
        ->where(array(
            'trans_so_header.stsorder' => $where
            ))
        ->result_array();*/
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(27);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        /*$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(17);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(17);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
       // $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(17);
       */
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
            'color' => array('rgb' => '25500'),
            'name' => 'Verdana'
            )
        );
        $det = array(
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font' => array(
            'bold' => true
            )
        );
        $objPHPExcel->getActiveSheet()->getStyle("A1:H2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:H2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'REPORT DATA SALES ORDER STATUS'.$sts);


        //$objPHPExcel->getActiveSheet()->mergeCells('H4:I4');
        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 3;
        foreach ($data_so as $row):
          $objPHPExcel->getActiveSheet()->getStyle($counter)->getFont()->setBold(true);
          $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$counter, 'No.')
              ->setCellValue('B'.$counter, 'NO. SO')
              ->setCellValue('C'.$counter, 'NAMA CUSTOMER')
              ->setCellValue('D'.$counter, 'TANGGAL SO')
              ->setCellValue('E'.$counter, 'NAMA SALESMAN')
              ->setCellValue('F'.$counter, 'DPP')
              ->setCellValue('G'.$counter, 'PPN')
              ->setCellValue('H'.$counter, 'TOTAL');
              $counter = $counter+1;
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, $row->no_so);
            $ex->setCellValue('C'.$counter, $row->nm_customer);
            $ex->setCellValue('D'.$counter, date('d-m-Y', strtotime($row->tanggal)));
            $ex->setCellValue('E'.$counter, $row->nm_salesman);
            $ex->setCellValue('F'.$counter, $row->dpp);
            $ex->setCellValue('G'.$counter, $row->ppn);
            $ex->setCellValue('H'.$counter, $row->total);


            $counter = $counter+1;
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$counter.':H'.$counter);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':H'.$counter)
                    ->applyFromArray($det)
                    ->getFont();
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$counter, 'DATA DETAIL');

            $counter = $counter+1;
            $objPHPExcel->getActiveSheet()->getStyle($counter)->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$counter, 'NAMA BARANG')
                ->setCellValue('C'.$counter, 'SATUAN')
                ->setCellValue('D'.$counter, 'QTY ORDER')
                ->setCellValue('E'.$counter, 'QTY BOOKED')
                ->setCellValue('F'.$counter, 'QTY PENDING')
                ->setCellValue('G'.$counter, 'QTY CANCEL')
                ->setCellValue('H'.$counter, 'QTY SUPPLY');

            $counter = $counter+1;

            $data_so_detail = $this->Detailsalesorder_model
                              ->where(array(
                                  'no_so' => $row->no_so
                                  ))
                              ->find_all();

            foreach ($data_so_detail as $row_detail):
              $ex->setCellValue('B'.$counter, $row_detail->nm_barang);
              $ex->setCellValue('C'.$counter, $row_detail->satuan);
              $ex->setCellValue('D'.$counter, $row_detail->qty_order);
              $ex->setCellValue('E'.$counter, $row_detail->qty_booked);
              $ex->setCellValue('F'.$counter, $row_detail->qty_pending);
              $ex->setCellValue('G'.$counter, $row_detail->qty_cancel);
              $ex->setCellValue('H'.$counter, $row_detail->qty_supply);
              $counter = $counter+1;
            endforeach;
        endforeach;

        $objPHPExcel->getActiveSheet()->getStyle('A1:H'.$counter)->applyFromArray(
      	 array(
      	     'borders' => array(
      	          'allborders' => array(
      	             'style' => PHPExcel_Style_Border::BORDER_THIN
      	             )
      	        )
      	 )
      	);
        $objPHPExcel->getProperties()->setCreator("Mohammad Ichsan")
            ->setLastModifiedBy("Mohammad Ichsan")
            ->setTitle("Export Report Data Sales Order")
            ->setSubject("Export Report Data Sales Order")
            ->setDescription("Report Data Sales Order for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Report Data Sales Order');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportReportSO'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }

    //Create New Purchase Order
    public function create()
    {

        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $supplier = $this->Purchaseorder_model->get_data('1=1','supplier');
        $this->template->set('cabang',$cabang);
        $this->template->set('supplier',$supplier);

        if($this->uri->segment(3) == ""){

            $data = $this->Purchaserequest_model->get_data("proses_po IS NULL","trans_pr_header");
            //$data = $this->Purchaserequest_model->order_by('no_pr','ASC')->find_all_by(array('proses_po'=>0));
        }else{
            $data = $this->Purchaserequest_model->get_data("kdcab ='".$this->uri->segment(3)."' ","trans_pr_header");
        }
        $this->template->set('results', $data);

        $this->template->title('Input Purchase Order');
        $this->template->render('po_form');
    }

    //Create New Purchase Order
    public function proses()
    {
        $getparam = explode(";",$_GET['param']);
        $getpr = $this->Detailpurchaserequest_model->join('cabang','trans_pr_header.kdcab = cabang.kdcab','left')->get_where_in('trans_pr_header.no_pr',$getparam,'trans_pr_header');

        $and = " proses_po IS NULL ";
        $getitempr = $this->Detailpurchaserequest_model->get_where_in_and('no_pr',$getparam,$and,'trans_pr_detail');
        $this->template->set('param',$getparam);
        $this->template->set('headerpr',$getpr);
        $this->template->set('getitempr',$getitempr);
        $this->template->title('Input Purchase Order');
        $this->template->render('purchaseorder_form');
    }
    function print_picking_list($noso){
        $no_so = $noso;
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $so_data = $this->Salesorder_model->find_data('trans_so_header',$no_so,'no_so');
        $customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        $detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so));

        $this->template->set('so_data', $so_data);
        $this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }


}

?>
