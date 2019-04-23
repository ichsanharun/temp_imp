<?php
    $ENABLE_ADD = has_permission('Purchaseorder.Add');
    $ENABLE_MANAGE = has_permission('Purchaseorder.Manage');
    $ENABLE_VIEW = has_permission('Purchaseorder.View');
    $ENABLE_DELETE = has_permission('Purchaseorder.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
<div class="box">
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th>No. PO</th>
                <th>No. PR</th>
                <th>No. RC</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th width="7%">Aksi</th>
            </tr>
        </thead>
        <tbody>
           <?php
           if (@$results) {
               $n = 1;
               foreach (@$results as $kp => $vp) {
                 $cek_pr = $this->db->select('no_pr')->where(array('no_po'=>$vp->no_po))->group_by('no_pr')->get('trans_po_detail')->row();
                 $cek_rc = $this->db->select('no_receiving')->where(array('po_no'=>$vp->no_po))->group_by('no_receiving')->get('trans_receive')->row();
                   $no = $n++; ?>
           <tr>
             <td><center><?php echo $no; ?></center></td>
             <td><center><?php echo $vp->no_po; ?></center></td>
             <td><center><?php echo $cek_pr->no_pr; ?></center></td>
             <td><center><?php echo $cek_rc->no_receiving; ?></center></td>
             <td><?php echo $vp->id_supplier; ?> / <?= get_supplier($vp->id_supplier); ?></td>
             <td><center><?php echo date('d/m/Y', strtotime($vp->tgl_po)); ?></center></td>
             <td><?php echo $vp->status; ?></td>
             <td>
                <center>
                <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vp->no_po; ?>')">
                    <span class="glyphicon glyphicon-print"></span>
                </a>
                </center>
             </td>
           </tr>
           <?php
               } ?>
           <?php
           } ?>
        </tbody>
        </table>
    </div>
</div>
<div id="form-area">
<?php //$this->load->view('salesorder/salesorder_form')?>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Purchase Order (PO)</h4>
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
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js');?>"></script>

<!-- page script -->
<script type="text/javascript">
    $(function() {
        $("#example1").DataTable();
        $("#form-area").hide();
    });
    function add_data(){
        window.location.href = siteurl+"purchaseorder/create";
    }
    function PreviewPdf(nopo)
    {
      tujuan = 'purchaseorder/print_request_cabang/'+nopo;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>
