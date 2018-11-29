<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "customer_toko"
 */

class Toko_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'customer_toko';
    protected $key        = 'id_toko';

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
        $query = $this->db->query("SELECT MAX(id_toko) as max_id FROM customer_toko where id_customer='$kode'");
        $row = $query->row_array();
        $max_id = $row['max_id'];
        $max_id1 =(int) substr($max_id,12,3);
        $counter = $max_id1 +1;
        $idcusttoko = $kode."-T".str_pad($counter, 3, "0", STR_PAD_LEFT);
        return $idcusttoko;
    }

    //Toko
    public function tampil_toko($id_customer){
        $query="SELECT
            customer_toko.id_toko,
            customer_toko.id_customer,
            customer_toko.nm_toko,
            customer_toko.foto_toko,
            customer_toko.status_milik,
            customer_toko.luas,
            customer_toko.thn_berdiri,
            customer_toko.area,
            customer_toko.alamat_toko
            FROM `customer_toko` WHERE id_customer='$id_customer' and deleted=0";
        return $this->db->query($query);
    }

    function pilih_toko($id_customer){
        $query="SELECT
                customer_toko.id_toko,
                customer_toko.nm_toko
                FROM `customer_toko` WHERE id_customer='$id_customer' and deleted=0";
        return $this->db->query($query);
    }
    
    public function get_idcust($id_customer) {
      $query = $this->db->query("SELECT id_customer FROM customer WHERE id_customer='$id_customer'");
      $row = $query->row_array();
      $id_customer     = $row['id_customer'];
      return id_customer;
    }
}
