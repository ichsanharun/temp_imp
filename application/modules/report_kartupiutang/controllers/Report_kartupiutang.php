<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Reportstok
 */

class Report_kartupiutang extends Admin_Controller {

    /**
     * Load the models, library, etc
     *
     *
     */
    //Permission

    protected $viewPermission   = "Report_kartupiutang.View";
    protected $addPermission    = "Report_kartupiutang.Add";
    protected $managePermission = "Report_kartupiutang.Manage";
    protected $deletePermission = "Report_kartupiutang.Delete";

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Reportar/Reportar_model',
                                 'Report_kartupiutang/Report_kartupiutang_model',

                                 'Cabang/Cabang_model',
								                 'Piutang_cabang/Piutang_cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        date_default_timezone_set("Asia/Bangkok");

        $this->template->title('Report AR');
        $this->template->page_icon('fa fa-table');
    }

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
    $date = $Tahun.str_pad($Bulan, 2, "0", STR_PAD_LEFT);
    $data_inv = $this->db->group_by('id_customer')->get_where('trans_invoice_header',array('kdcab'=>$kdcab,'flag_cancel'=>'N'))->result();
    $cus=array();
    $arr = 0;
    foreach ($data_inv as $key => $value) {
      $cus[$arr] = $value->id_customer;
      $arr++;
    }
    $customer = $this->db->where_in('id_customer', $cus)->get('customer')->result();

    //$data_inv = $this->db->like('tanggal_invoice', $date)->get_where('trans_invoice_header',array('kdcab'=>$kdcab,'flag_cancel'=>'N'))->result();
		$data			= $this->db->get_where('ar',array('kdcab'=>$kdcab,'bln'=>$Bulan,'thn'=>$Tahun))->result();
		$cabang 		= $this->db->get('cabang')->result();
        $this->template->title('Report Kartu Piutang');
        $this->template->set('cabang', $cabang);
        $this->template->set('results', $data_inv);
        $this->template->set('customer', $customer);
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

    function print_trans($id){
        $mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
        $mpdf->SetImportUse();
        $customer = $this->db->where('id_customer', $id)->get('customer')->row();
        $session = $this->session->userdata('app_session');
        $data_inv = $this->db->where(array('id_customer'=>$id,'flag_cancel'=>'N'))->get('trans_invoice_header')->result();

        $this->template->set('customer', $customer);
        $this->template->set('data_inv', $data_inv);
        //$this->template->set('detail', $detail);
        $show = $this->template->load_view('print_kartu',$data);

        $header = '<table width="100%" border="0" id="header-tabel">
                      <tr>
                      <th width="30%" style="text-align: left;">
                        <img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
                      </th>
                          <th colspan="3" width="20%" style="text-align: center;font-size: 16px;">KARTU PIUTANG<br>Cus. :'.$customer->nm_customer.'</th>
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
                    'sheet-size'=> [210,290],
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

    function get_pilihan(){
      $kdcab = $this->auth->user_cab();
        $id_customer = $_GET['customer'];
        $det ='<table cellpadding="5" cellspacing="0" border="0" class="table table-bordered" style="padding-left:50px;">';
        //foreach ($datbarang as $key => $value) {
          $det .= '<tr>
                <td>
                  <form action="'.base_url("reportpenjualan/index").'" method="post">
                    <input type="hidden" name="filter_by" value="by_customer">
                    <input type="hidden" name="filter_value" value="'.$id_customer.'">
                    <input type="hidden" name="periode_awal" value="2000-01-01">
                    <input type="hidden" name="periode_akhir" value="'.date("Y-m-d").'">
                    <input type="hidden" name="ket" value="view">
                    <button type="submit" class="btn btn-sm btn-danger" style="width:100%">View Invoice</button>
                  </form>
                </td>
                <!--<td><a href="javascript:void(0)" onclick="get_payment("'.$id_customer.'")" class="btn btn-sm btn-success" style="width:100%">View Payment</a></td>-->
                <td>
                  <a href="'.base_url('reportpembayaran/get_filter/'.$id_customer).'" class="btn btn-sm btn-success" style="width:100%">View Payment</a>
                </td>
              </tr>';
        //}
        $det .= '</table>';

        //echo json_encode($datbarang);
        echo $det;
    }
}
?>
