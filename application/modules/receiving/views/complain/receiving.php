<link href="<?= base_url()?>assets/css/switch.css" rel="stylesheet" />
<div class="nav-tabs-pr">
    <div class="tab-content">
        <div class="tab-pane active" id="pr">
            <div class="box box-primary">
                <form id="komplain"  method="post">
                <div class="form-horizontal">
                    <div class="box-body">
                        <input id="no_pr" type="hidden" name="no_po" value="<?= $nopo ?>"/>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">Supplier</label>
                                <div class="col-sm-8" style="margin-top: 6px;">
                                    <div class="input-group">
                                        <?= $rec->id_supplier ?> - <?= $rec->nm_supplier ?>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <label for="tglpr" class="col-sm-4 control-label">No. Pengiriman</label>
                                <div class="col-sm-8" style="margin-top: 6px;">
                                    <div class="input-group">
                                        <input name="no_pengiriman" class="form-control input-sm" />
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
           <tr>
                <th width="%">NO</th>
                <th width="%">ITEM NO.</th>
                <th width="%">NAMA BARANG</th>
                <th width="%">QTY </th>
                <th width="%">QTY BAGUS</th>
                <th width="%">QTY RUSAK</th>
                <th width="%">KETERANGAN</th>
            </tr>
            <?php
            $no=0;
            $t_rmb=0;
            $t_usd=0;
            $t_rp=0;
            $t_qty=0;
            foreach ($barang->result() as $row_bar)
            {
                $no++;
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $row_bar->id_barang ?></td>
                    <td><?= $row_bar->nama_barang ?></td>
                    <td><?= $row_bar->rusak ?></td>
                    <td></td>
                </tr>
                <?php
                $query_koli = $this->db->query("SELECT * FROM `receive_detail_koli` WHERE no_po='$nopo' and id_barang='$row_bar->id_barang' AND rusak !='0'");
    
                foreach ($query_koli->result() as $row_koli)
                {
                    ?>
                    <input type="hidden" name="id_barang[]" value="<?= $row_koli->id_barang ?>" />
                    <input type="hidden" name="id_koli[]" value="<?= $row_koli->id_koli ?>" />
                    <input type="hidden" name="nama_koli[]" value="<?= $row_koli->nama_koli ?>" />
                    <tr>
                        <td></td>
                        <td><?= $row_koli->id_koli ?></td>
                        <td><?= $row_koli->nama_koli ?></td>
                        <td>
                            <input readonly="" class="form-control input-sm" name="qty[]" value="<?= $row_koli->rusak ?>" />
                        </td>
                        <td>
                            <input class="form-control input-sm" name="bagus[]" value="<?= $row_koli->rusak ?>" />
                        </td>
                        <td>
                            <input class="form-control input-sm" name="rusak[]" value="0" />
                        </td>
                        <td>
                            <input class="form-control input-sm" name="keterangan[]" value="" />
                        </td>
                    </tr>
                    <?php
                }
            }
        ?>
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
<script>
    function saveheaderpr(){
            
                var formdata = $("#komplain").serialize();
               // console.log(formdata);
                $.ajax({
                    url: siteurl+"receiving/receiving_complain/receiving_save",
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
                                window.location.href=siteurl+'receiving/receiving_complain';
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
