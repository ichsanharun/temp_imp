<?php
date_default_timezone_set("Asia/Bangkok");
header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=REPORT_PIUTANG_".date("d-M-Y_H:i:s").".xls");

header("Pragma: no-cache");

header("Expires: 0");
if($sales_pilih){
	$Judul_Sales	= $rows_header[0]->nm_salesman;
}else{
	$Judul_Sales	= "ALL SALES";
}
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
                  TTNT
                  </center>
              </th>
          </tr>
          <tr>
            <th colspan="16" style="text-align: left;">SALES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $Judul_Sales?></th>
          </tr>
          <tr>
            <th colspan="16" style="text-align: left;">TANGGAL : <?php echo date('d F Y')?></th>
          </tr>
          <tr>
              <th width="2%" rowspan="3">NO</th>
              <th width="13%" rowspan="3">CUSTOMER</th>
			  <th width="13%" rowspan="3">SALESMAN</th>
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
        $loop			= 0;
        $total_sub 		= 0;
		$total_inv		= 0;
		$Grand_Total	= 0;
		$Cust_Pilih		= '';
		$Grand_Inv		= 0;
        if(@$rows_header){
			foreach(@$rows_header as $kr=>$vr){
				$loop++;
				$piutang	= $vr->hargajualtotal - $vr->jum_bayar;
				$cust_name	= $vr->nm_customer;
				if($loop == 1){
					$Cust_Pilih	= $vr->nm_customer;
				} 
				if($Cust_Pilih != $cust_name){
					echo"<tr>";
						echo"<td colspan='6'><center><strong> TOTAL CUST ==> : ".$Cust_Pilih."</strong></center></td>";
						echo"<td style='text-align:right;font-weight:bold;'>".number_format($total_inv)."</td>";
						echo"<td style='text-align:right;font-weight:bold;'>".number_format($total_sub)."</td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
						echo"<td></td>";
					echo"</tr>";
					$Cust_Pilih	= $vr->nm_customer;
					$total_sub	= 0;
					$total_inv	= 0;
				}
				$total_sub		+=$piutang;
				$Grand_Total	+=$piutang;
				$total_inv		+=$vr->hargajualtotal;
				$Grand_Inv		+=$vr->hargajualtotal;
				echo"<tr>";
					echo"<td><center>".$loop."</center></td>";
					echo"<td>".$cust_name."</td>";
					echo"<td>".$vr->nm_salesman."</td>";
					echo"<td style='text-align:center;'>".date('d-m-Y',strtotime($vr->tanggal_invoice))."</td>";
					echo"<td>".$vr->no_invoice."</td>";
					echo"<td style='text-align:center;'>".date('d-m-Y',strtotime($vr->tgljatuhtempo))."</td>";
					echo"<td style='text-align:right;'>".number_format($vr->hargajualtotal)."</td>";
					echo"<td style='text-align:right;'>".number_format($piutang)."</td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td></td>";
					echo"<td><center>".$vr->umur."</center></td>";
				echo"</tr>";
			}
			echo"<tr>";
				echo"<td colspan='6'><center><strong> TOTAL CUST ==> : ".$Cust_Pilih."</strong></center></td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($total_inv)."</td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($total_sub)."</td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
			echo"</tr>";
			echo"<tr>";
				echo"<td colspan='6'><center><strong> TOTAL ALL CUSTOMER =====> </strong></center></td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($Grand_Inv)."</td>";
				echo"<td style='text-align:right;font-weight:bold;'>".number_format($Grand_Total)."</td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
				echo"<td></td>";
			echo"</tr>";
		}
      ?>
    </table>
    </div>
    <?php $tglprint = date("d-m-Y H:i:s");?>
<htmlpagefooter name="footer">
    <hr />
    <table width="100%" id="tabel-laporan">
        <tr>
            <td></td>
            <td width="10%"><center>DIPERIKSA</center></td>
            <td width="10%"><center>FISIK UANG TUNAI/GIRO</center></td>
            <td width="40%" colspan="2"><center>PENGEMBALIAN FAKTUR</center></td>
            <td width="40%" colspan="2"><center>PENYERAHAN FAKTUR</center></td>
        </tr>
        <tr>
            <td></td>
            <td width="20%" style="border-bottom: none;"><center></center></td>
            <td width="20%"><center>DITERIMA</center></td>
            <td width="20%"><center>DITERIMA</center></td>
            <td width="20%"><center>DISERAHKAN</center></td>
            <td width="20%"><center>DITERIMA</center></td>
            <td width="20%"><center>DISERAHKAN</center></td>
        </tr>
        <tr>
            <td></td>
            <td width="20%" style="height: 80px;border-top: none;" valign="bottom"><center>(..................................................)<br>SALES SPV</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>KASIR</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>ADM. PENAGIHAN</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>SALESMAN</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>SALESMAN</center></td>
            <td width="20%" style="height: 80px;" valign="bottom"><center>(..................................................)<br>ADM. PENAGIHAN</center></td>
        </tr>
        <tr>
            <td></td>
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
        <tr>
          <td colspan="7">PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap) ." On ". $tglprint; ?></td>
        </tr>
    </table>
    </div>
</htmlpagefooter>

</body>
</html>
