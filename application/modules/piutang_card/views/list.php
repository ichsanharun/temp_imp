<?php

    
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="col-lg-12">
		<form action="<?= site_url(strtolower($this->uri->segment(1).'/index'))?>" method="POST" id='form_proses'>
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
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<?php
							echo"<select name='pelanggan' id='pelanggan' class='form-control input-sm'>";
								echo"<option value=''>All Customer</option>";
								foreach($customer as $key=>$val){
								
									$yuup=($key == $cust_pilih)?'selected':'';
								echo"<option value='".$key."' $yuup>".$val."</option>";
								}
							echo"</select>";
							?>
						</div>						
						<input type="button" id="btn-submit" class="btn btn-md btn-warning" value="Tampilkan">
						<button type="button" id="btn-print" class="btn btn-md bg-maroon"><i class="fa fa-print">&nbsp;</i>Print</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- /.box-header -->
	
	<div class="box-body" style="overflow-y:auto">
		<?php
		if($results){
			$Cust_Pilih		= '';
			$intI			= 0;
			$intS			= 0;
			foreach($results as $keyI=>$valI){
				$intI++;
				$Customer	= $valI->id_customer;
				$Tot_Debet	= $Tot_Kredit	= 0;
				if($Cust_Pilih != $Customer){
					$intS++;
					$det_Customer	= $this->Piutang_card_model->get_data_customer($Customer);
					if($intS > 1){
							echo"</div>";
						echo"</div>";
						echo"</br>";
						//if($intS > 6)break;
					}
					echo"<div class='box box-warning'>";
						echo"<div class='box-header text-center'>";
							echo"<h4 class='box-title text-blue'><b>".$det_Customer[0]->id_customer." / ".$det_Customer[0]->nm_customer."</b></h4>";
						echo"</div>";
						echo"<div class='box-body'>";
							echo"<div class='form-group row'>";
								echo"<label class='control-label col-sm-2'>Alamat</label>";
								echo"<div class='col-sm-4'>";
									echo $det_Customer[0]->alamat;
								echo"</div>";
								echo"<label class='control-label col-sm-2'>Telpon</label>";
								echo"<div class='col-sm-4'>";
									echo $det_Customer[0]->telpon;
								echo"</div>";
							echo"</div>";
						echo"</div></br>";
						echo"<div class='box-body'>";
					$Cust_Pilih		= $Customer;
				}
				
					echo"<table class='table table-striped table-bordered'>";
						echo"<thead>";
							echo"<tr class='bg-blue'>";
								echo"<th class='text-center'>Tanggal</th>";
								echo"<th class='text-center'>Tipe</th>";
								echo"<th class='text-center'>No Reff</th>";
								echo"<th class='text-center'>Jatuh Tempo</th>";
								echo"<th class='text-center'>Debet</th>";
								echo"<th class='text-center'>Kredit</th>";
								echo"<th class='text-center'>Saldo</th>";
							echo"</tr>";
						echo"</thead>";
						echo"<tbody>";
							$Tot_Debet	+= $valI->hargajualtotal;
							echo"<tr>";
								echo"<td class='text-center'>".date('d/m/Y',strtotime($valI->tanggal_invoice))."</td>";
								echo"<td class='text-center'>INV</td>";
								echo"<td class='text-center'>".$valI->no_invoice."</td>";
								echo"<td class='text-center'>".date('d/m/Y',strtotime($valI->tgljatuhtempo))."</td>";
								echo"<td class='text-right'>".number_format($valI->hargajualtotal)."</td>";
								echo"<td class='text-right'>0</td>";
								echo"<td class='text-right'>0</td>";
							echo"</tr>";
							$Query_Jurnal	= "SELECT * FROM jurnal WHERE no_reff='".$valI->no_invoice."' AND no_perkiraan LIKE '1104-%' AND (debet > 0 OR kredit > 0) AND NOT (nomor LIKE '%JS%' OR nomor LIKE '%JP%' OR nomor LIKE '%JC%') ORDER BY tanggal ASC";
							$Proses_Jurnal	= $this->db->query($Query_Jurnal);
							$num_Jurnal		= $Proses_Jurnal->num_rows();
							if($num_Jurnal > 0){
								$det_Jurnal	= $Proses_Jurnal->result();
								foreach($det_Jurnal as $ky=>$Values){
									$Debet		= ($Values->debet > 0)?$Values->debet:0;
									$Kredit		= ($Values->kredit > 0)?$Values->kredit:0;
									$Tot_Debet	+=$Debet;
									$Tot_Kredit	+=$Kredit;
									echo"<tr>";
										echo"<td class='text-center'>".date('d/m/Y',strtotime($Values->tanggal))."</td>";
										echo"<td class='text-center'>".$Values->tipe."</td>";
										echo"<td class='text-center text-blue'><a href='#' onClick='view_jurnal(\"".$Values->nomor."\",\"".$Values->tipe."\");'><b>".$Values->nomor."</b></a></td>";
										echo"<td class='text-center'>-</td>";
										echo"<td class='text-right'>".number_format($Debet)."</td>";
										echo"<td class='text-right'>".number_format($Kredit)."</td>";
										echo"<td class='text-right'>0</td>";
									echo"</tr>";
								}
							}
						echo"</tbody>";
						echo"<tfoot>";
							echo"<tr class='bg-gray'>";
								$Saldo_Akhir	= $Tot_Debet - $Tot_Kredit;
								echo"<th class='text-right text-red' colspan='4'><b>Saldo ".$valI->no_invoice."</b></th>";
								echo"<th class='text-right text-red'><b>".number_format($Tot_Debet)."</b></th>";
								echo"<th class='text-right text-red'><b>".number_format($Tot_Kredit)."</b></th>";
								echo"<th class='text-right text-red'><b>".number_format($Saldo_Akhir)."</b></th>";
							echo"</tr>";
						echo"</tfoot>";
					echo"</table><br>";
				
				
			}
				echo"</div>";
			echo"</div>";
		}
		
		?>
		
	</div>
	<!-- /.box-body -->
</div>
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" id="btn-close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Jurnal</h4>
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
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo site_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	$(document).ready(function(){
		$('#btn-close, #btn-close2').click(function(){
			$('#MyModalBody').empty();
			$('#dialog-popup').modal('hide');
		});
		$('#kdcab').change(function(){
			var kdcab	= $('#kdcab').val();
			if(kdcab !='' && kdcab !=null){
				var baseurl	= base_url+active_controller+'/get_card_customer/'+kdcab+'/Y';
				$.ajax({
					'url'		: baseurl,
					'type'		: 'get', 
					'success'	: function(data){
						var Template	= '<option value="">All Customer</option>';
						$.each(data,function(key,nilai){
							Template	+= '<option value="'+key+'">'+nilai+'</option>';
						});
						$('#pelanggan').html(Template).trigger('chosen:updated');
					}
				});
			}
		 });
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
	function view_jurnal(kode_jurnal,tipe_jurnal){
		$('#MyModalBody').empty();
		$('#myModalLabel').text('Detail Jurnal');
		tujuan = active_controller+'/display_jurnal/'+kode_jurnal+'/'+tipe_jurnal;
		//console.log(tujuan);
		$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
		$('#dialog-popup').show();
	}
	
	
	$('#btn-print').click(function(){
		var cabang		= $('#kdcab').val();
		var customer	= $('#pelanggan').val();
		if(customer=='' || customer==null){
			customer='all';
		}
		
		var Links		= siteurl+active_controller+'/print_kartu/'+cabang+'/'+customer;
		//alert(Links);
		window.open(Links,'_blank');
	});
	
	


	
</script>
