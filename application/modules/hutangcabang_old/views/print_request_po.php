<style>
            table.dataGrid
            {
               border-collapse:collapse;
               border:1px solid black;
               width:100%;
               font-size: 10px;
            }
            table.dataGrid td
            {
               border:1px solid black;
               padding:5px 5px 5px 5px;
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
<table width="100%">
    <tr>
        <td style="width: 60%" style="text-align: center">
            <img width="330px" height="50px" src="<?= base_url(); ?>/logo/Importa Logo.jpg" />
        </td>
        <td style="width: 40%">
            <p style="font-size: 9px">
                Head Office : <br />
                <b>PT. IMPORTA JAYA ABADI SURABAYA</b><br />
                Pergudangan Tanrise South Gate Blok A no : 33 
                Jl. Nangka Sruni-Sidoarjo 
                <br />
                Telp (031) 8915079
            </p>
        </td>
    </tr>
</table>
<hr />

<?php
$query = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$po_data->id_supplier'");
$row = $query->row();
?>
<table width="100%">
    <tr>
        <td style="width: 35%; text-align: left">
            <br />
            <p style="font-size: 9px">
                <b>PURCHASE FROM</b> <br />
                <b>VENDOR NAME :</b> <br />
                <b><?= @$row->nm_supplier ?></b><br />
                <?= @$row->alamat ?> <br />
                TEL : <?= @$row->telpon ?> &nbsp;&nbsp; FAX : <?= @$row->fax ?>
            </p>
        </td>
        <td style="width: 30%; vertical-align: top">
            <center><b>PURCHASE ORDER</b></center>
        </td>
        <td style="width: 35%">
            <br />
            <p style="font-size: 9px">
                P.O#    &nbsp;&nbsp;&nbsp;&nbsp;: <?= @$po_data->no_po?><br />
                DATE    &nbsp;&nbsp;&nbsp;: <?php echo date('d-M-Y',strtotime(@$po_data->tgl_po))?><br />
                REF TO  :  <?= @$po_data->no_pi?><br />
                <br />
                DELIVER TO <br />
                SHIP TO :  <?= @$po_data->ref_to?>
                IMPORTA JAYA ABADI, PERGUDANGAN TANRISE SQOUTH GATE BLOK A-33 JL. NANGKA KELURAHAN SRUNI KECAMATAN
                GEDANGAN KABUPATEN SIDOARJO, JAWA TIMUR <br />
                NO TELP / FAX  : +621-7329837<br />
                NPWP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                74.922.074.5.643.000
            </p>
            
        </td>
    </tr>
</table>
<br />
<p style="font-size: 9px">
    With reference to the above, we are pleased to placed an order on you for supply of the following items :
</p>
<table class="dataGrid">
    <tr>
        <th>
            S/N
        </th>
        <th>
            ITEM NO.
        </th>
        <th>
            DESCRIPTION
        </th>
        <th>
            COLOR
        </th>
        <th>
            QTY
        </th>
        <th>
            UNIT PRICE (RMB)
        </th>
        <th>
            AMOUNT (RMB)
        </th>
        <th>
            CBM EACH
        </th>
        <th>
            CBM TOTAL
        </th>
        <th>
            G.W (KGS)
        </th>
        <th>
            TOTAL G.W (KGS)
        </th>
        <th>
            PICTURE
        </th>
    </tr>
    <?php
    $no=0;
    $qty=0;
    $sub_amout=0;
    $cbm_tot=0;
    $gross_tot=0;
    foreach(@$barang as $ks=>$vs){
        $no ++;
        $qty +=$vs->qty_acc;
        $sub_amout +=$vs->qty_acc*$vs->harga_satuan;
        $cbm_tot += $vs->cbm_each*$vs->qty_acc;
        $gross_tot += $vs->gross_weight*$vs->qty_acc;
        ?>
        <tr>
            <td style="vertical-align: top;"><center><?php echo $no.'.'?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->id_barang ?></center></td>
            <td style="vertical-align: top;"><?php echo $vs->nm_barang?></td>
            <td style="vertical-align: top;"><center><?php echo $vs->varian?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->qty_acc?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->harga_satuan?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->qty_acc*$vs->harga_satuan?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->cbm_each?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->cbm_each*$vs->qty_acc?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->gross_weight?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->gross_weight*$vs->qty_acc?></center></td>
            <td></td>
        </tr>
        
        <?php
    }
    ?>
    <tr>
        <td colspan="4" style="text-align: center"><b>GOODS AMOUNT IN RMB</b></td>
        <td style="text-align: center">
            <?= $qty ?>
        </td>
        <td style="text-align: center">/</td>
        <td style="text-align: center">
            <?= $sub_amout ?>
        </td>
        <td style="text-align: center">/</td>
        <td style="text-align: center">
            <?= $cbm_tot ?>
        </td>
        <td style="text-align: center">/</td>
        <td style="text-align: center">
            <?= $gross_tot ?>
        </td>
        <td style="text-align: center">/</td>
    </tr>
</table>

<table style="font-size: 9px" width="100%">
    <tr>
        <td style="width: 65%">
            TERMS & CONDITION <br />
            1. Freight condition : <?= @$po_data->pilihan?><br />
            2. Payment term : <?= @$po_data->term?><br />
            3. Lead time of delivery : <?= @$po_data->lead?>
        </td>
        <td></td>
    </tr>
    <?php
    $query_cbm = $this->db->query("SELECT * FROM `supplier_cbm` as a, cbm as b WHERE a.id_supplier='$po_data->id_supplier' AND a.id_cbm='$po_data->id_cbm' AND a.id_cbm=b.id_cbm");
    $row_cbm = $query_cbm->row();
    ?>
    <tr>
        <td style="width: 65%">
            Remarks : <br />
            1. Send the Sales contract after receiving Purchase Order. <br />
            2. Shipper should grant 14 days free time of detention at destination. <br />
            3. Demurage and storage cost caused by late receiving original documents will be backcharged to the shipper. <br />
            4. Max capacity is <?= $row_cbm->cbm ?> CBM per shipping <?= $row_cbm->name_cbm ?>. <br />
            5. Max weight is  <?= $row_cbm->kgs ?> Tons per shipping <?= $row_cbm->name_cbm ?>. <br>
            6. The supplied materials must be guaranteed against bad workmanship detective materials, incorrectness of the parts, improper packing and unsatisfactory service. 
        </td>
        <td></td>
    </tr>
</table>
<br />
<table style="font-size: 10px" width="65%">
    <tr>
        <td width="70%">
            <b>
                Seller <br />
                <br />
                <br />
                <br />
                <b style="text-decoration: overline">
                <?= @$row->nm_supplier ?>
                </b>
            </b>
        </td>
        <td width="30%">
            <b>
                Buyer <br />
                <br />
                <br />
                <br />
                <b style="text-decoration: overline">
                    PT. IMPORTA JAYA ABADI
                </b>
                
            </b>
        </td>
    </tr>
</table>
