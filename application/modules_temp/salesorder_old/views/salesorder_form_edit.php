<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-so" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idcustomer" class="col-sm-4 control-label">Nama Customer <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getcustomer()">
                                <option value=""></option>
                                <?php
                                foreach(@$customer as $kc=>$vc){
                                ?>
                                <option value="<?php echo $vc->id_customer; ?>" <?php echo set_select('nm_customer', $vc->id_customer, isset($data->nm_customer) && $data->id_customer == $vc->id_customer) ?>>
                                    <?php echo $vc->id_customer.' , '.$vc->nm_customer ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmcustomer" id="nmcustomer" value="<?php echo @$data->nm_customer?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">Nama Salesman <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idsalesman" name="idsalesman" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getsalesman()">
                                <option value=""></option>
                                <?php
                                foreach(@$marketing as $km=>$vm){
                                ?>
                                <option value="<?php echo $vm->id_karyawan; ?>" <?php echo set_select('nm_salesman', $vm->id_karyawan, isset($data->id_salesman) && $data->id_salesman == $vm->id_karyawan) ?>>
                                    <?php echo $vm->nama_karyawan ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmsalesman" id="nmsalesman" class="form-control input-sm" value="<?php echo @$data->nm_salesman?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                             <?php 
                                $tglso=date('Y-m-d');
                                if(@$data){
                                    $tglso = @$data->tanggal;
                                }
                                ?>
                            <label for="tglso" class="col-sm-4 control-label">Tanggal <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglso" id="tglso" class="form-control input-sm datepicker" value="<?php echo $tglso?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ">
                            <label for="pic" class="col-sm-4 control-label">PIC <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="pic" id="pic" class="form-control input-sm" value="<?php echo @$data->pic?>">
                                    <input type="hidden" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="ppnso" id="ppnso" class="form-control input-sm" value="10" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <!--
                         <div class="form-group ">
                            <label for="dppso" class="col-sm-4 control-label">DPP <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="form-group ">
                            <label for="flagppnso" class="col-sm-4 control-label">Flag PPN <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <?php
                                    if(@$data->flag_ppn == 10){
                                    ?>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn" checked="checked" />PPN (10%)   
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn"/>Tanpa PPN
                                    <?php }else{ ?>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn"/>PPN (10%)   
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn" checked="checked"/>Tanpa PPN
                                    <?php } ?>
                                    <input type="hidden" name="nilaippn" id="nilaippn" value="<?php echo @$data->flag_ppn?>" />
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="form-group ">
                            <label for="ppnso" class="col-sm-4 control-label">PPN<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="ppnso" id="ppnso" class="form-control input-sm" value="10" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="totalso" class="col-sm-4 control-label">Grand Total <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        -->
                    </div>

                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
    <div class="box-body">
        <form id="form-detail-so" method="post">
        <table class="table table-bordered">
            <tr>
                <th class="text-center" colspan="8">FORM ITEM DETAIL</th>
            </tr>
            <tr style="text-align: center;">
                    <th width="25%">
                    <!-- onchange="setitembarang()" -->
                        <select id="item_brg_so" name="item_brg_so" class="form-control input-sm" style="width: 100%;" tabindex="-1" required readonly="readonly">
                            <option value=""></option>
                            <?php
                            foreach(@$itembarang as $k=>$v){
                            ?>
                            <option value="<?php echo $v->id_barang; ?>" <?php echo set_select('nm_barang', $v->id_barang, isset($data->nm_barang) && $data->id_barang == $v->id_barang) ?>>
                                <?php echo $v->id_barang.' , '.$v->nm_barang ?>
                            </option>
                            <?php } ?>
                        </select>
                    </th>
                    <th width="10%">
                        <input type="hidden" name="nama_barang" id="nama_barang" class="form-control input-sm"> 
                        <input type="text" name="satuan" id="satuan" class="form-control input-sm">
                    </th>
                    <th width="10%">
                        <input type="text" name="qty_avl" id="qty_avl" class="form-control input-sm" readonly="readonly">
                    </th>
                    <th width="10%">
                        <input type="number" name="qty_order" id="qty_order" class="form-control input-sm" min="1" value="1" onkeyup="hitungso()">
                    </th>
                    <th width="15%">
                        <input type="text" name="harga" id="harga" class="form-control input-sm" readonly="readonly">
                    </th>
                    <th width="10%">
                        <input type="number" name="diskon" id="diskon" class="form-control input-sm" min="0" max="100" value="0">
                    </th>
                    <th width="15%">
                        <input type="text" name="total" id="total" class="form-control input-sm" readonly="readonly">
                    </th>
                    <th width="3%">
                        <button class="btn btn-success btn-sm" type="submit" id="submit" name="save"><i class="fa fa-save"></i> Simpan</button>
                    </th>
                </tr>
        </table>
        </form>
        <table id="salesorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Qty Avl</th>
                    <th>Qty Order</th>
                    <th>Harga</th>
                    <th>Diskon (%)</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //print_r(@$listitembarang);
                if(@$listitembarang){
                $n=1;
                $grand = 0;
                foreach(@$listitembarang as $ks=>$vs){
                    $grand += $vs->harga*$vs->qty_order;
                    $no = $n++;
                ?>
                <tr>
                    <td class="text-center"><?php echo $no?></td>
                    <td><?php echo $vs->id_barang?></td>
                    <td><?php echo $vs->nm_barang?></td>
                    <td><?php echo $vs->satuan?></td>
                    <td><?php echo $vs->qty_supply?></td>
                    <td><?php echo $vs->qty_order?></td>
                    <td><?php echo $vs->harga?></td>
                    <td><?php echo $vs->diskon?></td>
                    <td class="text-right"><?php echo $vs->harga*$vs->qty_order?></td>
                    <td class="text-center">
                        <a class="text-green" href="javascript:void(0)" title="Edit" onclick="edit_detail('<?php echo $vs->no_so?>','<?php echo $vs->id_barang?>')"><i class="fa fa-pencil"></i>
                        </a>
                        <a style="display: none;" class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vs->no_so?>','<?php echo $vs->id_barang?>')"><i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-right">DPP : </th>
                    <th colspan="2" class="text-right"><?php echo $grand?><input type="hidden" name="grandtotalso" id="grandtotalso" value="<?php echo $grand?>"></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7" class="text-right">PPN : </th>
                    <th colspan="2" class="text-right"><span id="ppnview"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="7" class="text-right">GRAND TOTAL : </th>
                    <th colspan="2" class="text-right"><span id="totalview"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th class="text-right" colspan="10">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="saveheaderso()">
                            <i class="fa fa-save"></i><b> Simpan Data SO</b>
                        </button>
                    </th>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var gt = $('#grandtotalso').val();
        $('#dppso').val(gt);
        $("#idcustomer,#idsalesman").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        var dataTableItem = $('#salesorderitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
        sethitung();
    });
    function cancel(){
        $(".box").show();
        $("#form-area").hide();
    }
    function kembali(){
        window.location.href = siteurl+'salesorder';
    }
    function resetform(){
        $('#item_brg_so').val('');
        $('#nama_barang').val('');
        $('#harga').val(0);
        $('#satuan').val('');
        $('#qty_order').val(0);
        $('#qty_avl').val(0);
        $('#total').val(0);
    }
    function setppn(ppn){ 
        if(ppn == 10) {
            $('#nilaippn').val(10);
            sethitung();
        }else{
            $('#nilaippn').val(0);
            sethitung();
        }
    }
    function sethitung(){
        var npp = $('#nilaippn').val();
        var dpp = parseInt($('#dppso').val());
        var ppn = 10*dpp*0.01;
        if(npp == 0){
            ppn = 0;
        }
        $('#ppnso').val(ppn);
        $('#flagppnso').val(ppn);
        $('#totalso').val(dpp+ppn);
        $('#ppnview').html(ppn);
        $('#totalview').html(dpp+ppn);
    }
    function setitembarang(){
        var idbarang = $('#item_brg_so').val();
        var qty = $('#qty_order').val();
        if(idbarang != ""){
            $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_item_barang",
                data:"idbarang="+idbarang,
                success:function(result){
                    var data = JSON.parse(result);
                    console.log(data);
                    $('#nama_barang').val(data.nm_barang)
                    $('#harga').val(data.harga);
                    $('#satuan').val(data.satuan);
                    $('#qty_avl').val(data.qty_avl);
                    $('#total').val(data.harga*qty);
                }
            });
        }
    }
    function edit_detail(noso,id){
        if(noso != "" && id != ""){
            $.ajax({
                type:"POST",
                url:siteurl+"salesorder/get_detail_so",
                data:{"NO_SO":noso,"ID":id},
                success:function(result){
                    var data = JSON.parse(result);
                    $('#item_brg_so').val(data.id_barang);
                    $('#nama_barang').val(data.nm_barang);
                    $('#satuan').val(data.satuan);
                    $('#qty_avl').val(data.qty_supply);
                    $('#qty_order').val(data.qty_order);
                    $('#harga').val(data.harga);
                    $('#diskon').val(data.diskon);
                    $('#total').val(data.harga*data.qty_order);
                }
            });
        }
    }
    function getcustomer(){
        var idcus = $('#idcustomer').val();
        if(idcus != ''){
           $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_customer",
                data:"idcus="+idcus,
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nmcustomer').val(data.nm_customer);
                }
            }); 
        }
    }
    function getsalesman(){
        var idsls = $('#idsalesman').val();
        if(idsls != ''){
           $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_salesman",
                data:"idsales="+idsls,
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nmsalesman').val(data.nama_karyawan);
                }
            }); 
        }
    }
    function hitungso(){
        var harga = $('#harga').val();
        var qty = $('#qty_order').val();
        $('#total').val(harga*qty);
    }
    /*
    $('#form-detail-so').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#form-detail-so").serialize();
        $.ajax({
            url: siteurl+"salesorder/saveitemso",
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
                    resetform();
                    sethitung();
                    setTimeout(function(){
                        window.location.reload();
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
    });
    */
    function delete_datas(noso,id){
        //alert(id);
        swal({
          title: "Anda Yakin?",
          text: "Data Akan Terhapus secara Permanen!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, delete!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
            $.ajax({
                    url: siteurl+'salesorder/hapus_item_so',
                    data :{"NO_SO":noso,"ID":id},
                    dataType : "json",
                    type: 'POST',
                    success: function(result){
                        if(result.delete == '1'){                         
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.reload();
                            },1600);
                        } else {
                            swal({
                              title: "Gagal!",
                              text: "Data gagal dihapus",
                              type: "error",
                              timer: 1500,
                              showConfirmButton: false
                            });
                        };
                    },
                    error: function(){
                        swal({
                          title: "Gagal!",
                          text: "Gagal Eksekusi Ajax",
                          type: "error",
                          timer: 1500,
                          showConfirmButton: false
                        });
                    }
                });
          } else {
            //cancel();
          }
        });
    }

    function saveheadersos(){
        var formdata = $("#form-header-so").serialize();
        $.ajax({
            url: siteurl+"salesorder/saveheaderso",
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
                        window.location.href=siteurl+'salesorder';
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
</script>
