<?php
    $ENABLE_ADD     = has_permission('Pendingpo.Add');
    $ENABLE_MANAGE  = has_permission('Pendingpo.Manage');
    $ENABLE_VIEW    = has_permission('Pendingpo.View');
    $ENABLE_DELETE  = has_permission('Pendingpo.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th>NO. PR</th>
                <th>Cabang</th>
                <th>Tanggal PR</th>
                <th>Nama Supplier</th>
                <th>Qty Total</th>
                <th>Qty PO</th>
            </tr>
        </thead>
        <tbody>
           <?php
           if(@$results){
           $n=1;
           foreach(@$results as $kp => $vp){
            $no = $n++;
           ?>
           <tr>
             <td><center><?php echo $no?></center></td>
             <td><center><?php echo $vp->no_pr?></center></td>
             <td><?php echo $vp->kdcab.' / '.$vp->namacabang?></td>
             <td><center><?php echo date('d/m/Y',strtotime($vp->tgl_pr))?></center></td>
             <td><?php echo $vp->id_supplier.' / '.$vp->nm_supplier?></td>
             <td><?php echo $vp->qty_pr?></td>
             <td><?php echo $vp->qty_po?></td>
           </tr>
           <?php } ?>
           <?php } ?>
        </tbody>
        <tfoot>
            <tr>
              <th width="2%">#</th>
              <th>NO. PR</th>
              <th>Cabang</th>
              <th>Tanggal PR</th>
              <th>Nama Supplier</th>
              <th>Qty Total</th>
              <th>Qty PO</th>
            </tr>
        </tfoot>
        </table>
    </div>
</div>
<div id="form-area">
<?php //$this->load->view('salesorder/salesorder_form') ?>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Purchase Order (PR)</h4>
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
        $("#example1").DataTable();
        $("#form-area").hide();
    });
    function add_data(){
        window.location.href = siteurl+"purchaseorder/createpending";
    }
    function PreviewPdf(nopr)
    {
      tujuan = 'purchaserequest/print_request/'+nopr;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>
