<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Barang_model"
 */

class Reportstok_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'barang_stock';
    protected $key        = 'id_barang';

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

    public function get_stok_group($idgroup,$kdcab){
        $q = "SELECT
                a.id_barang,
                a.nm_barang,
                a.jenis,
                a.qty_stock,
                a.qty_avl,
                b.id_group 
            FROM
                barang_stock a
                JOIN barang_master b ON a.id_barang = b.id_barang
            WHERE b.sts_aktif = 'aktif' AND b.id_group='".$idgroup."' AND a.kdcab='".$kdcab."' ";
        $r = $this->db->query($q);
        return $r->result();
    }
}
