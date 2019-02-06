<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profroma_invoice extends Admin_Controller {

	protected $viewPermission   = "Cbm.View";
    protected $addPermission    = "Cbm.Add";
    protected $managePermission = "Cbm.Manage";
    protected $deletePermission = "Cbm.Delete";
	public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_profroma_invoice', 'pi');
        
        $this->template->title('Manage Data PI');
        $this->template->page_icon('fa fa-table');

        date_default_timezone_set("Asia/Bangkok");
        
    }

	public function index()
	{
        $pi= $this->pi->all();
       
        $this->template->set('results', $pi);
        $this->template->title('Data Profroma Invoice');
        $this->template->render('index');
    }

    public function konfrimasi()
    {
        $sup    =$this->uri->segment(3);
        $no     =$this->uri->segment(4);
        $session = $this->session->userdata('app_session');
        $this->db->select('*');
        $this->db->from('trans_po_detail');
        $this->db->where('trans_po_detail.no_po', $no);
        $itembarang  =$this->db->get()->result();
        
        $this->db->select('*');
        $this->db->from('supplier_cbm');
        $this->db->join('cbm', 'cbm.id_cbm = supplier_cbm.id_cbm', 'left');
        $this->db->where('supplier_cbm.id_supplier', $sup);
        $cbm_sup  =$this->db->get()->result();
        
        $pr_hader=$query = $this->db->query("SELECT * FROM `trans_pr_header` where no_pr='$no'")->row();
        $query_pr_tambahan = $this->db->query("SELECT * FROM `trans_pr_tambahan` WHERE no_pr='$no'");
        
        $this->template->set('supplier',$sup);
        $this->template->set('no_pr',$no);
        $this->template->set('pr_tambahan',$query_pr_tambahan);
        $this->template->set('pr_hader',$pr_hader);
        $this->template->set('cbm_sup',$cbm_sup);
        $this->template->set('itembarang',$itembarang);
        $this->template->title('Konfirmasi Invoice');
        $this->template->render('konfirmasi');
    }
    
    public function konfrimasi_save(){
        $session    = $this->session->userdata('app_session');
        $nopo       = $this->input->post('no_pr');
        
        $headerpr   = array(
            'no_po'             => $this->input->post('no_pr'),
            'no_invoice'        => $this->input->post('no_invoice'),
            'start_produksi'    => $this->input->post('start_produksi'),
            'finish_produksi'   => $this->input->post('finish_produksi'),
            'proses_shipping'   => $this->input->post('proses_shipping'),
            'shipping'          => $this->input->post('shipping'),
            'eta'               => $this->input->post('eta'),
            'tgl_invoice'       => date('Y-m-d'),
            'created_on'        => date('Y-m-d H:i:s'),
            'created_by'        => $session['id_user'],
        );
        $this->db->trans_begin();
            $this->pi->insert($headerpr);
            
            $jumlah = count($_POST["idet"]);

            for($i=0; $i < $jumlah; $i++)
            {
                    $detil   = array(
                        'qty_i'             => $_POST["qty_i"][$i],
                    );
                    $iddet=$_POST["idet"][$i];
                    $this->pi->update($iddet,$detil);
               
            } 
            
            $this->db->query("UPDATE `trans_po_header` SET `status` = 'INVOICE' WHERE `trans_po_header`.`no_po` ='$nopo';");
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $param = array(
                'save' => 0,
                'msg' => "GAGAL, perubahan..!!!"
                );
            }
            else
            {
                $this->db->trans_commit();
                $param = array(
                'save' => 1,
                'msg' => "SUKSES, melakukan perubahaan..!!!"
                );
            }
        
        echo json_encode($param);
    }


}
