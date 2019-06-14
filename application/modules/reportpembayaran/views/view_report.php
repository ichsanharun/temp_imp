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
         <th>Jenis Bayar</th>
         <th>Tanggal Pembayaran</th>
         <th>Status</th>
         <th>Jumlah</th>
       </tr>
     </thead>
     <tbody>
     <?php
     $n=1;
     if(@$results){
     foreach(@$results as $kr=>$vr){
       $no = $n++;
       if ($vr->jenis_reff == 'BG') {
         $jenis = 'GIRO';
         $class = 'badge bg-blue';
       }elseif ($vr->jenis_reff == 'TRANSFER') {
         $jenis = 'TRANSFER';
         $class = 'badge bg-orange';
       }elseif ($vr->jenis_reff == 'CASH') {
         $jenis = 'CASH';
         $class = 'badge bg-green';
       }

       if ($vr->status_bayar != 'LUNAS') {
         $status = 'BELUM LUNAS BAYAR';
         $cl = 'badge bg-red';
       }else {
         $status = 'LUNAS';
         $cl = 'badge bg-green';
       }
     ?>
     <tr>
       <td><center><?php echo $no?></center></td>
       <td><center><?php echo $vr->kd_pembayaran?></center></td>
       <td><?php echo $vr->no_invoice?></td>
       <td><?php echo $vr->nm_customer?></td>
       <td><center><span class="<?=$class?>"><?php echo $jenis?></span></center></td>
       <td><?php echo date("d M Y",strtotime($vr->tgl_pembayaran))?></td>
       <td><center><span class="<?=$cl?>"><?php echo $status?></span></center></td>
       <td class="text-right"><?php echo $vr->jumlah_pembayaran?></td>
     </tr>
     <?php } ?>
     <?php } ?>
     </tbody>
     </table>
