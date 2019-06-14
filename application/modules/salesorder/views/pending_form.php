<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-so" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                  <!-- Header Session -->
                    <?php
                    //$headersession = $this->session->userdata('header_so');
                    ?>
                    <hr>
                    <?php
                    $pic = $this->Salesorder_model->get_pic_customer($data->id_customer)->result();
                    $disc_cash = $this->Salesorder_model->get_data(array('diskon'=>'CASH'),'diskon');
                    foreach ($disc_cash as $key => $value) {
                      $disc_cash = $value->persen;
                    }
                    ?>
                  <!-- Header Session -->
                    <div class="col-sm-6">
                      <!-- Data Customer -->
                        <div class="form-group">
                          <input type="hidden" name="no_so_pending" value="<?=$this->uri->segment(3)?>">
                            <label for="idcustomer" class="col-sm-4 control-label">Nama Customer <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 100%;" tabindex="-1" onchange="getcustomer()" required>
                                <option value=""></option>
                                <?php
                                foreach(@$customer as $kc=>$vc){
                                ?>
                                <option value="<?php echo $vc->id_customer; ?>" <?php echo set_select('nm_customer', $vc->id_customer, isset($data->nm_customer) && $data->id_customer == $vc->id_customer) ?>>
                                    <?php echo '('.$vc->bidang_usaha.') , '.$vc->nm_customer ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmcustomer" id="nmcustomer" class="form-control input-sm" value="<?php echo $data->nm_customer?>">

                              <!-- Bidan Usaha -->
                                <input type="hidden" name="bidang_usaha" id="bidang_usaha" value="<?=$data->bidang_usaha?>">
                              <!-- Bidang Usaha -->

                                </div>
                            </div>
                        </div>
                      <!-- Data Customer -->

                      <!-- Data Sales -->
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">Nama Salesman <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idsalesman" name="idsalesman" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getsalesman()">
                                <option value=""></option>
                                <?php
                                foreach(@$marketing as $km=>$vm){
                                    $selected = '';
                                    if($data->id_salesman == $vm->id_karyawan){
                                        $selected = 'selected="selected"';
                                    }
                                ?>
                                <option value="<?php echo $vm->id_karyawan; ?>" <?php echo $selected?>>
                                    <?php echo $vm->nama_karyawan ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmsalesman" id="nmsalesman" class="form-control input-sm" value="<?php echo $data->nm_salesman?>">
                                </div>
                            </div>
                        </div>
                      <!-- Data Sales -->

                      <!-- Data Tanggal SO -->
                        <div class="form-group ">
                            <?php
                            if($data->tanggal){
                                $tglso=$data->tanggal;
                            }else{
                                $tglso=date('Y-m-d');
                            }
                            ?>
                            <label for="tglso" class="col-sm-4 control-label">Tanggal <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglso" id="tglso" class="form-control input-sm datepicker" value="<?php echo $tglso?>" required>
                                </div>
                            </div>
                        </div>
                      <!-- Data Tanggal SO -->

                      <!-- Data PIC -->
                        <div class="form-group ">
                            <label for="pic" class="col-sm-4 control-label">PIC <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <?php
                                    if($data->pic){
                                    ?>
                                    <select name="pic" id="pic" class="form-control input-sm select2" required>
                                        <?php
                                        foreach($pic as $kp=>$vp){
                                            $selected ='';
                                            if($data->pic == $vp->id_pic){
                                                $selected = 'selected="selected"';
                                            }
                                        ?>
                                        <option value="<?php echo $vp->id_pic?>" <?php echo $selected?>><?php echo $vp->nm_pic.' ('.$vp->divisi.'-'.$vp->jabatan.')'?></option>
                                        <?php } ?>
                                    </select>
                                    <?php
                  									}else{
                  										echo '<select name="pic" id="pic" class="form-control input-sm select2">

                                      </select>
                                      ';
                  								  } ?>

                                    <input type="hidden" name="pic_code" id="pic_code" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="pic_name" id="pic_name" class="form-control input-sm" readonly="readonly">
                                </div>
                            </div>
                        </div>
                      <!-- Data PIC -->
                    </div>

                    <div class="col-sm-6">
                      <div class="form-horizontal">

                        <!-- Data TOP -->
                        <div class="form-group ">
                          <label for="pic" class="col-sm-4 control-label">T.O.P <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                              <input name="top" id="top" class="form-control" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="<?php echo $data->top?>" required>
                              <span class="input-group-addon">Hari</span>
                            </div>
                          </div>
                        </div>
                        <!-- Data TOP -->

                        <!-- Data PPN -->
                        <div class="form-group ">
                          <label for="flagppnso" class="col-sm-4 control-label">Flag PPN <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">

                            <?php if (isset($data->ppn)) {
                              $nilppn = $data->ppn;
                            }else {
                              $nilppn=0;
                            } ?>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn" <?php if($nilppn != 0){echo "checked";} ?>>PPN (10%)
                                  </label>
                                </div>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn" <?php if($nilppn == 0){echo "checked";} ?> >Tanpa PPN
                                  </label>
                                  <input type="hidden" name="nilaippn" id="nilaippn" value="<?php echo $nilppn;?>" />
                                </div>
                          </div>
                        </div>
                        <!-- Data PPN -->

                        <!-- Data Cara Pembayaran -->
                        <div class="form-group ">
                          <label for="diskoncash" class="col-sm-4 control-label">Pembayaran <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">

                              <?php
                              if($data->persen_diskon_cash == 0){
                                ?>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="<?php echo $disc_cash ?>" onclick="setdiskoncash(this.value)" name="diskoncash"/>CASH
                                  </label>
                                </div>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="0" onclick="setdiskoncash(this.value)" name="diskoncash" checked="checked" />KREDIT
                                  </label>
                                  <input type="hidden" name="nilaidiskoncash" id="nilaidiskoncash" value="0" />
                                </div>

                              <?php }else{ ?>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="<?php echo $disc_cash ?>" onclick="setdiskoncash(this.value)" name="diskoncash" checked="checked"/>CASH
                                  </label>
                                </div>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="0" onclick="setdiskoncash(this.value)" name="diskoncash"/>KREDIT
                                  </label>
                                  <input type="hidden" name="nilaidiskoncash" id="nilaidiskoncash" value="3" />
                                </div>
                              <?php } ?>


                          </div>
                        </div>
                        <!-- Data Cara Pembayaran -->

                        <!-- Data Keterangan -->
                        <div class="form-group ">
                          <label for="keterangan" class="col-sm-4 control-label">Keterangan<font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-file"></i></span>
                              <textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan"><?php echo $data->keterangan?></textarea>
                            </div>
                          </div>
                        </div>
                        <!-- Data Keterangan -->

                      </div>
                    </div>



                  <!-- Data HEADER SO -->
                    <input type="hidden" name="no_so" id="no_so" class="form-control input-sm" readonly="readonly" value="<?php echo $data->no_so?>">
                    <input type="hidden" name="no_picking_list" id="no_picking_list" class="form-control input-sm" readonly="readonly" value="<?php echo $data->no_picking_list?>">
                    <input type="hidden" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly" value="<?php echo $data->dpp?>">
                    <input type="hidden" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly" value="<?php echo $data->total?>">
                    <input type="hidden" name="ppnso" id="ppnso" class="form-control input-sm" readonly="readonly" value="<?php echo $data->ppn?>">
                    <input type="hidden" name="persen_diskon_toko" id="persen_diskon_toko" value="<?php echo $data->persen_diskon_toko?>">
                    <input type="hidden" name="persen_diskon_cash" id="persen_diskon_cash" value="<?php echo $data->persen_diskon_cash?>">
                    <input type="hidden" name="diskon_toko" id="diskon_toko" value="<?php echo $data->diskon_toko?>">
                    <input type="hidden" name="diskon_cash" id="diskon_cash" value="<?php echo $data->diskon_cash?>">
                  <!-- Data HEADER SO -->


                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
  <div class="box-header">
    <h3>Form SO Detail</h3>
	</div>
  <hr style="border:1px  solid #ddd">
  <form id="form-so" method="post">
    <?php
    //$js_array_brg = json_encode($itembarang);
    //$arr_brg = implode(",", $itembarang);
     //print_r($arr_brg); ?>
    <div class="box-body">
        <div id="div-form">
          <?php //print_r($detail) ?>
          <table id="listadjust" class="table table-bordered table-striped dataTable" width="100%">
              <thead>
                  <tr>
                    <th class="text-right">
                      #
                    </th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th>Ket.Stok</th>
                    <th width="5%">Qty Order</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Total</th>
                  </tr>
              </thead>
              <tbody id="isi_so">
                <?php
                $i = 0;
                $dpp = 0;
                if ($detail) {
                  foreach ($detail as $key => $value) {
                    $i = $key+1;
                    $db = $this->db->query("select * FROM barang_stock WHERE id_barang = '$value[id_barang]' AND kdcab ='".$this->auth->user_cab()."'")->row();
                    $harga_net_sdisso = ($db->harga*((100-$db->diskon_standar_persen)/100))*((100-$db->diskon_promo_persen)/100)-($db->diskon_promo_rp);
                    if ($value['tipe_diskon_so'] == "rupiah_kurang") {
                      $harga_net = $harga_net_sdisso-$value['diskon_so'];
                    }elseif ($value['tipe_diskon_so'] == "rupiah_tambah") {
                      $harga_net = $harga_net_sdisso+$value['diskon_so'];
                    }else {
                      $harga_net = $harga_net_sdisso-($harga_net_sdisso*$value['diskon_so']/100);
                    }
                    ?>
                    <tr>
                      <td>
                        <a class="text-red hapus_item_js" href="javascript:void(0)" title="Hapus Item"><i class="fa fa-times"></i></a><span class="numbering"><?=$i?></span>
                      </td>
                      <td>
                        <select onchange="setitembarangx(this.value,this.id)" id="item_brg_so<?=$i?>" name="item_brg_so" class="form-control input-xs form_item_so select2" tabindex="-1" style="width:90%">
                          <option value=""></option>
                          <?php
                          foreach(@$barang as $k=>$v){
                            ?>
                            <option value="<?php echo $v->id_barang; ?>" <?php if ($v->id_barang == $value['id_barang']) {
                              echo "selected";
                            }?>>
                            <?php echo $v->id_barang ?> , <?php echo $v->nm_barang?> , <?php echo $v->kdcab ?>
                          </option><?php } ?>
                        </select>
                      </td>
                      <td>
                        <span id="item_brg_so<?=$i?>_satuan"><?=$db->satuan?></span>
                      </td>
                      <td>
                        <span id="item_brg_so<?=$i?>_stok">
                          Qty Stock:<?=$db->qty_stock?><br>
                          Qty Avl&emsp;&nbsp;:<?=$db->qty_avl?><br>
                        </span>
                      </td>
                      <td>
                        <span id="item_brg_so<?=$i?>_qty">
                          <input type="text" class="form-control input-sm qty_order number" id="qty_order_<?=$db->id_barang?>" onkeyup="qty_order_keyup('<?=$db->id_barang?>',this.value,'item_brg_so<?=$i?>');this.value = this.value.match(/^[0-9]+$/)" name="qty_order[]" style="width:100% !important;" value="<?=$value['qty_pending']?>">



                          <input type="hidden" class="form-control input-sm id_barang" id="id_barang_<?=$db->id_barang?>" name="id_barang[]" value="<?=$db->id_barang?>">
                          <input type="hidden" class="form-control input-sm harga" id="harga_<?=$db->id_barang?>" name="harga[]" value="<?=$db->harga?>">
                          <input type="hidden" class="form-control input-sm harga_nett" id="harga_nett_<?=$db->id_barang?>" name="harga_nett[]" value="<?=$harga_net_sdisso?>">
                          <input type="hidden" class="form-control input-sm diskon_standar_persen" id="diskon_standar_persen_<?=$db->id_barang?>" name="diskon_standar_persen[]" value="<?=$db->diskon_standar_persen?>">
                          <input type="hidden" class="form-control input-sm diskon_promo_persen" id="diskon_promo_persen_<?=$db->id_barang?>" name="diskon_promo_persen[]" value="<?=$db->diskon_promo_persen?>">
                          <input type="hidden" class="form-control input-sm qty_stock" id="qty_stock_<?=$db->id_barang?>" name="qty_stock[]" value="<?=$db->qty_stock?>">
                          <input type="hidden" class="form-control input-sm qty_avl" id="qty_avl_<?=$db->id_barang?>" name="qty_avl[]" value="<?=$db->qty_avl?>">
                          <input type="hidden" class="form-control input-sm landed_cost" id="landed_cost_<?=$db->id_barang?>" name="landed_cost[]" value="<?=$db->landed_cost?>">
                          <input type="hidden" class="form-control input-sm input_subtotal" id="total_<?=$db->id_barang?>" name="subtotal[]" value="<?=$value['subtotal']?>">
                        </span>
                      </td>
                      <td>
                        <span id="item_brg_so<?=$i?>_harga">
                          Normal&nbsp;&nbsp;&nbsp;:<strong><?=formatnomor($db->harga)?></strong><br>
                          Nett&emsp;&emsp;:<strong><span id="<?=$db->id_barang?>_harga_nett"><?=formatnomor($harga_net)?></span></strong><br>
                        </span>
                      </td>
                      <td>
                        <span id="item_brg_so<?=$i?>_diskon">
                          Standar&nbsp;&nbsp;&nbsp;:<strong><?=$db->diskon_standar_persen?> %</strong><br>
                          Promo&emsp;:<strong><?=$db->diskon_promo_persen?>%</strong><br>
                          <div class="input-group">
                            <div class="input-group-btn">
                              <?php if ($value['tipe_diskon_so'] == "persen") {
                                $style = 'none';
                                $tp = 'persen';
                                $symbol = '%';
                              }else {
                                $style = 'block';
                                if ($value['tipe_diskon_so'] == "rupiah_kurang") {
                                  $rk = 'checked="checked"';
                                  $rt = '';
                                  $tp = 'rupiah_kurang';
                                  $symbol = 'Rp';
                                }elseif ($value['tipe_diskon_so'] == "rupiah_tambah") {
                                  $rt = 'checked="checked"';
                                  $rk = '';
                                  $tp = 'rupiah_tambah';
                                  $symbol = 'Rp';
                                }else {
                                  $style = 'none';
                                  $tp = 'persen';
                                  $symbol = '%';
                                }
                              }
                              if (isset($value['diskon_so'])) {
                                $diskon_so = $value['diskon_so'];
                              }else {
                                $diskon_so = '0';
                              } ?>
                              <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tipe_disso_1<?=$db->id_barang?>" name="tipe_disso1[]" value="<?=$tp?>"><?=$symbol?><span class="caret"></span></button>
                              <input type="hidden" id="tipe_disso_<?=$db->id_barang?>" class="tipe_disso" name="tipe_disso[]" value="<?=$tp?>">

                              <ul class="dropdown-menu bg-dark">
                                <li><a href="javascript:void(0)" onclick="getdisso('%','<?=$db->id_barang?>')">Persen (%)</a></li>
                                <li><a href="javascript:void(0)" onclick="getdisso('Rp','<?=$db->id_barang?>')">Rupiah (Rp)</a></li>
                              </ul>
                            </div><!-- /btn-group -->
                            <input type="text" class="form-control input-sm disso number" aria-label="" name="disso[]" id="disso_<?=$db->id_barang?>" class="input-sm" value="<?=$diskon_so?>" >
                          </div><!-- /input-group -->
                          <div class="radio_disso_rp_<?=$db->id_barang?>" style="display:<?=$style?>">
                            <div class="radio-inline">
                              <label>
                                <input type="radio" class="radio_disso" value="tambah" id="<?=$i.'tambah'?>" name="radio_disso_rp_<?=$db->id_barang?>" <?=$rt?> >(+)
                              </label>
                            </div>
                            <div class="radio-inline">
                              <label>
                                <input type="radio" class="radio_disso" value="kurang" id="<?=$i.'kurang'?>" name="radio_disso_rp_<?=$db->id_barang?>" <?=$rk?> >(-)
                              </label>
                            </div>
                          </div>
                        </span>
                      </td>
                      <td>
                        <span id="item_brg_so<?=$i?>_total" class="subtotal_view"><?=formatnomor($value['subtotal'])?></span>
                      </td>
                    </tr>
                    <?php
                    $dpp += $value['subtotal'];
                  }
                }
                ?>
              </tbody>
              <tfoot id="input_tambahan">
                <tr>
                  <th class="text-right" colspan="7">
                    <strong>DPP:</strong>
                  </th>
                  <th>
                    <span id="dpp_view"><?=$dpp?></span>
                  </th>
                </tr>
                <tr>
                  <th class="text-right" colspan="7">
                    <strong>Diskon Toko:</strong>
                  </th>
                  <th>
                    <span id="dt_view">0</span>
                  </th>
                </tr>
                <tr>
                  <th class="text-right" colspan="7">
                    <strong>Diskon Cash:</strong>
                  </th>
                  <th>
                    <span id="dc_view">0</span>
                  </th>
                </tr>
                <tr>
                  <th class="text-right" colspan="7">
                    <strong>Total:</strong>
                  </th>
                  <th>
                    <span id="total_view">0</span>
                  </th>
                </tr>
                  <tr>
                      <th class="text-left" colspan="3">
                        <button class="btn btn-success" data-toggle="" title="Add" onclick="add_list()" id="button_add_list" type="button"><i class="fa fa-plus">&nbsp;</i>Tambah Item</button>
                        <a class="btn btn-danger" href="javascript:void(0)" data-toggle="" title="Add" onclick="remove_list()"><i class="fa fa-minus">&nbsp;</i>Kurangi Item</a>
                      </th>
                      <th class="text-right" colspan="5">
                          <a class="btn btn-danger" onclick="kembali()">
                              <i class="fa fa-refresh"></i><b> Kembali</b>
                          </a>
                          <button class="btn btn-primary" type="button" onclick="save()">
                              <i class="fa fa-save"></i><b> Simpan Data</b>
                          </button>
                          <button class="btn btn-primary" type="button" onclick="TEST()">
                              <i class="fa fa-save"></i><b> TEST</b>
                          </button>
                      </th>
                  </tr>
              </tfoot>

          </table>
        </div>
    </div>
  </form>
</div>


<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">

  var Table = $('#listadjust').dataTable({
    paging: false,
    "sDom": 'Bfrtip',
    "bFilter":false,
  });
  $(document).ready(function() {
    var tabb = $('#listadjust').DataTable();
    $('#listadjust tbody').on( 'click', 'a.hapus_item_js', function () {

      tabb
          .row( $(this).parents('tr') )
          .remove()
          .draw();

      if ($('#isi_so tr .dataTables_empty')[0]) {
        var x=1;
      }else {
        var x = $('#isi_so tr').length+1;
      }
      for (var i = 0; i < x; i++) {
        $('.numbering').eq(i-1).text(i);
      }
      testing($(this));
      hitung_total();
    } );
    jQuery(document).on( "keyup change", ".qty_order,.disso,.radio_disso", function(){
      var penting = $(this);
      testing(penting);

      var a = this.className;

      //var harga_net =
      if (a.indexOf("qty_order")) {

      }else {

      }
    });
    jQuery(document).on( "keyup", ".number", function(){
      $(this).val($(this).val().match(/^[0-9]+$/));
    });
    jQuery(document).on( "blur keyup", ".number", function(){
      if ($(this).val() == "") {
        $(this).val(0);
        testing($(this));
      }
      //$(this).val($(this).val().match(/^[0-9]+$/));
    });
    $('#listadjust tbody').on( 'change', 'select.form_item_so', function () {
      var tabb = $('#listadjust').DataTable();
      var a = $(this).val();
      var satuan = 0;
      var stok = 0;
      var xxc = tabb.row( $(this).parents('tr') );

      if(a != ""){
        console.log('1');
        $.ajax({
          type:"GET",
          url:siteurl+"salesorder/get_item_barang",
          data:"idbarang="+a,
          success:function(result){
            var data = JSON.parse(result);
            console.log(data);
            var idbar = "'"+data.id_barang+"'";
            var harga_net = (data.harga*((100-data.diskon_standar_persen)/100))*((100-data.diskon_promo_persen)/100)-(data.diskon_promo_rp);
            var satuan = data.satuan;
            var stok = 'Qty Stock:'+data.qty_stock+'<br>'+
            'Qty Avl&emsp;&nbsp;:'+data.qty_avl+'<br>';

            var qty =
              '<input type="text" class="form-control input-sm qty_order number" id="qty_order_'+data.id_barang+'" name="qty_order[]" style="width:100% !important;">'+



              '<input type="hidden" class="form-control input-sm id_barang" id="id_barang_'+data.id_barang+'" name="id_barang[]" value="'+data.id_barang+'">'+
              '<input type="hidden" class="form-control input-sm harga_normal" id="harga_'+data.id_barang+'" name="harga[]" value="'+data.harga+'">'+
              '<input type="hidden" class="form-control input-sm harga_nett" id="harga_nett_'+data.id_barang+'" name="harga_nett[]" value="'+harga_net+'">'+
              '<input type="hidden" class="form-control input-sm diskon_standar_persen" id="diskon_standar_persen_'+data.id_barang+'" name="diskon_standar_persen[]" value="'+data.diskon_standar_persen+'">'+
              '<input type="hidden" class="form-control input-sm diskon_promo_persen" id="diskon_promo_persen_'+data.id_barang+'" name="diskon_promo_persen[]" value="'+data.diskon_promo_persen+'">'+
              '<input type="hidden" class="form-control input-sm qty_stock" id="qty_stock_'+data.id_barang+'" name="qty_stock[]" value="'+data.qty_stock+'">'+
              '<input type="hidden" class="form-control input-sm qty_avl" id="qty_avl_'+data.id_barang+'" name="qty_avl[]" value="'+data.qty_avl+'">'+
              '<input type="hidden" class="form-control input-sm landed_cost" id="landed_cost_'+data.id_barang+'" name="landed_cost[]" value="'+data.landed_cost+'">'+
              '<input type="hidden" class="form-control input-sm input_subtotal" id="total_'+data.id_barang+'" name="subtotal[]" value="">'
            ;

            var harga =
              'Normal&nbsp;&nbsp;&nbsp;:<strong>'+data.harga+'</strong><br>'+
              'Nett&emsp;&emsp;:<strong><span id="'+a+'_harga_nett">'+harga_net+'</span></strong><br>'
            ;

            var diskon =
              'Standar&nbsp;&nbsp;&nbsp;:<strong>'+data.diskon_standar_persen+' %</strong><br>'+
              'Promo&emsp;:<strong>'+data.diskon_promo_persen+' %</strong><br>'+
              '  <div class="input-group">'+
              '    <div class="input-group-btn">'+
              '<input type="hidden" id="tipe_disso_'+data.id_barang+'" name="tipe_disso[]" value="persen" class="tipe_disso">'+
              '      <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tipe_disso_1'+data.id_barang+'" name="tipe_disso[]" value="persen">%<span class="caret"></span></button>'+
              '      <ul class="dropdown-menu bg-dark">'+
              '        <li><a href="javascript:void(0)" onclick="getdisso(\'%\',\''+data.id_barang+'\')">Persen (%)</a></li>'+
              '        <li><a href="javascript:void(0)" onclick="getdisso(\'Rp\',\''+data.id_barang+'\')">Rupiah (Rp)</a></li>'+
              '      </ul>'+
              '    </div><!-- /btn-group -->'+
              '    <input type="text" class="form-control input-sm disso number" aria-label="" name="disso[]" id="disso_'+data.id_barang+'" class="input-sm" value="0">'+
              '  </div><!-- /input-group -->'+
              '  <div class="radio_disso_rp_'+data.id_barang+'" style="display:none">'+
              '    <div class="radio-inline">'+
              '      <label>'+
              '        <input type="radio" value="tambah" class="radio_disso" name="radio_disso_rp_'+a+'" >(+)'+
              '      </label>'+
              '    </div>'+
              '    <div class="radio-inline">'+
              '      <label>'+
              '        <input type="radio" value="kurang" class="radio_disso" name="radio_disso_rp_'+a+'" >(-)'+
              '      </label>'+
              '    </div>'+
              '  </div>'
            ;

            tabb.cell(xxc, 2).data(data.satuan).draw();
            tabb.cell(xxc, 3).data(stok).draw();
            tabb.cell(xxc, 4).data(qty).draw();
            tabb.cell(xxc, 5).data(harga).draw();
            tabb.cell(xxc, 6).data(diskon).draw();



            console.log(data.satuan);
            }
          });
        }

    } );

    $("#idcustomer,#idsalesman,#pic,.select2").select2({
      placeholder: "Pilih",
      allowClear: true
    });

    var idcus = $('#idcustomer').val();
    $("#button_add_list").prop("disabled", true);
    if(idcus != ''){
      getpiccustomer(idcus);
      $("#button_add_list").prop("disabled", false);
    }

    $(".datepicker").datepicker({
      format : "yyyy-mm-dd",
      showInputs: true,
      autoclose:true
    });

    hitung_total();
  });


  function testing(penting){
    var tabel = $('#listadjust').DataTable();
    var rows = tabel.row( penting.parents('tr') );
    //alert(this.className);
    //console.log(penting.parents('tr').find("input.id_barang").val());
    var id_barang = penting.parents('tr').find("input.id_barang").val();
    var harga_normal = parseFloat(penting.parents('tr').find("input.harga_normal").val());
    var harga_nett = parseFloat(penting.parents('tr').find("input.harga_nett").val());
    var qty_order = parseFloat(penting.parents('tr').find("input.qty_order").val());
    var disso = parseFloat(penting.parents('tr').find("input.disso").val());
    var diskon_standar_persen = parseFloat(penting.parents('tr').find("input.diskon_standar_persen").val());
    var diskon_promo_persen = parseFloat(penting.parents('tr').find("input.diskon_promo_persen").val());
    var tipe_disso = penting.parents('tr').find("input.tipe_disso").val();
    var radio = $("input[name='radio_disso_rp_"+id_barang+"']:checked").val();
    if (tipe_disso == "persen") {
      var subtotal = (harga_nett-(harga_nett*disso/100))*qty_order;
      var harga_nett = (harga_nett-(harga_nett*disso/100));
    }else {
      if (radio == 'kurang') {
        var subtotal = (harga_nett-disso)*qty_order;
        var harga_nett = (harga_nett-disso);
      }else {
        var subtotal = (harga_nett+disso)*qty_order;
        var harga_nett = (harga_nett+disso);
      }
    }
    //console.log(radio);
    $('#total_'+id_barang).val(subtotal);
    hitung_total();
    penting.parents('tr').find("span.subtotal_view").text(formatCurrency((subtotal).toFixed(2),',','.',2))
    //tabel.cell(rows, 7).data(formatCurrency((subtotal).toFixed(2),',','.',2)).draw();
    $('#'+id_barang+'_harga_nett').text(formatCurrency((harga_nett).toFixed(2),',','.',2));
  }
  function TEST(){
    $("form#form-header-so :input").each(function(){
     var input = $(this); // This is the jquery object of the input, do what you will
     console.log($(this).attr('name') + " = " + $(this).val());
    });
    console.log("----------------------------------");
    $("form#form-so :input").each(function(){
     var input = $(this); // This is the jquery object of the input, do what you will
     console.log($(this).attr('name') + " = " + $(this).val());
    });

  }
  function getcustomer(){
    var idcus = $('#idcustomer').val();
    if(idcus != ''){
      $.ajax({
        type:"GET",
        url:siteurl+"salesorder/get_customer",
        data:"idcus="+idcus,
        success:function(result){
          var data = JSON.parse(result);
          $('#nmcustomer').val(data.nm_customer);
          $('#diskontoko').text(formatCurrency(data.diskon_toko*parseFloat($('#grandtotalso').val())/100,',','.',0));
          $('#distoko_text').text(data.diskon_toko+"%");
          $('#persen_diskon_toko').val(data.diskon_toko);
          getpiccustomer(idcus);
          $("#button_add_list").prop("disabled", false);
          //get_bidus(data.bidang_usaha)
          if ($('#bidang_usaha').val() != "") {
            if (data.bidang_usaha != $('#bidang_usaha').val()) {
              if ($('#totalview').text() == "0") {
                if (data.bidang_usaha == 'DISTRIBUTOR') {
                  $('#diskon_promo_persen').val(0);
                  $('#diskon_promo_rp').val(0);
                  $('#bidang_usaha').val(data.bidang_usaha);
                  hitung_total();
                }
                else {
                  $('#bidang_usaha').val(data.bidang_usaha);
                  hitung_total();
                }
              }else {
                $('#bidang_usaha').val(data.bidang_usaha);
                hitung_total();
                //reset_itemso();
              }
            }
          }else {
            $('#diskon_promo_persen').val(0);
            $('#diskon_promo_rp').val(0);
            $('#bidang_usaha').val(data.bidang_usaha);
          }
          resetform();
          hitung_total();
        }
      });
    }else {
      $("#button_add_list").prop("disabled", true);
    }
    hitung_total();
    //sethitung();

  }
  function remove_list(){
    var tabdua = $('#listadjust').DataTable();
    tabdua.row($('#isi_so tr').length-1).remove().draw();
  }
  function add_list(){
    var tabdua = $('#listadjust').DataTable();
    //tabdua.rows().remove();
    var arr_brg_json = <?php echo json_encode($itembarang); ?>;
    var arr_brg_string = JSON.stringify(arr_brg_json);
    var arr_brg = JSON.parse(arr_brg_string);
    if ($('#isi_so tr .dataTables_empty')[0]) {
      var i=1;
    }else {
      var i = $('#isi_so tr').length+1;
    }

    tabdua.row.add([
      '<a class="text-red hapus_item_js" href="javascript:void(0)" title="Hapus Item"><i class="fa fa-times"></i></a><span class="numbering">'+i+'</span>',
      '<select onkeyup="setitembarang(this.value,this.id)" id="item_brg_so'+i+'" name="item_brg_so" class="form-control input-xs form_item_so select2" style="width: 100%;" tabindex="-1">'+
      '<option value=""></option>'+
      '<?php
      foreach(@$barang as $k=>$v){
        ?>'+
        '<option value='+'<?php echo $v->id_barang; ?>'+'>'+
        '<?php echo $v->id_barang ?>'+' , '+'<?php echo $v->nm_barang?>'+' , '+'<?php echo $v->kdcab ?>'+
        '</option>'+'<?php } ?>'+
        '</select>',
        '<span id="item_brg_so'+i+'_satuan"></span>',
        '<span id="item_brg_so'+i+'_stok"></span>',
        '<span id="item_brg_so'+i+'_qty"></span>',
        '<span id="item_brg_so'+i+'_harga"></span>',
        '<span id="item_brg_so'+i+'_diskon"></span>',
        '<span id="item_brg_so'+i+'_total" class="subtotal_view"></span>',


      ]).draw();
      $(".form_item_so").select2({
        placeholder: "Pilih",
        allowClear: true
      });
    }
  function reset_itemso(){
    $.ajax({
      type:"GET",
      url:siteurl+"salesorder/hapus_item_so_all",
      //data:"id_user="+<?= $session['id_user']?>,
      success:function(result){
        swal({
          title: "Peringatan",
          text: "Item SO dihapus karena berbeda Bidang Usaha dengan yang sebelumnya!",
          type: "success",
          timer: 2000,
          showConfirmButton: false
        });
        resetform();
        setTimeout(function(){
          window.location.reload();
        },1600);
      }
    });
  }
  function get_bidus(bid){
    if (bid == 'DISTRIBUTOR') {
      $('#diskon_promo_persen').remove();
      $('#diskon_promo_rp').remove();
    }else if (bid == 'AGEN') {
      $('#diskon_promo_persen').name('diskon_promo_persen');
      $('#diskon_promo_rp').name('diskon_promo_rp');
    }else {
      alert(data.bidang_usaha);
    }
  }
  function hitung_total(){
    var sum = 0;
    var x = document.getElementsByClassName('input_subtotal');
    var i;
    for (i = 0; i < x.length; i++) {
        sum += parseFloat(x[i].value);
    }
    var dc = 0;
    var dt = 0;
    var dpp = sum;
    var total = sum;
    var dt_nominal = 0;
    var dc_nominal = 0;
    if ($('#persen_diskon_toko').val() != 0) {
      var dt = parseFloat($('#persen_diskon_toko').val());
      var dt_nominal = parseFloat(total*dt/100);
      var total = total-(total*dt/100);
    }
    if ($('#persen_diskon_cash').val() != 0) {
      var dc = parseFloat($('#persen_diskon_cash').val());
      var dc_nominal = parseFloat(total*dc/100);
      var total = total-(total*dc/100);
    }
    //alert(dc);
    //alert(sum);
    $('#dpp_view').text(num(dpp));
    $('#dt_view').text(dt+'%'+'('+num(dt_nominal)+')');
    $('#dc_view').text(dc+'%'+'('+num(dc_nominal)+')');
    $('#total_view').text(num(total));
    $('#totalso').val(total);
    $('#dppso').val(sum);
  }
  function num(n) {
    return (n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  }
  function filterAngka(a){
    if(!a.match(/^[0-9]+$/)){
      return 0;
    }else{
      return 1;
    }
  }
  function kembali(){
    window.location.href = siteurl+"salesorder/";
  }
  function pembulatan(x){
    var string_harga = x.toString();
    var cek = parseInt(string_harga.substr(-3));
    if (cek > 0) {
      var pembantu = 1000 - cek;
      var hasil = parseInt(x) + parseInt(pembantu);
      return hasil;
    }else {
      return x;
    }
  }
  function getpiccustomer(idcus){
    $.ajax({
      type:"GET",
      url:siteurl+"salesorder/get_pic_customer",
      data:"idcus="+idcus,
      success:function(result){
        $('#pic').html("");
        $('#pic').html(result);

      }
    });
  }
  function getsalesman(){
    var idsls = $('#idsalesman').val();
    if(idsls != ''){
      $.ajax({
        type:"GET",
        url:siteurl+"salesorder/get_salesman",
        data:"idsales="+idsls,
        success:function(result){
          var data = JSON.parse(result);
          $('#nmsalesman').val(data.nama_karyawan);
        }
      });
    }
  }
  function save(){
    //sethitung();
    var formdata = $("#form-header-so,#form-so").serialize();
    if($('#idcustomer').val() != "" && $('#pic').val() != "" && $('#top').val() != "" && $('#tglso').val() != "" && $('#idsalesman').val() != ""){
      $.ajax({
        url: siteurl+"salesorder/saveso_pending",
        dataType : "json",
        type: 'POST',
        data: formdata,
        success: function(result){
          if(result.save=='1'){
            swal({
              title: "Sukses!",
              text: result['msg'],
              type: "success",
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(function(){
              window.location.href=siteurl+'salesorder';
            },1600);
          } else {
            swal({
              title: "Gagal!",
              text: "Data Gagal Di Simpan",
              type: "error",
              timer: 1500,
              showConfirmButton: false
            });
          };
        },
        error: function(){
          swal({
            title: "Gagal!",
            text: "Ajax Data Gagal Di Proses",
            type: "error",
            timer: 1500,
            showConfirmButton: false
          });
        }
      });
    }else{
      swal({
        title: "Peringatan!",
        text: "Silahkan pilih customer",
        type: "warning",
        timer: 1500,
        showConfirmButton: false
      });
    }
  }
  function formatCurrency(amount, decimalSeparator, thousandsSeparator, nDecimalDigits){
      var num = parseInt( amount );
      decimalSeparator = decimalSeparator || '.';
      thousandsSeparator = thousandsSeparator || ',';
      nDecimalDigits = nDecimalDigits == null? 2 : nDecimalDigits;
      var fixed = num.toFixed(nDecimalDigits);
      var parts = new RegExp('^(-?\\d{1,3})((?:\\d{3})+)(\\.(\\d{' + nDecimalDigits + '}))?$').exec(fixed);
      if(parts){
          return parts[1] + parts[2].replace(/\d{3}/g, thousandsSeparator + '$&') + (parts[4] ? decimalSeparator + parts[4] : '');
      }else{
          return fixed.replace('.', decimalSeparator);
      }
  }

  function get_disso(id,val,idbar,no){
    console.log(id+"/"+val+"/"+idbar+"/"+$("#tipe_disso_"+idbar).val());
    var harga_normal = parseInt($("#harga_"+idbar).val());
    var disstd = parseInt($("#diskon_standar_persen_"+idbar).val());
    var dispro = parseInt($("#diskon_promo_persen_"+idbar).val());
    var qty = parseInt($("#qty_order_"+idbar).val());
    if ($("#qty_order_"+idbar).val() == '') {
      var qty = 0;
    }
    //var harga_total = parseInt((harga_normal*(100 - disstd)/100)*(100 - dispro)/100)*qty;
    var harga_nett = parseFloat((harga_normal*(100 - disstd)/100)*(100 - dispro)/100);
    if ($("#disso_"+idbar).val() !== '') {
      var disso = parseFloat($("#disso_"+idbar).val());
    }else {
      var disso = 0;
    }
    //alert($("#tipe_disso_"+idbar).val());
    if ($("#tipe_disso_"+idbar).val() === "persen") {
      if (parseInt($("#disso_"+idbar).val()) > 100) {
        swal({
          title: "Peringatan!",
          text: "Tidak boleh lebih dari 100%",
          type: "warning",
          timer: 1500,
          showConfirmButton: false
        });
        $("#disso_"+idbar).val(0);
      }else {
        var net = harga_nett*(100 - disso)/100;
        var subnet = (net).toFixed(2)*qty;
        $("#harga_nett_"+idbar).val((net).toFixed(2));
        $("#"+idbar+"_harga_nett").text(formatCurrency((net).toFixed(2),',','.',2));
        $("#"+id+"_total").text(formatCurrency(subnet,',','.',0));
        $("#"+id+"_total_input").val(subnet);
        $('#total_'+idbar).val(subnet);
      }
    }else {
      var radioValue = $("input[name='radio_disso_rp["+no+"]']:checked").val();
      if (radioValue == "tambah") {
        $("#harga_nett_"+idbar).val(harga_nett + disso);
        $("#"+idbar+"_harga_nett").text(formatCurrency((harga_nett + disso).toFixed(2),',','.',2));
        $("#"+id+"_total").text(formatCurrency(((harga_nett + disso)*qty).toFixed(2),',','.',2));
        $('#total_'+idbar).val((harga_nett + disso)*qty);
      }else {
        $("#harga_nett_"+idbar).val(harga_nett - disso);
        $("#"+idbar+"_harga_nett").text(formatCurrency((harga_nett - disso).toFixed(2),',','.',2));
        $("#"+id+"_total").text(formatCurrency(((harga_nett - disso)*qty).toFixed(2),',','.',2));
        $('#total_'+idbar).val((harga_nett - disso)*qty);
      }
    }

    hitung_total();

  }
  function setitembarangx(a,b){

    if ($('#isi_so tr .dataTables_empty')[0]) {
      var i=1;
    }else {
      var i = $('#isi_so tr').length-1;
    }
    //var idbarang = $('#'+a).val();
    var qty = $('#qty_order').val();
    var qty_sup = $('#qty_supply').val();
    //alert(a,);
    if(a != ""){
      $.ajax({
        type:"GET",
        url:siteurl+"salesorder/get_item_barang",
        data:"idbarang="+a,
        success:function(result){
          var data = JSON.parse(result);
          console.log(data);
          var id_span = "'"+b+"'";
          var idbar = "'"+data.id_barang+"'";
          var harga_net = (data.harga*((100-data.diskon_standar_persen)/100))*((100-data.diskon_promo_persen)/100)-(data.diskon_promo_rp);

          $('#'+b+'_satuan').text(data.satuan);
          $('#'+b+'_stok').html(
            'Qty Stock:'+data.qty_stock+'<br>'+
            'Qty Avl&emsp;&nbsp;:'+data.qty_avl+'<br>'
          );
          $('#'+b+'_qty').html(
            '<input type="text" class="form-control input-sm" id="qty_order_'+data.id_barang+'" onkeyup="qty_order_keyup('+idbar+',this.value,'+id_span+');this.value = this.value.match(/^[0-9]+$/)" name="qty_order[]" style="width:100% !important;">'+



            '<input type="hidden" class="form-control input-sm" id="id_barang_'+data.id_barang+'" name="id_barang[]" value="'+data.id_barang+'">'+
            '<input type="hidden" class="form-control input-sm" id="harga_'+data.id_barang+'" name="harga[]" value="'+data.harga+'">'+
            '<input type="hidden" class="form-control input-sm" id="harga_nett_'+data.id_barang+'" name="harga_nett[]" value="'+harga_net+'">'+
            '<input type="hidden" class="form-control input-sm" id="diskon_standar_persen_'+data.id_barang+'" name="diskon_standar_persen[]" value="'+data.diskon_standar_persen+'">'+
            '<input type="hidden" class="form-control input-sm" id="diskon_promo_persen_'+data.id_barang+'" name="diskon_promo_persen[]" value="'+data.diskon_promo_persen+'">'+
            '<input type="hidden" class="form-control input-sm" id="qty_stock_'+data.id_barang+'" name="qty_stock[]" value="'+data.qty_stock+'">'+
            '<input type="hidden" class="form-control input-sm" id="qty_avl_'+data.id_barang+'" name="qty_avl[]" value="'+data.qty_avl+'">'+
            '<input type="hidden" class="form-control input-sm" id="landed_cost_'+data.id_barang+'" name="landed_cost[]" value="'+data.landed_cost+'">'+
            '<input type="hidden" class="form-control input-sm input_subtotal" id="total_'+data.id_barang+'" name="subtotal[]" value="">'
          );

          $('#'+b+'_harga').html(
            'Normal&nbsp;&nbsp;&nbsp;:<strong>'+data.harga+'</strong><br>'+
            'Nett&emsp;&emsp;:<strong><span id="'+a+'_harga_nett">'+harga_net+'</span></strong><br>'
          );

          $('#'+b+'_diskon').html(
            'Standar&nbsp;&nbsp;&nbsp;:<strong>'+data.diskon_standar_persen+' %</strong><br>'+
            'Promo&emsp;:<strong>'+data.diskon_promo_persen+' %</strong><br>'+
            '  <div class="input-group">'+
            '    <div class="input-group-btn">'+
            '<input type="hidden" id="tipe_disso_'+data.id_barang+'" name="tipe_disso[]" value="persen">'+
            '      <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tipe_disso_1'+data.id_barang+'" name="tipe_disso[]" value="persen">%<span class="caret"></span></button>'+
            '      <ul class="dropdown-menu bg-dark">'+
            '        <li><a href="javascript:void(0)" onclick="getdisso(\'%\',\''+data.id_barang+'\')">Persen (%)</a></li>'+
            '        <li><a href="javascript:void(0)" onclick="getdisso(\'Rp\',\''+data.id_barang+'\')">Rupiah (Rp)</a></li>'+
            '      </ul>'+
            '    </div><!-- /btn-group -->'+
            '    <input type="text" class="form-control form_item_so input-sm" aria-label="" name="disso[]" id="disso_'+data.id_barang+'" class="input-sm" value="0" onkeyup="get_disso(\''+b+'\',this.value,\''+data.id_barang+'\',\''+i+'\')">'+
            '  </div><!-- /input-group -->'+
            '  <div class="radio_disso_rp_'+data.id_barang+'" style="display:none">'+
            '    <div class="radio-inline">'+
            '      <label>'+
            '        <input type="radio" value="tambah" name="radio_disso_rp['+i+']" onchange="get_disso(\''+b+'\',document.getElementById(\'disso_'+data.id_barang+'\').value,\''+data.id_barang+'\',\''+i+'\')">(+)'+
            '      </label>'+
            '    </div>'+
            '    <div class="radio-inline">'+
            '      <label>'+
            '        <input type="radio" value="kurang" name="radio_disso_rp['+i+']" onchange="get_disso(\''+b+'\',document.getElementById(\'disso_'+data.id_barang+'\').value,\''+data.id_barang+'\',\''+i+'\')">(-)'+
            '      </label>'+
            '    </div>'+
            '  </div>'
          );



          }
        });
      }
      //getcustomer();
    }
  function qty_order_keyup(a,b,c){
    var id_barang = a;
    var value     = b;
    var id_span   = c;

    //var landed_cost = parseFloat($('#landed_cost_'+a).val())*b;
    var total = parseFloat($('#harga_nett_'+a).val())*b;
    $('#'+c+'_total').text(formatCurrency(total,',','.',0));
    $('#'+c+'_total_input').val(total);
    $('#total_'+a).val(total);
    hitung_total();
    //alert('#'+c+'_total');
  }
  function getdisso(dis,id){
    $('#tipe_disso_1'+id).html(dis);
    if (dis == "%") {
      $('#tipe_disso_'+id).val('persen');
      $('.radio_disso_rp_'+id).hide();
    }else {
      $('#tipe_disso_'+id).val('rupiah_');
      $('.radio_disso_rp_'+id).show();
    }
  }
  function resetform(){

      $('#item_brg_so').val('').trigger('change');
      $('.form_item_so').val('');
      $('#nama_barang').val('');
      $('#harga').val(0);
      $('#satuan').val('');
      $('#jenis').val('');
      $('#qty_order').val(0);
      $('#qty_supply').val(0);
      $('#qty_pending').val(0);
      $('#qty_cancel').val(0);
      $('#qty_avl').val(0);
      $('#total').val(0);
  }
  function setppn(ppn){
      if(ppn == 10) {
          $('#nilaippn').val(10);
      }else{
          $('#nilaippn').val(0);
      }
  }
  function setdiskoncash(d){
          $('#persen_diskon_cash').val(d);
          $('#nilaidiskoncash').val(d);
          hitung_total();
  }
  function setitembarang_bonus(){
      var idbarang = $('#item_brg_so_bonus').val();
      if(idbarang != ""){
          $('#submit_bonus').prop('disabled', false);
      }
      else {
        $('#submit_bonus').prop('disabled', true);
      }

      if(idbarang != ""){
          $.ajax({
              type:"GET",
              url:siteurl+"salesorder/get_item_barang_bonus",
              data:"idbarang="+idbarang,
              success:function(result){
                  var data = JSON.parse(result);
                  console.log(data);
                  $('#qty_avl_bonus').val(data.qty_avl);

                  $('#nama_barang_bonus').val(data.nm_barang);
                  $('#harga_bonus').val(data.harga);
                  $('#satuan_bonus').val(data.satuan);
                  $('#jenis_bonus').val(data.jenis);

              }
          });
      }
  }

</script>
