<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form method="POST" id="form_proses">
	<div class="nav-tabs-salesorder">
		<div class="tab-content">
			<div class="tab-pane active" id="salesorder">
				<div class="box box-primary">
					<div class="box-header">
						<h4 class="box-title">Pembayaran Hutang</h4>
					</div>
					<div class="box-body">
						<div class="form-group row">
							<label class="control-label col-sm-2"><b>Nomor</b></label>
							<div class="col-sm-4">
								<span class="badge bg-maroon">AUTOMATIC</span>
							</div>
							<label class="control-label col-sm-2"><b>Tgl Bayar</b></label>
							<div class="col-sm-4">
								<span class="badge bg-green"><?php echo date('d F Y');?></span>
							</div>
						</div>
						<div class="form-group row">
							<label class="control-label col-sm-2"><b>Bayar Kepada</b></label>
							<div class="col-sm-4">								
								<?php
									echo form_input(array('id'=>'bayar_kepada','name'=>'bayar_kepada','class'=>'form-control input-sm','autocomplete'=>'off','value'=>$rows_cabang[0]->cabang,'readOnly'=>true));											
								?>
								
							</div>
							<label class="control-label col-sm-2"><b>Bayar Dari</b></label>
							<div class="col-sm-4">
								<?php
									$rows_coa[0]	= 'Silahkan Pilih';
									echo form_dropdown('no_perkiraan',$rows_coa, '0', array('id'=>'no_perkiraan','class'=>'form-control input-sm'));
								?>
							</div>
						</div>
						<div class="form-group row">
							<label class="control-label col-sm-2"><b>Keterangan</b></label>
							<div class="col-sm-4">								
								<?php
									echo form_textarea(array('id'=>'descr','name'=>'descr','class'=>'form-control input-md','cols'=>'75','rows'=>'3','autocomplete'=>'off','placeholder'=>'Description','style'=>'text-transform:uppercase','value'=>'PEMBAYARAN HUTANG KE '.strtoupper($rows_cabang[0]->cabang)));											
								?>
								
							</div>
							<label class="control-label col-sm-2"><b></b></label>
							<div class="col-sm-4">
								<?php
									
								?>
							</div>
						</div>
					</div>
					<div class="box-body">
						<div class="box box-warning">
							<div class="box-header">
								<h4 class="box-title">Detail Data</h4>
							</div>
							<div class="box-body">
								<table class="table table-bordered table-striped">
									<thead>
										<tr class="bg-blue">
											<th class="text-center">No</th>
											<th class="text-center">No DO</th>
											<th class="text-center">No PO</th>
											<th class="text-center">Supplier</th>
											<th class="text-center">Total</th>
										</tr>
									</thead>
									<tbody id="list_detail">
										<?php
										$Totals		= 0;
										$Loop		= 0;
										if($rows_data){
											foreach($rows_data as $key=>$vals){
												$Loop++;
												$Totals	+=$vals->saldo_akhir;
												echo"<tr id='tr_".$Loop."'>";
													echo form_input(array('id'=>'nodo_'.$Loop,'name'=>'dataDet['.$Loop.'][no_do]','type'=>'hidden','value'=>$vals->no_do));
													echo form_input(array('id'=>'nopo_'.$Loop,'name'=>'dataDet['.$Loop.'][no_po]','type'=>'hidden','value'=>$vals->no_po));
													echo form_input(array('id'=>'total_old_'.$Loop,'name'=>'dataDet['.$Loop.'][total_old]','type'=>'hidden','value'=>$vals->saldo_akhir));
													echo form_input(array('id'=>'kode_'.$Loop,'name'=>'dataDet['.$Loop.'][kode]','type'=>'hidden','value'=>$vals->id));
													echo form_input(array('id'=>'supplier_'.$Loop,'name'=>'dataDet['.$Loop.'][supplier]','type'=>'hidden','value'=>$vals->nm_supplier));
													echo"<td class='text-center'>".$Loop."</td>";
													echo"<td class='text-left'>".$vals->no_do."</td>";
													echo"<td class='text-left'>".$vals->no_po."</td>";
													echo"<td class='text-left'>".$vals->nm_supplier."</td>";
													echo"<td>";
														echo form_input(array('id'=>'total_baru_'.$Loop,'name'=>'dataDet['.$Loop.'][total_baru]','class'=>'form-control input-sm harga','onBlur'=>'stopCalculation();','onFocus'=>'startCalculation('.$Loop.');','data-decimal'=>'.','data-thousand'=>'','data-precision'=>'0','data-allow-zero'=>false,'value'=>number_format($vals->saldo_akhir)));
													echo"</td>";
												echo"</tr>";
												
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr class="bg-gray">
											<td class="text-right" colspan="4"><b>Grand Total</b></td>
											<td>
												<?php
													echo form_input(array('id'=>'grand_tot','name'=>'grand_tot','class'=>'form-control input-sm','value'=>number_format($Totals),'readOnly'=>true));											
												?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="box-footer">
						 <button class="btn btn-danger" id="btn-back" type="button">
						   <i class="fa fa-refresh"></i><b> Kembali</b>
						</button>
						<button class="btn btn-primary" type="button" id="proses_bayar_bro">
							<i class="fa fa-save"></i><b> Simpan Data</b>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.min.js')?>"></script>
<script src="<?= base_url('assets/js/jquery.maskMoney.js')?>"></script>

<script type="text/javascript">
	var base_url			= siteurl;
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(document).ready(function(){
		$('.harga').maskMoney();
		$('#btn-back').click(function(){
			window.location.href = base_url + active_controller+'/daftar_piutang';
		});
		$('#proses_bayar_bro').click(function(e){
			e.preventDefault();
			$('#proses_bayar_bro, #btn-back').prop('disabled',true);
			var nama	= $('#no_perkiraan').val();
			
			if(nama=='' || nama==null || nama=='-' || nama=='0'){
				swal({
				  title	: "Error Message!",
				  text	: 'Empty Payment Account Type, please choose Payment Account Type first.....',
				  type	: "warning"
				});
				$('#proses_bayar_bro, #btn-back').prop('disabled',false);
				return false;
				
			}
			
			var ints	=0 ;
			$('#list_detail').find('tr').each(function(){			
				var nil		= $(this).attr('id');
				var jum		= nil.split('_');
				var loop	= jum[1];
				var awal	= $('#total_baru_'+loop).val().replace(/\,/g,'');
				if(awal=='' || awal==null || parseInt(awal) < 1){
					ints++;
				}
				
			});
			if(ints > 0){
				swal({
				  title	: "Error Message!",
				  text	: 'Incorrect Total Value, please input Payment total value first.....',
				  type	: "warning"
				});
				$('#proses_bayar_bro, #btn-back').prop('disabled',false);
				return false;
			}
			swal({
				  title: "Are you sure?",
				  text: "You will not be able to process again this data!",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonClass: "btn-danger",
				  confirmButtonText: "Yes, Process it!",
				  cancelButtonText: "No, cancel process!",
				  closeOnConfirm: false,
				  closeOnCancel: true
				},
				function(isConfirm) {
				  if (isConfirm) {
						
						var formData 	= new FormData($('#form_proses')[0]);
						var baseurl		= base_url + active_controller +'/save_jurnal';
						$.ajax({
							url			: baseurl,
							type		: "POST",
							data		: formData,
							cache		: false,
							dataType	: 'json',
							processData	: false, 
							contentType	: false,				
							success		: function(data){								
								if(data.status == 1){
									swal({
										  title	: "Save Success!",
										  text	: data.pesan,
										  type	: "success",
										  timer	: 7000
										});
									window.location.href = base_url + active_controller;
								}
								else{
									if(data.status == 2){
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000
										});
									}else{
										swal({
										  title	: "Save Failed!",
										  text	: data.pesan,
										  type	: "warning",
										  timer	: 7000,
										  showCancelButton	: false,
										  showConfirmButton	: false,
										  allowOutsideClick	: false
										});
									}
									$('#proses_bayar_bro, #btn-back').prop('disabled',false);
								}
							},
							error: function() {
								
								swal({
								  title				: "Error Message !",
								  text				: 'An Error Occured During Process. Please try again..',						
								  type				: "warning",								  
								  timer				: 7000,
								  showCancelButton	: false,
								  showConfirmButton	: false,
								  allowOutsideClick	: false
								});
								$('#proses_bayar_bro, #btn-back').prop('disabled',false);
							}
						});
				  } else {
					swal("Cancelled", "Data can be process again :)", "error");
					$('#proses_bayar_bro, #btn-back').prop('disabled',false);
					return false;
				  }
			});
		});
	});

	function startCalculation(id){  
		intervalCalculation = setInterval('Calculation('+id+')',1);
	}
	function Calculation(id){  
		var harga_lama		=parseInt($('#total_old_'+id).val().replace(/\,/g,''));		
		var harga_baru		=parseInt($('#total_baru_'+id).val().replace(/\,/g,''));
		
		if(harga_baru > harga_lama){
			swal({
			  title	: "Error Message!",
			  text	: 'Total row greater than '+harga_lama.format(0,3,',')+', please input correct total.....',
			  type	: "warning"
			});
			$('#total_baru_'+id).val(harga_lama.format(0,3,','));
		}
		
		CalcALL();
	}
	function stopCalculation(){   
		clearInterval(intervalCalculation);
	}
	function CalcALL(){
		var sub_tot	=0;		
		$('#list_detail').find('tr').each(function(){			
			var nil		= $(this).attr('id');
			var jum		= nil.split('_');
			var loop	= jum[1];
			var awal	= $('#total_baru_'+loop).val().replace(/\,/g,'');
			sub_tot		= parseFloat(sub_tot) + parseFloat(awal); 
			
		});
		
		$('#grand_tot').val(sub_tot.format(0,3,','));
		
		
	}
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>