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

<?php echo form_open('#', array('id'=>'FormForecast')); ?>
<div class="box">
	<div class="box-header col-md-3">
		<label>Cabang</label>
		<input type="text" name="kdcab" id="kdcab" class="form-control" value="<?php echo $kdcab->cabang; ?>" readonly>
	</div>

	<div class="box-header col-md-2">
		<label>Periode</label>
		<input type="text" name="periode" id="periode" class="form-control" value="<?php echo date('F-Y', strtotime($periode.'-01')); ?>" readonly>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="my-grid" class="table table-bordered table-striped table-condensed" style="width: 100%">
		<thead>
			<tr>
				<th rowspan="2" class="no-sort" width="5" style="vertical-align: middle;">#</th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">Cabang</th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">Produk</th>
				<th colspan="4" class="no-sort"><center>Qty Demand</center></th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">
					Forecast<br><?php echo date('F', strtotime($periode.'-01')); ?>
				</th>
				<!-- <th rowspan="2" class="no-sort" style="vertical-align: middle;">Set Forecast<br><?php echo date('F');?></th> -->
				<th colspan="3" class="no-sort"><center>Waktu (Month)</center></th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">Qty Safety Stock</th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">Point (Month)</th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">Qty Reorder Point</th>
				<th rowspan="2" class="no-sort" style="vertical-align: middle;">Qty Stock</th>
			</tr>
			<tr>
				<?php
				for($m=4; $m>=1; $m--)
				{
					$fc_last_month = $periode.'-01';
					$fc_last_month = date('F', strtotime("-".$m." Month", strtotime($fc_last_month)));
					echo "<th>M-".$m."<br>".$fc_last_month."</th>";
				}
				?>
				<th>Waktu Produksi</th>
				<th>Waktu Pengiriman</th>
				<th>Safety Stock</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($forecast->num_rows() > 0)
			{
			$no = 1;
			foreach($forecast->result() as $f)
			{
				?>
				<tr>
					<td>
						<?php echo $no; ?>
						<input type="hidden" name="fc_id_detail[]" value="<?php echo $f->fc_id_detail; ?>">
					</td>
					<td><?php echo $f->kdcab; ?></td>
					<td style="white-space: nowrap;"><?php echo $f->nm_barang; ?></td>
					<td><?php echo $f->qty_demand_m4; ?></td>
					<td><?php echo $f->qty_demand_m3; ?></td>
					<td><?php echo $f->qty_demand_m2; ?></td>
					<td><?php echo $f->qty_demand_m1; ?></td>
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "set_forecast[]",
							'id' 	=> "set_forecast",
							'class' => "form-control input-sm",
							'size' 	=> "9",
							'onkeydown' => 'return check_int(this, event);',
							'maxlength' => '4',
							'value' => $f->set_forecast
						));
						?>
					</td>
					<!-- <td></td> -->
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "wkt_produksi[]",
							'id' 	=> "wkt_produksi",
							'class' => "form-control input-sm",
							'size' 	=> "4",
							'maxlength' => '4',
							'value' => @$f->waktu_produksi
						));
						?>
					</td>
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "wkt_pengiriman[]",
							'id' 	=> "wkt_pengiriman",
							'class' => "form-control input-sm",
							'size' 	=> "4",
							'maxlength' => '4',
							'value' => @$f->waktu_pengiriman
						));
						?>
					</td>
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "safety_stock[]",
							'id' 	=> "safety_stock",
							'class' => "form-control input-sm",
							'size' 	=> "4",
							'maxlength' => '4',
							'value' => @$f->safety_stock
						));
						?>
					</td>
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "qty_safety_stock[]",
							'id' 	=> "qty_safety_stock",
							'class' => "form-control input-sm",
							'size' 	=> "4",
							'maxlength' => '4',
							'readonly' => true,
							'value' => $f->qty_safety_stock
						));
						?>
					</td>
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "wkt_order_point[]",
							'id' 	=> "wkt_order_point",
							'class' => "form-control input-sm",
							'size' 	=> "4",
							'maxlength' => '4',
							'readonly' => true,
							'data-toggle' =>'tooltip',
							'data-placement' =>'top',
							'title' => '(Waktu Produksi+Waktu Pengiriman+Safety Stok)',
							'value' => $f->wkt_order_point
						));
						?>
					</td>
					<td>
						<?php 
						echo form_input(array(
							'name' 	=> "qty_reorder_point[]",
							'id' 	=> "qty_reorder_point",
							'class' => "form-control input-sm",
							'size' 	=> "4",
							'maxlength' => '4',
							'readonly'  => true,
							'data-toggle' =>'tooltip',
							'data-placement' =>'top',
							'title' =>'(Point (Month) * Set Forecast)',
							'value' => $f->qty_reorder_point
						));
						?>
					</td>
					<td><?php echo $f->qty_stock; ?></td>
				</tr>
				<?php
				$no++;
			}
			}
			?>
		</tbody>
		</table>
		<br>

		<div class="box-header col-md-2">
			<div class="row">
				&nbsp;<a href="<?php echo site_url('forecast'); ?>" class="btn btn-info">
					<i class="fa fa-chevron-left"></i>&nbsp; Kembali
				</a>
				<a href="#" class="btn btn-primary" id="SaveForecast"><i class='fa fa-undo'></i> Simpan</a>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>

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
				<span class="glyphicon glyphicon-remove"></span> Tutup</button>
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
		$('body').addClass('sidebar-collapse');
		$('#set_forecast').mask('0000');
		var dataTable = $('#my-grid').DataTable({
	        "serverSide": false,
	        "stateSave" : false,
	        "bAutoWidth": true,
	        "searching": false,
	        "bLengthChange" : false,
	        "bPaginate": false,
	        "aaSorting": [[ 0, "asc" ]],
	        "columnDefs": [ 
	            {"aTargets":[0], "sClass" : "column-hide"},
	            {"aTargets": 'no-sort', "orderable": false}
	        ],
	        "sPaginationType": "simple_numbers", 
	        "iDisplayLength": 10,
	        "aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]]
	    });

		// Confirmation Save
		$(document).on('click', '#SaveForecast', function(e){
			e.preventDefault();
	        var btnSave  = "<button type='button' class='btn btn-warning' id='ProcessSave'>Yes, Save</button>";
	        var btnClose = "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
	        $('.modal-dialog').removeClass('modal-lg');
	        $('.modal-dialog').addClass('modal-sm');
	        $('#modal-title').html('Konfirmasi');
	        $('#modal-body').html("Anda yakin ingin mengubah data ini ?");
	        $('#modal-footer').html(btnSave + btnClose);
	        $('#MyModal').modal({
	            backdrop: 'static',
	            keyboard: false
	        });
	        $('#MyModal').modal('show');
	    });


	    // Process Save
		$(document).on('click', '#ProcessSave', function(e){
			e.preventDefault();
			$.ajax({
				url: "<?php echo site_url('forecast/fc_edit/'.$fc_id.'/'.$periode); ?>",
				cache: false,
				type: "POST",
				data: $('#FormForecast').serialize(),
				dataType: "json",
				success: function(data){
					if(data.status == 0){
						// $('#MyModal').modal('hide');
						// ToastNotif(data.pesan);

				        var btnClose = "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
				        $('.modal-dialog').removeClass('modal-lg');
				        $('.modal-dialog').addClass('modal-sm');
				        $('#modal-title').html('Konfirmasi');
				        $('#modal-body').html(data.pesan);
				        $('#modal-footer').html(btnClose);
				        $('#MyModal').modal({
				            backdrop: 'static',
				            keyboard: false
				        });
				        $('#MyModal').modal('show');
					}
					if(data.status == 1){
						$('#MyModal').modal('hide');
						$.toast({
						    position: 'top-center',
						    text: data.pesan,
						    bgColor: '#FF1356',
    						textColor: 'white'
						});
						if(data.redirect_page == "YES"){
	                        setTimeout(function(){
	                            GoToPage(data.redirect_page_URL);
	                        }, 2000);
	                    }
					}
					if(data.status == 2){
						$('#MyModal').modal('hide');
						ToastNotif(data.pesan);
					}
					if(data.status == 3){
						$('#MyModal').modal('hide');
						ToastNotif(data.pesan);						
					}
				}
			});
		});


	    // set forecast
		$(document).on('keyup', '#set_forecast', function(e){
			e.preventDefault();
			var nilai_forecast 	  = $(this).val();
			var waktu_produksi    = $(this).parent().parent().find('td:nth-child(9) input');
			var waktu_pengiriman  = $(this).parent().parent().find('td:nth-child(10) input');
			var safety_stok  	  = $(this).parent().parent().find('td:nth-child(11) input');
			var qty_safety_stock  = $(this).parent().parent().find('td:nth-child(12) input');
			var point_month 	  = $(this).parent().parent().find('td:nth-child(13) input');
			var qty_reorder_point = $(this).parent().parent().find('td:nth-child(14) input');

			if(waktu_produksi.val() == ''){
				alert('Waktu produksi masih kosong.');
				waktu_produksi.focus();
				$(this).val('');
				return false;
			}else if(waktu_pengiriman.val() == ''){
				alert('Waktu pengiriman masih kosong.');
				waktu_pengiriman.focus();
				$(this).val('');
				return false;
			}else if(safety_stok.val() == ''){
				alert('Safet Stok masih kosong.');
				safety_stok.focus();
				$(this).val('');
				return false;
			}
			else
			{
				var total_qty_safety_stock = (parseFloat(safety_stok.val()) * parseFloat(nilai_forecast));
				var total_point_month = (parseFloat(waktu_produksi.val()) + parseFloat(waktu_pengiriman.val()) + parseFloat(safety_stok.val())).toFixed(1);
				var total_qty_reorder_point = (total_point_month * parseFloat(nilai_forecast));

				if(nilai_forecast == '' || nilai_forecast == null){
					total_qty_safety_stock 	= 0;
					total_qty_reorder_point = 0;
				}

				qty_safety_stock.val(total_qty_safety_stock);
				point_month.val(parseFloat(total_point_month));
				qty_reorder_point.val(total_qty_reorder_point);
			}
		});
	});
</script>
<style type="text/css">
	.kdcab:focus{
		border: 1px solid #d2d6de;
	}
	table {
        display: block;
        overflow-x: auto;
    }
    .skin-blue .main-header .logo{
	    background-color: #617c9c;
	    box-shadow: inset 0px -2px 1px 0px #9a9a9a;
	}
	.skin-blue .main-header .logo:hover{
	    background-color: #617c9c;
	    box-shadow: inset 0px -2px 1px 0px #9a9a9a;
	}
	.skin-blue .main-header .navbar{
	    background-color: #6383a8;
	    box-shadow: inset 0px -1px 5px 0px #969696;
	}
</style>
