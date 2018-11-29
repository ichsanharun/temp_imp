<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Purchaserequest_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'trans_pr_header';
    protected $key        = 'no_pr';

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
    function pilih_item($kdcab){
        $query="SELECT
                barang_stock.id_barang,
                barang_stock.nm_barang,
                barang_stock.harga,
                barang_stock.satuan,
                barang_stock.qty_stock,
                barang_stock.qty_avl
                FROM
                barang_stock WHERE kdcab='$kdcab'";
        return $this->db->query($query);
    }

    function get_item_barang($idbarang){
        $query="SELECT
                barang_stock.id_barang,
                barang_stock.nm_barang,
                barang_stock.satuan,
                barang_stock.jenis,
                barang_stock.harga,
                barang_stock.qty_stock,
                barang_stock.qty_avl
                FROM
                barang_stock where id_barang='$idbarang'";
        return $this->db->query($query);
    }

    function generate_nopr($kdcab){
        $query = "SELECT cabang.no_pr
                  FROM
                  cabang WHERE cabang.kdcab='$kdcab'";
        $q = $this->db->query($query);
        $r = $q->row();
        $kode = (int)$r->no_pr+1;
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
        return $kdcab.'-PR-'.date('y').$kode_bln.$next_kode;
    }

    function get_supplier($idsup){
        $query="SELECT
                supplier.id_supplier,
                supplier.nm_supplier,
                supplier.id_negara,
                supplier.alamat
                FROM supplier where id_supplier='$idsup'";
        return $this->db->query($query);
    }

    public function cek_data($kunci,$tabel) {
        $this->db->where($kunci);
        $query=$this->db->get($tabel);
        return $query->row();
    }

    public function get_data($kunci,$tabel) {
        $this->db->where($kunci);
        $query=$this->db->get($tabel);
        return $query->result();
    }

}
