<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>

        body
        {
            width:100%;
            font-family:Arial;
            font-size:8pt;
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

    <table width="100%" id="tabel-laporan">

        <?php
        $n=1;
        $total = 0;
        foreach(@$detail as $ks=>$vs){
        $no = $n++;
        $total += $vs->harga*$vs->qty_order;
        $colly = $this->Salesorder_model->get_data(array('id_barang' => $vs->id_barang),'barang_koli');
        ?>
        <tr>
            <td width="1%" style="vertical-align: top;"><center><?php echo $no?></center></td>
            <td width="40%" style="vertical-align: top;"><?php echo $vs->nm_barang?></td>
            <td width="20%">
                 <?php
                $sn = 1;
                foreach($colly as $kc=>$vc){
                    echo $no.'.'.$sn++.' -'.$vc->nm_koli.'<br>';
                }
                ?>
            </td>
            <td width="7%" style="vertical-align: top;">
                <center>
                <?php
                $sn = 1;
                foreach($colly as $kc=>$vc){
                    echo $vc->qty.'<br>';
                }
                ?>
                </center>
            </td>
            <td width="7%" style="vertical-align: top;">
                <center>
                <?php
                $tc = 0;
                foreach($colly as $kc =>$vc){
                    $tc = $vc->qty*$vs->qty_order;
                    echo $tc.'<br>';
                }
                ?>
                </center>
            </td>
            <td width="8%" style="vertical-align: top;"><center><?php echo $vs->satuan?></center></td>
            <td width="5%" style="vertical-align: top;"><center><?php echo $vs->qty_order?></center></td>
            <td width="15%" style="text-align: right;vertical-align: top;"><?php echo formatnomor($vs->harga)?></td>
            <td width="15%" style="text-align: right;vertical-align: top;"><?php echo formatnomor($vs->harga*$vs->qty_order)?></td>

        </tr>
        <?php }
          $total = $total - @$so_data->persen_diskon_toko*$total/100 - @$so_data->diskon_cash + @$so_data->ppn
         ?>
    </table>
</div>

</body>
</html>
