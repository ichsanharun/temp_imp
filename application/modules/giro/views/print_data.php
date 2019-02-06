<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Giro</title>
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
            height:297mm;
            width:210mm;
            page-break-after:always;
        }
 
        table
        {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            border-spacing:0;
            border-collapse: collapse; 
             
        }
         
        table td 
        {
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
         
        table.heading
        {
            height:20mm;
        }
         
        h1.heading
        {
            font-size:14pt;
            color:#000;
            font-weight:normal;
        }
         
        h2.heading
        {
            font-size:9pt;
            color:#000;
            font-weight:normal;
        }
         
        hr
        {
            color:#ccc;
            background:#ccc;
        }

        #cv_datadiri table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
     
            border-spacing:0;
            border-collapse: collapse; 
             
            margin-top:5mm;
        }

        #cv_body
        {
            height: 149mm;
        }
         
        #cv_body , #invoice_total
        {   
            width:100%;
        }
        #cv_body table , #invoice_total table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
     
            border-spacing:0;
            border-collapse: collapse; 
             
            margin-top:5mm;
        }
         
        #cv_body table td , #invoice_total table td
        {
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding:2mm 0;
        }
         
        #cv_body table td.mono  , #invoice_total table td.mono
        {
            font-family:monospace;
            text-align:right;
            padding-right:3mm;
            font-size:10pt;
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
    <p style="text-align:center; font-weight:bold; padding-top:5mm;">RINCIAN GIRO OPNAME<br>Per Tgl. : <?php echo date('d-m-Y')?></p>
    <table style="width:100%;" border="1" width="100%">
        <tr>
            <th width="1%" rowspan="2">NO</th>
            <th colspan="2">DITERIMA</th>
            <th colspan="4">CHEQUE / GIRO</th>
            <th width="5%" rowspan="2">STATUS</th>
        </tr>
        <tr>
            <th><center>TANGGAL</center></th>
            <th><center>CUSTOMER</center></th>
            <th><center>BANK</center></th>
            <th><center>NOMOR</center></th>
            <th><center>J. TEMPO</center></th>
            <th><center>NOMINAL</center></th>
        </tr>
        <?php
        $n=1;
        $tot_nom =0;
        if(@$giro){
        foreach(@$giro as $kg=>$vg){
            $no=$n++;
            $tot_nom += $vg->nilai_fisik;
        ?>
        <tr>
            <td><center><?php echo $no?></center></td>
            <td><center><?php echo date('d-m-Y',strtotime($vg->tgl_giro))?></center></td>
            <td><?php echo $vg->nm_customer?></td>
            <td><?php echo $vg->nm_bank?></td>
            <td><?php echo $vg->no_giro?></td>
            <td><center><?php echo date('d-m-Y',strtotime($vg->tgl_jth_tempo))?></center></td>
            <td style="text-align: right;"><?php echo formatnomor($vg->nilai_fisik)?></td>
            <td><center><?php echo $vg->status?></center></td>
        </tr>
        <?php } ?>
        <tr>
            <td style="text-align: right;" colspan="5">SALDO ADM PEMBUKUAN : </td>
            <td colspan="2" style="text-align: right;"><?php echo formatnomor($tot_nom)?></td>
        </tr>
        <tr>
            <td style="text-align: right;" colspan="5">SALDO CHECK FISIK : </td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style="text-align: right;" colspan="5">SELISIH : </td>
            <td colspan="2"></td>
        </tr>
        <?php }else{ ?>
        <tr><td colspan="8"><center>Tidak ada data yang ditampilkan</center></td></tr>
        <?php } ?>
    </table>
     
    <?php $tglprint = date("d-m-Y H:i:s");?>     
    <htmlpagefooter name="footer">
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