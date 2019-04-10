<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Yunas Handra
 * @copyright Copyright (c) 2018, Yunas Handra
 *
 * This is model class for table "Customer"
 */

class Purchaserequest_model extends BF_Model
{

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'trans_pr_header';
    protected $key        = 'no_pr';

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
    function pilih_item($kdcab){
        $query="SELECT
                barang_stock.id_barang,
                barang_stock.nm_barang,
                barang_stock.harga,
                barang_stock.satuan,
                barang_stock.qty_stock,
                barang_stock.qty_avl
                FROM
                barang_stock WHERE kdcab='$kdcab'";
        return $this->db->query($query);
    }

    function pilih_item_sup($kdcab,$id_supplier){
        $query="SELECT
                s.id_barang,
                s.nm_barang,
                s.harga,
                s.satuan,
                s.qty_stock,
                s.qty_avl,
                m.cbm_each,
                m.gross_weight,
                m.id_supplier
                FROM
                barang_stock s INNER JOIN barang_master m
                ON s.id_barang = m.id_barang
                WHERE kdcab='$kdcab' AND id_supplier = '$id_supplier'";
        return $this->db->query($query);
    }

    function get_item_barang($idbarang){
        $query="SELECT
                barang_stock.id_barang,
                barang_stock.nm_barang,
                barang_stock.satuan,
                barang_stock.jenis,
                barang_stock.harga,
                barang_stock.qty_stock,
                barang_stock.qty_avl
                FROM
                barang_stock where id_barang='$idbarang'";
        return $this->db->query($query);
    }

    function generate_nopr($kdcab,$tgl){
      $arr_tgl = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',
      7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L'
      );
      $bln_now = date('m',strtotime($tgl));
      $kode_bln = '';
      foreach($arr_tgl as $k=>$v){
        if($k == $bln_now){
          $kode_bln = $v;
        }
      }
      $cek = $kdcab.'-PR-'.date('y').$kode_bln;
      /*$query_cek = $this->db->query("SELECT MAX(no_so) as max_id FROM trans_so_header
      WHERE no_so LIKE '%$cek%'")->num_rows();*/
      $query = "SELECT MAX(no_pr) as max_id
      FROM
      trans_pr_header WHERE LEFT(no_pr,3)='$kdcab' AND no_pr LIKE '%$cek%'";
      $q = $this->db->query($query);
      $query_cek = $q->num_rows();
      if ($query_cek == 0) {
        $kode = 1;
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
        $fin = $kdcab.'-PR-'.date('y').$kode_bln.$next_kode;
      }else {
        $query = "SELECT MAX(no_pr) as max_id
        FROM
        trans_pr_header WHERE LEFT(no_pr,3)='$kdcab' AND no_pr LIKE '%$cek%'";
        $q = $this->db->query($query);
        $r = $q->row();
        $kode = (int)substr($r->max_id,10)+1;
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
        $fin =  $kdcab.'-PR-'.date('y').$kode_bln.$next_kode;
      }

      return $fin;
    }

    function generate_nopr_old($kdcab){
        $query = "SELECT cabang.no_pr
                  FROM
                  cabang WHERE cabang.kdcab='$kdcab'";
        $q = $this->db->query($query);
        $r = $q->row();
        $kode = (int)$r->no_pr+1;
        $next_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);

        $arr_tgl = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',
                         7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L'
                        );
        $bln_now = date('m');
        $kode_bln = '';
        foreach($arr_tgl as $k=>$v){
            if($k == $bln_now){
                $kode_bln = $v;
            }
        }
        return $kdcab.'-PR-'.date('y').$kode_bln.$next_kode;
    }

    function get_supplier($idsup){
        $query="SELECT
                supplier.id_supplier,
                supplier.nm_supplier,
                supplier.id_negara,
                supplier.alamat
                FROM supplier where id_supplier='$idsup'";
        return $this->db->query($query);
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

    public function get_purchaserequest_open($where=false){
      $q = " SELECT
              h.no_pr,
              h.kdcab,
              h.id_supplier,
              h.nm_supplier,
              h.tgl_pr,
              h.total_pr,
              d.proses_po

            FROM
              trans_pr_header h
            JOIN
            trans_pr_detail d ON h.no_pr = d.no_pr
            WHERE h.total_pr != 0  ".$where."
            GROUP BY h.no_pr
          ";
      $r = $this->db->query($q);
      return $r->result();
    }


    public function get_pending_po(){
      $q = "SELECT
            a.no_pr,
            a.id_supplier,
            a.nm_supplier,
            a.tgl_pr,
            a.proses_po,
            a.kdcab,
            SUM(b.qty_pr) AS qty_pr,
            SUM(b.qty_po) AS qty_po,
            SUM(b.qty_cancel) AS qty_cancel
            FROM
            trans_pr_header a
            JOIN
            trans_pr_detail b ON a.no_pr = b.no_pr
            WHERE a.proses_po != 1
            GROUP BY a.no_pr ORDER BY a.no_pr DESC ";
      $r = $this->db->query($q);
      return $r->result();
    }

    function fetch_data_pr($like_value = NULL, $column_order = NULL, $column_dir = NULL, $limit_start = NULL, $limit_length = NULL)
    {
        $sql = "
            SELECT
                (@row:=@row+1) AS nomor,
                 a.no_pr,
                 a.tgl_pr,
                 a.kdcab,
                 b.namacabang,
                 a.plan_delivery_date,
                 a.id_supplier,
                 a.nm_supplier
            FROM
                trans_pr_header as a
                LEFT JOIN cabang b ON a.kdcab=b.kdcab,
                (SELECT @row := 0) r
            WHERE
                1=1
        ";

        $data['totalData'] = $this->db->query($sql)->num_rows();

        if( ! empty($like_value))
        {
            $sql .= " AND ( ";
            $sql .= "
                b.namacabang LIKE '%".$this->db->escape_like_str($like_value)."%'
                OR a.nm_supplier LIKE '%".$this->db->escape_like_str($like_value)."%'
            ";
            $sql .= " ) ";
        }

        $data['totalFiltered']  = $this->db->query($sql)->num_rows();

        $columns_order_by = array(
            0 => 'nomor'
        );

        $sql .= " ORDER BY ".$columns_order_by[$column_order]." ".$column_dir.", nomor ";
        $sql .= " LIMIT ".$limit_start." ,".$limit_length." ";

        $data['query'] = $this->db->query($sql);
        return $data;
    }

    public function insert_trans_pr_tambahan($data)
    {
        return $this->db->insert('trans_pr_tambahan', $data);
    }

    public function update_pr_header($id, $data)
    {
        $this->db->where('no_pr', $id);
        return $this->db->update('trans_pr_header', $data);
    }

    public function delete_pr_detail($id)
    {
        $this->db->where('no_pr', $id);
        return $this->db->delete('trans_pr_detail');
    }

    public function delete_pr_tambahan($id)
    {
        $this->db->where('no_pr', $id);
        return $this->db->delete('trans_pr_tambahan');
    }

    public function delete_pr_header($id)
    {
        $this->db->where('no_pr', $id);
        return $this->db->delete('trans_pr_header');
    }

}
