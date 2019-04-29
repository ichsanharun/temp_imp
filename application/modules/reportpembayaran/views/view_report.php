<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=REPORT_PEMBAYARAN_".date("d-M-Y_H:i:s").".xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
 <table id="example1" class="table table-bordered table-striped">
     <thead>
       <tr>
           <th width="2%">#</th>
           <th width="15%">No. Pembayaran</th>
           <th>No. Invoice</th>
           <th>Customer</th>
           <th>Tanggal Pembayaran</th>
           <th>Jumlah</th>
       </tr>
     </thead>
     <tbody>
     <?php
     $n=1;
     if(@$results){
     foreach(@$results as $kr=>$vr){
       $no = $n++;
     ?>
     <tr>
       <td><center><?php echo $no?></center></td>
       <td><center><?php echo $vr->kd_pembayaran?></center></td>
       <td><?php echo $vr->no_invoice?></td>
       <td><?php echo $vr->nm_customer?></td>
       <td><?php echo date("d M Y",strtotime($vr->tgl_pembayaran))?></td>
       <td class="text-right"><?php echo formatnomor($vr->jumlah_pembayaran)?></td>
     </tr>
     <?php } ?>
     <?php } ?>
     </tbody>
     </table>
