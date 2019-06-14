<?php
date_default_timezone_set('Asia/Jakarta');
set_time_limit(0);
$sroot 			= $_SERVER['DOCUMENT_ROOT'];
$data_url		= base_url();
$Split_Beda		= explode('/',$data_url);
$Jum_Beda		= count($Split_Beda);
$Nama_APP		= $Split_Beda[$Jum_Beda - 2];
//echo"<pre>";print_r($Split_Beda);exit;
include $sroot."/application/libraries/MPDF57/mpdf.php";
//echo $sroot."/importa/application/libraries/MPDF57/mpdf.php";exit;
$mpdf=new mPDF('utf-8', 'A4');				// Create new mPDF Document

//Beginning Buffer to save PHP variables and HTML tags
ob_start();

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
    left: 225px;
	}

h3 {
	font-family: calibri,arial,sans-serif;
	position: fixed;
	top: 40px;
	left: 250px;
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

$Header		="
<div>
<img src='".$sroot."/assets/img/logo.JPG' style='float:left' width='172' height='60'/>
</div>
<div id='space'></div>

<p class='pt'>PT IMPORTA JAYA ABADI</p>
<p class='alamat'>YOGYAKARTA</p>


<h2 align='center'>KARTU PIUTANG</h2>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>";
echo $Header;
$i			= 0;
$rows		= 0;
if($rows_header){
	$Cust_Pilih		= '';
	$intI			= 0;
	$intS			= 0;
	foreach($rows_header as $keyI=>$valI){
		$intI++;
		$Customer	= $valI->id_customer;
		$Tot_Debet	= $Tot_Kredit	= 0;
		if($Cust_Pilih != $Customer){
			$intS++;
			$det_Customer	= $this->Piutang_card_model->get_data_customer($Customer);
			if($intS > 1){					
				echo"<div id='space'></div>
					<div id='hrnew'></div>
					<div id='space'></div>";
				echo "<pagebreak>";				
			}
			echo"<div id='space'></div>
				<div id='hrnew'></div>
				<div id='space'></div>";
			echo"<table class='noborder' width='100%'>
					<tr>
						<th align='center' colspan='6' style='font-size:14px;color:red;font-weigh:bold italic;'>".$det_Customer[0]->id_customer." / ".$det_Customer[0]->nm_customer."</th>
					</tr>
					<tr>
						<td align='left' width='15%'>Alamat</td>
						<td align='left' width='2%'>:</td>
						<td align='left' width='33%'>".$det_Customer[0]->alamat."</td>
						<td align='left' width='15%'>Telepon</td>
						<td align='left' width='2%'>:</td>
						<td align='left' width='33%'>".$det_Customer[0]->telpon."</td>
					</tr>					
				</table>
				<div id='space'></div>";
			$Cust_Pilih		= $Customer;
		}
		$Tot_Debet	+= $valI->hargajualtotal;
		echo"<table class='gridtable' width='100%'>
				
				<tr>
					<th width='12%' align='center'>Tanggal</th>
					<th width='8%' align='center'>Tipe</th>
					<th width='20%' align='center'>No Reff</th>
					<th width='15%' align='center'>Jatuh Tempo</th>
					<th width='15%' align='center'>Debet</th>
					<th width='15%' align='center'>Kredit</th>
					<th width='15%' align='center'>Saldo</th>
				</tr>";		
			echo"<tr>";
				echo"<td align='center' width='12%'>".date('d/m/Y',strtotime($valI->tanggal_invoice))."</td>";
				echo"<td align='center' width='8%'>INV</td>";
				echo"<td align='center' width='20%'>".$valI->no_invoice."</td>";
				echo"<td align='center' width='15%'>".date('d/m/Y',strtotime($valI->tgljatuhtempo))."</td>";
				echo"<td align='right' width='15%'>".number_format($valI->hargajualtotal)."</td>";
				echo"<td align='right' width='15%'>0</td>";
				echo"<td align='right' width='15%'>0</td>";
			echo"</tr>";
			$Query_Jurnal	= "SELECT * FROM jurnal WHERE no_reff='".$valI->no_invoice."' AND no_perkiraan LIKE '1104-%' AND (debet > 0 OR kredit > 0) AND NOT (nomor LIKE '%JS%' OR nomor LIKE '%JP%' OR nomor LIKE '%JC%') ORDER BY tanggal ASC";
			$Proses_Jurnal	= $this->db->query($Query_Jurnal);
			$num_Jurnal		= $Proses_Jurnal->num_rows();
			if($num_Jurnal > 0){
				$det_Jurnal	= $Proses_Jurnal->result();
				foreach($det_Jurnal as $ky=>$Values){
					$Debet		= ($Values->debet > 0)?$Values->debet:0;
					$Kredit		= ($Values->kredit > 0)?$Values->kredit:0;
					$Tot_Debet	+=$Debet;
					$Tot_Kredit	+=$Kredit;
					echo"<tr>";
						echo"<td align='center' width='12%'>".date('d/m/Y',strtotime($Values->tanggal))."</td>";
						echo"<td align='center' width='8%'>".$Values->tipe."</td>";
						echo"<td align='center' width='20%'>".$Values->nomor."</td>";
						echo"<td align='center' width='15%'>-</td>";
						echo"<td align='right' width='15%'>".number_format($Debet)."</td>";
						echo"<td align='right' width='15%'>".number_format($Kredit)."</td>";
						echo"<td align='right' width='15%'>0</td>";
					echo"</tr>";
					
				}
			}
			$Saldo_Akhir	= $Tot_Debet - $Tot_Kredit;
			echo"<tr style='color:red;'>
					<th colspan='4' align='center'><b>Total ".$valI->no_invoice."<b></th>
					<th width='15%' align='right'><b>".number_format($Tot_Debet)."</b></th>
					<th width='15%' align='right'><b>".number_format($Tot_Kredit)."</b></th>
					<th width='15%' align='right'><b>".number_format($Saldo_Akhir)."</b></th>
				</tr>";	
			echo"</table>";
			echo"<div id='space'></div>";
			echo"<div id='space'></div>";
	}
	echo"<div id='space'></div>
	<div id='hrnew'></div>
	<div id='space'></div>";
}
	
	
?>


<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<p class='footer'>
<?php 
echo "<i>PT IMPORTA JAYA ABADI - Printed by : ".ucwords($userData->nm_lengkap).", ".$today."</i>";
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
$mpdf->Output("Kartu_Piutang.pdf",'I');
exit;
?>