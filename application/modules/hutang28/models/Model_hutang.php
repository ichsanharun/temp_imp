<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_hutang extends CI_Model
{

    public function all($per_page = 0, $offset = 0)
	{
	  //  $this->db->where('status', 'PI');
		if ($per_page !== 0) $this->db->limit($per_page, $offset);
		return $this->db->get('trans_po_header');
	}

	public function insert($data)
	{
		return $this->db->insert('trans_po_invoice', $data);
	}

	public function update($id, $data)
	{
		$this->db->where('id_detail_po', $id);
		return $this->db->update('trans_po_detail', $data);
	}


	
	

}