<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Piutang_card_model extends CI_Model
{

    public function get_payment_jurnal($Cabang,$invoice)
	{
		$Length			= strlen(trim($Cabang));
		$Query_Jurnal	= "SELECT * FROM jurnal WHERE no_perkiraan='1104-01-01' AND SUBSTRING(nomor,1,$Length)='$Cabang' AND no_reff='$invoice' AND (debet > 0 OR kredit > 0) ORDER BY tanggal ASC";
		$det_Jurnal		= $this->db->query($Query_Jurnal)->result();
		
		return $det_Jurnal;
	}
	
	public function get_data_Cabang(){
		$Arr_Cabang	= array();
		$det_Cabang		= $this->db->get('pastibisa_tb_cabang')->result();
		if($det_Cabang){
			foreach($det_Cabang as $key=>$vals){
				$kode_cab	= $vals->nocab;
				$Cabang		= $vals->cabang;
				$Arr_Cabang[$kode_cab]	= $Cabang;
			}
		}
		return $Arr_Cabang;
	}
	
	public function get_data_customer($kode_cust){
		$det_Customer	= $this->db->get_where('customer',array('id_customer'=>$kode_cust))->result();
		return $det_Customer;
	}

}
