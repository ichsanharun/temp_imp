<?php
    $ENABLE_ADD     = has_permission('Reportstok.Add');
    $ENABLE_MANAGE  = has_permission('Reportstok.Manage');
    $ENABLE_VIEW    = has_permission('Reportstok.View');
    $ENABLE_DELETE  = has_permission('Reportstok.Delete');
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="box-header"><b>Pilih Cabang : </b>
    <select id="kdcab" name="kdcab" class="form-control input-sm" style="width: 25%;" tabindex="-1" required onchange="getcabang(this.id)" disabled="disabled">
        <option value=""></option>
        <?php
          $cab='';
          foreach(@$cabang as $kc=>$vc){
            $selected = '';
            $session = $this->session->userdata('app_session');
            $kdcab = $session['kdcab'];
            if($this->uri->segment(3) == $vc->kdcab){
                 $selected = 'selected="selected"';
                 $cab = $vc->namacabang;
            }
            if($kdcab == $vc->kdcab){
                 $selected = 'selected="selected"';
                 $cab = $vc->namacabang;
            }
            ?>
        <option value="<?php echo $vc->kdcab; ?>" <?php echo set_select('namacabang', $vc->kdcab, isset($data->namacabang) && $data->kdcab == $vc->kdcab) ?> <?php echo $selected?>>
          <?php echo $vc->kdcab.' , '.$vc->namacabang ?>
        </option>
        <?php } ?>
    </select>
		<?php if ($ENABLE_ADD) : ?>

			<span class="pull-right">
				<?php echo anchor(site_url('reportstok/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
				<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>
				<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekapGroup()"><i class="fa fa-print">&nbsp;</i>STOK GROUP</a>
			</span>

		<?php endif; ?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th width="5">#</th>
      <th>Cabang</th>
			<th>Kode Produk</th>
			<th>Nama Set</th>
			<th>Jenis Produk</th>
			<th>Satuan</th>
			<th>Qty Stock</th>
			<th>Qty Available</th>
			<th>Qty Rusak</th>
			<th>Landed Cost</th>
			<th>Harga</th>
      <th>Persediaan</th>
			<th>Status</th>
			<?php if($ENABLE_MANAGE) : ?>
			<th width="50">Action</th>
			<?php endif; ?>
		</tr>
		</thead>

		<tbody>
		<?php
		//print_r($results);
		if(empty($results)){
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
        <td><?= $record->kdcab." , ".$record->namacabang ?></td>
	    <td><?= $record->id_barang ?></td>
			<td><?= $record->nm_barang ?></td>
			<td><?= strtoupper($record->jenis) ?></td>
			<td><?= $satuan ?></td>
			<td><?= $record->qty_stock ?></td>
			<td><?= $record->qty_avl ?></td>
			<td><?= $record->qty_rusak ?></td>
			<td><?= number_format($record->landed_cost) ?></td>
			<td><?= number_format($record->harga) ?></td>
      <td><?= "Rp ".number_format($record->landed_cost*$record->qty_stock) ?></td>
			<td>
				<?php if($record->sts_aktif == 'aktif'){ ?>
					<label class="label label-success">Aktif</label>
				<?php }else{ ?>
					<label class="label label-danger">Non Aktif</label>
				<?php } ?>
			</td>
			<td style="padding-left:20px">
			<?php if($ENABLE_VIEW) : ?>
				<a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?=$record->id_barang?>')">
				<span class="glyphicon glyphicon-print"></span>
				</a>
			<?php endif; ?>

			<?php if($ENABLE_DELETE) : ?>
				<a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?=$record->id_barang?>')"><i class="fa fa-trash"></i>
				</a>
			<?php endif; ?>
			</td>
		</tr>
		<?php } }  ?>
		</tbody>

		<tfoot>
		<tr>
			<th width="5">#</th>
      <th>Cabang</th>
			<th>Kode Produk</th>
			<th>Nama Set</th>
			<th>Jenis Produk</th>
			<th>Satuan</th>
			<th>Qty Stock</th>
			<th>Qty Available</th>
			<th>Qty Rusak</th>
			<th>Landed Cost</th>
			<th>Harga</th>
			<th>Persediaan</th>
      <th>Status</th>
			<?php if($ENABLE_MANAGE) : ?>
			<th width="50">Action</th>
			<?php endif; ?>
		</tr>
		</tfoot>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<div id="form-area">
<?= $this->load->view('barang/barang_form') ?>
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-rekap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Rekap Produk</h4>
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

<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Produk</h4>
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
$(document).ready(function(){
    $("#kdcab").select2({
        placeholder: "Pilih",
        allowClear: true
    });

});
  	$(function() {
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

	    var table = $('#example1').DataTable( {
	        orderCellsTop: true,
	        fixedHeader: true
	    } );
    	$("#form-area").hide();
  	});
    function getcabang(a){
          var cabang = $('#kdcab').val();
          //var tabledetail = $('#example1').DataTable();
          //alert(cabang);
          $('#example1').DataTable().column(1).search(cabang).draw();
          //window.location.href = siteurl+"reportso/filter/"+type_so;
        }

  	function add_data(){

			var url = 'setup_stock/create/';
			$(".box").hide();
			$("#form-area").show();

			$("#form-area").load(siteurl+url);

		    $("#title").focus();

	}

  	function edit_data(kodebarang){
		if(kodebarang!=""){
			var url = 'setup_stock/edit/'+kodebarang;
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
		            url: siteurl+'setup_stock/hapus_barang/'+id,
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
		tujuan = 'setup_stock/print_request/'+param;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
	}

	function PreviewRekap()
	{
		tujuan = 'reportstok/print_rekap';
	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
	}

	function PreviewRekapGroup()
	{
		tujuan = 'reportstok/print_rekap_group';
	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
	}

</script>
