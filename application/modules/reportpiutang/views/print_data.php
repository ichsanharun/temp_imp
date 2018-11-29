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
            font-family:Calibri;
            font-size:7pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Calibri;
            font-size:7pt;
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
           border : dotted 1px #000;
            margin: 0px;
            height: 20px;
        }

        #tabel-laporan td{
            border : dotted 1px #000;
            margin: 0px;
            height: 20px;
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
<body style="border: solid 1px #000;">
    <div id="wrapper">
    <table width="100%" id="tabel-laporan">
          <tr>
              <th colspan="16" style="font-size: 12pt !important;">
                  <center>
                  TTNT
                  </center>
              </th>
          </tr>
          <tr>
            <th colspan="16" style="text-align: left;">SALES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo @$header[0]->nm_salesman?></th>
          </tr>
          <tr>
            <th colspan="16" style="text-align: left;">TANGGAL : <?php echo date('d F Y')?></th>
          </tr>
          <tr>
              <th width="2%" rowspan="3">NO</th>
              <th width="13%" rowspan="3">CUSTOMER</th>
              <th rowspan="3">TGL INVOICE</th>
              <th rowspan="3">NO INVOICE</th>
              <th rowspan="3">JATUH TEMPO</th>
              <th rowspan="3">NILAI INVOICE</th>
              <th rowspan="3">SISA PIUTANG</th>
              <th colspan="8">DIBAYAR DENGAN</th>
              <th width="5%" rowspan="3">KET</th>
          </tr>
          <tr>
              <th width="7%" rowspan="2">TUNAI</th>
              <th width="7%" rowspan="2">TRANSFER</th>
              <th colspan="4">GIRO</th>
              <th width="7%" rowspan="2">DISKON<br>JUAL</th>
              <th width="7%" rowspan="2">RETURN<br>JUAL</th>
          </tr>
          <tr>
              <th width="7%">NILAI GIRO</th>
              <th width="7%">NM. BANK</th>
              <th width="7%">NO. GIRO</th>
              <th width="7%">JTT</th>
          </tr>
       <?php
        $n=1;
        if(@$header){
        foreach(@$header as $kr=>$vr){
          $no = $n++;
          $total += $vr->piutang;
          $umur = selisih_hari($vr->tanggal_invoice,date('Y-m-d'));
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><?php echo $vr->nm_customer?></td>
          <td><center><?php echo date('d-m-Y',strtotime($vr->tanggal_invoice))?></center></td>
          <td><center><?php echo $vr->no_invoice?></center></td>
          <td><center><?php echo date('d-m-Y',strtotime($vr->tgljatuhtempo))?></center></td>
          <td style="text-align: right;"><?php echo formatnomor($vr->hargajualtotal)?></td>
          <td style="text-align: right;"><?php echo formatnomor($vr->piutang)?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><center><?php echo $umur?></center></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="6"><center><b>TOTAL</b></center></td>
            <td style="text-align: right;"><b><?php echo formatnomor($total)?></b></td>
            <td colspan="9"></td>
        </tr>
        <?php } ?>
    </table>
    </div> 
    <?php $tglprint = date("d-m-Y H:i:s");?>     
<htmlpagefooter name="footer">
    <hr />
    <table width="100%" id="tabel-laporan">
        <tr>
            <td width="10%"><center>DIPERIKSA</center></td>
            <td width="10%"><center>FISIK UANG TUNAI/GIRO</center></td>
            <td width="40%" colspan="2"><center>PENGEMBALIAN FAKTUR</center></td>
            <td width="40%" colspan="2"><center>PENYERAHAN FAKTUR</center></td>
        </tr>
        <tr>
            <td width="20%" style="border-bottom: none;"><center></center></td>
            <td width="20%"><center>DITERIMA</center></td>
            <td width="20%"><center>DITERIMA</center></td>
            <td width="20%"><center>DISERAHKAN</center></td>
            <td width="20%"><center>DITERIMA</center></td>
            <td width="20%"><center>DISERAHKAN</center></td>
        </tr>
        <tr>
            <td width="20%" style="height: 80px;border-top: none;" valign="bottom"><center>(..................................................)<br>SALES SPV</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>KASIR</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>ADM. PENAGIHAN</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>SALESMAN</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>SALESMAN</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>ADM. PENAGIHAN</center></td>
        </tr>
        <tr>
            <td width="20%">&nbsp;&nbsp;&nbsp;TGL : </td>
            <td width="20%">&nbsp;&nbsp;&nbsp;TGL : </td>
            <td width="20%">&nbsp;&nbsp;&nbsp;TGL : </td>
            <td width="20%">&nbsp;&nbsp;&nbsp;TGL : </td>
            <td width="20%">&nbsp;&nbsp;&nbsp;TGL : </td>
            <td width="20%">&nbsp;&nbsp;&nbsp;TGL : </td>
        </tr>
    </table>
    <br>
    <div id="footer"> 
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap) ." On ". $tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />  
</body>
</html> 