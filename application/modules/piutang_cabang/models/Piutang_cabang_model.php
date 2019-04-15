<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Piutang_cabang_model extends CI_Model
{

    public function get_Coa_Kas_Bank($Cabang)
	{
		//TEST CABANG
		//$Cabang		= '102';
		$Arr_Coa	= $det_COA	= array();
		$Bulan_Now	= date('n');
		$Tahun_Now	= date('Y');
		$Bulan_Lalu	= 1;
		$Tahun_Lalu	= $Tahun_Now;
		if($Bulan_Now == 1){
		  $Bulan_Lalu	= 12;
		  $Tahun_Lalu	= $Tahun_Now - 1;
		}
		$Kode_Cab		= 'A';
		$Query_Cabang	= $this->db->get_where('pastibisa_tb_cabang',array('nocab'=>$Cabang))->result();
		if($Query_Cabang){
		  $Kode_Cab	= $Query_Cabang[0]->subcab;
		}
		$Cab_Kode		= $Cabang.'-'.$Kode_Cab;
		$Query_Coa	= "SELECT * FROM COA WHERE bln='$Bulan_Now' AND thn='$Tahun_Now' AND `level`='5' AND SUBSTRING(no_perkiraan,1,4) IN ('1101','1102') AND kdcab='$Cab_Kode'";
		$Pros_Coa		= $this->db->query($Query_Coa);
		$Num_Coa		= $Pros_Coa->num_rows();
		if($Num_Coa > 0){
		   $det_COA	= $Pros_Coa->result();
		}else{
		   $Query_Coa	= "SELECT * FROM COA WHERE bln='$Bulan_Lalu' AND thn='$Tahun_Lalu' AND `level`='5' AND SUBSTRING(no_perkiraan,1,4) IN ('1101','1102') AND kdcab='$Cab_Kode'";
		  $Pros_Coa		= $this->db->query($Query_Coa);
		  $Num_Coa		= $Pros_Coa->num_rows();
		  if($Num_Coa > 0){
			  $det_COA	= $Pros_Coa->result();
		  }
		}
		if($det_COA){
			foreach($det_COA as $key=>$vals){
				$Name_Coa		= $vals->no_perkiraan.' - '.$vals->nama;
				$Kode_Coa		= $vals->no_perkiraan;
				$Arr_Coa[$Kode_Coa]	= $Name_Coa;
			}
		}
		
		return $Arr_Coa;
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

}
