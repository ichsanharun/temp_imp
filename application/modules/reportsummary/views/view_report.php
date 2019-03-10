<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=REPORT_PENJUALAN_".date("d-M-Y_H:i:s").".xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                  <th width="2%">NO</th>
                  <th width="15%">NO. INVOICE</th>
                  <th width="15%">NO. SO</th>
                  <th>NAMA CUSTOMER</th>
                  <th>TANGGAL INVOICE</th>
                  <th>NAMA SALES</th>
                  <th>TOTAL INVOICE</th>
                  <th>NAMA PRODUK</th>
                  <th>JENIS PRODUK</th>
                  <th>GRUP PRODUK</th>
                  <th>SATUAN PRODUK</th>
                  <th>JUMLAH</th>
                  <th>HARGA PRODUK</th>
                  <th>DISKON STD.</th>
                  <th>HARGA SETELAH DIS.STD</th>
                  <th>DISKON PROMO</th>
                  <th>DISKON SO</th>
                  <th>HARGA SETELAH DISKON SO</th>
                  <th>PPN</th>
                  <th>HARGA SUBTOTAL</th>
                  <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $n=1;
            $total=0;
            if(@$results){
              $nos = '';
              $jns = '';
              $cjns = 0;
              $arr_nama = array();
              $tc = count($results);
              $exe ='';
            foreach(@$results as $kr=>$vr){
              $so = $this->Salesorder_model->select('no_so')->cek_data(array('no_do'=>$vr->no_do),'trans_do_detail');
              $no = $n++;
              $total += $vr->hargajualtotal;
              if ($jns != $vr->nm_jenis) {
                //$exe .= 'PENJUALAN '.$jns.' = '.($cjns*100/$tc).'%<br>';
                $cjns = 0;
              }
            ?>
            <tr>
              <td><center><?php echo $no?></center></td>
              <?php /* if ($nos != $vr->no_invoice) {?>
                <td><center><?php echo $vr->no_invoice?></center></td>
                <td><center><?php echo $so->no_so?></center></td>
                <td><?php echo $vr->nm_customer?></td>
                <td><center><?php echo $vr->tanggal_invoice?></center></td>
                <td><?php echo $vr->nm_salesman?></td>
                <td class="text-right"><?php echo $vr->hargajualtotal?></td>
              <?php }else { ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"></td>
              <?php } */ ?>

              <td><center><?php echo $vr->no_invoice?></center></td>
              <td><center><?php echo $so->no_so?></center></td>
              <td><?php echo $vr->nm_customer?></td>
              <td><center><?php echo $vr->tanggal_invoice?></center></td>
              <td><?php echo $vr->nm_salesman?></td>
              <td class="text-right"><?php echo $vr->hargajualtotal?></td>

              <td><?= $vr->nm_barang ?> </td>
              <td><?= $vr->nm_jenis ?> </td>
              <td><?= $vr->nm_group ?> </td>
              <td><?= $vr->satuan ?> </td>
              <td><?= $vr->jumlah ?> </td>

              <td><?= $vr->hargajual ?> </td>
              <td><?= $vr->persen_diskon_stdr ?> </td>
              <td><?= $vr->harga_after_diskon_stdr ?> </td>
              <td><?= $vr->diskon_promo_persen ?> </td>
              <td><?php echo $vr->diskon_so."(".$vr->tipe_diskon_so.")" ?> </td>
              <td><?= $vr->harga_nett ?> </td>
              <td><?= $vr->ppn ?> </td>
              <td><?= $vr->subtot_after_diskon ?> </td>
    		  <td class="text-center">
    			<?php
    				$OK		=1;
    				if($vr->flag_cancel == 'N'){
    					echo"TIDAK BATAL";
    				}else{
    					$OK		= 0;
    					echo"BATAL";
    				}
    			?>
            </tr>
            <?php $nos = $vr->no_invoice;
            $jns = $vr->nm_jenis;
            $cjns++;
            $arr_nama[$vr->nm_jenis] = $cjns;
            $exe = 'PENJUALAN '.$jns.' = '.($cjns*100/$tc).'%<br>';
           } ?>
            <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                  <th colspan="6" class="text-right">TOTAL</th>
                  <th style="text-align: right;"><?php echo $total?></th>
                  <th style="text-align: left;" colspan="14">
                    <?php
                    foreach ($arr_nama as $key => $val) {
                      echo 'PENJUALAN '.$key.' = '.($val*100/$tc).'%<br>';
                    }
                     ?>
                  </th>
              </tr>
            </tfoot>
        </table>
