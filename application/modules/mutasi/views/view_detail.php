<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
               
                <div class="box-body">
					<div class="form-group row">
						<label for="cabang_asal" class="col-sm-2 control-label">No Mutasi</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								echo form_input(array('id'=>'no_mutasi','name'=>'no_mutasi','class'=>'form-control input-sm','value'=>$header->no_mutasi,'readOnly'=>true));
							?>
						</div>
						 <label for="cabang_tujuan" class="col-sm-2 control-label">Tgl Mutasi</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								echo form_input(array('id'=>'tgl_mutasi','name'=>'tgl_mutasi','class'=>'form-control input-sm','value'=>date('d F Y',strtotime($header->tgl_mutasi)),'readOnly'=>true));
							?>                                
						</div>
					</div>
					<div class="form-group row">
						<label for="cabang_asal" class="col-sm-2 control-label">Cabang Asal</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								echo form_input(array('id'=>'cabang_asal','name'=>'cabang_asal','class'=>'form-control input-sm','value'=>$header->cabang_asal,'readOnly'=>true));
							?>
						</div>
						 <label for="cabang_tujuan" class="col-sm-2 control-label">Cabang Tujuan</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								echo form_input(array('id'=>'cabang_tujuan','name'=>'cabang_tujuan','class'=>'form-control input-sm','value'=>$header->cabang_tujuan,'readOnly'=>true));
							?>                                
						</div>
					</div>
					<div class="form-group row">
						<label for="cabang_asal" class="col-sm-2 control-label">Kendaraan</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								echo form_input(array('id'=>'kendaraan','name'=>'kendaraan','class'=>'form-control input-sm','value'=>$header->ket_kendaraan,'readOnly'=>true));
							?>
						</div>
						 <label for="cabang_tujuan" class="col-sm-2 control-label">Supir</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								echo form_input(array('id'=>'nm_supir','name'=>'nm_supir','class'=>'form-control input-sm','value'=>$header->nm_supir,'readOnly'=>true));
							?>                                
						</div>
					</div>
					<div class="form-group row">
						<label for="cabang_asal" class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4" style="padding-top: 8px;">
							<?php
								if($header->status_mutasi =='IT'){
									echo"<span class='badge bg-green'>INTRANSIT</span>";
								}else{
									echo"<span class='badge bg-maroon'>RECEIVED</span>";
								}
								
							?>
						</div>
						 <label for="cabang_tujuan" class="col-sm-2 control-label"></label>
						<div class="col-sm-4" style="padding-top: 8px;">
							                                
						</div>
					</div>
                </div>
               
            </div>
        </div>
    </div>
</div>
<div class="box box-default ">
	<div class="box-header">
		<h4 class="box-title">Detail Item Mutasi</h4>
		
	</div>
    <div class="box-body">
       
		<table class="table table-bordered" width="100%" id="tabel-detail-mutasi">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">Kode Produk</th>
					<th class="text-center">Kode Produk</th>
					<th class="text-center">Nama Produk</th>
					<th class="text-center">Qty Mutasi</th>
					<th class="text-center">Qty Receive</th>
				</tr>
			</thead>
			<tbody id="list_item_mutasi">
				<?php
				if($detail){
					$intI	=0;
					foreach($detail as $key=>$vals){
						$intI++;
						echo"<tr>";
							echo"<td class='text-center'>".$intI."</td>";
							echo"<td class='text-left'>".$vals->id_barang."</td>";
							echo"<td class='text-left'>".$vals->nm_barang."</td>";
							echo"<td class='text-center'>".number_format($vals->qty_mutasi)."</td>";
							echo"<td class='text-center'>".number_format($vals->qty_received)."</td>";
						echo"</tr>";
					}
				}
				?>
			</tbody>
		</table>
       
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_mutasi()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
       
    </div>
  </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
				
	$(document).ready(function(){
		
	});
    
   
    function kembali_mutasi(){
        window.location.href = siteurl+"mutasi";
    }
    
</script>
