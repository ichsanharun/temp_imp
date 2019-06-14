<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-header">
        <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
    </div>
    <div class="box-body">
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">No Mutasi</th>
					<th class="text-center">Tgl Mutasi</th>
					<th class="text-center">Cabang Pemesan</th>
					<th class="text-center">Cabang Pengirim</th>
					<th class="text-center">Status</th>
					<th class="text-center">Aksi</th>
				</tr>
			</thead>
			<tbody id="list_detail">


			</tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Mutasi Produk</h4>
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
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var cabang_user			= '<?php echo $cabs_user;?>';
	var arr_cabang			= <?php echo json_encode($Arr_Cabang);?>;
    $(function() {
		get_data();
    });
    function add_data(){
        window.location.href = base_url+active_controller+"/create";
    }

	function get_data(){

		var my_table = $('#my-grid').dataTable( {
			"processing"	: true,
			"serverSide"	: true,
			"destroy"	: true,
			"ajax"			: {
				"url"	:  base_url + active_controller +'/display_data_json',
				"type"	: "POST"
			},
			"columns": [
				{"data":"no_mutasi","sClass":"text-left"},
				{"data":"tgl_mutasi","sClass":"text-center"},
				{"data":"cabang_asal","sClass":"text-center"},
				{"data":"cabang_tujuan","sClass":"text-left"},
				{"data":"status_mutasi","sClass":"text-center"},
				{"data":"action","sClass":"text-center","searchable":false,"sortable":false},
			],
			"rowCallback": function(row,data,index,iDisplayIndexFull){
				var kode_asal	= data.kdcab_asal;
				var kode_tujuan	= data.kdcab_tujuan;
				var sts_data	= data.status_mutasi;
				if(sts_data=='OPEN'){
					var status	='<span class="badge bg-green">OPEN</span>';
				}else if(sts_data=='CLOSE'){
					var status	='<span class="badge bg-maroon">CLOSE</span>';
				}

				if(cabang_user==kode_tujuan && sts_data!='CLOSE'){
					var Template	='<a href="'+base_url+active_controller+'/getdetailmutasi/'+data.no_mutasi+'" title="Buat SO Internal" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"></span></a>';
				}
				else
				{
					var Template	='<a href="'+base_url+active_controller+'/receive/'+data.no_mutasi+'" title="Buat SO Internal" class="badge bg-blue"><span class="glyphicon glyphicon-download"><div style="font-family:arial">Receive</div></span></a>';
				}
				// if(cabang_user==kode_asal && sts_data=='IT'){
				// Template	+='<a href="#" class="btn btn-sm btn-danger" onClick="previewPDF('+'\''+data.no_mutasi+'\''+');"><span class="glyphicon glyphicon-print"></span></a>';
				// }
				$('td:eq(4)',row).html(status);
				$('td:eq(5)',row).html(Template);

			},
			"order": [[1,"desc"]]
		});
	}

	function previewPDF(jurnal){
		tujuan = active_controller+'/print_request/'+jurnal;
		//console.log(tujuan);
		$("#MyModalBody").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
		$('#dialog-popup').show();
	}
</script>
