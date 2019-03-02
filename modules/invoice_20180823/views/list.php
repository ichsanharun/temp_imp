<?php
    $ENABLE_ADD     = has_permission('Invoice.Add');
    $ENABLE_MANAGE  = has_permission('Invoice.Manage');
    $ENABLE_VIEW    = has_permission('Invoice.View');
    $ENABLE_DELETE  = has_permission('Invoice.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
	 <div class="box-header">
        <?php if ($ENABLE_ADD) : ?>
            <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
        <?php endif; ?>

        <span class="pull-right">
                <?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
        </span>
    </div>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
	        <tr>
	            <th width="2%">#</th>
              <th width="15%">NO. Invoice</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Jatuh Tempo</th>
              <th>Nama Salesman</th>
              <th>Total</th>
              <th>Aksi</th>
	        </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><center><?php echo $vr->no_invoice?></center></td>
          <td><?php echo $vr->nm_customer?></td>
          <td><center><?php echo date('d M Y',strtotime($vr->tanggal_invoice))?></center></td>
          <td><center><?php echo date('d M Y',strtotime($vr->tgljatuhtempo))?></center></td>
          <td><?php echo $vr->nm_salesman?></td>
          <td class="text-right"><?php echo formatnomor($vr->hargajualtotal)?></td>
          <td>
            <center>
            <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vr->no_invoice?>')">
                <span class="glyphicon glyphicon-print"></span>
            </a>
            </center>
          </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th width="2%">#</th>
              <th>NO. Invoice</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Total</th>
              <th>Aksi</th>
          </tr>
        </tfoot>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Invoice</h4>
      </div>
      <div class="modal-body" id="MyModalBody">
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
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
    $(function() {
      var dataTable = $("#example1").DataTable();
    });
    function add_data(){
        window.location.href = siteurl+"invoice/create";
    }
    function PreviewPdf(noinv)
    {
      param=noinv;
      tujuan = 'invoice/print_request/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
    
</script>