<?php
date_default_timezone_set('Asia/Jakarta');
set_time_limit(0);
$sroot 			= $_SERVER['DOCUMENT_ROOT'];

//echo"<pre>";print_r($Split_Beda);exit;
include $sroot."/application/libraries/MPDF57/mpdf.php";
//echo $sroot."/application/libraries/MPDF57/mpdf.php";exit;
$mpdf=new mPDF('utf-8', 'A4-L');				// Create new mPDF Document

//Beginning Buffer to save PHP variables and HTML tags
ob_start();
if($sales_pilih){
	$Judul_Sales	= $rows_header[0]->nm_salesman;
}else{
	$Judul_Sales	= "ALL SALES";
}
?>  

<style type="text/css">
@page {
	margin-top: 0.8cm;
    margin-left: 1cm;
    margin-right: 1cm;
	margin-bottom: 0.8cm;
}
.font{
	font-family: verdana,arial,sans-serif;
	font-size:14px;
}
.fontheader{
	font-family: verdana,arial,sans-serif;
	font-size:13px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}

table.noborder {
	font-family: verdana,arial,sans-serif;
}

table.noborder th {
	font-size:12px;
	padding: 1px;
	border-color: #666666;
}

table.noborder td {
	font-size:12px;
	padding: 1px;
	border-color: #666666;
	background-color: #ffffff;
}

table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}

table.gridtable th {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #f2f2f2;
}

table.gridtable th.head {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #7f7f7f;
	color: #ffffff;
}
table.gridtable td {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}

table.gridtable td zero {
	border-width: 1px;
	padding: 5px;
	border-color: #666666;
	background-color: #ffffff;
}

table.gridtable td.cols {
	border-width: 1px;
	padding: 5px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}

table.cooltabs {
	font-size:12px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
}

table.cooltabs th.reg {
	font-family: verdana,arial,sans-serif;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px;
}

table.cooltabs td.reg {
	font-family: verdana,arial,sans-serif;
    border-radius: 5px 5px 5px 5px;
    padding: 5px;
	border-width: 1px;
}

#cooltabs {
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px; 
    width: 800px;
    height: 20px; 
}

#cooltabs2{
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 5px 5px;
    background: #e3e0e4;
    padding: 5px; 
    width: 180px;
    height: 10px;
}

#space{
    padding: 3px; 
    width: 180px;
    height: 1px;
}

#cooltabshead{
	font-size:12px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 5px 5px 0 0;
    background: #dfdfdf;
    padding: 5px; 
    width: 162px;
    height: 10px;
	float:left;
}

#cooltabschild{
	font-size:10px;
	font-family: verdana,arial,sans-serif;
	border-width: 1px;
	border-style: solid;
    border-radius: 0 0 5px 5px;
    padding: 5px; 
    width: 162px;
    height: 10px;
	float:left;
}

p {
  margin: 0 0 0 0;
}

p.pos_fixed {
	font-family: verdana,arial,sans-serif;
    position: fixed;
    top: 50px;
    left: 230px;
}

p.pos_fixed2 {
	font-family: verdana,arial,sans-serif;
    position: fixed;
    top: 589px;
    left: 230px;
}

p.notesmall {
	font-size: 9px;
}

.barcode {
    padding: 1.5mm;
    margin: 0;
    vertical-align: top;
    color: #000044;
}

.barcodecell {
    text-align: center;
    vertical-align: middle;
	position: fixed;
	top: 14px;
	right: 10px;
}

p.pt {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 62px;
    left: 5px;
}

h2 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
    top: 15px;
    left: 400px;
	}

h3 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
	top: 40px;
	left: 400px;
}
	
p.reg {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
}

p.sub {
	font-family: verdana,arial,sans-serif;
	font-size:13px;
    position: fixed;
    top: 55px;
    left: 220px;
	color: #6b6b6b;
}

p.header {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color: #330000;
}

p.barcs {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
    position: fixed;
    top: 30px;
    right: 1px;
}

p.alamat {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 71px;
    left: 5px;
}

p.tlp {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 80px;
    left: 5px;
}

p.date {
	font-family: verdana,arial,sans-serif;
	font-size:12px;
    text-align: right;
}

p.foot {
	font-family: verdana,arial,sans-serif;
	font-size:7px;
    position: fixed;
    top: 750px;
    left: 5px;
}

p.footer {
	font-family: verdana,arial,sans-serif;
	font-size:9px;
    position: fixed;
    bottom: 5px;
    left: 2px;
}

p.ln {
	font-family: verdana,arial,sans-serif;
	font-size:9px;
    position: fixed;
    bottom: 1px;
    left: 2px;
}

#hrnew {
    border: 0;
    border-bottom: 1px dashed #ccc;
    background: #999;
}
</style>

<?php
$today 		= date("d-m-Y - H:i:s");
$date_now	= date("Y-m-d");



$header		="
<div>
<img src='".$sroot."/assets/img/logo.JPG' style='float:left' width='172' height='60'/>
</div>
<div id='space'></div>

<p class='pt'>PT IMPORTA JAYA ABADI</p>
<p class='alamat'>YOGYAKARTA</p>



<h2 align='center'>REPORT TTNT</h2>
<h3 align='center'>SALES : ".$Judul_Sales."</h3>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<table class='noborder' width='100%'>
	
	<tr>
		<td align='left' width='15%'>Tanggal</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='33%'>".date('d F Y')."</td>
		<td align='left' width='15%'></td>
		<td align='left' width='2%'></td>
		<td align='left' width='33%'></td>
	</tr>
</table>
<div id='space'></div>";
echo $header;
	
	echo"<table class='gridtable' width='100%'>
			<tr>
				<th align='left' colspan='17'># Detail</th>
			</tr>
			<tr>
				<th width='5%' align='center' rowspan='3'>No.</th>
				<th width='13%' align='center' rowspan='3'>Customer</th>
				<th width='13%' align='center' rowspan='3'>Salesman</th>
				<th align='center' rowspan='3'>Tgl Invoice</th>
				<th align='center' rowspan='3'>No Invoice</th>
				<th align='center' rowspan='3'>Jatuh Tempo</th>
				<th align='center' rowspan='3'>Nilai Invoice</th>
				<th align='center' rowspan='3'>Sisa Piutang</th>
				<th align='center' colspan='8'>Dibayar Dengan</th>
				<th width='5%' align='center' rowspan='3'>Ket</th>
			</tr>
			<tr>
				<th width='7%' align='center' rowspan='2'>Tunai</th>
				<th width='7%' align='center' rowspan='2'>Transfer</th>
				<th align='center' colspan='4'>Giro</th>
				<th width='7%' align='center' rowspan='2'>Disc Jual</th>
				<th width='7%' align='center' rowspan='2'>Return Jual</th>
			</tr>
			<tr>				
				<th align='center'>Nilai Giro</th>
				<th align='center'>Nama Bank</th>
				<th align='center'>No Giro</th>
				<th align='center'>JTT</th>
			</tr>";
		$loop			= 0;
        $total_sub 		= 0;
		$total_inv		= 0;
		$Grand_Total	= 0;
		$Cust_Pilih		= '';
		$Grand_Inv		= 0;
		$rows			= 0;
		$pages			= 0;
		$next_page		= 1;
		$batas			= 11;
        if($rows_header){
			foreach($rows_header as $kr=>$vr){
				$loop++;
				$rows++;
				
				$piutang	= $vr->hargajualtotal - $vr->jum_bayar;
				$cust_name	= $vr->nm_customer;
				if($loop == 1){
					$Cust_Pilih	= $vr->nm_customer;
				} 
				if($rows==$batas){
					echo "</table>";			
					echo"<div id='space'></div>
						<div id='space'></div>
						<p class='footer'>
						<i>Page : ".$next_page."<br>PT IMPORTA JAYA ABADI - Printed by : ".ucwords($userData->nm_lengkap).", ".$today."</i>
						</p>";
					echo "<pagebreak>";
					echo $header;
					echo"<table class='gridtable' width='100%'>
							<tr>
								<th align='left' colspan='17'># Detail</th>
							</tr>
							<tr>
								<th width='5%' align='center' rowspan='3'>No.</th>
								<th width='13%' align='center' rowspan='3'>Customer</th>
								<th width='13%' align='center' rowspan='3'>Salesman</th>
								<th align='center' rowspan='3'>Tgl Invoice</th>
								<th align='center' rowspan='3'>No Invoice</th>
								<th align='center' rowspan='3'>Jatuh Tempo</th>
								<th align='center' rowspan='3'>Nilai Invoice</th>
								<th align='center' rowspan='3'>Sisa Piutang</th>
								<th align='center' colspan='8'>Dibayar Dengan</th>
								<th width='5%' align='center' rowspan='3'>Ket</th>
							</tr>
							<tr>
								<th width='7%' align='center' rowspan='2'>Tunai</th>
								<th width='7%' align='center' rowspan='2'>Transfer</th>
								<th align='center' colspan='4'>Giro</th>
								<th width='7%' align='center' rowspan='2'>Disc Jual</th>
								<th width='7%' align='center' rowspan='2'>Return Jual</th>
							</tr>
							<tr>				
								<th align='center'>Nilai Giro</th>
								<th align='center'>Nama Bank</th>
								<th align='center'>No Giro</th>
								<th align='center'>JTT</th>
							</tr>";
					$next_page++;				
					$rows	=0;
				}
				if($Cust_Pilih != $cust_name){
					echo"<tr>";
						echo"<td colspan='6'><center><strong> TOTAL CUST ==> : ".$Cust_Pilih."</strong></center></td>";
						echo"<td style='text-align:right;font-weight:bold;'>".number_format($total_inv)."</td>";
						echo"<td style='text-align:right;font-weight:bold;'>".number_format($total_sub)."</td>";
						echo"<td colspan='9'></td>";
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
			echo"<td colspan='9'></td>";
		echo"</tr>";
		echo"<tr>";
			echo"<td colspan='6'><center><strong> TOTAL ALL CUSTOMER =====> </strong></center></td>";
			echo"<td style='text-align:right;font-weight:bold;'>".number_format($Grand_Inv)."</td>";
			echo"<td style='text-align:right;font-weight:bold;'>".number_format($Grand_Total)."</td>";
			echo"<td colspan='9'></td>";
		echo"</tr>";
	}
echo"</table>";

?>


<div id='space'></div>
<div id='space'></div>

<?php
$rows_baru	= $rows + 5;
if($rows_baru > $batas){
	echo "</table>";			
	echo"<div id='space'></div>
		<div id='hrnew'></div>
		<div id='space'></div>
		<p class='footer'>
		<i>Page : ".$next_page."<br>PT IMPORTA JAYA ABADI - Printed by : ".ucwords($userData->nm_lengkap).", ".$today."</i>
		</p>";
	echo "<pagebreak>";
	echo $header;
	$next_page++;
}
?>

<table class="gridtable" width='100%'>
<tr>
	<th width='10%' align='center' rowspan='2'>Diperiksa</th>
	<th width='10%' align='center'>Fisik Uang Tunai / Giro</th>
	<th width='40%' align='center' colspan='2'>Pengembalian Faktur</th>
	<th width='40%' align='center' colspan='2'>Penyerahan Faktur</th>
</tr>
<tr>
	<th width='10%' align='center'>Diterima</th>
	<th width='20%' align='center'>Diterima</th>
	<th width='10%' align='center'>Diserahkan</th>
	<th width='20%' align='center'>Diterima</th>
	<th width='10%' align='center'>Diserahkan</th>
</tr>
<tr>	
	<td align='center' width='10%'><br><br><br><br><br><br><br><b>Sales SPV</b><br></td>
	<td align='center' width='10%'><br><br><br><br><br><br><br><b>Kasir</b><br></td>
	<td align='center' width='20%'><br><br><br><br><br><br><br><b>Adm Penagihan</b><br></td>
	<td align='center' width='20%'><br><br><br><br><br><br><br><b>Salesman</b><br></td>
	<td align='center' width='20%'><br><br><br><br><br><br><br><b>Salesman</b><br></td>
	<td align='center' width='20%'><br><br><br><br><br><br><br><b>Adm Penagihan</b><br></td>
</tr>

<tr>
	<th width='10%' align='center'>Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	<th width='10%' align='center'>Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	<th width='20%' align='center'>Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	<th width='20%' align='center'>Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	<th width='20%' align='center'>Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	<th width='20%' align='center'>Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
</tr>
</table>

<div id='space'></div>
<div id='space'></div>
<p class='footer'>
<?php 
echo "<i>Page : ".$next_page."<br>PT IMPORTA JAYA ABADI - Printed by : ".ucwords($userData->nm_lengkap).", ".$today."</i>";
?>
</p>

<?php
$html = ob_get_contents();
//echo $html;exit;
ob_end_clean();
$mpdf->SetWatermarkText('Importa Jaya Abadi');
$mpdf->showWatermarkText = true;
$mpdf->WriteHTML($html);
//$mpdf->AddPage($html2);
$mpdf->Output("Report_TTNT_".date('Y-m-d').".pdf",'I');
exit;
?>