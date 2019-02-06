<link rel="stylesheet" href="<?php echo base_url('assets/css/radiobutton.css')?>">
<link href="<?= base_url()?>assets/css/switch.css" rel="stylesheet" />
<div class="nav-tabs-pr">
    <div class="tab-content">
        <div class="tab-pane active" id="pr">
            <div class="box box-primary">
                <form id="form-header-pr"  method="post">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input id="no_pr" type="hidden" name="no_pr" value="<?= $no_pr ?>"/>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">Star Produksi<font size="4" color="red"><B>*</B></font></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="start_produksi" id="start_produksi" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">Finish Produksi<font size="4" color="red"><B>*</B></font></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="finish_produksi" id="finish_produksi" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">Proses Shipping<font size="4" color="red"><B>*</B></font></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="proses_shipping" id="proses_shipping" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
    
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">Shipping (ETD)<font size="4" color="red"><B>*</B></font></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="shipping" id="shipping" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">ETA<font size="4" color="red"><B>*</B></font></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" name="eta" id="eta" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d')?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">No. Invoice<font size="4" color="red"><B>*</B></font></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                        <input type="text" name="no_invoice" id="no_invoice" class="form-control input-sm" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
<div class="box box-default ">
    <div class="box-body">
       <table class="table table-bordered" width="100%">
           <thead >
               <tr>
                   <th>NO</th>
                   <th>Nama Barang</th>
                   <th>QTY Proforma Invoice</th>
                   <th>QTY Invoice</th>
               </tr>
           </thead>
           <tbody>
               <?php
               $no=0;
                foreach(@$itembarang as $data => $datas){
                    $no ++;
                    ?>
                    <input type="hidden" name="idet[]" value="<?= $datas->id_detail_po ?>" />
                    <tr>
                        <td><?= $no ?></td>
                        <td><?= $datas->nm_barang ?></td>
                        <td><?= $datas->qty_acc ?></td>
                        <td>
                            <input name="qty_i[]" value="<?= $datas->qty_acc ?>" />
                        </td>
                    </tr>
               <?php } ?>
           </tbody>
       </table>
    </div>
    </form>
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

                
<script type="text/javascript">
    $(document).ready(function() {
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
    });
</script>
<script>
    function saveheaderpr(){
            
                var formdata = $("#form-header-pr").serialize();
               // console.log(formdata);
                $.ajax({
                    url: siteurl+"profroma_invoice/konfrimasi_save",
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
                                window.location.href=siteurl+'profroma_invoice';
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