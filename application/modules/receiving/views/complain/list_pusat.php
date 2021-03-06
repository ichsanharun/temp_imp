<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">

<div class="box">
    
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th width="2%">#</th>
            <th>NO. PO</th>
            <th>Supplier</th>
            <th>No Container</th>
            <th>Tgl Receiving</th>
            <th>Status</th>
            <th width="10%">Aksi</th>
        </tr>
        </thead>

        <tbody>
           <?php
           $no = 0;
           $query = $this->db->query("SELECT * FROM `receive_detail_barang` as a, trans_receive as b WHERE a.no_po=b.po_no AND a.rusak !='0' GROUP BY a.no_po ");

            foreach ($query->result() as $row) {
                ++$no; ?>
           <tr>
             <td><center><?php echo $no; ?></center></td>
             <td><center><?php echo $row->no_po; ?></center></td>
             <td><?php echo $row->id_supplier; ?> - <?php echo $row->nm_supplier; ?></td>
             <td><?php echo $row->container_no; ?></td>
             <td><center><?php echo date('d/m/Y', strtotime($row->tglreceive)); ?></center></td>
             <td> <?php
                 if ($row->status == 0) {
                     echo 'Proses';
                 } elseif ($row->status == 1) {
                     echo 'Diterima';
                 } else {
                     echo 'Selesai';
                 } ?></td>
             <td>
                <center>
                    <?php
                    if ($row->status == '0') {
                        ?>
                        <a href="<?= base_url("receiving/receiving_complain/konfrimasi/$row->no_po"); ?>">Confrim</a>
                        <?php
                    } ?>
                    
                    &nbsp;&nbsp;
                        <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdfxxx('<?php echo $row->no_po; ?>')">
                            <span class="glyphicon glyphicon-print"></span>
                        </a>
                    </center>
             </td>
           </tr>
           <?php
            } ?>
        </tbody>

        </table>
    </div>
    <!-- /.box-body -->
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 1000px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Complain</h4>
      </div>
      <div class="modal-body" style="width: 1000px">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js');?>"></script>

<!-- page script -->
<script type="text/javascript">
    function PreviewPdfxxx(nopo)
    {
      tujuansss = 'print_complain/'+nopo;
        $(".modal-body").html('<iframe src="'+tujuansss+'" frameborder="no" width="100%" height="400"></iframe>');
    }
    
    $(function() {
        $("#example1").DataTable();
        $("#form-area").hide();
    });


</script>
