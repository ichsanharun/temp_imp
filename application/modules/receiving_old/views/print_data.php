<?php
date_default_timezone_set('Asia/Bangkok');
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
         <style>
            table.dataGridz
            {
               border-collapse:collapse;
               border:1px solid black;
               width:100%;
               font-size: 11px;
            }
            table.dataGridz td
            {
               border:1px solid black;
               padding:3px 3px 3px 3px;
            }
            
            table.dataGridz td.tot
            {
              border:1px solid black;
              padding:3px 3px 3px 3px;
              background:lightgrey;
            }
            
            table.dataGridz tr.ganjil:hover,
            table.dataGridz tr.genap:hover
            {
              background:lightblue;
            }
            table.dataGridz tr.genap
            {
            background:dimegray;
            }
            table.dataGridz tr.ganjil
            {
            background:lightgrey;
            }
            
            table.dataGridz th
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
    <div id="wrapper">
        <h2 align="center">REPORT UNLOADING CONTAINER</h2>
        <table width="100%" border="0" id="header-tabel">
        <tr>
            <th colspan="3" width="20%" style="text-align: left;">PT IMPORTA JAYA ABADI<br>YOGYAKARTA</th>
            <th style="border-right: none;">RECEIVING PO<br><?php echo 'NO. : '.@$header->no_receiving; ?></th>
            <th colspan="3" style="border-left: none;"></th>
        </tr>
        <tr>
            <td width="10%">CONTAINER NO</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$header->container_no; ?></td>
            <td width="15%">DATE OF UNLOADING</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y', strtotime(@$header->date_unloading)); ?></td>
        </tr>
         <tr>
            <td width="10%">SUPPLIER</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo @$header->id_supplier.' / '.@$header->nm_supplier; ?></td>
            <td width="15%">NO. SJ SUPPLIER</td>
            <td width="1%">:</td>
            <td><?php echo @$header->no_sjsupplier; ?></td>
        </tr>
        <tr>
            <td width="10%">TGL REC.</td>
            <td width="1%">:</td>
            <td colspan="2"><?php echo date('d-M-Y', strtotime(@$header->tglreceive)); ?></td>
            <td width="15%">TGL SJ SUPPLIER</td>
            <td width="1%">:</td>
            <td><?php echo date('d-M-Y', strtotime(@$header->tgl_sjsupplier)); ?></td>
        </tr>
    </table>
    <table width="100%" class="dataGridz">
       <tr>
            <th width="1%">NO</th>
            <th width="30%">NAMA PRODUK</th>
            <th width="20%">COLLY</th>
            <th width="7%">BAGUS</th>
            <th width="7%">RUSAK</th>
            <th width="10%">KETERANGAN</th>
        </tr>
        <?php
        $no = 0;
        $query = $this->db->query("SELECT * FROM `receive_detail_barang` where no_po='$no_po'");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                ++$no; ?>
               <tr>
                   <td><?= $no; ?></td>
                   <td>
                       <?= $row->nama_barang; ?>
                   </td>
                   <td></td>
                   <td>
                       <?= $row->bagus; ?>
                   </td>
                   <td>
                       <?= $row->rusak; ?>
                   </td>
                   <td></td>
               </tr>
               <?php
                $queryql = $this->db->query("SELECT * FROM `receive_detail_koli` WHERE no_po='$no_po' and id_barang='$row->id_barang'");
                foreach ($queryql->result() as $rowql) {
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>
                            <?= $rowql->nama_koli; ?>
                        </td>
                        <td>
                            <?= $rowql->bagus; ?>
                        </td>
                        <td>
                            <?= $rowql->rusak; ?>
                        </td>
                        <td>
                            <?= $rowql->keterangan; ?>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>
    </table>
    </div> 
    <?php $tglprint = date('d-m-Y H:i:s'); ?>     
<htmlpagefooter name="footer">
<style>
    table.dataGrid {
      border-collapse: collapse;
    }
    
    table.dataGrid td {
      border: 1px solid black;
      text-align: center;
    }
    
    
</style>
    <table class="dataGrid" align="center" width="80%" >
        <tr>
            <td  colspan="2" style="text-align: center;">Check by</td>
            <td rowspan="3" style="border-top: 1px solid #fefefe; border-bottom: 1px solid #fefefe;"></td>
            <td width="20%">Verification By,</td>
        </tr>  
        <tr>
            <td width="25%">
                <br />
                <br />
                <br />
                <?= @$header->administrator; ?>
                <br />
                Administrator of Warehouse
            </td>
            <td width="25%">
                <br />
                <br />
                <br />
                <?= @$header->head; ?>
                <br />
                Head of Warehouse
            </td>
            
            <td>
                <br />
                <br />
                <br />
                <?= @$header->branch; ?>
                <br />
                Branch Manager
            </td>
        </tr>
        <tr>
            <td>
                Date : <?php echo date('d-M-Y', strtotime(@$header->date_check)); ?>
            </td>
            <td>
                Date : <?php echo date('d-M-Y', strtotime(@$header->date_check)); ?>
            </td>
            <td>
                Date :
            </td>
        </tr>
    </table>
    <hr />
    <div id="footer"> 
    <table>
        <tr><td>PT IMPORTA JAYA ABADI - Printed By <?php echo ucwords($userData->nm_lengkap).' On '.$tglprint; ?></td></tr>
    </table>
    </div>
</htmlpagefooter>
<sethtmlpagefooter name="footer" value="on" />  
</body>
</html> 