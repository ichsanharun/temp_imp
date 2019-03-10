<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-body">
        <?php //print_r($detail)?>
        <form id="form-detail-so-pending" method="post">
        <table id="salesorderitem_x" class="table table-bordered table-striped" style="width:100% !important;color:#000">
        <thead>
            <tr>
                <th width="10%">Nama Barang Set</th>

                <th width="5%">Satuan</th>
                <th width="10%"><center>Qty Order Awal</center></th>
                <th width="10%"><center>Qty Confirm</center></th>
                <th width="10%"><center>Qty Pending</center></th>
                <th width="10%"><center>Qty Available Now</center></th>
                <th width="10%"><center>Qty Order Baru</center></th>
                <th width="10%"><center>Qty Confirm</center></th>
                <th width="10%"><center>Qty Pending Again</center></th>
                <th width="10%"><center>Qty Cancel</center></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n=1;
            foreach($getitemso as $kv=>$vo){
                $no=$n++;
                $session = $this->session->userdata('app_session');
                $key = array(
                    'id_barang'=>$vo->id_barang,
                    'kdcab' => $session['kdcab']
                    );
                $stok = $this->Salesorder_model->cek_data($key,'barang_stock');
                //print_r($stok);
            ?>
            <tr>
                <td>
                  <?php echo $vo->id_barang.", ".$vo->nm_barang?>
                  <!--select onchange="setitembarang()" id="item_brg_so" name="item_brg_so" class="form-control input-xs item_brg_so" style="width: 100%;" tabindex="-1" required>
                          <option value=""></option>
                          <?php
                          foreach(@$itembarang as $k=>$v){
                          ?>
                          <option value="<?php echo $v->id_barang; ?>" <?php echo set_select('nm_barang', $v->id_barang, isset($vo->nm_barang) && $vo->id_barang == $v->id_barang) ?>>
                              <?php echo $v->id_barang.' , '.$v->nm_barang ?>
                          </option>
                          <?php } ?>
                  </select-->
                  <input type="hidden" name="noso[<?php echo $no?>]" value="<?php echo $vo->no_so; ?>">
                  <input type="hidden" name="id_barang[<?php echo $no?>]" value="<?php echo $vo->id_barang; ?>">
                </td>

                <td><center><span id="satuan_<?php echo $no?>"><?php echo $vo->satuan?></span></center></td>
                <td><center><span id="qty_order_<?php echo $no?>"><?php echo $vo->qty_order?></span></center></td>
                <td><center><span id="qty_booked_<?php echo $no?>"><?php echo $vo->qty_booked?></span></center></td>
                <td><center><span id="qty_pending_<?php echo $no?>"><?php echo $vo->qty_pending?></span></center></td>
                <td><center><span id="qty_avl_<?php echo $no?>"><?php echo $stok->qty_avl+$vo->qty_booked_awal?></span></center></td>
                <td>
                    <center>
                        <input type="text" name="qty_order[<?php echo $no?>]" id="qty_order<?php echo $no?>" class="form-control input-sm" style="width: 70%;" onkeyup="cekorder('<?php echo $no?>')" required>
                    </center>
                </td>
                <td>
                    <center>
                        <input type="hidden" name="harga[<?php echo $no?>]" id="harga<?php echo $no?>" class="form-control input-sm harga" value="<?php echo $vo->harga_so?>">
                        <input type="hidden" name="diskon_persen[<?php echo $no?>]" id="diskon_persen<?php echo $no?>" class="form-control input-sm diskon_persen" value="<?php echo $vo->diskon_persen?>">
                        <input type="hidden" name="harga_normal[<?php echo $no?>]" id="harga_normal<?php echo $no?>" class="form-control input-sm harga_normal" value="<?php echo $vo->harga_normal?>">
                        <input type="hidden" name="qty_avl[<?php echo $no?>]" id="qty_avl<?php echo $no?>" class="form-control input-sm qty_avl" value="<?php echo $stok->qty_avl+ $vo->qty_booked_awal?>">
                        <input type="hidden" name="qty_avl_barang[<?php echo $no?>]" id="qty_avl_barang<?php echo $no?>" class="form-control input-sm qty_avl_barang" value="<?php echo $stok->qty_avl + $vo->qty_booked_awal?>">
                        <input type="hidden" name="qty_pending[]" id="qty_pending<?php echo $no?>" class="form-control input-sm qty_pending" value="<?php echo $vo->qty_pending?>">
                        <input type="text" name="qty_confirm[<?php echo $no?>]" id="qty_confirm<?php echo $no?>" class="form-control input-sm qty_confirm" style="width: 70%;" onkeyup="cekconfirm('<?php echo $no?>')" required>
                    </center>
                </td>
                <td>
                    <center>
                        <input type="text" name="pending_again[<?php echo $no?>]" id="pending_again<?php echo $no?>" class="form-control input-sm pending_again" style="width: 70%;" onkeyup="hitungcancel('<?php echo $no?>')" required>
                    </center>
                </td>
                <td>
                    <center>
                        <input type="text" name="cancel_again[<?php echo $no?>]" id="cancel_again<?php echo $no?>" class="form-control input-sm cancel_again" style="width: 70%;" readonly="readonly">
                    </center>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        </table>
        </form>
    </div>
</div>
<!--div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_pending()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="savependingso()">
            <i class="fa fa-save"></i><b> Simpan Proses Pending</b>
        </button>
    </div>
  </div>
</div-->
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
    var uri3 = '<?php echo $this->uri->segment(3)?>';
    console.log(uri3);
    $(function() {
        var dataTableItem_x = $('#salesorderitem_x').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
    });
  $(document).ready(function() {
    $(".item_brg_so").select2({
        placeholder: "Pilih",
        allowClear: true
    });
  });
  function cekorder(no){
      var order = $("#qty_order"+no).val();
      $("#qty_order"+no).val($("#qty_order"+no).val().match(/^[0-9]+$/));
      //alert(order);
      //filterAngka(this.value);
      //this.value =

      var harga = parseInt($('#harga'+no).val());
      var avl = parseInt($('#qty_avl'+no).val());
      var qty = parseInt($('#qty_confirm'+no).val());
      var order = parseInt($('#qty_order'+no).val());
      var booked = parseInt($('#qty_booked_'+no).html());
      //var diskon = parseInt($('#diskon').val());
      //var qtybonus = parseInt($('#qty_bonus').val());
      //var qtybonus = parseInt(0);

      //$("#qty_bonus").val(bonus);
      $("#qty_confirm"+no).val(parseInt(order));
      if ( parseInt($('#qty_confirm'+no).val()) > parseInt($('#qty_avl'+no).val()) ) {
        $("#qty_confirm"+no).val(parseInt($('#qty_avl'+no).val()));
        $("#pending_again"+no).val(order - avl);
        $("#qty_cancel"+no).val(order - parseInt($('#pending_again'+no).val()) - parseInt($('#qty_confirm'+no).val()) );
      }
      else {
        $("#qty_confirm"+no).val(order);
        $("#pending_again"+no).val(parseInt( parseInt($('#qty_order'+no).val()) - parseInt($('#qty_confirm'+no).val()) ));
        $("#qty_cancel"+no).val(parseInt($('#qty_order'+no).val()) - parseInt($('#qty_confirm'+no).val()) - parseInt($('#pending_again'+no).val()));
      }
      //alert(bonus);
      if (isNaN($("#qty_order"+no).val()) || isNaN($("#qty_confirm"+no).val()) || isNaN($("#pending_again"+no).val()) ) {
        $("#qty_confirm"+no+",#pending_again"+no+",#qty_cancel"+no).val('');
      }



  }
    function setitembarang(){
        var idbarang = $('#item_brg_so').val();
        var qty = $('#qty_order').val();
        var qty_sup = $('#qty_confirm').val();
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
    /*
    function cekconfirm(no){
        var avl = parseInt($('#stok_avl'+no).val());
        var pending = parseInt($('#qty_pending'+no).val());
        var confirm = parseInt($('#qty_confirm'+no).val());
        var order = parseInt($('#qty_order'+no).val());
        if(filterAngka($('#qty_confirm'+no).val()) == 1){
            if(avl <= 0){
                swal({
                    title: "Peringatan!",
                    text: "Stok Available belum mencukupi",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#qty_confirm'+no).val(0);
            }else{
                if(confirm > pending){
                    swal({
                        title: "Peringatan!",
                        text: "Qty Confirm tidak boleh melebihi Qty Pending",
                        type: "warning",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#qty_confirm'+no).val(0);
                }else{
                    if(pending == confirm){
                        $('#pending_again'+no).val(0);
                        $('#cancel_again'+no).val(0);
                    }
                }
            }
        }else{
            var ang = $('#qty_confirm'+no).val();
            $('#qty_confirm'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }
    function hitungcancel(no){
        var pending = parseInt($('#qty_pending'+no).val());
        var pending_again = parseInt($('#pending_again'+no).val());
        var confirm = parseInt($('#qty_confirm'+no).val());
        var x = confirm+pending_again;
        var cancel = pending-x;
        if(filterAngka($('#pending_again'+no).val()) == 1){
            if($('#qty_confirm'+no).val() != ''){
                if(x > pending){
                    swal({
                        title: "Peringatan!",
                        text: "Melebihi Qty Pending",
                        type: "warning",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#pending_again'+no).val('');
                }else{
                    if(!isNaN(cancel)){
                        $('#cancel_again'+no).val(cancel);
                    }
                }
            }else{
               swal({
                    title: "Peringatan!",
                    text: "Qty Confirm tidak boleh kosong",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                /*
               $('#pending_again'+no).val('');
               setTimeout(function(){
                    $('#qty_confirm'+no).focus();
               },1000);
               /
            }
        }else{
           var ang = $('#pending_again'+no).val();
            $('#pending_again'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }*/
    function save1pendingso(){
        swal({
          title: "Peringatan !",
          text: "Pastikan data sudah lengkap dan benar",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, simpan!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            var formdata = $("#form-detail-so-pending").serialize();
            $.ajax({
                url: siteurl+"pendingso/savependingso/"+uri3,
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
                            window.location.href=siteurl+'pendingso';
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
        }
        });
    }
    function save11pendingso(){
      //e.preventDefault();
      var formdata = $("#form-detail-so-pending").serialize();
      $.ajax({
        url: siteurl+"salesorder/save_edit_so_pending",
        dataType : "json",
        type: 'POST',
        data: formdata,
        success: function(result){
          if(result.save=='1'){
            swal({
              title: "Sukses!",
              text: result['header'],
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
              text: result['msg'],
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
    }
    function kembali_pending(){
        window.location.href = siteurl+'pendingso';
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
</script>
