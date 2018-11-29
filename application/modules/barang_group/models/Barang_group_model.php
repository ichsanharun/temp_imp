<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * @author Yunas Handra
 * @copyright Copyright (c) 2016, Yunas Handra
 * 
 * This is model class for table "Barang_group_model"
 */

class Barang_group_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'barang_group';
    protected $key        = 'id_group';

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

    public function get_id_group($kode='') {
      $query = $this->db->query("SELECT MAX(id_group) as max_id FROM barang_group"); 
      $row = $query->row_array();
      $max_id = $row['max_id']; 
      $max_id1 =(int) substr($max_id,1,2);
      $kode_group = $max_id1 +1;
      $maxgroup_barang = $kode.str_pad($kode_group, 2, "0", STR_PAD_LEFT);
      return $maxgroup_barang;
    }

    public function pilih_gb(){
        $query="SELECT
                barang_group.id_group,
                barang_group.nm_group
                FROM
                barang_group where sts_aktif='aktif' and deleted=0";
        return $this->db->query($query);
    }

    public function get_nmgroup($id_group){
        $query=$this->db->query("SELECT
                barang_group.nm_group
                FROM
                barang_group where id_group='".$id_group."'");
        $row = $query->row_array();
        $nm_group = $row['nm_group']; 
        return $nm_group;
    }    
}
