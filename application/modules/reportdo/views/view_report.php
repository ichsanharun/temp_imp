<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=REPORT_DO_".date("d-M-Y_H:i:s").".xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                  <th>No</th>
                  <th>NO. DO</th>
                  <th>NAMA CUSTOMER</th>
                  <th>TANGGAL DO</th>
                  <th>STATUS</th>
                  <th>NAMA SALESMAN</th>
                  <th>NAMA SUPIR</th>
                  <th>NAMA HELPER</th>
                  <th>KENDARAAN</th>
                  <th>NAMA PRODUK</th>
                  <th>JENIS PRODUK</th>
                  <th>GRUP PRODUK</th>
                  <th>SATUAN PRODUK</th>
                  <th>QTY ORDER</th>
                  <th>QTY BOOKED</th>
                  <th>QTY PENDING</th>
                  <th>QTY CANCEL</th>
                  <th>QTY SUPPLY</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(@$results){
                $n=1;
                foreach(@$results as $row){
                    $no=$n++;
                ?>
                <tr>
                  <td><?= $no++ ?> </td>
                  <td><?= $row->no_do ?> </td>
                  <td><?= $row->nm_customer ?> </td>
                  <td><?= $row->tgl_do ?> </td>
                  <td><?= $row->status ?> </td>
                  <td><?= $row->nm_salesman ?> </td>
                  <td><?= $row->nm_supir ?> </td>
                  <td><?= $row->nm_helper ?> </td>
                  <td><?= $row->ket_kendaraan ?> </td>
                  <td><?= $row->nm_barang ?> </td>
                  <td><?= $row->nm_jenis ?> </td>
                  <td><?= $row->nm_group ?> </td>
                  <td><?= $row->satuan ?> </td>
                  <td><?= $row->qty_order ?> </td>
                  <td><?= $row->qty_booked ?> </td>
                  <td><?= $row->qty_pending ?> </td>
                  <td><?= $row->qty_cancel ?> </td>
                  <td><?= $row->qty_supply ?> </td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
        </table>
