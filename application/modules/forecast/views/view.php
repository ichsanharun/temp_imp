<?php
$ENABLE_ADD     = has_permission('Customer.Add');
$ENABLE_MANAGE  = has_permission('Customer.Manage');
$ENABLE_VIEW    = has_permission('Customer.View');
$ENABLE_DELETE  = has_permission('Customer.Delete');
?>
<style type="text/css">
	thead input{width: 100%;}
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<div class="box">
	<!-- /.box-header -->
	<div class="box-body">
		<div class="box-header">
			<div class="row">
				&nbsp;
				<a href="<?php echo site_url('forecast/fc_new'); ?>" class="btn btn-primary">
					<i class="fa fa-edit"></i>&nbsp; Create New
				</a>
			</div>
		</div>
		<table id="my-grid" class="table table-bordered table-striped table-condensed" style="width: 100%">
		<thead>
			<tr>
				<th width="50"><center>#</center></th>
				<th>Tanggal Forecast</th>
				<th>Status</th>
				<th>Cabang</th>
				<th width="20%">Process</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 1;
			if($view->num_rows() > 0){
				foreach($view->result() as $d){
				?>
				<tr>
					<td><center><?php echo $no; ?></center></td>
					<td><?php echo date('F-Y', strtotime($d->fc_date)); ?></td>
					<td><?php echo $d->fc_status; ?></td>
					<td>
						<?php 
						if($d->fc_kdcab == '101'){
							echo "YOGYAKARTA";
						}elseif($d->fc_kdcab == '102'){
							echo "SEMARANG";
						}elseif($d->fc_kdcab == '103'){
							echo "JAKARTA";
						}elseif($d->fc_kdcab == '111'){
							echo "BANDUNG";
						}
						?>
					</td>
					<td>
						<a href="<?php echo site_url('forecast/fc_edit/'.urlencode($d->fc_id).'/'.$d->fc_date); ?>" class='btn btn-sm btn-info'>
							<i class="fa fa-edit"></i> Update
						</a>
						<a href="<?php echo site_url('forecast/fc_remove/'.urlencode($d->fc_id)); ?>" class='btn btn-sm btn-danger' id='DeleteCast'>
							<i class="fa fa-remove"></i> Delete
						</a>
					</td>
				</tr>
				<?php
				$no++;
				}
			}
			?>
		</tbody>
		</table>
	</div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="MyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background:none;top:20%;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="modal-title"></h4>
			</div>
			<div class="modal-body" id="modal-body">
			...
			</div>
			<div class="modal-footer" id="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
				<span class="glyphicon glyphicon-remove"></span>  Tutup</button>
			</div>
		</div>
	</div>
</div>
<link href="<?php echo base_url('assets/plugins/toast/css/jquery.toast.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?= base_url('assets/plugins/toast/js/jquery.toast.js')?>"></script>
<!-- DataTables -->
<link href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>" rel="stylesheet">
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?= base_url('assets/js/jquery.mask.min.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// $('body').addClass('sidebar-collapse');
		$('#set_forecast').mask('0000');

		// Confirmation Delete
		$(document).on('click', '#DeleteCast', function(e){
			e.preventDefault();
			var LinkURL  = $(this).attr('href');
	        var btnSave  = "<button type='button' class='btn btn-warning' data-link_url='"+LinkURL+"' id='ProcessDelete'>Yes, Save</button>";
	        var btnClose = "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
	        $('.modal-dialog').removeClass('modal-lg');
	        $('.modal-dialog').addClass('modal-sm');
	        $('#modal-title').html('Konfirmasi');
	        $('#modal-body').html("Yakin ingin menghapus data ini ?");
	        $('#modal-footer').html(btnSave + btnClose);
	        $('#MyModal').modal({
	            backdrop: 'static',
	            keyboard: false
	        });
	        $('#MyModal').modal('show');
	    });
		// delete forecast
		$(document).on('click', '#ProcessDelete', function(e){
			e.preventDefault();
			$.ajax({
				url: $(this).data('link_url'),
				cache: false,
				type: "POST",
				data: "delete=true",
				dataType: "json",
				success: function(data){
					if(data.status == 1){
						$('#MyModal').modal('hide');
						$.toast({
						    position: 'top-center',
						    text: data.pesan,
						    bgColor: '#FF1356',
    						textColor: 'white'
						});
						setTimeout(function(){
							GoToPage(data.refresh_page);
						}, 1500);
					}
				}
			});
		});
	});
</script>
<style type="text/css">
	.skin-blue .main-header .logo{
	    background-color: #617c9c;
	}
	.skin-blue .main-header .logo:hover{
	    background-color: #617c9c;
	}
	.skin-blue .main-header .navbar{
	    background-color: #6383a8;
	}
</style>
