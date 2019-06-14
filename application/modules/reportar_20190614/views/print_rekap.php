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

        #tabel-laporan tr{
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

        th, td {
            padding: 15px;
            text-align: left;
        }

        tr.border_bottom td {
          border-bottom:1pt ridge black;
        }

        #summary table {
            border-collapse: collapse;
        }

        #summary th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body> 
<div id="wrapper"> 
    <p style="text-align:center; font-weight:bold; padding-top:5mm;">REKAP DATA STOK</p>
    <table width="100%" id="tabel-laporan">
        <tr>
            <th>Kode Produk</th>
			<th>Nama Set</th>
			<th>Jenis Produk</th>					
			<th>Satuan</th>			
			<th>Qty Stock</th>
			<th>Qty Available</th>
			<th>Qty Rusak</th>	
			<th>Landed Cost</th>
			<th>Harga</th>
			<th>Status</th>
        </tr>        
        <tr class="border_bottom" id='tabel-laporan'>   
        <?php                 
        if(empty($stok_data)){
		}else{
			$numb=0; foreach($stok_data AS $record){ $numb++; ?>
		<tr>
			<?php
				if($record->satuan==''){
					$satuan = $record->setpcs;
				}else{
					$satuan = $record->satuan;
				}
			?>
	        <td><?= $record->id_barang ?></td>
			<td><?= $record->nm_barang ?></td>	
			<td><?= strtoupper($record->jenis) ?></td>
			<td><?= $satuan ?></td>		
			<td><?= $record->qty_stock ?></td>	
			<td><?= $record->qty_avl ?></td>	
			<td><?= $record->qty_rusak ?></td>			
			<td><?= number_format($record->landed_cost) ?></td>	
			<td><?= number_format($record->harga) ?></td>	
			<td>
				<?php if($record->sts_aktif == 'aktif'){ ?>
					<label class="label label-success">Aktif</label>
				<?php }else{ ?>
					<label class="label label-danger">Non Aktif</label>
				<?php } ?>
			</td>
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