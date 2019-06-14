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
if(strtolower($type_jurnal)=='bum'){
	$Keterangan	= $rows_header[0]->terima_dari;
}else{
	$Keterangan	= $rows_header[0]->keterangan;
}
$Kode_Pecah	= explode('-',$rows_header[0]->nomor);

$Header		="
<div>
<img src='".$sroot."/assets/img/logo.JPG' style='float:left' width='172' height='60'/>
</div>
<div id='space'></div>

<p class='pt'>PT IMPORTA JAYA ABADI</p>
<p class='alamat'>YOGYAKARTA</p>

<p class='barcs'>
	<table class='noborder'>
		<tr>		
			<td align='left'><barcode code='".$rows_header[0]->nomor."' type='QR' size='1.0' error='M'/></td>
		</tr>	
	</table>
</p>

<h2 align='center'>JURNAL ".$type_jurnal." ".$rows_header[0]->nomor."</h2>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<div id='space'></div>
<table class='noborder' width='100%'>
	
	<tr>
		<td align='left' width='15%'>Tgl Jurnal</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='33%'>".date('d M Y',strtotime($rows_header[0]->tgl))."</td>
		<td align='left' width='15%'>Total Jurnal</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='33%'>".number_format($rows_header[0]->jml)."</td>
	</tr>	
	<tr>
		<td align='left' width='15%'>Keterangan</td>
		<td align='left' width='2%'>:</td>
		<td align='left' width='33%'>".$Keterangan."</td>
		<td align='left' width='15%'></td>
		<td align='left' width='2%'></td>
		<td align='left' width='33%'></td>
	</tr>
	
</table>
<div id='space'></div>";
echo $Header;

	
	$i			= 0;
	$rows		= 0;
	$pages		= 0;
	$next_page	= 1;
	$batas		= 29;
	
	echo"<table class='gridtable' width='100%'>
			<tr>
				<th align='left' colspan='5'># Detail</th>
			</tr>
			<tr>
				<th width='12%' align='center'>No Perkiraan</th>
				<th width='40%' align='center'>Nama Perkiraan</th>
				<th width='18%' align='center'>No Reff</th>
				<th width='15%' align='center'>Debet</th>
				<th width='15%' align='center'>Kredit</th>
			</tr>";
			$rows =  0;
			if($rows_detail){
				
				foreach($rows_detail as $key=>$vals){
					$rows++;
					if($rows==$batas){
						echo "</table>";			
						echo"<div id='space'></div>
							<div id='space'></div>
							<p class='footer'>
							<i>Page : ".$next_page."<br>PT IMPORTA JAYA ABADI - Printed by : ".ucwords($userData->nm_lengkap).", ".$today."</i>
							</p>";
						echo "<pagebreak>";
						echo $Header;
						echo"<table class='gridtable' width='100%'>
								<tr>
									<th align='left' colspan='5'># Detail</th>
								</tr>
								<tr>
									<th width='12%' align='center'>No Perkiraan</th>
									<th width='40%' align='center'>Nama Perkiraan</th>
									<th width='18%' align='center'>No Reff</th>
									<th width='15%' align='center'>Debet</th>
									<th width='15%' align='center'>Kredit</th>
								</tr>";
						$next_page++;				
						$rows	=0;
					}
					$no_Coa		= $vals->no_perkiraan;
					$Pecah_Coa	= explode('-',$no_Coa);
					$No_Reff	= $vals->no_reff;
					if($Pecah_Coa[0] !='1104'){
						$No_Reff	='-';
					}
					$qry_COA	= "SELECT nama FROM COA WHERE kdcab='".$Kode_Pecah[0]."-A' AND no_perkiraan='$no_Coa' LIMIT 1";
					$det_COA	= $this->db->query($qry_COA)->result();
					$Nama_COA	= $det_COA[0]->nama;
					echo"<tr>";
						echo"<td align='center'>".$no_Coa."</td>";
						echo"<td align='left'>".$Nama_COA."</td>";
						echo"<td align='center'>".$No_Reff."</td>";
						echo"<td align='right'>".number_format($vals->debet)."</td>";
						echo"<td align='right'>".number_format($vals->kredit)."</td>";
					echo"</tr>";
				}
			}
	
echo"</table>";

?>


<div id='space'></div>
<div id='space'></div>
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
$mpdf->Output($rows_header[0]->nomor.".pdf",'I');
exit;
?>