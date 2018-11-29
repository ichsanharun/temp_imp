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
            <th style="border-right: none;">MUTASI PRODUK<br><?php echo 'NO. : '.@$header->no_mutasi?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
        <tr>
            <td width="15%">CABANG ASAL</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo strtoupper(@$header->kdcab_asal.', '.@$header->cabang_asal)?></td>
            <td width="10%">NAMA SUPIR</td>
            <td width="1%">:</td>
            <td><?php echo $header->nm_supir?></td>
        </tr>
        <tr>
            <td width="15%">CABANG TUJUAN</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo strtoupper(@$header->kdcab_tujuan.', '.@$header->cabang_tujuan)?></td>
            <td width="10%">KENDARAAN</td>
            <td width="1%">:</td>
            <td><?php echo $header->ket_kendaraan?></td>
        </tr>
        <tr>
            <td width="15%">TGL MUTASI</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo date('d M Y',strtotime($header->tgl_mutasi)) ?></td>
            <td width="10%">KETERANGAN</td>
            <td width="1%">:</td>
            <td></td>
        </tr>
    </table>
    <br>
    <table width="100%" id="tabel-laporan">
        <tr>
            <th width="2%" class="text-center">NO</th>
            <th width="20%">KODE PRODUK</th>
            <th>NAMA PRODUK</th>
            <th width="10%">QTY MUTASI</th>
        </tr>
        <?php
            $n=1;
            foreach(@$detail as $k=>$v){ 
                $no=$n++;
        ?>
        <tr>
            <td><center><?php echo $no?></center></td>
            <td><center><?php echo $v->id_barang?></center></td>
            <td><?php echo $v->nm_barang?></td>
            <td><center><?php echo $v->qty_mutasi?></center></td>
        </tr>
        <?php } ?>
    </table>
    </div> 
    <?php $tglprint = date("d-m-Y H:i:s");?>     
<htmlpagefooter name="footer">
    <hr>
    <table width="100%" border="0">
        <tr>
            <td width="30%"><center>Dibuat Oleh,</center></td>
            <td width="30%"><center>Diterima Oleh,</center></td>
        </tr>
        <tr>
            <td width="15%" colspan="2" style="height: 50px;"></td>
        </tr>
        <tr>
            <td width="15%"><center>( KA.Gudang Asal)</center></td>
            <td width="15%"><center>( KA.Gudang Tujuan )</center></td>
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