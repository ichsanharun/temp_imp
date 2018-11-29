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
    <div class="box-body">
        <form id="form-detail-po" method="post">
        <table id="purchaseorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                  <th width="2%">#</th>
                  <th>ITEM NO.</th>
                  <th>Item Barang</th>
                  <th>Satuan</th>
                  <th>Qty Confirm</th>
                  <th width="15%">Qty Supply</th>
                  <!--th width="2%">#</th>
                  <th>No. PR</th>
                  <th>Item Barang</th>
                  <th>Satuan</th>
                  <th>Qty Confirm</th>
                  <th width="15%">Qty Supply</th-->
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
                        <?php echo $vdo->qty_pr?>
                        <input type="hidden" id="qtyconfirm_<?php echo $no?>" value="<?php echo $vdo->qty_pr?>" name="qty_pr[]">
                    </td>
                    <td>
                        <center><input onkeyup="cekqtysupply('<?php echo $no?>')" type="text" name="qty_acc[]" id="qty_supply_<?php echo $no?>" class="form-control input-sm"></center>
                    </td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
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
    });
    function saveheaderpo(){
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
