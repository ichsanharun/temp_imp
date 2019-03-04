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
                    if (!empty(@get_invoice($row->no_po))) {
                        ?>
                <tr>
                    <td><?= $num; ?></td>
                    <td><?= $row->no_po; ?></td>
                    <td><?= @get_invoice($row->no_po); ?></td>
                    <td><?= $row->id_supplier; ?> - <?php echo $row->nm_supplier; ?></td>
                    <td style="text-align: right">$ <?= number_format($row->dollar, 2, ',', '.'); ?></td>
                    <td style="text-align: right">Rp. <?= number_format($row->hutang, 2, ',', '.'); ?></td>
                    <td><center><?php echo date('d/m/Y', strtotime($row->perkiraan_bayar)); ?></center></td>
                    <td style="text-align: center">
                        <?php
                        if ($row->status == 'open') {
                            ?>
                            <a class="text-green" href="<?= base_url("hutang/bayar_form/$row->id"); ?>" >Bayar</a>
                            <?php
                        } else {
                            echo 'close';
                        } ?>
                        
                    </td>
                </tr>
            <?php ++$num;
                    }
    endforeach;
} ?>
        </tbody>

        </table>
    </div>
    <!-- /.box-body -->
</div>


<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js');?>"></script>

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
