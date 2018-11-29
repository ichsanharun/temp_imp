<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Forecast extends Admin_Controller 
{
	/**
	 * @author Dando Ridwanto
	 * @copyright Copyright (c) 2018, CodeIgniter
	 * 
	 * This is controller for Forecast
	 */
	public function __construct()
	{
		parent::__construct();              
        $this->template->page_icon('fa fa-dashboard');
	}

	public function index()
	{
		$qry = "SELECT * FROM forecast_header WHERE 1=1";
		$data['view'] = $this->db->query($qry);

		$this->template->title('Forecast');
		$this->template->render('view', $data);
	}


	/**
	* .............................................................................
	* function for create new forecast
	* 2018/September/02
	*/
	public function fc_new()
	{
		$dt['periode'] = date('m-Y');
		$dt['cabang']  = $this->db->where('sts_aktif', 'aktif')->order_by('namacabang')->get('cabang');

		$this->template->title('Forecast');
		$this->template->render('new', $dt);
	}

	/**
	* .............................................................................
	* function for load dataTables by on change cabang
	* 2018/September/02
	*/
	public function fc_get_cabang()
	{
		if(! $this->input->is_ajax_request())
		{
			exit('No direct script access allowed');
		}
		else
		{
			$variable  = "?kdcab=".$this->input->post('kdcab');
			$variable .= "&periode=".$this->input->post('periode');
			echo json_encode(array(
				'status' => 1,
				'link_detail' => site_url('forecast/fc_new_detail/'.$variable)
			));
		}
	}

	/**
	* .............................................................................
	* function for load dataTables by on change cabang
	* 2018/September/02
	*/
	public function fc_new_detail()
	{
		$kdcab 	 = $_GET['kdcab'];
		$periode = $_GET['periode'];

		$sql = "
			SELECT 
				b.kdcab,
				a.id_barang,
				a.nm_barang,
				b.qty_forecast,
				b.qty_leadtime_produksi AS wkt_produksi,
				b.qty_leadtime_pengiriman AS wkt_pengiriman,
				b.safety_stock,
				b.qty_safety_stock,
				(b.qty_leadtime_produksi + b.qty_leadtime_pengiriman + b.safety_stock) AS wkt_order_point,
				-- (
				-- 	b.qty_forecast * (b.qty_leadtime_produksi + b.qty_leadtime_pengiriman + b.safety_stock)
				-- ) AS qty_reorder_point,
				b.qty_stock 
			FROM 
				barang_master AS a  
				INNER JOIN barang_stock AS b ON a.id_barang = b.id_barang
			WHERE 1=1
				AND b.kdcab = '".$kdcab."'
			";
		$data['forecast'] = $this->db->query($sql);
		$data['periode']  = $periode;

		$this->template->render('new_detail', $data);
	}

	/*
	* ----------------------------------------------------------------------
	* function for Save New ForeCast
	* 04-September-2018
	*/
	public function fc_new_save()
	{
		if(! $this->input->is_ajax_request())
		{
			exit('No direct script access allowed.');
		}
		else
		{
			if($_POST)
			{
				if(! empty($_POST['set_forecast']))
	            {
	                $total = 0;
	                foreach($_POST['set_forecast'] as $k)
	                {
	                    if( ! empty($k))
	                    { 
	                        $total++; 
	                    }
	                }

	                if($total > 0)
	                {
	                    $this->load->library('form_validation');
	                    $no = 0;
	                    foreach($_POST['set_forecast'] as $i)
	                    {
	                        $this->form_validation->set_rules('set_forecast['.$no.']','Set Forecast #'.($no + 1), 'trim|required');
	                        $this->form_validation->set_rules('wkt_produksi['.$no.']','Waktu Produksi #'.($no + 1), 'trim|required');
	                        $this->form_validation->set_rules('wkt_pengiriman['.$no.']','Waktu Pengiriman #'.($no + 1), 'trim|required');
	                        $this->form_validation->set_rules('safety_stock['.$no.']','Safety Stock #'.($no + 1), 'trim|required');
	                        $no++;
	                    }
	                    // $this->form_validation->set_rules('paket', 'Paket', 'trim|required');
	                    $this->form_validation->set_message('required', '%s harus diisi.');
	                    if($this->form_validation->run() == TRUE)
	                    {
	                    	$cek_header = $this->db->where('fc_date', date('Y-m'))->limit(1)->get('forecast_header');
	                    	if($cek_header->num_rows() > 0){
	                    		echo json_encode(array(
	                    			'status' => 3,
	                    			'pesan'  => '<i class="fa fa-remove"></i> Data Forecast Bulan Ini Sudah Ada'
	                    		));
	                    	}
	                    	else
	                    	{
	                            $datet    = date('Y-m-d');
	                            $terinput = 0;

	                            ### /////////////////////////////////////////////////////////////////////
	                            ### GET UNIQUE CODE FOR PRIMARY KEY
	                            $Max   = "SELECT fc_id FROM forecast_header WHERE fc_date LIKE '".DATE('Y-')."%' ORDER BY fc_id DESC LIMIT 1";
	                            $Max   = $this->db->query($Max);
	                            $nilai = 1;
	                            if($Max->num_rows() > 0){
	                                $nilai = intval(substr($Max->row()->fc_id, -5)) + 1;
	                            }
	                            $kode  = 'FC-'.date('Y').'-'.sprintf('%05d', $nilai);

	                            ## ..............................
	                            ## I. INSERT FORECAST HEADER
	                            ## ''''''''''''''''''''''''''''''
	                            $DataHeader['fc_id']	 	= $kode;
	                            $DataHeader['fc_date']	 	= date('Y-m');
	                            $DataHeader['fc_kdcab']	 	= $_POST['kdcab'];
	                            $DataHeader['fc_status']	= 'OPN';
	                            $DataHeader['insert_by']	= $this->session->userdata('app_session')['nm_lengkap'];
	                            $DataHeader['insert_date']	= date('Y-m-d H:i:s');
	                            foreach($_POST['set_forecast'] as $key => $value)
	                            {
	                                if( ! empty($value))
	                                {
	                                    ## ..............................
	                                    ## II. INSERT FORECAST DETAIL
	                                    ## ''''''''''''''''''''''''''''''
	                                    $Detail['fc_id_header']     = $kode;
	                                    $Detail['id_barang']     	= $_POST['id_barang'][$key];
	                                    $Detail['nm_barang']     	= $_POST['nm_barang'][$key];
	                                    $Detail['qty_demand_m4']	= $_POST['qty_demand_m4'][$key];
	                                    $Detail['qty_demand_m3']	= $_POST['qty_demand_m3'][$key];
	                                    $Detail['qty_demand_m2']	= $_POST['qty_demand_m2'][$key];
	                                    $Detail['qty_demand_m1']	= $_POST['qty_demand_m1'][$key];
	                                    $Detail['forecast_m1']		= $_POST['forecast_m1'][$key];
	                                    $Detail['set_forecast']     = $value;
	                                    $Detail['waktu_produksi']	= $_POST['wkt_produksi'][$key];
	                                    $Detail['waktu_pengiriman']	= $_POST['wkt_pengiriman'][$key];
	                                    $Detail['safety_stock']		= $_POST['safety_stock'][$key];
	                                    $Detail['qty_safety_stock']	= $_POST['qty_safety_stock'][$key];
	                                    $Detail['wkt_order_point']	= $_POST['wkt_order_point'][$key];
	                                    $Detail['qty_reorder_point']= $_POST['qty_reorder_point'][$key];
	                                    $Detail['qty_stock']		= $_POST['qty_stock'][$key];
	                                    $Detail['created_by']       = $this->session->userdata('app_session')['nm_lengkap'];
	                                    $Detail['created_on']  		= date('Y-m-d H:i:s');
	                                    $insert_db = $this->db->insert('forecast_detail', $Detail);
	                                    if($insert_db)
	                                    {
	                                        $terinput++;
	                                    }
	                                }
	                            }

								$this->db->trans_start();
								$this->db->insert('forecast_header', $DataHeader);
								$this->db->trans_complete();
								if ($this->db->trans_status() === FALSE){
									$this->db->trans_rollback();
								}else{
									$this->db->trans_commit();
									$terinput++;
								}

	                            if($terinput > 0){
	                                echo json_encode(array(
										'status' => 1,
										'pesan' => 'Data Successfully Saved',
										'redirect_page' => 'YES',
										'redirect_page_URL' => site_url('forecast')
									));
	                            }else{
	                                echo json_encode(array('status' => 2, 'pesan' => 'Terjadi kesalahan, coba lagi'));
	                            }

	                            /*$header = $this->db->insert('forecast_header', $DataHeader);
	                            if($header){
	                                $terinput++;
	                            }*/
	                        }
	                    }
	                    else
	                    {
	                        echo json_encode(array(
								'status' => 0, 
								'pesan' => validation_errors("<p class='error_input'><i class='fa fa-times-circle'></i> ", "</p>")
							));
	                    }
	                }
	                else
	                {
	                    echo json_encode(array('status' => 2, 'pesan' => 'Set Forecast Tidak boleh kosong'));
	                }
	            }
	            else
	            {
	                echo json_encode(array('status' => 2, 'pesan' => 'Set Forecast Tidak boleh kosong'));
	            }
			}
		}
	}



	/*
	* ----------------------------------------------------------------------
	* function for edit data forecast
	* 04-September-2018
	*/
	public function fc_edit($fc_id=NULL, $periode=NULL)
	{
		if($_POST)
		{
			if(! empty($_POST['set_forecast']))
            {
                $total = 0;
                foreach($_POST['set_forecast'] as $k)
                {
                    if( ! empty($k))
                    {
                        $total++; 
                    }
                }

                if($total > 0)
                {
                    $this->load->library('form_validation');
                    $no = 0;
                    foreach($_POST['set_forecast'] as $i)
                    {
                        $this->form_validation->set_rules('set_forecast['.$no.']','Set Forecast #'.($no + 1), 'trim|required');
                        $this->form_validation->set_rules('wkt_produksi['.$no.']','Waktu Produksi #'.($no + 1), 'trim|required');
                        $this->form_validation->set_rules('wkt_pengiriman['.$no.']','Waktu Pengiriman #'.($no + 1), 'trim|required');
                        $this->form_validation->set_rules('safety_stock['.$no.']','Safety Stock #'.($no + 1), 'trim|required');
                        $no++;
                    }
                    // $this->form_validation->set_rules('paket', 'Paket', 'trim|required');
                    $this->form_validation->set_message('required', '%s harus diisi.');
                    if($this->form_validation->run() == TRUE)
                    {
                        $terinput = 0;

                        ## ..............................
                        ## I. UPDATE FORECAST HEADER
                        ## ''''''''''''''''''''''''''''''
                        $DataHeader['fc_date']	 	= $periode;
                        $DataHeader['fc_status']	= 'OPN';
                        $DataHeader['insert_by']	= $this->session->userdata('app_session')['nm_lengkap'];
                        $DataHeader['insert_date']	= date('Y-m-d H:i:s');
                        foreach($_POST['set_forecast'] as $key => $value)
                        {
                            if( ! empty($value))
                            {
                                ## ..............................
                                ## II. UPDATE FORECAST DETAIL
                                ## ''''''''''''''''''''''''''''''
                                // $Detail['id_barang']     	= $_POST['id_barang'][$key];
                                // $Detail['nm_barang']     	= $_POST['nm_barang'][$key];
                                // $Detail['qty_demand_m4']	= $_POST['qty_demand_m4'][$key];
                                // $Detail['qty_demand_m3']	= $_POST['qty_demand_m3'][$key];
                                // $Detail['qty_demand_m2']	= $_POST['qty_demand_m2'][$key];
                                // $Detail['qty_demand_m1']	= $_POST['qty_demand_m1'][$key];
                                // $Detail['forecast_m1']		= $_POST['forecast_m1'][$key];
                                $Detail['set_forecast']     = $value;
                                $Detail['waktu_produksi']	= $_POST['wkt_produksi'][$key];
                                $Detail['waktu_pengiriman']	= $_POST['wkt_pengiriman'][$key];
                                $Detail['safety_stock']		= $_POST['safety_stock'][$key];
                                $Detail['qty_safety_stock']	= $_POST['qty_safety_stock'][$key];
                                $Detail['wkt_order_point']	= $_POST['wkt_order_point'][$key];
                                $Detail['qty_reorder_point']= $_POST['qty_reorder_point'][$key];
                                $Detail['modified_by']      = $this->session->userdata('app_session')['nm_lengkap'];
                                $Detail['modified_on']  	= date('Y-m-d H:i:s');
                                $insert_db = $this->db->where('fc_id_detail', $_POST['fc_id_detail'][$key])->update('forecast_detail', $Detail);
                                if($insert_db)
                                {
                                    $terinput++;
                                }
                            }
                        }

						$this->db->trans_start();
						$this->db->where('fc_id', $fc_id)->update('forecast_header', $DataHeader);
						$this->db->trans_complete();
						if ($this->db->trans_status() === FALSE){
							$this->db->trans_rollback();
						}else{
							$this->db->trans_commit();
							$terinput++;
						}

                        if($terinput > 0){
                            echo json_encode(array(
								'status' => 1,
								'pesan' => 'Data Successfully Updated',
								'redirect_page' => 'YES',
								'redirect_page_URL' => site_url('forecast')
							));
                        }else{
                            echo json_encode(array('status' => 2, 'pesan' => 'Terjadi kesalahan, coba lagi'));
                        }
                    }
                    else
                    {
                        echo json_encode(array(
							'status' => 0, 
							'pesan' => validation_errors("<p class='error_input'><i class='fa fa-times-circle'></i> ", "</p>")
						));
                    }
                }
                else
                {
                    echo json_encode(array('status' => 2, 'pesan' => 'Set Forecast Tidak boleh kosong'));
                }
            }
            else
            {
                echo json_encode(array('status' => 2, 'pesan' => 'Set Forecast Tidak boleh kosong'));
            }
		}
		else
		{
			$sql = "
				SELECT
					a.fc_kdcab AS kdcab,
					b.* 
				FROM 
					forecast_header AS a 
					LEFT JOIN forecast_detail AS b ON a.fc_id = b.fc_id_header
				WHERE 1=1
					AND a.fc_id = '".urldecode($fc_id)."'
				";
			$dt['forecast']	= $this->db->query($sql);
			$dt['fc_id']  	= $fc_id;
			$dt['periode']  = $periode;
			$dt['kdcab']	= $this->db->query("
				SELECT 
					CASE 
						WHEN fc_kdcab = '101' THEN 'YOGYAKARTA'
						WHEN fc_kdcab = '102' THEN 'SEMARANG'
						WHEN fc_kdcab = '103' THEN 'JAKARTA'
						WHEN fc_kdcab = '111' THEN 'BANDUNG'
					ELSE '' END AS cabang
				FROM forecast_header WHERE fc_id = '".$fc_id."' LIMIT 1
			")->row();

			$this->template->title('Forecast');
			$this->template->render('edit', $dt);
		}
	}

	/*
	* ----------------------------------------------------------------------
	* function for Delete ForeCast
	* 05-September-2018
	*/
	public function fc_remove($fc_id)
	{
		if(! $this->input->is_ajax_request())
		{
			exit('No direct script access allowed.');
		}
		else
		{
			$delete = $this->db->where('fc_id', $fc_id)->delete('forecast_header');
			if($delete){
				$this->db->where('fc_id_header', $fc_id)->delete('forecast_detail');
				echo json_encode(array(
					'status' => 1,
					'pesan' => 'Data Successfully Remove',
					'refresh_page' => site_url('forecast')
				));
			}
		}
	}
}