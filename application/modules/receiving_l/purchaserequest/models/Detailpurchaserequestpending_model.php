<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Mohammad Ichsan
 * @copyright Copyright (c) 2018, Mohammad Ichsan
 *
 * This is model class for table "trans_pr_pending_detail"
 */

class Detailpurchaserequestpending_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'trans_pr_pending_detail';
    protected $key        = 'id_pr_pending_detail';

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

    function get_where_in($field,$kunci,$tabel){
        $this->db->where_in($field,$kunci);
        $query=$this->db->get($tabel);
        return $query->result();
    }

    function get_where_in_and($field,$kunci,$and,$tabel){
        $this->db->where_in($field,$kunci);
        $this->db->where($and);
        $query=$this->db->get($tabel);
        return $query->result();
    }

}