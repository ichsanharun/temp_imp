<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-invoice" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idcustomer_do" class="col-sm-4 control-label">Nama Customer </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerdo[0]->nm_customer?>
                                <input type="hidden" name="idcustomer_do" value="<?php echo $headerdo[0]->id_customer?>">
                                <input type="hidden" name="nmcustomer_do" value="<?php echo $headerdo[0]->nm_customer?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idcustomer" class="col-sm-4 control-label">Alamat Customer </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerdo[0]->alamat_customer?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">Nama Salesman </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".$headerdo[0]->nm_salesman?>
                                <input type="hidden" name="id_salesman" value="<?php echo $headerdo[0]->id_salesman?>">
                                <input type="hidden" name="nm_salesman" value="<?php echo $headerdo[0]->nm_salesman?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                         <div class="form-group ">
                            <?php $tgldo=date('Y-m-d')?>
                            <label for="tgldo" class="col-sm-4 control-label">Tanggal DO </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ": ".date('d M Y',strtotime($headerdo[0]->tgl_do))?>
                                <input type="hidden" name="tgldo" value="<?php echo $headerdo[0]->tgl_do?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">T.O.P </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php echo ': 45 Hari'?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tgldo" class="col-sm-4 control-label">Tgl Jatuh Tempo </label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <?php
                                $tglnow = date('Y-m-d');
                                $jthtempo = date('d M Y',strtotime('+45 days',strtotime($tglnow)));
                                echo ": ".$jthtempo; 
                                ?>
                                <input type="hidden" name="tgljthtempo" value="<?php echo date('Y-m-d',strtotime($jthtempo))?>">
                                <input type="hidden" name="param_do" value="<?php echo $this->input->get('param')?>">
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
        <form id="form-detail-invoice" method="post">
        <table id="deliveryorderitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th width="15%">No. DO</th>
                    <th width="15%">No. SO</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th width="10%">Qty Supply</th>
                    <th width="10%">Harga</th>
                    <th width="5%">Diskon</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1; 
                foreach(@$detaildo as $kd=>$vd){
                    $no = $n++;
                    $key = array('no_so'=>$vd->no_so,'id_barang'=>$vd->id_barang);
                    $detailso = $this->Invoice_model->cek_data($key,'trans_so_detail'); 
                    $grand += $detailso->harga*$vd->qty_supply;
                ?>
                <tr>
                    <td><center><?php echo $no?></center></td>
                    <td><center><?php echo $vd->no_do?></center></td>
                    <td><center><?php echo $vd->no_so?></center></td>  
                    <td><?php echo $vd->id_barang.' / '.$vd->nm_barang?></td>
                    <td><center><?php echo $vd->satuan?></center></td>
                    <td><center><?php echo $vd->qty_supply?></center></td>
                    <td><center><?php echo formatnomor($detailso->harga)?></center></td>
                    <td><center><?php echo $detailso->diskon.' %'?></center></td>
                    <td class="text-right"><?php echo formatnomor($detailso->harga*$vd->qty_supply)?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" class="text-right"><b>GRAND TOTAL</b></td>
                    <td colspan="2" class="text-right"><b><?php echo formatnomor($grand)?></b></td>
                </tr>
            </tfoot>
        </table>
        </form>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_inv()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="saveheaderinvoice()">
            <i class="fa fa-save"></i><b> Simpan Data Invoice</b>
        </button>
    </div>
  </div>
</div>
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
    function saveheaderinvoice(){
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
            var formdata = $("#form-header-invoice").serialize();
            $.ajax({
                url: siteurl+"invoice/saveheaderinvoice",
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
                            window.location.href=siteurl+'invoice';
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
    function kembali_inv(){
        window.location.href = siteurl+"invoice";
    }
</script>
