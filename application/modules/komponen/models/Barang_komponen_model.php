<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 * 
 * This is model class for table "Barang_komponen_model"
 */

class Barang_komponen_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'barang_komponen';
    protected $key        = 'id_komponen';

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

    public function get_id_komponen($kode='') {
      $query = $this->db->query("SELECT MAX(id_komponen) as max_id FROM barang_komponen where id_koli='$kode'"); 
      $row = $query->row_array();
      $max_id = $row['max_id']; 
      $max_id1 =(int) substr($max_id,15,2);
      $counter = $max_id1 +1;
      $komp_barang = $kode.str_pad($counter, 2, "0", STR_PAD_LEFT);
      return $komp_barang;
    }

    public function tampil_komponen($id){
        $query="SELECT
        barang_koli.id_koli,
        barang_koli.nm_koli,
        barang_komponen.nm_komponen,
        barang_komponen.id_komponen,
        barang_komponen.qty,
        barang_komponen.satuan,
        barang_koli.nm_barang,
        barang_komponen.foto_komponen,
        barang_komponen.keterangan
        FROM
        barang_koli
        INNER JOIN barang_komponen ON barang_komponen.id_koli = barang_koli.id_koli
        WHERE
        id_barang='$id' and barang_komponen.deleted=0";
        return $this->db->query($query);
    }

    public function tampil_dkomponen(){
        $query="SELECT      
        barang_komponen.id_koli,  
        barang_komponen.nm_komponen,
        barang_komponen.id_komponen,
        barang_komponen.qty,
        barang_komponen.satuan
        FROM
        barang_komponen";
        return $this->db->query($query);
    }
}
