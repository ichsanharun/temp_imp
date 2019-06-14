<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form method="POST" id="form_proses">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">            
			<div class="box box-primary">				
				<div class="box-body">					
					<div class='form-group row'>						
						<label class='control-label col-sm-2'><b>No Invoice</b></label>
						<div class='col-sm-4'>
							 <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-bookmark"></i></span> 
									<?php
										echo form_input(array('id'=>'no_invoice','name'=>'no_invoice','class'=>'form-control input-sm','readOnly'=>true),$row_header->no_invoice);
									?>											
								
							</div>
						</div>	
						<label class='label-control col-sm-2'><b>Tgl Invoice</b></label>
						<div class='col-sm-4'>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
								<?php					
									echo form_input(array('id'=>'tgl_invoice','name'=>'no_invoice','class'=>'form-control input-sm','disabled'=>true),date('d M Y',strtotime($row_header->tanggal_invoice)));
								?>	
							</div>
						</div>
						
					</div>
					
					<div class='form-group row'>						
						<label class='control-label col-sm-2'><b>Customer</b></label>
						<div class='col-sm-4'>
							 <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-home"></i></span> 
									<?php
										echo form_input(array('id'=>'customer','name'=>'customer','class'=>'form-control input-sm','disabled'=>true),$row_header->nm_customer);
									?>											
								
							</div>
						</div>	
						<label class='label-control col-sm-2'><b>Alamat</b></label>
						<div class='col-sm-4'>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-map"></i></span> 
								<?php					
									echo form_textarea(array('id'=>'alamat','name'=>'alamat','class'=>'form-control input-sm','cols'=>'75','rows'=>'1','disabled'=>true),$row_header->alamatcustomer);
								?>	
							</div>
						</div>
						
					</div>
					<div class='form-group row'>						
						<label class='control-label col-sm-2'><b>Alasan Batal</b></label>
						<div class='col-sm-4'>
							 <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-file"></i></span> 
									<?php
										echo form_textarea(array('id'=>'cancel_reason','name'=>'cancel_reason','class'=>'form-control input-sm','cols'=>'75','rows'=>'2'));
									?>											
								
							</div>
						</div>	
						<label class='label-control col-sm-2'><b>Faktur Pajak</b></label>
						<div class='col-sm-4'>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-credit-card"></i></span> 
								<?php					
									echo form_dropdown('faktur_pajak',array('B'=>'BLOK','O'=>'RE-OPEN'), 'B', array('id'=>'faktur_pajak','class'=>'form-control input-sm'));
								?>	
							</div>
						</div>
						
					</div>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped" id="detail_invoice">
						<thead>
							<tr class="bg-blue">
								<th class="text-center">No</th>
								<th class="text-center">No DO</th>
								<th class="text-center">Nama Alat</th>
								<th class="text-center">Satuan</th>
								<th class="text-center">Qty</th>
								<th class="text-center">Harga</th>
								<th class="text-center">Diskon</th>
								<th class="text-center">Total Harga</th>								
							</tr>
						</thead>
						<tbody>						
							<?php
							if($row_detail){
								$loop		= 0;
								foreach($row_detail as $keyD=>$valD){
									$loop++;
									$Harga			= $valD->hargajual;
									$Qty			= $valD->jumlah;
									$Diskon			= $valD->diskon;
									
									$Total_Bersih	= ($Harga - $Diskon) * $Qty;
									echo"<tr>";
										echo"<td class='text-center'>".$loop."</td>";
										echo"<td class='text-center'>".$valD->no_do."</td>";
										echo"<td class='text-left'>".$valD->nm_barang."</td>";
										echo"<td class='text-center'>".$valD->satuan."</td>";
										echo"<td class='text-center'>".number_format($Qty)."</td>";
										echo"<td class='text-right'>".number_format($Harga)."</td>";
										echo"<td class='text-right'>".number_format($Diskon)."</td>";
										echo"<td class='text-right'>".number_format($Total_Bersih)."</td>";
									echo"</tr>";
								}
							}							
							?>
						</tbody>
						<tfoot>
							<tr class="bg-gray">
								<td colspan="7" class="text-right"><b>Total</b></td>
								<td class="text-right text-red"><?php echo number_format($row_header->dpp);?></td>
							</tr>
							<tr class="bg-gray">
								<td colspan="7" class="text-right"><b>PPN</b></td>
								<td class="text-right text-red"><?php echo number_format($row_header->ppn);?></td>
							</tr>
							<tr class="bg-gray">
								<td colspan="7" class="text-right"><b>Biaya Materai</b></td>
								<td class="text-right text-red"><?php echo number_format($row_header->meterai);?></td>
							</tr>
							<tr class="bg-gray">
								<td colspan="7" class="text-right"><b>Ongkir</b></td>
								<td class="text-right text-red"><?php echo number_format(0);?></td>
							</tr>
							<tr class="bg-gray">
								<td colspan="7" class="text-right"><b>Grand Total</b></td>
								<td class="text-right text-red"><b><?php echo number_format($row_header->hargajualtotal);?></b></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			
        </div>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_inv()" style="display: none;">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" id="proses_inv">
            <i class="fa fa-save"></i><b> PROSES BATAL</b>
        </button>
    </div>
  </div>
</div>
 </form>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.min.js')?>"></script>
<script src="<?= base_url('assets/dist/jquery.maskedinput.min.js')?>"></script>

<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(document).ready(function(){
       
        $('#detail_invoice').DataTable();
		
		$('#proses_inv').click(function(e){
			var alasan	= $('#cancel_reason').val();
			if(alasan=='' || alasan==null || alasan=='-'){
				swal({
				  title: "Error Message!",
				  text: 'Alasan Batal Belum Diinput, Mohon Input Alasan Batal Terlebih Dahulu.....',
				  type: "warning",								  
				  timer: 5000
				});
				
				return false;
			}
			  e.preventDefault();
			  swal({
					  title: "Are you sure?",
					  text: "You will not be able to process again this data!",
					  type: "warning",
					  showCancelButton: true,
					  confirmButtonClass: "btn-danger",
					  confirmButtonText: "Yes, Process it!",
					  cancelButtonText: "No, cancel process!",
					  closeOnConfirm: false,
					  closeOnCancel: false,
					  showLoaderOnConfirm: true
					},
					function(isConfirm) {
					  if (isConfirm) {
							
							var formData 	=new FormData($('#form_proses')[0]);
							var baseurl=base_url + active_controller +'/cancel_invoice';
							$.ajax({
								url			: baseurl,
								type		: "POST",
								data		: formData,
								cache		: false,
								dataType	: 'json',
								processData	: false, 
								contentType	: false,				
								success		: function(data){
									
									var kode_bast	= data.kode;
									if(data.status == 1){											
										swal({
											  title	: "Save Success!",
											  text	: data.pesan,
											  type	: "success",
											  timer	: 15000,
											  showCancelButton	: false,
											  showConfirmButton	: false,
											  allowOutsideClick	: false
											});
										window.location.href = base_url + active_controller;
									}else{
										
										if(data.status == 2){
											swal({
											  title	: "Save Failed!",
											  text	: data.pesan,
											  type	: "danger",
											  timer	: 10000,
											  showCancelButton	: false,
											  showConfirmButton	: false,
											  allowOutsideClick	: false
											});
										}else{
											swal({
											  title	: "Save Failed!",
											  text	: data.pesan,
											  type	: "warning",
											  timer	: 10000,
											  showCancelButton	: false,
											  showConfirmButton	: false,
											  allowOutsideClick	: false
											});
										}
										
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
								}
							});
					  } else {
						swal("Cancelled", "Data can be process again :)", "error");
						return false;
					  }
				});
		});
    });
   
    function kembali_inv(){
        window.location.href = base_url + active_controller;
    }
</script>
