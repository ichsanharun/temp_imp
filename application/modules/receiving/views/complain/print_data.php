<?php
date_default_timezone_set("Asia/Bangkok");
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

<div id="wrapper">
    <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI</th>
            <th style="border-right: none;">Complain</th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
        <tr>
            <td width="10%">SUPPLIER</td>
            <td width="1%">:</td>
            <td colspan="2"><?= $rec->id_supplier ?></td>
            <td width="15%">NO. CONTAINER</td>
            <td width="1%">:</td>
            <td><?php echo @$rec->container_no?></td>
        </tr>
    </table>

    <table width="100%" id="tabel-laporan" border="1">
        <tr>
            <th width="%">NO</th>
            <th width="%">ITEM NO.</th>
            <th width="%">NAMA BARANG</th>
            <th width="%">QTY RUSAK</th>
            <th width="%">KETERANGAN</th>
        </tr>
        <?php
        $no=0;
        $t_rmb=0;
        $t_usd=0;
        $t_rp=0;
        $t_qty=0;
        foreach ($barang->result() as $row_bar)
        {
            $no++;
            ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $row_bar->id_barang ?></td>
                <td><?= $row_bar->nama_barang ?></td>
                <td><?= $row_bar->rusak ?></td>
                <td></td>
            </tr>
            <?php
            $query_koli = $this->db->query("SELECT * FROM `receive_detail_koli` WHERE no_po='$nopo' and id_barang='$row_bar->id_barang' AND rusak !='0'");

            foreach ($query_koli->result() as $row_koli)
            {
                ?>
                <tr>
                    <td></td>
                    <td><?= $row_koli->id_koli ?></td>
                    <td><?= $row_koli->nama_koli ?></td>
                    <td><?= $row_koli->rusak ?></td>
                    <td><?= $row_koli->keterangan ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
<?php $tglprint = date("d-m-Y H:i:s");?>
<htmlpagefooter name="footer">
    <div id="footer">
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap) ." On ". $tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />
</body>
</html>
