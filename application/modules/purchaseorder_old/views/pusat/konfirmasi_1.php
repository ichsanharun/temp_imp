<link rel="stylesheet" href="<?php echo base_url('assets/css/radiobutton.css'); ?>">
<link href="<?= base_url(); ?>assets/css/switch.css" rel="stylesheet" />
<div class="nav-tabs-pr">
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
                            <label class="col-sm-4 control-label">NO. PI</label>
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
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">NO. PI</label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <input type="text" name="no_pi"  class="form-control input-sm" value="" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                KURS
                            </label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            RMB
                                        </td>
                                        <td>
                                            to USD
                                        </td>
                                        <td>
                                            <input id="kurs_usd" name="kurs_usd" value="0" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            USD
                                        </td>
                                        <td>
                                            to Rupiah
                                        </td>
                                        <td>
                                            <input id="kurs_rp" name="kurs_rp" value="0" />
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
                    <th>Harga Beli</th>
                    <th>HB (Dollar)</th>
                    <th>HB (Rupiah)</th>
                    <th>% Fiskal</th>
                    <th>Fiskal</th>
                    <th>No Fiskal</th>
                    <th>Ppn</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;

                foreach (@$itembarang as $data => $datas) {
                    $no = $no++; ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $datas->nm_barang; ?></td>
                        <td><?= $datas->qty_po; ?></td>
                        <td>
                            <input type="hidden" name="idet[]" value="<?= $datas->id_detail_po; ?>" />
                            <input id="qtyconfirm_<?php echo $no; ?>" class="form-control input-sm qtyconfirm" name="qty_acc[]" value="<?= $datas->qty_acc; ?>" />
                        </td>
                        <td>
                            <input id="harga_beli_<?php echo $no; ?>" class="form-control input-sm harga_beli" name="harga_satuan[]"  value="0" />
                        </td>
                        <td>
                            <input type="text" name="usd[]" class="form-control input-sm usd" value="0" readonly="">
                        </td>
                        <td>
                            <input type="text" name="rupiah[]" class="form-control input-sm rupiah" value="0" readonly="">
                        </td>
                        <td style="width: 50px">
                            <input style="width: 40px" class="form-control input-sm fiskal" name="fiskal[]" value="0" />
                        </td>
                        <th>
                            <input class="form-control input-sm subtotal" name="subtotal[]"  readonly="" value="0"/>
                        </th>
                        <th>
                            <input class="form-control input-sm subtotal_no" name="subtotal_no[]"  readonly="" value="0"/>
                        </th>
                        <th>
                            <input class="form-control input-sm subtotal_ppn" name="subtotal_ppn[]"  readonly="" value="0"/>
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
                        <input id="total_rupiah" class="form-control input-sm" name="total_rupiah"  readonly="" value="0"/>
                    </td>
                    <td></td>
                    <td>
                        <input id="total_fiskal" class="form-control input-sm" name="total_fiskal"  readonly="" value="0"/>
                    </td>
                    <td>
                        <input id="total_nofiskal" class="form-control input-sm" name="total_nofiskal"  readonly="" value="0"/>
                    </td>
                    <td>
                        <input id="total_ppn" class="form-control input-sm" name="total_ppn"  readonly="" value="0"/>
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
                        <div class="col-xs-3">
                          Persen
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
                          <input type="text" name="pembayaran[]" class="form-control" value="100" >
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

<script>
    

    function saveheaderpr(){
            
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
</script>
<script>
    $(document).ready(function() {
        
        
        
                
        var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $(".input_fields_wrap"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID
        
        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                
                
                
                $(wrapper).append('<div class="row">'+
                                        '<div class="col-xs-1">'+x+'</div>'+
                                        '<div class="col-xs-3">'+
                                          '<input type="text" name="pembayaran[]" class="form-control" value="0" >'+
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
</script>

<script>
    $('.datepickerxx').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    })
    var jum = <?php echo count($itembarang); ?>;
    $('.harga_beli,.qtyconfirm, #kurs_rp,#kurs_usd, .fiskal').on('keyup', function(){
        var total_qty = 0;
        var total_harga_beli = 0;
        var total_dollar = 0;
        var total_rupiah = 0;
        var total_fiskal = 0;
        var total_nofiskal = 0;
        var total_ppn = 0;
        ppn_cek=document.getElementById('ppnD').value;
        usdK=parseFloat(document.getElementById('kurs_usd').value);
        rpK=parseFloat(document.getElementById('kurs_rp').value);
       
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
            
            if(ppn_cek=="yes"){
                fiskalSubtotal = parseFloat($('.subtotal').eq(i).val());
                $('.subtotal_ppn').eq(i).val((fiskalSubtotal*10)/100);
            }
            
            
            //$('#total_fiskal').val(formatCurrency(total_fiskal += parseInt($('.subtotal').eq(i).val())));
            $('#total_qty').text(total_qty += parseInt($('.qtyconfirm').eq(i).val()));
            $('#total_harga_beli').text(total_harga_beli += parseFloat($('.harga_beli').eq(i).val()));
            $('#total_dollar').text(total_dollar += parseFloat($('.usd').eq(i).val()));
            $('#total_rupiah').val(total_rupiah += parseFloat($('.rupiah').eq(i).val()));
            $('#total_fiskal').val(total_fiskal += parseFloat($('.subtotal').eq(i).val()));
            $('#total_nofiskal').val(total_nofiskal += parseFloat($('.subtotal_no').eq(i).val()));
            if(ppn_cek=="yes"){
                $('#total_ppn').val(total_ppn += parseFloat($('.subtotal_ppn').eq(i).val()));
            }
            
        }
    });
    
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
</script>