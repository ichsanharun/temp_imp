<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=REPORT_PIUTANG_BULANAN_".date("d-M-Y_H:i:s").".xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>

    <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th colspan="9">REPORT PIUTANG BULANAN</th>
          </tr>
          <tr>
              <th width="2%">#</th>
              <th width="15%">NO. Invoice</th>
              <th>Customer</th>
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Saldo Awal</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Saldo Akhir</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
          $debet=0;
          if($vr->debet != 0){
              $debet=$vr->debet;
          }
          $kredit=0;
          if($vr->kredit != 0){
              $kredit=$vr->kredit;
          }
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><center><?php echo $vr->no_invoice?></center></td>
          <td><?php echo $vr->customer_code.', '.$vr->customer?></td>
          <td><?php echo the_bulan($vr->bln)?></td>
          <td><?php echo $vr->thn?></td>
          <td class="text-right"><?php echo $vr->saldo_awal?></td>
          <td class="text-right"><?php echo $debet?></td>
          <td class="text-right"><?php echo $kredit?></td>
          <td class="text-right"><?php echo $vr->saldo_akhir?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        </table>
