<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=REPORT_PENJUALAN_".date("d-M-Y_H:i:s").".xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<table id="example1" class="table table-bordered table-striped">
<thead>
<tr>
  <th width="5">#</th>
  <th>Cabang</th>
  <th>Kode Produk</th>
  <th>Nama Set</th>
  <th>Jenis Produk</th>
  <th>Satuan</th>
  <th>Qty Stock</th>
  <th>Qty Available</th>
  <th>Qty Rusak</th>
  <th>Landed Cost</th>
  <th>Harga</th>
  <th>Persediaan</th>
  <th>Status</th>
</tr>
</thead>

<tbody>
<?php
//print_r($results);
if(empty($results)){
}else{
  $total = 0;
  $numb=0; foreach($results AS $record){ $numb++;
    $total += $record->landed_cost*$record->qty_stock;
    ?>
<tr>
  <?php
    if($record->satuan==''){
      $satuan = $record->setpcs;
    }else{
      $satuan = $record->satuan;
    }
  ?>
    <td><?= $numb; ?></td>
    <td><?= $record->kdcab." , ".$record->namacabang ?></td>
  <td><?= $record->id_barang ?></td>
  <td><?= $record->nm_barang ?></td>
  <td><?= strtoupper($record->jenis) ?></td>
  <td><?= $satuan ?></td>
  <td><?= $record->qty_stock ?></td>
  <td><?= $record->qty_avl ?></td>
  <td><?= $record->qty_rusak ?></td>
  <td><?= number_format($record->landed_cost) ?></td>
  <td><?= number_format($record->harga) ?></td>
  <td><?= number_format($record->landed_cost*$record->qty_stock) ?></td>
  <td>
    <?php if($record->sts_aktif == 'aktif'){ ?>
      <label class="label label-success">Aktif</label>
    <?php }else{ ?>
      <label class="label label-danger">Non Aktif</label>
    <?php } ?>
  </td>
</tr>
<?php } }  ?>
<tr>
  <td colspan="11">
    Total Persediaan
  </td>
  <td>
    <?=$total?>
  </td>
</tr>
</tbody>

<tfoot>
<tr>
  <th width="5">#</th>
  <th>Cabang</th>
  <th>Kode Produk</th>
  <th>Nama Set</th>
  <th>Jenis Produk</th>
  <th>Satuan</th>
  <th>Qty Stock</th>
  <th>Qty Available</th>
  <th>Qty Rusak</th>
  <th>Landed Cost</th>
  <th>Harga</th>
  <th>Persediaan</th>
  <th>Status</th>
</tr>
</tfoot>
</table>
