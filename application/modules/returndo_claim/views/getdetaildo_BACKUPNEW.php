<form id="form-detail-do-konfirm" method="post">
<table class="table table-bordered" width="100%" style="padding:10px !important">
  <tr>
    <th colspan="4"><?php echo @$header->nm_customer?></th>
    <th colspan="2" class="text-center">
      <input type="hidden" name="no_do_konfirm" id="no_do_konfirm" class="form-control" value="<?php echo @$header->no_do?>">
      <?php echo "NO. DO : ".@$header->no_do?>
    </th>
  </tr>
  <tr>
    <th width="2%">NO</th>
    <th width="30%">NAMA PRODUK</th>
    <th width="7%"><center>QTY</center></th>
    <th width="5%"><center>SATUAN</center></th>
    <th width="15%">KONFIRMASI</th>
    <th width="10%">RETURN</th>
  </tr>
  <?php
  $n=1;
  $total = 0;
  foreach(@$detail as $kd=>$vd){
    $no=$n++;
    ?>
    <tr>
      <td rowspan="5"><center><?php echo $no?></center></td>
      <td rowspan="5"><?php echo $vd->nm_barang?></td>
      <td rowspan="5"><center><?php echo $vd->qty_supply?></center></td>
      <td rowspan="5" style="border-right:1px solid #fff"><center><?php echo $vd->satuan?></center></td>
    </tr>
        <!--select class="form-control" name="konfirm_do[]" id="konfirm_do<?php echo $no?>" onchange="setclose('<?php echo $no?>')">
          <option value="">Pilih Konfirmasi</option>
          <option value="CLOSE">CLOSE</option>
          <option value="RETURN BAGUS">RETURN (GOOD)</option>
          <option value="RETURN RUSAK">RETURN (SCRAP)</option>
          <option value="RETURN HILANG">RETURN (LOST)</option>
        </select-->
          <!--div class="form-group">
            <div class="col-sm-10">
              <div class="checkbox">
                <label>
                  <input type="checkbox"> Check All Return
                </label>
              </div>
            </div>
          </div-->
      <tr>
        <td>
          <div class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="close_<?php echo $no?>" onclick="cls('<?php echo $no?>')" name="konfirm_do[][]" value="CLOSE">CLOSE
                  </label>
                </div>
              </div>
            </div>
          </div>
        </td>
        <td>
          <input type="hidden" name="return_do[]" id="return_do<?php echo $no?>" class="form-control input-sm return_do" onkeyup="cekreturn('<?php echo $no?>')" value="0">
        </td>
      </tr>
      <tr>
        <td>
          <div class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="return_b_<?php echo $no?>" name="konfirm_do[][]" class="checkbox_opsi" value="RETURN BAGUS">RETURN (GOOD)
                  </label>
                </div>
              </div>
            </div>
          </div>
        </td>
        <td>
          <input type="text" name="return_do_bagus[]" id="return_do_bagus<?php echo $no?>" class="form-control input-sm" onkeyup="cekreturn('<?php echo $no?>')">
        </td>
      </tr>
      <tr>
        <td>
          <div class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="return_r_<?php echo $no?>" name="konfirm_do[][]" class="checkbox_opsi" value="RETURN RUSAK">RETURN (SCRAP)
                  </label>
                </div>
              </div>
            </div>
          </div>
        </td>
        <td>
          <input type="text" name="return_do_rusak[]" id="return_do_rusak<?php echo $no?>" class="form-control input-sm" onkeyup="cekreturn('<?php echo $no?>')">
        </td>
      </tr>
      <tr>
        <td>
          <div class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-10">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" id="return_h_<?php echo $no?>" name="konfirm_do[][]" class="checkbox_opsi" value="RETURN HILANG">RETURN (LOST)
                  </label>
                </div>
              </div>
            </div>
          </div>
        </td>
        <td>
          <input type="text" name="return_do_hilang[]" id="return_do_hilang<?php echo $no?>" class="form-control input-sm" onkeyup="cekreturn('<?php echo $no?>')">
        </td>
      </tr>




      <!--td>
        <input type="text" name="return_do[]" id="return_do<?php echo $no?>" class="form-control return_do" onkeyup="cekreturn('<?php echo $no?>')" value="0">
        <input type="text" name="return_do_bagus[]" id="return_do_bagus<?php echo $no?>" class="form-control" onkeyup="cekreturn('<?php echo $no?>')">
        <input type="text" name="return_do_rusak[]" id="return_do_rusak<?php echo $no?>" class="form-control" onkeyup="cekreturn('<?php echo $no?>')">
        <input type="text" name="return_do_hilang[]" id="return_do_hilang<?php echo $no?>" class="form-control" onkeyup="cekreturn('<?php echo $no?>')">
        <input type="hidden" name="id_barang_do_konfirm[]" class="form-control" value="<?php echo $vd->id_barang?>">
        <input type="hidden" name="qty_supply_do[]" id="qty_supply_do<?php echo $no?>" class="form-control" value="<?php echo $vd->qty_supply?>">
      </td-->

    <?php } ?>
</table>
</form>
<script type="text/javascript">
//$('.return_do').hide();
function cls(no){
  var n = (parseInt(no) - 1) * 3;
  var n_max = parseInt(n) + 3;
  var max = <?php echo $no ?>;
  //alert($("#close_"+no).prop('checked'));
    if ($("#close_"+no).prop('checked') == true) {
      for (var i = n; i < n_max; i++) {
        $(".checkbox_opsi").eq(i).prop('disabled', true);
        $("#return_do"+no).hide();
        $("#return_do_bagus"+no).hide();
        $("#return_do_rusak"+no).hide();
        $("#return_do_hilang"+no).hide();
      }
    }else {
      for (var i = n; i < n_max; i++) {
        $(".checkbox_opsi").eq(i).prop('disabled', false);
        $("#return_do_bagus"+no).show();
        $("#return_do_rusak"+no).show();
        $("#return_do_hilang"+no).show();
      }
    }

}
    function setclose(no,val){

      var val = $('#konfirm_do'+no).val();
      if (val == "CLOSE") {
        $('#return_do'+no).show();
        $('#return_do'+no).hide();
      }else {
        $('#return_do'+no).hide();
        $('#return_do'+no).show();
      }
    }

</script>
