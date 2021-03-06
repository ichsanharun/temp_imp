<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">

<div class="box">

    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th width="50">#</th>
            <th>KODE PO</th>
            <th>Invoice</th>
            <th>Supplier</th>
            <th>Dollar</th>
            <th>Rupiah</th>
            <th>Perkiraan Bayar</th>
            <th width="25">Action</th>
        </tr>
        </thead>

        <tbody>
            <?php if ($results->num_rows() !== 0) {
    $num = 1;
    foreach ($results->result() as $row) :
      if ($row->rupiah_total == 0) {
        $dolar = 0;
      }else {
        if ($row->rupiah ==0) {
          $kurs_dollar_saat_ini = $this->db->where(array('kode'=>'USD'))->get("mata_uang")->row();
          $dolar = $row->rupiah_total / $kurs_dollar_saat_ini->kurs;
        }else {
          $dolar = $row->rupiah_total / $row->rupiah;
        }
        // code...
      }

      if ($row->tipe_payment == "persen") {
        // code...

    $peresen_dolar = $row->persen * $dolar / 100;
    $persen_rupiah = $row->persen * $row->rupiah_total / 100; ?>

                <tr>
                    <td><?= $num; ?> </td>
                    <td><?= $row->no_po; ?></td>
                    <td><?= @get_invoice($row->no_po); ?></td>
                    <td><?= $row->id_supplier; ?> - <?php echo $row->nm_supplier; ?></td>

                    <?php if ($persen_rupiah != 0) { ?>
                     <td style="text-align: right">$ <?= number_format($row->nominal, 2, ',', '.'); ?></td>
                     <td style="text-align: right">Rp. <?= number_format($persen_rupiah, 2, ',', '.'); ?></td>
                    <?php }else { ?>
                      <td style="text-align: right">$ <?= number_format($row->nominal, 2, ',', '.'); ?></td>
                      <td style="text-align: right">Rp. <?= number_format($row->bayar, 2, ',', '.'); ?></td>
                    <?php } ?>


                    <td><center><?php echo date('d/m/Y', strtotime($row->perkiraan_bayar)); ?></center></td>
                    <td style="text-align: center">
                        <?php
                        if ($row->st == 'open') {
                            ?>
                            <a class="text-green" href="<?= base_url("hutang/bayar_form/$row->idsss/$peresen_dolar"); ?>" >Bayar</a>
                            <?php
                        } else {
                            echo 'close';
                        } ?>

                    </td>
                </tr>
            <?php } else {
              $persen_rupiah = $row->nominal * $row->rupiah;

              ?>

                         <tr>
                             <td><?= $num; ?> </td>
                             <td><?= $row->no_po; ?></td>
                             <td><?= @get_invoice($row->no_po); ?></td>
                             <td><?= $row->id_supplier; ?> - <?php echo $row->nm_supplier; ?></td>
                            <?php if ($row->nominal == $row->bayar) { ?>
                             <td style="text-align: right">$ <?= number_format(0, 2, ',', '.'); ?></td>
                             <td style="text-align: right">Rp. <?= number_format($row->nominal, 2, ',', '.'); ?></td>
                             <td><center><?php echo date('d/m/Y', strtotime($row->perkiraan_bayar)); ?></center></td>
                            <?php }else { ?>
                              <td style="text-align: right">$ <?= number_format($row->nominal, 2, ',', '.'); ?></td>
                              <td style="text-align: right">Rp. <?= number_format($row->bayar, 2, ',', '.'); ?></td>
                              <td><center><?php echo date('d/m/Y', strtotime($row->perkiraan_bayar)); ?></center></td>
                            <?php } ?>
                             <td style="text-align: center">
                                 <?php
                                 if ($row->st == 'open') {
                                     ?>
                                     <a class="text-green" href="<?= base_url("hutang/bayar_form/$row->idsss/"); ?>" >Bayar</a>
                                     <?php
                                 } else {
                                     echo 'close';
                                 } ?>

                             </td>
                         </tr>
                     <?php
            }
            ++$num;

    endforeach;
} ?>
        </tbody>

        </table>
    </div>
    <!-- /.box-body -->
</div>


<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<!-- page script -->
<script type="text/javascript">

    $(function() {
        $("#example1").DataTable();
    });

    function edit_data(id){
        console.log(id);
        if(id!=""){
            var url = 'hutang/bayar_form/'+id;
            $(".box").hide();
            $("#form-area").show();
            $("#form-area").load(siteurl+url);
            $("#title").focus();
        }
    }


    function PreviewPdf(id)
    {
        param=id;
        tujuan = 'customer/print_request/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
    }


</script>
