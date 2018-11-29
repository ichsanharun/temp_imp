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
  foreach(@$detail as $kd=>$vd){
    $no=$n++;
    ?>
    <tr>
      <td rowspan="<?php echo $rs?>"><center><?php echo $no?></center></td>
      <td rowspan="<?php echo $rs?>"><?php echo $vd->nm_barang?></td>
      <td rowspan="<?php echo $rs?>"><center><?php echo $vd->qty_supply?></center></td>
      <td rowspan="<?php echo $rs?>"><center><?php echo $vd->satuan?></center></td>
      <td>
        <?php echo $vd->konfirm_do_detail?>
      </td>
      <td>
        <?php echo $vd->return_do?>
      </td>
      <td>
        <?php echo $vd->return_do_rusak?>
      </td>
      <td>
        <?php echo $vd->return_do_hilang?>
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
