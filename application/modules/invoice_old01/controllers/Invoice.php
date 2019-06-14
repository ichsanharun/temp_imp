<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Invoice
 */

class Invoice extends Admin_Controller {

    //Permission
    /*
    protected $viewPermission   = "Deliveryorder.View";
    protected $addPermission    = "Deliveryorder.Add";
    protected $managePermission = "Deliveryorder.Manage";
    protected $deletePermission = "Deliveryorder.Delete";
    */
    public function __construct(){
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Invoice/Invoice_model',
                                 'Invoice/Detailinvoice_model',
                                 'Customer/Customer_model',
                                 'Deliveryorder_2/Deliveryorder_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Invoice');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index(){
        //$this->auth->restrict($this->viewPermission);
        $session = $this->session->userdata('app_session');
        $data = $this->Invoice_model->order_by('no_invoice','DESC')->find_all_by(array('LEFT(no_invoice,3)'=>$session['kdcab']));
        $this->template->set('results', $data);
        $this->template->title('Invoice');
        $this->template->render('list');
    }

    //Create New Invoice
    public function create(){
        $session = $this->session->userdata('app_session');
        $customer = $this->Customer_model->find_all_by(array('kdcab'=>$session{'kdcab'}));
        $this->template->set('customer',$customer);
        $kode_customer		= '';

  		$Arr_Where			= array(
  			'status !='		=> 'INV',
        'konfirm_do'  => 'SUDAH',
        'LEFT(no_do,3)'=>$session['kdcab']
  		);
  		if($this->input->post()){
  			$kode_customer		= $this->input->post('idcustomer');
  			if(!empty($kode_customer)){
  				$Arr_Where['id_customer']	= $kode_customer;
  			}
  		}

        //$data = $this->Deliveryorder_model->order_by('no_do','ASC')->find_all();
        $data = $this->Deliveryorder_model->order_by('no_do','DESC')->find_all_by($Arr_Where);

        $this->template->set('kode_customer', $kode_customer);
        $this->template->set('results', $data);
        $this->template->title('Input Invoice');
        $this->template->render('list_do');
    }

     //Create New Invoice
    public function proses(){
  		if($this->input->post()){
  			$customer		      = $this->input->post('cekcustomer');
  			$getparamdo 		  = explode(";",$this->input->post('cekcus'));
  			$data_customer		= $this->Customer_model->find_data('customer',$customer,'id_customer');
        //$header		= $this->db->get_where('trans_do_header',array('no_do'=>$param))->result();
  			$Arr_Data		      = array();
  			                    $this->db->where_in('no_do',$getparamdo);
  			$headerdo         = $this->db->get('trans_do_header')->result_array();
        $customer         = $this->Customer_model->find_all_by(array('deleted'=>0));
        $Faktur_header	  = $this->Invoice_model->getFakturMaster();

  			foreach($headerdo as $key=>$vals){
  				$Arr_Data[$key]	= $vals;
  				$details				=
          $this->db->join("trans_so_header","trans_so_header.no_so = trans_do_detail.no_so","left")
          ->get_where('trans_do_detail',array('no_do'=>$vals['no_do'],'qty_supply != '=>0))->result_array();
  				$Arr_Data[$key]['detail_data']	= $details;
  			}

  			$this->template->set('data_cust', $data_customer);
        $this->template->set('customer', $customer);
  			$this->template->set('faktur', $Faktur_header);
  			$this->template->set('records', $Arr_Data);
        //$this->template->set('header', $header);
  			$this->template->title('Input Invoice');
  			$this->template->render('invoice_form');
  		}
      else{
  			 $this->template->render('list_do');
		  }

    }
    function get_customer(){
        $idcus = $_GET['idcus'];
        $customer = $this->Invoice_model->get_customer($idcus)->row();

        echo json_encode($customer);
    }

    function saveheaderinvoice(){
  		if($this->input->post()){
        $nd       		  = $this->input->post('nd');
  			$detail_do		  = $this->input->post('det_do');
  			$custid				  = $this->input->post('idcustomer_do');
  			$custname			  = $this->input->post('nmcustomer_do');

        $alamat			    = $this->input->post('alamat');
        $npwp			      = $this->input->post('npwp');
        $alamat_npwp	  = $this->input->post('alamat_npwp');


  			$tgl_expired		= $this->input->post('tgljthtempo');
  			$kefak				  = $this->input->post('kode_faktur');
  			$sts_materai		= $this->input->post('materai');
  			$id_salesman		= $this->input->post('id_salesman');
  			$nm_salesman		= $this->input->post('nm_salesman');
  			$nofaktur			  = '';
  			$OK					    = 1;

  			$faktur_data	  = $this->Invoice_model->getFakturAktif();

  			if($faktur_data['hasil'] != '1'){
  				$OK			= 0;
  			}else{
  				$Kode_Gen		= $faktur_data['data']['idgen'];
  				$fakturid		= $faktur_data['data']['no_faktur'];
  			}

  			if($OK==1){
  				$Tgl_Invoice			= $this->input->post('tgl_inv');
  				$session 				  = $this->session->userdata('app_session');
  				//$no_invoice 			= $this->Invoice_model->generate_noinv($session['kdcab']);
          $no_invoice 			= $this->Invoice_model->generate_noinv($session['kdcab']);
  				$customer 				= $this->Invoice_model->cek_data(array('id_customer'=>$custid),'customer');


  				$nofaktur			= $kefak.'.'.$fakturid;
  				$Arr_Update			= array(
  					'sts'				=> '1',
  					'nofaktur'			=> $nofaktur,
  					'tglfaktur'			=> $Tgl_Invoice,
  					'tglinvoice'		=> $Tgl_Invoice,
  					'noinvoice'			=> $no_invoice,
  					'tanggal_generate'	=> date('Y-m-d H:i:s')
  				);

  				$Biaya_Materai			= 0;
  				if($sts_materai=='Y'){
  					$Biaya_Materai		= 6000;
  				}
  				$Arr_detail				= array();
  				$Total_DPP	      = $Total_Diskon	= $Total_After	= $PPN	= $Total_landed = $Harga_tahap_1 = $Harga_tahap_2 = $Harga_tahap_3 = $Total_Diskon_item= 0;
  				$DO_Detail			  = $this->Invoice_model->get_where_in('no_do',$detail_do,'trans_do_detail');
  				$Awal					    =  0;
          $detail_inv_post = $this->input->post('id');
          for ($i = 0; $i<count($detail_inv_post); $i++) {
            $Awal++;
            $Arr_detail[$Awal]['no_invoice']				         = $no_invoice;
  					$Arr_detail[$Awal]['id_barang']					         = $this->input->post('id_barang')[$i];
  					$Arr_detail[$Awal]['nm_barang']					         = $this->input->post('nm_barang')[$i];
  					$Arr_detail[$Awal]['jumlah']					           = $this->input->post('jumlah')[$i];
  					//$Arr_detail[$Awal]['satuan']					           = $this->input->post('satuan')[$i];
  					$Arr_detail[$Awal]['hargajual']					         = $this->input->post('hargajual')[$i];
  					$Arr_detail[$Awal]['persen_diskon_stdr']		     = $this->input->post('persen_diskon_stdr')[$i];
  					$Arr_detail[$Awal]['harga_after_diskon_stdr']	   = $this->input->post('harga_after_diskon_stdr')[$i];
  					$Arr_detail[$Awal]['diskon_promo_persen']		     = $this->input->post('diskon_promo_persen')[$i];
  					$Arr_detail[$Awal]['diskon_promo_persen_rpnya']	 = $this->input->post('diskon_promo_persen_rpnya')[$i];
  					//$Arr_detail[$Awal]['diskon_promo_rp']			       = $this->input->post('diskon_promo_rp')[$i];
  					$Arr_detail[$Awal]['harga_nett_dari_so']		     = $this->input->post('harga_nett_dari_so')[$i];
  					$Arr_detail[$Awal]['harga_nett']				         = $this->input->post('harga_nett')[$i];
  					//$Arr_detail[$Awal]['total_diskon_item']			     = $this->input->post('total_diskon_item')[$i];

  					//$Arr_detail[$Awal]['hargalanded']				         = $this->input->post('hargalanded')[$i];

  					$Arr_detail[$Awal]['subtot_bef_diskon']			     = $this->input->post('subtot_bef_diskon')[$i];
  					$Arr_detail[$Awal]['subtot_after_diskon']		     = $this->input->post('subtot_after_diskon')[$i];

  					//	$Arr_detail[$Awal]['diskon']					= $discount_satuan;
  					$Arr_detail[$Awal]['tgljual']				    	       = $this->input->post('tgljual')[$i];
  					$Arr_detail[$Awal]['ppn']						             = $this->input->post('ppn')[$i];
  					$Arr_detail[$Awal]['no_do']					             = $this->input->post('no_do')[$i];
  					//$Arr_detail[$Awal]['bonus']				               = $this->input->post('bonus')[$key];
          }
  				foreach($DO_Detail as $keys=>$values){

  					$header_so			= $this->Invoice_model->cek_data(array('no_so'=>$noso),'trans_so_header');
  					$detail_so			= $this->Invoice_model->cek_data(array('no_so'=>$noso,'id_barang'=>$kode_barang),'trans_so_detail');
  					$landed_stock		= $this->Invoice_model->cek_data(array('id_barang'=>$kode_barang),'barang_stock');

  					$harga_so			       = $detail_so->harga_normal;// harga asli sebelum diskon
  					$diskon_persen_stdr  = $detail_so->diskon_persen; // (30%)
  					$rp_diskon_so		     = ceil($harga_so*$diskon_persen_stdr/100);
  					$Harga_tahap_1		   = $harga_so-$rp_diskon_so;// Harga item setelah diskon 30%

  					$diskon_promo_persen = $detail_so->diskon_promo_persen;
  					$diskon_promo_rp 	= $detail_so->diskon_promo_rp;

  					$rp_nya_persen = 0;
  					if($diskon_promo_persen > 0){
  						$rp_nya_persen = ceil($Harga_tahap_1*$diskon_promo_persen/100);
  					}

  					$Harga_tahap_2		= $Harga_tahap_1-$rp_nya_persen;

  					if($diskon_promo_rp > 0){
  						$Harga_tahap_3 = $Total_tahap_2-$diskon_promo_rp;
  					}else{
  						$Harga_tahap_3 = $Harga_tahap_2; // Tahap 3 = Total setelah diskon 30%,diskon promo dan diskon RP;
  					}

  					$diskon_toko_persen = $header_so->persen_diskon_toko; // Jika Agen 10%;
  					$diskon_cash_persen = $header_so->persen_diskon_cash; // Jika bayar cash 3%;
  					$dpp_barang			= $qty_supply * $harga_so;
  					$harga_bersih		= $qty_supply * $Harga_tahap_3;
  					$Total_Diskon_item 	= $dpp_barang-$harga_bersih;
  					$landed_cost		= $qty_supply * $landed_stock->landed_cost;

  					$sts_ppn			= $header_so->ppn;
  					$nil_ppn			= 0;
  					if($sts_ppn > 0){
  						$nil_ppn		= ceil($harga_bersih * 0.1);
  					}
  					$Total_DPP			+= $dpp_barang; // grand total sebelum diskon 30%
  					$Total_After		+= $harga_bersih; // grand total setelah diskon 30%

  					$Total_Diskon		+= $Total_Diskon_item;
  					$PPN				+= $nil_ppn;
  					$Total_landed		+= $landed_cost;




  				}

  				//echo"<pre> Cek bro : ";print_r($Arr_detail);exit;

  				if($PPN > 0){
  					$faktur_pajak		= $nofaktur;
  				}else{
  					$faktur_pajak		= '';

  				}

  				$Diskon_Toko 			= $diskon_toko_persen;
  				$Diskon_Cash 			= $diskon_cash_persen;
  				$Diskon_Toko_Rp 		= ceil($Total_After*$Diskon_Toko/100);
  				$Diskon_Cash_Rp 		= ceil($Total_After*$Diskon_Cash/100);
  				$Total_After_Bersih		= $Total_After-$Diskon_Toko_Rp-$Diskon_Cash_Rp;

  				$Grand_Total			= ceil($Total_After_Bersih + $PPN + $Biaya_Materai);

  				//echo"<pre> Cek bro : ";print_r($Arr_detail);exit;

  				$headerinv = array(
  					'no_invoice' 		        => $no_invoice,
  					'kdcab' 			          => $session['kdcab'],
  					'id_customer'	 	        => $this->input->post('id_customer'),
  					'nm_customer' 		      => $this->input->post('nm_customer'),
  					'tanggal_invoice'      	=> $this->input->post('tanggal_invoice'),
  					'id_salesman'	         	=> $this->input->post('id_salesman'),
  					'nm_salesman' 		      => $this->input->post('nm_salesman'),
  					'nofakturpajak' 	      => $faktur_pajak,
  					'tglfakturpajak' 	      => $this->input->post('tanggal_invoice'),
  					'tgljatuhtempo' 	      => $this->input->post('tgljatuhtempo'),
  					'alamatcustomer' 	      => $this->input->post('alamatcustomer'),
  					'npwpcustomer' 		      => $this->input->post('npwpcustomer'),
  					'diskon_toko_persen'    => $this->input->post('diskon_toko_persen'),
  					//'diskon_toko_rp'	      => $this->input->post('diskon_toko_rp'),
  					'diskon_cash_persen'    => $this->input->post('diskon_cash_persen'),
  					//'diskon_cash_rp'        => $this->input->post('diskon_cash_rp'),
  					'hargajualbefdis'	      => $this->input->post('hargajualbefdis'),
  					'hargajualafterdis'	    => $this->input->post('hargajualafterdis'),
  					'hargajualafterdistoko'	=> $this->input->post('hargajualafterdistoko'),
  					'hargajualafterdiscash'	=> $this->input->post('hargajualafterdiscash'),
  					'diskon_stdr_rp'	      => $this->input->post('diskon_stdr_rp'),
  					//'diskontotal'		        => $this->input->post('diskontotal'),
  					'dpp'				            => $this->input->post('dpp'),
  					'ppn'				            => $this->input->post('ppn'),
  					//'meterai'			          => $this->input->post('meterai'),
  					'hargajualtotal'	      => $this->input->post('hargajualtotal'),
  					'piutang'			          => $this->input->post('hargajualtotal'),
  					'hargalandedtotal'     	=> $Total_landed,
  				);

  				//echo"<pre> ono bro : ";print_r($Arr_detail);echo "<br>HEADER =><br>";print_r($headerinv);exit;

  				$dataAR = array(
  					'no_invoice' 		=> $no_invoice,
  					'tgl_invoice'		=> $this->input->post('tanggal_invoice'),
  					'customer_code'	=>  $this->input->post('id_customer'),
  					'customer' 			=>  $this->input->post('nm_customer'),
  					'bln'				    => date('m'),
  					'thn'				    => date('Y'),
  					'saldo_awal' 		=> $this->input->post('hargajualtotal'), //nilai invoice
  					'debet'				  => 0,
  					'kredit'			  => 0,
  					'saldo_akhir'		=> $this->input->post('hargajualtotal'), //nilai invoice
  					'kdcab'				  => $session['kdcab']
  					);

  				//echo"<pre> DETAIL INV :<br> ";print_r($Arr_detail);echo "<br>HEADER INV :<br>";print_r($headerinv);echo "<br>DATA AR :<br>";print_r($dataAR);exit;

  				$Kode_Proses			   = implode("','",$detail_do);

  				$Qry_Update_DO			 = "UPDATE trans_do_header SET status='INV',no_invoice='$no_invoice' WHERE no_do IN ('".$Kode_Proses."')";
  				$Qry_Update_Cabang	 = "UPDATE cabang SET no_invoice=no_invoice + 1 WHERE kdcab='".$session['kdcab']."'";
  				$this->db->trans_begin();
  				$this->db->query($Qry_Update_Cabang);
  				$this->db->query($Qry_Update_DO);
  				if($this->input->post('ppn') > 0){
  					$this->db->update('faktur_detail',$Arr_Update,array('idgen'=>$Kode_Gen,'fakturid'=>$fakturid));
  				}
  				$this->db->insert_batch('trans_invoice_detail',$Arr_detail);
  				$this->db->insert('trans_invoice_header',$headerinv);
  				$this->db->insert('ar',$dataAR);
  				if($this->db->trans_status() === FALSE){
  					 $this->db->trans_rollback();
  					 $Arr_Return		= array(
  							'status'		=> 2,
  							 'pesan'		=> 'Gagal simpan data'
  					   );
  				}else{
  					 $this->db->trans_commit();
  					 $Arr_Return		= array(
  						'status'		=> 1,
  						 'pesan'			=> 'Sukses simpan data'
  				   );
  				}
  			}else{
  				$Arr_Return		= array(
  					'status'		=> 2,
  					'pesan'			=> $faktur_data['pesan']
  			   );
  			}
  		}else{
  			$Arr_Return		= array(
  				'status'		=> 3,
  				'pesan'			=> 'No Record Was Found To Process. Please Try Again...'
  		   );
  		}

          echo json_encode($Arr_Return);


    }

    function print_request($noinv){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('header', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);

        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    function print_custom_invoice(){
      $mpdf=new mPDF('utf-8', array(210,145), 10 ,'Arial', 5, 5, 16, 16, 1, 4, 'P');
      $mpdf->SetImportUse();

        $noinv = $this->input->get('noinv');
        $cusdiskon = $this->input->get('diskon');

        $inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('inv_data', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $this->template->set('set_custom', $cusdiskon);

        $show = $this->template->load_view('print_data_custom',$data);

        $tglprint = date("d-m-Y H:i:s");
        $header = '
        <div style="display: inline-block; position:relative;width:100%;display: none;">
          <div style="width:25%">
            <img src="assets/img/logo.JPG">
          </div>
          <div style="position: absolute;text-align: center;margin-left:15% !important" width="75%">INVOICE (FAKTUR)<br>NO. : '.$inv_data->no_invoice.'</div>
        </div>
        	<table width="100%" border="0" id="header-tabel">
  	      	<tr>
  	      		<th width="30%" style="text-align: left;">
  	      			<img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
  	      		</th>
  	      		<th colspan="4" style="border-right: none;text-align: center;padding:0 !important;margin:0px !important" width="75%">INVOICE (FAKTUR)<br>NO. : '.@$inv_data->no_invoice.'</th>
              	<th colspan="3" style="border-left: none;"></th>
  	      	</tr>
        	</table>
          <table width="100%" border="0" id="header-tabel">
          <!--tr>
              <th colspan="1">
                <img src="assets/img/logo.JPG" width="25%">
              </th>

              <th colspan="2" style="border-right: none;text-align: center;padding:0 !important;margin:0px !important" width="75%">INVOICE (FAKTUR)<br>NO. : '.@$inv_data->no_invoice.'</th>
              <th colspan="3" style="border-left: none;"></th>
          </tr-->
          <tr>
              <td width="5%">NO. SO</td>

              <td colspan="3" width="50%">:</td>
              <td width="15%">Yogyakarta</td>
              <td width="1%">,</td>
              <td>'.date('d/m/Y',strtotime(@$inv_data->tanggal_invoice)).'</td>
          </tr>
          <tr>
              <td width="5%">SALES</td>

              <td colspan="3">: '. @$inv_data->nm_salesman.'</td>
              <td width="15%">Kepada Yth,</td>
              <td width="1%"></td>
              <td></td>
          </tr>
          <tr>
              <td width="5%">TOP</td>

              <td colspan="3">:
                  45 HARI  &nbsp;&nbsp;&nbsp; TGL JATUH TEMPO : '.date('d/m/Y',strtotime(@$inv_data->tgljatuhtempo)).'
              </td>
              <td width="15%" colspan="3" style="font-size:9pt !important;">
                  '.@$inv_data->nm_customer.'
              </td>
          </tr>
          <tr>
              <td width="5%">KETERANGAN</td>

              <td colspan="3">:</td>
              <td width="15%" colspan="3" style="font-size:9pt !important;">
                  '.@$inv_data->alamatcustomer.'
              </td>
          </tr>
      </table>';

        $this->mpdf->SetHTMLHeader($header,'0',true);
        $session = $this->session->userdata('app_session');
        $total_nominal = @$inv_data->hargajualafterdis;
        $diskon_stdr_persen = '-';
        $diskon_stdr_rp = 0;
        $diskon_toko_persen = '-';
        $diskon_toko_rp = 0;
        $diskon_cash_persen = '-';
        $grand_total_view = @$inv_data->hargajualafterdis;
        //$diskon_stdr   = @$inv_data->
        if(@$set_custom != ""){
            $total_nominal = @$inv_data->hargajualbefdis;
            $diskon_stdr_persen = 30;
            $diskon_toko_persen = @$inv_data->diskon_toko_persen;
            $diskon_cash_persen = @$inv_data->diskon_cash_persen;
            $diskon_stdr_rp = @$inv_data->diskon_stdr_rp;
            $diskon_toko_rp = @$inv_data->diskon_toko_rp;
            $grand_total_view = @$inv_data->hargajualbefdis-$diskon_stdr_rp-$diskon_toko_rp;
        }

        $this->mpdf->SetHTMLFooter('
        <hr>
        <table width="100%" border="0" style="font-size:9pt">

            <tr>
                <td colspan="3">
                  <i>TERBILANG : '.ucwords(ynz_terbilang_format($grand_total_view)).'</i>
                </td>
                <td width="15%">JUMLAH NOMINAL</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($total_nominal).'</td>
                <!--<td width="10%"></td>-->

            </tr>
        <tr>
                <td colspan="3"></td>
                <td width="15%">DISKON &nbsp;&nbsp;&nbsp;'.$diskon_stdr_persen.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($diskon_stdr_rp).'</td>
                <!--<td width="10%"></td>-->

            </tr>
            <tr>
                <td colspan="3">
                    <center>Hormat Kami,</center>
                </td>
                <td width="15%">DISKON TOKO &nbsp;&nbsp;&nbsp;'.$diskon_toko_persen.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;"> '.formatnomor($diskon_toko_rp).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3"></td>
                <td width="15%">DISKON CASH &nbsp;&nbsp;&nbsp;'.$diskon_cash_persen.' %</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($ongkir).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3" style="height: 40px;"></td>

                <td width="15%">GRAND TOTAL</td>
                <td width="1%">:</td>
                <td width="15%" style="text-align: right;">'.formatnomor($grand_total_view).'</td>
                <!--<td width="10%"></td>-->
            </tr>
            <tr>
                <td colspan="3">
                    <center>(BM/SPV)</center>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    Catatan : Pembayaran dengan cek/giro dianggap lunas apabila sudah dicairkan.
                </td>
            </tr>
        </table>
        <hr />
        <div id="footer">
        <table>
            <tr><td>PT IMPORTA JAYA ABADI - Printed By '.ucwords($userData->nm_lengkap).' On '.$tglprint.'</td></tr>
        </table>
        </div>');
        $this->mpdf->AddPageByArray([
                'orientation' => 'P',
                'sheet-size'=> [210,148],
                'margin-top' => 45,
                'margin-bottom' => 40,
                'margin-left' => 5,
                'margin-right' => 10,
                'margin-header' => 1,
                'margin-footer' => 0,
            ]);
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }
    function print_custom_invoice_1(){

        $noinv = $this->input->get('noinv');
        $cusdiskon = $this->input->get('diskon');

        $inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
        //$customer = $this->Invoice_model->cek_data(array('id_customer'=>$inv_data->id_customer),'customer');
        $detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

        $this->template->set('header', $inv_data);
        //$this->template->set('customer', $customer);
        $this->template->set('detail', $detail);
        $this->template->set('set_custom', $cusdiskon);

        $this->template->load_view('print_data_custom');


    }

	 function cancel_invoice($noinv=''){
		 if($this->input->post()){
			 //echo"<pre>";print_r($this->input->post());exit;
			 $No_Inv		= $this->input->post('no_invoice');
			 $Alasan		= $this->input->post('cancel_reason');
			 $Tgl_Batal		= date('Y-m-d H:i:s');
			 $session 		= $this->session->userdata('app_session');
			 $Faktur		= $this->input->post('faktur_pajak');
			 $Arr_Faktur	= array();

			 $Cek_Faktur	= $this->db->query("SELECT * FROM faktur_detail WHERE noinvoice='".$No_Inv."'")->num_rows();
			 $Upd_Invoice	= array(
				'hargajualbefdis'	=> 0,
				'diskontotal'		=> 0,
				'dpp'				=> 0,
				'ppn'				=> 0,
				'meterai'			=> 0,
				'hargajualtotal'	=> 0,
				'flag_cancel'		=> 'Y',
				'cancel_reason'		=> $Alasan,
				'cancel_date'		=> $Tgl_Batal,
				'cancel_by'			=> $session['username']
			 );

			 $Upd_Delivery	= array(
				'status'		=> 'DO',
				'no_invoice'	=> ''
			 );
			 $Upd_Detail	= array(
				'hargajual'	=> 0,
				'diskon'	=> 0,
				'ppn'		=> 0
			 );
			 if($Cek_Faktur > 0 && $Faktur=='O'){
				 $Upd_Invoice['nofakturpajak']	='';
				 $Arr_Faktur			= array(
					'sts'				=> '0',
					'nofaktur'			=> '',
					'tglfaktur'			=> '',
					'tglinvoice'		=> '',
					'noinvoice'			=> '',
					'tanggal_generate'	=> ''
				);
			 }
			$this->db->trans_begin();
			if($Arr_Faktur){
				$this->db->update('faktur_detail',$Arr_Faktur,array('noinvoice'=>$No_Inv));
			}
			//update Delivery
			$this->db->update('trans_do_header',$Upd_Delivery,array('no_invoice'=>$No_Inv));

			// Update Detail
			$this->db->update('trans_invoice_detail',$Upd_Detail,array('no_invoice'=>$No_Inv));

			// Update Header
			$this->db->update('trans_invoice_header',$Upd_Invoice,array('no_invoice'=>$No_Inv));


			if($this->db->trans_status() === FALSE){
				 $this->db->trans_rollback();
				 $Arr_Return		= array(
						'status'		=> 2,
						'pesan'			=> 'Cancel Process Failed. Please Try Again...'
				   );
			}else{
				 $this->db->trans_commit();
				 $Arr_Return		= array(
					'status'		=> 1,
					'pesan'			=> 'Cancel Process Success. Thank You & Have A Nice Day...'
			   );
			}
			echo json_encode($Arr_Return);
		 }else{
			$inv_data = $this->Invoice_model->find_data('trans_invoice_header',$noinv,'no_invoice');
			$detail = $this->Detailinvoice_model->find_all_by(array('no_invoice' => $noinv));

			$this->template->set('row_header', $inv_data);
			$this->template->set('row_detail', $detail);

			$this->template->title('Batal Invoice');
			$this->template->render('cancel');
		 }

    }

}

?>
