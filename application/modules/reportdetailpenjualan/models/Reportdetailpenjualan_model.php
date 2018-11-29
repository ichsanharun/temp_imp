<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Barang_model"
 */

class Reportdetailpenjualan_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'trans_invoice_header';
    protected $key        = 'no_invoice';

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

    public function getdetailpenjualan(){
        $q = "SELECT
                h.no_invoice,
                h.kdcab,
                h.tanggal_invoice,
                h.id_customer,
                h.nm_customer,
                h.nm_salesman,
                h.hargajualtotal,
                h.hargalandedtotal,
                d.id_barang,
                d.nm_barang,
                d.satuan,
                d.jumlah,
                d.hargajual,
                d.diskon
            FROM
                trans_invoice_header h
                INNER JOIN trans_invoice_detail d ON h.no_invoice = d.no_invoice ";
        $r = $this->db->query($q);
        $rs= $r->result();
        return $rs;
    }

    public function getdetailpenjualanfilter($where){
        $q = "SELECT
                h.no_invoice,
                h.kdcab,
                h.tanggal_invoice,
                h.id_customer,
                h.nm_customer,
                h.nm_salesman,
                h.hargajualtotal,
                h.hargalandedtotal,
                d.id_barang,
                d.nm_barang,
                d.satuan,
                d.jumlah,
                d.hargajual,
                d.diskon
            FROM
                trans_invoice_header h
                INNER JOIN trans_invoice_detail d ON h.no_invoice = d.no_invoice $where ";
        $r = $this->db->query($q);
        $rs= $r->result();
        return $rs;
    }
}
