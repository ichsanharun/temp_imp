<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=REPORT_PEMBELIAN_".date("d-M-Y_H:i:s").".xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                  <th width="2%">NO</th>
                  <th width="15%">NO. RECEIVE</th>
                  <th width="15%">NO. PO</th>
                  <th>NAMA SUPPLIER</th>
                  <th>NO. PI</th>
                  <th>NO. CONTAINER</th>
                  <th>TANGGAL CEK</th>
                  <th>TANGGAL RECEIVE</th>
                  <th>ID BARANG</th>
                  <th>NAMA PRODUK</th>
                  <th>JENIS PRODUK</th>
                  <th>GRUP PRODUK</th>
                  <th>JUMLAH PICKING LIST</th>
                  <th>JUMLAH BAGUS</th>
                  <th>JUMLAH RUSAK</th>
                  <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no=1;
            $total=0;
            if(@$results){
            foreach(@$results as $kr=>$vr){
              //$so = $this->Salesorder_model->select('no_so')->cek_data(array('no_do'=>$vr->no_do),'trans_do_detail');
              $no = $n++;

            ?>
            <tr>
              <td><center><?php echo $no?></center></td>

              <td><center><?php echo $vr->no_receiving?></center></td>
              <td><center><?php echo $vr->no_po?></center></td>
              <td><?php echo $vr->nm_supplier?></td>
              <td><center><?php echo $vr->no_pi?></center></td>
              <td><center><?php echo $vr->container_no?></center></td>
              <td><center><?php echo $vr->date_check?></center></td>
              <td><center><?php echo $vr->tglreceive?></center></td>
              <td><?php echo $vr->id_barang?></td>
              <td><?php echo $vr->nama_barang?></td>
              <td><?php echo substr($vr->id_barang,0,2)?></td>
              <td><?php echo substr($vr->id_barang,2,2)?></td>
              <td class="text-right"><?php echo $vr->qty_pl?></td>
              <td class="text-right"><?php echo $vr->bagus?></td>
              <td class="text-right"><?php echo $vr->rusak?></td>
              <td><?php echo $vr->status?></td>
            </tr>
            <?php
          }}
             ?>
            </tbody>
            <tfoot>
              <tr>

              </tr>
            </tfoot>
        </table>
