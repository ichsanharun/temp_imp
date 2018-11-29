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

            font-size:10pt;
            color:#000;
        }
        body
        {
            width:100%;
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
            position: fixed;
        }

        #tabel-laporan {
            border-spacing: 0;
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
            font-size:7pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        img.resize {
          max-width:12%;
          max-height:12%;
        }
    </style>
</head>
<?php
$uk1 = 9;
$ukk = 17;
$ukkk = 11;
?>
<body style=<?php echo '"font-size: '.$uk1.'pt !important;"';?>>
    <div id="content-report1" style="padding-top:7px !important">
    <table width="100%" id="tabel-laporan" style=<?php echo '"font-size: '.$ukkk.'px !important;font-weight:bold;padding-top:7px !important"';?>>
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
            $rs1 = $this->db->query("SELECT * FROM barang_koli WHERE id_barang ='".$vd->id_barang."'")->num_rows()+1;
            $rs = count($colly);
        ?>
        <tr>
            <td rowspan="<?php echo $rs?>"><center><?php echo $no?></center></td>
            <td rowspan="<?php echo $rs?>"><?php echo $vd->nm_barang?></td>
            <td rowspan="<?php echo $rs?>"><center><?php echo $vd->qty_supply?></center></td>
            <td rowspan="<?php echo $rs?>"><center><?php echo $vd->satuan?></center></td>



            <?php
                $nc=1;
                $counter_c = 0;
                foreach($colly as $kc=>$vc) {
                    $ncl = $nc++;
                    $q = $vd->qty_supply;
                if ($ncl != 1) {
                  echo "<tr>";
                }
            ?>

                <td><center><?php echo $no.'.'.$ncl?></center></td>
                <td style="padding-left: 10px;"><?php echo $vc->nm_koli?></td>
                <td><center><?php echo $vc->qty?></center></td>
                <td><center><?php echo $vc->qty*$q?></center></td>
                <?php if ($ncl == 1) {
                  echo "<td rowspan=".$rs."></td>";
                } ?>
            </tr>
            <?php }




        } ?>
        <!--tr>
          <td rowspan="3">A</td>
          <td rowspan="3">B</td>
          <td rowspan="3">C</td>
          <td rowspan="3">D</td>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
        <tr>
          <td rowspan="4">A</td>
          <td rowspan="4">B</td>
          <td rowspan="4">C</td>
          <td rowspan="4">D</td>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr>
        <tr>
          <td>1</td>
          <td>2</td>
          <td>3</td>
          <td>4</td>
          <td>5</td>
        </tr-->

    </table>
    </div>
</body>
</html>
