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
            font-family:Arial;
            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-style: kitfont;
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
            width: 210mm;
            height: 145mm;
            page-break-after:always;
        }

        #header-tabel tr {
            padding: 0px;
        }
        #tabel-laporan {
            border-spacing: -1px;
            padding: 0px !important;
        }

        #tabel-laporan th{
            /*
            border-top: solid 1px #000;
            border-bottom: solid 1px #000;
            */
           border : solid 1px #000;
            margin: 0px;
            height: auto;
        }

        #tabel-laporan td{
            border : solid 1px #000;
            margin: 0px;
            height: auto;
        }
        #tabel-laporan {
          border-bottom:1px solid #000 !important;
        }

        .isi td{
          border-top:0px !important;
          border-bottom:0px !important;
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

    <?php $diskon = explode("|", $set_custom);
    ?>
    <table width="100%" id="tabel-laporan" style="font-size:10.5pt !important; padding: 0 !important;font-weight:bold">
       <tr>
            <th width="5%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="30%">SURAT JALAN</th>
            <th width="5%">QTY</th>
            <th width="10%">SATUAN<br>(SET/PCS)</th>
            <th width="10%">HARGA</th>
            <th width="1%">DISKON(%)</th>
            <th>HARGA NETT</th>
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
                if (in_array("diskon_standart", $diskon)) {
                  if (in_array("diskon_agen", $diskon)) {
                    if (in_array("diskon_cash", $diskon)) {
                      $hrg_view = $v->hargajual;
                      $sub_total_view = $v->subtot_after_diskon;

                    }else {
                      $hrg_view = $v->hargajual;
                      $sub_total_view = ceil($v->subtot_after_diskon*((100 - @$header->diskon_toko_persen)/100));

                    }
                  }else {
                    if (in_array("diskon_cash", $diskon)) {
                      $hrg_view = $v->hargajual;
                      $sub_total_view = $v->subtot_after_diskon;

                    }else {
                      $hrg_view = $v->hargajual;
                      $sub_total_view = ceil($v->subtot_after_diskon*((100 - @$header->diskon_cash_persen)/100));

                    }
                  }
                }else {
                  if (in_array("diskon_agen", $diskon)) {
                    if (in_array("diskon_cash", $diskon)) {
                      $hrg_view = $v->harga_after_diskon_stdr;
                      $sub_total_view = $v->subtot_after_diskon;

                    }else {
                      $hrg_view = $v->harga_after_diskon_stdr;
                      $sub_total_view = ceil($v->subtot_after_diskon*((100 - @$header->diskon_cash_persen)/100));

                    }
                  }else {
                    if (in_array("diskon_cash", $diskon)) {
                      $hrg_view = $v->harga_after_diskon_stdr;
                      $sub_total_view = $v->subtot_after_diskon*((100 - @$header->diskon_toko_persen)/100);

                    }else {
                      $hrg_view = $v->harga_after_diskon_stdr;
                      $sub_total_view = $v->subtot_after_diskon;

                    }
                  }
                }
            }
        ?>
        <tr class="isi" style="">
            <td style="" width="1%"><center><?php echo $no?></center></td>
            <td style=" width:20%" width="20%"><?php echo $v->nm_barang?></td>
            <td style="" width="2%"><center><?php echo $v->no_do?></center></td>
            <td style="" width="1%"><center><?php echo $v->jumlah?></center></td>
            <td style="" width="1%"><center><?php echo $v->satuan?></center></td>
            <td style="text-align: right;"><?php echo formatnomor($v->hargajual)?></td>
            <?php
              echo '<td style="text-align: center;">'.$v->persen_diskon_stdr.'+'.$v->diskon_promo_persen.'+'.$v->diskon_so.'</td>';
            ?>

            <!--td style="text-align: center;"><center><?php echo $v->diskon_promo_persen?></center></td-->
            <td style="text-align: center;"><center><?php echo formatnomor($v->harga_nett)?></center></td>
            <td style="text-align: right;"><?php echo formatnomor($v->subtot_after_diskon)?></td>
        </tr>

      <?php } ?>
    </table>

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
    <?php
    /*echo
'<htmlpagefooter name="footer">
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
'*/
?>
</body>
</html>
