<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Barang_koli_model"
 */

class Giro_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'giro';
    protected $key        = 'id_giro';

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

    public function get_data($kunci,$tabel) {
        $this->db->where($kunci);
        $query=$this->db->get($tabel);
        return $query->result();
    }

    public function cek_data($kunci,$tabel) {
        $this->db->where($kunci);
        $query=$this->db->get($tabel);
        return $query->row();
    }

    function generate_bank(){

        $cek = 'B0';
        /*$query_cek = $this->db->query("SELECT MAX(no_so) as max_id FROM trans_so_header
        WHERE no_so LIKE '%$cek%'")->num_rows();*/
        $query = "SELECT MAX(kd_bank) as max_id
        FROM
        bank WHERE kd_bank LIKE '%$cek%'";
        $q = $this->db->query($query);
        $query_cek = $q->num_rows();
        if ($query_cek == 0) {
          $kode = 1;
          $next_kode = str_pad($kode, 3, "0", STR_PAD_LEFT);
          $fin = 'B0'.$next_kode;
        }else {
          $query = "SELECT MAX(kd_bank) as max_id
          FROM
          bank WHERE kd_bank LIKE '%$cek%'";
          $q = $this->db->query($query);
          $r = $q->row();
          $kode = (int)substr($r->max_id,2)+1;
          $next_kode = str_pad($kode, 3, "0", STR_PAD_LEFT);
          $fin =  'B0'.$next_kode;
        }


      return $fin;
    }
}
