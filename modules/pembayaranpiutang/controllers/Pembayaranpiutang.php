<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Pembayaranpiutang extends Admin_Controller {

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
        $data = $this->Invoice_model->where(array('piutang >'=>0))->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->title('Report Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data);
        $this->template->render('list');
    }

    public function filter()
      {

        $data = $this->Invoice_model
        ->where("kdcab='".$this->uri->segment(3)."' AND piutang > 0 ")
        ->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->set('results', $data);
        $this->template->set('cabang', $cabang);
        $this->template->title('Report Piutang');
        $this->template->render('list');
      }

    function setpembayaran(){
        $no_inv = $this->input->post('NO_INV');
        //$invoice = $this->Invoice_model->where("no_invoice='".$no_inv."' ")->order_by('no_invoice','DESC')->find_all();
        $invoice = $this->Invoice_model->cek_data(array("no_invoice"=>$no_inv),'trans_invoice_header');
        $pembayaran = $this->Invoice_model->get_data(array("no_invoice"=>$no_inv),'pembayaran_piutang');
        $bank = $this->Invoice_model->get_data('1=1','bank');
        $this->template->set('pembayaran', $pembayaran);
        $this->template->set('invoice', $invoice);
        $this->template->set('bank', $bank);
        $this->template->render('setpembayaran');
    }

    function generatekodepembayaran($kdcab){
        $counter = $this->Invoice_model->get_data('1=1','pembayaran_piutang');
        $kode = 1;
        if(count($counter) > 0){
            $kode = count($counter)+1;
        }
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
        return $kdcab.'-PB-'.$next_kode;
    }

    function generatenomorjurnaljarh($kdcab){
        $counter = $this->Invoice_model->get_data('1=1','jarh');
        $kode = 1;
        if(count($counter) > 0){
            $kode = count($counter)+1;
        }
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
        return $kdcab.'AUM'.date('Y').$next_kode;
    }

    function simpanpembayaran(){
        $session = $this->session->userdata('app_session');
        $bank = explode('|',$this->input->post('bank'));
        $pembayaran_ke = $this->Invoice_model->get_data(array("no_invoice"=>$this->input->post('no_invoice')),'pembayaran_piutang');
        $newpiutang = $this->input->post('jml_piutang')-$this->input->post('jml_bayar');
        $nomor_jurnal_jarh = $this->generatenomorjurnaljarh($this->input->post('kdcab'));
        
        $datapost = array(
            'kd_pembayaran' => $this->generatekodepembayaran($this->input->post('kdcab')),
            'no_invoice' => $this->input->post('no_invoice'),
            'no_reff' => $this->input->post('no_reff'),
            'kdcab' => $this->input->post('kdcab'),
            'tgl_pembayaran' => $this->input->post('tgl_bayar'),
            'jumlah_piutang' => $this->input->post('jml_piutang'),
            'jumlah_pembayaran' => $this->input->post('jml_bayar'),
            'pembayaran_ke' => count($pembayaran_ke)+1,
            'kd_bank' => $bank[0],
            'nm_bank' => $bank[1],
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user']
            );

        $datajarh = array(
            'nomor'         => $nomor_jurnal_jarh,
            'tgl'           => $this->input->post('tgl_bayar'),
            'jml'           => $this->input->post('jml_bayar'),
            'kdcab'         => $this->input->post('kdcab'),
            'jenis_reff'    => '',
            'no_reff'       => $this->input->post('no_reff'),
            'terima_dari'   => $this->input->post('nmcus'),
            'valid'         => 1,
            'tgl_valid'     => date('Y-m-d'),
            'user_id'       => $session['id_user']
            );

        $noperk = $this->Invoice_model->cek_data(array('kd_bank'=>$bank[0]),'bank');

        $datajurnal_1 = array(
            'nomor'         => $nomor_jurnal_jarh,
            'tanggal'       => $this->input->post('tgl_bayar'),
            'tipe'          => 'BUM',
            'no_perkiraan'  => $noperk->no_perkiraan,
            'keterangan'    => 'Pembayaran Invoice #'.$this->input->post('no_invoice').'#'.$this->input->post('nmcus'),
            'no_reff'       => $this->input->post('no_invoice'),
            'debet'         => $this->input->post('jml_bayar'),
            'kredit'        => 0
            );
        $datajurnal_2 = array(
            'nomor'         => $nomor_jurnal_jarh,
            'tanggal'       => $this->input->post('tgl_bayar'),
            'tipe'          => 'BUM',
            'no_perkiraan'  => '1104-01-01',
            'keterangan'    => 'Pembayaran Invoice #'.$this->input->post('no_invoice').'#'.$this->input->post('nmcus'),
            'no_reff'       => $this->input->post('no_invoice'),
            'debet'         => 0,
            'kredit'        => $this->input->post('jml_bayar')
            );

        /*
        echo '<pre>'.print_r($datapost);
        echo '<pre>'.print_r($datajarh);
        echo '<pre>'.print_r($datajurnal_1);
        echo '<pre>'.print_r($datajurnal_2);
        exit();
        */

        $this->db->trans_begin();
        $this->db->insert('pembayaran_piutang',$datapost);
        $this->db->insert('jarh',$datajarh);
        $this->db->insert('jurnal',$datajurnal_1);
        $this->db->insert('jurnal',$datajurnal_2);
        //UPDATE PIUTANG
        $this->db->where(array('no_invoice'=>$this->input->post('no_invoice')));
        $this->db->update('trans_invoice_header',array('piutang'=>$newpiutang));
        //-----//
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $param = array(
            'save' => 0,
            'msg' => "GAGAL, simpan data..!!!"
            );
        }
        else
        {
            $this->db->trans_commit();
            $param = array(
            'save' => 1,
            'no_inv' => $this->input->post('no_invoice'),
            'msg' => "SUKSES, simpan data..!!!"
            );
        }
        echo json_encode($param);
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
