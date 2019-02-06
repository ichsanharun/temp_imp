<?php 
/*
    $ENABLE_ADD     = has_permission('Koli.Add');
    $ENABLE_MANAGE  = has_permission('Koli.Manage');
    $ENABLE_VIEW    = has_permission('Koli.View');
    $ENABLE_DELETE  = has_permission('Koli.Delete');
    */
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="box-header">
		<?php //if ($ENABLE_ADD) : ?>
			<div class="col-sm-1">		
			<a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
			</div>
			<div class="col-sm-2">
			<select class="form-control" id="filter-giro" onchange="filtergiro()">
				<option value="">Pilih Filter</option>
				<?php 
				foreach(is_status_giro() as $k=>$v){ 
					$selected='';
					if(@$filter_status == $k){
						$selected='selected="selected"';
					}
				?>
				<option value="<?php echo $k?>" <?php echo $selected?>><?php echo $v?></option>
				<?php } ?>
			</select>
			</div>	
			<a class="btn btn-primary" data-toggle="modal" href="#dialog-giro" title="Print" onclick="PreviewPdf()"><i class="fa fa-print">&nbsp;</i>Cetak PDF</a>		
		<?php //endif; ?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped" width="100%">
		<thead>
		<tr>
			<th width="2%">#</th>
			<th width="15%">Nomor Giro</th>
			<th width="15%">Customer</th>
			<th width="10%">Tgl Transaksi</th>
			<th width="15%">Nama Bank</th>
			<th width="10%">Nilai Fisik</th>
			<th width="10%">Tgl JTT</th>			
			<th width="30%">JTT</th>
			<th width="5%">Status</th>
		</tr>
		</thead>
        
		<tbody>
			<?php
			$n=1;
			if(@$results){
			foreach(@$results as $k=>$v){
				$no=$n++;
				$badge = 'bg-green';
				$hari = selisih_hari($v->tgl_jth_tempo,date('Y-m-d'));
				$viewhari = $hari.' Hari Lagi';
				if(date('Y-m-d') <= $v->tgl_jth_tempo){
					if($hari < 8 && $hari >= 1){
						$badge = 'bg-yellow';
					}elseif($hari <= 0){
						$viewhari = ' Sudah JTT';
						$badge = 'bg-red';
					}
				}else{
					$viewhari = ' Sudah JTT';
					$badge = 'bg-red';
				}
			?>
			<tr>
				<td><center><?php echo $no?></center></td>
				<td><center><?php echo $v->no_giro?></center></td>
				<td><?php echo $v->nm_customer?></td>
				<td><center><?php echo date('d-M-Y',strtotime($v->tgl_giro))?></center></td>
				<td><center><?php echo $v->nm_bank?></center></td>
				<td style="text-align: right;"><?php echo formatnomor($v->nilai_fisik)?></td>
				<td><center><?php echo date('d-M-Y',strtotime($v->tgl_jth_tempo))?></center></td>
				<td><center><span class="badge <?php echo $badge?>"><?php echo $viewhari ?></span></center></td>
				<td><center><span class="badge bg-green"><?php echo $v->status?></span></center></td>
			</tr>
			<?php } ?>
			<?php } ?>
		</tbody>
		
		<tfoot>
		<tr>
			<th width="2%">#</th>
			<th width="15%">Nomor Giro</th>
			<th width="15%">Customer</th>
			<th width="15%">Tgl Transaksi</th>
			<th>Nama Bank</th>
			<th width="15%">Nilai Fisik</th>
			<th width="15%">Tgl JTT</th>	
			<th width="10%">Ket JTT</th>		
			<th width="5%">Status</th>
		</tr>
		</tfoot>
		</table>
	</div>
	<!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-giro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Giro</h4>
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
		var url = siteurl+'giro/create';
		window.location.href = url;
	}

	function filtergiro(){
		var filter = $('#filter-giro').val();
		if(filter != ""){
			var url = siteurl+'giro/filter?status='+filter;
			window.location.href = url;
		}
	}

  	function edit_data(kodebarang){
		if(kodebarang!=""){
			var url = 'koli/edit/'+kodebarang;	
			$(".box").hide(); 
			$("#form-area").show();	

			$("#form-area").load(siteurl+url);

		    $("#title").focus();
		}
	}

	function PreviewPdf()
	{
		var st = '<?php echo @$filter_status?>';
		tujuan = 'giro/print_request/'+st;

	   	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
	}

</script>