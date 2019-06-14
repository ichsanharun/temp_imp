<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchaseorder_pusat_model extends BF_Model
{
    public function update_po_header($id, $data)
    {
        $this->db->where('no_po', $id);
        return $this->db->update('trans_po_header', $data);
    }
    
    public function update_po_detail($id, $data)
    {
        $this->db->where('id_detail_po', $id);
        return $this->db->update('trans_po_detail', $data);
    }
    
    public function insert_po_payment($data)
    {
        return $this->db->insert('trans_po_payment', $data);
    }
}