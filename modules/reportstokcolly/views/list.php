<?php
    //$ENABLE_ADD     = has_permission('Reportstok.Add');
    //$ENABLE_MANAGE  = has_permission('Reportstok.Manage');
    $ENABLE_VIEW    = has_permission('Reportstokcolly.View');
    //$ENABLE_DELETE  = has_permission('Reportstok.Delete');
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="box-header"><b>Pilih Cabang : </b>
    <select id="kdcab" name="kdcab" class="form-control input-sm" style="width: 25%;" tabindex="-1" required onchange="getcabang(this.id)">
        <option value=""></option>
        <?php
          $cab='';
          foreach(@$cabang as $kc=>$vc){
            $selected = '';
            if($this->input->get('idcabang') == $vc->kdcab){
                 $selected = 'selected="selected"';
                 $cab = $vc->namacabang;
            }
            ?>
        <option value="<?php echo $vc->kdcab; ?>" <?php echo set_select('namacabang', $vc->kdcab, isset($data->namacabang) && $data->kdcab == $vc->kdcab) ?> <?php echo $selected?>>
          <?php echo $vc->kdcab.' , '.$vc->namacabang ?>
        </option>
        <?php } ?>
    </select>
		<?php //if ($ENABLE_ADD) : ?>

			<span class="pull-right">
				<?php echo anchor(site_url('reportstokcolly/downloadExcel').'?idcabang='.$this->input->get('idcabang'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
				<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>
			</span>

		<?php //endif; ?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1s" class="table table-bordered table-striped" width="100%">
		<thead>
		<tr>
			<th width="5">#</th>
      <th>Cabang</th>
			<th>Kode Produk</th>
			<th>Nama Set</th>
			<th>Jenis Produk</th>
			<th>Satuan</th>
			<th>Qty Stock</th>
			<!--<th>Qty Available</th>
			<th>Qty Rusak</th>
			<th>Landed Cost</th>
			<th>Harga</th>-->
			<th>Colly</th>
			<th>Qty</th>
		</tr>
		</thead>

		<tbody>
		<?php
		//print_r($results);
		if(empty($results)){ ?>
		<tr>
			<td colspan="9"><b>Tidak ada data stok</b></td>
		</tr>
		<?php
		}else{
			$numb=0; 
			foreach($results AS $record){
			 $numb++; 
			 $colly = $this->Reportstokcolly_model->get_data(array('id_barang' => $record->id_barang),'barang_koli');
             $rs = count($colly)+1;
		?>
		<tr>
			<?php
				if($record->satuan==''){
					$satuan = $record->setpcs;
				}else{
					$satuan = $record->satuan;
				}
			?>
		    <td rowspan="<?php echo $rs?>" valign="middle"><?= $numb; ?></td>
        	<td rowspan="<?php echo $rs?>" valign="middle"><?= $record->kdcab." , ".$record->namacabang ?></td>
	    	<td rowspan="<?php echo $rs?>" valign="middle"><?= $record->id_barang ?></td>
			<td rowspan="<?php echo $rs?>" valign="middle"><?= $record->nm_barang ?></td>
			<td rowspan="<?php echo $rs?>" valign="middle"><?= strtoupper($record->jenis) ?></td>
			<td rowspan="<?php echo $rs?>" valign="middle"><?= $satuan ?></td>
			<td rowspan="<?php echo $rs?>" valign="middle" class="text-center"><?= $record->qty_stock ?></td>
			<td colspan="2" style="border: none;height: 0px;padding: 0px;margin:0px;"></td>
		</tr>
		<?php
            $nc=1;
            foreach($colly as $kc=>$vc) {
                $ncl = $nc++;
                $q = $vs->qty_order;
        ?>
        <tr>
            <td style="padding-left: 10px;"><?php echo $vc->nm_koli?></td>
            <td><center><?php echo $record->qty_stock*$vc->qty?></center></td>
        </tr>
        <?php } ?>
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
			<!--<th>Qty Available</th>
			<th>Qty Rusak</th>
			<th>Landed Cost</th>
			<th>Harga</th>-->
			<th>Colly</th>
			<th>Qty</th>
		</tr>
		</tfoot>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-rekap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Rekap Stok Colly</h4>
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

<!-- Modal -->
<div class="modal modal-primary" id="modalDetailColly" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Detail Colly</h4>
      </div>
      <div class="modal-body" id="MyModalBody"></div>
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
  		/*
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
		*/
	    var table = $('#example1').DataTable( {
	        orderCellsTop: true,
	        fixedHeader: true
	    } );
  	});
  	function detailcolly(id){
  		$('#modalDetailColly').modal('show');
  	}
    function getcabang(a){
          var cabang = $('#kdcab').val();
          //$('#example1').DataTable().column(1).search(cabang).draw();
          window.location.href = siteurl+'reportstokcolly?idcabang='+cabang;
        }

	function PreviewPdf(id)
	{
		param=id;
		tujuan = 'setup_stock/print_request/'+param;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
	}

	function PreviewRekap()
	{
		tujuan = 'reportstokcolly/print_stok_colly';
	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
	}

</script>
