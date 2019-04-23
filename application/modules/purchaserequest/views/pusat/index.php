<?php
    $ENABLE_ADD = has_permission('Purchaserequest.Add');
    $ENABLE_MANAGE = has_permission('Purchaserequest.Manage');
    $ENABLE_VIEW = has_permission('Purchaserequest.View');
    $ENABLE_DELETE = has_permission('Purchaserequest.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#PR" data-toggle="tab" aria-expanded="true" id="data">PR Non ACC</a></li>
        <li class=""><a href="#PO" data-toggle="tab" aria-expanded="false" id="data_koli">PR ACC</a></li>
    </ul>
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="PR">
          <div class="box">

              <div class="box-body" style="overflow-x:auto !important">
                  <table id="example1" class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th width="2%">#</th>
                          <th>NO. PR</th>
                          <th>Cabang</th>
                          <th>Tanggal PR</th>
                          <th> Supplier</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                     <?php
                     if (@$non) {
                         $n = 1;
                         foreach (@$non as $kp => $vp) {
                             $no = $n++; ?>
                     <tr>
                       <td><center><?php echo $no; ?></center></td>
                       <td><center><?php echo $vp->nopr; ?></center></td>
                       <td><?php echo $vp->kdcab.' / '.$vp->namacabang; ?></td>
                       <td><center><?php echo date('d/m/Y', strtotime($vp->tgl_pr)); ?></center></td>
                       <td><?php echo $vp->id_supplier; ?> - <?php echo $vp->nm_supplier; ?></td>
                       <!--td class="text-right"><?php echo formatnomor($vp->total_pr); ?></td-->
                       <td>
                          <center>
                              <?php
                              if ($vp->proses_po == 'Proses' || $vp->proses_po == 'REVISI') {
                                  ?>

                                  <a href="<?= base_url("purchaserequest/Pusat_purchaserequest/konfirmasi/$vp->id_supplier/$vp->nopr"); ?>">
                                    <span class="badge bg-orange">CONFIRM</span>
                                  </a> &nbsp;
                                  <?php
                              } ?>

                          <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vp->nopr; ?>')">
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
        </div>
        <div class="tab-pane" id="PO">
          <div class="box">

              <div class="box-body" style="overflow-x:auto !important">
                  <table id="tbl_acc" class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th width="2%">#</th>
                          <th>NO. PR</th>
                          <th>NO. PO</th>
                          <th>NO. RC</th>
                          <th>Cabang</th>
                          <th>Tanggal PR</th>
                          <th> Supplier</th>
                          <th>Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                     <?php
                     if (@$results) {
                         $n = 1;
                         foreach (@$results as $kp => $vp) {
                           $cek_po = $this->db->select('no_po')->where(array('no_pr'=>$vp->nopr))->group_by('no_pr')->get('trans_po_detail')->row();
                           $cek_rc = $this->db->select('no_receiving')->where(array('po_no'=>$cek_po->no_po))->group_by('no_receiving')->get('trans_receive')->row();
                           //print_r($cek_po);
                             $no = $n++; ?>
                     <tr>
                       <td><center><?php echo $no; ?></center></td>
                       <td><center><?php echo $vp->nopr; ?></center></td>
                       <td><center><?php echo $cek_po->no_po; ?></center></td>
                       <td><center><?php echo $cek_rc->no_receiving; ?></center></td>
                       <td><?php echo $vp->kdcab.' / '.$vp->namacabang; ?></td>
                       <td><center><?php echo date('d/m/Y', strtotime($vp->tgl_pr)); ?></center></td>
                       <td><?php echo $vp->id_supplier; ?> - <?php echo $vp->nm_supplier; ?></td>
                       <!--td class="text-right"><?php echo formatnomor($vp->total_pr); ?></td-->
                       <td>
                          <center>
                              <?php
                              if ($vp->proses_po == 'Proses' || $vp->proses_po == 'REVISI') {
                                  ?>

                                  <a href="<?= base_url("purchaserequest/Pusat_purchaserequest/konfirmasi/$vp->id_supplier/$vp->nopr"); ?>">
                                    <span class="badge bg-orange">CONFIRM</span>
                                  </a> &nbsp;
                                  <?php
                              } ?>

                          <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vp->nopr; ?>')">
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
        </div>

    </div>
    <!-- /.tab-content -->
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
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<!-- page script -->
<script type="text/javascript">
    $(function() {
        $("#example1,#tbl_acc").DataTable();
        $("#form-area").hide();
    });
    function add_data(){
        window.location.href = siteurl+"purchaserequest/new_create";
    }

    function edit_data(id, pr){
        if(id!=""){
            var url = 'purchaserequest/edit/'+id+'/'+pr;
            $(".box").hide();
            $("#form-area").show();
            $("#form-area").load(siteurl+url);
            $("#title").focus();
        }
    }

    function delete_data(id){
        //alert(id);
        swal({
          title: "Anda Yakin?",
          text: "Data Akan Terhapus secara Permanen!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, delete!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            $.ajax({
                    url: siteurl+'purchaserequest/hapus_pr/'+id,
                    dataType : "json",
                    type: 'POST',
                    success: function(msg){
                        if(msg['delete']=='1'){
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            window.location.reload();
                        } else {
                            swal({
                              title: "Gagal!",
                              text: "Data gagal dihapus",
                              type: "error",
                              timer: 1500,
                              showConfirmButton: false
                            });
                        };
                    },
                    error: function(){
                        swal({
                          title: "Gagal!",
                          text: "Gagal Eksekusi Ajax",
                          type: "error",
                          timer: 1500,
                          showConfirmButton: false
                        });
                    }
                });
          } else {
            //cancel();
          }
        });
    }

    function PreviewPdf(nopr)
    {
      tujuan = 'purchaserequest/print_request/2/'+nopr;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>
