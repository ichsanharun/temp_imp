<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
<form action="<?= site_url(strtolower($this->uri->segment(1).'/create'))?>" method="POST" id='form_proses'>
<div class="box">

    <!-- /.box-header -->
    <div class="box-body">
        <table id="my-grid" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">#</th>
					<th class="text-center">No DO</th>
					<th class="text-center">Tgl Rec</th>
					<th class="text-center">No PO</th>
					<th class="text-center">Supplier</th>
					<th class="text-center">Total</th>
					<th class="text-center">Umur Hutang (Hari)</th>
					<th class="text-center"><input type="checkbox" name="chk_all" id="chk_all"></th>
				</tr>
			</thead>

			<tbody id="list_detail">
            <?php 
				if ($results) {
					$num = 1;
					foreach ($results as $row){
						$Umur		= (strtotime(date('Y-m-d')) -strtotime($row->tgl_receive)) / (60*60*24);
						echo"<tr>";
							echo"<td class='text-center'>".$num."</td>";
							echo"<td class='text-left'>".$row->no_do."</td>";
							echo"<td class='text-center'>".date('d F Y',strtotime($row->tgl_receive))."</td>";
							echo"<td class='text-left'>".$row->no_po."</td>";
							echo"<td class='text-left'>".$row->nm_supplier."</td>";
							echo"<td class='text-right'>".number_format($row->saldo_akhir)."</td>";
							echo"<td class='text-right'>".$Umur."</td>";
							echo"<td class='text-center'>";
								echo"<input type='checkbox' name='dataPilih[$num]' id='dataPilih_$num' value='".$row->id."'>";
							echo"</td>";
						echo"</tr>";
						$num++;
					}
				}
               ?>
			</tbody>
			
        </table>
    </div>
	<div class="box-footer">
		<button class="btn btn-success" id="btn-proses" type="button"> Proses Bayar</button>&nbsp;&nbsp;<button class="btn btn-danger" id="btn-proses-back" type="button"> Kembali</button>
	</div>
    <!-- /.box-body -->
</div>
</form>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js'); ?>"></script>

<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(function() {
		 $('#btn-proses-back').click(function(){
			 window.location =  base_url+active_controller;
		 });
        $("#my-grid").dataTable({
			"columnDefs": [
				{"targets":0,"orderable":false,"searchable":false},
				{"targets":7,"orderable":false,"searchable":false}
			]
		});
		$('#chk_all').click(function(){
			if($(this).is(':checked')){
				$('#list_detail').find('input[type="checkbox"]').each(function(){
					$(this).prop('checked',true);
				});
			}else{
				$('#list_detail').find('input[type="checkbox"]').each(function(){
					$(this).prop('checked',false);
				});
			}
		});
		$('#btn-proses').click(function(e){
			e.preventDefault();
			var ints		=0;
			$('#list_detail').find('input[type="checkbox"]').each(function(){
				if($(this).is(':checked')){
					ints++;
				}
			});
			
			if(ints==0){
				swal({
				  title: "Peringatan!",
				  text: 'Silahkan pilih data terlebih dahulu',
				  type: "warning",
				  timer: 5000
				});
				return false;
			}
			var links	= base_url+active_controller+'/proses';
			$('#form_proses').attr('action',links);
			$('#form_proses').submit();

	  });
});

   

</script>
