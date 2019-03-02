<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Customer
 */

class Diskon extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf','upload','Image_lib'));
        $this->load->model(array('Diskon/Diskon_model',
                                 'Aktifitas/aktifitas_model'
                                ));
        $this->template->title('Manage Data Diskon');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
    }

    public function index()
    {
        $data = $this->Diskon_model->order_by('diskon','ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Diskon');
        $this->template->render('list');
    }

    public function create(){
    	$this->template->title('Add New Diskon');
        $this->template->render('diskon_form');
    }

    public function savediskon(){
    	$datainsert = array(
    		'diskon' => $this->input->post('diskon'),
            'persen' => $this->input->post('persen_diskon'),
    		'sts_aktif' => $this->input->post('sts_aktif')
    		);
        if($this->input->post('persen_diskon') > 100){
            $param = array(
                'save' => 0,
                'msg' => "Diskon melebihi 100%."
                );
        }else{
        	$this->db->trans_begin();
        	if(!empty($this->input->post('id_diskon'))){
        		$this->db->where(array('id_diskon'=>$this->input->post('id_diskon')));
        		$this->db->update('diskon',$datainsert);
        	}else{
    	        $this->db->insert('diskon',$datainsert);
    	    }
    	    if ($this->db->trans_status() === FALSE)
    	    {
    	        $this->db->trans_rollback();
    	        $param = array(
    	            'save' => 0,
    	            'msg' => "GAGAL, tambah diskon..!!!"
    	            );
    	    }
    	    else
    	    {
    	       $this->db->trans_commit();
    	         $param = array(
    	          'save' => 1,
    	          'msg' => "SUKSES, simpan data diskon..!!!"
    	          );
    	    }
        }
    	
        echo json_encode($param);
    }

    public function edit(){
    	$id = $this->uri->segment(3);
    	$data = $this->Diskon_model->find_by(array('id_diskon'=>$id));
    	$this->template->set('detail', $data);
    	$this->template->title('Edit New Diskon');
        $this->template->render('diskon_form');
    }

    public function hapus_diskon(){
    $id = $this->input->post('ID');
        if(!empty($id)){
           $result = $this->Diskon_model->delete($id);
           $param['delete'] = 1; 
        }else{
            $param['delete'] = 0;
        }
        echo json_encode($param);
    }
}

?>
