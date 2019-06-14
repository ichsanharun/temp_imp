<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-do" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idcustomer" class="col-sm-4 control-label">Nama Customer </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerso[0]->nm_customer?>
                                <input type="hidden" name="idcustomer_do" value="<?php echo $headerso[0]->id_customer?>">
                                <input type="hidden" name="nmcustomer_do" value="<?php echo $headerso[0]->nm_customer?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">Nama Salesman </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerso[0]->nm_salesman?>
                                <input type="hidden" name="id_salesman" value="<?php echo $headerso[0]->id_salesman?>">
                                <input type="hidden" name="nm_salesman" value="<?php echo $headerso[0]->nm_salesman?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tglso=date('Y-m-d')?>
                            <label for="tgldo" class="col-sm-4 control-label">Tanggal SO </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".date('d M Y',strtotime($headerso[0]->tanggal))?>
                                <input type="hidden" name="tgldo" value="<?php echo $headerso[0]->tanggal?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                         <div class="form-group ">
                            <label for="tipekirim" class="col-sm-4 control-label">Tipe Pengiriman <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <select name="tipekirim" id="tipekirim" class="form-control input-sm">
                                        <option value="">Pilih</option>
                                        <option value="EKSPEDISI">EKSPEDISI</option>
                                        <option value="SELF">SELF</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="supir_do" class="col-sm-4 control-label">Nama Supir <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <select class="form-control input-sm select2" name="supir_do" id="supir_do">
                                        <option value="">Pilih</option>
                                        <?php
                                        foreach(@$driver as $kd=>$vd){ 
                                        ?>
                                        <option value="<?php echo $vd->id_karyawan?>"><?php echo $vd->nama_karyawan?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="kendaraan_do" class="col-sm-4 control-label">Kendaraan <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <select class="form-control input-sm select2" name="kendaraan_do" id="kendaraan_do">
                                        <option value="">Pilih</option>
                                        <?php
                                        foreach(@$kendaraan as $kk=>$vk){ 
                                        ?>
                                        <option value="<?php echo $vk->id_kendaraan?>"><?php echo $vk->nm_kendaraan?></option>
                                        <?php } ?>
                                    </select>
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
        <form id="form-detail-do" method="post">
        <table id="deliveryorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>No. SO</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th>Qty Order</th>
                    <th width="15%">Qty Supply</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(@$getitemso){
                $n=1;
                foreach(@$getitemso as $kdo => $vdo){
                    $no=$n++;
                ?>
                <tr>
                    <td><center><?php echo $no?></center></td>
                    <td>
                        <center>
                        <?php echo $vdo->no_so?>
                        <input type="hidden" name="noso_todo[]" value="<?php echo $vdo->no_so?>">
                        </center>
                    </td>
                    <td>
                        <?php echo $vdo->id_barang.' / '.$vdo->nm_barang?>
                        <input type="hidden" name="id_barang[]" value="<?php echo $vdo->id_barang?>">
                    </td>
                    <td><?php echo $vdo->satuan?></td>
                    <td>
                        <?php echo $vdo->qty_order?>
                        <input type="hidden" id="qtyorder_<?php echo $no?>" value="<?php echo $vdo->qty_order?>">
                    </td>
                    <td>
                        <center><input onkeyup="cekqtysupply('<?php echo $no?>')" type="text" name="qty_supply[]" id="qty_supply_<?php echo $no?>" class="form-control input-sm"></center>
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
        <button class="btn btn-danger" onclick="kembali_do()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="saveheaderdo()">
            <i class="fa fa-save"></i><b> Simpan Data DO</b>
        </button>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-item-do" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data SO untuk Delivery Order (DO)</h4>
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
        $("#supir_do,#kendaraan_do").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        var dataTableItem = $('#deliveryorderitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
    });
    function saveheaderdo(){
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
            var formdata = $("#form-header-do,#form-detail-do").serialize();
            $.ajax({
                url: siteurl+"deliveryorder_2/saveheaderdo",
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
                            window.location.href=siteurl+'deliveryorder_2';
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
    function kembali_do(){
        window.location.href = siteurl+"deliveryorder_2";
    }
    function cekqtysupply(no){
        var order = $('#qtyorder_'+no).val();
        var supply = $('#qty_supply_'+no).val();
        if(supply > order){
            swal({
                title: "Peringatan!",
                text: "Qty Supply tidak boleh melebihi Qty Order",
                type: "warning",
                timer: 1500,
                showConfirmButton: false
            });
            $('#qty_supply_'+no).val(0);
        }
    }
</script>
