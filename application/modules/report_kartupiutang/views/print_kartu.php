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
            border-spacing: 4px;
        }

        #tabel-laporan th{


            margin: 0px;
        }

        #tabel-laporan tr{


            margin: 0px;
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



            background:#eee;



        }


        img.resize {
          max-width:12%;
          max-height:12%;
        }

        th, td {
            padding: 5px;
            text-align: left;
        }

        tr.border_bottom td {
          border-bottom:1pt ridge black;
        }

        #summary table {
            border-collapse: collapse;
        }

        #summary th, td {

        }
    </style>
</head>
<body>
<div id="wrapper">
    <!--<p style="text-align:center; font-weight:bold; padding-top:5mm;">REKAP DATA STOK</p>-->
    <table width="100%" id="tabel-laporan">
    <tr>
      <th width="2%">#</th>
      <th>Tanggal</th>
      <th>TP</th>
      <th width="25%">NO. Reff</th>
      <th>Jatuh Tempo</th>
      <th>Mata Uang</th>
      <th>Debet</th>
      <th>Kredit</th>
      <th>Saldo Akhir</th>
	  </tr>
        <?php
			$numb=0;
      foreach($data_inv AS $k => $record){ $numb++;
        $n=0;
        $sub_jual = $record->hargajualtotal;
        $sub_piutang = 0;?>
		<tr>
	    <td><?= $numb ?></td>
			<td><?= $record->tanggal_invoice ?></td>
			<td><?= "INV" ?></td>
      <td><?= $record->no_invoice ?></td>
      <td><?= $record->tgljatuhtempo ?></td>
      <td><?= "IDR" ?></td>
      <td><?= formatnomor($record->hargajualtotal) ?></td>
      <td><?= '' ?></td>
      <td><?= ''; ?></td>
	  </tr>
    <?php $cek_pem = $this->db->get_where('pembayaran_piutang', array('no_invoice'=>$record->no_invoice))->result();
    foreach ($cek_pem as $key => $value) {
      $n++;
      $sub_piutang += $value->jumlah_pembayaran;
    ?>
    <tr>
	    <td><?= $numb.".".$n ?></td>
			<td><?= $value->tgl_pembayaran ?></td>
			<td><?= "CR" ?></td>
      <td><?= $value->kd_pembayaran ?></td>
      <td><?= '' ?></td>
      <td><?= "IDR" ?></td>
      <td><?= '' ?></td>
      <td><?= formatnomor($value->jumlah_pembayaran) ?></td>
      <td><?= ''; ?></td>
	  </tr>
    <?php
    }
    ?>
    <tr>
      <td><strong><?= '' ?></strong></td>
      <td><strong><?= '' ?></strong></td>
      <td><strong><?= "" ?></strong></td>
      <td><strong><?= "SALDO ".$record->no_invoice ?></strong></td>
      <td><strong><?= '' ?></strong></td>
      <td><strong><?= "IDR" ?></strong></td>
      <td style="border-top:solid 1px #000"><strong><?= formatnomor($sub_jual) ?></strong></td>
      <td style="border-top:solid 1px #000"><strong><?= formatnomor($sub_piutang) ?></strong></td>
      <td style="border-top:solid 1px #000"><strong><?= formatnomor($sub_jual-$sub_piutang) ?></strong></td>
    </tr>
    <?php
      }
    ?>
    </table>
</div>

</body>
</html>
