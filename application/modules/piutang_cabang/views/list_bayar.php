
<?php
    $ENABLE_ADD = has_permission('Putang_cabang.Add');
    $ENABLE_MANAGE = has_permission('Putang_cabang.Manage');
    $ENABLE_VIEW = has_permission('Putang_cabang.View');
    $ENABLE_DELETE = has_permission('Putang_cabang.Delete');
?>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
<div class="box">
	<div class="box-header">
		<h4 class=""></h4>
		
		<?php if ($ENABLE_ADD) : ?>
            <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>BAYAR HUTANG</a>
        <?php endif; ?>
			
	</div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Nomor</th>
					<th class="text-center">Date</th>
					<th class="text-center">Branch</th>
					<th class="text-center">Description</th>
					<th class="text-center">Total</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>

			<tbody id="list_detail">
           
			</tbody>
			
        </table>
    </div>
	<div class="box-footer">
		<button class="btn btn-success" id="btn-proses" type="button"> Proses Bayar</button>&nbsp;&nbsp;<button class="btn btn-danger" id="btn-proses-back" type="button"> Kembali</button>
	</div>
    <!-- /.box-body -->
</div>
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" id="btn-close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Bukti Uang Keluar (BUK)</h4>
      </div>
      <div class="modal-body" id="MyModalBody">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close2">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var cabang_user			= '<?php echo $user_cabang;?>';
	var arr_cabang			= <?php echo json_encode($rows_cabang);?>;
    $(function() {
		 $('#btn-close, #btn-close2').click(function(){
			 $('#MyModalBody').empty();
			 $('#dialog-popup').hide();
		 });
		 $('#btn-proses-back').click(function(){
			 window.location =  base_url+active_controller;
		 });
		data_display();
		
	});
function add_data(){
	window.location.href = base_url + active_controller+'/daftar_piutang';
}

function data_display(){
	var table_data = $('#my-grid').dataTable( {
		"paging"	: true,
		"processing": true,
		"serverSide": true,
		'destroy'	: true,
		"ajax": {
			"url"	:  base_url + active_controller+'/get_data_display',
			"type"	: "POST"
			/*
			"data"	:{'datet':tgl,'owner':owner,'asuransi':asuransi,'tag':'list_cpr'}
			*/			
		},		 
		"columns": [
			{"data":"jurnalid"},
			{"data":"datet","sClass":"text-center"},
			{"data":"kdcab","sClass":"text-center"},
			{"data":"descr","sClass":"text-left"},
			{"data":"total","sClass":"text-right"},
			{"data":"action","sClass":"text-center","searchable":false}
		],
		"rowCallback": function(row,data,index,iDisplayIndexFull){
			//console.log(data.tool_id);
			var cabang	= arr_cabang[data.kdcab];
			
			
			var Template		='<a href="#" class="btn btn-sm btn-danger" onClick="previewPDF('+'\''+data.jurnalid+'\''+');"><span class="glyphicon glyphicon-print"></span></a>';
			$('td:eq(2)',row).html(cabang);
			$('td:eq(5)',row).html(Template);
			
		},
		"order": [[1,"desc"]]
	});
	
}
function previewPDF(jurnal){
    tujuan = active_controller+'/print_buk/'+jurnal;
	//console.log(tujuan);
	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
	$('#dialog-popup').show();
}

</script>
