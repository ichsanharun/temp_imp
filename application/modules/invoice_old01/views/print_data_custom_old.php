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
    </style>
</head>
<body>
    <div id="wrapper">
      <div style="display: inline-block; position:relative;width:100%;display: none;">
        <div style="width:25%">
          <img src="assets/img/logo.JPG">
        </div>
        <div style="position: absolute;text-align: center;margin-left:15% !important" width="75%">INVOICE (FAKTUR)<br><?php echo 'NO. : '.@$header->no_invoice?></div>
      </div>
      	<table width="100%" border="0" id="header-tabel">
	      	<tr>
	      		<th width="30%" style="text-align: left;">
	      			<img src="assets/img/logo.JPG" style="height: 50px;width: auto;">
	      		</th>
	      		<th colspan="4" style="border-right: none;text-align: center;padding:0 !important;margin:0px !important" width="75%">INVOICE (FAKTUR)<br><?php echo 'NO. : '.@$header->no_invoice?></th>
            	<th colspan="3" style="border-left: none;"></th>
	      	</tr>
      	</table>
        <table width="100%" border="0" id="header-tabel">
        <!--tr>
            <th colspan="1">
              <img src="assets/img/logo.JPG" width="25%">
            </th>

            <th colspan="2" style="border-right: none;text-align: center;padding:0 !important;margin:0px !important" width="75%">INVOICE (FAKTUR)<br><?php //echo 'NO. : '.@$header->no_invoice?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr-->
        <tr>
            <td width="5%">NO. SO</td>

            <td colspan="3" width="50%">:</td>
            <td width="15%">Yogyakarta</td>
            <td width="1%">,</td>
            <td><?php echo date('d/m/Y',strtotime(@$header->tanggal_invoice))?></td>
        </tr>
        <tr>
            <td width="5%">SALES</td>

            <td colspan="3">:<?php echo @$header->nm_salesman?></td>
            <td width="15%">Kepada Yth,</td>
            <td width="1%"></td>
            <td></td>
        </tr>
        <tr>
            <td width="5%">TOP</td>

            <td colspan="3">:
                <?php echo '45 HARI  &nbsp;&nbsp;&nbsp; TGL JATUH TEMPO : '.date('d/m/Y',strtotime(@$header->tgljatuhtempo))?>
            </td>
            <td width="15%" colspan="3">
                <?php echo @$header->nm_customer?>
            </td>
        </tr>
        <tr>
            <td width="5%">KETERANGAN</td>

            <td colspan="3">:</td>
            <td width="15%" colspan="3">
                <?php echo @$header->alamatcustomer?>
            </td>
        </tr>
    </table>
    <table width="100%" id="tabel-laporan">
       <tr>
            <th width="5%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="5%">QTY</th>
            <th width="10%">SATUAN<br>(SET/PCS)</th>
            <th width="10%">HARGA</th>
            <!--<th width="10%">DISKON %</th>-->
            <th width="10%">JUMLAH</th>
            <!--<th width="20%">KETERANGAN</th>-->
        </tr>
        <?php
        $n=1;
        foreach(@$detail as $k=>$v){
            $no=$n++;
            $hrg_view = $v->harga_after_diskon_stdr;
            $sub_total_view = $v->subtot_after_diskon;

            if(@$set_custom != ""){
                $hrg_view = $v->hargajual;
                $sub_total_view = $v->subtot_bef_diskon;
            }
        ?>
        <tr>
            <td width="1%"><center><?php echo $no?></center></td>
            <td><?php echo $v->id_barang.' / '.$v->nm_barang?></td>
            <td width="1%"><center><?php echo $v->jumlah?></center></td>
            <td width="1%"><center><?php echo $v->satuan?></center></td>
            <td style="text-align: right;"><?php echo formatnomor($hrg_view)?></td>
            <td style="text-align: right;"><?php echo formatnomor($sub_total_view)?></td>
        </tr>
        <?php } ?>
    </table>
    </div>
    <?php
    $tglprint = date("d-m-Y H:i:s");
    $total_nominal = @$header->hargajualafterdis;
    $diskon_stdr_persen = '-';
    $diskon_stdr_rp = 0;
    $diskon_toko_persen = '-';
    $diskon_toko_rp = 0;
    $diskon_cash_persen = '-';
    $grand_total_view = @$header->hargajualafterdis;
    //$diskon_stdr   = @$header->
    if(@$set_custom != ""){
        $total_nominal = @$header->hargajualbefdis;
        $diskon_stdr_persen = 30;
        $diskon_toko_persen = @$header->diskon_toko_persen;
        $diskon_cash_persen = @$header->diskon_cash_persen;
        $diskon_stdr_rp = @$header->diskon_stdr_rp;
        $diskon_toko_rp = @$header->diskon_toko_rp;
        $grand_total_view = @$header->hargajualbefdis-$diskon_stdr_rp-$diskon_toko_rp;
    }
    ?>
<htmlpagefooter name="footer">
    <hr>
    <table width="100%" border="0">
        <tr>
            <td colspan="3">
                <i><?php echo "TERBILANG : ".ucwords(ynz_terbilang_format($grand_total_view))?></i>
            </td>
            <td width="15%">JUMLAH NOMINAL</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($total_nominal)?></td>
            <!--<td width="10%"></td>-->
        </tr>
        <tr>
            <td colspan="3"></td>
            <td width="15%">DISKON &nbsp;&nbsp;&nbsp;<?php echo ' '.$diskon_stdr_persen.' %'?></td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($diskon_stdr_rp)?></td>
            <!--<td width="10%"></td>-->
        </tr>
		<tr>
            <td colspan="3"></td>
            <td width="15%">DISKON TOKO &nbsp;&nbsp;&nbsp;<?php echo ' '.$diskon_toko_persen.' %'?></td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($diskon_toko_rp)?></td>
            <!--<td width="10%"></td>-->
        </tr>
        <tr>
            <td colspan="3">
                <center>Hormat Kami,</center>
            </td>
            <td width="15%">DISKON CASH &nbsp;&nbsp;&nbsp;<?php echo ' '.$diskon_cash_persen.' %'?></td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php //echo formatnomor($ongkir)?></td>
            <!--<td width="10%"></td>-->
        </tr>
        <tr>
            <td colspan="3"></td>
            <td width="15%">GRAND TOTAL</td>
            <td width="1%">:</td>
            <td width="15%" style="text-align: right;"><?php echo formatnomor($grand_total_view)?></td>
            <!--<td width="10%"></td>-->
        </tr>
        <tr>
            <td colspan="3" style="height: 40px;"></td>
        </tr>
        <tr>
            <td colspan="3">
                <center>(BM/SPV)</center>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                Catatan : Pembayaran dengan cek/giro dianggap lunas apabila sudah dicairkan.
            </td>
        </tr>
    </table>
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
