<?php 
    $ENABLE_ADD     = has_permission('Koli.Add');
    $ENABLE_MANAGE  = has_permission('Koli.Manage');
    $ENABLE_VIEW    = has_permission('Koli.View');
    $ENABLE_DELETE  = has_permission('Koli.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="box-header">
		<?php if ($ENABLE_ADD) : ?>		
			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>			
		<?php endif; ?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<?php print_r(@$results)?>
		<table id="example1" class="table table-bordered table-striped" width="100%">
		<thead>
		<tr>
			<th width="2%">#</th>
			<th width="10%">Kode</th>
			<th width="10%">No. Invoice</th>
			<th width="10%">Jenis Bayar</th>
			<th width="10%">Nama Bank</th>			
			<th width="10%">No. Reff</th>
			<th width="10%">Nominal</th>
			<th width="5%">Status</th>
			<th width="5%">Aksi</th>
		</tr>
		</thead>
        
		<tbody>
			<?php
			$n=1;
			if(@$results){
			foreach(@$results as $k=>$v){
				$no=$n++;
			?>
			<tr>
				<td><center><?php echo $no?></center></td>
				<td><center><?php echo $v->kd_pembayaran?></center></td>
				<td><center><?php echo $v->no_invoice?></center></td>
				<td><center><?php echo $v->jenis_reff?></center></td>
				<td><center><?php echo $v->nm_bank?></center></td>
				<td><center><?php echo $v->no_reff?></center></td>
				<td style="text-align: right;"><?php echo formatnomor($v->jumlah_pembayaran)?></td>
				<td><center><span class="badge bg-green"><?php echo $v->status_bayar?></span></center></td>
				<td>
					<center>
						<button class="btn btn-xs btn-warning" type="button" onclick="setpelunasan('<?php echo $v->kd_pembayaran?>','<?php echo $v->no_invoice?>')">
							<i class="fa fa-check"></i> Pelunasan
						</button>
					</center>
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
		</tbody>
		
		<tfoot>
		<tr>
			<th width="2%">#</th>
			<th width="15%">Kode</th>
			<th width="10%">No. Invoice</th>
			<th width="10%">Jenis Bayar</th>
			<th width="10%">Nama Bank</th>			
			<th width="10%">No. Reff</th>
			<th width="10%">Nominal</th>
			<th width="5%">Status</th>
			<th width="5%">Aksi</th>
		</tr>
		</tfoot>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup-pelunasan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-money"></span>&nbsp;Pelunasan Pembayaran Piutang</h4>
      </div>
      <div class="modal-body" id="MyModalBodyPelunasan" style="background: #FFF !important;color:#000 !important;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="prosespelunasan()">
        <span class="glyphicon glyphicon-save"></span>  Proses Data Pelunasan</button>
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

  	function PreviewPdf(id)
	{
		param=id;
		tujuan = 'koli/print_request/'+param;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
	}

	function setpelunasan(kd,inv){
		$('#dialog-popup-pelunasan').modal('show');
		pelunasanpiutang(kd,inv);
	}

	function pelunasanpiutang(kd,inv){
		var url = siteurl+'pelunasan/pelunasanpiutang';
	    $.post(url,{'KD':kd,'NO_INV':inv},function(result){
	      $('#MyModalBodyPelunasan').html(result);
	    });
	}

</script>