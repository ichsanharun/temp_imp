<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Salesorder_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'trans_so_header';
    protected $key        = 'no_so';

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
    function pilih_item(){
        $query="SELECT
                barang_stock.id_barang,
                barang_stock.nm_barang,
                barang_stock.harga,
                barang_stock.qty_stock,
                barang_stock.qty_avl
                FROM
                barang_stock";
        return $this->db->query($query);
    }

    function get_item_barang($idbarang){
        $query="SELECT
                barang_stock.id_barang,
                barang_stock.nm_barang,
                barang_stock.satuan,
                barang_stock.harga,
                barang_stock.qty_stock,
                barang_stock.qty_avl
                FROM
                barang_stock where id_barang='$idbarang'";
        return $this->db->query($query);
    }

    function generate_noso($kdcab){
        $query = "SELECT cabang.no_so 
                  FROM 
                  cabang WHERE cabang.kdcab='$kdcab'"; 
        $q = $this->db->query($query);
        $r = $q->row();
        $kode = (int)$r->no_so+1; 
        $next_kode = str_pad($kode, 4, "0", STR_PAD_LEFT);
        return $kdcab.'-SO'.date('y').$next_kode;
    }

    function pilih_marketing(){
        $query="SELECT
                karyawan.id_karyawan,
                karyawan.nama_karyawan
                FROM karyawan where divisi='3'";
        return $this->db->query($query);
    }

    function get_marketing($idkaryawan){
        $query="SELECT
                karyawan.id_karyawan,
                karyawan.nama_karyawan
                FROM karyawan where id_karyawan='$idkaryawan'";
        return $this->db->query($query);
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

}
