<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
      <style>
            table.dataGrid
            {
               border-collapse:collapse;
               border:1px solid black;
               width:100%;
               font-size: 11px;
            }
            table.dataGrid td
            {
               border:1px solid black;
               padding:3px 3px 3px 3px;
            }
            
            table.dataGrid td.tot
            {
              border:1px solid black;
              padding:3px 3px 3px 3px;
              background:lightgrey;
            }
            
            table.dataGrid tr.ganjil:hover,
            table.dataGrid tr.genap:hover
            {
              background:lightblue;
            }
            table.dataGrid tr.genap
            {
            background:dimegray;
            }
            table.dataGrid tr.ganjil
            {
            background:lightgrey;
            }
            
            table.dataGrid th
            {
                text-align:center;
             border:1px solid black;
            
             color:black;
            }


</style>
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
    $query = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$po_data->id_supplier'");
    $row = $query->row();
    ?>
<div id="wrapper">
    <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;"><?php echo 'NO. : '.@$po_data->no_po?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
         <tr>
            <td width="10%">NO.REFF</td>
            <td width="1%">:</td>
            <td colspan="2"></td>
            <td width="15%">Masa Produksi</td>
            <td width="1%">:</td>
            <td><?= $row->produksi_awal." - ".$row->produksi_akhir." hari" ?></td>
        </tr>
        <tr>
            <td width="10%">SUPPLIER</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$po_data->id_supplier.' / '.@get_supplier($po_data->id_supplier)?></td>
            <td width="15%">Masa Pengapalan Container</td>
            <td width="1%">:</td>
            <td><?= $row->pengapalan_awal." - ".$row->pengapalan_akhir." hari" ?></td>
        </tr>
        <tr>
            <td width="10%">TGL PO</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo date('d-M-Y',strtotime(@$po_data->tgl_po))?></td>
            <td width="15%">Masa Pengiriman</td>
            <td width="1%">:</td>
            <td><?= $row->pengiriman_awal." - ".$row->pengiriman_akhir." hari" ?></td>
        </tr>
         <tr>
           <td width="10%"></td>
           <td width="1%"></td>
           <td colspan="2"></td>
           <td width="15%">Masa Proses Cukai</td>
           <td width="1%">:</td>
           <td><?= $row->cukai_awal." - ".$row->cukai_akhir." hari" ?></td>
       </tr>
    </table>

    <table width="100%" class="dataGrid">
        <tr>
            <td>NO.</td>
            <td>Nama Barang</td>
            <td>QTY</td>
            <td>QTY ACC</td>
            <td>QTY INVOICE</td>
        </tr>
        <?php
        $no=0;
        foreach(@$detail as $ks=>$vs){
            $no ++;
            ?>
            <tr>
                <td><?= $no ?></td>
                <td><?php echo $vs->nm_barang?></td>
                <td><?php echo $vs->qty_po?></td>
                <td><?php echo $vs->qty_acc?></td>
                <td><?php echo $vs->qty_i?></td>
            </tr>
            <?php
        }
        ?>
    </table>

</div>
<?php $tglprint = date("d-m-Y H:i:s");?>     
<htmlpagefooter name="footer">

    <hr/>
    <div id="footer"> 
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap) ." On ". $tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />  
</body>
</html>