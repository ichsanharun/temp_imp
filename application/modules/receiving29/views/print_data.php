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
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;">RECEIVING PO<br><?php echo 'NO. : '.@$header->no_receiving?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
         <tr>
            <td width="10%">SUPPLIER</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$header->id_supplier.' / '.@$header->nm_supplier?></td>
            <td width="15%">NO. SJ SUPPLIER</td>
            <td width="1%">:</td>
            <td><?php echo @$header->no_sjsupplier?></td>
        </tr>
        <tr>
            <td width="10%">TGL REC.</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo date('d-M-Y',strtotime(@$header->tglreceive))?></td>
            <td width="15%">TGL SJ SUPPLIER</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y',strtotime(@$header->tgl_sjsupplier))?></td>
        </tr>
    </table>
    <table width="100%" id="tabel-laporan">
       <tr>
            <th width="1%">NO</th>
            <th width="15%">NO. PO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="20%">COLLY</th>
            <th width="7%">QTY COLLY</th>
            <th width="7%">TOTAL COLLY</th>
            <th width="10%">QTY RECEIVED</th>
            <th width="10%">SATUAN</th>
            <th width="10%">HARGA BELI</th>
            <th width="10%">TOTAL</th>
            <th width="10%">KETERANGAN</th>
        </tr>
        <?php
        if(@$detail){
            $n=1;
            $total=0;
            foreach(@$detail as $kdr=>$vdr){
                $no=$n++;
                $total += $vdr->jumlah*$vdr->hargabeli;
                $colly = $this->Receiving_model->get_data(array('id_barang' => $vdr->kodebarang),'barang_koli');

        ?>
        <tr>
            <td><center><?php echo $no?></center></td>
            <td><center><?php echo $vdr->po_no?></center></td>
            <td><?php echo $vdr->kodebarang.' / '.$vdr->namabarang?></td>
            <td>
                 <?php 
                $sn = 1;
                foreach($colly as $kc=>$vc){
                    echo $no.'.'.$sn++.' -'.$vc->nm_koli.'<br>';
                }
                ?>
            </td>
            <td style="vertical-align: top;">
                <center>
                <?php 
                $sn = 1;
                foreach($colly as $kc=>$vc){
                    echo $vc->qty.'<br>';
                }
                ?>
                </center>
            </td>
            <td style="vertical-align: top;">
                <center>
                <?php 
                $tc = 0;
                foreach($colly as $kc){
                    $tc = count($colly);
                    echo $tc.'<br>';
                }
                ?>
                </center>
            </td>
            <td><center><?php echo $vdr->jumlah?></center></td>
            <td><center><?php echo $vdr->Satuan?></center></td>
            <td style="text-align: right;"><?php echo formatnomor($vdr->hargabeli)?></td>
            <td style="text-align: right;"><?php echo formatnomor($vdr->jumlah*$vdr->hargabeli)?></td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>
    </div> 
    <?php $tglprint = date("d-m-Y H:i:s");?>     
<htmlpagefooter name="footer">
    <hr>
    <table width="100%" border="0">
        <tr>
            <td colspan="3">
                <i><?php echo "TERBILANG : ".ucwords(ynz_terbilang_format($total))?></i>
            </td>
            <td width="15%"><b>GRAND TOTAL</b></td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><b><?php echo formatnomor($total)?></b></td>
            <td width="10%"></td>
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