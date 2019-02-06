<?php
date_default_timezone_set('Asia/Bangkok');
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
            table.dataGrid
            {
               border-collapse:collapse;
               border:1px solid black;
               width:100%;
               font-size: 11px;
            }
            table.dataGrid td
            {
               border:1px solid black;
               padding:3px 3px 3px 3px;
            }
            
            table.dataGrid td.tot
            {
              border:1px solid black;
              padding:3px 3px 3px 3px;
              background:lightgrey;
            }
            
            table.dataGrid tr.ganjil:hover,
            table.dataGrid tr.genap:hover
            {
              background:lightblue;
            }
            table.dataGrid tr.genap
            {
            background:dimegray;
            }
            table.dataGrid tr.ganjil
            {
            background:lightgrey;
            }
            
            table.dataGrid th
            {
                text-align:center;
             border:1px solid black;
            
             color:black;
            }


</style>
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
            border-top: solid 1px #000;
            border-bottom: solid 1px #000;
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
    <?php
    $query = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$pr_data->id_supplier'");
    $row = $query->row();
    if ($tipe == '1') {
        $sup = get_supplier($pr_data->id_supplier);
    } else {
        $sup = $pr_data->nm_supplier;
    }
    ?>
<div id="wrapper">
    <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;">PURCHASE ORDER (PR)<br><?php echo 'NO. : '.@$pr_data->no_pr; ?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
         <tr>
            <td width="10%">NO.REFF</td>
            <td width="1%">:</td>
            <td colspan="2"></td>
            <td width="15%">Masa Produksi</td>
            <td width="1%">:</td>
            <td><?= $row->produksi_awal.' - '.$row->produksi_akhir.' hari'; ?></td>
        </tr>
        <tr>
            <td width="10%">SUPPLIER</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$pr_data->id_supplier.' / '.@$sup; ?></td>
            <td width="15%">Masa Pengapalan Container</td>
            <td width="1%">:</td>
            <td><?= $row->pengapalan_awal.' - '.$row->pengapalan_akhir.' hari'; ?></td>
        </tr>
        <tr>
            <td width="10%">TGL PR</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo date('d-M-Y', strtotime(@$pr_data->tgl_pr)); ?></td>
            <td width="15%">Masa Pengiriman</td>
            <td width="1%">:</td>
            <td><?= $row->pengiriman_awal.' - '.$row->pengiriman_akhir.' hari'; ?></td>
        </tr>
        <tr>
           <td width="10%">CABANG</td>
           <td width="1%">:</td>
           <td colspan="2"><?php echo @$pr_data->kdcab.' / '.@$pr_data->namacabang; ?></td>
           <td width="15%">Masa Proses Cukai</td>
           <td width="1%">:</td>
           <td><?= $row->cukai_awal.' - '.$row->cukai_akhir.' hari'; ?></td>
       </tr>
    </table>

    <table width="100%" id="tabel-laporan" border="1">
        <!--tr>
            <th width="1%">NO</th>
            <th width="40%">NAMA BARANG</th>
            <th width="20%">COLLY</th>
            <th width="7%">QTY COLLY</th>
            <th width="7%">TOTAL COLLY</th>
            <th width="8%">SATUAN</th>
            <th width="5%">QTY</th>
            <th width="15%">HARGA</th>
            <th width="15%">JUMLAH</th>
            <th width="10%">KET</th>
        </tr-->
        <tr>
            <th width="%">NO</th>
            <th width="%">ITEM NO.</th>
            <th width="%">NAMA BARANG</th>
            <th width="%">COLOR</th>
            <th width="%">STOK</th>
            <th width="%">FORECASTING</th>
            <th width="%">STANDAR STOK</th>
            <th width="%">QTY</th>
            <th width="%">QTY(SET)</th>
            <th width="%">CBM EACH</th>
            <th width="%">CBM TOTAL</th>
            <th width="%">G.W.(KGS)</th>
            <th width="%">G.W. TOTAL(KGS)</th>
        </tr>
        <?php
        $n = 1;
        $total = 0;
        foreach (@$detail as $ks => $vs) {
            $no = $n++;
            $total += $vs->sub_total_pr;
            $colly = $this->Purchaserequest_model->get_data(array('id_barang' => $vs->id_barang), 'barang_koli'); ?>
        <tr>
            <td style="vertical-align: top;"><center><?php echo $no.'.'; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->id_barang; ?></center></td>
            <td style="vertical-align: top;"><?php echo $vs->nm_barang; ?></td>
            <!--td>
                 <?php
                $sn = 1;
            foreach ($colly as $kc => $vc) {
                echo $no.'.'.$sn++.' -'.$vc->nm_koli.'<br>';
            } ?>
            </td>
            <td style="vertical-align: top;">
                <center>
                <?php
                $sn = 1;
            foreach ($colly as $kc => $vc) {
                echo $vc->qty.'<br>';
            } ?>
                </center>
            </td>
            <td style="vertical-align: top;">
                <center>
                <?php
                $tc = 0;
            foreach ($colly as $kc) {
                $tc = count($colly);
                echo $tc.'<br>';
            } ?>
                </center>
            </td-->
            <td style="vertical-align: top;"><center><?php echo $vs->varian; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo '0'; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo '0'; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo '0'; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->qty_pr; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->qty_pr; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->cbm_each; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->cbm_each * $vs->qty_pr; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->gross_weight; ?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->gross_weight * $vs->qty_pr; ?></center></td>
            <!--td style="text-align: right;vertical-align: top;"><?php echo formatnomor($vs->harga_satuan); ?></td>
            <td style="text-align: right;vertical-align: top;"><?php echo formatnomor($vs->sub_total_pr); ?></td-->

        </tr>
        <?php
        } ?>
    </table>
    
    <br />
    <br />
    <br />
    <br />
    <div style="width: 30%; float: right">
        <table width="100%" class="dataGrid">
            <tr>
                <td style="text-align: center">
                    Tanggal Approval
                </td>
                <td style="width: 70%; text-align: center">
                   
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="text-align: center">
                    Approval
                </td>
                <td>
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                </td>
            </tr>
            <tr>
                <td style="text-align: center">Nizar Bawazier</td>
            </tr>
        </table>
    </div>
</div>

<?php $tglprint = date('d-m-Y H:i:s'); ?>
<htmlpagefooter name="footer">
    
    <hr/>
    <div id="footer">
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap).' On '.$tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />
</body>
</html>
