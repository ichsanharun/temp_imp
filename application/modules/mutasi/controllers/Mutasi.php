<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Salesorder
 */

class Mutasi extends Admin_Controller {

    //Permission

    protected $viewPermission   = "Mutasi.View";
    protected $addPermission    = "Mutasi.Add";
    protected $managePermission = "Mutasi.Manage";
    protected $deletePermission = "Mutasi.Delete";

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Mpdf','upload','Image_lib'));
		

        $this->load->model(array('Mutasi/Mutasi_model',
                                 'Mutasi/Detailmutasi_model',
                                 'Cabang/Cabang_model',
                                 'Aktifitas/aktifitas_model'
                                ));

        $this->template->title('Mutasi Produk');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        
		$session 			= $this->session->userdata('app_session');
		$Cabang_User		= $session['kdcab'];
		$det_Cabang			= $this->db->get('pastibisa_tb_cabang')->result();
		$Arr_Cabang			= array();
		if($det_Cabang){
			foreach($det_Cabang as $key=>$vals){
				$kode_cab				= $vals->nocab;
				$Arr_Cabang[$kode_cab]	= $kode_cab;
			}
		}
		$this->template->set('Arr_Cabang', $Arr_Cabang);
        $this->template->set('cabs_user', $Cabang_User);
        $this->template->title('Data Mutasi Produk');
        $this->template->render('list');
    }
	
	function display_data_json(){
		include APPPATH.'libraries/ssp.class.php';
		include APPPATH.'helpers/extend_helper.php';
		$session 			= $this->session->userdata('app_session');
		$Cabang_User		= $session['kdcab'];
		$det_Akses			= akses_server_side();
		$table 				= 'trans_mutasi_header';
		$primaryKey 		= 'no_mutasi';
		$WHERE				="";
		if($Cabang_User !='100'){
			$WHERE				= "(kdcab_asal='$Cabang_User' OR kdcab_tujuan='$Cabang_User')";
		}
		
		
		$columns = array(
			array( 'db' => 'no_mutasi', 'dt' => 'no_mutasi'),
			array( 'db' => 'kdcab_tujuan', 'dt' => 'kdcab_tujuan'),
			array( 'db' => 'cabang_tujuan', 'dt' => 'cabang_tujuan'),				
			array('db' => 'kdcab_asal','dt' => 'kdcab_asal'),
			array( 'db' => 'cabang_asal', 'dt' => 'cabang_asal'),
			array( 'db' => 'id_supir', 'dt' => 'id_supir'),
			array( 'db' => 'nm_supir', 'dt' => 'nm_supir'),
			array( 'db' => 'id_kendaraan', 'dt' => 'id_kendaraan'),
			array( 'db' => 'ket_kendaraan', 'dt' => 'ket_kendaraan'),
			array( 'db' => 'status_mutasi', 'dt' => 'status_mutasi'),
			array( 
				'db' => 'tgl_mutasi', 
				'dt'=> 'tgl_mutasi',
				'formatter' => function($d,$row){
					return date('d F Y',strtotime($d));
				}
			),
			array( 
				'db' => 'no_mutasi', 
				'dt'=> 'action',
				'formatter' => function($d,$row){
					return '';
				}
			)
		);
		$sql_details = array(
			'user' => $det_Akses['hostuser'],
			'pass' => $det_Akses['hostpass'],
			'db'   => $det_Akses['hostdb'],
			'host' => $det_Akses['hostname']
		);
		
		
		
		echo json_encode(
			SSP::complex ( $_POST, $sql_details, $table, $primaryKey, $columns, null, $WHERE )
		);
	}

    public function create()
    {
        
		$session 			= $this->session->userdata('app_session');
		$Cabang_User		= $session['kdcab'];
		$det_Cabang			= $this->db->get_where('pastibisa_tb_cabang',array('nocab !='=>'100'))->result();
		$Arr_Cabang			= array();
		if($det_Cabang){
			foreach($det_Cabang as $key=>$vals){
				$kode_cab				= $vals->nocab;
				$Arr_Cabang[$kode_cab]	= $vals->cabang;
			}
		}
		$stok_cabang	= array();
		if($Cabang_User !='100'){
			$stok_cabang 	= $this->Mutasi_model->get_data(array('kdcab'=>$Cabang_User,'qty_avl >'=>0),'barang_stock');						
		}
       
        
       
       
        
      
		$this->template->set('Arr_Cabang', $Arr_Cabang);
        $this->template->set('cabs_user', $Cabang_User);
        $this->template->set('stok_cabang',$stok_cabang);
        $this->template->title('Create Mutasi Produk');
        $this->template->render('mutasi_form');
    }
	
	function get_stock_item(){
		$Cabang			= $this->input->post('cabang');
		$stok_cabang 	= $this->Mutasi_model->get_data(array('kdcab'=>$Cabang,'qty_avl >'=>0),'barang_stock');
		$data			= array(
			'rows_data'		=> $stok_cabang
		);
		//echo"<pre>";print_r($stok_cabang);exit;
		$this->template->set('rows_data', $stok_cabang);
		$this->template->render('list_stock');
	}
	function get_Driver($cabang=''){
		$driver 	= $this->Mutasi_model->pilih_driver($cabang)->result();
		$Arr_Driver	= array();
		if($driver){
			foreach($driver as $keyD=>$valD){
				$Kode_Driver		= $valD->id_karyawan;
				$Name_Driver		= $valD->nama_karyawan;
				$Arr_Driver[$Kode_Driver]	= $Name_Driver;
			}
			unset($driver);
		}
		echo json_encode($Arr_Driver);
	}
	function get_Kendaraan($cabang=''){
		$Arr_Kendaraan 	= array();
		$kendaraan 		= $this->Mutasi_model->pilih_kendaraan($cabang)->result();
		if($kendaraan){
			foreach($kendaraan as $keyK=>$valK){
				$Id_kendaraan        			= $valK->id_kendaraan;
				$Nama_kendaraan        			= $valK->nm_kendaraan;
				$Arr_Kendaraan[$Id_kendaraan]   = $Nama_kendaraan;
			}
			unset($kendaraan);
		}
		echo json_encode($Arr_Kendaraan);
	}
	
    public function savemutasi(){
        $session = $this->session->userdata('app_session');
        $cabang_asal = explode('|',$this->input->post('cabang_asal'));
        $cabang_tujuan = explode('|',$this->input->post('cabang_tujuan'));
        $supir = explode('|',$this->input->post('supir_mutasi'));
        $mobil = explode('|',$this->input->post('kendaraan_mutasi'));
        $Id_supir = $supir[0];
        $Nm_supir = $supir[1];
        $Id_kendaraan = $mobil[0];
        $Ket_kendaraan = $mobil[1];

        $Kode_Driver    = $Name_Driver  ='-';
  		  $Kode_Kendaraan	= $Nama_Kendaraan	='-';
  		  $Tipe_Kirim		= $this->input->post('tipekirim');
  		  if(strtolower($Tipe_Kirim)=='sendiri'){
        			$Pecah_Driver	= explode('^_^',$this->input->post('supir_do'));
        			$Kode_Driver	= $Pecah_Driver[0];
        			$Name_Driver	= $Pecah_Driver[1];

              $Pecah_Kendaraan   = explode('^_^',$this->input->post('kendaraan_do'));
              $Kode_Kendaraan    = $Pecah_Kendaraan[0];
              $Nama_Kendaraan    = $Pecah_Kendaraan[1];
  		  }else{
              $Name_Driver    = strtoupper($this->input->post('supir_do'));
  			      $Nama_Kendaraan	= strtoupper($this->input->post('kendaraan_do'));
  		  }

        $dataheader = array(
            'no_mutasi'         => $this->Mutasi_model->generate_no_mutasi($session['kdcab']),
            'tgl_mutasi'        => date('Y-m-d'),
            'kdcab_asal'        => $cabang_asal[0],
            'cabang_asal'       => $cabang_asal[1],
            'kdcab_tujuan'      => $cabang_tujuan[0],
            'cabang_tujuan'     => $cabang_tujuan[1],
            'id_supir'          => $Kode_Driver,
            'nm_supir'          => $Name_Driver,
            'id_kendaraan'      => $Kode_Kendaraan,
            'ket_kendaraan'     => $Nama_Kendaraan,
            //'nm_helper'         => $this->input->post('helper_do'),
            'status_mutasi'     => 'IT',
            'created_on'        => date('Y-m-d H:i:s'),
            'created_by'        => $session['id_user']
            );
        $this->db->trans_begin();
        for($i=0;$i < count($this->input->post('kode_produk'));$i++){
            $datadetail = array(
                'no_mutasi'     => $this->Mutasi_model->generate_no_mutasi($session['kdcab']),
                'id_barang'     => $this->input->post('kode_produk')[$i],
                'nm_barang'     => $this->input->post('nama_produk')[$i],
                'qty_mutasi'    => $this->input->post('qty_mutasi')[$i],
                'created_on'    => date('Y-m-d H:i:s'),
                'created_by'    => $session['id_user']
                );
             $this->db->insert('trans_mutasi_detail',$datadetail);
             //Update QTY_AVL
             $keycek = array('kdcab'=>$session['kdcab'],'id_barang'=>$this->input->post('kode_produk')[$i]);
             $stok_avl = $this->Mutasi_model->cek_data($keycek,'barang_stock');
             $this->db->where($keycek);
             $this->db->update('barang_stock',array('qty_avl'=>$stok_avl->qty_avl-$this->input->post('qty_mutasi')[$i]));
             //Update QTY_AVL
        }
        //Update counter NO_MUTASI
        $count = $this->Mutasi_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
        $this->db->where(array('kdcab'=>$session['kdcab']));
        $this->db->update('cabang',array('no_mutasi'=>$count->no_mutasi+1));
        //Update counter NO_MUTASI
        $this->db->insert('trans_mutasi_header',$dataheader);
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
	function view_data($kode=''){
		$header = $this->Mutasi_model->find_data('trans_mutasi_header',$kode,'no_mutasi');
		$detail = $this->Detailmutasi_model->find_all_by(array('no_mutasi' => $kode));

		$this->template->set('header', $header);
		$this->template->set('detail', $detail);
		$this->template->title('View Mutasi Produk');
		$this->template->render('view_detail');
	}
    function print_request($mutasi){
        $mpdf=new mPDF('','','','','','','','','','');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $header = $this->Mutasi_model->find_data('trans_mutasi_header',$mutasi,'no_mutasi');
        $detail = $this->Detailmutasi_model->find_all_by(array('no_mutasi' => $mutasi));

        $this->template->set('header', $header);
        $this->template->set('detail', $detail);

        $show = $this->template->load_view('print_data',$data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

}

?>
