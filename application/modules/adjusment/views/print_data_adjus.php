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

    <table width="100%" id="tabel-laporan" style="font-size:8.5pt !important; padding: 0 !important;font-weight:bold">
      <tr>
        <th>No.</th>
        <th width="15">Kode Produk</th>
        <th width="20%">Nama Set</th>
        <th width="5">Jenis Produk</th>
        <th width="5">Satuan</th>
        <th width="5">Tipe Adj.</th>
        <th width="5">Qty Stock Sebelum</th>
        <th width="5">Qty Avl Sebelum</th>
        <th width="5">Qty</th>
        <th width="5">Qty Stock Sesudah</th>
        <th width="5">Qty Avl Sesudah</th>
        <th width="5">@harga</th>
        <th width="5">Total</th>
      </tr>
        <?php
        $n=1;
        foreach($detail as $k=>$v){
            $no=$n++;

        ?>
        <tr class="isi" style="">
            <td width="1%"><center><?php echo $no?></center></td>
            <td><?php echo $v->id_barang?></td>
            <td width="1%"><center><?php echo $v->nm_barang?></center></td>
            <td width="1%"><center><?php echo $v->jenis?></center></td>
            <td><?php echo $v->satuan?></td>
            <td><?php echo $v->tipe_adjusment?></td>
            <td><center><?php echo $v->qty_stock_awal?></center></td>
            <td><center><?php echo $v->qty_avl_awal?></center></td>
            <td><center><?php echo $v->qty?></center></td>
            <td><center><?php echo $v->qty_stock_akhir?></center></td>
            <td><center><?php echo $v->qty_avl_akhir?></center></td>
            <td><?php echo formatnomor($v->nilai_barang)?></td>
            <td><?php echo formatnomor($v->nilai_barang*$v->qty)?></td>
        </tr>

      <?php } ?>
    </table>


</body>
</html>
