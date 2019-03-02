<?php
    $ENABLE_ADD     = has_permission('Customer.Add');
    $ENABLE_MANAGE  = has_permission('Customer.Manage');
    $ENABLE_VIEW    = has_permission('Customer.View');
    $ENABLE_DELETE  = has_permission('Customer.Delete');
?>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">	
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th width='50'>#</th>
            <th>Nama Toko</th>
            <th>Kepemilikan</th>
            <th>Luas</th>
            <th>Tahun Berdiri</th>
            <th>Area</th>
            <th>Alamat</th>
			<?php if($ENABLE_MANAGE) : ?>
			<th width="25">Action</th>
			<?php endif; ?>
		</tr>
		</thead>

		<tbody>
		<?php if(empty($results)){
		}else{
			$numb=0; foreach($results AS $record){ $numb++; ?>
		<tr>
		    <td><?= $numb; ?></td>
			<td><?= $record->id_customer ?></td>
			<td><?= $record->nm_customer ?></td>
			<td><?= $record->bidang_usaha ?></td>
			<td><?= $record->produk_jual ?></td>
			<td><?= $record->kredibilitas ?></td>
			<td><?= $record->alamat ?></td>
			<td>
				<?php if($record->sts_aktif == 'aktif'){ ?>
					<label class="label label-success">Aktif</label>
				<?php }else{ ?>
					<label class="label label-danger">Non Aktif</label>
				<?php } ?>
			</td>
			<td style="padding-left:20px">
			<?php if($ENABLE_VIEW) : ?>
				<a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?=$record->id_customer?>')">
				<span class="glyphicon glyphicon-print"></span>
				</a>
			<?php endif; ?>

			<?php if($ENABLE_MANAGE) : ?>
				<a class="text-green" href="javascript:void(0)" title="Edit" onclick="edit_data('<?=$record->id_customer?>')"><i class="fa fa-pencil"></i>
				</a>
			<?php endif; ?>

			<?php if($ENABLE_DELETE) : ?>
				<a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?=$record->id_customer?>')"><i class="fa fa-trash"></i>
				</a>
			<?php endif; ?>
			</td>
		</tr>
		<?php } }  ?>
		</tbody>

		<tfoot>
		<tr>
			<th width='50'>#</th>
            <th>Nama Toko</th>
            <th>Kepemilikan</th>
            <th>Luas</th>
            <th>Tahun Berdiri</th>
            <th>Area</th>
            <th>Alamat</th>
			<?php if($ENABLE_MANAGE) : ?>
			<th width="25">Action</th>
			<?php endif; ?>
		</tr>
		</tfoot>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<div id="form-area">
<?php $this->load->view('customer/customer_form') ?>
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Customer</h4>
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
		var url = 'customer/create/';
		$(".box").hide();
		$("#form-area").show();
		$("#form-area").load(siteurl+url);
		$("#title").focus();
	}

  	function edit_data(id_customer){
		if(id_customer!=""){
			var url = 'customer/edit/'+id_customer;
			$(".box").hide();
			$("#form-area").show();
			$("#form-area").load(siteurl+url);
		    $("#title").focus();
		}
	}

	//Delete
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
		            url: siteurl+'customer/hapus_customer/'+id,
		            dataType : "json",
		            type: 'POST',
		            success: function(msg){
		                if(msg['delete']=='1'){
		                    $("#dataku"+id).hide(2000);
		                    //swal("Terhapus!", "Data berhasil dihapus.", "success");
		                    swal({
		                      title: "Terhapus!",
		                      text: "Data berhasil dihapus",
		                      type: "success",
		                      timer: 1500,
		                      showConfirmButton: false
		                    });
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

	function PreviewPdf(id)
	{
		param=id;
		tujuan = 'customer/print_request/'+param;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
	}

</script>
