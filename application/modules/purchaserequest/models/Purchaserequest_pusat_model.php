<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchaserequest_pusat_model extends BF_Model
{
    public function insert_revisi_pr_header($data)
    {
        return $this->db->insert('revisi_pr_header', $data);
    }
    
    public function insert_revisi_pr_detail($data)
    {
        return $this->db->insert('revisi_pr_detail', $data);
    }
    
    public function insert_revisi_pr_tambahan($data)
    {
        return $this->db->insert('revisi_pr_tambahan', $data);
    }
    
    public function insert_po_header($data)
    {
        return $this->db->insert('trans_po_header', $data);
    }
    
    public function insert_po_detail($data)
    {
        return $this->db->insert('trans_po_detail', $data);
    }
    
    public function insert_po_tambahan($data)
    {
        return $this->db->insert('trans_po_tambahan', $data);
    }
}