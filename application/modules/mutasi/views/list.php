<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-header">
        <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
    </div>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
	        <tr>
	            <th width="2%">#</th>
              <th>NO. Mutasi</th>
	            <th>Tgl Mutasi</th>
              <th>Asal</th>
              <th>Tujuan</th>
              <th>Nama Supir</th>
              <th>Kendaraan</th>
	            <th>Status</th>
	            <th>Aksi</th>
	        </tr>
        </thead>
        <tbody>
          <?php if(@$results){ ?>
            <?php 
            $n = 1;
            foreach(@$results as $kso=>$vso){ 
                $no = $n++;
                $status = '<span class="badge bg-green">IN TRANSIT</span>';
                if($vso->status_mutasi == "REC"){
                  $status = '<span class="badge bg-red">RECEIVED</span>';
                }
            ?>
            <tr>
              <td><center><?php echo $no?></center></td>
              <td><?php echo $vso->no_mutasi?></td>
              <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_mutasi))?></td>
              <td><?php echo $vso->cabang_asal?></td>
              <td><?php echo $vso->cabang_tujuan?></td>
              <td><?php echo $vso->nm_supir?></td>
              <td><?php echo $vso->ket_kendaraan?></td>
              <td><center><?php echo $status?></center></td>
              <td class="text-center">
                    <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vso->no_mutasi?>')">
                    <span class="glyphicon glyphicon-print"></span>
                    </a>
              </td>
            </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th width="2%">#</th>
              <th>NO. Mutasi</th>
              <th>Tgl Mutasi</th>
              <th>Asal</th>
              <th>Tujuan</th>
              <th>Nama Supir</th>
              <th>Kendaraan</th>
              <th>Status</th>
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
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Mutasi Produk</h4>
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
        window.location.href = siteurl+"mutasi/create";
    }
    function PreviewPdf(nomutasi)
    {
      tujuan = 'mutasi/print_request/'+nomutasi;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>