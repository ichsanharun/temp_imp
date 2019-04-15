<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
    @font-face { font-family: kitfont; src: url('1979 Dot Matrix Regular.TTF'); }
      html
        {
            margin:0;
            padding:0;
            font-style: kitfont;

            font-size:6pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-style: kitfont;
            font-size:6pt;
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
            width: 210mm;
            height: 145mm;
            page-break-after:always;
        }

        #header-tabel tr {
            padding: 0px;
            position: fixed;
        }

        #tabel-laporan {
            border-spacing: -1;
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
            height: 15px; //UKURANG ITEM
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
            font-size:7pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        img.resize {
          max-width:12%;
          max-height:12%;
        }
    </style>
</head>
<?php
$uk1 = 9;
$ukk = 17;
$ukkk = 11;
?>
<body style=<?php echo '"font-size: '.$uk1.'pt !important;"';?>>
    <div id="content-report1" style="padding-top:0px !important">
    <table width="100%" id="tabel-laporan" style=<?php echo '"font-size: '.$uk1.'px !important;font-weight:bold;padding-top:0px !important;font-family: Arial, Helvetica, sans-serif !important;"';?>>
        <tr>
            <th width="5%">NO</th>
            <th width="55%">KETERANGAN</th>
            <th width="20%">NO REFF</th>
            <th width="20%">TOTAL</th>
           
        </tr>
        <?php
        $loop	= 0;
        $total 	= 0;
        foreach(@$rows_data as $kd=>$vd){
            if($vd->debet > 0){
				$loop++;
				$total		+=$vd->debet;
				echo"<tr>";
					echo"<td align='center'>".$loop."</td>";
					echo"<td align='left'>".$vd->keterangan."</td>";
					echo"<td align='center'>".$vd->no_reff."</td>";
					echo"<td align='right'>".number_format($vd->debet)."</td>";
				echo"</tr>";
				
        
				}
		}
		echo"<tr>";
			echo"<th colspan='3' align='right'>GRAND TOTAL </th>";
			echo"<th align='right'>".number_format($total)."</th>";
		echo"</tr>";
		?>
        
    </table>
    </div>
</body>
</html>
