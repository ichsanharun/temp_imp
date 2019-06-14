<?php
//print_r(@$results);
/*
    $ENABLE_ADD     = has_permission('Reportstok.Add');
    $ENABLE_MANAGE  = has_permission('Reportstok.Manage');
    $ENABLE_VIEW    = has_permission('Reportstok.View');
    $ENABLE_DELETE  = has_permission('Reportstok.Delete');
    */
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<form action="<?= site_url(strtolower($this->uri->segment(1).'/index'))?>" method="POST" id='form_proses'>
		<div class="col-lg-12">
			
		
			<div class="box-header text-left"><b>Pilih Cabang : </b>				
				<div class="form-inline">
					<div class="form-group">
						<div class="input-group">
							<?php
								if($rows_cab_user=='100'){
									if($cabang){
										echo"<select name='kdcab' id='kdcab' class='form-control input-sm'>";
										foreach($cabang as $key=>$vals){
											$nama_cabang	= $key.', '.$vals;
											$yuup=($key==$cab_pilih)?'selected':'';
											echo"<option value='$key' $yuup>".$nama_cabang."</option>";
										}
										echo"</select>";
									}
								}else{
									$Cabang_data	= $rows_cab_user.', '.$cabang[$rows_cab_user];
									echo"<input type='text' class='form-control input-sm' name='nama_cabang' id='nama_cabang' value='$Cabang_data' disabled>";
									echo"<input type='hidden' class='form-control input-sm' name='kdcab' id='kdcab' value='$rows_cab_user'>";
								}
							?>
						</div>
						<div class="input-group">
							<?php
							echo"<select name='bulan' id='bulan' class='form-control input-sm'>";
							 foreach(the_bulan() as $kb=>$vb){
								
								$yuup=($kb==$bulan_pilih)?'selected':'';
								echo"<option value='$kb' $yuup>".$vb."</option>";
							}
							echo"</select>";
							?>
						</div>
						<div class="input-group">
							<input value="<?php echo $tahun_pilih?>" type="text" name="tahun" id="tahun" class="form-control input-sm" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
						</div>
						
						<input type="button" id="btn-submit" class="btn btn-md btn-warning" value="Tampilkan">
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- /.box-header -->
	<div class="col-sm-12" style="padding-bottom: 20px;">
		<span class="pull-right">
			<button type="button" id="btn-excel" class="btn btn-md btn-success"><i class="fa fa-download">&nbsp;</i>Excel</button>
			
				
		</span>
	</div>
	<div class="box-body" style="overflow-x:auto">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">No</th>
					<th class="text-center">No Invoice</th>
					<th class="text-center">Customer</th>					
					<th class="text-center">Bulan</th>
					<th class="text-center">Tahun</th>
					<th class="text-center">Saldo Awal</th>
					<th class="text-center">Debet</th>
					<th class="text-center">Kredit</th>
					<th class="text-center">Saldo Akhir</th>					
				</tr>
        </thead>
        <tbody>
        <?php
		$intL	= 0;
		$Total_Awal	= $Total_Debet	= $Total_Kredit	= $Total_Akhir = 0;
		if(@$results){
			foreach($results as $key=>$vals){
				$intL++;
				$saldo_awal				= $vals->saldo_awal;
				$debet					= $vals->debet;
				$kredit					= $vals->kredit;
				$saldo_akhir			= $vals->saldo_akhir;
				
				$Total_Awal		+= $saldo_awal;
				$Total_Debet	+= $debet;
				$Total_Kredit	+= $kredit;
				$Total_Akhir	+= $saldo_akhir;
				echo"<tr>";
					echo"<td class='text-center'>".$intL."</td>";
					echo"<td class='text-center'>".$vals->no_invoice."</td>";
					echo"<td class='text-left'>".$vals->customer."</td>";
					echo"<td class='text-center'>".the_bulan($vals->bln)."</td>";
					echo"<td class='text-center'>".$vals->thn."</td>";
					echo"<td class='text-right'>".number_format($saldo_awal)."</td>";
					echo"<td class='text-right'>".number_format($debet)."</td>";
					echo"<td class='text-right'>".number_format($kredit)."</td>";
					echo"<td class='text-right'>".number_format($saldo_akhir)."</td>";
				echo"</tr>";
			}
			
		}
		echo"</tbody>";
		echo"<tfoot>";
			echo"<tr class='bg-gray text-red'>";
				echo"<td class='text-right' colspan='5'><b>Grand Total</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_Awal)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_Debet)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_Kredit)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_Akhir)."</b></td>";
				
			echo"</tr>";
		echo"</tfoot>";
		?>
        </table>
	</div>
	<!-- /.box-body -->
</div>


<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
	$(document).ready(function(){
		var dataTable = $("#example1").DataTable().draw();
		$(".datepicker").datepicker({
			todayHighlight: true,
			format : "yyyy-mm-dd",
			showInputs: true,
			autoclose:true
		});
		$("#btn-submit").on('click', function(){
			$('#form_proses').submit();
		});
		
   
	});
	$('#btn-excel').click(function(){
		var cabang	= $('#kdcab').val();
		var bulan	= $('#bulan').val();
		var tahun		= $('#tahun').val();
		
		var Links		= siteurl+'reportar/excel_piutang/'+cabang+'/'+bulan+'/'+tahun;
		//alert(Links);
		window.open(Links,'_blank');
	});
	
	


	
</script>
