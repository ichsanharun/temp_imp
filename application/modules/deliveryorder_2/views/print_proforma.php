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
    <table width="100%" id="tabel-laporan" style="font-size:8.5pt !important; padding: 0 !important;font-weight:bold">
       <tr>
            <th width="5%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="5%">QTY</th>
            <th width="7">SATUAN</th>
            <th width="10%">HARGA NORMAL</th>
            <th width="5%">DISKON</th>
            <th width="10%">HARGA SETELAH DISKON</th>
            <th width="10%">TOTAL</th>
            <!--<th width="20%">KETERANGAN</th>-->
        </tr>
        <?php
        $n=1;
        foreach(@$detail as $k=>$v){
            $headerso = $this->Deliveryorder_model->cek_data(array('no_so'=>$v->no_so),'trans_so_header');
            $detailso = $this->Deliveryorder_model->cek_data(array('no_so'=>$v->no_so,'id_barang'=>$v->id_barang),'trans_so_detail');
            $no=$n++;
            $hrg_view = $v->harga_after_diskon_stdr;
            $sub_total_view = $v->subtot_after_diskon;

            $harga_normal       = $detailso->harga_normal;

            if ($headerso->ppn > 0) {
              //$harga  = $detailso->harga_normal/110*100;
              $ppn    = $harga_normal - $harga;
              $ppn_all = $ppn*$qty_supply;
            }else {
              $harga = $harga_normal;
            }
            $harga              = $harga_normal;
            $diskon_std_persen  = $detailso->diskon_persen;
            $diskon_std_rp      = $diskon_std_persen/100*$harga_normal;
            $harga_setelah_diskon_std = $harga_normal - $diskon_std_rp;

            $diskon_promo_persen= $detailso->diskon_promo_persen;
            $diskon_promo_rp    = $detailso->diskon_promo_persen/100*$harga_setelah_diskon_std;
            $harga_setelah_diskon_promo = $harga_setelah_diskon_std - $diskon_promo_rp;

            $diskon_so = $detailso->diskon_so;
            $tipe_diskon_so = $detailso->tipe_diskon_so;
            if ($tipe_diskon_so == "rupiah_tambah") {
              $harga_setelah_diskon_so = $harga_setelah_diskon_promo + $diskon_so;
              $tampil_diskon_so = "+Rp ".number_format($diskon_so);
            }elseif ($tipe_diskon_so == "rupiah_kurang") {
              $harga_setelah_diskon_so = $harga_setelah_diskon_promo - $diskon_so;
              $tampil_diskon_so = "-Rp ".number_format($diskon_so);
            }else {
              $harga_setelah_diskon_so = $harga_setelah_diskon_promo*(100-$diskon_so)/100;
              $tampil_diskon_so = $diskon_so." %";
            }
            //-------------------------END OF HARGA------------------------//
            $diskon_toko        = $headerso->persen_diskon_toko;
            $diskon_toko_rp     = $diskon_toko/100*$harga_setelah_diskon_so;
            $diskon_toko_rp_all = $diskon_toko_rp*$qty_supply;
            $harga_setelah_diskon_toko = $harga_setelah_diskon_so - $diskon_toko_rp;

            $diskon_cash        = $headerso->persen_diskon_cash;
            $diskon_cash_rp     = $diskon_cash/100*$harga_setelah_diskon_toko;
            $diskon_cash_rp_all = $diskon_cash_rp*$qty_supply;
            $harga_setelah_diskon_cash = $harga_setelah_diskon_toko - $diskon_cash_rp;

            $hargajualbefdis += $harga*$qty_supply;
            $hargajualafterdistoko += $harga_setelah_diskon_toko*$qty_supply;
            $dpp_sebelum += $harga_setelah_diskon_so*$qty_supply;

            $dpp_barang			= $qty_supply * $harga_setelah_diskon_cash;
            $diskon_barang		= $diskon_so;
            //$diskon_barang		= $qty_supply * $discount_satuan;
            $harga_bersih		= $dpp_barang - $diskon_barang;
            //$grand 				+= $harga_bersih;
            $grand_diskon_toko +=$diskon_toko_rp_all;
            $grand_diskon_cash +=$diskon_cash_rp_all;
            $grand_ppn += $ppn_all;
            $grand_setelah_toko += $harga_setelah_diskon_toko*$qty_supply;
            $grand 				+= $dpp_barang;
            $grand = ceil($grand);

        ?>
        <tr class="isi" style="">
            <td style="" width="1%"><center><?php echo $no?></center></td>
            <td style=""><?php echo $v->id_barang.' / '.$v->nm_barang?></td>
            <td style="" width="1%"><center><?php echo $v->qty_supply?></center></td>
            <td style="" width="1%"><center><?php echo $v->satuan?></center></td>
            <td style="text-align: right;"><?php echo formatnomor($harga_normal)?></td>
            <td style="text-align: center;">
              <?php echo $diskon_std_persen.'+'.$diskon_promo_persen.'+('.$tampil_diskon_so.')' ?>
            </td>

            <td style="text-align: center;"><center><?php echo number_format($harga_setelah_diskon_so)?></center></td>
            <td style="text-align: center;"><center><?php echo number_format($harga_setelah_diskon_so*$v->qty_supply)?></center></td>

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
