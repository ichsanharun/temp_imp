<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Reportso
 */

class Report_stok_bulanan extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Report_stok_bulanan.View";
    protected $addPermission    = "Report_stok_bulanan.Add";
    protected $managePermission = "Report_stok_bulanan.Manage";
    protected $deletePermission = "Report_stok_bulanan.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf','upload','Image_lib'));

        $this->load->model(array( 'Barang/Barang_model',
                                  'Barang_stock/Barang_stock_model',
                                  'Aktifitas/aktifitas_model'
                                ));

        $this->template->title('Report Stok Bulanan');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $type = array(
          ['kdtype' => 'OPEN', 'nmtype' => 'OPEN'],
          ['kdtype' => 'CANCEL', 'nmtype' => 'CANCEL'],
          ['kdtype' => 'CLOSE', 'nmtype' => 'CLOSE'],
          ['kdtype' => 'ALL', 'nmtype' => 'ALL']
                    );


            $data = $this->db->where(array('kdcab'=>$kdcab))->get('barang_stock_bulanan')->result();


        $this->template->set('results', $data);
        $this->template->title('Report Stok Bulanan');
        $this->template->render('list');
    }

    function downloadExcel_old()
    {
      $bln = $this->uri->segment(4);
      $thn = $this->uri->segment(3);
      $session = $this->session->userdata('app_session');
      if ($this->uri->segment(3) == "All") {
        $data_so = $this->db->where(array('kdcab'=>$session['kdcab']))
        ->join("barang_jenis", "barang_stock_bulanan.jenis = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(barang_stock_bulanan.id_barang,3,2) = barang_group.id_group", "left")
        ->get("barang_stock_bulanan")->result();
      }else {

        $data_so = $this->db->where(array('kdcab'=>$session['kdcab'],'bulan'=>$this->uri->segment(4), 'tahun'=>$this->uri->segment(3)))
        ->join("barang_jenis", "barang_stock_bulanan.jenis = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(barang_stock_bulanan.id_barang,3,2) = barang_group.id_group", "left")
        ->get("barang_stock_bulanan")->result();
      }

        $sts = "STOK PERIODE ".date("M Y", strtotime($thn."-".$bln));

      $data = array(
        'title2'		=> 'Report',
        'results'	=> $data_so
      );
      $this->template->set('head', $sts);
      /*$this->template->set('results', $data_so);
      $this->template->title('Report SO');*/
      $this->load->view('view_report',$data);


    }
    function downloadExcel()
    {
      if ($this->uri->segment(4) == "All") {
        $data_so = $this->Salesorder_model->find_all();
      }else {

        $data_so = $this->Salesorder_model
        ->where(array(
          'stsorder' => $this->uri->segment(3)
        ))
        ->get_data("tanggal like '%".$this->uri->segment(4)."%'","trans_so_header");
      }
      if ($this->uri->segment(3) == "CLOSE") {
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

            $data_so_detail = $this->Salesorder_model
            ->get_data("no_so = '".$row->no_so."'","trans_so_detail");
            //$this->Detailsalesorder_model->find_all_by(array('no_so' => $row->no_so));

            foreach (@$data_so_detail as $row_detail){
              $ex->setCellValue('B'.$counter, $row_detail->nm_barang);
              $ex->setCellValue('C'.$counter, $row_detail->satuan);
              $ex->setCellValue('D'.$counter, $row_detail->qty_order);
              $ex->setCellValue('E'.$counter, $row_detail->qty_booked);
              $ex->setCellValue('F'.$counter, $row_detail->qty_pending);
              $ex->setCellValue('G'.$counter, $row_detail->qty_cancel);
              $ex->setCellValue('H'.$counter, $row_detail->qty_supply);
              $counter = $counter+1;
            }
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

    function downloadExcel_new()
    {
      $session = $this->session->userdata('app_session');
      if ($this->uri->segment(4) == "All") {
        $data_so = $this->Salesorder_model
        ->join("trans_so_detail", "trans_so_detail.no_so = trans_so_header.no_so", "left")
        ->join("barang_jenis", "LEFT(trans_so_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_so_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->find_all_by(array('LEFT(trans_so_header.no_so,3)'=>$session['kdcab']));
      }else {

        $data_so = $this->Salesorder_model
        ->where(array(
          'trans_so_header.stsorder' => $this->uri->segment(3)
        ))
        ->join("trans_so_detail", "trans_so_detail.no_so = trans_so_header.no_so", "left")
        ->join("barang_jenis", "LEFT(trans_so_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_so_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->get_data("LEFT(trans_so_header.no_so,3) = '".$session['kdcab']."' AND trans_so_header.tanggal like '%".$this->uri->segment(4)."%'","trans_so_header");
      }
      if ($this->uri->segment(3) == "CLOSE") {
        $sts = " CLOSE PERIODE ".date("M Y", strtotime($this->uri->segment(4)));
      }
      else {
        $sts = " OPEN PERIODE ".date("M Y", strtotime($this->uri->segment(4)));
      }

        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(27);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
       /* $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(17);
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
        $objPHPExcel->getActiveSheet()->getStyle("A1:Q2")
                    ->applyFromArray($header)
                    ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:Q2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'REPORT DATA SALES ORDER STATUS'.$sts);


        //$objPHPExcel->getActiveSheet()->mergeCells('H4:I4');
        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $counter = 3;
        $objPHPExcel->getActiveSheet()->getStyle($counter)->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$counter, 'No.')
        ->setCellValue('B'.$counter, 'NO. SO')
        ->setCellValue('C'.$counter, 'NAMA CUSTOMER')
        ->setCellValue('D'.$counter, 'TANGGAL SO')
        ->setCellValue('E'.$counter, 'NAMA SALESMAN')
        ->setCellValue('F'.$counter, 'DPP')
        ->setCellValue('G'.$counter, 'PPN')
        ->setCellValue('H'.$counter, 'TOTAL')
        ->setCellValue('I'.$counter, 'NAMA PRODUK')
        ->setCellValue('J'.$counter, 'JENIS PRODUK')
        ->setCellValue('K'.$counter, 'GRUP PRODUK')
        ->setCellValue('L'.$counter, 'SATUAN PRODUK')
        ->setCellValue('M'.$counter, 'QTY ORDER')
        ->setCellValue('N'.$counter, 'QTY BOOKED')
        ->setCellValue('O'.$counter, 'QTY PENDING')
        ->setCellValue('P'.$counter, 'QTY CANCEL')
        ->setCellValue('Q'.$counter, 'QTY SUPPLY');
        $counter = $counter+1;
        $nos = '';
        foreach ($data_so as $row):
          if ($nos != $row->no_so) {
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, $row->no_so);
            $ex->setCellValue('C'.$counter, $row->nm_customer);
            $ex->setCellValue('D'.$counter, PHPExcel_Shared_Date::PHPToExcel($row->tanggal));
            $ex->setCellValue('E'.$counter, $row->nm_salesman);
            $ex->setCellValue('F'.$counter, $row->dpp);
            $ex->setCellValue('G'.$counter, $row->ppn);
            $ex->setCellValue('H'.$counter, $row->total);
          }else {
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, '');
            $ex->setCellValue('C'.$counter, '');
            $ex->setCellValue('D'.$counter, '');
            $ex->setCellValue('E'.$counter, '');
            $ex->setCellValue('F'.$counter, '');
            $ex->setCellValue('G'.$counter, '');
            $ex->setCellValue('H'.$counter, '');
          }

            $ex->setCellValue('I'.$counter, $row->nm_barang);
            $ex->setCellValue('J'.$counter, $row->nm_jenis);
            $ex->setCellValue('K'.$counter, $row->nm_group);
            $ex->setCellValue('L'.$counter, $row->satuan);
            $ex->setCellValue('M'.$counter, $row->qty_order);
            $ex->setCellValue('N'.$counter, $row->qty_booked);
            $ex->setCellValue('O'.$counter, $row->qty_pending);
            $ex->setCellValue('P'.$counter, $row->qty_cancel);
            $ex->setCellValue('Q'.$counter, $row->qty_supply);
            $nos = $row->no_so;

            $counter++;
        endforeach;

        $objPHPExcel->getActiveSheet()->getStyle('A1:Q'.$counter)->applyFromArray(
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

    /*function downloadExcel_old()
    {
      $session = $this->session->userdata('app_session');
      if ($this->uri->segment(4) == "All") {
        $data_so = $this->Salesorder_model
        ->join("trans_so_detail", "trans_so_detail.no_so = trans_so_header.no_so", "left")
        ->join("barang_jenis", "LEFT(trans_so_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_so_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->find_all_by(array('LEFT(trans_so_header.no_so,3)'=>$session['kdcab']));
      }else {

        $data_so = $this->Salesorder_model
        ->where(array(
          'trans_so_header.stsorder' => $this->uri->segment(3)
        ))
        ->join("trans_so_detail", "trans_so_detail.no_so = trans_so_header.no_so", "left")
        ->join("barang_jenis", "LEFT(trans_so_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_so_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->get_data("LEFT(trans_so_header.no_so,3) = '".$session['kdcab']."' AND trans_so_header.tanggal like '%".$this->uri->segment(4)."%'","trans_so_header");
      }
      if ($this->uri->segment(3) == "CLOSE") {
        $sts = " CLOSE PERIODE ".date("M Y", strtotime($this->uri->segment(4)));
      }
      else {
        $sts = " OPEN PERIODE ".date("M Y", strtotime($this->uri->segment(4)));
      }
      $data = array(
  			'title2'		=> 'Report',
  			'results'	=> $data_so
  		);
      /*$this->template->set('results', $data_so);
      $this->template->set('head', $sts);
      $this->template->title('Report SO');
      $this->load->view('view_report',$data);


    }*/

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



}

?>
