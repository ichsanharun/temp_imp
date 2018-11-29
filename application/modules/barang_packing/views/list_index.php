<?php
    $ENABLE_ADD     = has_permission('Barang.Add');
    $ENABLE_MANAGE  = has_permission('Barang.Manage');
    $ENABLE_VIEW    = has_permission('Barang.View');
    $ENABLE_DELETE  = has_permission('Barang.Delete');
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#produk" data-toggle="tab" aria-expanded="true" id="data">Produk Set</a></li>
        <li class=""><a href="#koli" data-toggle="tab" aria-expanded="false" id="data_koli">Produk Koli</a></li>
        <li class=""><a href="#komponen" data-toggle="tab" aria-expanded="false" id="data_komponen">Produk Komponen</a></li>
    </ul>
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="produk">
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_barang','name'=>'frm_barang','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="example1" class="table table-bordered table-striped">
                		<thead>
                  		<tr>
                  			<th width="5">#</th>
                  			<th>Kode Produk</th>
                  			<th>Jenis Produk</th>
                  			<th>Group Produk</th>
                  			<th>Nama Set</th>
                  			<th>Supplier</th>
                  			<th>Satuan</th>
                  			<th>Qty</th>
                  			<th>Status</th>
                  			<?php if($ENABLE_MANAGE) : ?>
                  			<th width="50">Action</th>
                  			<?php endif; ?>
                  		</tr>
                		</thead>

              		<tbody>
                		<?php if(empty($results)){
                		}else{
                			$numb=0; foreach($results AS $record){ $numb++; ?>
                		<tr>
                			<?php
                				if($record->satuan==''){
                					$satuan = $record->setpcs;
                				}else{
                					$satuan = $record->satuan;
                				}
                			?>
                		    <td><?= $numb; ?></td>
                	        <td><?= $record->id_barang ?></td>

                			<td><?= strtoupper($record->nm_jenis) ?></td>
                			<td><?= strtoupper($record->nm_group) ?></td>
                			<td><?= $record->nm_barang ?></td>
                			<td><?= $record->nm_supplier ?></td>
                			<td><?= $satuan ?></td>
                			<td><?= $record->qty_stock ?></td>
                			<td>
                				<?php if($record->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                  			<?php if($ENABLE_MANAGE) : ?>
                  				<a class="btn bg-primary btn-sm" href="javascript:void(0)" title="Edit" onclick="unpacking('<?=$record->id_barang?>')">
                            Unpacking
                  				</a>
                  			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Produk</th>
                			<th>Jenis Produk</th>
                			<th>Group Produk</th>
                			<th>Nama Set</th>
                			<th>Supplier</th>
                			<th>Satuan</th>
                			<th>Qty</th>
                			<th>Status</th>
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

        <div class="tab-pane" id="koli">
          <!-- Data Koli -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_koli','name'=>'frm_koli','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="table_koli" class="table table-bordered table-striped">
                		<thead>
                  		<tr>
                  			<th width="5">#</th>
                  			<th>Kode Produk</th>
                  			<th>Jenis Produk</th>
                  			<th>Group Produk</th>
                  			<th>Nama Set</th>
                  			<th>Supplier</th>
                  			<th>Satuan</th>
                  			<th>Qty</th>
                  			<th>Status</th>
                  			<?php if($ENABLE_MANAGE) : ?>
                  			<th width="50">Action</th>
                  			<?php endif; ?>
                  		</tr>
                		</thead>

              		<tbody>
                		<?php if(empty($colly)){
                		}else{
                			$numb=0; foreach($colly AS $record_colly){ $numb++; ?>
                		<tr>
                			<?php
                				if($record_colly->satuan==''){
                					$satuan = $record_colly->setpcs;
                				}else{
                					$satuan = $record_colly->satuan;
                				}
                			?>
                		    <td><?= $numb; ?></td>
                	        <td><?= $record_colly->id_barang ?></td>

                			<td><?= strtoupper($record_colly->nm_jenis) ?></td>
                			<td><?= strtoupper($record_colly->nm_group) ?></td>
                			<td><?= $record_colly->nm_barang ?></td>
                			<td><?= $record_colly->nm_supplier ?></td>
                			<td><?= $satuan ?></td>
                			<td><?= $record_colly->qty_stock ?></td>
                			<td>
                				<?php if($record_colly->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                  			<?php if($ENABLE_MANAGE) : ?>
                  				<a class="btn bg-primary btn-sm" href="javascript:void(0)" title="Edit" onclick="unpacking('<?=$record_colly->id_barang?>')">
                            Unpacking
                  				</a>
                  			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Produk</th>
                			<th>Jenis Produk</th>
                			<th>Group Produk</th>
                			<th>Nama Set</th>
                			<th>Supplier</th>
                			<th>Satuan</th>
                			<th>Qty</th>
                			<th>Status</th>
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

        <div class="tab-pane" id="komponen">
          <!-- Data komponen -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open_multipart($this->uri->uri_string(),array('id'=>'frm_komponen','name'=>'frm_komponen','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                  <table id="table_komponen" class="table table-bordered table-striped">
                		<thead>
                  		<tr>
                  			<th width="5">#</th>
                  			<th>Kode Produk</th>
                  			<th>Jenis Produk</th>
                  			<th>Group Produk</th>
                  			<th>Nama Set</th>
                  			<th>Supplier</th>
                  			<th>Satuan</th>
                  			<th>Qty</th>
                  			<th>Status</th>
                  			<?php if($ENABLE_MANAGE) : ?>
                  			<th width="50">Action</th>
                  			<?php endif; ?>
                  		</tr>
                		</thead>

              		<tbody>
                		<?php if(empty($component)){
                		}else{
                			$numb=0; foreach($component AS $record){ $numb++; ?>
                		<tr>
                			<?php
                				if($record->satuan==''){
                					$satuan = $record->setpcs;
                				}else{
                					$satuan = $record->satuan;
                				}
                			?>
                		    <td><?= $numb; ?></td>
                	        <td><?= $record->id_barang ?></td>

                			<td><?= strtoupper($record->nm_jenis) ?></td>
                			<td><?= strtoupper($record->nm_group) ?></td>
                			<td><?= $record->nm_barang ?></td>
                			<td><?= $record->nm_supplier ?></td>
                			<td><?= $satuan ?></td>
                			<td><?= $record->qty_stock ?></td>
                			<td>
                				<?php if($record->sts_aktif == 'aktif'){ ?>
                					<label class="label label-success">Aktif</label>
                				<?php }else{ ?>
                					<label class="label label-danger">Non Aktif</label>
                				<?php } ?>
                			</td>
                			<td style="padding-left:20px">
                  			<?php if($ENABLE_MANAGE) : ?>
                  				<a class="btn bg-primary btn-sm" href="javascript:void(0)" title="Edit" onclick="unpacking('<?=$record->id_barang?>')">
                            Unpacking
                  				</a>
                  			<?php endif; ?>
                			</td>
                		</tr>
                		<?php } }  ?>
              		</tbody>

              		<tfoot>
                		<tr>
                			<th width="5">#</th>
                			<th>Kode Produk</th>
                			<th>Jenis Produk</th>
                			<th>Group Produk</th>
                			<th>Nama Set</th>
                			<th>Supplier</th>
                			<th>Satuan</th>
                			<th>Qty</th>
                			<th>Status</th>
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

          <!-- Data Komponen -->
        </div>
    </div>
    <!-- /.tab-content -->
</div>
<!-- Modal Bidus-->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<!-- End Modal Bidus-->
<script type="text/javascript">


    $(function() {
      //$("#example1").DataTable();
    	$('#example1 thead tr').clone(true).appendTo( '#example1 thead' );
	    $('#example1 thead tr:eq(1) th').each( function (i) {
	        var title = $(this).text();
	        //alert(title);
	        if (title == "#" || title =="Action" ) {
	        	$(this).html( '' );
	        }else{
	        	$(this).html( '<input type="text" />' );
	        }

	        $( 'input', this ).on( 'keyup change', function () {
	            if ( table.column(i).search() !== this.value ) {
	                table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }else{
	            	table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );
      $('#table_koli thead tr').clone(true).appendTo( '#table_koli thead' );
	    $('#table_koli thead tr:eq(1) th').each( function (i) {
	        var title = $(this).text();
	        //alert(title);
	        if (title == "#" || title =="Action" ) {
	        	$(this).html( '' );
	        }else{
	        	$(this).html( '<input type="text" />' );
	        }

	        $( 'input', this ).on( 'keyup change', function () {
	            if ( table.column(i).search() !== this.value ) {
	                table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }else{
	            	table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );
      $('#table_komponen thead tr').clone(true).appendTo( '#table_komponen thead' );
	    $('#table_komponen thead tr:eq(1) th').each( function (i) {
	        var title = $(this).text();
	        //alert(title);
	        if (title == "#" || title =="Action" ) {
	        	$(this).html( '' );
	        }else{
	        	$(this).html( '<input type="text" />' );
	        }

	        $( 'input', this ).on( 'keyup change', function () {
	            if ( table.column(i).search() !== this.value ) {
	                table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }else{
	            	table
	                    .column(i)
	                    .search( this.value )
	                    .draw();
	            }
	        } );
	    } );

	    var table = $('#example1,#table_koli,#table_komponen').DataTable( {
	        orderCellsTop: true,
	        fixedHeader: true
	    } );
    	$("#form-area").hide();
      $("#button_submit").hide();
  	});


    function unpacking(kodebarang){
      if(kodebarang!=""){
        var url = 'barang_packing/edit/'+kodebarang;
        $(".box").hide();
        $("#form-area").show();
        $("#button_submit").show();
        $("#form-area").load(siteurl+url);

          $("#title").focus();
      }
    }
    function kembali_up(){
        $(".box").show();
        $("#form-area").hide();
        $("#button_submit").hide();
        $("#form-area").unload();

          $("#title").focus();

    }
    function saveunpacking(){
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
            var formdata = $("#form-unpacking").serialize();
            $.ajax({
                url: siteurl+"barang_packing/saveunpacking",
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
                            window.location.href=siteurl+'barang_packing';
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
