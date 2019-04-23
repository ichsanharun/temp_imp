<?php

 header("Content-type: application/vnd-ms-excel");

 header("Content-Disposition: attachment; filename=x.xls");

 header("Pragma: no-cache");

 header("Expires: 0");

 ?>
        <table border="1" width="100%">
            <thead>
                <tr>
                  <th>No</th>
                  <th>NO. SO</th>
                  <th>NAMA CUSTOMER</th>
                  <th>TANGGAL SO</th>
                  <th>STATUS ORDER</th>
                  <th>NAMA SALESMAN</th>
                  <th>DPP</th>
                  <th>PPN</th>
                  <th>TOTAL</th>
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
                  <td><?= $row->no_so ?> </td>
                  <td><?= $row->nm_customer ?> </td>
                  <td><?= $row->tanggal ?> </td>
                  <td><?= $row->stsorder ?> </td>
                  <td><?= $row->nm_salesman ?> </td>
                  <td><?= $row->dpp ?> </td>
                  <td><?= $row->ppn ?> </td>
                  <td><?= $row->total ?> </td>
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
