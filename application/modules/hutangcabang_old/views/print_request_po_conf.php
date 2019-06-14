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

        
        
        table.xxx
            {
               border-collapse:collapse;
               border:1px solid black;
               width:100%;
            }
            table.xxx td
            {
               border:1px solid black;
               padding:5px 5px 5px 5px;
            }
            
            .indexxx {
                border: .1px solid red;
                border-bottom-style: hidden;
                border-top-style: hidden;
            }
            
            table.cont
            {
               border-collapse:collapse;
               border:3px solid black;
               width:100%;
            }
            table.cont td
            {
               border:3px solid black;
               padding:5px 5px 5px 5px;
            }
</style>
<?php
$query = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$po_data->id_supplier'");
$row = $query->row();
?>
<table width="100%">
    <tr >
        <td  style="width: 60%; text-align: center; " rowspan="1">
            <h2>PEMBELIAN</h2>
        </td>
        <td rowspan="2" width="40%">
            <table class="xxx" width="100%">
                <tr>
                    <td style="width: 30%; text-align: center">
                        No PO
                    </td>
                    <td style="width: 70%">
                        <?= $po_data->no_po ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;&nbsp; Tanggal PO &nbsp;&nbsp;
                    </td>
                    <td>
                        <?php echo date('d-M-Y',strtotime(@$po_data->tgl_po))?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="text-align: center">
            <?= $po_data->nm_supplier ?>
        </td>
    </tr>
</table>
<?php
    $query_cbm = $this->db->query("SELECT * FROM `supplier_cbm` WHERE id_supplier='$po_data->id_supplier' AND id_cbm='$po_data->id_cbm'");
    $row_cbm = $query_cbm->row();
    ?>
<table width="100%" style="font-size: 9px">
    <tr>
        <td colspan="3">
            <b>Notes :</b>
        </td>
    </tr>
    <tr>
        <td width="1%" style="text-align: center">
            1
        </td>
        <td colspan="2">
            Maximun CBM adalah <?= $row_cbm->cbm ?> CBM/ per container. 
        </td>
    </tr>
    <tr>
        <td style="text-align: center">
            2
        </td>
        <td colspan="2">
            Maximun Tonnase adalah <?= $row_cbm->kgs ?> Tons/ per container. 
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <b>Lead Time :</b>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td width="15%">
            Masa Produksi
        </td>
        <td>
            <?= $row->produksi_awal." - ".$row->produksi_akhir." hari" ?>
        </td>
    </tr>
    <tr>
        <td>2</td>
        <td>
            Masa Pengapalan Container
        </td>
        <td>
            <?= $row->pengapalan_awal." - ".$row->pengapalan_akhir." hari" ?>
        </td>
    </tr>
    <tr>
        <td>3</td>
        <td>
            Masa Pengiriman
        </td>
        <td>
            <?= $row->pengiriman_awal." - ".$row->pengiriman_akhir." hari" ?>
        </td>
    </tr>
    <tr>
        <td>4</td>
        <td>
            Masa Proses Cukai
        </td>
        <td>
            <?= $row->cukai_awal." - ".$row->cukai_akhir." hari" ?>
        </td>
    </tr>
    
</table>

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
        $qty +=$vs->qty_po;
        $cbm_tot += $vs->cbm_each*$vs->qty_po;
        $gross_tot += $vs->gross_weight*$vs->qty_po;
        ?>
        <tr>
            <td style="vertical-align: top;"><center><?php echo $no.'.'?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->id_barang ?></center></td>
            <td style="vertical-align: top;"><?php echo $vs->nm_barang?></td>
            <td style="vertical-align: top;"><center><?php echo $vs->varian?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->qty_po?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->cbm_each?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->cbm_each*$vs->qty_po?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->gross_weight?></center></td>
            <td style="vertical-align: top;"><center><?php echo $vs->gross_weight*$vs->qty_po?></center></td>
            <td></td>
        </tr>
        
        <?php
    }
    ?>
    <tr>
        <td colspan="4" style="text-align: center"><b>TOTAL</b></td>
        <td style="text-align: center">
            <?= $qty ?>
        </td>
        <td style="text-align: center"></td>
        <td style="text-align: center">
            <?= $cbm_tot ?>
        </td>
        <td style="text-align: center"></td>
        <td style="text-align: center">
            <?= $gross_tot ?>
        </td>
        <td style="text-align: center"></td>
    </tr>
</table>
<br />
<table class="cont" width="50%">
    <tr>
        <td>
            <h4>Total Jumlah Container</h4>
        </td>
        <td>
            <h4>
                <?=  $cbm_tot/$row_cbm->cbm ?>
            </h4>
        </td>
    </tr>
</table>
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