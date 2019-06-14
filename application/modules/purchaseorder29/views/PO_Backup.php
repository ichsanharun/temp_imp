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
                                <input type="hidden" name="namacabang_po" value="<?php echo $headerpr[0]->namacabang?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsupplier" class="col-sm-4 control-label">Nama Supplier </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerpr[0]->nm_supplier?>
                                <input type="hidden" name="id_supplier" value="<?php echo $headerpr[0]->id_supplier?>">
                                <input type="hidden" name="nm_supplier" value="<?php echo $headerpr[0]->nm_supplier?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tglso=date('Y-m-d')?>
                            <label for="tglpo" class="col-sm-4 control-label">Tanggal PR </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".date('d M Y',strtotime($headerpr[0]->tgl_pr))?>
                                <input type="hidden" name="tglpo" value="<?php echo $headerpr[0]->tgl_pr?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group ">
                          <?php $tglso=date('Y-m-d')?>
                          <label for="tglpo" class="col-sm-4 control-label">Plan Delivery PO </label>
                          <div class="col-sm-8" style="padding-top: 8px;">
                              <?php echo ": ".date('d M Y',strtotime($headerpr[0]->plan_delivery_date))?>
                              <input type="hidden" name="tglpo_plan" value="<?php echo $headerpr[0]->plan_delivery_date?>">
                          </div>
                      </div>
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
    <div class="box-body">
        <form id="form-detail-po" method="post">
        <table id="purchaseorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>ITEM NO.</th>
                    <th>Item Barang</th>
                    <th>CBM Each</th>
                    <th>CBM Total</th>
                    <th>Qty Confirm</th>
                    <th width="15%">Qty Supply</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(@$getitempr){
                  $total=0;
                  $cbm_sub=0;
                  $cbm_tot=0;
                $n=1;
                foreach(@$getitempr as $kpo => $vpo){
                    $no=$n++;
                    //$total += $vpo->sub_total_pr;
                    $cbm_sub = $vpo->cbm_each*$vpo->qty_pr;
                    $cbm_tot += $cbm_sub;
                ?>
                <tr>
                    <td><center><?php echo $no?></center></td>
                    <td>
                        <center>
                        <?php echo $vpo->no_pr?>
                        <input type="hidden" name="nopr_topo[]" value="<?php echo $vpo->no_pr?>">
                        </center>
                    </td>
                    <td>
                        <?php echo $vpo->id_barang.' / '.$vpo->nm_barang?>
                        <input type="hidden" name="id_barang[]" value="<?php echo $vpo->id_barang?>">
                    </td>
                    <td><?php echo $vpo->cbm_each?></td>
                    <td><?php echo $vpo->cbm_each*$vpo->qty_pr?></td>
                    <td>
                        <?php echo $vpo->qty_pr?>
                        <input type="hidden" id="qtyconfirm_<?php echo $no?>" value="<?php echo $vpo->qty_pr?>" name="qty_confirm[]">
                    </td>
                    <td>
                        <center><input onkeyup="cekqtysupply('<?php echo $no?>')" type="text" name="qty_supply[]" id="qty_supply_<?php echo $no?>" class="form-control input-sm"></center>
                    </td>
                </tr>

                <?php } ?>
            </tbody>
          <?php } ?>
          <tfoot>
              <tr>
                  <th class="text-right" colspan="4">Total CBM Container</th>
                  <th class="text-right"><div id="cbm_tot_tmp"><?php echo $cbm_tot;?></div></th>
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

<!-- Modal -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
        /*$("#supir_do,#kendaraan_do").select2({
            placeholder: "Pilih",
            allowClear: true
        });*/
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
    });
    function saveheaderpo(){
        var kirim = $('#tglpo').val();
        if(kirim == ""){
            swal({
                title: "Peringatan!",
                text: "Tanggal PO tidak boleh kosong",
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
            var formdata = $("#form-header-po").serialize();
            $.ajax({
                url: siteurl+"/purchaseorder/saveheader_po",
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
                error: function(response){
                  var r = jQuery.parseJSON(response.responseText);
    alert("Message: " + r.Message);
    alert("StackTrace: " + r.StackTrace);
    alert("ExceptionType: " + r.ExceptionType);
                }
            });
        }
        });
    }
    }
    function kembali_po(){
        window.location.href = siteurl+"purchaseorder";
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
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
        }else{
            var ang = $('#qty_supply_'+no).val();
            $('#qty_supply_'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }
</script>
