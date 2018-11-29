<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-mutasi" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="cabang_asal" class="col-sm-4 control-label">Cabang Asal </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <select class="form-control input-sm" name="cabang_asal" id="cabang_asal"> 
                                <option value="">Pilih</option>
                                <?php
                                foreach(@$cabang as $kc=>$vc){ 
                                    $selected='';
                                    if($kdcab->kdcab.'|'.$kdcab->namacabang == $vc->kdcab.'|'.$vc->namacabang){
                                        $selected='selected="selected"';
                                    }
                                    ?>
                                    <option value="<?php echo $vc->kdcab.'|'.$vc->namacabang?>" <?php echo $selected?>><?php echo $vc->kdcab.', '.$vc->namacabang?></option>
                                    <?php } ?>
                                </select> 
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="cabang_tujuan" class="col-sm-4 control-label">Cabang Tujuan<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                                <select class="form-control input-sm" name="cabang_tujuan" id="cabang_tujuan">
                                <option value="">Pilih</option>
                                <?php
                                foreach(@$cabang as $kc=>$vc){ 
                                    ?>
                                    <option value="<?php echo $vc->kdcab.'|'.$vc->namacabang?>"><?php echo $vc->kdcab.', '.$vc->namacabang?></option>
                                    <?php } ?>
                                </select> 
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ">
                            <label for="supir_mutasi" class="col-sm-4 control-label">Nama Supir <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <select class="form-control input-sm select2" name="supir_mutasi" id="supir_mutasi">
                                        <option value="">Pilih</option>
                                        <?php
                                        foreach(@$driver as $kd=>$vd){ 
                                        ?>
                                        <option value="<?php echo $vd->id_karyawan.'|'.$vd->nama_karyawan?>"><?php echo $vd->nama_karyawan?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="kendaraan_mutasi" class="col-sm-4 control-label">Kendaraan <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group" id="select-kendaraan">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <select class="form-control input-sm select2" name="kendaraan_mutasi" id="kendaraan_mutasi">
                                        <option value="">Pilih</option>
                                        <?php
                                        foreach(@$kendaraan as $kk=>$vk){ 
                                        ?>
                                        <option value="<?php echo $vk->id_kendaraan.'|'.$vk->nm_kendaraan?>"><?php echo $vk->nm_kendaraan?></option>
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
        <form id="form-detail-mutasi" method="post">
            <table class="table table-bordered" width="100%" id="tabel-detail-mutasi">
                <tr style="background: #ECF0F5;">
                    <th width="15%"><center>KODE PRODUK</center></th>
                    <th width="30%"><center>NAMA PRODUK</center></th>
                    <th width="10%"><center>STOK AVL</center></th>
                    <th width="10%"><center>STOK REAL</center></th>
                    <th width="10%"><center>QTY MUTASI</center></th>
                    <th width="5%">
                        <input class="form-control input-sm" type="hidden" id="rowcount" value="1">
                        <button class="btn btn-sm btn-success" id="tambah" type="button" style="width: 100%;">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                    </th>
                </tr>
            </table>
        </form>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_mutasi()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="savemutasi()">
            <i class="fa fa-save"></i><b> Simpan Data Mutasi</b>
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

<div class="modal modal-primary" id="dialog-data-stok" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Stok</h4>
      </div>
      <div class="modal-body" id="MyModalBodyStok" style="background: #FFF !important;color:#000 !important;">
          <table class="table table-bordered" width="100%" id="tabel-stok">
              <thead>
                  <tr>
                      <th width="75%">Produk Set</th>
                      <th width="10%">Stok Avl</th>
                      <th width="10%">Stok Real</th>
                      <th width="2%" class="text-center">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  foreach($stok_cabang as $ks=>$vs){
                  ?>
                  <tr>
                      <td><?php echo $vs->id_barang.', '.$vs->nm_barang?></td>
                      <td><center><?php echo $vs->qty_avl?></center></td>
                      <td><center><?php echo $vs->qty_stock?></center></td>
                      <td>
                        <center>
                            <button id="btn-<?php echo $vs->id_barang?>" class="btn btn-warning btn-sm" type="button" onclick="startmutasi('<?php echo $vs->id_barang?>','<?php echo $vs->nm_barang?>','<?php echo $vs->qty_avl?>','<?php echo $vs->qty_stock?>')">
                                Pilih
                            </button>
                        </center>
                      </td>
                  </tr>
                  <?php } ?>
              </tbody>
          </table>
      </div>
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
	//$('#input-kendaraan').hide();
    $(document).ready(function(){
        $("#supir_mutasi,#kendaraan_mutasi").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
    });
    $(function() {
      var dataTable = $("#tabel-stok").DataTable({lengthMenu:[5,10,15,20]}).draw();
    });
    function startmutasi(id,nm,avl,real){
        var row = parseInt($('#rowcount').val());
        var idnya = "'"+id+"'";
        html='<tr id="tr-'+row+'">'
            + '<td style="padding:3px;">'
            + '<input type="text" class="form-control input-sm kode-produk" name="kode_produk[]" id="kode_produk_'+row+'" readonly value="'+id+'">'
            + '</td>'
            + '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="nama_produk[]" id="nama_produk_'+row+'" readonly value="'+nm+'"></td>'
            + '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="stok_avl[]" id="stok_avl_'+row+'" style="text-align:center;" readonly value="'+avl+'"></td>'
            + '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="stok_real[]" id="stok_real'+row+'" style="text-align:center;" value="'+real+'" readonly></td>'
            + '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="qty_mutasi[]" id="qty_mutasi_'+row+'" style="text-align:center;" onkeyup="cekqtymutasi('+row+')"></td>'
            + '<td style="padding:3px;"><center><div class="btn-group" style="margin:0px;">'
            + '<button type="button" onclick="deleterow('+row+','+idnya+')" id="delete-row" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>'
            + '</div></center></td>'
            + '</tr>';
        $("#tabel-detail-mutasi").append(html);
        $("#btn-"+id).removeClass('btn-warning');
        $("#btn-"+id).addClass('btn-danger');
        $("#btn-"+id).attr('disabled',true);
        $("#btn-"+id).text('Sudah');
        $('#rowcount').val(row+1);
    }
    $("#tambah").click(function(){
        //startmutasi();
        $('#dialog-data-stok').modal('show');
    });
    function deleterow(tr,id){
        $('#tr-'+tr).remove();
        $("#btn-"+id).removeClass('btn-danger');
        $("#btn-"+id).addClass('btn-warning');
        $("#btn-"+id).attr('disabled',false);
        $("#btn-"+id).text('Pilih');
    }
    function savemutasi(){
        var tujuan = $('#cabang_tujuan').val();
        var supir = $('#supir_mutasi').val();
        var mobil = $('#kendaraan_mutasi').val();
        if(tujuan == "" || supir == "" || mobil == ""){
            swal({
                title: "Peringatan!",
                text: "Data harus lengkap",
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
          cancelButtonText: "Batal!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            var formdata = $("#form-header-mutasi,#form-detail-mutasi").serialize();
            $.ajax({
                url: siteurl+"mutasi/savemutasi",
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
                            window.location.href=siteurl+'mutasi';
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
    }
    function kembali_mutasi(){
        window.location.href = siteurl+"mutasi";
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function cekqtymutasi(no){
        var mutasi = parseInt($('#qty_mutasi_'+no).val());
        var avl = parseInt($('#stok_avl_'+no).val());
        if(filterAngka($('#qty_mutasi_'+no).val()) == 1){
            if(mutasi > avl){
                swal({
                    title: "Peringatan!",
                    text: "Qty Mutasi tidak boleh melebihi Stok Avl",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#qty_mutasi_'+no).val(0);
            }
        }else{
            var ang = $('#qty_mutasi_'+no).val();
            $('#qty_mutasi_'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }
</script>
