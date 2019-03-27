<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Jurnal_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'japh';
    protected $key        = 'nomor';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'create_on';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'modified_on';

    /**
     * @var bool Set the created time automatically on a new record (if true)
     */
    protected $set_created = true;

    /**
     * @var bool Set the modified time automatically on editing a record (if true)
     */
    protected $set_modified = true;
    /**
     * @var string The type of date/time field used for $created_field and $modified_field.
     * Valid values are 'int', 'datetime', 'date'.
     */
    /**
     * @var bool Enable/Disable soft deletes.
     * If false, the delete() method will perform a delete of that row.
     * If true, the value in $deleted_field will be set to 1.
     */
    protected $soft_deletes = false;

    protected $date_format = 'datetime';

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = true;

    /**
     * Function construct used to load some library, do some actions, etc.
     */
    public function __construct()
    {
        parent::__construct();
    }


	function get_Nomor_Jurnal_Pembelian($Cabang='',$Tgl_Inv=''){
		$nocab			= 'A';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "SELECT subcab,nomorJP FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cab		= $Pros_Cab->result_array();
		if($det_Cab){
			$nocab		= $det_Cab[0]['subcab'];
			$Urut		= intval($det_Cab[0]['nomorJP']) + 1;
		}
		$Format			= $Cabang.'-'.$nocab.'-JP'.date('y',strtotime($Tgl_Inv));

		$Nomor_JP		= $Format.str_pad($Urut, 5, "0", STR_PAD_LEFT);

		return $Nomor_JP;
	}

	function get_Nomor_Jurnal_Sales($Cabang='',$Tgl_Inv=''){
		$nocab			= 'A';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "SELECT subcab,nomorJS FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cab		= $Pros_Cab->result_array();
		if($det_Cab){
			$nocab		= $det_Cab[0]['subcab'];
			$Urut		= intval($det_Cab[0]['nomorJS']) + 1;
		}
		$Format			= $Cabang.'-'.$nocab.'-JS'.date('y',strtotime($Tgl_Inv));

		$Nomor_JS		= $Format.str_pad($Urut, 5, "0", STR_PAD_LEFT);

		return $Nomor_JS;
	}

	function get_Nomor_Jurnal_Memorial($Cabang='',$Tgl_Inv=''){
		$nocab			= 'A';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "SELECT subcab,nomorJM FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cab		= $Pros_Cab->result_array();
		if($det_Cab){
			$nocab		= $det_Cab[0]['subcab'];
			$Urut		= intval($det_Cab[0]['nomorJM']) + 1;
		}
		$Format			= $Cabang.'-'.$nocab.'-JM'.date('y',strtotime($Tgl_Inv));

		$Nomor_JM		= $Format.str_pad($Urut, 5, "0", STR_PAD_LEFT);

		return $Nomor_JM;
	}

	function get_Nomor_Jurnal_BUK($Cabang='',$Tgl_Inv='',$tipe='KAS'){
		$nocab			= 'A';
		$kdcab			= 'YOG';
		$prefix			= '';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "SELECT kdcab,subcab FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cab		= $Pros_Cab->result_array();
		if($det_Cab){
			$nocab		= $det_Cab[0]['subcab'];
			$kdcab		= $det_Cab[0]['kdcab'];
		}
		$Query_Kode		= "SELECT prefik,nobuk FROM konter_bumbuk WHERE kdcab='$kdcab' AND LOWER(kasbank)='".strtolower($tipe)."'";
		$Pros_Kode		= $this->db->query($Query_Kode);
		$det_Kode		= $Pros_Kode->result_array();
		if($det_Kode){
			$prefix		= $det_Kode[0]['prefik'];
			$Urut		= intval($det_Kode[0]['nobuk']) + 1;
		}
		$Format			= $Cabang.'-'.$nocab.$prefix.date('y',strtotime($Tgl_Inv));

		$Nomor_BUK		= $Format.str_pad($Urut, 5, "0", STR_PAD_LEFT);

		return $Nomor_BUK;
	}

	function get_Nomor_Jurnal_BUM($Cabang='',$Tgl_Inv=''){
		$nocab			= 'A';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "SELECT subcab,nobum FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cab		= $Pros_Cab->result_array();
		if($det_Cab){
			$nocab		= $det_Cab[0]['subcab'];
			$Urut		= intval($det_Cab[0]['nobum']) + 1;
		}
		$Format			= $Cabang.'-'.$nocab.date('y',strtotime($Tgl_Inv));

		$Nomor_BUM		= $Format.str_pad($Urut, 5, "0", STR_PAD_LEFT);

		return $Nomor_BUM;
	}

	function update_Nomor_Jurnal($Cabang='',$tipe='BUM'){
		if(strtolower($tipe)=='bum'){
			$fields		= "nobum";
		}else if(strtolower($tipe)=='jp'){
			$fields		= "nomorJP";
		}else if(strtolower($tipe)=='jm'){
			$fields		= "nomorJM";
		}else if(strtolower($tipe)=='js'){
			$fields		= "nomorJS";
		}

		$nocab			= 'A';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "UPDATE pastibisa_tb_cabang SET ".$fields."=".$fields." + 1 WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);

	}

	function update_Nomor_Jurnal_BUK($Cabang='',$tipe='KAS'){
		$Query_Cab		= "SELECT kdcab,subcab FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cab		= $Pros_Cab->result_array();
		if($det_Cab){
			$kdcab		= $det_Cab[0]['kdcab'];
		}
		$Query_Kode		= "UPDATE konter_bumbuk SET nobuk=nobuk + 1 WHERE kdcab='$kdcab' AND LOWER(kasbank)='".strtolower($tipe)."'";
		$Pros_Kode		= $this->db->query($Query_Kode);

	}

	function get_COA_Piutang($Cabang){

		$accid			= '1104-01-01';
		$bulan_Proses	= date('Y',strtotime($Tgl_Inv));
		$Urut			= 1;
		$Query_Cab		= "SELECT * FROM pastibisa_tb_cabang WHERE nocab='".$Cabang."'";
		$Pros_Cab		= $this->db->query($Query_Cab);
		$det_Cabang		= $Pros_Cab->result_array();
		if($det_Cab){
			$accid		= $det_Cab[0]['coa_piutang_ho'];
		}
		return $accid;
	}

}
