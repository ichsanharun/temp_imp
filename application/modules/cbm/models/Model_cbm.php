<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_cbm extends CI_Model
{

    public function all($per_page = 0, $offset = 0)
	{
		if ($per_page !== 0) $this->db->limit($per_page, $offset);
		return $this->db->get('cbm');
	}

	public function insert($data)
	{
		return $this->db->insert('cbm', $data);
	}
	
    public function by_id($id)
    {
        $this->db->where('id_cbm', $id);
        return $this->db->get('cbm');
    }

	public function update($id, $data)
	{
		$this->db->where('id_cbm', $id);
		return $this->db->update('cbm', $data);
	}

	public function delete($id)
	{
		$this->db->where('id_cbm', $id);
		return $this->db->delete('cbm');
	}
	
	

}