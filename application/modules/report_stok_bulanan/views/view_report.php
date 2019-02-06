<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=x.xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
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
       <th>Periode</th>
       <th>Status</th>

     </tr>
  </thead>

  <tbody>
     <?php
     //print_r($results);
     if(empty($results)){
     }else{
       $numb=0; foreach($results AS $key => $record){ $numb++; ?>
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
       <td><?= date("m/Y", strtotime($record->tahun."-".$record->bulan)) ?></td>
       <td>
         <?php if($record->sts_aktif == 'aktif'){ ?>
           <label class="label label-success">Aktif</label>
         <?php }else{ ?>
           <label class="label label-danger">Non Aktif</label>
         <?php } ?>
       </td>

     </tr>
     <?php } }  ?>
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
       <th>Periode</th>
       <th>Status</th>
       <?php if($ENABLE_MANAGE) : ?>

       <?php endif; ?>
     </tr>
  </tfoot>
 </table>
