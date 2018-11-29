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
	<div class="box-header col-md-3">
		<!-- <?php if ($ENABLE_ADD) : ?>
			<a class="btn btn-success" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>New</a>
		<?php endif; ?> -->
		<label>Cabang</label>
		<select name="kdcab" id="kdcab" class="form-control kdcab">
			<option value="">Pilih Cabang</option>
			<?php
			if($cabang->num_rows() > 0){
				foreach($cabang->result() as $d){
					echo "<option value='".$d->kdcab."'>".$d->namacabang."</option>";
				}
			}
			?>
		</select>
	</div>

	<div class="box-header col-md-2">
		<label>Periode</label>
		<input type="text" name="periode" id="periode" class="form-control" value="<?php echo $periode; ?>" readonly>
	</div>
	<div class="box-header col-md-2">
		<label>&nbsp;</label>
		<button type="button" id="generate" class="btn btn-primary form-control"><i class="fa fa-apple"></i>&nbsp; GENERATE</button>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<div id="LoadDetail"></div>
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
		// load detail order by cabang
		$(document).on('change', '#kdcab', function(r){
			r.preventDefault();
			$.ajax({
				url: "<?php echo site_url('forecast/fc_get_cabang'); ?>",
				cache: false,
				type: "POST",
				data: "kdcab="+this.value+"&periode="+$('#periode').val(),
				dataType: "json",
				success: function(data){
					if(data.status == 1){
						$('#LoadDetail').load(data.link_detail);
					}
				}
			});
		});
		// load detail order by cabang
		$(document).on('click', '#generate', function(r){
			r.preventDefault();
			if($('#kdcab').val() == ''){
				ToastNotif('Cabang Tidak Boleh Kosong.');
			}else{
				$.ajax({
					url: "<?php echo site_url('forecast/fc_get_cabang'); ?>",
					cache: false,
					type: "POST",
					data: "kdcab="+$('#kdcab').val()+"&periode="+$('#periode').val(),
					dataType: "json",
					success: function(data){
						if(data.status == 1){
							$('#LoadDetail').load(data.link_detail);
						}
					}
				});
			}			
		});

	    // set forecast
		$(document).on('keyup', '#set_forecast', function(e){
			e.preventDefault();
			var nilai_forecast 	  = $(this).val();
			var waktu_produksi    = $(this).parent().parent().find('td:nth-child(10) input');
			var waktu_pengiriman  = $(this).parent().parent().find('td:nth-child(11) input');
			var safety_stok  	  = $(this).parent().parent().find('td:nth-child(12) input');
			var qty_safety_stock  = $(this).parent().parent().find('td:nth-child(13) input');
			var point_month 	  = $(this).parent().parent().find('td:nth-child(14) input');
			var qty_reorder_point = $(this).parent().parent().find('td:nth-child(15) input');

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

		// Confirmation Save
		$(document).on('click', '#SaveForecast', function(e){
			e.preventDefault();
	        var btnSave  = "<button type='button' class='btn btn-warning' id='ProcessSave'>Yes, Save</button>";
	        var btnClose = "<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
	        $('.modal-dialog').removeClass('modal-lg');
	        $('.modal-dialog').addClass('modal-sm');
	        $('#modal-title').html('Konfirmasi');
	        $('#modal-body').html("Apakah Data Sudah Benar ?");
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
			var param = '&kdcab='+$('#kdcab').val();
			$.ajax({
				url: "<?php echo site_url('forecast/fc_new_save'); ?>",
				cache: false,
				type: "POST",
				data: $('#FormForecast').serialize()+param,
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
	});
</script>
<style type="text/css">
	.kdcab:focus{
		border: 1px solid #d2d6de;
	}
	table {
        display: block;
        overflow-x: auto;
        /*white-space: nowrap;*/
    }
    .form-control[readonly]{
    	background: #fff;
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
