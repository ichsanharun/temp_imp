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
    <th rowspan="2" width="2%">NO</th>
    <th rowspan="2" width="30%">NAMA PRODUK</th>
    <th rowspan="2" width="7%"><center>QTY</center></th>
    <th rowspan="2" width="5%"><center>SATUAN</center></th>
    <th rowspan="2" width="15%">KONFIRMASI</th>
    <th colspan="3" width="10%">RETURN</th>
  </tr>
  <tr>
    <th>
      GOOD
    </th>
    <th>
      SCRAP
    </th>
    <th>
      LOST
    </th>
  </tr>
  <?php
  $n=1;
  $total = 0;
  if ($detail) {
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
          <input type="text" name="return_do_bagus[]" id="return_do_bagus<?php echo $no?>" class="form-control return_do" onkeyup="cekreturn('<?php echo $no?>')">
          <input type="hidden" name="id_barang_do_konfirm[]" class="form-control" value="<?php echo $vd->id_barang?>">
          <input type="hidden" name="qty_supply_do[]" id="qty_supply_do<?php echo $no?>" class="form-control" value="<?php echo $vd->qty_supply?>">
        </td>
        <td>
          <input type="text" name="return_do_rusak[]" id="return_do_rusak<?php echo $no?>" class="form-control return_do" onkeyup="cekreturn('<?php echo $no?>')">
        </td>
        <td>
          <input type="text" name="return_do_hilang[]" id="return_do_hilang<?php echo $no?>" class="form-control return_do" onkeyup="cekreturn('<?php echo $no?>')">
        </td>
      </tr>
    <?php }} ?>
  
</table>
</form>
<script type="text/javascript">
  $(document).ready(function() {
    $('.return_do').val(0);
  });
    function setclose(no,val){

      var val = $('#konfirm_do'+no).val();
      if (val == "CLOSE") {
        $('#return_do_bagus'+no).show();
        $('#return_do_rusak'+no).show();
        $('#return_do_hilang'+no).show();
        $('#return_do_bagus'+no).hide();
        $('#return_do_rusak'+no).hide();
        $('#return_do_hilang'+no).hide();
      }else {
        $('#return_do_bagus'+no).hide();
        $('#return_do_rusak'+no).hide();
        $('#return_do_hilang'+no).hide();
        $('#return_do_bagus'+no).show();
        $('#return_do_rusak'+no).show();
        $('#return_do_hilang'+no).show();
      }
    }

</script>
