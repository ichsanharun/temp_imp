<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Reportstok_trans extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission
    protected $viewPermission   = "Reportstok_trans.View";
    protected $addPermission    = "Reportstok_trans.Add";
    protected $managePermission = "Reportstok_trans.Manage";
    protected $deletePermission = "Reportstok_trans.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Reportstok/Reportstok_model',
                                 'Cabang/Cabang_model',
                                 'Trans_stock/Trans_stock_model',
                                 'Adjusment/Adjusment_stock_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report Stock');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $data = $this->Reportstok_model
        ->join("cabang","barang_stock.kdcab = cabang.kdcab","left")
        ->find_all();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->render('list');
    }

   	//Create New barang
   	public function create()
   	{

        $this->auth->restrict($this->addPermission);

        $barang = $this->Barang_model->pilih_barang()->result();

        $this->template->set('barang',$barang);
        $this->template->title('Stock');
		$this->template->render('report_stok_form');
   	}

   	//Edit barang


    //Save using ajax




    function get_data(){
        $id_barang = $this->input->post('id_barang');
        if(!empty($id_barang)){
            $detail  = $this->Barang_model->find($id_barang);
        }
        echo json_encode($detail);
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

    function print_rekap_group(){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $group_data = $this->Reportstok_model->get_data('1=1','barang_group');

        $this->template->set('group_data', $group_data);

        $show = $this->template->load_view('print_rekap_group',$data);

        $this->mpdf->AddPage('P');
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
    function print_trans($id){
        $mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
        $mpdf->SetImportUse();
        //$mpdf->RestartDocTemplate();
        $session = $this->session->userdata('app_session');
        $trans = $this->Trans_stock_model->order_by('date_stock','DESC')->find_all_by(array('id_barang'=>$id,'kdcab'=>$session['kdcab']));
        //$customer = $this->Salesorder_model->cek_data(array('id_customer'=>$so_data->id_customer),'customer');
        //$detail = $this->Detailsalesorder_model->find_all_by(array('no_so' => $no_so, 'qty_booked >'=>0));

        //$this->template->set('so_data', $so_data);
        $this->template->set('trans', $trans);
        //$this->template->set('detail', $detail);
        $show = $this->template->load_view('print_kartu',$data);

        $header = '<table width="100%" border="0" id="header-tabel">
                      <tr>
                      <th width="30%" style="text-align: left;">
                        <img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
                      </th>
                          <th colspan="3" width="20%" style="text-align: center;font-size: 16px;">KARTU STOK BARANG<br>No. :'.$id.'<br>'.$trans[0]->nm_barang.'</th>
                          <th colspan="4" style="border-left: none;"></th>
                      </tr>
                      </table>

        ';

            $this->mpdf->SetHTMLHeader($header,'0',true);
            $session = $this->session->userdata('app_session');
            $this->mpdf->SetHTMLFooter('
            <hr>
                  <div id="footer">
                  <table>
                      <tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($session["nm_lengkap"]) .' On '. date("d-m-Y H:i:s").'</td></tr>
                  </table>
                  </div>
            ');


            $this->mpdf->AddPageByArray([
                    'orientation' => 'P',
                    'sheet-size'=> [210,148],
                    'margin-top' => 20,
                    'margin-bottom' => 17,
                    'margin-left' => 5,
                    'margin-right' => 5,
                    'margin-header' => 0,
                    'margin-footer' => 0,
                ]);
            $this->mpdf->WriteHTML($show);
            $this->mpdf->Output();
    }
}
?>
