<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_invoice_pembelian extends CI_Model
{

    public function all()
	{
	    $this->db->select('*');
        $this->db->from('trans_po_invoice');
        $this->db->join('trans_po_header', 'trans_po_header.no_po = trans_po_invoice.no_po');
		return $this->db->get();
	}

	
}