<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form method="POST" id="form_proses">
  <input type="hidden" name="no_rk" value="<?=$this->input->get('n')?>">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
               	<?php //print_r($kode_customer)?>
                <div class="box-body">
                    <div class="col-sm-6 form-horizontal">
                        <div class="row">
                          <div class="form-group">
                            <label for="idcustomer_do" class="col-sm-4 control-label">Nama Customer </font></label>
                              <div class="col-sm-8" style="padding-top: 8px;">
                                  <?php echo ": ".$ambil_rk_head->nm_customer?>
                                  <input type="hidden" name="idcustomer_do" value="<?php echo $ambil_rk_head->id_customer?>">
                                  <input type="hidden" name="nmcustomer_do" value="<?php echo $ambil_rk_head->nm_customer?>">
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
                              <textarea name="alamatcustomer" class="form-control input-sm" id="alamat" height=100 readonly>'.$ambil_rk_head->alamat_customer.'</textarea>
                              </div>'
                              ?>
                            </div>
                          </div>

                        </div>



                        <div class="row">
                          <div class="form-group ">
                            <?php
                            $tglinv=date('Y-m-d');
                            ?>
                            <label for="tgl_inv" class="col-sm-4 control-label">No. Retur</label>
                            <div class="col-sm-8" style="padding-top: 6px;">
                              <?php echo ": ".$ambil_rk_head->no_retur?>
                            </div>
                          </div>

                        </div>
                    </div>

                    <div class="col-sm-6 form-horizontal">
                      <div class="form-group ">
                        <?php
                        $tglinv=date('Y-m-d');
                        ?>
                        <label for="tgl_inv" class="col-sm-4 control-label">No. Invoice</label>
                        <div class="col-sm-8" style="padding-top: 6px;">
                          <?php echo ": ".$ambil_rk_head->no_invoice?>
                        </div>
                      </div>
                      <div class="form-group ">
                        <?php
                        $tglinv=date('Y-m-d');
                        ?>
                        <label for="tgl_inv" class="col-sm-4 control-label">Tgl Proses Retur :</label>
                        <div class="col-sm-8">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="tgl_retur" id="tgl_retur" class="form-control input-sm datepicker" value="<?=$tglinv?>">
                          </div>
                        </div>
                      </div>
                      <div class="form-group ">
                        <?php
                        $tglinv=date('Y-m-d');
                        ?>
                        <label for="tgl_inv" class="col-sm-4 control-label">Tgl Kirim :</label>
                        <div class="col-sm-8">
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="tgl_kirim" id="tgl_kirim" class="form-control input-sm datepicker" value="<?=$tglinv?>">
                          </div>
                        </div>
                      </div>
                        <!-- Data Keterangan -->
                        <div class="form-group ">
                          <label for="keterangan" class="col-sm-4 control-label">Keterangan<font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-file"></i></span>
                              <textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan"></textarea>
                            </div>
                          </div>
                        </div>
                        <!-- Data Keterangan -->
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
                    <!--th width="15%">No. DO</th-->
					          <!--th width="10%">Salesman</th-->
                    <!--<th width="15%">No. SO</th>-->
                    <th width="15%">Item Barang</th>
                    <!--th width="7">Satuan</th-->
                    <th width="7%">Qty Retur</th>
                    <th width="10%">Qty Tukar</th>
                    <th width="5%">Qty Uang Kembali</th>
                    <th width="10%">Nilai Uang Kembali</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = $i =0;
                $hargalandedtotal = 0;
                $tot_rk = 0;
				if($ambil_rk_detail){

					foreach($ambil_rk_detail as $keys => $vals){
						$i++;


							echo"<tr>";

								echo"<td class='text-center'>".$i."</td>";
								echo"<td class='text-left'>".$vals->id_barang.' / '.$vals->nm_barang."</td>";
								echo"<td class='text-center'>".($vals->ganti+$vals->qty_uang)."</td>";
								echo"<td class='text-center'>".$vals->ganti."</td>";
                echo"<td class='text-center'>".number_format($vals->qty_uang)."</td>";
                echo"<td class='text-center'>".$vals->uang."</td>";
                echo"<td class='text-center'>".number_format(($vals->ganti*$vals->harga_landed) + ($vals->qty_uang*$vals->uang))."</td>";
                $tot_rk += ($vals->ganti*$vals->harga_landed) + ($vals->qty_uang*$vals->uang);
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
                  <input type="hidden" name="hargalanded[]" value="<?php echo $detaillanded->landed_cost?>">

                <?php
							echo"</tr>";

					}


				}
        $dpp = $grand/(100 + $headerso->ppn)*100;
        $pn = $grand-$dpp;
                ?>

            </tbody>

            <tfoot>
                <tr>
                    <td colspan="6" class="text-right"><b>SubTotal :</b></td>
                    <td  class="text-right"><b><?php echo formatnomor($tot_rk)?></b></td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_inv()" type="button">
           <i class="fa fa-refresh"></i><b> Batal</b>
        </button>
        <button class="btn btn-primary" type="button" id="proses_rk">
            <i class="fa fa-save"></i><b> Simpan Data Retur Klaim</b>
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

        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });

        /*
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        */
        $('#tgl_retur').datepicker({
            //startDate: 'm',
            //endDate: '+2d',
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true,
            maxViewMode: 1
         });
        var dataTableItem = $('#deliveryorderitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });

		$('#proses_rk').click(function(e){
			  e.preventDefault();
        if ($('#tgl_inv').val() == "") {
          swal({
            title	: "TANGGAL RETUR TIDAK BOLEH KOSONG!",
            text	: "ISI TANGGAL INVOICE!",
            type	: "danger",
            timer	: 10000,
            showCancelButton	: false,
            showConfirmButton	: false,
            allowOutsideClick	: false
          });
        }else {

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
              var baseurl=base_url + active_controller +'/saveheaderrk';
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

                    if(data.status == 0){
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
        }
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
                    $('#npwpcustomer').val(data.npwp);
                    $('#alamat_npwp').html(data.alamat_npwp);
                    //$('#persen_diskon_toko').val(data.diskon_toko*parseInt($('#grandtotalso').val())/100);

                }
            });
        }
    }
    function kembali_inv(){
        window.location.href = siteurl+"returndo_claim/"+"cancel/"+'<?=$this->input->get('n')?>';
    }

 $("#tgl_inv").change(function() {
   var date = new Date($("#tgl_inv").val()),
          days = parseInt($("#top").val());

       if(!isNaN(date.getTime())){
           date.setDate(date.getDate() + days);

           var yyyy = date.getFullYear().toString();
           var mm = (date.getMonth()+1).toString(); // getMonth() is zero-based
           var dd  = date.getDate().toString();

           $("#tgljatuhtempo").val(yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]));
       }

  });
</script>
