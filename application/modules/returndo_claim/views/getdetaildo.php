<form id="form-detail-do-konfirm" method="post">
  <input type="hidden" name="no_invoice" value="<?php echo @$header->no_invoice?>">
<table class="table table-bordered" width="100%">
  <tr>
    <th colspan="4"><?php echo @$header->nm_customer?></th>
    <th colspan="2" class="text-center">
      <input type="hidden" name="no_do_konfirm" id="no_do_konfirm" class="form-control" value="<?php echo @$detail[0]->no_do?>">
      <?php echo "NO. DO : ".@$detail[0]->no_do?>
    </th>
  </tr>
  <tr>
    <th width="2%">NO</th>
    <th width="30%">NAMA PRODUK</th>
    <th width="7%"><center>QTY</center></th>
    <th width="5%"><center>SATUAN</center></th>
    <th width="15%">STATUS RETURN:</th>
    <th width="10%">CLAIM RETURN</th>
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
        <td rowspan="<?php echo $rs?>"><center><?php echo $vd->jumlah?></center></td>
        <td rowspan="<?php echo $rs?>"><center><?php echo $vd->satuan?></center></td>
        <td>
          <?php $cr = $this->db->query("SELECT * FROM trans_do_detail WHERE no_do = '$vd->no_do' AND id_barang = '$vd->id_barang'")->result(); ?>
          <strong><?php echo $cr[0]->konfirm_do_detail?></strong><br>
          GOOD: <?php echo $cr[0]->return_do?><br>
          SCRAP: <?php echo $cr[0]->return_do_rusak?><br>
          LOST: <?php echo $cr[0]->return_do_hilang?><br>
          <!--<select class="form-control" name="konfirm_do[]" id="konfirm_do<?php echo $no?>" onchange="setclose('<?php echo $no?>')">
            <option value="">Pilih Konfirmasi</option>
            <option value="CLOSE">CLOSE</option>
            <option value="RETURN">RETURN</option>
          </select>-->
        </td>
        <td width="30%">
          <div class="col-sm-12">
            <div class="row" id="rowganti<?=$no-1?>" style="display:none !important">
              <div class="form-group ">
                <label for="ganti" class="col-sm-4 control-label">Ganti</label>
                <div class="col-sm-8">
                  <div class="input-group">

                    <input name="ganti[<?=$no-1?>]" id="ganti<?=$no-1?>" class="form-control" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="0" placeholder="QTY Ganti">

                  </div>
                </div>
              </div>
            </div>
            <div class="row" style="margin-top:7px;display:none !important" id="rowuang<?=$no-1?>">
              <div class="form-group ">
                <label for="uang" class="col-sm-4 control-label">Uang Kembali</label>
                <div class="col-sm-8">
                  <div class="input-group">
                    <input name="qty[<?=$no-1?>]" id="qty<?=$no-1?>" class="form-control" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="0" placeholder="QTY">
                    <span class="input-group-addon" style="padding:0;margin:0;" id="spanx<?=$no-1?>">X</span>
                    <input name="uang[<?=$no-1?>]" id="uang<?=$no-1?>" class="form-control" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="0" style="width:100%" placeholder="NOMINAL">
                  </div>
                </div>
              </div>
            </div>
            <div class="row" style="margin-top:7px">
              <div class="form-inline">
                <label>
                  <input type="checkbox" value="close" id="<?=$no-1?>" onclick="setclose(this.id)" name="cekclose[<?=$no-1?>]" class="checkbox" checked> Tidak Retur
                </label>
              </div>
            </div>
          </div>



          <input type="hidden" name="id_barang_do_konfirm[<?=$no-1?>]" class="form-control" value="<?php echo $vd->id_barang?>">
          <input type="hidden" name="nm_barang_do_konfirm[<?=$no-1?>]" class="form-control" value="<?php echo $vd->nm_barang?>">
          <input type="hidden" name="return_do_rusak[<?=$no-1?>]" id="return_do_rusak<?php echo $no?>" class="form-control return_do" onkeyup="this.value=this.value.replace(/[^0-9.]/g,'');cekreturn('<?php echo $no?>');">
          <input type="hidden" name="qty_supply_do[<?=$no-1?>]" id="qty_supply_do<?php echo $no?>" class="form-control" value="<?php echo $vd->jumlah?>">

        </td>
        <!--td>
          <input type="text" name="return_do_hilang[]" id="return_do_hilang<?php echo $no?>" class="form-control return_do" onkeyup="cekreturn('<?php echo $no?>')">
        </td-->
      </tr>
    <?php }} ?>

</table>
</form>
<script type="text/javascript">
  $(document).ready(function() {
    $('.return_do').val(0);
  });
    function setclose(id){
      //console.log();
      if (document.getElementById(id).checked == true) {
        document.getElementById("rowganti"+id).style.display = "none";
        document.getElementById("rowuang"+id).style.display = "none";
        //document.getElementById("ganti"+id).style.display = "none";
        //document.getElementById("uang"+id).style.display = "none";
        //document.getElementById("spanx"+id).style.display = "none";
      }else {
        document.getElementById("rowganti"+id).style.display = "block";
        document.getElementById("rowuang"+id).style.display = "block";
        //document.getElementById("ganti"+id).style.display = "block";
        //document.getElementById("uang"+id).style.display = "block";
        //document.getElementById("spanx"+id).style.display = "block";
      }

    }

</script>
