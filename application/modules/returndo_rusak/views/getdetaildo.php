<form id="form-detail-do-konfirm" method="post">
<table class="table table-bordered" width="100%">
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
      <td rowspan="<?php echo $rs?>"><center><?php echo $no?></center></td>
      <td rowspan="<?php echo $rs?>"><?php echo $vd->nm_barang?></td>
      <td rowspan="<?php echo $rs?>"><center><?php echo $vd->qty_supply?></center></td>
      <td rowspan="<?php echo $rs?>"><center><?php echo $vd->satuan?></center></td>
      <td>
        <select class="form-control" name="konfirm_do[]" id="konfirm_do<?php echo $no?>" onchange="setclose('<?php echo $no?>')">
          <option value="">Pilih Konfirmasi</option>
          <option value="CLOSE">CLOSE</option>
          <option value="RETURN">RETURN</option>
        </select>
      </td>
      <td>
        <input type="text" name="return_do[]" id="return_do<?php echo $no?>" class="form-control" onkeyup="cekreturn('<?php echo $no?>')">
        <input type="hidden" name="id_barang_do_konfirm[]" class="form-control" value="<?php echo $vd->id_barang?>">
        <input type="hidden" name="qty_supply_do[]" id="qty_supply_do<?php echo $no?>" class="form-control" value="<?php echo $vd->qty_supply?>">
      </td>
    </tr>
    <?php } ?>
</table>
</form>
<script type="text/javascript">

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
