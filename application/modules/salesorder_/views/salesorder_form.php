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
                  <!-- Header Session -->
                    <div class="col-sm-6">
                      <!-- Data Customer -->
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
                                    <?php echo '('.$vc->bidang_usaha.') , '.$vc->nm_customer ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmcustomer" id="nmcustomer" class="form-control input-sm" value="<?php echo $headersession['nmcustomer']?>">

                              <!-- Bidan Usaha -->
                                <input type="hidden" name="bidang_usaha" id="bidang_usaha" value="<?=$headersession['bidang_usaha']?>">
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
                      <!-- Data Sales -->

                      <!-- Data Tanggal SO -->
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
                                    <!--input name="pic" id="pic" class="form-control" value="<?php echo $headersession['pic']?>"-->
                                    <?php
                                    if($headersession['pic']){
                                    ?>
                                    <select name="pic" id="pic" class="form-control input-sm select2" required>
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
                              <input name="top" id="top" class="form-control" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="<?php echo $headersession['top']?>" required>
                              <span class="input-group-addon">Hari</span>
                            </div>
                          </div>
                        </div>
                        <!-- Data TOP -->

                        <!-- Data PPN -->
                        <div class="form-group ">
                          <label for="flagppnso" class="col-sm-4 control-label">Flag PPN <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">

                            <?php if (isset($headersession['nilaippn'])) {
                              $nilppn = $headersession['nilaippn'];
                            }else {
                              $nilppn=0;
                            } ?>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn" <?php if($headersession['nilaippn'] != 0){echo "checked";} ?>>PPN (10%)
                                  </label>
                                </div>
                                <div class="radio-inline">
                                  <label>
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn" <?php if($headersession['nilaippn'] == 0){echo "checked";} ?> >Tanpa PPN
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
                              if($headersession['diskoncash'] == 0){
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
                              <textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan"><?php echo $headersession['keterangan']?></textarea>
                            </div>
                          </div>
                        </div>
                        <!-- Data Keterangan -->

                      </div>
                    </div>



                  <!-- Data HEADER SO -->
                    <input type="hidden" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly">
                    <input type="hidden" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly">
                    <input type="hidden" name="ppnso" id="ppnso" class="form-control input-sm" value="10" readonly="readonly">
                    <input type="hidden" name="persen_diskon_toko" id="persen_diskon_toko" value="<?php echo $headersession['persen_diskon_toko']?>">
                    <input type="hidden" name="persen_diskon_cash" id="persen_diskon_cash" value="<?php echo $headersession['persen_diskon_cash']?>">
                    <input type="hidden" name="diskon_toko" id="diskon_toko" value="<?php echo $headersession['diskon_toko']?>">
                    <input type="hidden" name="diskon_cash" id="diskon_cash" value="<?php echo $headersession['diskon_cash']?>">
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

            </tbody>
            <tfoot id="input_tambahan">
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
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="save()">
                            <i class="fa fa-save"></i><b> Simpan Data</b>
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

        $("#item_brg_so,#idcustomer,#idsalesman,#pic,#item_brg_so_bonus").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        var idcus = $('#idcustomer').val();
        if(idcus != ''){
          getpiccustomer(idcus);
        }

        $("#button_add_list").prop("disabled", true);
        var a = $('#idcustomer').val();

        if (a != '') {
          $("#button_add_list").prop("disabled", false);
        }


        var gt = $('#grandtotalso').val();
        $('#dppso').val(gt);

        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });

        sethitung();
    });


    $("#item_brg_so").on('mouseover', function(){
      $('#item_brg_so').popover('show');
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

    $("#disso").on('keyup', function(){
      var harga_normal = parseInt($("#harga_normal").val());
      var disstd = parseInt($("#diskon_standar_persen").val());
      var dispro = parseInt($("#diskon_promo_persen").val());
      var dispro_rp = parseInt($("#diskon_promo_rp").val());
      var harga = parseInt((harga_normal*(100 - disstd)/100)*(100 - dispro)/100 - dispro_rp);
      var disso = parseFloat($("#disso").val());
      if ($("#tipe_disso").val() === "%") {
        if (parseInt($("#disso").val()) > 100) {
          swal({
            title: "Peringatan!",
            text: "Tidak boleh lebih dari 100%",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
          });
          $("#disso").val(0);
        }else {
          $("#harga").val(harga*(100 - disso)/100);
        }
      }else {
        var radioValue = $("input[name='radio_disso_rp']:checked"). val();
        if (radioValue == "tambah") {
          $("#harga").val(harga + disso);
        }else {
          $("#harga").val(harga - disso);
        }
      }
      if (isNaN($("#harga").val())) {
        $("#harga").val(harga);
      }
    });

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
                    hitungso();
                  }
                  else {
                    $('#bidang_usaha').val(data.bidang_usaha);
                    hitungso();
                  }
                }else {
                  $('#bidang_usaha').val(data.bidang_usaha);
                  hitungso();
                  reset_itemso();
                }
              }
            }else {
              $('#diskon_promo_persen').val(0);
              $('#diskon_promo_rp').val(0);
              $('#bidang_usaha').val(data.bidang_usaha);
            }
            resetform();
          }
        });
      }else {
        $("#button_add_list").prop("disabled", true);
      }
      sethitung();

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
    function get_disso(id,val,idbar){
      var harga_normal = parseInt($("#harga_"+idbar).val());
      var disstd = parseInt($("#diskon_standar_persen_"+idbar).val());
      var dispro = parseInt($("#diskon_promo_persen_"+idbar).val());
      var qty = parseInt($("#qty_order_"+idbar).val());
      if ($("#qty_order_"+idbar).val() == '') {
        var qty = 0;
      }
      //var harga_total = parseInt((harga_normal*(100 - disstd)/100)*(100 - dispro)/100)*qty;
      var harga_nett = parseInt((harga_normal*(100 - disstd)/100)*(100 - dispro)/100);
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
          $("#harga_nett_"+idbar).val(harga_nett*(100 - disso)/100);
          $("#"+idbar+"_harga_nett").text(harga_nett*(100 - disso)/100);
          $("#"+id+"_total").text(harga_nett*(100 - disso)/100*qty);
        }
      }else {
        var radioValue = $("input[name='radio_disso_rp[]']:checked").val();
        if (radioValue == "tambah") {
          $("#harga_nett_"+idbar).val(harga_nett + disso);
          $("#"+idbar+"_harga_nett").text(harga_nett + disso);
          $("#"+id+"_total").text((harga_nett + disso)*qty);
        }else {
          $("#harga_nett_"+idbar).val(harga_nett - disso);
          $("#"+idbar+"_harga_nett").text(harga_nett - disso);
          $("#"+id+"_total").text((harga_nett - disso)*qty);
        }
      }
      if (isNaN($("#"+id+"_total").text())) {
        $("#"+id+"_total").text(harga_total);
      }
      hitung_total();

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
      //alert(arr_brg[0].id_barang);

      /*for (var i = 0; i < arr_brg.length; i++) {
        var brg += '<option value="'+arr_brg[i]+'">'
        +arr_brg[i].id_barang+', '+arr_brg[i].nama_barang+
        '</option>';
      }*/
      tabdua.row.add([
        i,
        '<select onchange="setitembarang(this.value,this.id)" id="item_brg_so'+i+'" name="item_brg_so" class="form-control input-xs form_item_so select2" style="width: 100%;" tabindex="-1">'+
                '<option value=""></option>'+
                '<?php
                foreach(@$itembarang as $k=>$v){
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
      $("#item_brg_so"+i).select2({
          placeholder: "Pilih",
          allowClear: true
      });
    }
    function setitembarang(a,b){
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
              '<input type="hidden" class="form-control input-sm" id="total_'+data.id_barang+'" name="subtotal[]" value="">'
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
              '      <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tipe_disso_'+data.id_barang+'" name="tipe_disso[]" value="persen">%<span class="caret"></span></button>'+
              '      <ul class="dropdown-menu bg-dark">'+
              '        <li><a href="javascript:void(0)" onclick="getdisso(\'%\',\''+data.id_barang+'\')">Persen (%)</a></li>'+
              '        <li><a href="javascript:void(0)" onclick="getdisso(\'Rp\',\''+data.id_barang+'\')">Rupiah (Rp)</a></li>'+
              '      </ul>'+
              '    </div><!-- /btn-group -->'+
              '    <input type="text" class="form-control form_item_so input-sm" aria-label="" name="disso[]" id="disso_'+data.id_barang+'" class="input-sm" value="0" onkeyup="get_disso(\''+b+'\',this.value,\''+data.id_barang+'\')">'+
              '  </div><!-- /input-group -->'+
              '  <div class="radio_disso_rp_'+data.id_barang+'" style="display:none">'+
              '    <div class="radio-inline">'+
              '      <label>'+
              '        <input type="radio" value="tambah" name="radio_disso_rp[]" >(+)'+
              '      </label>'+
              '    </div>'+
              '    <div class="radio-inline">'+
              '      <label>'+
              '        <input type="radio" value="kurang" name="radio_disso_rp[]" >(-)'+
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
      var total = parseInt($('#harga_nett_'+a).val())*b;
      $('#'+c+'_total').text(total);
      $('#total_'+a).val(total);
      hitung_total();
      //alert('#'+c+'_total');
    }
    function hitung_total(){
      var sum = 0;
      $('.subtotal_view').each(function(){
          sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
      });
      $('#total_view').text(formatCurrency(sum,',','.',0));
      $('#totalso').val(sum);
    }
    function getdisso(dis,id){
      $('#tipe_disso_'+id).html(dis);
      if (dis == "%") {
        $('#tipe_disso_'+id).val('persen');
        $('.radio_disso_rp_'+id).hide();
      }else {
        $('#tipe_disso_'+id).val('rupiah_');
        $('.radio_disso_rp_'+id).show();
      }
    }
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
    function hitungso(){
        var harga = parseInt($('#harga').val());
        var avl = parseInt($('#qty_avl').val());
        var qty = parseInt($('#qty_supply').val());
        var order = parseInt($('#qty_order').val());
        var diskon = parseInt($('#diskon').val());
        var bonus = parseInt($('#qty_bonus').val());
        var poin_per_item = parseInt($('#poin_per_item').val());

        var total = harga*qty;
        var poin = parseInt(total/poin_per_item);
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
          $('#jumlah_poin').val(poin);
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
        $('#distoko_text').text($('#persen_diskon_toko').val()+"%");
        var dpp_so = parseInt($('#dppso').val());
        var dto = parseFloat($('#persen_diskon_toko').val())*dpp_so/100;
        var dpp_n = parseInt($('#dppso').val()) - dto;
        var dcc = parseInt($('#nilaidiskoncash').val())*dpp_n/100;
        var dpp = parseInt(dpp_n - dcc);

        if(npp == 0){
            ppn = 0;
        }

        $('#ppnso').val(npp);

        $('#totalso').val(dpp);
        $('#diskon_toko').val(dto);
        $('#diskon_cash').val(dcc);
        $('#diskoncash').text(formatCurrency(dcc,',','.',0));
        $('#ppnview').text("(Include) "+npp+"%");
        $('#diskontoko').text(formatCurrency(dto,',','.',0));
        $('#totalview').text(formatCurrency(dpp,',','.',0));
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
                        window.location.href=siteurl+"salesorder/create";
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
    function save(){
      //sethitung();
        var formdata = $("#form-header-so,#form-so").serialize();
        if($('#idcustomer').val() != "" && $('#pic').val() != "" && $('#top').val() != "" && $('#tglso').val() != "" && $('#idsalesman').val() != ""){
        $.ajax({
            url: siteurl+"salesorder/saveso",
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
