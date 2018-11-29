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
            border: none;
        }

        #tabel-laporan {
            border-spacing: -1px;
        }

        #tabel-laporan th{
            /*
            border-top: solid 1px #000;
            border-bottom: solid 1px #000;
            */
           border : solid 1px #000;
            margin: 0px;
            height: 30px;
        }

        #tabel-laporan td{
            border : solid 1px #000;
            margin: 0px;
            height: 30px;
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

        .td-none {
        border: none;
        height: 0px;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="7" width="20%" style="text-align: center;font-size: 18px;">PICKING LIST (PL)<br>No. :<?php echo $so_data->no_picking_list?></th>
        </tr>
        <tr>
            <td width="15%">NO. SO</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$so_data->no_so?></td>
            <td width="10%">TGL SO</td>
            <td width="1%">:</td>
            <td width="15%"><?php echo date('d-M-Y',strtotime(@$so_data->tanggal))?></td>
        </tr>

        <tr>
            <td width="10%">CUSTOMER</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo strtoupper(@$so_data->nm_customer)?></td>
            <td width="10%">SALES</td>
            <td width="1%">:</td>
            <td width="15%"><?php echo @$so_data->nm_salesman?></td>
        </tr>
         <tr>
            <td width="10%">Alamat Customer</td>
            <td width="1%">:</td>
            <td colspan="5"><?php echo strtoupper(@$customer->alamat)?></td>
        </tr>
    </table>
    <hr>
    <table width="100%" id="tabel-laporan">
        <tr>
            <th width="1%">No</th>
            <th width="20%">Nama Set</th>
            <th width="5%">Qty</th>
            <th width="5%">Satuan<br>(Set/Pcs)</th>
            <th width="5%">No</th>
            <th width="15%">Colly Produk</th>
            <th width="5%">Qty<br>(Colly)</th>
            <th width="5%">Total Qty<br>(Colly)</th>
            <th width="8%">Checker</th>
            <th width="8%">Supir</th>
        </tr>
        <?php
        $n=1;
        $total = 0;
        foreach(@$detail as $ks=>$vs){
        $no = $n++;
        $total += $vs->harga*$vs->qty_order;
        $colly = $this->Salesorder_model->get_data(array('id_barang' => $vs->id_barang),'barang_koli');
        $rs = count($colly)+1;
        ?>
        <tr>
            <td rowspan="<?php echo $rs?>" valign="middle"><center><?php echo $no?></center></td>
            <td rowspan="<?php echo $rs?>" valign="middle"><?php echo $vs->nm_barang?></td>
            <td width="5%" rowspan="<?php echo $rs?>" valign="middle"><center><?php echo $vs->qty_booked?></center></td>
            <td width="5%" rowspan="<?php echo $rs?>" valign="middle"><center><?php echo $vs->satuan?></center></td>
            <td colspan="4" style="border: none;height: 0px;padding-top: 0px;"></td>
            <td rowspan="<?php echo $rs?>" valign="middle"></td>
            <td rowspan="<?php echo $rs?>" valign="middle"></td>
        </tr>
        <?php
            $nc=1;
            foreach($colly as $kc=>$vc) {
                $ncl = $nc++;
                $q = $vs->qty_order;
        ?>
        <tr>
            <td><center><?php echo $no.'.'.$ncl?></center></td>
            <td style="padding-left: 10px;"><?php echo $vc->nm_koli?></td>
            <td><center><?php echo $vc->qty?></center></td>
            <td><center><?php echo $vc->qty*$q?></center></td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>
</div>
<?php $tglprint = date("d-m-Y H:i:s");?>
<htmlpagefooter name="footer">
    <table width="100%" border="0">
        <tr>
            <td width="25%"><center>Dibuat Oleh,</center></td>
            <td width="25%"><center>Pertugas Gudang</center></td>
            <td width="25%"><center>Checker</center></td>
            <td width="25%"><center>Supir</center></td>
        </tr>
        <tr>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
            <td style="height: 50px;"></td>
        </tr>
        <tr>
            <td><center>(Sales Planning & Support)</center></td>
            <td><center>(________________________)</center></td>
            <td><center>(________________________)</center></td>
            <td><center>(________________________)</center></td>
        </tr>
    </table>
    <hr />
    <div id="footer">
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap) ." On ". $tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />
</body>
</html>
