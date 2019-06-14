<table class="table table-bordered">
  <tr class="bg-blue">
    <th width="1%">No</th>
    <th width="15%">Tanggal Transaksi</th>
    <th>Nomor Giro</th>
    <th>Nama Bank</th>
    <th>Nilai Fisik</th>
    <th width="15%">Jatuh Tempo</th>
    <th width="5%"></th>
  </tr>
  <?php
  if(@$giro){
    $n=1;
    foreach(@$giro as $kg=>$vg){
      $no=$n++;
  ?>
  <tr>
    <td><center><?php echo $no?></center></td>
    <td><center><?php echo date('d/m/Y',strtotime($vg->tgl_giro))?></center></td>
    <td><center><?php echo $vg->no_giro?></center></td>
    <td><center><?php echo $vg->nm_bank?></center></td>
    <td style="text-align: right;"><?php echo formatnomor($vg->nilai_fisik)?></td>
    <td><center><?php echo date('d/m/Y',strtotime($vg->tgl_jth_tempo))?></center></td>
    <td>
      <center>
        <button class="btn btn-sm btn-warning" onclick="pilihgiro('<?php echo $vg->no_giro?>','<?php echo $vg->nilai_fisik?>')" type="button">Pilih</button>
      </center>
    </td>
  </tr>
  <?php } ?>
  <?php }else{ ?>
  <tr>
    <td colspan="7" class="bg-blue">Belum ada data giro</td>
  </tr>
  <?php } ?>
</table>