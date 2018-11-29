<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-body">
        <?php //print_r($detail)?>
        <form id="form-detail-so-pending" method="post">
        <table id="salesorderitem" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="10%">Kode</th>
                <th width="20%">Produk Set</th>
                <th>Satuan</th>
                <th width="10%"><center>Qty Order Awal</center></th>
                <th width="10%"><center>Qty Supply</center></th>
                <th width="10%"><center>Qty Pending</center></th>
                <th width="10%"><center>Qty Available</center></th>
                <th width="10%"><center>Qty Confirm</center></th>
                <th width="10%"><center>Qty Pending Again</center></th>
                <th width="10%"><center>Qty Cancel</center></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n=1;
            foreach($detail as $kv=>$vo){
                $no=$n++;
                $session = $this->session->userdata('app_session');
                $key = array(
                    'id_barang'=>$vo->id_barang,
                    'kdcab' => $session['kdcab']
                    );
                $stok = $this->Salesorder_model->cek_data($key,'barang_stock');
                //print_r($stok);
            ?>
            <tr>
                <td><?php echo $vo->id_barang?></td>
                <td><?php echo $vo->nm_barang?></td>
                <td><center><?php echo $vo->satuan?></center></td>
                <td><center><?php echo $vo->qty_order?></center></td>
                <td><center><?php echo $vo->qty_supply?></center></td>
                <td><center><?php echo $vo->qty_pending?></center></td>
                <td><center><?php echo $stok->qty_avl?></center></td>
                <td>
                    <center>
                        <input type="hidden" name="stok_avl[]" id="stok_avl<?php echo $no?>" class="form-control input-sm" value="<?php echo $stok->qty_avl?>">
                        <input type="hidden" name="no_so_pending[]" id="no_so_pending<?php echo $no?>" class="form-control input-sm" value="<?php echo $vo->no_so?>">
                        <input type="hidden" name="id_barang[]" id="id_barang<?php echo $no?>" class="form-control input-sm" value="<?php echo $vo->id_barang?>">
                        <input type="hidden" name="qty_pending[]" id="qty_pending<?php echo $no?>" class="form-control input-sm" value="<?php echo $vo->qty_pending?>">
                        <input type="text" name="qty_confirm[]" id="qty_confirm<?php echo $no?>" class="form-control input-sm" style="width: 70%;" onkeyup="cekconfirm('<?php echo $no?>')" required>
                    </center>
                </td>
                <td>
                    <center>
                        <input type="text" name="pending_again[]" id="pending_again<?php echo $no?>" class="form-control input-sm" style="width: 70%;" onkeyup="hitungcancel('<?php echo $no?>')" required>
                    </center>
                </td>
                <td>
                    <center>
                        <input type="text" name="cancel_again[]" id="cancel_again<?php echo $no?>" class="form-control input-sm" style="width: 70%;" readonly="readonly">
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
        <button class="btn btn-danger" onclick="kembali_pending()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="savependingso()">
            <i class="fa fa-save"></i><b> Simpan Proses Pending</b>
        </button>
    </div>
  </div>
</div>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
    var uri3 = '<?php echo $this->uri->segment(3)?>';
    console.log(uri3);
    $(function() {
        var dataTableItem = $('#salesorderitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
    });
    function cekconfirm(no){
        var avl = parseInt($('#stok_avl'+no).val());
        var pending = parseInt($('#qty_pending'+no).val());
        var confirm = parseInt($('#qty_confirm'+no).val());
        if(filterAngka($('#qty_confirm'+no).val()) == 1){
            if(avl <= 0){
                swal({
                    title: "Peringatan!",
                    text: "Stok Available belum mencukupi",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#qty_confirm'+no).val(0);
            }else{
                if(confirm > pending){
                    swal({
                        title: "Peringatan!",
                        text: "Qty Confirm tidak boleh melebihi Qty Pending",
                        type: "warning",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#qty_confirm'+no).val(0);
                }else{
                    if(pending == confirm){
                        $('#pending_again'+no).val(0);
                        $('#cancel_again'+no).val(0);
                    }
                }
            }
        }else{
            var ang = $('#qty_confirm'+no).val();
            $('#qty_confirm'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }
    function hitungcancel(no){
        var pending = parseInt($('#qty_pending'+no).val());
        var pending_again = parseInt($('#pending_again'+no).val());
        var confirm = parseInt($('#qty_confirm'+no).val());
        var x = confirm+pending_again;
        var cancel = pending-x;
        if(filterAngka($('#pending_again'+no).val()) == 1){
            if($('#qty_confirm'+no).val() != ''){
                if(x > pending){
                    swal({
                        title: "Peringatan!",
                        text: "Melebihi Qty Pending",
                        type: "warning",
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#pending_again'+no).val('');
                }else{
                    if(!isNaN(cancel)){
                        $('#cancel_again'+no).val(cancel);
                    }
                }
            }else{
               swal({
                    title: "Peringatan!",
                    text: "Qty Confirm tidak boleh kosong",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                /* 
               $('#pending_again'+no).val('');
               setTimeout(function(){ 
                    $('#qty_confirm'+no).focus();
               },1000);
               */
            }
        }else{
           var ang = $('#pending_again'+no).val();
            $('#pending_again'+no).val(ang.replace(/[^0-9]/g,'')); 
        }
    }
    function savependingso(){
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
            var formdata = $("#form-detail-so-pending").serialize();
            $.ajax({
                url: siteurl+"pendingso/savependingso/"+uri3,
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
                            window.location.href=siteurl+'pendingso';
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
    function kembali_pending(){
        window.location.href = siteurl+'pendingso';
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
</script>