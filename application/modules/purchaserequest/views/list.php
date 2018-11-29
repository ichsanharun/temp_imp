<?php
    $ENABLE_ADD     = has_permission('Purchaserequest.Add');
    $ENABLE_MANAGE  = has_permission('Purchaserequest.Manage');
    $ENABLE_VIEW    = has_permission('Purchaserequest.View');
    $ENABLE_DELETE  = has_permission('Purchaserequest.Delete');
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
                <th>NO. PR</th>
                <th>Cabang</th>
                <th>Tanggal PR</th>
                <th>Plan Delivery</th>
                <th>Nama Supplier</th>
                <th width="7%">Aksi</th>
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
             <td><center><?php echo $vp->nopr?></center></td>
             <td><?php echo $vp->kdcab.' / '.$vp->namacabang?></td>
             <td><center><?php echo date('d/m/Y',strtotime($vp->tgl_pr))?></center></td>
             <td><center><?php echo date('d/m/Y',strtotime($vp->plan_delivery_date))?></center></td>
             <td><?php echo $vp->id_supplier.' / '.$vp->nm_supplier?></td>
             <!--td class="text-right"><?php echo formatnomor($vp->total_pr)?></td-->
             <td>
                <center>
                <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vp->nopr?>')">
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
              <th>NO. PR</th>
              <th>Nama Supplier</th>
              <th>Tanggal</th>
              <th>Plan Delivery</th>

              <th>Aksi</th>
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
        window.location.href = siteurl+"purchaserequest/create";
    }
    function PreviewPdf(nopr)
    {
      tujuan = 'purchaserequest/print_request/'+nopr;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>
