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
            position: fixed;
        }

        #tabel-laporan {
            border-spacing: -1px;
            /*margin: 0px 5px 0px 0px;*/
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
    </style>
</head>
<body style="font-size: 11px !important;">
    <div id="content-report1" style="font-size: 10px !important;">
    <table width="100%" id="tabel-laporan">
        <tr>
            <th width="2%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="7%">QTY</th>
            <th width="5%">SATUAN<br>(SET/PCS)</th>
            <th width="5%">NO</th>
            <th width="20%">COLLY PRODUK</th>
            <th width="7%">QTY<br>(COLLY)</th>
            <th width="7%">TOTAL QTY<br>(COLLY)</th>
            <th width="15%">KETERANGAN</th>
        </tr>
        <?php
        $n=1;
        $total = 0;
        foreach(@$detail as $kd=>$vd){
            $no=$n++;
            $colly = $this->Salesorder_model->get_data(array('id_barang' => $vd->id_barang),'barang_koli');
            $rs = count($colly)+1;
        ?>
        <tr>
            <td rowspan="<?php echo $rs?>"><center><?php echo $no?></center></td>
            <td rowspan="<?php echo $rs?>"><?php echo $vd->nm_barang?></td>
            <td rowspan="<?php echo $rs?>"><center><?php echo $vd->qty_supply?></center></td>
            <td rowspan="<?php echo $rs?>"><center><?php echo $vd->satuan?></center></td>
            <td colspan="4" style="border: none;height: 0px;padding-top: 0;"></td>
            <td rowspan="<?php echo $rs?>"></td>
        </tr>
        <?php
            $nc=1;
            foreach($colly as $kc=>$vc) {
                $ncl = $nc++;
                $q = $vd->qty_supply;
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
</body>
</html>
