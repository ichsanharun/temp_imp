<?php
date_default_timezone_set("Asia/Bangkok");
header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=REPORT_PIUTANG_BULANAN_".date("d-M-Y_H:i:s").".xls");

header("Pragma: no-cache");

header("Expires: 0");
$Arr_Data	= array(
	'no_invoice'			=> 'No Invoice',
	'customer'				=> 'Customer',
	'bln'					=> 'Bulan',
	'thn'					=> 'Tahun',
	'saldo_awal'			=> 'Saldo Awal',
	'debet'					=> 'Debet',
	'kredit'				=> 'Kredit',
	'saldo_akhir'			=> 'Saldo Akhir'
);
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>



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
                  <?php echo $judul;?>
                  </center>
              </th>
          </tr>
         
          <tr>
			<?php
			echo" <th align='center'>No</th>";
			foreach($Arr_Data as $key=>$vals){
				echo" <th align='center'>".$vals."</th>";
			}
			?>
          </tr>
       <?php
        $Total_Awal	= $Total_Debet	= $Total_Kredit	= $Total_Akhir = 0;
        if(@$results){
			$loop	=0;
			foreach($results as $key=>$val){
				$loop++;
				$NewRow++;
				echo"<tr>";
				echo"<td>$loop</td>";
				foreach($Arr_Data as $keyF=>$valF){
					$intD++;
					$Mulai++;
					if($intD==3){
						$Nil_Data		= date('F',mktime(0,0,0,$val->$keyF,1,date('Y')));
					}else if($intD==5){
						$Nil_Data		= number_format(round($val->$keyF));
						$Total_Awal		+=round($val->$keyF);
					}else  if($intD==6){
						$Nil_Data		= number_format(round($val->$keyF));
						$Total_Debet		+=round($val->$keyF);
					}else if($intD==7){
						$Nil_Data		= number_format(round($val->$keyF));
						$Total_Kredit	+= round($val->$keyF);
					}else if($intD==8){
						$Nil_Data		= number_format(round($val->$keyF));
						$Total_Akhir	+= round($val->$keyF);
					}else{
						$Nil_Data		= $val->$keyF;
					}
					echo"<td>$Nil_Data</td>";
				}
				echo"</tr>";
				
			}
			echo"<tr>";
				echo"<td colspan='5'><center><strong> GRAND TOTAL</strong></center></td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($Total_Awal)."</td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($Total_Debet)."</td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($Total_Kredit)."</td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($Total_Akhir)."</td>";
				
			echo"</tr>";
			
		}
      ?>
    </table>
    </div>
   

</body>
</html>
