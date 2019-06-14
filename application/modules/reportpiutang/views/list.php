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
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<?php
							echo"<select name='salesman' id='salesman' class='form-control input-sm'>";
								echo"<option value=''>All Salesman</option>";
								foreach($marketing as $key=>$val){
								
								$yuup=($val->id_karyawan == $sales_pilih)?'selected':'';
								echo"<option value='".$val->id_karyawan."' $yuup>".$val->nama_karyawan."</option>";
								}
							echo"</select>";
							?>
						</div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<?php
							echo"<select name='pelanggan' id='pelanggan' class='form-control input-sm'>";
								echo"<option value=''>All Customer</option>";
								foreach($customer as $key=>$val){
								
									$yuup=($val->id_customer == $cust_pilih)?'selected':'';
								echo"<option value='".$val->id_customer."' $yuup>".$val->id_customer.", ".$val->nm_customer."</option>";
								}
							echo"</select>";
							?>
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
			<button type="button" id="btn-excel" class="btn btn-md btn-success"><i class="fa fa-download">&nbsp;</i>Excel</button>&nbsp;&nbsp;
			<button type="button" id="btn-print" class="btn btn-md bg-maroon"><i class="fa fa-print">&nbsp;</i>Print</button>
				
		</span>
	</div>
	<div class="box-body" style="overflow-x:auto">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center" rowspan="2">No</th>
					<th class="text-center" rowspan="2">No Invoice</th>
					<th class="text-center" rowspan="2">Tgl Invoice</th>
					<th class="text-center" rowspan="2">Customer</th>					
					<th class="text-center" rowspan="2">Salesman</th>					
					<th class="text-center" rowspan="2">Total Invoice</th>
					<th class="text-center" rowspan="2">Piutang</th>
					<th class="text-center" rowspan="2">Aging</th>
					<th class="text-center" colspan="5">Range Umur Piutang (Hari)</th>
				</tr>
				<tr class="bg-blue">
					<th class="text-center">0-15</th>
					<th class="text-center">16-30</th>
					<th class="text-center">31-60</th>
					<th class="text-center">61-90</th>
					<th class="text-center">>90</th>
				</tr>
        </thead>
        <tbody>
        <?php
		$intL	= 0;
		$Total_Invoice	= $Total_Piutang	= $Total_15	= $Total_30 = $Total_60 = $Total_90 = $Total_91 = 0;
		if(@$results){
			foreach($results as $key=>$vals){
				$intL++;
				$tot_inv				= $vals->hargajualtotal;
				$tot_piutang			= $vals->hargajualtotal - $vals->jum_bayar;
				$Umur					= $vals->umur;
				
				$Total_Invoice		+= $tot_inv;
				$Total_Piutang		+= $tot_piutang;
				$A_15 = $A_30 = $A_60 = $A_90 =$A_91 ='-';
				if($Umur <= 15){
					$Total_15 +=$tot_piutang;
					$A_15		="<span class='badge bg-green'>".number_format($tot_piutang)."</span>";
				}else  if($Umur > 15 && $Umur <=30){
					$Total_30 +=$tot_piutang;
					$A_30		="<span class='badge bg-green'>".number_format($tot_piutang)."</span>";
				}else  if($Umur > 30 && $Umur <=60){
					$Total_60 +=$tot_piutang;
					$A_60		="<span class='badge bg-green'>".number_format($tot_piutang)."</span>";
				}else  if($Umur > 60 && $Umur <=90){
					$Total_90 +=$tot_piutang;
					$A_90		="<span class='badge bg-green'>".number_format($tot_piutang)."</span>";
				}else if($Umur > 90){
					$Total_91 +=$tot_piutang;
					$A_91		="<span class='badge bg-green'>".number_format($tot_piutang)."</span>";
				}
				echo"<tr>";
					echo"<td class='text-center'>".$intL."</td>";
					echo"<td class='text-center'>".$vals->no_invoice."</td>";
					echo"<td class='text-center'>".date('d M Y',strtotime($vals->tanggal_invoice))."</td>";
					echo"<td class='text-left'>".$vals->nm_customer."</td>";
					echo"<td class='text-left'>".$vals->nm_salesman."</td>";
					echo"<td class='text-right'>".number_format($tot_inv)."</td>";
					echo"<td class='text-right'>".number_format($tot_piutang)."</td>";
					echo"<td class='text-right'>".number_format($Umur)."</td>";
					echo"<td class='text-center'>".$A_15."</td>";
					echo"<td class='text-center'>".$A_30."</td>";
					echo"<td class='text-center'>".$A_60."</td>";
					echo"<td class='text-center'>".$A_90."</td>";
					echo"<td class='text-center'>".$A_91."</td>";
				echo"</tr>";
			}
			
		}
		echo"</tbody>";
		echo"<tfoot>";
			echo"<tr class='bg-gray text-red'>";
				echo"<td class='text-right' colspan='5'><b>Grand Total</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_Invoice)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_Piutang)."</b></td>";
				echo"<td class='text-center'><b>-</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_15)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_30)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_60)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_90)."</b></td>";
				echo"<td class='text-right'><b>".number_format($Total_91)."</b></td>";
			echo"</tr>";
		echo"</tfoot>";
		?>
        </table>
	</div>
	<!-- /.box-body -->
</div>

<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" id="btn-close">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Report TTNT</h4>
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
	$(document).ready(function(){
		$('#btn-close, #btn-close2').click(function(){
			 $('#MyModalBody').empty();
			 $('#dialog-popup').hide();
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
	$('#btn-excel').click(function(){
		var cabang	= $('#kdcab').val();
		var sales	= $('#salesman').val();
		var customer= $('#tahun').val();
		if(customer=='' || customer==null){
			customer='all';
		}
		if(sales=='' || sales==null){
			sales='all';
		}
		var Links		= siteurl+'reportpiutang/preview_data/'+cabang+'/'+sales+'/'+customer+'/excel';
		//alert(Links);
		window.open(Links,'_blank');
	});
	
	$('#btn-print').click(function(){
		var cabang	= $('#kdcab').val();
		var sales	= $('#salesman').val();
		var customer= $('#tahun').val();
		if(customer=='' || customer==null){
			customer='all';
		}
		if(sales=='' || sales==null){
			sales='all';
		}
		//tujuan = 'reportpiutang/preview_data/'+cabang+'/'+sales+'/'+customer+'/pdf';
		//$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
		//$('#dialog-popup').show();
		var Links		= siteurl+'reportpiutang/preview_data/'+cabang+'/'+sales+'/'+customer+'/pdf';
		//alert(Links);
		window.open(Links,'_blank');
	});
	
	


	
</script>
