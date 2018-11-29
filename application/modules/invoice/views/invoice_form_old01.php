<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form method="POST" id="form_proses">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
               	<?php //print_r($kode_customer)?>
                <div class="box-body">
                    <div class="col-sm-6 form-horizontal">
                        <div class="row">
                          <div class="form-group">
                              <!--div class="col-sm-8" style="padding-top: 8px;">
                                  <?php echo ": ".$data_cust[0]->nm_customer?>
                                  <input type="hidden" name="idcustomer_do" value="<?php echo $data_cust[0]->id_customer?>">
                                  <input type="hidden" name="nmcustomer_do" value="<?php echo $data_cust[0]->nm_customer?>">
                              </div-->
                              <label for="idcustomer_do" class="col-sm-4 control-label">Nama Customer </font></label>
                              <div class="col-sm-8">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                  <select id="idcustomer_do" name="idcustomer_do" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getcustomer()">
                                    <option value=""></option>
                                    <?php
                                    foreach(@$customer as $kc=>$vc){
                                    ?>
                                      <option value="<?php echo $vc->id_customer; ?>" <?php echo set_select('nmcustomer_do', $vc->id_customer, isset($data_cust[0]->nm_customer) && $data_cust[0]->id_customer == $vc->id_customer) ?>>
                                          <?php echo $vc->id_customer.' , '.$vc->nm_customer ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                  <input type="hidden" name="nmcustomer_do" id="nmcustomer_do" value="<?php echo $data_cust[0]->nm_customer?>">
                                </div>
                              </div>

                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group">
                            <label for="alamat" class="col-sm-4 control-label">Alamat Customer </font></label>
                            <div class="col-sm-8" >
                              <?php
                              $alamat		= (isset($data_cust[0]->alamat_npwp) && $data_cust[0]->alamat_npwp)?$data_cust[0]->alamat_npwp:$data_cust[0]->alamat;
                              echo '
                              <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <textarea name="alamat" class="form-control input-sm" id="alamat" height=100 readonly>'.$data_cust[0]->alamat.'</textarea>
                              </div>'
                              ?>
                            </div>
                          </div>

                        </div>

                        <div class="row">
                          <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">NPWP </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                              <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <input type="text" name="npwp" class="form-control input-sm" id="npwp" value="<?php echo ": ".$data_cust[0]->npwp?>" readonly>
                              </div>

                            </div>
                          </div>

                        </div>

                        <div class="row">
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
                    </div>

                    <div class="col-sm-6 form-horizontal">
                         <div class="form-group ">
                            <?php $tgldo=date('Y-m-d')?>
                            <label for="tgldo" class="col-sm-4 control-label">Alamat NPWP </label>
                            <div class="col-sm-8">

                                <?php
                                echo '
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                <textarea name="alamat_npwp" class="form-control input-sm" id="alamat_npwp" height=100 readonly>'.$data_cust[0]->alamat_npwp.'</textarea>
                                </div>'
                                ?>

                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">T.O.P </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                              <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <!--input type="text" name="npwp" class="form-control input-sm" id="npwp" value="<?php echo ": ".@$records[0]['top']->top?>" readonly-->
                              <?php $key = implode(",",$records[0]);

                              $val = explode(",",$key);
                              ?>
                              <input type="text" name="npwp" class="form-control input-sm" id="npwp" value="<?php echo $records[0]['detail_data'][0]['top']; ?>" readonly>
                              </div>

                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">Tgl Jatuh Tempo </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php
                                $tglnow = date('Y-m-d');
                                $jthtempo = date('d M Y',strtotime('+'.$records[0]['detail_data'][0]['top'].' days',strtotime($tglnow)));
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
										echo form_dropdown('materai',array('Y'=>'EXCLUDE','N'=>'INCLUDE'), 'N', array('id'=>'materai','class'=>'form-control input-sm'));
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
					          <th width="10%">Salesman</th>
                    <!--<th width="15%">No. SO</th>-->
                    <th width="15%">Item Barang</th>
                    <th width="7">Satuan</th>
                    <th width="7%">Qty Supply</th>
                    <th width="10%">Harga Normal</th>
                    <th width="10%">Harga Diskon</th>
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
							$qty_supply			= $values['qty_supply'];
							$kunci 		= array('no_so'=>$values['no_so'],'id_barang'=>$values[id_barang]);
							$detailso	= $this->Invoice_model->cek_data($kunci,'trans_so_detail');
              $headerso	= $this->Invoice_model->cek_data(array('no_so'=>$values['no_so']),'trans_so_header');
              
              $harga_so     = $detailso->harga_normal;
							$harga_diskon	= $detailso->harga;
							$qty_so				= $detailso->qty_supply;
							$diskon_so			= $detailso->diskon;

							$discount_satuan	= 0;
							if($diskon_so > 0){
								$discount_satuan	= round($diskon_so / $qty_so);
							}

							$dpp_barang			= $qty_supply * $harga_diskon;
							$diskon_barang		= $diskon_so;
							//$diskon_barang		= $qty_supply * $discount_satuan;
							$harga_bersih		= $dpp_barang - $diskon_barang;
							//$grand 				+= $harga_bersih;
              $grand 				+= $dpp_barang;
							echo"<tr>";
								if(!empty($kode_sales) && $kode_sales !='-'){
									echo form_input(array('id'=>'id_salesman','name'=>'id_salesman','class'=>'form-control input-sm','type'=>'hidden'),$kode_sales);
									echo form_input(array('id'=>'nm_salesman','name'=>'nm_salesman','class'=>'form-control input-sm','type'=>'hidden'),$salesman);

								}
								echo form_input(array('id'=>'det_do_'.$i,'name'=>'det_do['.$i.']','class'=>'form-control input-sm','type'=>'hidden'),$vals['no_do']);
                $nodo = $vals['no_do'];
								echo"<td class='text-center'>".$n."</td>";
								echo"<td class='text-center'>".$values['no_do']."</td>";
								echo"<td class='text-center'>".$salesman."</td>";
								//echo"<td class='text-center'>".$values[no_so]."</td>";
								echo"<td class='text-left'>".$values[id_barang].' / '.$values[nm_barang]."</td>";
								echo"<td class='text-center'>".$values[satuan]."</td>";
								echo"<td class='text-center'>".$qty_supply."</td>";
                echo"<td class='text-center'>".number_format($harga_so)."</td>";
								echo"<td class='text-center'>".number_format($harga_diskon)."</td>";
								echo"<td class='text-center'>".number_format($diskon_so)."</td>";
								echo"<td class='text-center'>".number_format($dpp_barang)."</td>";
							echo"</tr>";
						}


					}
				}
                ?>
            </tbody>
            <input type="hidden" name="nd" value="<?php echo $nodo?>">
            <tfoot>
                <tr>
                    <td colspan="8" class="text-right"><b>DPP :</b></td>
                    <td colspan="2" class="text-right"><b><?php echo formatnomor($grand)?></b></td>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">Diskon Toko (<?php echo formatnomor($headerso->persen_diskon_toko)?>%): </th>
                    <th colspan="2" class="text-right"><b><?php echo formatnomor($headerso->diskon_toko)?></b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">Diskon Cash (<?php echo formatnomor($headerso->persen_diskon_cash)?>%): </th>
                    <th colspan="2" class="text-right"><b><?php echo formatnomor($headerso->diskon_cash)?></b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">PPN : </th>
                    <th colspan="2" class="text-right"><b><?php echo formatnomor($headerso->ppn)?></b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">GRAND TOTAL : </th>
                    <th colspan="2" class="text-right"><b><?php echo formatnomor($headerso->total)?></b></th>
                    <th></th>
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
	var base_url			= siteurl;
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(document).ready(function(){
		//console.log(active_controller);
        $("#idcustomer_do,#supir_do,#kendaraan_do").select2({
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
					  title: "Anda Yakin?",
					  text: "You will not be able to process again this data!",
					  type: "warning",
					  showCancelButton: true,
					  confirmButtonClass: "btn-danger",
					  confirmButtonText: "Ya Lanjutkan",
					  cancelButtonText: "Batal",
					  closeOnConfirm: false,
					  closeOnCancel: false,
					  showLoaderOnConfirm: true
					},
					function(isConfirm) {
					  if (isConfirm) {

							//var formData 	= $('#form_proses').serialize();
							var formData 	=new FormData($('#form_proses')[0]);
							//console.log(formData);return false;
							var baseurl=base_url + active_controller +'/saveheaderinvoice';
							//console.log(baseurl);return false;
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
						swal("Batal Proses", "Data bisa diproses nanti", "error");
						return false;
					  }
				});
		});
    });
    function getcustomer(){
        var idcus = $('#idcustomer_do').val();
        if(idcus != ''){
           $.ajax({
                type:"GET",
                url:siteurl+"invoice/get_customer",
                data:"idcus="+idcus,
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nmcustomer_do').val(data.nm_customer);
                    //$('#diskontoko').text(formatCurrency(data.diskon_toko*parseInt($('#grandtotalso').val())/100,',','.',0));
                    $('#alamat').html(data.alamat);
                    $('#npwp').val(data.npwp);
                    $('#alamat_npwp').html(data.alamat_npwp);
                    //$('#persen_diskon_toko').val(data.diskon_toko*parseInt($('#grandtotalso').val())/100);

                }
            });
        }
    }
    function kembali_inv(){
        window.location.href = siteurl+"invoice";
    }
</script>
