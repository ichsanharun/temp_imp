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
        .get-footer
        {
            /*width:180mm;*/
            margin-bottom: 15mm;
            padding-bottom:3mm;
            position: relative;
            display: block;
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
        <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;">DELIVERY ORDER (DO)<br><?php echo 'NO. : '.@$do_data->no_do?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
        <tr>
            <td width="15%">No. SO</td>
            <td width="1%">:</td>
            <td colspan="2">
                <?php
                $ar = array();
                foreach(@$detail as $kds=>$vds){
                    $ar=array($vds->no_so);
                }
                $arr = array_unique($ar);
                foreach($arr as $k){
                    echo $k;
                }
                ?>
            </td>
            <td width="15%">Yogyakarta</td>
            <td width="1%">,</td>
            <td><?php echo date('d-M-Y')?></td>
        </tr>
        <tr>
            <td width="10%">SALES</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo strtoupper(@$do_data->nm_salesman)?></td>
            <td width="8%">Kepada Yth,</td>
            <td width="1%"></td>
            <td></td>
        </tr>
        <tr>
            <td width="10%" valign="top">KETERANGAN</td>
            <td width="1%" valign="top">:</td>
            <td colspan="2"></td>
            <td width="10%" colspan="3"><?php echo strtoupper(@$do_data->nm_customer).'<br>'.@$customer->alamat?></td>
        </tr>

        <tr>
            <td colspan="7">
                Dengan Hormat,<br>
                Kami kirimkan barang-barang sebagai berikut ini :
            </td>
        </tr>
    </table>
    <table width="100%" id="tabel-laporan" style="page-break-inside:avoid;margin-bottom:20% !important">
        <tr>
            <th width="2%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="7%">QTY</th>
            <th width="5%">SATUAN<br>(SET/PCS)</th>
            <th width="2%">NO</th>
            <th width="20%">COLLY PRODUK</th>
            <th width="7%">QTY<br>(COLLY)</th>
            <th width="7%">TOTAL QTY<br>(COLLY)</th>
            <th width="10%">KETERANGAN</th>
        </tr>
        <?php
        $n=1;
        $total = 0;

        foreach(@$detail as $kd=>$vd){
            $no=$n++;
            $colly = $this->Salesorder_model->get_data(array('id_barang' => $vd->id_barang),'barang_koli');
            $rs = count($colly)+1;
        ?>
        <tr>
            <td rowspan="<?php echo $rs?>"><center><?php echo $no?></center></td>
            <td rowspan="<?php echo $rs?>"><?php echo $vd->nm_barang?></td>
            <td rowspan="<?php echo $rs?>"><center><?php echo $vd->qty_supply?></center></td>
            <td rowspan="<?php echo $rs?>"><center><?php echo $vd->satuan?></center></td>
            <td colspan="4" style="border: none;height: 0px;padding-top: 0;"></td>
            <td rowspan="<?php echo $rs?>"></td>
        </tr>
        <?php
            $nc=1;
            foreach($colly as $kc=>$vc) {
                $ncl = $nc++;
                $q = $vd->qty_supply;
        ?>
        <tr>
            <td><center><?php echo $no.'.'.$ncl?></center></td>
            <td style="padding-left: 10px;"><?php echo $vc->nm_koli?></td>
            <td><center><?php echo $vc->qty?></center></td>
            <td><center><?php echo $vc->qty*$q?></center></td>
        </tr>
        <?php if ($n == 7) {
          //$mpdf->AddPage('L');
        }else {
          // code...
        } ?>
        <?php } ?>
        <?php } ?>
    </table>
    </div>
    <?php $tglprint = date("d-m-Y H:i:s");?>
<div class="get-footer">
  <htmlpagefooter name="footer">
    <hr>
    <table width="100%" border="0" style="font-size:16pt">
        <tr>
            <td colspan="4">
                Keterangan :<br>
                Mohon setelah barang diterima surat jalan & lembar copy an wajib dicap toko, tanda tangan & cantumkan nama penerima barang
            </td>
        </tr>
        <tr>
            <td width="30%"><center>Dibuat Oleh,</center></td>
            <td width="40%"><center>Diperiksa 1 Oleh,</center></td>
            <td width="30%"><center>Diperiksa 2 Oleh,</center></td>
            <td width="30%"><center>Diterima Oleh,</center></td>
        </tr>
        <tr>
            <td width="15%" colspan="4" style="height: 50px;"></td>
        </tr>
        <tr>
            <td width="15%"><center>( Adm. Sales & Stok )</center></td>
            <td width="15%"><center>( KA.Gudang )</center></td>
            <td width="15%"><center>( Sopir )</center></td>
            <td width="15%"><center>( TTD & CAP TOKO )</center></td>
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
</div>
</body>
</html>
