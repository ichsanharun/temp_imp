<?php
    $ENABLE_ADD     = has_permission('Diskonmaster.Add');
    $ENABLE_MANAGE  = has_permission('Diskonmaster.Manage');
    $ENABLE_VIEW    = has_permission('Diskonmaster.View');
    $ENABLE_DELETE  = has_permission('Diskonmaster.Delete');
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#diskon_barang" data-toggle="tab" aria-expanded="true" id="data">Diskon Barang</a></li>
        <li class=""><a href="#diskon_customer" data-toggle="tab" aria-expanded="false" id="data_koli">Diskon Customer</a></li>
        <li class=""><a href="#diskon_khusus" data-toggle="tab" aria-expanded="false" id="data_komponen">Diskon Khusus</a></li>
        <li class=""><a href="#ket_dis" data-toggle="tab" aria-expanded="false" id="ket-dis">Ketentuan Diskon</a></li>
    </ul>
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="diskon_barang">
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_barang','name'=>'frm_barang','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="data_barang" class="table table-bordered table-striped">
                		<thead>
                  		<tr>
                  			<th width="5">#</th>
                  			<th>Kode Produk</th>
                  			<th>Nama Produk</th>
                  			<th>Satuan</th>
                  			<th>Diskon Std.</th>
                        <th>Diskon Promo(%)</th>
                  			<?php if($ENABLE_MANAGE) : ?>
                  			<th width="50">Action</th>
                  			<?php endif; ?>
                  		</tr>
                		</thead>

              		<tbody>
                		<?php
                    $n = 0;
                    foreach($data_barang AS $record){ $n++; ?>
                		<tr>
                			<?php
                				if($record->satuan==''){
                					$satuan = $record->setpcs;
                				}else{
                					$satuan = $record->satuan;
                				}
                			?>
                		  <td><?= $n; ?></td>
                	    <td><?= $record->id_barang ?></td>
                			<td><?= $record->nm_barang ?></td>
                			<td><?= $satuan ?></td>
                			<td><?= $record->diskon_standar_persen ?></td>
                			<td>
                				<?= $record->diskon_promo_persen ?>
                			</td>
                			<td style="padding-left:20px">
                  			<?php if($ENABLE_MANAGE) : ?>
                  				<a class="btn bg-primary btn-sm" href="#edit_diskon" data-toggle="modal" title="Edit" onclick="edit('<?=$record->id_barang?>','barang')">
                            Edit
                  				</a>
                  			<?php endif; ?>
                			</td>
                		</tr>
                		<?php }  ?>
              		</tbody>

              		<tfoot>
                    <tr>
                      <th width="5">#</th>
                      <th>Kode Produk</th>
                      <th>Nama Produk</th>
                      <th>Satuan</th>
                      <th>Diskon Std.</th>
                      <th>Diskon Promo(%)</th>
                      <?php if($ENABLE_MANAGE) : ?>
                      <th width="50">Action</th>
                      <?php endif; ?>
                    </tr>
              		</tfoot>
              		</table>


                </div>
            <?= form_close() ?>
            </div>

            <div id="form-area">

            </div>
            <div class="text-right" colspan="12" id="button_submit">
                <button class="btn btn-danger" onclick="kembali_up()">
                    <i class="fa fa-refresh"></i><b> Kembali</b>
                </button>
                <button class="btn btn-primary" type="button" onclick="saveunpacking()">
                    <i class="fa fa-save"></i><b> Simpan Data Unpacking</b>
                </button>
            </div>
          <!-- Data Produk -->
        </div>

        <div class="tab-pane" id="diskon_customer">
          <!-- Data Koli -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_koli','name'=>'frm_koli','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="data_customer" class="table table-bordered table-striped">
                		<thead>
                  		<tr>
                  			<th width="5">#</th>
                  			<th>ID Customer</th>
                  			<th>Nama Customer</th>
                  			<th>Bid. Usaha</th>
                  			<th>Diskon</th>
                  			<?php if($ENABLE_MANAGE) : ?>
                  			<th width="50">Action</th>
                  			<?php endif; ?>
                  		</tr>
                		</thead>

              		<tbody>
                		<?php
                    $n=0;
                    foreach($data_customer AS $record){ $n++; ?>
                		<tr>
                			<td><?= $n; ?></td>
                	    <td><?= $record->id_customer ?></td>
                			<td><?= strtoupper($record->nm_customer) ?></td>
                			<td><?= strtoupper($record->bidang_usaha) ?></td>
                			<td><?= $record->diskon_toko ?></td>
                			<td style="padding-left:20px">
                  			<?php if($ENABLE_MANAGE) : ?>
                  				<a class="btn bg-primary btn-sm" href="#edit_diskon" data-toggle="modal" title="Edit" onclick="edit('<?=$record->id_customer?>','customer')">
                            Edit
                  				</a>
                  			<?php endif; ?>
                			</td>
                		</tr>
                		<?php }  ?>
              		</tbody>

              		<tfoot>
                    <tr>
                      <th width="5">#</th>
                      <th>ID Customer</th>
                      <th>Nama Customer</th>
                      <th>Bid. Usaha</th>
                      <th>Diskon</th>
                      <?php if($ENABLE_MANAGE) : ?>
                      <th width="50">Action</th>
                      <?php endif; ?>
                    </tr>
              		</tfoot>
              		</table>


                </div>
            <?= form_close() ?>
            </div>

            <div id="form-area-colly">

            </div>
          <!-- Data Koli -->
        </div>

        <div class="tab-pane" id="diskon_khusus">
          <!-- Data Diskon -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_komponen','name'=>'frm_komponen','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="data_diskon" class="table table-bordered table-striped">
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
                			$n=1;
                			foreach($data_diskon as $vd){
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
                					<a class="text-green" href="#edit_diskon" data-toggle="modal" title="Edit" onclick="edit('<?php echo $vd->id_diskon?>','data_diskon')"><i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vd->id_diskon?>')"><i class="fa fa-trash"></i>
                                    </a>
                				</td>
                			</tr>
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
            <?= form_close() ?>
            </div>

            <div id="form-area">

            </div>

          <!-- Data Komponen -->
        </div>

        <div class="tab-pane" id="ket_dis">
          <!-- Data Diskon -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_komponen','name'=>'frm_komponen','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="data_ket_dis" class="table table-bordered table-striped">
                		<thead>
                      <tr>
                  			<th width="1%">#</th>
                  			<th>Nama Bidang Usaha</th>
                  			<th>Ketentuan Diskon</th>
                  			<th>Status</th>
                  			<th width="8%">Aksi</th>
                  		</tr>
                		</thead>

                		<tbody>
                      <?php
                  			$n=1;
                  			foreach($data_diskon as $vd){
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
                  					<a class="text-green" href="#edit_diskon" data-toggle="modal" title="Edit" onclick="edit('<?php echo $vd->id_diskon?>','data_diskon')"><i class="fa fa-pencil"></i>
                                      </a>
                                      <a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vd->id_diskon?>')"><i class="fa fa-trash"></i>
                                      </a>
                  				</td>
                  			</tr>
                  		<?php } ?>
                		</tbody>

                		<tfoot>
                      <tr>
                  			<th width="1%">#</th>
                  			<th>Nama Bidang Usaha</th>
                  			<th>Ketentuan Diskon</th>
                  			<th>Status</th>
                  			<th width="8%">Aksi</th>
                  		</tr>
                		</tfoot>
              		</table>


                </div>
            <?= form_close() ?>
            </div>

            <div id="form-area">

            </div>

        </div>

    </div>
    <!-- /.tab-content -->
</div>

<div class="modal modal-primary" id="edit_diskon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Edit Diskon Barang</h4>
      </div>
      <div class="modal-body" id="Modals">
		...
      </div>
      <div class="modal-footer">
        <button type="button" name="save" class="btn btn-info" id="submit" onclick="save()">Simpan</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>
<!-- Modal Bidus-->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<!-- End Modal Bidus-->
<script type="text/javascript">


    $(function() {
    	$("#form-area").hide();
      $("#button_submit").hide();
  	});

    function edit(id,data){
      url = siteurl+'diskon_master/edit/'+id;
      $.post(url,{'ID':id,'data':data},function(result){
        $("#Modals").html(result);
      });
    }


    function save(){
        swal({
          title: "Peringatan !",
          text: "Pastikan data sudah lengkap dan benar",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, simpan!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            var formdata = $("#frm-diskon").serialize();
            $.ajax({
                url: siteurl+"diskon_master/savediskon",
                dataType : "json",
                type: 'POST',
                data: formdata,
                success: function(result){
                    if(result.save=='1'){
                        swal({
                            title: "Sukses!",
                            text: JSON.stringify(result['msg']),
                            type: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                            window.location.href=siteurl+'diskon_master';
                        },1600);
                    } else {
                        swal({
                            title: "Gagal!",
                            text: "Data Gagal Di Simpan",
                            type: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    };
                },
                error: function(){
                    swal({
                        title: "Gagal!",
                        text: "Ajax Data Gagal Di Proses",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
        });
    }
</script>
