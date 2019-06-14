<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Ichsan
 * @copyright Copyright (c) 2018, Ichsan
 *
 * This is controller for Reportso
 */

class Reportso extends Admin_Controller {

    //Permission
    protected $viewPermission   = "Reportso.View";
    protected $addPermission    = "Reportso.Add";
    protected $managePermission = "Reportso.Manage";
    protected $deletePermission = "Reportso.Delete";

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

        $this->template->title('Report SO');
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

        //$data = $this->Salesorder_model->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab,'total !='=>0))->order_by('no_so','DESC')->find_all();
        if($this->uri->segment(3) == ""){

            $data = $this->Salesorder_model->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab))->order_by('no_so','DESC')->find_all();
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab))
            ->order_by('trans_so_header.no_so','ASC')->find_all();
        }else{
            $data = $this->Salesorder_model->get_data("stsorders ='".$this->uri->segment(3)."' AND LEFT(trans_so_header.no_so,3)='".$kdcab."' ","trans_so_header");
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab))
            ->order_by('trans_so_header.no_so','ASC')->get_data("stsorder ='".$this->uri->segment(3)."' ","trans_so_header");
        }
        $this->template->set('results', $data);
        $this->template->set('type', $type);
        $this->template->set('detail', $detail);
        $this->template->title('Report SO');
        $this->template->render('list');
    }

    public function filter()
    {
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $type = array(
                      ['kdtype' => 'OPEN', 'nmtype' => 'OPEN'],
                      ['kdtype' => 'CANCEL', 'nmtype' => 'CANCEL'],
                      ['kdtype' => 'CLOSE', 'nmtype' => 'CLOSE'],
                      ['kdtype' => 'ALL', 'nmtype' => 'ALL']
                    );

        //$data = $this->Salesorder_model->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab))->order_by('no_so','DESC')->find_all();
        if($this->uri->segment(3) == ""){

            $data = $this->Salesorder_model->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab,'total !='=>0))->order_by('no_so','DESC')->find_all();
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab))
            ->order_by('trans_so_header.no_so','ASC')->find_all();
            //$data = $this->Purchaserequest_model->order_by('no_pr','ASC')->find_all_by(array('proses_po'=>0));
        }else{
          if ($this->uri->segment(3) == "ALL") {
            $data = $this->Salesorder_model->find_all_by(array('LEFT(trans_so_header.no_so,3)'=>$kdcab));
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->order_by('trans_so_header.no_so','ASC')
            ->find_all_by(array('LEFT(trans_so_header.no_so,3)'=>$kdcab));
          }else {
            $data = $this->Salesorder_model->get_data("stsorder ='".$this->uri->segment(3)."' AND LEFT(trans_so_header.no_so,3)='".$kdcab."'  AND total != 0 ","trans_so_header");
            $detail = $this->Salesorder_model
            ->join("trans_so_detail","trans_so_detail.no_so = trans_so_header.no_so","left")
            ->where(array('LEFT(trans_so_header.no_so,3)'=>$kdcab))
            ->order_by('trans_so_header.no_so','ASC')->get_data("stsorder ='".$this->uri->segment(3)."' ","trans_so_header");
          }
        }
        $this->template->set('results', $data);
        $this->template->set('type', $type);
        $this->template->set('detail', $detail);
        $this->template->title('Report SO');
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
      $session = $this->session->userdata('app_session');
      $status = $this->uri->segment(3);
      $tgl = $this->uri->segment(4);
      $tgl_con = "trans_so_header.tanggal != '' ";
      if ($tgl != "All") {
        $tgl_con = "trans_so_header.tanggal like '%".$tgl."%'";
      }
      if ($this->uri->segment(3) == "ALL") {
        $data_so = $this->Salesorder_model
        ->select("*,trans_so_header.created_on as con,trans_so_header.modified_on as mon")
        ->where(array(
          'LEFT(trans_so_header.no_so,3)' => $session['kdcab']
        ))
        ->join("trans_so_detail", "trans_so_detail.no_so = trans_so_header.no_so", "left")
        ->join("barang_jenis", "LEFT(trans_so_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_so_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->get_data("$tgl_con","trans_so_header");
      }else {

        $data_so = $this->Salesorder_model
        ->select("*,trans_so_header.created_on as con,trans_so_header.modified_on as mon")
        ->where(array(
          'trans_so_header.stsorder' => $this->uri->segment(3)
        ))
        ->join("trans_so_detail", "trans_so_detail.no_so = trans_so_header.no_so", "left")
        ->join("barang_jenis", "LEFT(trans_so_detail.id_barang,2) = barang_jenis.id_jenis", "left")
        ->join("barang_group", "MID(trans_so_detail.id_barang,3,2) = barang_group.id_group", "left")
        ->get_data("LEFT(trans_so_header.no_so,3) = '".$session['kdcab']."' AND $tgl_con","trans_so_header");
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
      $this->template->title('Report SO');*/
      $this->load->view('view_report',$data);


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



}

?>
