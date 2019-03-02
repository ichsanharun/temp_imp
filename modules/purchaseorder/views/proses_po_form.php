<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-purchaseorder">
    <div class="tab-content">
        <div class="tab-pane active" id="purchaseorder">
            <div class="box box-primary">
                <form id="form-header-po" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="kdcab" class="col-sm-4 control-label">Nama Cabang </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerpr[0]->namacabang?>
                                <input type="hidden" name="status_po" value="PO">
                                <input type="hidden" name="kdcab_po" value="<?php echo $headerpr[0]->kdcab?>">
                                <input type="hidden" name="nmcabang_po" value="<?php echo $headerpr[0]->namacabang?>">
                                <input type="hidden" name="total" value="<?php echo $headerpr[0]->total_pr?>">
                                <input id="supply_total" type="hidden" name="supply_total" style="width:100%;" class="form-control input-sm">
                                <input id="harga_beli_total" type="hidden" name="harga_beli_total" style="width:100%;" class="form-control input-sm">
                                <input id="fiskal_total" type="hidden" name="fiskal_total" style="width:100%;" class="form-control input-sm">
                                <input id="non_fiskal_total" type="hidden" name="non_fiskal_total" style="width:100%;" class="form-control input-sm">
                                <input type="hidden" name="harga_total" id="harga_total" style="width:100%;" class="form-control input-sm">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">Nama Supplier </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerpr[0]->nm_supplier?>
                                <input type="hidden" name="id_supplier" value="<?php echo $headerpr[0]->id_supplier?>">
                                <input type="hidden" name="nm_supplier" value="<?php echo $headerpr[0]->nm_supplier?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tglpr=date('Y-m-d')?>
                            <label for="tglpo" class="col-sm-4 control-label">Tanggal PR </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".date('d M Y',strtotime($headerpr[0]->tgl_pr))?>
                                <input type="hidden" name="tglpr" value="<?php echo $headerpr[0]->tgl_pr?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">

                        <div class="form-group ">
                            <?php $tglpr=date('Y-m-d')?>
                            <label for="tglpr" class="col-sm-4 control-label">Tanggal PO<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglpo" id="tglpo" class="form-control input-sm datepicker" value="<?php echo $tglpr?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tglpr=date('Y-m-d')?>
                            <label for="tglpr" class="col-sm-4 control-label">Real Delivery PO<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglpo_real" id="tglpo" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d', strtotime('+6 days', strtotime( $tglpr )))?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="box box-default ">
  <div class="box-header pull-right">
    <div class="form-group row">
        <label for="persen_fiskal" class="col-sm-6 control-label text-right">Besaran Fiskal<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-5 pull-right">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                <input type="text" name="persen_fiskal" id="persen_fiskal" class="form-control input-sm" value="" onchange="getpersen(fiskal)" onkeyup="filterAngka1(this.id)" maxlength="3">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="persen_ppn" class="col-sm-6 control-label text-right">Besaran PPN / PPH<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-6">
            <?php
              if (@$pajak) {
                foreach (@$pajak as $kp => $vp) {

                  ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        <input type="text" name="persen_ppn" id="persen_ppn" class="form-control input-sm" value="<?php echo $vp->ppn?>" readonly>
                        <span class="input-group-addon">/</span>

                        <input type="text" name="persen_pph" id="persen_pph" class="form-control input-sm" value="<?php echo $vp->pph?>" readonly>
                    </div>
                  <?php
                }
              }
             ?>
        </div>
    </div>
  </div>
    <div class="box-body">
        <form id="form-detail-po" method="post">
        <table id="purchaseorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                  <th rowspan="2">#</th>
                  <th rowspan="2">ITEM NO.</th>
                  <th rowspan="2">Item Barang</th>
                  <th rowspan="2">Satuan</th>
                  <th rowspan="2">Qty Order PR</th>
                  <th rowspan="2">Qty Confirm</th>
                  <th colspan="4"><center>Pembayaran </center></th>
                  <!--th width="2%">#</th>
                  <th>Item Barang</th>
                  <th>Satuan</th>
                  <th>Qty Confirm</th>
                  <th width="15%">Qty Supply</th-->
                </tr>
                <tr>
                  <th><center>Harga Beli </center></th>
                  <th><center>Fiskal </center></th>
                  <th><center>Non Fiskal </center></th>
                  <th><center>Subtotal </center></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(@$getitempr){
                $n=1;
                foreach(@$getitempr as $kdo => $vdo){
                    $no=$n++;
                ?>
                <tr>
                    <td><center><?php echo $no?></center></td>
                    <td>
                        <center>
                        <?php echo $vdo->no_pr?>
                        <input type="hidden" name="nopr_topo[]" value="<?php echo $vdo->no_pr?>">
                        </center>
                    </td>
                    <td>
                        <?php echo $vdo->id_barang.' / '.$vdo->nm_barang?>
                        <input type="hidden" name="id_barang[]" value="<?php echo $vdo->id_barang?>">
                        <input type="hidden" name="harga_satuan[]" value="<?php echo $vdo->harga_satuan?>">
                        <input type="hidden" name="sub_total_pr[]" value="<?php echo $vdo->sub_total_pr?>">
                    </td>
                    <td><?php echo $vdo->satuan?></td>
                    <td>
                        <span id="qty_pr_<?php echo $no?>"><?php echo $vdo->qty_pr-$vdo->qty_po?></span>
                        <input type="hidden" id="qtyconfirm_<?php echo $no?>" value="<?php echo $vdo->qty_pr?>" name="qty_pr[]" class="qtyconfirm">
                        <input type="hidden" value="<?php echo $vdo->qty_po?>" name="qty_po[]">
                    </td>
                    <td>
                        <center>
                          <div class="input-group">
                            <input onkeyup="cekqtysupply('<?php echo $no?>');perhitungan(<?php echo $no?>)" type="text" name="qty_supply[]" id="qty_supply_<?php echo $no?>" style="width:100%;" class="form-control input-sm supply">
                            <div class="input-group-btn">
                              <a class="btn btn-default btn-sm" onclick="getqty()">
                                <i class="fa fa-check"></i>
                              </a>
                            </div>
                          </div>
                        </center>
                    </td>

                    <td>
                        <center><input onkeyup="filterAngka1(this.id);perhitungan(<?php echo $no?>)" onkeyup="document.getElementById(this.id).value = formatCurrency(this.value);" type="text" name="harga_beli[]" id="harga_beli_<?php echo $no?>" style="width:100%;" class="form-control input-sm harga_beli" min="3" ></center>
                    </td>
                    <td>
                        <center><input type="text" name="fiskal[]" id="fiskal_<?php echo $no?>" style="width:100%;" class="form-control input-sm fiskal" readonly></center>
                    </td>
                    <td>
                        <center><input type="text" name="non_fiskal[]" id="non_fiskal_<?php echo $no?>" style="width:100%;" class="form-control input-sm non_fiskal" readonly></center>
                    </td>
                    <td>
                        <center><input type="text" name="subtotal[]" id="subtotal_<?php echo $no?>" style="width:100%;" class="form-control input-sm subtotal" readonly></center>
                    </td>
                </tr>
                <?php } ?>
                <span id="jumlahdata" hidden><?php echo $no?></span>
                <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5" bgcolor="#E0D6FD"><center>Grand Total</center></th>
                <th bgcolor="#F6F2FF"><center><span id="in_supply_total"></span></center></th>
                <th bgcolor="#F6F2FF"><center><span id="in_harga_beli_total"></span></center></th>
                <th bgcolor="#F6F2FF"><center><span id="in_fiskal_total"></span></center></th>
                <th bgcolor="#F6F2FF"><center><span id="in_non_fiskal_total"></span></center></th>
                <th bgcolor="#F6F2FF"><center><span id="in_harga_total"></span></center></th>
              </tr>
            </tfoot>
        </table>
        </form>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_po()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="saveheaderpo()">
            <i class="fa fa-save"></i><b> Simpan Data PO</b>
        </button>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-item-po" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data PR untuk Purchase Order (PO)</h4>
      </div>
      <div class="modal-body" id="MyModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>
<!-- Modal -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="http://peterolson.github.com/BigInteger.js/BigInteger.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });

        var dataTableItem = $('#purchaseorderitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
        var jum = <?php echo $no; ?>;
        for (var i = 1; i <= jum; i++) {
          $('#qty_supply_'+i).val($('#qty_pr_'+i).text());
        }
        var jum = <?php echo $no; ?>;
        $('.supply,.harga_beli,#persen_fiskal').on('keyup', function(){
          var fiskal_total = 0;
          var non_fiskal_total = 0;
          var harga_total = 0;
          var supply_total = 0;
          var harga_beli_total = 0;

          for (var i = 0; i < jum; i++) {
            var confirm = parseInt($('.qtyconfirm').eq(i).val());
            var supply = parseInt($('.supply').eq(i).val());
            var harga = $('.harga_beli').eq(i).val().replace(/[^\d]/g,"");
            var persen_fiskal = parseInt($('#persen_fiskal').val());
            var persen_pph = parseInt($('#persen_pph').val());
            var persen_ppn = parseInt($('#persen_ppn').val());
            var h = harga * supply + (persen_pph/100*harga*supply) + (persen_ppn/100*harga*supply);
            var fiskal = h*persen_fiskal/100;
            var non_fiskal = h*(100-persen_fiskal)/100;


            $('.subtotal').eq(i).val(formatCurrency(parseInt(h)));
            $('.fiskal').eq(i).val(fiskal);
            $('.non_fiskal').eq(i).val(non_fiskal);
            $('#fiskal_total').val(formatCurrency(fiskal_total += parseInt($('.fiskal').eq(i).val())));
            $('#non_fiskal_total').val(formatCurrency(non_fiskal_total += parseInt($('.non_fiskal').eq(i).val())));
            $('#harga_total').val(formatCurrency(harga_total += parseFloat($('.subtotal').eq(i).val().replace(/[^\d]/g,""))));
            $('#supply_total').val(supply_total += parseInt($('.supply').eq(i).val()));
            $('#harga_beli_total').val(formatCurrency(harga_beli_total += parseInt($('.harga_beli').eq(i).val())));

            $('#in_fiskal_total').text(formatCurrency(fiskal_total += parseInt($('.fiskal').eq(i).val())));
            $('#in_non_fiskal_total').text(formatCurrency(non_fiskal_total += parseInt($('.non_fiskal').eq(i).val())));
            $('#in_harga_total').text(formatCurrency(harga_total += parseFloat($('.subtotal').eq(i).val().replace(/[^\d]/g,""))));
            $('#in_supply_total').text(supply_total += parseInt($('.supply').eq(i).val()));
            $('#in_harga_beli_total').text(formatCurrency(harga_beli_total += parseInt($('.harga_beli').eq(i).val())));

          }
          for (var i = 0; i < jum; i++) {

          }
          //alert(supply);
          if(isNaN(fiskal))
          fiskal = "0";
          if(isNaN(non_fiskal))
          non_fiskal = "0";
        });
    });
    function cekqty(){
      var qty_confirm = $('#qty_confirm').InnerHTML();
      alert(qty_confirm);
    }
    function saveheaderpo(){
      var jum = <?php echo $no; ?>;
      for (var i = 0; i < jum; i++) {
        $('.subtotal').eq(i).val($('.subtotal').eq(i).val().replace(/[^\d]/g,""));
        $('.fiskal').eq(i).val($('.fiskal').eq(i).val().replace(/[^\d]/g,""));
        $('.non_fiskal').eq(i).val($('.non_fiskal').eq(i).val().replace(/[^\d]/g,""));
        $('.harga_beli').eq(i).val($('.harga_beli').eq(i).val().replace(/[^\d]/g,""));
      }
      $('#harga_total').val($('#harga_total').val().replace(/[^\d]/g,""));
        var tglpr = $('#tglpr').val();
        var tglpo = $('#tglpo').val();
        var tglpo_real = $('#tglpo_real').val();
        if(tglpr == "" || tglpo == "" || tglpo_real == ""){
            swal({
                title: "Peringatan!",
                text: "Tanggal tidak boleh kosong",
                type: "warning",
                timer: 1500,
                showConfirmButton: false
            });
        }else{
        swal({
          title: "Peringatan !",
          text: "Pastikan data sudah lengkap dan benar",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, simpan!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            var formdata = $("#form-header-po,#form-detail-po").serialize();
            $.ajax({
                url: siteurl+"purchaseorder/saveheaderpo",
                dataType : "json",
                type: 'POST',
                data: formdata,
                success: function(result){
                    if(result.save=='1'){
                        swal({
                            title: "Sukses!",
                            text: JSON.stringify(result['msg']),
                            type: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                            window.location.href=siteurl+'purchaseorder';
                        },1600);
                    } else {
                        swal({
                            title: "Gagal!",
                            text: "Data Gagal Di Simpan",
                            type: "error",
                            timer: 1500,
                            showConfirmButton: false
                        });
                    };
                },
                error: function(){
                  //console.log(result.type);
                    swal({
                        title: "Gagal!",
                        text: "Ajax Data Gagal Di Proses",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
        });
    }
    }
    function kembali_po(){
        window.location.href = siteurl+"purchaseorder";
    }
    function filterAngka1(a){
        document.getElementById(a).value = document.getElementById(a).value.match(/^[0-9]+$/);
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function formatCurrency(c){
      var	number_string = c.toString(),
        sisa 	= number_string.length % 3,
        rupiah 	= number_string.substr(0, sisa),
        ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

      if (ribuan) {
        separator = sisa ? ',' : '';
        return rupiah += separator + ribuan.join(',');
      }
    }
    /*
    function perhitunganq(no){
      var confirm = parseInt($('#qtyconfirm_'+no).val());
      var supply = parseInt($('#qty_supply_'+no).val());
      var harga = $('#harga_beli_'+no).val().replace(/[^\d]/g,"");
      var persen_fiskal = parseInt($('#persen_fiskal').val());
      var persen_pph = parseInt($('#persen_pph').val());
      var persen_ppn = parseInt($('#persen_ppn').val());
      var h = harga * supply + (persen_pph/100*harga*supply) + (persen_ppn/100*harga*supply);
      var fiskal = h*persen_fiskal/100;
      var non_fiskal = h*(100-persen_fiskal)/100;
      if(isNaN(fiskal))
      fiskal = "0";
      if(isNaN(non_fiskal))
      non_fiskal = "0";


      var	number_string = h.toString(),
        sisa 	= number_string.length % 3,
        rupiah 	= number_string.substr(0, sisa),
        ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

      if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
      }
      $('#subtotal_'+no).val(h);
      $('#fiskal_'+no).val(fiskal);
      $('#non_fiskal_'+no).val(non_fiskal);
    }

    function cekqtysupply(no){
        var confirm = parseInt($('#qtyconfirm_'+no).val());
        var supply = parseInt($('#qty_supply_'+no).val());
        if(filterAngka($('#qty_supply_'+no).val()) == 1){
            if(supply > confirm){
                swal({
                    title: "Peringatan!",
                    text: "Qty Supply tidak boleh melebihi Qty Confirm",
                    type: "warning",
                    timer: 1500,
                    showConfirmButton: false
                });
                $('#qty_supply_'+no).val(0);
            }
            else {
              //var harga_beli = $('#harga_beli_'+no).val();
              var harga = $('#harga_beli_'+no).val().replace(/[^\d]/g,"");
              var persen_fiskal = parseInt($('#persen_fiskal').val());
              var persen_pph = parseInt($('#persen_pph').val());
              var persen_ppn = parseInt($('#persen_ppn').val());
              var h = harga * supply + (persen_pph/100*harga*supply) + (persen_ppn/100*harga*supply);
              //$('#subtotal_'+no).val();
              //alert(h);

              var	number_string = h.toString(),
              	sisa 	= number_string.length % 3,
              	rupiah 	= number_string.substr(0, sisa),
              	ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

              if (ribuan) {
              	separator = sisa ? '.' : '';
              	rupiah += separator + ribuan.join('.');
              }
              $('#subtotal_'+no).val(rupiah);

            }
        }else{
            var ang = $('#qty_supply_'+no).val();
            $('#qty_supply_'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }*/
</script>
