<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Invoice_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'trans_invoice_header';
    protected $key        = 'no_invoice';

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

    function generate_noinv($kdcab){
        $query = "SELECT cabang.no_invoice 
                  FROM 
                  cabang WHERE cabang.kdcab='$kdcab'"; 
        $q = $this->db->query($query);
        $r = $q->row();
        $kode = (int)$r->no_invoice+1; 
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);

        $arr_tgl = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',
                         7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L'
                        );
        $bln_now = date('m');
        $kode_bln = '';
        foreach($arr_tgl as $k=>$v){
            if($k == $bln_now){
                $kode_bln = $v;
            }
        }
        return $kdcab.'-IV-'.date('y').$kode_bln.$next_kode;
    }

    function get_customer($idcus){
        $query="SELECT
                customer.id_customer,
                customer.nm_customer
                FROM customer where id_customer='$idcus'";
        return $this->db->query($query);
    }

    public function cek_data($kunci,$tabel) {
        $this->db->where($kunci);
        $query=$this->db->get($tabel);
        return $query->row();
    }

    function get_where_in($field,$kunci,$tabel){
        $this->db->where_in($field,$kunci);
        $query=$this->db->get($tabel);
        return $query->result();
    }

    public function get_data($kunci,$tabel) {
        $this->db->where($kunci);
        $query=$this->db->get($tabel);
        return $query->result();
    }

	function getFakturMaster(){
		$this->db->where(array('status'=>'0'));
		$query		= $this->db->get('faktur_master');
		$hasil		= $query->result_array();
		$Arr_Master	= array();
		if($hasil){
			foreach($hasil as $key=>$vals){
				$kode_faktur	= $vals['kefak'];
				$Arr_Master[$kode_faktur]	= $kode_faktur.' '.$vals['keterangan'];
			}
			unset($hasil);
		}
		return $Arr_Master;
	}
	
	function getFakturAktif(){
		$Arr_Return = array();
		$this->db->where(array('status'=>'1'));
		$Pros_Header		= $this->db->get('faktur_header');
		$Num_Header			= $Pros_Header->num_rows();
		if($Num_Header > 0){
			$det_Header		= $Pros_Header->result_array();
			$kode_gen		= $det_Header[0]['idgen'];
			$Qry_Faktur		= "SELECT * FROM faktur_detail WHERE idgen='".$kode_gen."' AND sts='0' ORDER BY idfaktur ASC LIMIT 1";
			$Pros_Faktur	= $this->db->query($Qry_Faktur);
			$Num_Faktur		= $Pros_Faktur->num_rows();
			if($Num_Faktur > 0){
				$det_Faktur		= $Pros_Faktur->result_array();
				$Arr_Data		= array(
					'idgen'			=> $kode_gen,
					'no_faktur'		=> $det_Faktur[0]['fakturid']					
				);
				$Arr_Return		= array(
					'hasil'			=> 1,
					'data'			=> $Arr_Data
				);
			}else{
				$Arr_Return		= array(
					'hasil'			=> 2,
					'pesan'			=> 'Empty Active Tax Invoice. Please Upload New Tax Invoice'
				);
			}
		}else{
			$Arr_Return		= array(
				'hasil'			=> 2,
				'pesan'			=> 'Tax Invoice Inactive. Please Check Tax Invoice.'
			);
		}
		return $Arr_Return;
		
	}
	
}
