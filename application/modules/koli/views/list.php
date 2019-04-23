<?php
    $ENABLE_ADD     = has_permission('Koli.Add');
    $ENABLE_MANAGE  = has_permission('Koli.Manage');
    $ENABLE_VIEW    = has_permission('Koli.View');
    $ENABLE_DELETE  = has_permission('Koli.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">


<div class="nav-tabs-custom" style="overflow:auto !important">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#master_koli" data-toggle="tab" aria-expanded="true" id="data">Master Koli</a></li>
        <li class=""><a href="#koli_model" data-toggle="tab" aria-expanded="false" id="data_koli">Koli Model</a></li>
        <li class=""><a href="#koli_warna" data-toggle="tab" aria-expanded="false" id="data_komponen">Koli Warna</a></li>
        <li class=""><a href="#koli_varian" data-toggle="tab" aria-expanded="false" id="ket-dis">Koli Varian</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="master_koli">

            <div class="box">
            	<div class="box-header">
            		<?php if ($ENABLE_ADD) : ?>
            			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data_master()"><i class="fa fa-plus">&nbsp;</i>New</a>
            		<?php endif; ?>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<table id="example1" class="table table-bordered table-striped">
              		<thead>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Koli</th>
                			<th>Nama Koli</th>
                      <th>Model</th>
                      <th>Warna</th>
                      <th>Varian</th>
                			<th>Kode Barang</th>
                			<th>Nama Barang</th>
                			<th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</thead>

              		<tbody>
                		<?php if(empty($results)){
                		}else{
                			$numb=0; foreach($results AS $record){ $numb++; ?>
                		<tr>
                		    <td><?= $numb; ?></td>
                			<td><?= $record->id_koli ?></td>
                			<td><?= $record->nm_koli ?></td>
                      <td><?= $record->koli_model ?></td>
                      <td><?= $record->koli_warna ?></td>
                      <td><?= $record->koli_varian ?></td>
                			<td><?= $record->id_barang ?></td>
                			<td><?= $record->nm_barang ?></td>
                			<td>
                				<?php if($record->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                			<?php if($ENABLE_VIEW) : ?>
                				<a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?=$record->id_koli?>')">
                				<span class="glyphicon glyphicon-print"></span>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_MANAGE) : ?>
                				<a class="text-green" href="javascript:void(0)" title="Edit" onclick="edit_master('<?=$record->id_koli?>')"><i class="fa fa-pencil"></i>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_DELETE) : ?>
                				<a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?=$record->id_koli?>','koli')"><i class="fa fa-trash"></i>
                				</a>
                			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Koli</th>
                			<th>Nama Koli</th>
                      <th>Model</th>
                      <th>Warna</th>
                      <th>Varian</th>
                			<th>Kode Barang</th>
                			<th>Nama Barang</th>
                			<th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</tfoot>
            		</table>
            	</div>
            	<!-- /.box-body -->
            </div>

            <div id="form-area-master">

            </div>

        </div>

        <div class="tab-pane" id="koli_model">

            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>

            <div class="box">
            	<div class="box-header">
            		<?php if ($ENABLE_ADD) : ?>
            			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="data_change('add','model')"><i class="fa fa-plus">&nbsp;</i>New</a>
            		<?php endif; ?>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<table id="tabel_model" class="table table-bordered table-striped">
              		<thead>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Model</th>
                			<th>Nama Model</th>
                      <th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</thead>

              		<tbody>
                		<?php if(empty($results)){
                		}else{
                			$numb=0; foreach($model AS $record){ $numb++; ?>
                		<tr>
                		    <td><?= $numb; ?></td>
                			<td><?= $record->id_koli_model ?></td>
                			<td><?= $record->koli_model ?></td>
                      <td>
                				<?php if($record->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                			<?php if($ENABLE_VIEW) : ?>
                				<a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?=$record->id_koli_model?>')">
                				<span class="glyphicon glyphicon-print"></span>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_MANAGE) : ?>
                				<a class="text-green" href="javascript:void(0)" title="Edit" onclick="data_change('<?=$record->id_koli_model?>','model')"><i class="fa fa-pencil"></i>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_DELETE) : ?>
                				<a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?=$record->id_koli_model?>','model')"><i class="fa fa-trash"></i>
                				</a>
                			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                      <th width="5">#</th>
                			<th>Kode Model</th>
                			<th>Nama Model</th>
                      <th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</tfoot>
            		</table>
            	</div>
            	<!-- /.box-body -->
            </div>

            <div id="form-area-model">

            </div>

        </div>

        <div class="tab-pane" id="koli_warna">


            <div class="box">
            	<div class="box-header">
            		<?php if ($ENABLE_ADD) : ?>
            			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="data_change('add','warna')"><i class="fa fa-plus">&nbsp;</i>New</a>
            		<?php endif; ?>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<table id="example1" class="table table-bordered table-striped">
              		<thead>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Varian</th>
                			<th>Nama Varian</th>
                      <th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</thead>

              		<tbody>
                		<?php if(empty($warna)){
                		}else{
                			$numb=0; foreach($warna AS $record){ $numb++; ?>
                		<tr>
                		    <td><?= $numb; ?></td>
                			<td><?= $record->id_koli_warna ?></td>
                			<td><?= $record->koli_warna ?></td>
                      <td>
                				<?php if($record->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                			<?php if($ENABLE_VIEW) : ?>
                				<a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?=$record->id_koli_warna?>')">
                				<span class="glyphicon glyphicon-print"></span>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_MANAGE) : ?>
                				<a class="text-green" href="javascript:void(0)" title="Edit" onclick="data_change('<?=$record->id_koli_warna?>','warna')"><i class="fa fa-pencil"></i>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_DELETE) : ?>
                				<a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?=$record->id_koli_warna?>','warna')"><i class="fa fa-trash"></i>
                				</a>
                			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                      <th width="5">#</th>
                			<th>Kode Warna</th>
                			<th>Nama Warna</th>
                      <th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</tfoot>
            		</table>
            	</div>
            	<!-- /.box-body -->
            </div>

            <div id="form-area-warna">

            </div>

        </div>

        <div class="tab-pane" id="koli_varian">

            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>


            <div class="box">
            	<div class="box-header">
            		<?php if ($ENABLE_ADD) : ?>
            			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="data_change('add','varian')"><i class="fa fa-plus">&nbsp;</i>New</a>
            		<?php endif; ?>
            	</div>
            	<!-- /.box-header -->
            	<div class="box-body">
            		<table id="example1" class="table table-bordered table-striped">
              		<thead>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Varian</th>
                			<th>Nama Varian</th>
                      <th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</thead>

              		<tbody>
                		<?php if(empty($varian)){
                		}else{
                			$numb=0; foreach($varian AS $record){ $numb++; ?>
                		<tr>
                		    <td><?= $numb; ?></td>
                			<td><?= $record->id_koli_varian ?></td>
                			<td><?= $record->koli_varian ?></td>
                      <td>
                				<?php if($record->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                			<?php if($ENABLE_VIEW) : ?>
                				<a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?=$record->id_koli_varian?>')">
                				<span class="glyphicon glyphicon-print"></span>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_MANAGE) : ?>
                				<a class="text-green" href="javascript:void(0)" title="Edit" onclick="data_change('<?=$record->id_koli_varian?>','varian')"><i class="fa fa-pencil"></i>
                				</a>
                			<?php endif; ?>

                			<?php if($ENABLE_DELETE) : ?>
                				<a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?=$record->id_koli_varian?>','varian')"><i class="fa fa-trash"></i>
                				</a>
                			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                      <th width="5">#</th>
                			<th>Kode Varian</th>
                			<th>Nama Varian</th>
                      <th>Status</th>
                			<?php if($ENABLE_MANAGE) : ?>
                			<th width="50"></th>
                			<?php endif; ?>
                		</tr>
              		</tfoot>
            		</table>
            	</div>
            	<!-- /.box-body -->
            </div>

            <div id="form-area-varian">

            </div>
          <!-- Data Koli -->
        </div>


    </div>
    <!-- /.tab-content -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Koli Barang</h4>
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
    	$("#example1,#tabel_model").DataTable();
    	$("#form-area-master,#form-area-model").hide();
  	});

  	function add_data_master(){
			var url = 'koli/create/';
			$(".box").hide();
			$("#form-area-master").show();

			$("#form-area-master").load(siteurl+url);
		  $("#title").focus();
	  }

  function edit_master(kodebarang){
		if(kodebarang!=""){
			var url = 'koli/edit/'+kodebarang;
			$(".box").hide();
			$("#form-area-master").show();

			$("#form-area-master").load(siteurl+url);
		  $("#title").focus();
		}
	}

  function data_change(act,tipe){
    if (act == 'add') {
      var url = 'koli/create_anak/'+tipe;
      $(".box").hide();
      $("#form-area-"+tipe).show();

      $("#form-area-"+tipe).load(siteurl+url);

      $("#title").focus();
    }else {
      if(act !=""){
  			var url = 'koli/edit_anak/'+tipe+'/'+act;
  			$(".box").hide();
  			$("#form-area-"+tipe).show();

  			$("#form-area-"+tipe).load(siteurl+url);

  		    $("#title").focus();
  		}
    }

  }

	//Delete
	function delete_data(id,tipe){
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
		            url: siteurl+'koli/hapus_koli/'+id+'/'+tipe,
		            dataType : "json",
		            type: 'POST',
		            success: function(msg){
		                if(msg['delete']=='1'){
		                    //swal("Terhapus!", "Data berhasil dihapus.", "success");
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

	function PreviewPdf(id)
	{
		param=id;
		tujuan = 'koli/print_request/'+param;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
	}

</script>
