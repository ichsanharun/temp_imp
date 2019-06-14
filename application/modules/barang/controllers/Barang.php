<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
 * @author Yunaz
 * @copyright Copyright (c) 2018, Yunaz
 *
 * This is controller for Barang
 */

class Barang extends Admin_Controller
{
    /**
     * Load the models, library, etc.
     */
    //Permission
    protected $viewPermission = 'Barang.View';
    protected $addPermission = 'Barang.Add';
    protected $managePermission = 'Barang.Manage';
    protected $deletePermission = 'Barang.Delete';

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('Mpdf'));
        $this->load->model(array('Barang/Barang_model',
                                 'Barang_group/Barang_group_model',
                                 'Barang/Barang_jenis_model',
                                 'Barang/Barang_cp_model',
                                 'Koli/Barang_koli_model',
                                 'Komponen/Barang_komponen_model',
                                 'Supplier/Supplier_model',
                                 'Aktifitas/aktifitas_model',
                                ));

        date_default_timezone_set('Asia/Bangkok');

        $this->template->title('Master Produk');
        $this->template->page_icon('fa fa-table');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);
        $data = $this->Barang_model->select('barang_master.id_barang,
                                            barang_jenis.nm_jenis,
                                            barang_group.nm_group,
                                            barang_master.nm_barang,
                                            barang_master.satuan AS setpcs,
                                            barang_master.netto_weight,
                                            barang_master.cbm_each,
                                            barang_master.gross_weight,
                                            barang_master.spesifikasi,
                                            barang_master.sts_aktif,
                                            barang_master.qty as qty')
                                            ->join('barang_group', 'barang_group.id_group = barang_master.id_group', 'left')
                                            ->join('barang_jenis', 'barang_master.jenis = barang_jenis.id_jenis', 'left')
                                            ->group_by('barang_master.id_barang')
                                            ->where('barang_master.deleted', 0)
                                            ->order_by('barang_master.nm_barang', 'ASC')->find_all();

        $this->template->set('results', $data);
        $this->template->title('Produk');
        $this->template->render('list');
    }

    //Create New barang
    public function create()
    {
        $this->auth->restrict($this->addPermission);

        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        $suppl_barang = $this->Supplier_model->pilih_supplier()->result();
        $jenis_barang = $this->Barang_jenis_model->pilih_jb()->result();
        $jenis_barang = $this->Barang_jenis_model->pilih_jb()->result();
        $koli = $this->Barang_koli_model->pilih_koli()->result();
        $cp_barang = $this->Barang_cp_model->pilih_cp()->result();
        $model = $this->Barang_koli_model->koli_model()->result();
        $warna = $this->Barang_koli_model->koli_warna()->result();
        $varian = $this->Barang_koli_model->koli_varian()->result();

        $this->template->set('model', $model);
        $this->template->set('warna', $warna);
        $this->template->set('varian', $varian);
        $this->template->set('cp_barang', $cp_barang);
        $this->template->set('koli', $koli);
        $this->template->set('jenis_barang', $jenis_barang);
        $this->template->set('group_barang', $group_barang);
        $this->template->set('suppl_barang', $suppl_barang);
        $this->template->title('Barang');
        $this->template->render('barang_form');
    }

    //Edit barang
    public function edit()
    {
        $this->auth->restrict($this->managePermission);
        $id = $this->uri->segment(3);
        $jenis_barang = $this->Barang_jenis_model->pilih_jb()->result();
        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        $suppl_barang = $this->Supplier_model->pilih_supplier()->result();
        $cp_barang = $this->Barang_cp_model->pilih_cp()->result();

        $barang = $this->Barang_model->pilih_barang()->result();
        $model = $this->Barang_koli_model->koli_model()->result();
        $warna = $this->Barang_koli_model->koli_warna()->result();
        $varian = $this->Barang_koli_model->koli_varian()->result();


        $this->template->set('cp_barang', $cp_barang);
        $this->template->set('jenis_barang', $jenis_barang);
        $this->template->set('group_barang', $group_barang);
        $this->template->set('suppl_barang', $suppl_barang);

        $this->template->set('model', $model);
        $this->template->set('warna', $warna);
        $this->template->set('varian', $varian);

        $this->template->set('data', $this->Barang_model->find($id));
        $this->template->title('Produk Group');
        $this->template->render('barang_form');
    }

    //Save using ajax
    public function save_data_ajax()
    {
        $id_barang = $this->input->post('id_barang');
        $type = $this->input->post('type');
        $id_jenis = $this->input->post('id_jenis');
        $id_group = $this->input->post('id_group');
        $nm_barang = strtoupper($this->input->post('nm_barang'));
        $brand = strtoupper($this->input->post('brand'));
        $series = strtoupper($this->input->post('series'));
        $varian = strtoupper($this->input->post('varian'));
        $id_supplier = $this->input->post('id_supplier');
        $qty = $this->input->post('qty');
        $satuan = $this->input->post('satuan');
        $netto_weight = $this->input->post('netto_weight');
        $cbm_each = $this->input->post('cbm_each');
        $gross_weight = $this->input->post('gross_weight');
        $sts_aktif = $this->input->post('sts_aktif');
        $spesifikasi = $this->input->post('spesifikasi');
        $leadtime_produksi = $this->input->post('leadtime_produksi');
        $leadtime_pengiriman = $this->input->post('leadtime_pengiriman');
        //====Muhaemin here====//
        $harga = $this->input->post('harga');
        $diskon_standart = $this->input->post('diskon_standart');
        $diskon_promo_rp = $this->input->post('diskon_promo_rp');
        $diskon_promo_persen = $this->input->post('diskon_promo_persen');
        $diskon_jika_qty = $this->input->post('diskon_jika_qty');
        $diskon_qty_gratis = $this->input->post('diskon_qty_gratis');
        //===========//
        //print_r($_POST);die();

        if (empty($id_barang) || $id_barang == '') {
            $param = $id_jenis.$id_group;
            $query = $this->Barang_model->get_kode_barang($param, $id_group, $id_jenis);
            if (empty($query)) {
                return 'Error';
            } else {
                $id_barang = $query;
            }
        } else {
            $id_barang = $id_barang;
        }

        //echo $id_barang;die();

        $gambar = $id_barang;
        $filelama = $this->input->post('foto_barang_lama');
        $config = array(
                'upload_path' => './photobarang/',
                'allowed_types' => 'gif|jpg|png|jpeg|JPG|PNG',
                'file_name' => $gambar,
                //'file_ext_tolower' => TRUE,
                'overwrite' => true,
                //'max_size' => 2048,
                'remove_spaces' => true,
                );
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('foto_barang')) {
            $result = $this->upload->display_errors();
        } else {
            if ($filelama != '' && isset($_FILES['foto_barang']) && $_FILES['foto_barang']['name'] != '') {
                @unlink($path.$filelama);
                $data_foto = array('upload_data' => $this->upload->data());
                $gambar = $data_foto['upload_data']['file_name'];
            } else {
                $data_foto = array('upload_data' => $this->upload->data());
                $gambar = $data_foto['upload_data']['file_name'];
            }
        }

        if ($type == 'edit') {
            $this->auth->restrict($this->managePermission);
            /*
            //====Muhaemin here====//
          $harga    = $this->input->post("harga");
          $diskon_standart    = $this->input->post("diskon_standart");
          $diskon_promo_rp    = $this->input->post("diskon_promo_rp");
          $diskon_promo_persen    = $this->input->post("diskon_promo_persen");
          $diskon_jika_qty    = $this->input->post("diskon_jika_qty");
          $diskon_qty_gratis    = $this->input->post("diskon_qty_gratis");
          //===========//
              */

            if ($id_barang != '') {
                $data = array(
                            array(
                                'id_barang' => $id_barang,
                                'id_group' => $id_group,
                                'nm_barang' => $nm_barang,
                                'brand' => $brand,
                                'jenis' => $id_jenis,
                                'series' => $series,
                                'varian' => $varian,
                                'qty' => $qty,
                                'satuan' => $satuan,
                                'foto_barang' => $gambar,
                                'spesifikasi' => $spesifikasi,
                                'sts_aktif' => $sts_aktif,
                                'netto_weight' => $netto_weight,
                                'cbm_each' => $cbm_each,
                                'gross_weight' => $gross_weight,
                                'leadtime_produksi' => $leadtime_produksi,
                                'harga' => $harga,
                                'diskon_standar_persen' => $diskon_standart,
                                'diskon_promo_rp' => $diskon_promo_rp,
                                'diskon_promo_persen' => $diskon_promo_persen,
                                'diskon_jika_qty' => $diskon_jika_qty,
                                'diskon_qty_gratis' => $diskon_qty_gratis,
                                'created_on' => date('Y-m-d H:i:s'),
                                'created_by' => $session['id_user'],
                                'modified_on' => date('Y-m-d H:i:s'),
                                'modified_by' => $session['id_user'],
                            ),
                        );
                //print_r($data);die();
                //Update data
                $result = $this->Barang_model->update_batch($data, 'id_barang');

                //===UPDATE NAMA BARANG Di STOK====//
                $update_nama_di_stok = array(
                    'nm_barang' => $nm_barang,
                    'brand' => $brand,
                    'jenis' => $id_jenis,
                    'series' => $series,
                    'varian' => $varian,
                    'satuan' => $satuan,
                    );
                $this->db->where(array('id_barang' => $id_barang));
                $this->db->update('barang_stock', $update_nama_di_stok);
                //===============================//

                $keterangan = 'SUKSES, Edit data Barang '.$id_barang.', atas Nama : '.$nm_barang;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = $id_barang;
                $jumlah = 1;
                $sql = $this->db->last_query();

                $barang = $id_barang;
            } else {
                $result = false;

                $keterangan = 'GAGAL, Edit data Barang '.$id_barang.', atas Nama : '.$nm_barang;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = $id_barang;
                $jumlah = 1;
                $sql = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        } else { //Add New
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_barang' => $id_barang,
                        'id_group' => $id_group,
                        'nm_barang' => $nm_barang,
                        'brand' => $brand,
                        'jenis' => $id_jenis,
                        'series' => $series,
                        'varian' => $varian,
                        'satuan' => $satuan,
                        'qty' => $qty,
                        'foto_barang' => $gambar,
                        'spesifikasi' => $spesifikasi,
                        'sts_aktif' => $sts_aktif,
                        'netto_weight' => $netto_weight,
                        'cbm_each' => $cbm_each,
                        'gross_weight' => $gross_weight,
                        'leadtime_produksi' => $leadtime_produksi,
                        'leadtime_pengiriman' => $leadtime_pengiriman,
                        'harga' => $harga,
                        'diskon_standar_persen' => $diskon_standart,
                        'diskon_promo_rp' => $diskon_promo_rp,
                        'diskon_promo_persen' => $diskon_promo_persen,
                        'diskon_jika_qty' => $diskon_jika_qty,
                        'diskon_qty_gratis' => $diskon_qty_gratis,
                        'created_on' => date('Y-m-d H:i:s'),
                        'created_by' => $session['id_user'],
                        'modified_on' => date('Y-m-d H:i:s'),
                        'modified_by' => $session['id_user'],
                        );
            //print_r($data);die();
            ////Add Data
            $id = $this->Barang_model->insert($data);

            if (is_numeric($id)) {
                $keterangan = 'SUKSES, tambahBarang '.$id_barang.', atas Nama : '.$nm_barang;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();

                $result = true;
                $barang = $id_barang;
            } else {
                $keterangan = 'GAGAL, tambah data Barang '.$id_barang.', atas Nama : '.$nm_barang;
                $status = 0;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();
                $result = false;
            }
            //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        }

        $param = array(
                'barang' => $barang,
                'series' => $series,
                'save' => $result,
                );

        echo json_encode($param);
    }

    //Save using ajax
    public function save_data_koli()
    {
        $id_koli = $this->input->post('id_koli');
        $type = $this->input->post('type1');
        $nm_koli = strtoupper($this->input->post('nm_koli'));
        $id_barang = $this->input->post('barang');
        $nm_barang = $this->input->post('barang_nm');
        $qty = $this->input->post('qty_koli');
        $id_koli_model   = $this->input->post("id_koli_model");
        $id_koli_warna   = $this->input->post("id_koli_warna");
        $id_koli_varian  = $this->input->post("id_koli_varian");
        $keterangan = $this->input->post('keterangan_kol');
        $sts_aktif = $this->input->post('sts_aktif');
        $netto_weight = $this->input->post('c_netto_weight');
        $cbm_each = $this->input->post('c_cbm_each');
        $gross_weight = $this->input->post('c_gross_weight');

        if (empty($id_koli) || $id_koli == '') {
            $query = $this->Barang_koli_model->get_id_koli($id_barang);
            if (empty($query)) {
                return 'Error';
            } else {
                $id_koli = $query;
            }
        } else {
            $id_koli = $id_koli;
        }

        if ($type == 'edit') {
            $this->auth->restrict($this->managePermission);

            if ($id_koli != '') {
                $data = array(
                            array(
                                'id_koli' => $id_koli,
                                'nm_koli' => $nm_koli,
                                'id_barang' => $id_barang,
                                'nm_barang' => $nm_barang,
                                'qty' => $qty,
                                'id_koli_model'=>$id_koli_model,
                                'id_koli_warna'=>$id_koli_warna,
                                'id_koli_varian'=>$id_koli_varian,
                                'keterangan' => $keterangan,
                                'sts_aktif' => $sts_aktif,
                                'netto_weight' => $netto_weight,
                                'cbm_each' => $cbm_each,
                                'gross_weight' => $gross_weight,
                            ),
                        );

                //Update data
                $result = $this->Barang_koli_model->update_batch($data, 'id_koli');

                $keterangan = 'SUKSES, Edit data Koli '.$id_koli.', atas Nama : '.$nm_koli;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = $id_barang;
                $jumlah = 1;
                $sql = $this->db->last_query();

            /*$data_barang = array(
                                array(
                                    'id_barang'=>$id_barang,
                                    'netto_weight'=>$netto_weight,
                                    'cbm_each'=>$cbm_each,
                                    'gross_weight'=>$gross_weight,
                                )
                            );
            $result_barang = $this->Barang_model->update_batch($data,'id_barang');*/
            } else {
                $result = false;

                $keterangan = 'GAGAL, Edit data Koli '.$id_koli.', atas Nama : '.$nm_koli;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = $id_koli;
                $jumlah = 1;
                $sql = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        } else { //Add New
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_koli' => $id_koli,
                        'nm_koli' => $nm_koli,
                        'id_barang' => $id_barang,
                        'nm_barang' => $nm_barang,
                        'qty' => $qty,
                        'id_koli_model'=>$id_koli_model,
                        'id_koli_warna'=>$id_koli_warna,
                        'id_koli_varian'=>$id_koli_varian,
                        'keterangan' => $keterangan,
                        'sts_aktif' => $sts_aktif,
                        'netto_weight' => $netto_weight,
                        'cbm_each' => $cbm_each,
                        'gross_weight' => $gross_weight,
                        );

            //Add Data
            $id = $this->Barang_koli_model->insert($data);

            if (is_numeric($id)) {
                $keterangan = 'SUKSES, tambah Koli '.$id_koli.', atas Nama : '.$nm_koli;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();

                $result = true;
                $barang = $id_barang;

            /*$data_barang = array(
                                array(
                                    'id_barang'=>$id_barang,
                                    'netto_weight'=>$netto_weight,
                                    'cbm_each'=>$cbm_each,
                                    'gross_weight'=>$gross_weight,
                                )
                            );
            $result_barang = $this->Barang_model->update_batch($data,'id_barang');*/
            } else {
                $keterangan = 'GAGAL, tambah data Koli '.$id_koli.', atas Nama : '.$nm_koli;
                $status = 0;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();
                $result = false;
            }
            //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        }

        $param = array(
                'koli' => $id_koli,
                'barang' => $id_barang,
                'save' => $result,
                );

        echo json_encode($param);
    }

    //Save using ajax
    public function save_data_komponen()
    {
        $type = $this->input->post('type2');
        $id_komponen = $this->input->post('id_komponen');
        $nm_komponen = strtoupper($this->input->post('nm_komponen'));
        $id_koli = $this->input->post('id_koli_c');
        $qty = $this->input->post('qty_komponen');
        $barang = $this->input->post('barangc');
        $keterangan = $this->input->post('keterangan_kom');
        $sts_aktif = $this->input->post('sts_aktif');
        //$foto_komponen = $this->input->post("foto_komponen");

        if (empty($id_komponen) || $id_komponen == '') {
            $query = $this->Barang_komponen_model->get_id_komponen($id_koli);
            if (empty($query)) {
                return 'Error';
            } else {
                $id_komponen = $query;
            }
        } else {
            $id_komponen = $id_komponen;
        }

        $gambarkom = $id_komponen;
        $filelamax = $this->input->post('foto_komponen_lama');
        $configx = array(
                'upload_path' => './photobarang/',
                'allowed_types' => 'gif|jpg|png|jpeg|JPG|PNG',
                'file_name' => $gambarkom,
                //'file_ext_tolower' => TRUE,
                'overwrite' => true,
                //'max_size' => 2048,
                'remove_spaces' => true,
                );
        $this->load->library('upload', $configx);
        //$this->upload->initialize($config);
        if (!$this->upload->do_upload('foto_komponen')) {
            $result = $this->upload->display_errors();
        } else {
            if ($filelamax != '') {
                @unlink($path.$filelamax);
                $data_foto = array('upload_data' => $this->upload->data());
                $gambarkom = $data_foto['upload_data']['file_name'];
            } else {
                $data_foto = array('upload_data' => $this->upload->data());
                $gambarkom = $data_foto['upload_data']['file_name'];
            }
        }

        if ($type == 'edit') {
            $this->auth->restrict($this->managePermission);

            if ($id_komponen != '') {
                $data = array(
                            array(
                                'id_komponen' => $id_komponen,
                                'nm_komponen' => $nm_komponen,
                                'id_koli' => $id_koli,
                                'qty' => $qty,
                                'keterangan' => $keterangan,
                                'sts_aktif' => $sts_aktif,
                                'foto_komponen' => $gambarkom,
                            ),
                        );

                //Update data
                $result = $this->Barang_komponen_model->update_batch($data, 'id_komponen');

                $keterangan = 'SUKSES, Edit data Komponen '.$id_komponen.', atas Nama : '.$nm_komponen;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = $id_komponen;
                $jumlah = 1;
                $sql = $this->db->last_query();
            } else {
                $result = false;

                $keterangan = 'GAGAL, Edit data Komponen '.$id_komponen.', atas Nama : '.$nm_komponen;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = $id_komponen;
                $jumlah = 1;
                $sql = $this->db->last_query();
            }

            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        } else { //Add New
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_komponen' => $id_komponen,
                        'nm_komponen' => $nm_komponen,
                        'id_koli' => $id_koli,
                        'qty' => $qty,
                        'keterangan' => $keterangan,
                        'sts_aktif' => $sts_aktif,
                        'foto_komponen' => $gambarkom,
                        );

            //Add Data
            $id = $this->Barang_komponen_model->insert($data);

            if (is_numeric($id)) {
                $keterangan = 'SUKSES, tambah Komponen '.$id_komponen.', atas Nama : '.$nm_komponen;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();

                $result = true;
                $komponen = $id_komponen;
            } else {
                $keterangan = 'GAGAL, tambah data Komponen '.$id_komponen.', atas Nama : '.$nm_komponen;
                $status = 0;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();
                $result = false;
            }
            //Save Log
            simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);
        }

        $param = array(
                'barang' => $barang,
                'save' => $result,
                );

        echo json_encode($param);
    }

    public function add_cp()
    {
        $id_colly_produk = strtoupper($this->input->post('id_colly'));
        $colly_produk = strtoupper($this->input->post('colly_produk'));

        if ($id_colly_produk != '') {
            $this->auth->restrict($this->addPermission);

            $data = array(
                            array(
                                'id_colly_produk' => $id_colly_produk,
                                'colly_produk' => $colly_produk,
                            ),
                        );
            //Add Data
            $id = $this->Barang_cp_model->update_batch($data, 'id_colly_produk');

            if (is_numeric($id)) {
                $keterangan = 'SUKSES, Edit data Colly Produk atas Nama : '.$colly_produk;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();

                $result = true;
            } else {
                $keterangan = 'GAGAL, Edit data Colly Produk atas Nama : '.$colly_produk;
                $status = 0;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();
                $result = false;
            }
        } else {
            $this->auth->restrict($this->addPermission);

            $data = array(
                        'id_colly_produk' => $id_colly_produk,
                        'colly_produk' => $colly_produk,
                        );

            //Add Data
            $id = $this->Barang_cp_model->insert($data);

            if (is_numeric($id)) {
                $keterangan = 'SUKSES, tambah data Colly Produk atas Nama : '.$colly_produk;
                $status = 1;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();

                $result = true;
            } else {
                $keterangan = 'GAGAL, tambah data Colly Produk atas Nama : '.$colly_produk;
                $status = 0;
                $nm_hak_akses = $this->addPermission;
                $kode_universal = 'NewData';
                $jumlah = 1;
                $sql = $this->db->last_query();
                $result = false;
            }
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
            'save' => $result,
        );

        echo json_encode($param);
    }

    public function add_gb()
    {
        $nm_group = strtoupper($this->input->post('nm_group'));
        $id_group = strtoupper($this->input->post('id_group'));
        $this->auth->restrict($this->addPermission);

        $data = array(
                        'id_group' => $id_group,
                        'nm_group' => $nm_group,
                        );

        //Add Data
        $id = $this->Barang_group_model->insert($data);

        if (is_numeric($id)) {
            $keterangan = 'SUKSES, tambah data Group Barang atas Nama : '.$nm_group;
            $status = 1;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = 'NewData';
            $jumlah = 1;
            $sql = $this->db->last_query();

            $result = true;
        } else {
            $keterangan = 'GAGAL, tambah data Group Barang atas Nama : '.$nm_group;
            $status = 0;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = 'NewData';
            $jumlah = 1;
            $sql = $this->db->last_query();
            $result = false;
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
            'save' => $result,
        );

        echo json_encode($param);
    }

    public function add_jb()
    {
        $nm_jenis = $this->input->post('nm_jenis');
        $id_jenis = $this->input->post('id_jenis');
        $this->auth->restrict($this->addPermission);

        $data = array(
                        'id_jenis' => $id_jenis,
                        'nm_jenis' => $nm_jenis,
                        );

        //Add Data
        $id = $this->Barang_jenis_model->insert($data);

        if (is_numeric($id)) {
            $keterangan = 'SUKSES, tambah data Jenis Barang atas Nama : '.$nm_jenis;
            $status = 1;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = 'NewData';
            $jumlah = 1;
            $sql = $this->db->last_query();

            $result = true;
        } else {
            $keterangan = 'GAGAL, tambah data Jenis Barang atas Nama : '.$nm_jenis;
            $status = 0;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = 'NewData';
            $jumlah = 1;
            $sql = $this->db->last_query();
            $result = false;
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
            'save' => $result,
        );

        echo json_encode($param);
    }

    public function get_nmcp()
    {
        $id_colly_produk = $_GET['id_colly_produk'];
        $datcp = $this->Barang_cp_model->get_nmcp($id_colly_produk);
        $param = array(
                'nm_cp' => $datcp,
                );
        echo json_encode($param);
    }

    public function get_nmgroup()
    {
        $id_group = $_GET['id_group'];
        $datgrup = $this->Barang_group_model->get_nmgroup($id_group);
        $param = array(
                'nm_group' => $datgrup,
                );
        echo json_encode($param);
    }

    public function get_cp()
    {
        $cp_barang = $this->Barang_cp_model->pilih_cp()->result();
        //echo $result;
        echo "<select id='id_colly_produk' name='id_colly_produk' class='form-control pil_gb select2-hidden-accessible'>";
        echo "<option value=''></option>";
        foreach ($cp_barang as $key => $st) :
                    echo "<option value='$st->id_colly_produk' set_select('id_colly_produk', $st->id_colly_produk, isset($data->id_colly_produk) && $data->id_colly_produk == $st->id_colly_produk)>$st->colly_produk
                    </option>";
        endforeach;
        echo '</select>';
    }

    public function get_gb()
    {
        $group_barang = $this->Barang_group_model->pilih_gb()->result();
        //echo $result;
        echo "<select id='id_group' name='id_group' class='form-control pil_gb select2-hidden-accessible'>";
        echo "<option value=''></option>";
        foreach ($group_barang as $key => $st) :
                    echo "<option value='$st->id_group' set_select('id_group', $st->id_group, isset($data->id_group) && $data->id_group == $st->id_group)>$st->nm_group
                    </option>";
        endforeach;
        echo '</select>';
    }

    public function get_jb()
    {
        $jenis_barang = $this->Barang_jenis_model->pilih_jb()->result();
        //echo $result;
        echo "<select id='id_jenis' name='id_jenis' class='form-control pil_jb select2-hidden-accessible'>";
        echo "<option value=''></option>";
        foreach ($jenis_barang as $key => $st) :
                    echo "<option value='$st->id_jenis' set_select('id_jenis', $st->id_jenis, isset($data->id_jenis) && $data->id_jenis == $st->id_jenis)>$st->nm_jenis
                    </option>";
        endforeach;
        echo '</select>';
    }

    public function get_koli()
    {
        $id = $_GET['id_barang'];
        $koli = $this->Barang_koli_model->tampil_koli($id)->result();
        //echo $result;
        echo "<select id='id_koli' name='id_koli' class='form-control pil_koli select2-hidden-accessible'>";
        echo "<option value=''></option>";
        foreach ($koli as $key => $st) :
                    echo "<option value='$st->id_koli' set_select('id_koli', $st->id_koli, isset($data->id_koli) && $data->id_koli == $st->id_koli)>$st->nm_koli
                    </option>";
        endforeach;
        echo '</select>';
    }

    public function hapus_barang()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if ($id != '') {
            $result = $this->Barang_model->delete($id);

            $keterangan = 'SUKSES, Delete data Barang '.$id;
            $status = 1;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = $id;
            $jumlah = 1;
            $sql = $this->db->last_query();
        } else {
            $result = 0;
            $keterangan = 'GAGAL, Delete data Setup Barang '.$id;
            $status = 0;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = $id;
            $jumlah = 1;
            $sql = $this->db->last_query();
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
                'delete' => $result,
                'idx' => $id,
                );

        echo json_encode($param);
    }

    public function hapus_koli()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if ($id != '') {
            $result = $this->Barang_koli_model->delete($id,"koli");

            $keterangan = 'SUKSES, Delete data Koli '.$id;
            $status = 1;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = $id;
            $jumlah = 1;
            $sql = $this->db->last_query();
        } else {
            $result = 0;
            $keterangan = 'GAGAL, Delete data Koli '.$id;
            $status = 0;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = $id;
            $jumlah = 1;
            $sql = $this->db->last_query();
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
                'delete' => $result,
                'id' => $id,
                );

        echo json_encode($param);
    }

    public function hapus_komponen()
    {
        $this->auth->restrict($this->deletePermission);
        $id = $this->uri->segment(3);

        if ($id != '') {
            $result = $this->Barang_komponen_model->delete($id);

            $keterangan = 'SUKSES, Delete data Komponen '.$id;
            $status = 1;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = $id;
            $jumlah = 1;
            $sql = $this->db->last_query();
        } else {
            $result = 0;
            $keterangan = 'GAGAL, Delete data Komponen '.$id;
            $status = 0;
            $nm_hak_akses = $this->addPermission;
            $kode_universal = $id;
            $jumlah = 1;
            $sql = $this->db->last_query();
        }

        //Save Log
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        $param = array(
                'delete' => $result,
                'id' => $id,
                );

        echo json_encode($param);
    }

    public function edit_koli()
    {
        $id_koli = $this->input->post('id_koli');
        if (!empty($id_koli)) {
            $detail = $this->Barang_koli_model->find($id_koli);
        }
        echo json_encode($detail);
    }

    public function edit_komponen()
    {
        $id_komponen = $this->input->post('id_komponen');
        if (!empty($id_komponen)) {
            $detail = $this->Barang_komponen_model->find($id_komponen);
        }
        echo json_encode($detail);
    }

    public function edit_cp()
    {
        $id_cp = $this->input->post('id');
        if (!empty($id_cp)) {
            $detail = $this->Barang_cp_model->find($id_cp);
        }
        echo json_encode($detail);
    }

    public function load_foto_barang()
    {
        $id_barang = $_GET['id'];
        $data = $this->Barang_model->tampil_foto($id_barang);
        $link_foto = base_url();
        if ($data->foto_barang == '') {
            $data->foto_barang = 'no_images.jpg';
        } else {
            $data->foto_barang = $data->foto_barang;
        } ?>
      <a target='_blank' href="<?php echo $link_foto.'photobarang/'.$data->foto_barang; ?>">
      <img class="img-thumbnail" src="<?php echo $link_foto.'photobarang/'.$data->foto_barang; ?>"/>
      </a>
      <?php
    }

    public function load_koli()
    {
        $id_barang = $_GET['id_barang'];
        echo "<div class='box-body'><B>Data Colly Produk</B><table id='lis_koli' class='table table-bordered table-striped'>
        <thead>
        <tr>
            <th width='50'>#</th>
            <th>ID Colly</th>
            <th>Nama Colly Produk</th>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Netto Weight</th>
            <th>CBM Each</th>
            <th>Gross Weight</th>
            <th>Status</th>
            <th width='25'>Hapus</th>
        </tr>
        </thead>";
        $no = 1;
        $data = $this->Barang_koli_model->tampil_koli($id_barang)->result();
        foreach ($data as $d) {
            echo "<tr id='dataku$d->id_koli'>
                <td>$no</td>
                <td>$d->id_koli</td>
                <td>$d->nm_koli</td>
                <td>$d->qty</td>
                <td>$d->satuan</td>
                <td>$d->netto_weight</td>
                <td>$d->cbm_each</td>
                <td>$d->gross_weight</td>
                <td>$d->sts_aktif</td>
                <td>
                 <a class='text-black' href='javascript:void(0)' title='Hapus' onclick=\"hapus_koli('".$d->id_koli."');\"><i class='fa fa-trash'></i>
                 <a class='text-black' href='javascript:void(0)' title='Edit' onclick=\"edit_koli('".$d->id_koli."');\"><i class='fa fa-pencil'></i>
                </a>
                </td>
                </tr>";
            $total += $d->qty;
            ++$no;
        }
        echo "<tfoot>
        <tr>
            <td colspan='3'><center><B>Total</B></center></>
            <td><B>$total</B></td>
            <td colspan='6'></td>
        </tr>
        </tfoot>";
        echo'</table></div>';
    }

    public function load_komponen()
    {
        $link_foto = base_url();
        $id_barang = $_GET['id_barang'];
        echo "<div class='box-body'><B>Data Komponen</B><table id='lis_komponen' class='table table-bordered table-striped'>
        <thead>
        <tr>
            <th width='50'>#</th>
            <th>Nama Colly Produk</th>
            <th>ID Komponen</th>
            <th>Nama Komponen</th>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Foto</th>
            <th width='25'>Hapus</th>
        </tr>
        </thead>";
        $no = 1;
        $data = $this->Barang_komponen_model->tampil_komponen($id_barang)->result();
        foreach ($data as $d) {
            if ($d->foto_komponen == '') {
                $d->foto_komponen = 'no_images.jpg';
            } else {
                $d->foto_komponen = $d->foto_komponen;
            }
            echo "<tr id='dataku$d->id_komponen'>
                <td>$no</td>
                <td>$d->nm_koli</td>
                <td>$d->id_komponen</td>
                <td>$d->nm_komponen</td>
                <td>$d->qty</td>
                <td>$d->satuan</td>
                <td><a target='_blank' href='".$link_foto.'photobarang/'.$d->foto_komponen."'>
                    <img src='".$link_foto.'photobarang/'.$d->foto_komponen."'>
                    </a>
                </td>
                <td>
                 <a class='text-black' href='javascript:void(0)' title='Hapus' onclick=\"hapus_komponen('".$d->id_komponen."');\"><i class='fa fa-trash'></i>
                </a>
                <a class='text-black' href='javascript:void(0)' title='Edit' onclick=\"edit_komponen('".$d->id_komponen."');\"><i class='fa fa-pencil'></i>
                </td>
                </tr>";
            ++$no;
            $total += $d->qty;
        }
        echo "<tfoot>
        <tr>
            <td colspan='4'><center><b>Total</b></center></td>
            <td><b>$total</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>";
        echo'</table></div>';
    }

    public function ListCP()
    {
        echo "<div class='box-body'><B>Colly Produk</B><table id='lis_CP' class='table table-bordered table-striped'>
        <thead>
        <tr>
            <th width='50'>#</th>
            <th>Nama Colly Produk</th>
            <th width='25'>Action</th>
        </tr>
        </thead>";
        $no = 1;
        $data = $this->Barang_cp_model->tampil_cp()->result();
        foreach ($data as $d) {
            echo "<tr id='dataku$d->id_colly_produk'>
                <td>$no</td>
                <td>$d->colly_produk</td>
                <td>
                <a class='text-black' href='javascript:void(0)' title='Edit' onclick=\"edit_cp('".$d->id_colly_produk."');\"><i class='fa fa-pencil'></i>
                </td>
                </tr>";
            ++$no;
        }
        echo '<tfoot>
        <tr>
        </tr>
        </tfoot>';
        echo'</table></div>';
    }

    public function print_rekap()
    {
        $mpdf = new mPDF('', '', '', '', '', '', '', '', '', '');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $brg_data = $this->Barang_model->tampil_produk()->result_array();
        $kol_data = $this->Barang_koli_model->tampil_dkoli()->result_array();
        $kom_data = $this->Barang_komponen_model->tampil_dkomponen()->result_array();
        $summary = $this->Barang_model->tampil_summary();

        $this->template->set('brg_data', $brg_data);
        $this->template->set('kol_data', $kol_data);
        $this->template->set('kom_data', $kom_data);
        $this->template->set('summary', $summary);

        $show = $this->template->load_view('print_rekap', $data);

        $this->mpdf->AddPage('L');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    public function print_request($id)
    {
        $id_barang = $id;
        $mpdf = new mPDF('', '', '', '', '', '', '', '', '', '');
        $mpdf->SetImportUse();
        $mpdf->RestartDocTemplate();

        $brg_data = $this->Barang_model->find_data('barang_master', $id_barang, 'id_barang');
        $kol_data = $this->Barang_koli_model->tampil_koli($id_barang)->result_array();
        $kom_data = $this->Barang_komponen_model->tampil_komponen($id_barang)->result_array();
        //$summary       =  $this->Barang_model->tampil_summary_barang();

        $this->template->set('brg_data', $brg_data);
        $this->template->set('kol_data', $kol_data);
        $this->template->set('kom_data', $kom_data);
        //$this->template->set('summary', $summary);
        $show = $this->template->load_view('print_data', $data);

        $this->mpdf->AddPage('P');
        $this->mpdf->WriteHTML($show);
        $this->mpdf->Output();
    }

    public function downloadExcel()
    {
        //$brg_data = $this->Barang_model->tampil_produk()->result_array();
        $data = $this->Barang_model->select('barang_master.id_barang,
                                            barang_jenis.nm_jenis,
                                            barang_group.nm_group,
                                            barang_master.nm_barang,
                                            barang_master.satuan AS setpcs,
                                            barang_master.netto_weight,
                                            barang_master.cbm_each,
                                            barang_master.gross_weight,
                                            barang_master.spesifikasi,
                                            barang_master.sts_aktif,
                                            barang_master.qty as qty')
                                            ->join('barang_group', 'barang_group.id_group = barang_master.id_group', 'left')
                                            ->join('barang_jenis', 'barang_master.jenis = barang_jenis.id_jenis', 'left')
                                            ->group_by('barang_master.id_barang')
                                            ->where('barang_master.deleted', 0)
                                            ->order_by('barang_group.nm_group', 'ASC')->find_all();
        //print_r($brg_data);die();
        $kol_data = $this->Barang_koli_model->tampil_dkoli()->result_array();
        $kom_data = $this->Barang_komponen_model->tampil_dkomponen()->result_array();

        $session = $this->session->userdata('app_session');
        $kdcab = $session['kdcab'];
        $filter = $this->input->get('filter');
        $param = $this->input->get('param');
        $where ='';
        if ($this->uri->segment(4) == "All") {
          $per = $this->uri->segment(5)."-";
        }else {
          $per = $this->uri->segment(5)."-".$this->uri->segment(4);
        }




        $data = array(
    			'title2'		     => 'Report',
    			'brg_data'	       => $data
    		);
        /*$this->template->set('results', $data_so);
        $this->template->set('head', $sts);
        $this->template->title('Report SO');*/
        $this->load->view('view_report',$data);


    }

    public function downloadExcel_old()
    {
        $brg_data = $this->Barang_model->tampil_produk()->result_array();
        //print_r($brg_data);die();
        $kol_data = $this->Barang_koli_model->tampil_dkoli()->result_array();
        $kom_data = $this->Barang_komponen_model->tampil_dkomponen()->result_array();

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(17);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(17);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(17);
        //// $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(17);

        $objPHPExcel->getActiveSheet()->getStyle(1)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle(2)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);

        $header = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '000000'),
                'name' => 'Verdana',
            ),
        );
        $objPHPExcel->getActiveSheet()->getStyle('A1:O2')
                ->applyFromArray($header)
                ->getFont()->setSize(14);
        $objPHPExcel->getActiveSheet()->mergeCells('A1:O2');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Rekap Data Produk')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'ID Produk')
            ->setCellValue('C3', 'Jenis Produk')
            ->setCellValue('D3', 'Group Produk')
            ->setCellValue('E3', 'Nama Set')
            ->setCellValue('F3', 'Satuan')
            ->setCellValue('G3', 'ID Colly')
            ->setCellValue('H3', 'Nama Colly')
            ->setCellValue('I3', 'Qty')
            ->setCellValue('J3', 'Satuan')
            ->setCellValue('K3', 'ID Komponen')
            ->setCellValue('L3', 'Nama Komponen')
            ->setCellValue('M3', 'Qty')
            ->setCellValue('N3', 'Satuan')
            ->setCellValue('O3', '');

        $ex = $objPHPExcel->setActiveSheetIndex(0);
        $no = 1;
        $NN = 0;
        $counter = 4;
        foreach ($brg_data as $row):
            $ex->setCellValue('A'.$counter, $no++);
        $ex->setCellValue('B'.$counter, strtoupper($row['id_barang']));
        $ex->setCellValue('C'.$counter, strtoupper($row['nm_jenis']));
        $ex->setCellValue('D'.$counter, strtoupper($row['nm_group']));
        $ex->setCellValue('E'.$counter, $row['nm_barang']);
        $ex->setCellValue('F'.$counter, $row['satuan']);
        $nco = $counter;

        foreach ($kol_data as $key => $y) {
            //$counter
            if ($row['id_barang'] == $y['id_barang']) {
                $ex->setCellValue('G'.$counter, strtoupper($y['id_koli']));
                $ex->setCellValue('H'.$counter, $y['nm_koli']);
                $ex->setCellValue('I'.$counter, $y['qty']);
                $ex->setCellValue('J'.$counter, $y['satuan']);
                foreach ($kom_data as $key => $xy) {
                    if ($y['id_koli'] == $xy['id_koli'] && $row['id_barang'] == $y['id_barang']) {
                        $ex->setCellValue('K'.$counter, strtoupper($xy['id_komponen']));
                        $ex->setCellValue('L'.$counter, strtoupper($xy['nm_komponen']));
                        $ex->setCellValue('M'.$counter, $xy['qty']);
                        $ex->setCellValue('N'.$counter, $xy['satuan']);
                        //$ex->setCellValue('O'.$counter, $row['sts_aktif']);
                        $counter = $counter + 1;
                        $NN =1;
                    } else {
                        $counter = $counter;
                    }
                }
                $counter = $counter + 1;
            } else {
                $ex->setCellValue('G'.$counter, '');
                $ex->setCellValue('H'.$counter, '');
                $ex->setCellValue('I'.$counter, '');
                $ex->setCellValue('J'.$counter, '');
                $ex->setCellValue('K'.$counter, '');
                $ex->setCellValue('L'.$counter, '');
                $ex->setCellValue('M'.$counter, '');
                $ex->setCellValue('N'.$counter, '');
                //$ex->setCellValue('O'.$counter, $row['sts_aktif']);
                $counter = $counter;
            }
        }

          $ex->setCellValue('O'.$nco, $row['sts_aktif']);

        $counter = $counter + 1;
        endforeach;

        $objPHPExcel->getProperties()->setCreator('Yunaz Fandy')
            ->setLastModifiedBy('Yunaz Fandy')
            ->setTitle('Export Rekap Data Produk')
            ->setSubject('Export Rekap Data Produk')
            ->setDescription('Rekap Data Produk for Office 2007 XLSX, generated by PHPExcel.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('PHPExcel');
        $objPHPExcel->getActiveSheet()->setTitle('Rekap Data Produk');
        ob_end_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
        header('Chace-Control: no-store, no-cache, must-revalation');
        header('Chace-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ExportRekapProduk'.date('Ymd').'.xls"');

        $objWriter->save('php://output');
    }
}
?>
