<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style type="text/css">
        #tabel-laporan {
            border-spacing: -1px;
        }

        #tabel-laporan th{
            margin: 0px;
        }

        #tabel-laporan tr{
            margin: 0px;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <th><center>DATA STOK PRODUK</center></th>
        </tr>
        <tr>
            <th><center>PER TANGGAL : <?php echo date('d F Y')?></center></th>
        </tr>
    </table>
    <?php foreach(@$group_data as $kg=>$vg){ ?>
    <table width="100%" id="tabel-laporan" border="1">
        <!--<tr style="background: #f2f2f2;">
            <th colspan="5"><?php //echo $vg->nm_group?></th>
        </tr>-->
        <?php
        $session = $this->session->userdata('app_session');
        $dataproduk = $this->Reportstok_model->get_stok_group($vg->id_group,$session['kdcab']);
        if($dataproduk){
        ?>
        <tr>
            <th width="2%"><center>NO</center></th>
            <th width="23%">GROUP</th>
            <th width="55%">PRODUCT SEAT</th>
            <th width="10%">STOK REAL</th>
            <th width="10%">STOK AVL</th>
        </tr>
        <?php } ?>
        <?php
        $count = count($dataproduk);
        $n=1;
        if($dataproduk){
        foreach($dataproduk as $kp=>$vp){
            $no=$n++;
        ?>
        <tr>
            <td><center><?php echo $no?></center></td>
            <td><center><?php echo strtoupper($vg->nm_group)?></center></td>
            <td><?php echo $vp->nm_barang?></td>
            <td><center><?php echo $vp->qty_stock?></center></td>
            <td><center><?php echo $vp->qty_avl?></center></td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>
    <?php if($dataproduk){ ?>
    <br>
    <?php } ?>
    <?php } ?>
    <?php echo '<b>KETERANGAN :</b><br><b>STOK AVL</b> = Stok Yang Masih Bisa Ditawarkan'?>
</body>
</html>