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
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        //$kdcab = '102';
        $data = $this->Invoice_model->where(array('piutang >'=>0,'kdcab'=>$kdcab))->order_by('no_invoice','DESC')->find_all();
        $cabang = $this->Cabang_model->order_by('kdcab','ASC')->find_all();
        $this->template->title('Pembayaran Piutang');
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
        $this->template->title('Pembayaran Piutang');
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

    function setdatagiro(){
        $no_inv = $this->input->post('NO_INV');
        $giro = $this->Invoice_model->get_data(array("status_giro"=>'N'),'giro');
        $this->template->set('invoice', $no_inv);
        $this->template->set('giro', $giro);
        $this->template->render('setdatagiro');
    }

    function simpandatagiro(){
        $session = $this->session->userdata('app_session');

        $kdbank = '';
        $nmbank = '';
        if(!empty($this->input->post('girobank'))){
            $bank = explode('|',$this->input->post('girobank'));
            $kdbank = $bank[0];
            $nmbank = $bank[1];
        }

        $datagiro = array(
            'no_giro' => $this->input->post('no_giro'),
            'kdcab' => $session['kdcab'],
            'id_bank' => $kdbank,
            'nm_bank' => $nmbank,
            'tgl_giro' => $this->input->post('tgl_transaksi_giro'),
            'nilai_fisik' => $this->input->post('nilai_fisik_giro'),
            'tgl_jth_tempo' => $this->input->post('tgl_jth_tempo_giro'),
            'status_giro' => 'N',
            'status' => 'OPEN',
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user']
            );

        $this->db->trans_begin();
        $this->db->insert('giro',$datagiro);
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
            'msg' => "SUKSES, simpan data..!!!"
            );
        }
        echo json_encode($param);
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

        $kdbank = '';
        $nmbank = '';
        if(!empty($this->input->post('bank'))){
            $bank = explode('|',$this->input->post('bank'));
            $kdbank = $bank[0];
            $nmbank = $bank[1];
        }

        $pembayaran_ke = $this->Invoice_model->get_data(array("no_invoice"=>$this->input->post('no_invoice')),'pembayaran_piutang');
        $newpiutang = $this->input->post('jml_piutang')-$this->input->post('jml_bayar');
        $nomor_jurnal_jarh = $this->generatenomorjurnaljarh($this->input->post('kdcab'));
        
        $datapost = array(
            'kd_pembayaran' => $this->generatekodepembayaran($this->input->post('kdcab')),
            'no_invoice' => $this->input->post('no_invoice'),
            'jenis_reff' => $this->input->post('jenis_bayar'),
            'no_reff' => $this->input->post('no_reff'),
            'kdcab' => $this->input->post('kdcab'),
            'tgl_pembayaran' => $this->input->post('tgl_bayar'),
            'jumlah_piutang' => $this->input->post('jml_piutang'),
            'jumlah_pembayaran' => $this->input->post('jml_bayar'),
            'pembayaran_ke' => count($pembayaran_ke)+1,
            'kd_bank' => $kdbank,
            'nm_bank' => $nmbank,
            'is_cancel' => 'N',
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user']
            );

        $datapost['status_bayar'] = 'GIRO';
        if($this->input->post('jenis_bayar') != 'BG'){ //SELAIN GIRO
            $datapost['status_bayar'] = 'LUNAS';
        }

        $datajarh = array(
            'nomor'         => $nomor_jurnal_jarh,
            'kd_pembayaran' => $this->generatekodepembayaran($this->input->post('kdcab')),
            'tgl'           => $this->input->post('tgl_bayar'),
            'jml'           => $this->input->post('jml_bayar'),
            'kdcab'         => $this->input->post('kdcab'),
            'jenis_reff'    => $this->input->post('jenis_bayar'),
            'no_reff'       => $this->input->post('no_reff'),
            'terima_dari'   => $this->input->post('nmcus'),
            'valid'         => 1,
            'tgl_valid'     => date('Y-m-d'),
            'user_id'       => $session['id_user']
            );

        $noPerkiraan = '1101-01-01'; // nomor perkiraan CASH
        if($this->input->post('jenis_bayar') != 'CASH'){
            $noperk = $this->Invoice_model->cek_data(array('kd_bank'=>$bank[0]),'bank');
            $noPerkiraan = $noperk->no_perkiraan;
        }

        $datajurnal_1 = array(
            'nomor'         => $nomor_jurnal_jarh,
            'tanggal'       => $this->input->post('tgl_bayar'),
            'tipe'          => 'BUM',
            'no_perkiraan'  => $noPerkiraan,
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

        if($this->input->post('jenis_bayar') != 'BG'){ //SELAIN GIRO
        //UPDATE PIUTANG
        $this->db->where(array('no_invoice'=>$this->input->post('no_invoice')));
        $this->db->update('trans_invoice_header',array('piutang'=>$newpiutang));
        //-----//
        }

         //UPDATE AR
        $debetold = $this->Invoice_model->cek_data(array('no_invoice'=>$this->input->post('no_invoice')),'ar');
        $debetnow = $debetold->debet+$this->input->post('jml_bayar');
        $this->db->where(array('no_invoice'=>$this->input->post('no_invoice')));
        $this->db->update('ar',array('debet'=>$debetnow,'saldo_akhir'=>$newpiutang));
        //-----//

        //UPDATE STATUS GIRO BERDASAR NOMOR GIRO
        if($this->input->post('jenis_bayar') == 'BG'){
            $this->db->where(array('no_giro'=>$this->input->post('no_reff')));
            $this->db->update('giro',array('status_giro'=>'Y','status'=>'INV'));
        }
        //=====//

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

    function generatekodecancelpembayaran($kdcab){
        $counter = $this->Invoice_model->get_data('1=1','pembayaran_piutang');
        $kode = 1;
        if(count($counter) > 0){
            $kode = count($counter)+1;
        }
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
        return $kdcab.'-CC-'.$next_kode;
    }

    function batalpembayaranpiutang(){
        $session = $this->session->userdata('app_session');
        $id = $this->input->post('ID');
        $inv = $this->input->post('INV');
        $key = array('kd_pembayaran'=>$id);
        if(!empty($id)){
           //$this->db->delete('pembayaran_piutang',$key);
           $byr = $this->Invoice_model->cek_data($key,'pembayaran_piutang');
           $headerinv = $this->Invoice_model->cek_data(array('no_invoice'=>$inv),'trans_invoice_header');
           $datacancel = array(
            'kd_pembayaran' => $this->generatekodecancelpembayaran($byr->kdcab),
            'no_invoice' => $byr->no_invoice,
            'jenis_reff' => $byr->jenis_reff,
            'no_reff' => $byr->no_reff,
            'kdcab' => $byr->kdcab,
            'tgl_pembayaran' => $byr->tgl_pembayaran,
            'jumlah_piutang' => $byr->jumlah_piutang,
            'jumlah_pembayaran' => -$byr->jumlah_pembayaran,
            'pembayaran_ke' => count($byr->pembayaran_ke)+1,
            'kd_bank' => $byr->kd_bank,
            'nm_bank' => $byr->nm_bank,
            'created_on' => date('Y-m-d H:i:s'),
            'created_by' => $session['id_user'],
            'is_cancel' => 'Y',
            'kd_bayar_cancel' => $id,
            'cancel_on' => date('Y-m-d H:i:s'),
            'cancel_by' => $session['id_user']
            );
           //print_r($datacancel);die();
           $this->db->insert('pembayaran_piutang',$datacancel);
           //UPDATE KD BAYAR CANCEL
            $this->db->where($key);
            $this->db->update('pembayaran_piutang',array('is_cancel'=>'Y'));
            //-----//

            if($byr->jenis_reff != 'BG'){
            //UPDATE PIUTANG INVOICE
            $this->db->where(array('no_invoice'=>$inv));
            $this->db->update('trans_invoice_header',array('piutang'=>$headerinv->piutang+$byr->jumlah_pembayaran));
            //-----//
            }

            //UPDATE STATUS JARH BATAL = 1
            $this->db->where(array('kd_pembayaran'=>$byr->kd_pembayaran));
            $this->db->update('jarh',array('batal'=>1));
            //-----//

            //UPDATE STATUS GIRO BERDASAR NOMOR GIRO
            if($byr->jenis_reff == 'BG'){
                $this->db->where(array('no_giro'=>$byr->no_reff));
                $this->db->update('giro',array('status_giro'=>'N','status'=>'TOLAK'));
            }
            //=====//

             //UPDATE AR
            $debetold = $this->Invoice_model->cek_data(array('no_invoice'=>$inv),'ar');
            $debetnow = $debetold->debet-$byr->jumlah_pembayaran;
            $saldoakhir = $debetold->saldo_akhir+$byr->jumlah_pembayaran;
            $this->db->where(array('no_invoice'=>$inv));
            $this->db->update('ar',array('debet'=>$debetnow,'saldo_akhir'=>$saldoakhir));
            //-----//

           $param['cancel'] = 1;
           $param['invoice'] = $inv;
        }else{
           $param['cancel'] = 0;
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
