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
<body>
    <div id="wrapper">
        <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;">INVOICE (FAKTUR)<br><?php echo 'NO. : '.@$header->no_invoice?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
        <tr>
            <td width="10%">NO. SO</td>
            <td width="1%">:</td>
            <td colspan="2"></td>
            <td width="15%">Yogyakarta</td>
            <td width="1%">,</td>
            <td><?php echo date('d/m/Y',strtotime(@$header->tanggal_invoice))?></td>
        </tr>
        <tr>
            <td width="10%">SALES</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$header->nm_salesman?></td>
            <td width="15%">Kepada Yth,</td>
            <td width="1%"></td>
            <td></td>
        </tr>
        <tr>
            <td width="10%">TOP</td>
            <td width="1%">:</td>
            <td colspan="2">
                <?php echo '45 HARI  &nbsp;&nbsp;&nbsp; TGL JATUH TEMPO : '.date('d/m/Y',strtotime(@$header->tgljatuhtempo))?>
            </td>
            <td width="15%" colspan="3">
                <?php echo @$header->nm_customer?>
            </td>
        </tr>
        <tr>
            <td width="10%">KETERANGAN</td>
            <td width="1%">:</td>
            <td colspan="2"></td>
            <td width="15%" colspan="3">
                <?php echo @$header->alamatcustomer?>
            </td>
        </tr>
    </table>
    <table width="100%" id="tabel-laporan">
       <tr>
            <th width="5%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="5%">QTY</th>
            <th width="10%">SATUAN<br>(SET/PCS)</th>
            <th width="10%">HARGA SET</th>
            <th width="10%">DISKON %</th>
            <th width="10%">HARGA TOTAL</th>
            <!--<th width="20%">KETERANGAN</th>-->
        </tr>
        <?php 
        $n = 1;
        foreach(@$detail as $kd=>$vd){ 
            $no = $n++;
            $jumnom += $vd->hargajual*$vd->jumlah;
            $ongkir = 0;
            //$grand = $jumnom+$ongkir;
        ?>
        <tr>
            <td style="vertical-align: middle;"><center><?php echo $no?></center></td>
            <td style="vertical-align: middle;"><?php echo $vd->nm_barang?></td>
            <td style="vertical-align: middle;"><center><?php echo $vd->jumlah?></center></td>
            <td style="vertical-align: middle;"><center><?php echo $vd->satuan?></center></td>
            <td style="text-align: right;vertical-align: middle;"><?php echo formatnomor($vd->hargajual)?></td>
            <td style="vertical-align: middle;"><center><?php echo formatnomor($vd->diskon)?></center></td>
            <td style="text-align: right;vertical-align: middle;"><?php echo formatnomor($vd->hargajual*$vd->jumlah)?></td>
            <!--<td style="text-align: center;vertical-align: middle;"> No DO : <?php //echo $vd->no_do; ?></td>-->
        </tr>
        <?php } ?>
    </table>
    </div> 
    <?php $tglprint = date("d-m-Y H:i:s");?>     
<htmlpagefooter name="footer">
    <hr>
    <table width="100%" border="0">
        <tr>
            <td colspan="3">
                <i><?php echo "TERBILANG : ".ucwords(ynz_terbilang_format($header->hargajualtotal))?></i>
            </td>
            <td width="15%">JUMLAH NOMINAL</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($header->dpp)?></td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td width="15%">TOTAL PPN</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($header->ppn)?></td>
            <td width="10%"></td>
        </tr>
		<tr>
            <td colspan="3"></td>
            <td width="15%">BIAYA MATERAI</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($header->meterai)?></td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td colspan="3">
                <center>Hormat Kami,</center>
            </td>
            <td width="15%">ONGKOS KIRIM</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($ongkir)?></td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td width="15%">GRAND TOTAL</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($header->hargajualtotal)?></td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td colspan="3" style="height: 40px;"></td>
        </tr>
        <tr>
            <td colspan="3">
                <center>(BM/SPV)</center>
            </td>
        </tr>
        <tr>
            <td colspan="7">
                Catatan : Pembayaran dengan cek/giro dianggap lunas apabila sudah dicairkan.
            </td>
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