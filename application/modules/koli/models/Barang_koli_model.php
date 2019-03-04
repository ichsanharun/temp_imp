<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Barang_koli_model"
 */

class Barang_koli_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'barang_koli';
    protected $key        = 'id_koli';

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

    public function get_id_koli($kode='') {
      $query = $this->db->query("SELECT MAX(id_koli) as max_id FROM barang_koli where id_barang='$kode'");
      $row = $query->row_array();
      $max_id = $row['max_id'];
      $max_id1 =(int) substr($max_id,7,3);
      $counter = $max_id1 +1;
      $koli_barang = $kode.str_pad($counter, 2, "0", STR_PAD_LEFT);
      return $koli_barang;
    }

    public function pilih_koli(){
        $query="SELECT
                barang_koli.id_koli,
                barang_koli.nm_koli
                FROM
                barang_koli where sts_aktif='aktif'";
        return $this->db->query($query);
    }

    public function tampil_koli($id){
        $query="SELECT
                barang_koli.id_koli,
                barang_koli.nm_barang,
                barang_koli.nm_koli,
                barang_koli.qty,
                barang_koli.satuan,
                barang_koli.sts_aktif,
                barang_koli.gross_weight,
                barang_koli.netto_weight,
                barang_koli.cbm_each
                FROM
                barang_koli where id_barang='$id' and deleted=0";
        return $this->db->query($query);
    }

    public function tampil_dkoli(){
        $query="SELECT
                barang_koli.id_koli,
                barang_koli.id_barang,
                barang_koli.nm_barang,
                barang_koli.nm_koli,
                barang_koli.qty,
                barang_koli.satuan,
                barang_koli.sts_aktif,
                barang_koli.gross_weight,
                barang_koli.netto_weight,
                barang_koli.cbm_each
                FROM
                barang_koli";
        return $this->db->query($query);
    }
}
