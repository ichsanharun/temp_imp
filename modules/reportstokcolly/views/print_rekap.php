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
    <p style="text-align:center; font-weight:bold; padding-top:5mm;">REKAP DATA STOK COLLY</p>
    <table width="100%" id="tabel-laporan">
        <tr>
            <th width="5%">Kode Produk</th>
			<th width="10%">Nama Set</th>
			<th width="5%">Jenis<br>Produk</th>					
			<th width="5%">Satuan</th>			
			<th width="5%"><center>Qty Stock</center></th>
            <th width="15%"><center>Colly Produk</center></th>
            <th width="5%"><center>Qty<br>(Colly)</center></th>
        </tr>        
        <tr class="border_bottom" id='tabel-laporan'>   
        <?php                 
        if(empty($stok_data)){
		}else{
			$numb=0; 
            $n=1;
            foreach($stok_data AS $record){ 
            $numb++;
            $no=$n++;
            $colly = $this->Reportstokcolly_model->get_data(array('id_barang' => $record->id_barang),'barang_koli');
            $rs = count($colly)+1; 
        ?>
		<tr>
			<?php
				if($record->satuan==''){
					$satuan = $record->setpcs;
				}else{
					$satuan = $record->satuan;
				}
			?>
	        <td rowspan="<?php echo $rs?>" valign="middle"><?= $record->id_barang ?></td>
			<td rowspan="<?php echo $rs?>" valign="middle"><?= $record->nm_barang ?></td>	
			<td rowspan="<?php echo $rs?>" valign="middle"><center><?= strtoupper($record->jenis) ?></center></td>
			<td rowspan="<?php echo $rs?>" valign="middle"><center><?= $satuan ?></center></td>		
			<td rowspan="<?php echo $rs?>" valign="middle"><center><?= $record->qty_stock ?></center></td>
            <?php if($colly){ ?>
            <td colspan="2" style="border:none;height: 0px;padding-top:0px;"></td>
            <?php }else{ ?>
            <td><center>-</center></td>
            <td><center>-</center></td>
            <?php } ?>
        </tr>
        <?php
            $nc=1;
            foreach($colly as $kc=>$vc) {
                $ncl = $nc++;
                $q = $vs->qty_order;
        ?>
        <tr>
            <td style="padding-left: 10px;"><?php echo $vc->nm_koli?></td>
            <td><center><?php echo $record->qty_stock*$vc->qty?></center></td>
        </tr>
        <?php } ?>
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