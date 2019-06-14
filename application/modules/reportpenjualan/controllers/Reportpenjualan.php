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
    protected $viewPermission   = "Reportpenjualan.View";
    /*
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
                                 'Salesorder/Salesorder_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Penjualan');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
       $session = $this->session->userdata('app_session');
       $kdcab = $session['kdcab'];
	   if($this->input->post()){
		   $filter_by		= $this->input->post('filter_by');
		   $filter_value	= $this->input->post('filter_value');
		   $periode_awal	= $this->input->post('periode_awal');
		   $periode_akhir	= $this->input->post('periode_akhir');
	   }else{
		   $filter_by		= "";
		   $filter_value	= "";
		   $periode_awal	= date('Y-m-01');
		   $periode_akhir	= date('Y-m-d');
	   }
	   $order_by	="tanggal_invoice";
	   $WHERE		= "kdcab='".$kdcab."' AND hargajualtotal !=0 AND (flag_cancel IS NULL OR flag_cancel='' OR flag_cancel='N')";
	   $rows_filter	= array();
	   if($filter_by){
		   $rows_filter	=$this->get_filter_data($filter_by,'N');
		   if($filter_value){
			   if(!empty($WHERE))$WHERE	.=" AND ";
			   if($filter_by=='by_customer'){
				   $order_by	="nm_customer";
				   $WHERE		.="id_customer='".$filter_value."'";
			   }else{
				   $order_by	="nm_salesman";
				   $WHERE		.="id_salesman='".$filter_value."'";
			   }
		   }

	   }
	   if(!empty($periode_awal) && !empty($periode_akhir)){
		    if(!empty($WHERE))$WHERE	.=" AND ";
			 $WHERE		.="(tanggal_invoice BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
	   }


  		$Qry_Invoice	= "SELECT * FROM trans_invoice_header WHERE ".$WHERE." ORDER BY ".$order_by." ASC";
  		$data			= $this->db->query($Qry_Invoice)->result();
        //$data = $this->Invoice_model->where($WHERE)->order_by('no_invoice','DESC')->find_all();
        $penanda = $this->input->post('ket');
        $this->template->set('ket', $penanda);
        $this->template->title('Report Penjualan');
        $this->template->set('results', $data);
  		$this->template->set('periode_awal', $periode_awal);
  		$this->template->set('periode_akhir', $periode_akhir);
  		$this->template->set('filter_by', $filter_by);
  		$this->template->set('filter_value', $filter_value);
  		$this->template->set('rows_filter', $rows_filter);
        $this->template->render('list_baru');
    }

    public function get_filter()
    {
       $session = $this->session->userdata('app_session');
       $kdcab = $session['kdcab'];
	   if($this->input->post()){
		   $filter_by		= $this->input->post('filter_by');
		   $filter_value	= $this->input->post('filter_value');
		   $periode_awal	= $this->input->post('periode_awal');
		   $periode_akhir	= $this->input->post('periode_akhir');
	   }else{
		   $filter_by		= "";
		   $filter_value	= "";
		   $periode_awal	= date('Y-m-01');
		   $periode_akhir	= date('Y-m-d');
	   }
	   $order_by	="tanggal_invoice";
	   $WHERE		= "kdcab='".$kdcab."' AND hargajualtotal !=0 AND (flag_cancel IS NULL OR flag_cancel='' OR flag_cancel='N')";
	   $rows_filter	= array();
	   if($filter_by){
		   $rows_filter	=$this->get_filter_data($filter_by,'N');
		   if($filter_value){
			   if(!empty($WHERE))$WHERE	.=" AND ";
			   if($filter_by=='by_customer'){
				   $order_by	="nm_customer";
				   $WHERE		.="id_customer='".$filter_value."'";
			   }else{
				   $order_by	="nm_salesman";
				   $WHERE		.="id_salesman='".$filter_value."'";
			   }
		   }

	   }
	   if(!empty($periode_awal) && !empty($periode_akhir)){
		    if(!empty($WHERE))$WHERE	.=" AND ";
			 $WHERE		.="(tanggal_invoice BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
	   }



  		$Qry_Invoice	= "SELECT * FROM trans_invoice_header WHERE ".$WHERE." ORDER BY ".$order_by." ASC";
  		$data			= $this->db->query($Qry_Invoice)->result();
        //$data = $this->Invoice_model->where($WHERE)->order_by('no_invoice','DESC')->find_all();

        $this->template->title('Report Penjualan');
        $this->template->set('results', $data);

  		$this->template->set('periode_awal', $periode_awal);
  		$this->template->set('periode_akhir', $periode_akhir);
  		$this->template->set('filter_by', $filter_by);
  		$this->template->set('filter_value', $filter_value);
  		$this->template->set('rows_filter', $rows_filter);
        $this->template->render('list_baru');
    }

	public function excel_penjualan($periode_awal,$periode_akhir,$filter_by,$filter_value)
    {
       $session = $this->session->userdata('app_session');
       $kdcab = $session['kdcab'];
	   $Judul		= "LAPORAN PENJUALAN";
	   $order_by	="tanggal_invoice";
	   $WHERE		= "kdcab='".$kdcab."' AND hargajualtotal !=0 AND (flag_cancel IS NULL OR flag_cancel='' OR flag_cancel='N')";
	   $rows_filter	= array();
	   if($filter_by !='ALL'){
		   $rows_filter	=$this->get_filter_data($filter_by,'N');
		   if($filter_value !='ALL'){
			   if(!empty($WHERE))$WHERE	.=" AND ";
			   if($filter_by=='by_customer'){
				   $Judul		.=" (Cust : ".$rows_filter[$filter_value].")";
				   $order_by	="nm_customer";
				   $WHERE		.="id_customer='".$filter_value."'";
			   }else{
				   $Judul		.=" (Sales : ".$rows_filter[$filter_value].")";
				   $order_by	="nm_salesman";
				   $WHERE		.="id_salesman='".$filter_value."'";
			   }
		   }

	   }
	   if(!empty($periode_awal) && !empty($periode_akhir)){
		   $Judul		.=" PERIODE ".date('d M Y',strtotime($periode_awal))." - ".date('d M Y',strtotime($periode_akhir));
		    if(!empty($WHERE))$WHERE	.=" AND ";
			 $WHERE		.="(tanggal_invoice BETWEEN '".$periode_awal."' AND '".$periode_akhir."')";
	   }


		$Qry_Invoice	= "SELECT * FROM trans_invoice_header WHERE ".$WHERE." ORDER BY ".$order_by." ASC";
		$data			= $this->db->query($Qry_Invoice)->result();
        //$data = $this->Invoice_model->where($WHERE)->order_by('no_invoice','DESC')->find_all();

        $this->template->set('results', $data);
		$this->template->set('judul', $Judul);
        $this->template->render('excel_penjualan');
    }

	function get_filter_data($kategori='',$tipe='Y'){
		$session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
		if($kategori=='by_customer'){
			$datafilter = $this->Invoice_model->get_data(array('kdcab'=>$kdcab),'customer');
		}else if($kategori=='by_sales'){
			$datafilter = $this->Salesorder_model->pilih_marketing($kdcab)->result();
		}

		$arr_Data	=  array();
		if($datafilter){
			foreach($datafilter as $key=>$vals){
				if($kategori=='by_customer'){
					$kode		= $vals->id_customer;
					$nilai		= $vals->nm_customer;
				}else{
					$kode		= $vals->id_karyawan;
					$nilai		= $vals->nama_karyawan;
				}
				$arr_Data[$kode]	= $nilai;
			}
		}

		if($tipe=='N'){
			return $arr_Data;
		}else{
			$temp_data	="<select name='filter_value' id='filter_value' class='form-control input-md'>";
			if($arr_Data){
				$temp_data	.="<option value=''>Silahkan Pilih</option>";
				foreach($arr_Data as $ky=>$vals){
					$temp_data	.="<option value='$ky'>$vals</option>";
				}
			}else{
				$temp_data	.="<option value=''>Empty List</option>";
			}
			$temp_data	.="</select>";
			echo $temp_data;

		}

	}

    public function filter()
      {
      	$session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $filter = $this->input->get('filter');
        $param = $this->input->get('param');
        $where ='';
        if($filter == "by_produk"){
            $where = " AND id_barang='".$param."' ";
        }elseif($filter == "by_customer"){
            $where = " AND id_customer='".$param."' ";
        }elseif($filter == "by_sales"){
            $where = " AND id_salesman='".$param."' ";
        }else {
          $where = "";
        }
        $data = $this->Invoice_model
        ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$kdcab."' ".$where)
        ->order_by('no_invoice','DESC')->find_all();
        $this->template->set('results', $data);
        $this->template->title('Report Penjualan');
        $this->template->render('list');
      }

    function getfilterby(){
        $filter = $this->input->post('FILTER');
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        if(!empty($filter)){
            if($filter == "by_produk"){
                $datafilter = $this->Invoice_model->get_data('1=1','barang_master');
            }elseif($filter == "by_customer"){
                $datafilter = $this->Invoice_model->get_data(array('kdcab'=>$kdcab),'customer');
            }else{
                $datafilter = $this->Salesorder_model->pilih_marketing($kdcab)->result();
            }
        }else{
            $datafilter='belum';
        }
        $this->template->set('filter', $filter);
        $this->template->set('selectfilter', $datafilter);
        $this->template->render('filterby');
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
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $filter = $this->input->get('filter');
        $param = $this->input->get('param');
        $where ='';
        if($filter == "by_produk"){
            $where = " AND id_barang='".$param."' ";
        }elseif($filter == "by_customer"){
            $where = " AND id_customer='".$param."' ";
        }else{
            $where = " AND id_salesman='".$param."' ";
        }
        $dataexcel = $this->Invoice_model
        ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$kdcab."' ".$where)
        ->order_by('no_invoice','DESC')->find_all();

        //print_r($dataexcel);die();

        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);

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

        $objPHPExcel->getActiveSheet()->getStyle("A1:G2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G2');


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Data Penjualan')
            ->setCellValue('A3', 'No')
            ->setCellValue('B3', 'No Invoice')
            ->setCellValue('C3', 'Nama Customer')
            ->setCellValue('D3', 'Tanggal')
            ->setCellValue('E3', 'Nama Sales')
            ->setCellValue('F3', 'Total')
            ->setCellValue('G3', 'Status');

        $ex = $objPHPExcel->setActiveSheetIndex(0);

        $no = 1;
        $counter = 4;
        $gt = 0;
        foreach ($dataexcel as $row):
            $gt += $row->hargajualtotal;
            $st = 'BATAL';
            if($row->flag_cancel == 'N'){
                $st ="TIDAK BATAL";
            }
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row->no_invoice));
            $ex->setCellValue('C'.$counter, strtoupper($row->nm_customer));
            $ex->setCellValue('D'.$counter, strtoupper($row->tanggal_invoice));
            $ex->setCellValue('E'.$counter, strtoupper($row->nm_salesman));
            $ex->setCellValue('F'.$counter, $row->hargajualtotal);
            $ex->setCellValue('G'.$counter, $st);

        $counter = $counter+1;
        endforeach;
        $c = "A".$counter.":E".$counter;
        $objPHPExcel->getActiveSheet()->mergeCells($c);
        $objPHPExcel->getActiveSheet()->getStyle($c)
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$counter, 'GRAND TOTAL');
        $ex->setCellValue('F'.$counter, $gt);

        $objPHPExcel->getProperties()->setCreator("Importa")
            ->setLastModifiedBy("Importa")
            ->setTitle("Export Rekap Penjualan")
            ->setSubject("Export Rekap Penjualan")
            ->setDescription("Rekap Penjualan for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Penjualan');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="LaporanPenjualan'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }

    function downloadExcel_new()
    {
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $filter = $this->input->get('filter');
        $param = $this->input->get('param');
        $where ='';
        if($filter == "by_produk"){
            $where = " AND id_barang='".$param."' ";
        }elseif($filter == "by_customer"){
            $where = " AND id_customer='".$param."' ";
        }elseif($filter == "by_sales"){
            $where = " AND id_salesman='".$param."' ";
        }else {
          $where = "";
        }
        $dataexcel = $this->Invoice_model
        ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$kdcab."' ".$where)
        ->order_by('no_invoice','DESC')->find_all();

        //print_r($dataexcel);die();

        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);

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

        $objPHPExcel->getActiveSheet()->getStyle("A1:G2")
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:G2');


        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Laporan Data Penjualan')
            ->setCellValue('A3', 'No')
            ->setCellValue('B3', 'No Invoice')
            ->setCellValue('C3', 'Nama Customer')
            ->setCellValue('D3', 'Tanggal')
            ->setCellValue('E3', 'Nama Sales')
            ->setCellValue('F3', 'Total')
            ->setCellValue('G3', 'Status');

        $ex = $objPHPExcel->setActiveSheetIndex(0);

        $no = 1;
        $counter = 4;
        $gt = 0;
        foreach ($dataexcel as $row):
            $gt += $row->hargajualtotal;
            $st = 'BATAL';
            if($row->flag_cancel == 'N'){
                $st ="TIDAK BATAL";
            }
            $ex->setCellValue('A'.$counter, $no++);
            $ex->setCellValue('B'.$counter, strtoupper($row->no_invoice));
            $ex->setCellValue('C'.$counter, strtoupper($row->nm_customer));
            $ex->setCellValue('D'.$counter, strtoupper($row->tanggal_invoice));
            $ex->setCellValue('E'.$counter, strtoupper($row->nm_salesman));
            $ex->setCellValue('F'.$counter, $row->hargajualtotal);
            $ex->setCellValue('G'.$counter, $st);

        $counter = $counter+1;
        endforeach;
        $c = "A".$counter.":E".$counter;
        $objPHPExcel->getActiveSheet()->mergeCells($c);
        $objPHPExcel->getActiveSheet()->getStyle($c)
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$counter, 'GRAND TOTAL');
        $ex->setCellValue('F'.$counter, $gt);

        $objPHPExcel->getProperties()->setCreator("Importa")
            ->setLastModifiedBy("Importa")
            ->setTitle("Export Rekap Penjualan")
            ->setSubject("Export Rekap Penjualan")
            ->setDescription("Rekap Penjualan for Office 2007 XLSX, generated by PHPExcel.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("PHPExcel");
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Penjualan');
        ob_end_clean();
        $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'. gmdate("D, d M Y H:i:s").'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', FALSE);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="LaporanPenjualan'. date('Ymd') .'.xls"');

        $objWriter->save('php://output');

    }

    function downloadExcel_old()
    {
      $session = $this->session->userdata('app_session');
      $kdcab = $session['kdcab'];

      $filter = $this->uri->segment(5);
      $param = $this->uri->segment(6);
      $where ='';
      if($filter == "all"){
          $where = "";
      }elseif($filter == "by_customer"){
          $where = " AND id_customer='".$param."' ";
      }elseif($filter == "by_sales"){
          $where = " AND id_salesman='".$param."' ";
      }else {
        $where = "";
      }
      $dataexcel = $this->Detailinvoice_model

      ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$kdcab."' ".$where)
      ->join("trans_invoice_header", "trans_invoice_detail.no_invoice = trans_invoice_header.no_invoice", "left")
      ->join("barang_jenis", "LEFT(trans_invoice_detail.id_barang,2) = barang_jenis.id_jenis", "left")
      ->join("barang_group", "MID(trans_invoice_detail.id_barang,3,2) = barang_group.id_group", "left")

      ->order_by('barang_jenis.id_jenis','DESC')

      ->find_all();

      $data_jenis = $this->Detailinvoice_model

      ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$kdcab."' ".$where)
      ->join("trans_invoice_header", "trans_invoice_detail.no_invoice = trans_invoice_header.no_invoice", "left")
      ->join("barang_jenis", "LEFT(trans_invoice_detail.id_barang,2) = barang_jenis.id_jenis", "left")
      ->join("barang_group", "MID(trans_invoice_detail.id_barang,3,2) = barang_group.id_group", "left")

      ->group_by('LEFT(trans_invoice_detail.id_barang,2)')

      ->find_all();


      $datahead = $this->Detailinvoice_model

      ->where("tanggal_invoice BETWEEN '".$this->uri->segment(3)."' AND '".$this->uri->segment(4)."' AND kdcab='".$kdcab."' ".$where)
      ->join("trans_invoice_header", "trans_invoice_detail.no_invoice = trans_invoice_header.no_invoice", "left")
      ->group_by('trans_invoice_header.no_invoice')

      ->find_all();


      $data = array(
  			'title2'		     => 'Report',
        'data_jenis'		 => $data_jenis,
  			'results'	       => $dataexcel,
        'results_head'	       => $datahead
  		);
      /*$this->template->set('results', $data_so);
      $this->template->set('head', $sts);
      $this->template->title('Report SO');*/
      $this->load->view('view_report',$data);


    }
}
?>
