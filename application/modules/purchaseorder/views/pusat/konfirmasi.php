<!--link rel="stylesheet" href="<?php echo base_url('assets/css/radiobutton.css'); ?>"-->
<link href="<?= base_url(); ?>assets/css/switch.css" rel="stylesheet" />
<div class="nav-tabs-pr" id="top">
    <div class="tab-content">
        <div class="tab-pane active" id="pr">
            <div class="box box-primary">
                <form id="form-header-pr" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">NO. PO</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                : <?= $no_pr; ?>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="idcabang" class="col-sm-4 control-label">Nama Cabang <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8" style="padding-top: 11px;">
                              <?php
                              $session = $this->session->userdata('app_session');
                              $caba = $this->Purchaserequest_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
                               echo ': '.$caba->kdcab.', '.$caba->namacabang; ?>
                               <input type="hidden" name="kdcab" id="kdcab" class="form-control input-sm" value="<?php echo $caba->kdcab; ?>">
                              <input type="hidden" name="namacabang" id="namacabang" class="form-control input-sm" value="<?php echo $caba->namacabang; ?>">
                          </div>
                        </div>
                        <?php
                        $queryc = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$supplier'");

                        $rowc = $queryc->row();
                        ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Supplier</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                : <?= $rowc->nm_supplier; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Alamat Supplier</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                : <?= $rowc->alamat; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Freight Condition</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <select class="form-control input-sm" name="pilihan">
                                    <option value='FOB' selected>FOB</option>
                                    <option value='CIF'>CIF</option>
                                    <option value='CNF'>CNF</option>
                                    <option value='EXWORK'>EXWORK</option>
                                    <option value='DOOR TO DOOR'>DOOR TO DOOR</option>
                                 </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Payment Term</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <textarea class="form-control" rows="3" name="term" ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">NO. PI</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <input type="text" name="no_pi" id="no_pi" class="form-control input-sm" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                KURS
                            </label>
                            <?php
                            $kurs_rmb = $this->Purchaseorder_model->cek_data(array("kode"=>"RMB"),'mata_uang');
                            $kurs_usd = $this->Purchaseorder_model->cek_data(array("kode"=>"USD"),'mata_uang');
                            $usd_to_rmb = $kurs_usd->kurs/$kurs_rmb->kurs;
                             ?>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <table width="100%" class="table-bordered">
                                    <tr>
                                        <td style="border-bottom:2px solid #000 !important">
                                            <strong>USD</strong>

                                            to <strong>RMB</strong>
                                        </td>
                                        <td style="border-bottom:1px solid #000 !important">
                                            <input id="kurs_usd" name="kurs_usd" value="<?=$usd_to_rmb?>"  />
                                        </td>
                                        <td rowspan="2">
                                          <a href="#modals-kurs" data-toggle="modal" class="btn btn-sm" onclick="change_kurs()"><span class="badge bg-primary">Change<br>Kurs</span></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>USD</strong>

                                            to <strong>Rupiah</strong>
                                        </td>
                                        <td>
                                            <input id="kurs_rp" name="kurs_rp" value="<?=$kurs_usd->kurs?>"  />
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <strong>Pembayaran Menggunakan:</strong>
                                      </td>
                                      <td>
                                        <div class="form-group" style="margin-left:5%">
                                          <div class="radio-inline">
                                            <label>
                                              <input type="radio" class="radio_paycon" name="opsi_top" value="rmb" onclick="get_paycon(this.value)">RMB
                                            </label>
                                          </div>
                                          <div class="radio-inline">
                                            <label>
                                              <input type="radio" class="radio_paycon" name="opsi_top" value="dollar" onclick="get_paycon(this.value)" checked>Dollar
                                            </label>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">PPN</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <select id="ppnD">
                                    <option value='yes' selected>Yes</option>
                                    <option value='no'>No</option>

                                 </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">REF TO</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <select class="form-control input-sm" name="ref_to">
                                    <option value='Tanjung Priok, Jakarta' selected>Tanjung Priok, Jakarta</option>
                                    <option value='Tanjung Perak, Surabaya'>Tanjung Perak, Surabaya</option>
                                 </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Lead time of delivery</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <textarea class="form-control" rows="3" name="lead" ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Ocean Freight</label>
                            <div class="col-sm-8" style="">
                              <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="text" name="shipping" id="shipping"  class="form-control input-sm" value="" onkeyup="this.value = this.value.match(/^[0-9]+$/)">
                              </div>
                            </div>
                        </div>



                    </div>

                </div>
                </div>
                   <input id="cbm_tot" type="hidden" name="cbm_tot" value="<?= $pr_hader->total_cbm; ?>">
                    <input id="no_pr" type="hidden" name="no_pr" value="<?= $no_pr; ?>"/>
                    <input id="supplier" type="hidden" name="idsupplier" value="<?= $supplier; ?>"/>

            </div>
        </div>
    </div>
</div>

<div class="box box-default ">
    <div class="box-body">
        <table id="TabelTransaksi" class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th>QTY</th>
                    <th style="100px">QTY Proforma Invoice</th>
                    <th>Harga Beli (RMB)</th>
                    <th>Harga Beli (Dollar)</th>
                    <th>Harga Beli (Rupiah)</th>
                    <th>% Fiskal</th>
                    <th>Fiskal</th>
                    <th>No Fiskal</th>
                    <th>Ppn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;

                foreach (@$itembarang as $data => $datas) {
                    $no++; ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $datas->nm_barang; ?></td>
                        <td><?= $datas->qty_po; ?></td>
                        <td>
                            <input type="hidden" name="idet[]" value="<?= $datas->id_detail_po; ?>" />
                            <input id="qtyconfirm_<?php echo $no; ?>" class="form-control input-sm qtyconfirm" name="qty_acc[]" value="<?= $datas->qty_acc; ?>" />
                        </td>
                        <td>
                            <input id="harga_beli_<?php echo $no; ?>" class="form-control input-sm harga_beli" name="harga_satuan[]" onblur="findall()"  value="0"  />
                        </td>
                        <td>
                            <input id="usd_rubah_<?php echo $no; ?>" type="hidden" name="usd[]" class="form-control input-sm usd" value="0" readonly="">
                            <input  type="text"  name="usd_rubah[]" class="form-control input-sm usd_rubah" value="0" >
                        </td>
                        <td>
                            <input id="rupiah_rubah_<?php echo $no; ?>" type="hidden" name="rupiah[]" class="form-control input-sm rupiah" value="0" readonly="">
                            <input  type="text" name="rupiah_rubah[]" class="form-control input-sm rupiah_rubah" value="0" readonly="">
                        </td>
                        <td style="width: 50px">
                            <input style="width: 40px" class="form-control input-sm fiskal" name="fiskal[]" value="0" />
                        </td>
                        <th>
                            <input id="subtotal_rubah_<?php echo $no; ?>" type="hidden" class="form-control input-sm subtotal" name="subtotal[]"  readonly="" value="0"/>
                            <input  class="form-control input-sm subtotal_rubah" name="subtotal_rubah[]"  readonly="" value="0"/>
                        </th>
                        <th>
                            <input id="subtotal_no_rubah_<?php echo $no; ?>" type="hidden" class="form-control input-sm subtotal_no" name="subtotal_no[]"  readonly="" value="0"/>
                            <input  class="form-control input-sm subtotal_no_rubah" name="subtotal_no_rubah[]"  readonly="" value="0"/>
                        </th>
                        <th>
                            <input id="subtotal_ppn_rubah_<?php echo $no; ?>" type="hidden" class="form-control input-sm subtotal_ppn" name="subtotal_ppn[]"  readonly="" value="0"/>
                            <input  class="form-control input-sm subtotal_ppn_rubah" name="subtotal_ppn_rubah[]"  readonly="" value="0"/>
                        </th>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="3" style="text-align: center">
                        TOTAL
                    </td>
                    <td id="total_qty"></td>
                    <td id="total_harga_beli"></td>
                    <td id="total_dollar"></td>
                    <td>
                        <input type="hidden" id="total_rupiah" class="form-control input-sm" name="total_rupiah"  readonly="" value="0"/>
                        <input id="total_rupiah_rubah" class="form-control input-sm" name="total_rupiah_rubah"  readonly="" value="0"/>
                    </td>
                    <td></td>
                    <td>
                        <input type="hidden" id="total_fiskal" class="form-control input-sm" name="total_fiskal"  readonly="" value="0"/>
                        <input id="total_fiskal_rubah" class="form-control input-sm" name="total_fiskal_rubah"  readonly="" value="0"/>
                    </td>
                    <td>
                        <input type="hidden" id="total_nofiskal" class="form-control input-sm" name="total_nofiskal"  readonly="" value="0"/>
                        <input id="total_nofiskal_rubah" class="form-control input-sm" name="total_nofiskal_rubah"  readonly="" value="0"/>
                    </td>
                    <td>
                        <input type="hidden" id="total_ppn" class="form-control input-sm" name="total_ppn"  readonly="" value="0"/>
                        <input id="total_ppn_rubah" class="form-control input-sm" name="total_ppn_rubah"  readonly="" value="0"/>
                    </td>
                </tr>
            </tbody>
        </table>

     </form>
      <form id="form-payment" method="post">
        <div class="row">
            <div class="col-sm-1">
            </div>
             <div class="col-sm-8">
                <b>Trem of payment</b>
                <div class="input_fields_wrap">


                    <div class="row">
                        <div class="col-xs-1">
                          NO
                        </div>
                        <div class="col-sm-3">
                          <div class="form-group ">
                            <div class="radio-inline">
                              <label>
                                <input type="radio" class="radio_logo" name="opsi_top" value="persen" onclick="get_logo(this.value)">Persen
                              </label>
                            </div>
                            <div class="radio-inline">
                              <label>
                                <input type="radio" class="radio_logo" name="opsi_top" value="nominal" onclick="get_logo(this.value)" checked>Nominal
                              </label>
                            </div>
                          </div>

                        </div>
                        <div class="col-xs-4">
                          Tgl Bayar
                        </div>
                        <div class="col-xs-2">
                          <button class="add_field_button btn btn-primary"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                          1
                        </div>
                        <div class="col-xs-3">
                          <div class="input-group">
                            <span class="input-group-addon logo_currency">$</span>
                            <input type="text" name="pembayaran[]"  class="form-control input-sm" value="100" required="" onkeyup="this.value = this.value.match(/^-?\d*[.]?\d*$/);get_payterm()">
                          </div>
                        </div>
                        <div class="col-xs-4">
                            <input type="text" name="perkiraan_bayar[]" class="form-control pull-right datepickerxx"  value="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="col-xs-2">
                            &nbsp;
                        </div>
                    </div>
                </div>
             </div>
             <div class="col-sm-3">

             </div>
         </div>

         </form>
     </div>


     <table id="prdetailitem" class="table table-bordered table-striped" width="100%">

            <tfoot>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" onclick="saveheaderpr()"  >
                            <i class="fa fa-save"></i><b> Simpan Konfirmasi</b>
                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>
</div>

<div class="modal modal-primary" id="modals-kurs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Purchase Order (PO)</h4>
      </div>
      <div class="modal-body" id="MyModalBody">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>
<script>

  $(document).ready(function() {

    $("input[name='harga_satuan[]'],input[name='usd_rubah[]']").prop('readonly', true);
      if ($("input:radio.radio_paycon:checked").val() == 'dollar') {
        $("input[name='usd_rubah[]']").prop('readonly', false);
      }else {
        $("input[name='harga_satuan[]']").prop('readonly', false);
      }
	/*
    $(".toolt").click(function(){
     $(".toolt").tooltip('destroy');
    });
	
	
    $('#no_pi').tooltip({
     disabled: true,
     close: function( event, ui ) { $(this).tooltip('disable'); }
    });

    $('#no_pi').on('click', function () {
     $(this).tooltip('enable').tooltip('open');
   });
	*/
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID

    //console.log(persen);

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
      e.preventDefault();
      if(x < max_fields){ //max input box allowed
        x++; //text box increment
        var persen          = $("input:radio.radio_logo:checked").val();
        ///console.log("a"+persen);
        if (persen == "persen") {
          var logo = "%";
        }else {
          var logo = "$";
        }


        $(wrapper).append('<div class="row">'+
        '<div class="col-xs-1">'+x+'</div>'+
        '<div class="col-xs-3">'+
        '<div class="input-group">'+
        '<span class="input-group-addon logo_currency">'+logo+'</span>'+
        '<input type="text" name="pembayaran[]"  class="form-control input-sm" value="100" required="" onkeyup="this.value = this.value.match(/^-?\\\d*[.]?\\\d*$/);get_payterm()">'+
        '</div>'+
        '</div>'+
        '<div class="col-xs-4"><input type="text" name="perkiraan_bayar[]" class="form-control pull-right "  id="datepickerxxr'+x+'"  value="<?= date('Y-m-d'); ?>"></div>'+
        '<a href="#" class="remove_field">Remove</a>'+
        '</div>'); //add input box
        $('#datepickerxxr'+x).datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true
        });
      }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
      e.preventDefault(); $(this).parent('div').remove(); x--;
    })


  });

    function get_payterm(){
      var persen          = $("input:radio.radio_logo:checked").val();
	  var total_rupiah	  = $("#total_rupiah").val().replace(/\,/g,'');
	  var kurs_rupiah	  = $("#kurs_rp").val().replace(/\,/g,'');
      var nominal         = parseFloat(total_rupiah) / parseFloat(kurs_rupiah);
      var values          = $("input[name='pembayaran[]']").map(function(){return $(this).val();}).get();
              //console.log(values);
      var pembayaran_all = 0;
      for (var i = 0; i < values.length; i++) {
        pembayaran_all = pembayaran_all + parseFloat(values[i]);
      }
      if (persen == "persen") {
        if (pembayaran_all > 100) {
          swal({
            title: "LIMIT 100%",
            text: "Tidak dapat lebih dari 100%",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
          });
          $("input[name='pembayaran[]']").val(0);
        }
      }else {
        if (pembayaran_all > nominal) {
          swal({
            title: "LIMIT $."+nominal,
            text: "Tidak dapat lebih dari $."+nominal,
            type: "warning",
            timer: 1500,
            showConfirmButton: false
          });
          $("input[name='pembayaran[]']").val(0);
        }
      }
      //console.log(pembayaran_all);
    }

    function get_logo(a){
      if (a == 'persen') {
        b = '%';
      }else {
        b = '$';
      }
      var x = document.getElementsByClassName("logo_currency");
      console.log(x);
      var i;
      for (i = 0; i < x.length; i++) {
          x[i].innerHTML= b;
      }
    }

    function change_kurs(){
      url = siteurl+'kurs';
      $.post(url,{'id':'1'},function(result){
        $("#MyModalBody").html(result);
      });
    }

    function saveheaderpr(){
		var test_nominal	= $("#total_dollar").text().replace(/\,/g,'');
		var persen          = $("input:radio.radio_logo:checked").val();
		var nominal         = parseFloat(test_nominal).format(2,3,',');
		var nominal_bayar	= parseFloat(test_nominal);
		var values          = $("input[name='pembayaran[]']").map(function(){return $(this).val();}).get();
              //console.log(values);
		var pembayaran_all = 0;
		for (var i = 0; i < values.length; i++) {
			pembayaran_all = pembayaran_all + parseFloat(values[i]);
		}
		var nomor_pi	= $('#no_pi').val();
		var tipe_ship	= $('#shipping').val();
		if(nomor_pi=='' || nomor_pi==null){
			swal({
			  title: "Peringatan!",
			  text: "Mohon Isi No PI terlebih dahulu...",
			  type: "warning",
			  timer: 2500,
			  showConfirmButton: false
			});
			return false;
			
		 }
		 if(tipe_ship=='' || tipe_ship==null){
			swal({
			  title: "Peringatan!",
			  text: "Mohon Isi Ocean Freight terlebih dahulu...",
			  type: "warning",
			  timer: 2500,
			  showConfirmButton: false
			});
			return false;
			
		 }
		 if(persen=='persen'){
			 if (pembayaran_all < 100) {
				swal({
				  title: "Peringatan!",
				  text: "Mohon Isi jumlah persen sampai dengan 100%",
				  type: "warning",
				  timer: 2500,
				  showConfirmButton: false
				});
				$("input[name='pembayaran[]']").val(0);
				return false;
			 }
		 }else{
			 if (pembayaran_all < nominal_bayar) {
				swal({
				  title: "PERINGATAN!",
				  text: "Mohon Isi jumlah nominal sesuai yang tertera hingga $."+nominal,
				  type: "warning",
				  timer: 2500,
				  showConfirmButton: false
				});
				$("input[name='pembayaran[]']").val(0);
				return false;
			 }
		 }
		 save();
     

    }
    function save(){
      var formdata = $("#form-header-pr, #form-payment").serialize();
      // console.log(formdata);
      $.ajax({
        url: siteurl+"purchaseorder/purchaseorder_pusat/konfirmasi_save",
        dataType : "json",
        type: 'POST',
        data: formdata,
        success: function(result){
          //console.log(result['msg']);
          if(result.save=='1'){
            swal({
              title: "Sukses!",
              text: result['msg'],
              type: "success",
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(function(){
              window.location.href=siteurl+'purchaseorder/purchaseorder_pusat';
            },1600);
          } else {
            swal({
              title: "Gagal!",
              text: result['msg'],
              type: "error",
              timer: 1500,
              showConfirmButton: false
            });
          };
        },
        error: function (request, error) {
          console.log(arguments);
          alert(" Can't do because: " + error);
        }
      });
    }

    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }

    function get_paycon(val){
      //cek=document.getElementById('kurs_usd').value;
      if(val=="dollar"){
        $(".usd_rubah").attr("readonly", false);
        $(".harga_beli").attr("readonly", true);
        $(".harga_beli").val(0);
      }else{
        $(".harga_beli").attr("readonly", false);
        $(".usd_rubah").attr("readonly", true);
        $(".usd_rubah").val(0);
      }
    }

    $("#kurs_rpx, #kurs_usdx").on("change paste keyup", function() {
        cek=document.getElementById('kurs_usd').value;
        if(cek=="0"){
             $(".usd_rubah").attr("readonly", false);
             $(".harga_beli").attr("readonly", true);
        }else{
             $(".harga_beli").attr("readonly", false);
             $(".usd_rubah").attr("readonly", true);
        }
    });


    function findall() {
          var array = document.getElementsByName('harga_satuan');
          var total = 0;
          var jum = <?php echo count($itembarang)+1; ?>;
          for (var i = 1; i < jum; i++) {
              total += parseInt(document.getElementById("harga_beli_"+i).value);
          }
          //console.log(parseInt(total));
        }

    $('.datepickerxx').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    })

    var jum = <?php echo count($itembarang); ?>;
	
	/*
	## ALI 05 MEI 2019 ##
	*/
    $('.harga_beli,.qtyconfirm, #kurs_rp,#kurs_usd,.usd_rubah, .fiskal').on('keyup', function(){
        var total_qty = 0;
        var total_harga_beli = 0;
        var total_dollar = 0;
        var total_rupiah = 0;
        var subtot_dollar = 0;
        var total_fiskal = 0;
        var total_nofiskal = 0;
        var total_ppn = 0;
        var payterm         = $("input:radio.radio_paycon:checked").val();
        ppn_cek				= document.getElementById('ppnD').value;
        usdK				= parseFloat($('#kurs_usd').val().replace(/\,/g,''));
        rpK					= parseFloat($('#kurs_rp').val().replace(/\,/g,''));
        cek					= $('#kurs_usd').val().replace(/\,/g,'');
        if(payterm=="dollar"){
                for (var i = 0; i < jum; i++) {
                    confirm 		= parseInt($('.qtyconfirm').eq(i).val().replace(/\,/g,''));
                    harga_beli 		= parseFloat($('.usd_rubah').eq(i).val().replace(/\,/g,''));
                    fiskal 			= parseFloat($('.fiskal').eq(i).val().replace(/\,/g,''));
                    noFiskal		= 100-fiskal;
                    usd_s			= confirm*harga_beli*rpK;
                    rupiah_s		= usd_s;
                     //console.log($('.usd').eq(i).val());
                    $('.usd').eq(i).val(harga_beli);
                    $('.rupiah').eq(i).val(rupiah_s);
                    $('.subtotal').eq(i).val(rupiah_s*fiskal/100);
                    $('.subtotal_no').eq(i).val(rupiah_s*noFiskal/100);
					
					$('.rupiah_rubah').eq(i).val(rupiah_s.format(2,3,','));
					$('.subtotal_rubah').eq(i).val((rupiah_s*fiskal/100).format(2,3,','));
                    $('.subtotal_no_rubah').eq(i).val((rupiah_s*noFiskal/100).format(2,3,','));
					
					
                    if(ppn_cek=="yes"){
                        fiskalSubtotal = parseFloat($('.subtotal').eq(i).val().replace(/\,/g,''));
						$('.subtotal_ppn').eq(i).val((fiskalSubtotal*10)/100);						
                        $('.subtotal_ppn_rubah').eq(i).val((fiskalSubtotal*0.1).format(2,3,','));				
                        
                    }
					
                   
                   
                    nomerzz					= parseFloat(i)+1;
					var nil_qty_conf		= parseInt($('.qtyconfirm').eq(i).val().replace(/\,/g,''));
					
					var nil_beli			= $('.harga_beli').eq(i).val().replace(/\,/g,'');
					var nil_usd_rubah		= $('#usd_rubah_'+nomerzz).val().replace(/\,/g,'');
					var nil_rupiah_rubah	= $('#rupiah_rubah_'+nomerzz).val().replace(/\,/g,'');
					var nil_subtotal_rubah	= $('#subtotal_rubah_'+nomerzz).val().replace(/\,/g,'');
					var nil_subno_rubah		= $('#subtotal_no_rubah_'+nomerzz).val().replace(/\,/g,'');
					
					if(nil_qty_conf=='' || nil_qty_conf==null){
						var nil_qty_conf			= 0;
					}
					if(nil_beli=='' || nil_beli==null){
						var nil_beli			= 0;
					}
					
					if(nil_usd_rubah=='' || nil_usd_rubah==null){
						var nil_usd_rubah		= 0;
					}
					if(nil_rupiah_rubah=='' || nil_rupiah_rubah==null){
						var nil_rupiah_rubah	= 0;
					}
					if(nil_subtotal_rubah=='' || nil_subtotal_rubah==null){
						var nil_subtotal_rubah	= 0;
					}
					if(nil_subno_rubah=='' || nil_subno_rubah==null){
						var nil_subno_rubah		= 0;
					}
					
					var total_qty		= parseInt(total_qty) + nil_qty_conf;
					subtot_dollar		+= (parseInt(nil_qty_conf) * parseFloat(nil_usd_rubah));
                    total_harga_beli	+= parseFloat(nil_beli);
                    total_dollar 		+= parseFloat(nil_usd_rubah);
                    total_rupiah 		+= parseFloat(nil_rupiah_rubah);
                    total_fiskal 		+= parseFloat(nil_subtotal_rubah);
                    total_nofiskal 		+= parseFloat(nil_subno_rubah);
					
                    if(ppn_cek=="yes"){
						var total_ppn	= parseFloat(total_ppn) + parseFloat($('.subtotal_ppn').eq(i).val().replace(/\,/g,''));
						$('#total_ppn').val(total_ppn);
                        $('#total_ppn_rubah').val(total_ppn.format(2,3,','));
						
                    }
					
					$('#total_qty').text(total_qty.format(0,3,','));
					$('#total_harga_beli').text(total_harga_beli.format(2,3,','));
					var text_dollar		= parseFloat(total_dollar.toFixed(2)).format(2,3,',')+"<br>"+"@Total $."+parseFloat(subtot_dollar.toFixed(2)).format(2,3,',');
					$('#total_dollar').html(text_dollar);
                    $('#total_rupiah').val(total_rupiah);
                    $('#total_fiskal').val(total_fiskal);
                    $('#total_nofiskal').val(total_nofiskal);					
					$('#total_rupiah_rubah').val(total_rupiah.format(2,3,','));
					$('#total_fiskal_rubah').val(total_fiskal.format(2,3,','));
					$('#total_nofiskal_rubah').val(total_nofiskal.format(2,3,','));
					
                }
            }else{
                for (var i = 0; i < jum; i++) {
                    confirm 		= parseInt($('.qtyconfirm').eq(i).val().replace(/\,/g,''));
                    harga_beli 		= parseFloat($('.harga_beli').eq(i).val().replace(/\,/g,''));
                    fiskal 			= parseFloat($('.fiskal').eq(i).val().replace(/\,/g,''));
                    noFiskal		= 100-fiskal;
                    usd_s			= (confirm*harga_beli)/usdK;
                    rupiah_s		= usd_s*rpK;
					var rupiah_des	= rupiah_s.toFixed(2);
                    $('.usd').eq(i).val(usd_s);
                    $('.rupiah').eq(i).val(rupiah_s);
                    $('.subtotal').eq(i).val(rupiah_s*fiskal/100);
                    $('.subtotal_no').eq(i).val(rupiah_s*noFiskal/100);

					$('.usd_rubah').eq(i).val(usd_s.format(2,3,','));
					$('.rupiah_rubah').eq(i).val(rupiah_s.format(2,3,','));
					$('.subtotal_rubah').eq(i).val((rupiah_des*fiskal/100).format(2,3,','));
                    $('.subtotal_no_rubah').eq(i).val((rupiah_des*noFiskal/100).format(2,3,','));
                   
                    if(ppn_cek=="yes"){
                        fiskalSubtotal = parseFloat($('.subtotal').eq(i).val().replace(/\,/g,''));
						
						$('.subtotal_ppn').eq(i).val((fiskalSubtotal.toFixed(2)*10)/100);						
                        $('.subtotal_ppn_rubah').eq(i).val((fiskalSubtotal.toFixed(2)*0.1).format(2,3,','));
						
                    }
                    nomerzz=parseFloat(i)+1;
					var nil_beli			= $('#harga_beli_'+nomerzz).val().replace(/\,/g,'');
					var nil_usd_rubah		= $('#usd_rubah_'+nomerzz).val().replace(/\,/g,'');
					var nil_rupiah_rubah	= $('#rupiah_rubah_'+nomerzz).val().replace(/\,/g,'');
					var nil_subtotal_rubah	= $('#subtotal_rubah_'+nomerzz).val().replace(/\,/g,'');
					var nil_subno_rubah		= $('#subtotal_no_rubah_'+nomerzz).val().replace(/\,/g,'');
					
					if(nil_beli=='' || nil_beli==null){
						var nil_beli			= 0;
					}
					if(nil_usd_rubah=='' || nil_usd_rubah==null){
						var nil_usd_rubah		= 0;
					}
					if(nil_rupiah_rubah=='' || nil_rupiah_rubah==null){
						var nil_rupiah_rubah	= 0;
					}
					if(nil_subtotal_rubah=='' || nil_subtotal_rubah==null){
						var nil_subtotal_rubah	= 0;
					}
					if(nil_subno_rubah=='' || nil_subno_rubah==null){
						var nil_subno_rubah		= 0;
					}
					
                    total_harga_beli	+= parseFloat(nil_beli);
                    total_dollar 		+= parseFloat(nil_usd_rubah);
                    total_rupiah 		+= parseFloat(nil_rupiah_rubah);
                    total_fiskal 		+= parseFloat(nil_subtotal_rubah);
                    total_nofiskal 		+= parseFloat(nil_subno_rubah);
					
					var total_qty	= parseInt(total_qty) +  parseInt($('.qtyconfirm').eq(i).val().replace(/\,/g,''));
					$('#total_qty').text(total_qty.format(0,3,','));
					
                    if(ppn_cek=="yes"){
						total_ppn	+= parseFloat($('.subtotal_ppn').eq(i).val().replace(/\,/g,''));
						$('#total_ppn').val(total_ppn);
                        $('#total_ppn_rubah').val(total_ppn.format(2,3,','));
						
                    }

                }

                $('#total_harga_beli').text(total_harga_beli.format(2,3,','));
                $('#total_dollar').text(total_dollar.format(2,3,','));
                $('#total_rupiah').val(total_rupiah);
                $('#total_fiskal').val(total_fiskal);
                $('#total_nofiskal').val(total_nofiskal);

				$('#total_rupiah_rubah').val(total_rupiah.format(2,3,','));
                $('#total_fiskal_rubah').val(total_fiskal.format(2,3,','));
                $('#total_nofiskal_rubah').val(total_nofiskal.format(2,3,','));
        }


    });

	/*
	## ORIGINAL ##
	$('.harga_beli,.qtyconfirm, #kurs_rp,#kurs_usd,.usd_rubah, .fiskal').on('keyup', function(){
        var total_qty = 0;
        var total_harga_beli = 0;
        var total_dollar = 0;
        var total_rupiah = 0;
        var subtot_dollar = 0;
        var total_fiskal = 0;
        var total_nofiskal = 0;
        var total_ppn = 0;
        var payterm          = $("input:radio.radio_paycon:checked").val();
        ppn_cek=document.getElementById('ppnD').value;
        usdK=parseFloat(document.getElementById('kurs_usd').value);
        rpK=parseFloat(document.getElementById('kurs_rp').value);
        cek=document.getElementById('kurs_usd').value;
        if(payterm=="dollar"){

                for (var i = 0; i < jum; i++) {
                    confirm = parseInt($('.qtyconfirm').eq(i).val());
                    harga_beli = parseFloat($('.usd_rubah').eq(i).val());
                    fiskal = parseFloat($('.fiskal').eq(i).val());
                    noFiskal=100-fiskal;
                    usd_s=confirm*harga_beli*rpK;
                    rupiah_s=usd_s;
                     //console.log($('.usd').eq(i).val());
                    $('.usd').eq(i).val(harga_beli);
                    $('.rupiah').eq(i).val(rupiah_s);
                    $('.subtotal').eq(i).val(rupiah_s*fiskal/100);
                    $('.subtotal_no').eq(i).val(rupiah_s*noFiskal/100);

                    $('.rupiah_rubah').eq(i).val(rubah(rupiah_s));
                    $('.subtotal_rubah').eq(i).val(rubah(rupiah_s*fiskal/100));
                    $('.subtotal_no_rubah').eq(i).val(rubah(rupiah_s*noFiskal/100));

                    if(ppn_cek=="yes"){
                        fiskalSubtotal = parseFloat($('.subtotal').eq(i).val());
                        $('.subtotal_ppn').eq(i).val((fiskalSubtotal*10)/100);
                        $('.subtotal_ppn_rubah').eq(i).val(rubah((fiskalSubtotal*10)/100));
                    }

                    $('#total_qty').text(rubah(total_qty += parseInt($('.qtyconfirm').eq(i).val())));
                    $('#total_harga_beli').text(rubah(total_harga_beli += parseFloat($('.harga_beli').eq(i).val())));
                    nomerzz=parseFloat(i)+1;
                    subtot_dollar += parseInt($('.qtyconfirm').eq(i).val()) * parseFloat(document.getElementById("usd_rubah_"+nomerzz).value);
                    total_dollar += parseFloat(document.getElementById("usd_rubah_"+nomerzz).value);
                    total_rupiah += parseFloat(document.getElementById("rupiah_rubah_"+nomerzz).value);
                    total_fiskal += parseFloat(document.getElementById("subtotal_rubah_"+nomerzz).value);
                    total_nofiskal += parseFloat(document.getElementById("subtotal_no_rubah_"+nomerzz).value);
                    //$('#total_dollar').text(rubah(total_dollar += parseFloat($('.usd').eq(i).val())));
                    //$('#total_rupiah').val(total_rupiah += parseFloat($('.rupiah').eq(i).val()));
                    //$('#total_fiskal').val(total_fiskal += parseFloat($('.subtotal').eq(i).val()));
                    //$('#total_nofiskal').val(total_nofiskal += parseFloat($('.subtotal_no').eq(i).val()));

                    //$('#total_rupiah_rubah').val(rubah(total_rupiah += parseFloat($('.rupiah').eq(i).val())));
                    //$('#total_fiskal_rubah').val(rubah(total_fiskal += parseFloat($('.subtotal').eq(i).val())));
                    //$('#total_nofiskal_rubah').val(rubah(total_nofiskal += parseFloat($('.subtotal_no').eq(i).val())));
                    if(ppn_cek=="yes"){
                        $('#total_ppn').val(total_ppn += parseFloat($('.subtotal_ppn').eq(i).val()));
                        $('#total_ppn_rubah').val(rubah(total_ppn += parseFloat($('.subtotal_ppn').eq(i).val())));
                    }

                    $('#total_dollar').html(rubah(total_dollar.toFixed(2))+"<br>"+"@Total $."+rubah(subtot_dollar.toFixed(2)));
                    $('#total_rupiah').val(total_rupiah);
                    $('#total_fiskal').val(total_fiskal);
                    $('#total_nofiskal').val(total_nofiskal);

                    $('#total_rupiah_rubah').val(rubah(total_rupiah.toFixed(2)));
                    $('#total_fiskal_rubah').val(rubah(total_fiskal.toFixed(2)));
                    $('#total_nofiskal_rubah').val(rubah(total_nofiskal.toFixed(2)));

                }
            }else{
                for (var i = 0; i < jum; i++) {
                    confirm = parseInt($('.qtyconfirm').eq(i).val());
                    harga_beli = parseFloat($('.harga_beli').eq(i).val());
                    fiskal = parseFloat($('.fiskal').eq(i).val());
                    noFiskal=100-fiskal;
                    usd_s=(confirm*harga_beli)/usdK;
                    rupiah_s=usd_s*rpK;
                    $('.usd').eq(i).val(usd_s);
                    $('.rupiah').eq(i).val(rupiah_s);
                    $('.subtotal').eq(i).val(rupiah_s*fiskal/100);
                    $('.subtotal_no').eq(i).val(rupiah_s*noFiskal/100);


                    $('.usd_rubah').eq(i).val(rubah(usd_s.toFixed(2)));
                    $('.rupiah_rubah').eq(i).val(rubah(rupiah_s.toFixed(2)));
                    $('.subtotal_rubah').eq(i).val(rubah(rupiah_s.toFixed(2)*fiskal/100));
                    $('.subtotal_no_rubah').eq(i).val(rubah(rupiah_s.toFixed(2)*noFiskal/100));
                    if(ppn_cek=="yes"){
                        fiskalSubtotal = parseFloat($('.subtotal').eq(i).val());
                        $('.subtotal_ppn').eq(i).val((fiskalSubtotal.toFixed(2)*10)/100);
                        $('.subtotal_ppn_rubah').eq(i).val(rubah((fiskalSubtotal.toFixed(2)*10)/100));
                    }
                    nomerzz=parseFloat(i)+1;
                    total_harga_beli += parseFloat(document.getElementById("harga_beli_"+nomerzz).value);
                    total_dollar += parseFloat(document.getElementById("usd_rubah_"+nomerzz).value);
                    total_rupiah += parseFloat(document.getElementById("rupiah_rubah_"+nomerzz).value);
                    total_fiskal += parseFloat(document.getElementById("subtotal_rubah_"+nomerzz).value);
                    total_nofiskal += parseFloat(document.getElementById("subtotal_no_rubah_"+nomerzz).value);
                    //total_harga_beli += parseFloat($('.harga_beli').eq(i).val());
                    //$('#total_fiskal').val(formatCurrency(total_fiskal += parseInt($('.subtotal').eq(i).val())));
                    $('#total_qty').text(rubah(total_qty += parseInt($('.qtyconfirm').eq(i).val())));
                    //$('#total_dollar').text(rubah(total_dollar += parseFloat($('.usd').eq(i).val())));
                    //$('#total_rupiah').val(total_rupiah += parseFloat($('.rupiah').eq(i).val()));
                    //$('#total_fiskal').val(total_fiskal += parseFloat($('.subtotal').eq(i).val()));
                    //$('#total_nofiskal').val(total_nofiskal += parseFloat($('.subtotal_no').eq(i).val()));

                    //$('#total_rupiah_rubah').val(rubah(total_rupiah += parseFloat($('.rupiah').eq(i).val())));
                    //$('#total_fiskal_rubah').val(rubah(total_fiskal += parseFloat($('.subtotal').eq(i).val())));
                    //$('#total_nofiskal_rubah').val(rubah(total_nofiskal += parseFloat($('.subtotal_no').eq(i).val())));
                    if(ppn_cek=="yes"){
                        $('#total_ppn').val(total_ppn += parseFloat($('.subtotal_ppn').eq(i).val()));
                        $('#total_ppn_rubah').val(rubah(total_ppn += parseFloat($('.subtotal_ppn').eq(i).val())));
                    }

                }

                $('#total_harga_beli').text(rubah(total_harga_beli.toFixed(2)));
                $('#total_dollar').text(rubah(total_dollar.toFixed(2)));
                $('#total_rupiah').val(total_rupiah);
                $('#total_fiskal').val(total_fiskal);
                $('#total_nofiskal').val(total_nofiskal);

                $('#total_rupiah_rubah').val(rubah(total_rupiah.toFixed(2)));
                $('#total_fiskal_rubah').val(rubah(total_fiskal.toFixed(2)));
                $('#total_nofiskal_rubah').val(rubah(total_nofiskal.toFixed(2)));
        }


    });	
	*/
    function formatCurrency(c){
      var   number_string = c.toString(),
        sisa    = number_string.length % 3,
        rupiah  = number_string.substr(0, sisa),
        ribuan  = number_string.substr(sisa).match(/\d{3}/g);

      if (ribuan) {
        separator = sisa ? ',' : '';
        return rupiah += separator + ribuan.join(',');
      }
    }

    function rubah(angka){
       var reverse = angka.toString().split('').reverse().join(''),
       ribuan = reverse.match(/\d{1,3}/g);
       ribuan = ribuan.join('.').split('').reverse().join('');
       return ribuan;
     }

    function bersihPemisah(ini){
        a=ini.toString().replace(".","");
        return a;
    }
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
			num = this.toFixed(Math.max(0, ~~n));

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
</script>
