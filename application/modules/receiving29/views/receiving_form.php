<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-receiving">
    <div class="tab-content">
        <div class="tab-pane active" id="receiving">
            <div class="box box-primary">
                <form id="form-header-po" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <?php //print_r(@$headerpo)?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-4 control-label">Nama Supplier :</font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                            <?php echo @$headerpo[0]->id_supplier.' / '.@$headerpo[0]->nm_supplier?>
                            <input type="hidden" name="idsupplier" id="idsupplier" value="<?php echo @$headerpo[0]->id_supplier?>">
                            <input type="hidden" name="nmsupplier" id="nmsupplier" value="<?php echo @$headerpo[0]->nm_supplier?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tglreceive" class="col-sm-4 control-label">Tanggal Receive :</font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                            <?php echo date('d M Y');?>
                            <input type="hidden" name="tglreceive" id="tglreceive" value="<?php echo date('Y-m-d')?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                         <div class="form-group ">
                            <label for="tipekirim" class="col-sm-4 control-label">No. DO Supplier :</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="no_do_supplier" id="no_do_supplier" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tgldosupp=date('Y-m-d')?>
                            <label for="tgldosupp" class="col-sm-4 control-label">Tanggal DO Supplier :</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tgldosupp" id="tgldosupp" class="form-control input-sm datepicker" value="<?php echo $tgldosupp?>">
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
        <table id="podetailtoreceiving" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>No. PO</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th>Qty PO</th>
                    <th>Qty Received</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $n=1;
                foreach(@$detailpo as $kp=>$vp){ 
                    $no=$n++;
                ?>
                <tr>
                    <td width="1%"><center><?php echo $no?></center></td>
                    <td><center><?php echo $vp->no_po?></center></td>
                    <td><?php echo $vp->id_barang.' / '.$vp->nm_barang?></td>
                    <td><center><?php echo $vp->satuan?></center></td>
                    <td><center><?php echo $vp->qty_po?></center></td>
                    <td width="10%">
                        <center>
                            <input style="width: 70%;" type="hidden" name="id_po_to_received[]" id="id_po_to_received" class="form-control input-sm" value="<?php echo $vp->id_detail_po?>">
                            <input style="width: 70%;" type="text" name="qty_received[]" id="qty_received" class="form-control input-sm">
                        </center>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </form>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_receiving()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="saveheaderreceiving()">
            <i class="fa fa-save"></i><b> Simpan Data Receiving</b>
        </button>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        var dataTableItem = $('#podetailtoreceiving').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
    });
    function kembali_receiving(){
        window.location.href = siteurl+'receiving/create';
    }
    function saveheaderreceiving(){
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
                url: siteurl+"receiving/saveheaderreceiving",
                dataType : "json",
                type: 'POST',
                data: formdata,
                success: function(result){
                    if(result.save=='1'){
                        swal({
                            title: "Sukses!",
                            text: result['msg'],
                            type: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function(){
                            window.location.href=siteurl+'receiving';
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
</script>
