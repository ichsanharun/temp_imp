<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="box-header">
			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>

		<span class="pull-right">
				<?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
		</span>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th width="1%">#</th>
			<th>Nama Diskon</th>
			<th>Persen Diskon (%)</th>
			<th>Status</th>
			<th width="8%">Aksi</th>
		</tr>
		</thead>

		<tbody>
		<?php
		if(@$results){
			$n=1;
			foreach(@$results as $kd=>$vd){
				$no=$n++;
		?>
			<tr>
				<td><center><?php echo $no?></center></td>
				<td><?php echo $vd->diskon?></td>
				<td><center><?php echo $vd->persen?></center></td>
				<td>
					<center>
					<?php if($vd->sts_aktif == 'aktif'){ ?>
					<label class="label label-success">Aktif</label>
					<?php }else{ ?>
						<label class="label label-danger">Non Aktif</label>
					<?php } ?>
					</center>
				</td>
				<td>
					<a class="text-green" href="javascript:void(0)" title="Edit" onclick="edit_data('<?php echo $vd->id_diskon?>')"><i class="fa fa-pencil"></i>
                    </a>
                    <a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vd->id_diskon?>')"><i class="fa fa-trash"></i>
                    </a>
				</td>
			</tr>
		<?php } ?>
		<?php } ?>
		</tbody>

		<tfoot>
		<tr>
			<th width="1%">#</th>
			<th>Nama Diskon</th>
			<th>Persen Diskon (%)</th>
			<th>Status</th>
			<th width="8%">Aksi</th>
		</tr>
		</tfoot>
		</table>
	</div>
	<!-- /.box-body -->
</div>


<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Kendaraan</h4>
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
  	});
  	function add_data(){
        window.location.href = siteurl+"diskon/create";
    }
    function edit_data(id){
		window.location.href = siteurl+"diskon/edit/"+id;
	}
	function delete_data(id){
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
          if(isConfirm) {
            $.ajax({
                    url: siteurl+'diskon/hapus_diskon',
                    data :{"ID":id},
                    dataType : "json",
                    type: 'POST',
                    success: function(result){
                        if(result.delete=='1'){                         
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.reload();
                            },1600);
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
</script>
