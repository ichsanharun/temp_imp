<?php
date_default_timezone_set('Asia/Bangkok');
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>

        {
            margin:0;
            padding:0;
            font-family:Arial;
            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-size:10pt;
            margin:0;
            padding:0;
        }

        p
        {
            margin:0;
            padding:0;
        }

        .page
        {
            /*height:297mm;
            width:210mm;*/
            width: 297mm;
            height: 210mm;
            page-break-after:always;
        }

        #header-tabel tr {
            padding: 0px;
        }

        #tabel-laporan {
            border-spacing: -1px;
        }

        #tabel-laporan th{
            border-top: solid 1px #000;
            border-bottom: solid 1px #000;
            margin: 0px;
        }

        #footer
        {
            /*width:180mm;*/
            margin:0 15mm;
            padding-bottom:3mm;
        }
        #footer table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;

            background:#eee;

            border-spacing:0;
            border-collapse: collapse;
        }
        #footer table td
        {
            width:25%;
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        img.resize {
          max-width:12%;
          max-height:12%;
        }
    </style>
</head>
<body>
<?php
$query_sup = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$po->id_supplier'");

$row_sup = $query_sup->row();
?>
<div id="wrapper">
    <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;">Packing List </th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
         <tr>
            <td width="10%">SUPPLIER</td>
            <td width="1%">:</td>
            <td colspan="2"><?= $row_sup->nm_supplier; ?></td>
            <td width="15%">Finish Produksi</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y', strtotime(@$inv->finish_produksi)); ?></td>
        </tr>
        <tr>
            <td width="10%"></td>
            <td width="1%"></td>
            <td colspan="2"><?= $row_sup->alamat; ?></td>
            <td width="15%">Proses Shipping</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y', strtotime(@$inv->proses_shipping)); ?></td>
        </tr>
        <tr>
            <td width="10%">No. Invoice</td>
            <td width="1%">:</td>
            <td colspan="2"><?= $inv->no_invoice; ?></td>
            <td width="15%">Shipping (ETD)</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y', strtotime(@$inv->shipping)); ?></td>
        </tr>
        <tr>
           <td width="10%">Star Produksi</td>
           <td width="1%">:</td>
           <td colspan="2"><?php echo date('d-M-Y', strtotime(@$inv->start_produksi)); ?></td>
           <td width="15%">ETA</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y', strtotime(@$inv->eta)); ?></td>
       </tr>
    </table>

    <table width="100%" id="tabel-laporan" border="1">
        <tr>
            <th width="%">NO</th>
            <th width="%">ITEM NO.</th>
            <th width="%">NAMA BARANG</th>
            <th width="%">QTY</th>
            <th width="%">RMB</th>
            <th width="%">USD (<?= formatnomor($po->dollar); ?>)</th>
            <th width="%">RUPIAH (<?= formatnomor($po->rupiah); ?>)</th>
        </tr>
        <?php
        $no = 0;
        $t_rmb = 0;
        $t_usd = 0;
        $t_rp = 0;
        $t_qty = 0;
        foreach ($detail->result() as $row_det) {
            ++$no;
            $t_rmb += $row_det->harga_satuan;
            $t_usd += $row_det->harga_satuan / $po->dollar;
            $t_rp += ($row_det->harga_satuan / $po->dollar) * $po->rupiah;
            $t_qty += $row_det->qty_i; ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $row_det->id_barang; ?></td>
                <td><?= $row_det->nm_barang; ?></td>
                <td style="text-align: center"><?= $row_det->qty_i; ?></td>
                <td style="text-align: right"><?= formatnomor($row_det->harga_satuan); ?></td>
                <td style="text-align: right"><?= formatnomor($row_det->harga_satuan / $po->dollar); ?></td>
                <td style="text-align: right"><?= formatnomor(($row_det->harga_satuan / $po->dollar) * $po->rupiah); ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="3" style="text-align: center">TOTAL</td>
            <td style="text-align: right"><?= formatnomor($t_qty); ?></td>
            <td style="text-align: right"><?= formatnomor($t_rmb); ?></td>
            <td style="text-align: right"><?= formatnomor($t_usd); ?></td>
            <td style="text-align: right"><?= formatnomor($t_rp); ?></td>
        </tr>
    </table>
</div>
<?php $tglprint = date('d-m-Y H:i:s'); ?>
<htmlpagefooter name="footer">
    <div id="footer">
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap).' On '.$tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />
</body>
</html>
