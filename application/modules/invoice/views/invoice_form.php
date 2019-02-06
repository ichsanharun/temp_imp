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
                                  <?php echo ": ".$data_cust->nm_customer?>
                                  <input type="hidden" name="idcustomer_do" value="<?php echo $data_cust->id_customer?>">
                                  <input type="hidden" name="nmcustomer_do" value="<?php echo $data_cust->nm_customer?>">
                              </div-->
                              <label for="idcustomer_do" class="col-sm-4 control-label">Nama Customer </font></label>
                              <div class="col-sm-8">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                  <select id="idcustomer_do" name="id_customer" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getcustomer()">
                                    <option value=""></option>
                                    <?php
                                    foreach($customer as $kc=>$vc){
                                    ?>
                                      <option value="<?php echo $vc->id_customer; ?>" <?php if($data_cust->id_customer == $vc->id_customer ){echo "selected";} ?>>
                                          <?php echo $vc->id_customer.' , '.$vc->nm_customer ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                  <input type="hidden" name="nm_customer" id="nmcustomer_do" value="<?php echo $data_cust->nm_customer?>">
                                </div>
                              </div>

                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group">
                            <label for="alamat" class="col-sm-4 control-label">Alamat Customer </font></label>
                            <div class="col-sm-8" >
                              <?php
                              $alamat		= (isset($data_cust->alamat_npwp) && $data_cust->alamat_npwp)?$data_cust->alamat_npwp:$data_cust->alamat;
                              echo '
                              <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <textarea name="alamatcustomer" class="form-control input-sm" id="alamat" height=100 readonly>'.$data_cust->alamat.'</textarea>
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
                              <input type="text" name="npwpcustomer" class="form-control input-sm" id="npwp" value="<?php echo " ".$data_cust->npwp?>" readonly>
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
                                <input type="text" name="tanggal_invoice" id="tgl_inv" class="form-control input-sm datepicker" value="<?php echo $tglinv?>" readonly>
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
                                <textarea name="alamat_npwp" class="form-control input-sm" id="alamat_npwp" height=100 readonly>'.$data_cust->alamat_npwp.'</textarea>
                                </div>'
                                ?>

                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">T.O.P </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                              <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>

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
                                <input type="hidden" name="tgljatuhtempo" value="<?php echo date('Y-m-d',strtotime($jthtempo))?>">
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
                    <th width="5%">Diskon</th>
                    <th width="10%">Harga Stelah Diskon</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = $i =0;
				if($records){

					foreach($records as $keys => $vals){
						$i++;
						//echo"<input type='hidden' name='det_do[$i]' value='".$vals['no_do']."'>";
						$kode_sales		= $vals['id_salesman'];
						$salesman		= $vals['nm_salesman'];
            echo $keys."<br>";
						foreach($vals['detail_data'] as $values){
							$n++;
							$qty_supply			= $values['qty_supply'];
							$kunci 		= array('no_so'=>$values['no_so'],'id_barang'=>$values[id_barang]);
							$detailso	= $this->Invoice_model->cek_data($kunci,'trans_so_detail');
              $headerso	= $this->Invoice_model->cek_data(array('no_so'=>$values['no_so']),'trans_so_header');

              $harga_normal       = $detailso->harga_normal;

              if ($headerso->ppn > 0) {
                //$harga  = $detailso->harga_normal/110*100;
                $ppn    = $harga_normal - $harga;
                $ppn_all = $ppn*$qty_supply;
              }else {
                $harga = $harga_normal;
              }
              $harga              = $harga_normal;
              $diskon_std_persen  = $detailso->diskon_persen;
              $diskon_std_rp      = $diskon_std_persen/100*$harga_normal;
              $harga_setelah_diskon_std = $harga_normal - $diskon_std_rp;

              $diskon_promo_persen= $detailso->diskon_promo_persen;
              $diskon_promo_rp    = $detailso->diskon_promo_persen/100*$harga_setelah_diskon_std;
              $harga_setelah_diskon_promo = $harga_setelah_diskon_std - $diskon_promo_rp;

              $diskon_so = $detailso->diskon_so;
              $tipe_diskon_so = $detailso->tipe_diskon_so;
              if ($tipe_diskon_so == "rupiah_tambah") {
                $harga_setelah_diskon_so = $harga_setelah_diskon_promo + $diskon_so;
                $tampil_diskon_so = "+Rp ".number_format($diskon_so);
              }elseif ($tipe_diskon_so == "rupiah_kurang") {
                $harga_setelah_diskon_so = $harga_setelah_diskon_promo - $diskon_so;
                $tampil_diskon_so = "-Rp ".number_format($diskon_so);
              }else {
                $harga_setelah_diskon_so = $harga_setelah_diskon_promo*(100-$diskon_so)/100;
                $tampil_diskon_so = $diskon_so." %";
              }
              //-------------------------END OF HARGA------------------------//
              $diskon_toko        = $headerso->persen_diskon_toko;
              $diskon_toko_rp     = $diskon_toko/100*$harga_setelah_diskon_so;
              $diskon_toko_rp_all = $diskon_toko_rp*$qty_supply;
              $harga_setelah_diskon_toko = $harga_setelah_diskon_so - $diskon_toko_rp;

              $diskon_cash        = $headerso->persen_diskon_cash;
              $diskon_cash_rp     = $diskon_cash/100*$harga_setelah_diskon_toko;
              $diskon_cash_rp_all = $diskon_cash_rp*$qty_supply;
              $harga_setelah_diskon_cash = $harga_setelah_diskon_toko - $diskon_cash_rp;

              $hargajualbefdis += $harga*$qty_supply;
              $hargajualafterdistoko += $harga_setelah_diskon_toko*$qty_supply;
              $dpp_sebelum += $harga_setelah_diskon_so*$qty_supply;

							$dpp_barang			= $qty_supply * $harga_setelah_diskon_cash;
							$diskon_barang		= $diskon_so;
							//$diskon_barang		= $qty_supply * $discount_satuan;
							$harga_bersih		= $dpp_barang - $diskon_barang;
							//$grand 				+= $harga_bersih;
              $grand_diskon_toko +=$diskon_toko_rp_all;
              $grand_diskon_cash +=$diskon_cash_rp_all;
              $grand_ppn += $ppn_all;
              $grand_setelah_toko += $harga_setelah_diskon_toko*$qty_supply;
              $grand 				+= $dpp_barang;
              $grand = ceil($grand);

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
                echo"<td class='text-center'>".number_format($harga_normal)."</td>";
                echo"<td class='text-center'>
                  ".number_format($diskon_std_persen)."% ,
                  ".$tampil_diskon_so."
                </td>";
								echo"<td class='text-center'>".number_format($harga_setelah_diskon_so)."</td>";
								echo"<td class='text-center'>".number_format($harga_setelah_diskon_so*$qty_supply)."</td>";
                ?>
                  <input type="hidden" name="id[]" value="<?php echo $n?>">
                  <input type="hidden" name="id_barang[]" value="<?php echo $values['id_barang']?>">
                  <input type="hidden" name="nm_barang[]" value="<?php echo $values['nm_barang']?>">
                  <input type="hidden" name="jumlah[]" value="<?php echo $qty_supply?>">
                  <input type="hidden" name="hargajual[]" value="<?php echo $harga_normal?>">
                  <input type="hidden" name="persen_diskon_stdr[]" value="<?php echo $diskon_std_persen?>">
                  <input type="hidden" name="harga_after_diskon_stdr[]" value="<?php echo $harga_setelah_diskon_std?>">
                  <input type="hidden" name="diskon_promo_persen[]" value="<?php echo $diskon_promo_persen?>">
                  <input type="hidden" name="diskon_promo_persen_rpnya[]" value="<?php echo $diskon_promo_rp?>">
                  <input type="hidden" name="tipe_diskon_so[]" value="<?php echo $tipe_diskon_so?>">
                  <input type="hidden" name="diskon_so[]" value="<?php echo $diskon_so?>">
                  <input type="hidden" name="harga_nett_dari_so[]" value="<?php echo $values['subtotal']?>">
                  <input type="hidden" name="harga_nett[]" value="<?php echo $harga_setelah_diskon_so?>">
                  <input type="hidden" name="subtot_bef_diskon[]" value="<?php echo $harga*$qty_supply?>">
                  <input type="hidden" name="subtot_after_diskon[]" value="<?php echo $harga_setelah_diskon_so*$qty_supply?>">
                  <input type="hidden" name="ppn[]" value="<?php echo $headerso->ppn?>">
                  <input type="hidden" name="tgljual[]" value="<?php echo $headerso->tanggal?>">
                  <input type="hidden" name="no_do[]" value="<?php echo $values['no_do']?>">

                <?php
							echo"</tr>";

						}


					}
				}
        $dpp = $grand/(100 + $headerso->ppn)*100;
        $pn = $grand-$dpp;
                ?>

            </tbody>
            <input type="hidden" name="nd" value="<?php echo $nodo?>">
            <input type="hidden" name="id_salesman" value="<?php echo $values['id_salesman']?>">
            <input type="hidden" name="nm_salesman" value="<?php echo $values['nm_salesman']?>">
            <input type="hidden" name="diskon_toko_persen" value="<?php echo $diskon_toko?>">
            <input type="hidden" name="diskon_cash_persen" value="<?php echo $diskon_cash?>">
            <input type="hidden" name="hargajualbefdis" value="<?php echo ceil($hargajualbefdis)?>">
            <input type="hidden" name="hargajualafterdis" value="<?php echo ceil($dpp_sebelum)?>">
            <input type="hidden" name="hargajualafterdistoko" value="<?php echo ceil($hargajualafterdistoko)?>">
            <input type="hidden" name="hargajualafterdiscash" value="<?php echo ceil($grand)?>">
            <input type="hidden" name="diskon_stdr_rp" value="<?php echo ceil($diskon_std_rp*$qty_supply)?>">
            <input type="hidden" name="dpp" value="<?php echo ceil($dpp)?>">
            <input type="hidden" name="n_ppn" id="n_ppn" value="<?php echo (Int)$pn?>">
            <input type="hidden" name="hargajualtotal" id="n_grand" value="<?php echo ceil($grand)?>">

            <tfoot>
                <tr>
                    <td colspan="8" class="text-right"><b>SubTotal :</b></td>
                    <td colspan="2" class="text-right"><b><?php echo formatnomor($dpp_sebelum)?></b></td>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">Diskon Toko (<?php echo formatnomor($headerso->persen_diskon_toko)?>%): </th>
                    <th colspan="2" class="text-right"><b><?php echo formatnomor($grand_diskon_toko)?>-</b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">Diskon Cash (<?php echo formatnomor($headerso->persen_diskon_cash)?>%): </th>
                    <th colspan="2" class="text-right" style="border-bottom:solid 2px #000"><b><?php echo formatnomor($grand_diskon_cash)?>-</b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">DPP : </th>
                    <th colspan="2" class="text-right"><b><?php echo formatnomor($dpp)?></b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">PPN : </th>
                    <th colspan="2" class="text-right" style="border-bottom:solid 2px #000"><b><?php echo formatnomor($pn)?>+</b></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="8" class="text-right">GRAND TOTAL : </th>
                    <th colspan="2" class="text-right"><b><span id="text-grand"><?php echo formatnomor($grand)?></span></b></th>
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
        if ($("#n_ppn_old").val() == 0) {
          var x = <?php echo $grand;?>;
          var string_harga = x.toString();
          var cek = parseInt(string_harga.substr(-3));
          if (cek > 0) {
            var pembantu = 1000 - cek;
            var hasil = parseInt(x) + parseInt(pembantu);
            //return hasil;
          $("#n_grand").val(hasil);
          $("#text-grand").text(hasil);
        }else {
          $("#n_grand").val(x);
          $("#text-grand").text(x);
        }
          //alert(hasil);
        }
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
    function pembulatan(x){
      var string_harga = x.toString();
      var cek = parseInt(string_harga.substr(-3));
      if (cek > 0) {
        var pembantu = 1000 - cek;
        var hasil = parseInt(x) + parseInt(pembantu);
        return hasil;
      }else {
        return x;
      }
    }
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
