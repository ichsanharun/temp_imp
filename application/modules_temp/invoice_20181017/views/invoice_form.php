<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form method="POST" id="form_proses">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
               	<?php //print_r($kode_customer)?>
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idcustomer_do" class="col-sm-4 control-label">Nama Customer </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$data_cust[0]->nm_customer?>
                                <input type="hidden" name="idcustomer_do" value="<?php echo $data_cust[0]->id_customer?>">
                                <input type="hidden" name="nmcustomer_do" value="<?php echo $data_cust[0]->nm_customer?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idcustomer" class="col-sm-4 control-label">Alamat Customer </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php
								$alamat		= (isset($data_cust[0]->alamat_npwp) && $data_cust[0]->alamat_npwp)?$data_cust[0]->alamat_npwp:$data_cust[0]->alamat;
								echo ": ".$data_cust[0]->alamat?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">NPWP </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$data_cust[0]->npwp?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php
                            $tglinv=date('Y-m-d');
                            ?>
                            <label for="tgl_inv" class="col-sm-4 control-label">Tgl Invoice :</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tgl_inv" id="tgl_inv" class="form-control input-sm datepicker" value="<?php echo $tglinv?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                         <div class="form-group ">
                            <?php $tgldo=date('Y-m-d')?>
                            <label for="tgldo" class="col-sm-4 control-label">Alamat NPWP </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$data_cust[0]->alamat_npwp?>

                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">T.O.P </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ': 45 Hari'?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">Tgl Jatuh Tempo </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php
                                $tglnow = date('Y-m-d');
                                $jthtempo = date('d M Y',strtotime('+45 days',strtotime($tglnow)));
                                echo ": ".$jthtempo;
                                ?>
                                <input type="hidden" name="tgljthtempo" value="<?php echo date('Y-m-d',strtotime($jthtempo))?>">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">FAKTUR PAJAK</h3>
				</div>
				<div class="box-body">
					<div class='form-group row'>
						<div class="col-sm-6">
							<label class='control-label col-sm-4'><b>Kode Faktur</b></label>
							<div class='col-sm-8'>
								 <div class="input-group">
									<span class="input-group-addon"><i class="fa fa-bookmark"></i></span>

										<?php
											echo form_dropdown('kode_faktur',$faktur, '010', array('id'=>'kode_faktur','class'=>'form-control input-sm'));
										?>

								</div>
							</div>
						</div>
						<div class="col-sm-6">

							<label class='label-control col-sm-4'><b>Biaya Materai</b></label>
							<div class='col-sm-8'>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
									<?php
										echo form_dropdown('materai',array('N'=>'EXCLUDE','Y'=>'INCLUDE'), 'N', array('id'=>'materai','class'=>'form-control input-sm'));
									?>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>

        </div>
    </div>
</div>
<div class="box box-default ">
    <div class="box-body">

        <table id="deliveryorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th width="15%">No. DO</th>
					 <th width="15%">Salesman</th>
                    <th width="15%">No. SO</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th width="10%">Qty Supply</th>
                    <th width="10%">Harga</th>
                    <th width="5%">Diskon</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = $i =0;
				if($records){
					foreach($records as $keys=>$vals){
						$i++;
						//echo"<input type='hidden' name='det_do[$i]' value='".$vals['no_do']."'>";
						$kode_sales		= $vals['id_salesman'];
						$salesman		= $vals['nm_salesman'];

						foreach($vals['detail_data'] as $key=>$values){
							$n++;
							$qty_supply			= $values[qty_supply];
							$kunci 		= array('no_so'=>$values['no_so'],'id_barang'=>$values[id_barang]);
							$detailso	= $this->Invoice_model->cek_data($kunci,'trans_so_detail');

							$harga_so			= $detailso->harga;
							$qty_so				= $detailso->qty_supply;
							$diskon_so			= $detailso->diskon;

							$discount_satuan	= 0;
							if($diskon_so > 0){
								$discount_satuan	= round($diskon_so / $qty_so);
							}

							$dpp_barang			= $qty_supply * $harga_so;
							$diskon_barang		= $qty_supply * $discount_satuan;
							$harga_bersih		= $dpp_barang - $diskon_barang;
							$grand 				+= $harga_bersih;
							echo"<tr>";
								if(!empty($kode_sales) && $kode_sales !='-'){
									echo form_input(array('id'=>'id_salesman','name'=>'id_salesman','class'=>'form-control input-sm','type'=>'hidden'),$kode_sales);
									echo form_input(array('id'=>'nm_salesman','name'=>'nm_salesman','class'=>'form-control input-sm','type'=>'hidden'),$salesman);

								}
								echo form_input(array('id'=>'det_do_'.$i,'name'=>'det_do['.$i.']','class'=>'form-control input-sm','type'=>'hidden'),$vals['no_do']);
								echo"<td class='text-center'>".$n."</td>";
								echo"<td class='text-center'>".$values['no_do']."</td>";
								echo"<td class='text-center'>".$salesman."</td>";
								echo"<td class='text-center'>".$values[no_so]."</td>";
								echo"<td class='text-left'>".$values[id_barang].' / '.$values[nm_barang]."</td>";
								echo"<td class='text-center'>".$values[satuan]."</td>";
								echo"<td class='text-center'>".$qty_supply."</td>";
								echo"<td class='text-center'>".number_format($harga_so)."</td>";
								echo"<td class='text-center'>".number_format($discount_satuan)."</td>";
								echo"<td class='text-center'>".number_format($harga_bersih)."</td>";
							echo"</tr>";
						}


					}
				}
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-right"><b>GRAND TOTAL</b></td>
                    <td colspan="2" class="text-right"><b><?php echo formatnomor($grand)?></b></td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_inv()" type="button">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" id="proses_inv">
            <i class="fa fa-save"></i><b> Simpan Data Invoice</b>
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
        $("#supir_do,#kendaraan_do").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        /*
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        */
        $('#tgl_inv').datepicker({
            startDate: 'm',
            endDate: '+2d',
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true,
            maxViewMode: 0
         });
        var dataTableItem = $('#deliveryorderitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });

		$('#proses_inv').click(function(e){
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

							var formData 	= $('#form_proses').serialize();
							//var formData 	=new FormData($('#form_proses')[0]);
							var baseurl=base_url + active_controller +'/saveheaderinvoice';
							$.ajax({
								url			: baseurl,
								type		: "POST",
								data		: formData,
								//cache		: false,
								dataType	: 'json',
								//processData	: false,
								//contentType	: false,
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
        window.location.href = siteurl+"invoice";
    }
</script>
