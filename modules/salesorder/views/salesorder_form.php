<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-so" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <?php
                    $headersession = $this->session->userdata('header_so');
                    ?>
                    <hr>
                    <?php
                    $pic = $this->Salesorder_model->get_pic_customer($headersession['idcustomer'])->result();
                    $disc_cash = $this->Salesorder_model->get_data(array('diskon'=>'CASH'),'diskon');
                    foreach ($disc_cash as $key => $value) {
                      $disc_cash = $value->persen;
                    }
                    ?>
                    <div class="col-sm-6">

                      <div class="form-group">
                          <label for="idcustomer" class="col-sm-4 control-label">NOMOR SO LAMA <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">
                              <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-user"></i></span>
                              <input type="text" name="ns" id="ns" class="form-control input-sm" value="<?php echo $headersession['ns']?>">
                              </div>
                          </div>
                      </div>
                        <div class="form-group">
                            <label for="idcustomer" class="col-sm-4 control-label">Nama Customer <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 100%;" tabindex="-1" onchange="getcustomer()" required>
                                <option value=""></option>
                                <?php
                                foreach(@$customer as $kc=>$vc){
                                ?>
                                <option value="<?php echo $vc->id_customer; ?>" <?php echo set_select('nm_customer', $vc->id_customer, isset($headersession['nmcustomer']) && $headersession['idcustomer'] == $vc->id_customer) ?>>
                                    <?php echo $vc->id_customer.' , '.$vc->nm_customer ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmcustomer" id="nmcustomer" class="form-control input-sm" value="<?php echo $headersession['nmcustomer']?>">
                                <input type="hidden" name="disc_toko" id="disc_toko" class="form-control input-sm" value="<?php echo $headersession['disc_toko']?>">
                                </div>
                            </div>
                        </div>
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
                                    if($headersession['idsalesman'] == $vm->id_karyawan){
                                        $selected = 'selected="selected"';
                                    }
                                ?>
                                <option value="<?php echo $vm->id_karyawan; ?>" <?php echo $selected?>>
                                    <?php echo $vm->nama_karyawan ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmsalesman" id="nmsalesman" class="form-control input-sm" value="<?php echo $headersession['nmsalesman']?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php
                            if($headersession){
                                $tglso=$headersession['tglso'];
                            }else{
                                $tglso=date('Y-m-d');
                            }
                            ?>
                            <label for="tglso" class="col-sm-4 control-label">Tanggal <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglso" id="tglso" class="form-control input-sm datepicker" value="<?php echo $tglso?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="pic" class="col-sm-4 control-label">PIC <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <!--input name="pic" id="pic" class="form-control" value="<?php echo $headersession['pic']?>"-->
                                    <?php
                                    if($headersession['pic']){
                                    ?>
                                    <select name="pic" id="pic" class="form-control input-sm select2">
                                        <?php
                                        foreach($pic as $kp=>$vp){
                                            $selected ='';
                                            if($headersession['pic'] == $vp->id_pic){
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
                  									?>


                                                      <?php } ?>
                  									<input type="hidden" name="pic_code" id="pic_code" class="form-control input-sm" readonly="readonly">
                  									<input type="hidden" name="pic_name" id="pic_name" class="form-control input-sm" readonly="readonly">

                                    <input type="hidden" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="ppnso" id="ppnso" class="form-control input-sm" value="10" readonly="readonly">
                                    <input type="hidden" name="persen_diskon_toko" id="persen_diskon_toko" value="<?php echo $headersession['persen_diskon_toko']?>">
                                    <input type="hidden" name="persen_diskon_cash" id="persen_diskon_cash" value="<?php echo $headersession['persen_diskon_cash']?>">
                                    <input type="hidden" name="diskon_toko" id="diskon_toko" value="<?php echo $headersession['diskon_toko']?>">
                                    <input type="hidden" name="diskon_cash" id="diskon_cash" value="<?php echo $headersession['diskon_cash']?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ">
                            <label for="pic" class="col-sm-4 control-label">T.O.P <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                                    <input name="top" id="top" class="form-control" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="<?php echo $headersession['top']?>">
                                    <span class="input-group-addon">Hari</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="flagppnso" class="col-sm-4 control-label">Flag PPN <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <?php
                                    if($headersession['nilaippn'] == 0){
                                    ?>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn"/>PPN (10%)
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn" checked="checked" />Tanpa PPN
                                    <input type="hidden" name="nilaippn" id="nilaippn" value="0" />
                                    <?php }else{ ?>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn" checked="checked"/>PPN (10%)
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn"/>Tanpa PPN
                                    <input type="hidden" name="nilaippn" id="nilaippn" value="10" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="diskoncash" class="col-sm-4 control-label">Pembayaran <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <?php
                                    if($headersession['diskoncash'] == 0){
                                    ?>
                                    <input type="radio" value="<?php echo $disc_cash ?>" onclick="setdiskoncash(this.value)" name="diskoncash"/>CASH
                                    <input type="radio" value="0" onclick="setdiskoncash(this.value)" name="diskoncash" checked="checked" />KREDIT
                                    <input type="hidden" name="nilaidiskoncash" id="nilaidiskoncash" value="0" />
                                    <?php }else{ ?>
                                    <input type="radio" value="<?php echo $disc_cash ?>" onclick="setdiskoncash(this.value)" name="diskoncash" checked="checked"/>CASH
                                    <input type="radio" value="0" onclick="setdiskoncash(this.value)" name="diskoncash"/>KREDIT
                                    <input type="hidden" name="nilaidiskoncash" id="nilaidiskoncash" value="3" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="keterangan" class="col-sm-4 control-label">Keterangan<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-file"></i></span>
                                    <textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan"><?php echo $headersession['keterangan']?></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
    <div class="box-body">
        <form id="form-detail-so" method="post">
        <table class="table table-bordered" width="100%">
            <tr>
                <th class="text-center" colspan="8">FORM ITEM DETAIL</th>
            </tr>
            <tr>
                <td width="9%"><b>PRODUCT SET</b></td>
                <td colspan="2" width="20%">
                  <span id="ket_item" data-toggle="popover" data-placement="bottom" data-content="Silahkan pilih customer terlebih dahulu!">
                    <select onchange="setitembarang()" id="item_brg_so" name="item_brg_so" class="form-control input-xs" style="width: 100%;" tabindex="-1" required>
                            <option value=""></option>
                            <?php
                            foreach(@$itembarang as $k=>$v){
                            ?>
                            <option value="<?php echo $v->id_barang; ?>" <?php echo set_select('nm_barang', $v->id_barang, isset($data->nm_barang) && $data->id_barang == $v->id_barang) ?>>
                                <?php echo $v->id_barang.' , '.$v->nm_barang ?>
                            </option>
                            <?php } ?>
                        </select>
                  </span>
                </td>
                <td width="5%" class="text-right"><b>HARGA NORMAL</b></td>
                <td width="10%"><input type="text" name="harga_normal" id="harga_normal" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="Harga Normal sebelum diskon standar dan Promo(Persen maupun Rupiah)" readonly="readonly"></td>
                <td width="5%" class="text-right"><b>HARGA SETELAH DISKON</b></td>
                <td width="10%"><input type="text" name="harga" id="harga" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="Harga setelah diskon standar dan Promo(Persen maupun Rupiah)" readonly="readonly"></td>
            </tr>
            <tr>
                <td width="5%" class="text-center"><b>QTY ORDER</b></td>
                <td width="5%" class="text-center"><b>QTY BONUS</b></td>
                <td width="5%" class="text-center"><b>QTY AVL</b></td>
                <td width="5%" class="text-center"><b>QTY CONFIRM</b></td>
                <td width="5%" class="text-center"><b>QTY PENDING</b></td>
                <td width="5%" class="text-center"><b>QTY CANCEL</b></td>
            </tr>
            <tr>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_order" id="qty_order" class="form-control input-sm" required="required">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_bonus" id="qty_bonus" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="Qty didapat dari diskon bonus" readonly>
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_avl" id="qty_avl" class="form-control input-sm" readonly="readonly">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_supply" id="qty_supply" class="form-control input-sm" onkeyup="hitungso()" required="required">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_pending" id="qty_pending" class="form-control input-sm" onkeyup="hitungcancel()" required="required">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_cancel" id="qty_cancel" class="form-control input-sm" readonly="readonly">
                    <input type="hidden" name="nama_barang" id="nama_barang" class="form-control input-sm">
                    <input type="hidden" name="satuan" id="satuan" class="form-control input-sm">
                    <input type="hidden" name="jenis" id="jenis" class="form-control input-sm">
                    <input type="hidden" name="total" id="total" class="form-control input-sm">
                </td>
                <td class="text-center" colspan="2" rowspan="4" style="border-left:solid 1px #f4f4f4;vertical-align:middle" width="5%">
                    <button class="btn btn-success btn-sm" type="submit" id="submit" name="save"><i class="fa fa-plus"></i> Tambah</button>
                </td>
            </tr>
            <tr>
                <td></td>
                <td width="5%" class="text-center"><b>Diskon Std.</b></td>
                <td width="5%" class="text-center" colspan="2"><b>Diskon Promo</b></td>
                <td width="5%" class="text-center" colspan="2"><b>Diskon QTY</b></td>
            </tr>
            <tr>
                <td></td>
                <td width="10%" class="text-center">
                  <b>Persen</b>
                  <div class="input-group">
                    <input type="text" name="diskon_standar_persen" id="diskon_standar_persen" class="form-control input-sm" readonly>
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                  </div>
                </td>
                <td width="10%" class="text-center">
                  <b>Persen</b>
                  <div class="input-group">
                    <input type="text" name="diskon_promo_persen" id="diskon_promo_persen" class="form-control input-sm" readonly>
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                  </div>
                </td>
                <td width="10%" class="text-center">
                  <b>Rupiah</b>
                  <div class="input-group">
                    <span class="input-group-addon">Rp</span>
                    <input type="text" name="diskon_promo_rp" id="diskon_promo_rp" class="form-control input-sm" readonly>
                  </div>
                </td>
                <td width="10%" class="text-center">
                  <b>Ketentuan</b>
                    <input type="text" name="diskon_jika_qty" id="diskon_jika_qty" class="form-control input-sm" readonly>
                </td>
                <td width="10%" class="text-center">
                  <b>Bonus</b>
                    <input type="text" name="diskon_qty_gratis" id="diskon_qty_gratis" class="form-control input-sm" readonly>
                </td>

            </tr>
            <tr>
                <td></td>
                <td width="5%" class="text-center" colspan="2"><b>PRODUCT SET BONUS :</b></td>
                <td width="5%" class="text-center" colspan="2">
                  <select onchange="setitembarang()" id="item_brg_so_bonus" name="item_brg_so_bonus" class="form-control input-xs" style="width: 100%;" tabindex="-1" disabled>
                          <option value=""></option>
                          <?php
                          foreach(@$itembarang as $k=>$v){
                          ?>
                          <option value="<?php echo $v->id_barang; ?>" <?php echo set_select('nm_barang', $v->id_barang, isset($data->nm_barang) && $data->id_barang == $v->id_barang) ?>>
                              <?php echo $v->id_barang.' , '.$v->nm_barang ?>
                          </option>
                          <?php } ?>
                      </select>
                </td>
            </tr>
        </table>
        </form>
        <div id="div-form">
        <table id="salesorderitemnya" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th>Stok Avl</th>
                    <th>Qty Order</th>
                    <th>Qty Confirm</th>
                    <th>Qty Pending</th>
                    <th>Qty Cancel</th>
                    <th>Harga</th>
                    <th>Subtotal Diskon (%)</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand = 0;
                if(@$listitembarang){
                $n=1;
                foreach(@$listitembarang as $ks=>$vs){
                    $grand += $vs->subtotal;
                    $no = $n++;
                ?>
                <tr>
                    <td class="text-center"><?php echo $no?></td>
                    <td><?php echo $vs->id_barang.' / '.$vs->nm_barang?></td>
                    <td><?php echo $vs->satuan?></td>
                    <td><?php echo $vs->stok_avl?></td>
                    <td><?php echo $vs->qty_order?></td>
                    <td><?php echo $vs->qty_booked?></td>
                    <td><?php echo $vs->qty_pending?></td>
                    <td><?php echo $vs->qty_cancel?></td>
                    <td><?php echo formatnomor($vs->harga)?></td>
                    <td><span id="pop_diskon" data-toggle="popover" data-placement="bottom" data-content="Jumlah Seluruh Diskon"><?php echo formatnomor($vs->diskon)?></span></td>
                    <td class="text-right"><?php echo formatnomor($vs->subtotal)?></td>
                    <td class="text-center">
                        <a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vs->no_so?>','<?php echo $vs->id_barang?>')"><i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="9" class="text-right">DPP : </th>
                    <th colspan="2" class="text-right"><?php echo formatnomor($grand)?>
                    <input type="hidden" name="grandtotalso" id="grandtotalso" value="<?php echo $grand?>"></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="9" class="text-right">Diskon Toko : </th>
                    <th colspan="2" class="text-right"><span id="diskontoko"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="9" class="text-right">Diskon Cash (3%): </th>
                    <th colspan="2" class="text-right"><span id="diskoncash"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="9" class="text-right">PPN : </th>
                    <th colspan="2" class="text-right"><span id="ppnview"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="9" class="text-right">GRAND TOTAL : </th>
                    <th colspan="2" class="text-right"><span id="totalview"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="saveheaderso()">
                            <i class="fa fa-save"></i><b> Simpan Data SO</b>
                        </button>
                    </th>
                </tr>
            </tfoot>

        </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(document).ready(function() {
        $("#item_brg_so,#idcustomer,#idsalesman,#pic").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $("#item_brg_so").prop("disabled", true);
        var a = $('#idcustomer').val();
        //alert(a);
        if (a != '') {
          $("#item_brg_so").prop("disabled", false);
        }
        $("#item_brg_so").on('mouseover', function(){
          $('#item_brg_so').popover('show')
        });

        $("#ket_item").on('click', function(){
          var a = $('#idcustomer').val();
          if (a !== '') {
            //alert(a);
            $( "#ket_item" ).popover('destroy');
          }
          else {

            $( "#ket_item" ).popover('show');
          }
        });
        $('#idcustomer').on('change', function(){
          var a = $('#idcustomer').val();
          if (a !== '') {
            $( "#ket_item" ).popover('destroy');
            $("#item_brg_so").prop("disabled", false);
          }
          else {

            $( "#ket_item" ).popover('show');
          }
        });


        $("#pop_diskon").on('mouseover', function(){
          $('#pop_diskon').popover('show')
        });
        $("#pop_diskon").on('mouseout', function(){
          $('#pop_diskon').popover('hide')
        });
        $("#qty_supply").on('keyup', function(){
          $("#qty_pending").val(parseInt($('#qty_order').val()) - parseInt($('#qty_supply').val()));
          $("#qty_cancel").val(parseInt($('#qty_order').val()) - parseInt($('#qty_pending').val()) - parseInt($('#qty_supply').val()) );
        });
        $("#qty_order").on('keyup', function(){
          //filterAngka(this.value);
          this.value = this.value.match(/^[0-9]+$/);
            var qo = parseInt($("#qty_order").val());
            var ket_bonus = parseInt($("#diskon_jika_qty").val());
            var bonus = parseInt(qo/ket_bonus);
            if (ket_bonus == 0) {
              bonus = 0;
            }
            var harga = parseInt($('#harga').val());
            var avl = parseInt($('#qty_avl').val());
            var qty = parseInt($('#qty_supply').val());
            var order = parseInt($('#qty_order').val());
            var diskon = parseInt($('#diskon').val());
            //var qtybonus = parseInt($('#qty_bonus').val());
            var qtybonus = parseInt(0);

            $("#qty_bonus").val(bonus);
            $("#qty_supply").val(parseInt(qo));
            if ( parseInt($('#qty_supply').val()) > parseInt($('#qty_avl').val()) ) {
              $("#qty_supply").val(parseInt($('#qty_avl').val()));
              $("#qty_pending").val(parseInt($('#qty_order').val()) - parseInt($('#qty_avl').val()) + bonus);
              $("#qty_cancel").val(parseInt($('#qty_order').val()) + parseInt($('#qty_bonus').val()) - parseInt($('#qty_pending').val()) - parseInt($('#qty_supply').val()) );
            }
            else {
              $("#qty_supply").val(parseInt(qo));
              $("#qty_pending").val(parseInt( parseInt($('#qty_order').val()) - parseInt($('#qty_supply').val()) + bonus ));
              $("#qty_cancel").val(parseInt($('#qty_order').val()) - parseInt($('#qty_supply').val()) - parseInt($('#qty_pending').val()) + parseInt($('#qty_bonus').val()) );
            }
            //alert(bonus);
            if (isNaN($("#qty_bonus").val()) || isNaN($("#qty_supply").val()) || isNaN($("#qty_pending").val()) ) {
              $("#qty_bonus,#qty_supply,#qty_pending,#qty_cancel").val('');
            }
            hitungso();
        });
        var gt = $('#grandtotalso').val();
        $('#dppso').val(gt);
        //console.log(gt);

        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        var dataTableItemszzz = $('#salesorderitemnya').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false
        });

        sethitung();
    });

    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function kembali(){
        window.location.href = siteurl+'salesorder';
    }
    function cancel(){
        $(".box").show();
        $("#form-area").hide();
    }
    function resetform(){
        $('#item_brg_so').val('');
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
            sethitung();
        }else{
            $('#nilaippn').val(0);
            sethitung();
        }
    }
    function setdiskoncash(d){
            $('#persen_diskon_cash').val(d);
            $('#nilaidiskoncash').val(d);
            sethitung();
    }
    function setitembarang(){
        var idbarang = $('#item_brg_so').val();
        var qty = $('#qty_order').val();
        var qty_sup = $('#qty_supply').val();
        if(idbarang != ""){
            $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_item_barang",
                data:"idbarang="+idbarang,
                success:function(result){
                    var data = JSON.parse(result);
                    console.log(data);
                    $('#nama_barang').val(data.nm_barang);
                    $('#harga').val(data.harga);
                    $('#satuan').val(data.satuan);
                    $('#jenis').val(data.jenis);
                    $('#qty_avl').val(data.qty_avl);
                    $('#total').val(data.harga*qty_sup);
                    $('#harga_normal').val(data.harga);

                    $('#diskon_standar_persen').val(data.diskon_standar_persen)
                    $('#diskon_promo_persen').val(data.diskon_promo_persen);
                    $('#diskon_promo_rp').val(data.diskon_promo_rp);
                    $('#diskon_jika_qty').val(data.diskon_jika_qty);
                    $('#diskon_qty_gratis').val(data.diskon_qty_gratis);

                    var d_std = parseInt($('#diskon_standar_persen').val() * $('#harga').val()/100);
                    var d_pp = parseInt($('#diskon_promo_persen').val() * $('#harga').val()/100);
                    var d_rp = $('#diskon_promo_rp').val();
                    var harga = parseInt($('#harga').val() - d_std - d_pp - d_rp);
                    $('#harga').val(harga);

                }
            });
        }
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
                    $('#disc_toko').val(data.diskon_toko);
                    $('#diskontoko').text(formatCurrency(data.diskon_toko*parseInt($('#grandtotalso').val())/100,',','.',0));
                    //$('#persen_diskon_toko').val(data.diskon_toko*parseInt($('#grandtotalso').val())/100);
                    $('#persen_diskon_toko').val(data.diskon_toko);
                    getpiccustomer(idcus);
                }
            });
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
              /*var hasilpic = JSON.parse(result);  // Memanggil result dengan json
              var dataHandler = $("#pic");  // Pemanggilan id muat-data-disini

              $.each(hasilpic, function(key,val){  // Menampung hasilDtt dalam variable val
              var newRow = $("<option>");

                                      // Menyimpan elemen data kedalam tabel
              //newRow.val(val.id_pic);
              newRow.html(val.nm_pic);
              dataHandler.append(newRow);

            });*/
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
    function hitungso(){
        var harga = parseInt($('#harga').val());
        var avl = parseInt($('#qty_avl').val());
        var qty = parseInt($('#qty_supply').val());
        var order = parseInt($('#qty_order').val());
        var diskon = parseInt($('#diskon').val());
        var bonus = parseInt($('#qty_bonus').val());

        var total = harga*qty;
        if(qty > avl){
          swal({
            title: "Peringatan!",
            text: "Stok Available tidak mencukupi",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
          });
          $('#qty_supply').val(0);
        }else{
          $('#total').val(total);
        }

        //
    }
    function sethitung(){
        var gt = $('#grandtotalso').val();

        $('#dppso').val(gt);
        var npp = parseInt($('#nilaippn').val());//PPN APA TIDAK
        //alert(dto);
        if ($('#persen_diskon_toko').val() == '') {
          $('#persen_diskon_toko').val(0);
        }
        var dpp_so = parseInt($('#dppso').val());
        var dto = parseFloat($('#persen_diskon_toko').val())*dpp_so/100;
        var dpp_n = parseInt($('#dppso').val()) - dto;
        var dcc = parseInt($('#nilaidiskoncash').val())*dpp_n/100;
        var dpp = dpp_n - dcc;
        var ppn = 10*dpp*0.01;
        if(npp == 0){
            ppn = 0;
        }
        //alert(dpp_n);
        $('#ppnso').val(ppn);
        $('#flagppnso').val(ppn);
        $('#totalso').val(dpp+ppn);
        $('#diskon_toko').val(dto);
        $('#diskon_cash').val(dcc);
        $('#diskoncash').text(formatCurrency(dcc,',','.',0));
        $('#ppnview').text(ppn);
        $('#diskontoko').text(formatCurrency(dto,',','.',0));
        $('#totalview').text(formatCurrency(dpp+ppn,',','.',0));
    }
    function hitungcancel(){
        var avl = parseInt($('#qty_avl').val());
        var qty = parseInt($('#qty_supply').val());
        var order = parseInt($('#qty_order').val());
        var pending = parseInt($('#qty_pending').val());
        var maks = qty+pending;
        var cancel = order-maks;
        if(filterAngka($('#qty_pending').val()) == 1){
            if(pending > avl){
                /*
                swal({
                    title: "Peringatan!",
                    text: "Qty available tidak cukup",
                    type: "warning",
                       timer: 1500,
                       showConfirmButton: false
                    });
                $('#qty_cancel').val(cancel);
                */
                swal({
                      title: "Peringatan !",
                      text: "Stok Available tidak cukup, lanjutkan ?",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Ya, Lanjutakan!",
                      cancelButtonText: "Tidak!",
                      closeOnConfirm: true,
                      closeOnCancel: true
                    },
                    function(isConfirm){
                      if (isConfirm) {
                        $('#qty_cancel').val(cancel);
                      }else{
                        //window.location.reload();
                      }
                    });
            }else{
                //if(pending != ""){
                    if(maks > order){
                        swal({
                            title: "Peringatan!",
                            text: "Qty melebihi order",
                            type: "warning",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        $('#qty_pending').val(0);
                    }else{
                        $('#qty_cancel').val(cancel);
                    }
                //}
            }
        }else{
            var ang = $('#qty_pending').val();
            $('#qty_pending').val(ang.replace(/[^0-9]/g,''));
        }
    }
    $('#form-detail-so').on('submit', function(e){
        hitungso();
        e.preventDefault();
        var formdata = $("#form-detail-so,#form-header-so").serialize();
        $.ajax({
            url: siteurl+"salesorder/saveitemso",
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
                    resetform();
                    setTimeout(function(){
                        window.location.reload();
                    },1600);
                    console.log(result.header);
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
    });
    function delete_data(noso,id){
        //alert(id);
        swal({
          title: "Anda Yakin?",
          text: "Data Akan Terhapus secara Permanen!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, delete!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            $.ajax({
                    url: siteurl+'salesorder/hapus_item_so',
                    data :{"NO_SO":noso,"ID":id},
                    dataType : "json",
                    type: 'POST',
                    success: function(result){
                        if(result.delete == '1'){
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            setTimeout(function(){
                                 window.location.reload();
                            },1600);
                        } else {
                            swal({
                              title: "Gagal!",
                              text: "Data gagal dihapus",
                              type: "error",
                              timer: 1500,
                              showConfirmButton: false
                            });
                        };
                    },
                    error: function(){
                        swal({
                          title: "Gagal!",
                          text: "Gagal Eksekusi Ajax",
                          type: "error",
                          timer: 1500,
                          showConfirmButton: false
                        });
                    }
                });
          } else {
            //cancel();
          }
        });
    }

    function saveheaderso(){
        var formdata = $("#form-header-so").serialize();
        if($('#idcustomer').val() != ""){
        $.ajax({
            url: siteurl+"salesorder/saveheaderso",
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
</script>
