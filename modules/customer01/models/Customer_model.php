<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Customer_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'customer';
    protected $key        = 'id_customer';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_on';

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
    protected $soft_deletes = true;

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

    function generate_id($kode='') {
      $query = $this->db->query("SELECT MAX(id_customer) as max_id FROM customer");
      $row = $query->row_array();
      $thnbln = date('Ym');
      $max_id = $row['max_id'];
      $max_id1 =(int) substr($max_id,7,3);
      $counter = $max_id1 +1;
      $idcust = $kode.$thnbln.str_pad($counter, 3, "0", STR_PAD_LEFT);
      return $idcust;
    }

    function get_idtoko($kode=''){
        $query = $this->db->query("SELECT MAX(id_toko) as max_id FROM customer_toko");
        $row = $query->row_array();
        $max_id = $row['max_id'];
        $max_id1 =(int) substr($max_id,12,3);
        $counter = $max_id1 +1;
        $idcusttoko = $kode."-T".str_pad($counter, 3, "0", STR_PAD_LEFT);
        return $idcusttoko;
    }

    function pilih_provinsi()
    {
        $query="SELECT
                provinsi.id_prov,
                provinsi.nama
                FROM
                provinsi";
        return $this->db->query($query);
    }

    function get_prov($id){
        $query = $this->db->query("SELECT provinsi FROM customer WHERE id_customer='$id'");
        $row = $query->row_array();
        $provinsi     = $row['provinsi'];
        return $provinsi;
    }

    function pilih_kota($provinsi){
        $query="SELECT
                kabupaten.id_kab,
                kabupaten.nama
                FROM kabupaten where id_prov='$provinsi'";
        return $this->db->query($query);
    }

    function pilih_marketing(){
        $query="SELECT
                karyawan.id_karyawan,
                karyawan.nama_karyawan
                FROM karyawan where divisi=3 and sts_aktif='aktif'";
        return $this->db->query($query);
    }

    function tampil_foto($id_customer){
        $query="SELECT
                customer.foto
                FROM customer WHERE id_customer='$id_customer'";
        $data = $this->db->query($query);
        return $data->row();
    }

   function rekap_data(){
        $query="SELECT 
            `customer`.`id_customer`,
            `customer`.`nm_customer`,
            `customer`.`bidang_usaha`,
            `customer`.`produk_jual`,
            `customer`.`kredibilitas`,
            `customer`.`alamat`,
            `customer`.`provinsi`,
            `customer`.`kota`,
            `customer`.`kode_pos`,
            `customer`.`telpon`,
            `customer`.`fax`,
            `customer`.`npwp`,
            `customer`.`alamat_npwp`,
            `customer`.`id_marketing`,
            `customer`.`referensi`,
            `customer`.`website`,
            `customer`.`foto`,
            `customer`.`sts_aktif`,
            `karyawan`.`nama_karyawan`
        FROM
            `customer`
        JOIN `karyawan` ON `customer`.`id_marketing` = `karyawan`.`id_karyawan`
        WHERE
            `customer`.`deleted` = 0
        ORDER BY
            `id_customer` ASC";
        return $this->db->query($query);
    }

}
