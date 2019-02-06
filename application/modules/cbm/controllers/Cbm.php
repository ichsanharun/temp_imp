<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cbm extends Admin_Controller {

	protected $viewPermission   = "Cbm.View";
    protected $addPermission    = "Cbm.Add";
    protected $managePermission = "Cbm.Manage";
    protected $deletePermission = "Cbm.Delete";
	public function __construct()
    {
        parent::__construct();
        $this->load->model('model_cbm', 'cbm');
        
        $this->template->title('Manage Data CBM');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
        
    }

	public function index()
	{
        $cbm= $this->cbm->all();
        
        $this->template->set('results', $cbm);
        $this->template->title('CBM');
        $this->template->render('index');
    }

    

    public function save()
    {
        $this->form_validation->set_rules('name_cbm', 'Nama', 'required');
        


        if(!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect_back();
        }

        

        $data = array(
            
            'name_cbm' => $this->input->post('name_cbm'),
            
        );
        $insert = $this->cbm->insert($data);
        $param = array(
                'save' => $insert
                );

        echo json_encode($param);
    }

    public function edit()
    {
        $id = $this->uri->segment(3);
        $cbm= $this->cbm->by_id($id)->row();
       
        $this->template->set('data', $cbm);
        
        $this->template->title('Edit Data CBM');
        $this->template->render('cbm_form');
    }

    public function edit_save()
    {

		$id   =$this->input->post('id');
        $data = array(
            'name_cbm' => $this->input->post('name_cbm'),
        );

        $update = $this->cbm->update($id, $data);
        $param = array(
                'save' => $update
                );

        echo json_encode($param);
    }

    public function delete($id)
    {
        $delete = $this->cbm->delete($id);
        $param = array(
                'delete' => $delete,
                );

        echo json_encode($param);
    }
	
	

}
