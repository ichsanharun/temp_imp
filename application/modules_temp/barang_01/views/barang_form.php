<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#produk" data-toggle="tab" aria-expanded="true" id="data">Master Produk</a></li>                
        <li class=""><a href="#koli" data-toggle="tab" aria-expanded="false" id="data_koli">Koli</a></li>
        <li class=""><a href="#komponen" data-toggle="tab" aria-expanded="false" id="data_komponen">Komponen</a></li>
    </ul>
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="produk">
        <!-- Data Produk -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_barang','name'=>'frm_barang','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">
                
                <input type="hidden" id="id_barang" name="id_barang" value="<?php echo set_value('id_barang', isset($data->id_barang) ? $data->id_barang :''); ?>"> 
                <?php  if(isset($data->id_barang)){$type='edit';}?>
                <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>"> 

                <div class="form-group ">
                    <label for="id_group" class="col-sm-2 control-label">Group Produk <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <select id="id_group" name="id_group" class="form-control pil_gb" style="width: 100%;" tabindex="-1" required>
                                <option value=""></option>
                                <?php foreach ($group_barang as $key => $st) : ?>
                                <option value="<?= $st->id_group; ?>" <?= set_select('id_group', $st->id_group, isset($data->id_group) && $data->id_group == $st->id_group) ?>>
                                <?= $st->nm_group ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-btn">
                            <a class="btn btn-info" href="#add_gb" data-toggle="modal" title="Add Group Barang">
                            <i class="fa fa-plus">&nbsp;</i>
                            </a>
                            </div>
                        </div>
                    </div>

                    <label for="nm_barang" class="col-sm-2 control-label">Nama Produk <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="nm_barang" name="nm_barang" maxlength="45" value="<?php echo set_value('nm_barang', isset($data->nm_barang) ? $data->nm_barang : ''); ?>" placeholder="Nama Barang" required="">
                        </div>
                    </div>                    
                </div>                                                                    

                <div class="form-group ">
                    <label for="brand" class="col-sm-2 control-label">Brand <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand Barang" value="<?php echo set_value('brand', isset($data->brand) ? $data->brand : ''); ?>" required>
                        </div>
                    </div>

                    <label for="model" class="col-sm-2 control-label">Model <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>              
                        <input type="text" class="form-control" id="model" name="model" placeholder="Model Barang" value="<?php echo set_value('model', isset($data->model) ? $data->model : ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group ">                    
                    <label for="satuan" class="col-sm-2 control-label">Satuan</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-opencart"></i></span>
                        <input type="text" class="form-control" id="satuan" name="satuan" maxlength="45" value="<?php echo set_value('satuan', isset($data->satuan) ? $data->satuan : ''); ?>" placeholder="Satuan barang" >
                        </div>
                    </div>

                    <label for="spesifikasi" class="col-sm-2 control-label">Spesifikasi Barang</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                        <textarea class="form-control" id="spesifikasi" name="spesifikasi" placeholder="Spesifikasi Barang" style="margin: 0px; height: 49px; width: 216px;"><?php echo set_value('spesifikasi', isset($data->spesifikasi) ? $data->spesifikasi : ''); ?></textarea>
                        </div>
                    </div>   
                </div>

                <div class="form-group "> 
                    <label for="id_supplier" class="col-sm-2 control-label">Supplier <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-btn">
                            <a class="btn btn-info" data-toggle="modal" title="Add Group Barang">
                            <i class="fa fa-truck">&nbsp;</i>
                            </a>
                            </div>      
                            <select id="id_supplier" name="id_supplier" class="form-control pil_sup" style="width: 100%;" tabindex="-1" required>
                                <option value=""></option>
                                <?php foreach ($suppl_barang as $key => $st) : ?>
                                <option value="<?= $st->id_supplier; ?>" <?= set_select('id_supplier', $st->id_supplier, isset($data->id_supplier) && $data->id_supplier == $st->id_supplier) ?>>
                                <?= $st->nm_supplier ?>
                                </option>
                                <?php endforeach; ?>
                            </select>                                               
                        </div>
                    </div>

                    <label for="sts_aktif" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-2">
                        <select id="sts_aktif" name="sts_aktif" class="form-control">
                            <option value="aktif" <?= set_select('sts_aktif', 'aktif', isset($data->sts_aktif) && $data->sts_aktif == 'aktif'); ?>>Active                              
                            </option>
                            <option value="nonaktif" <?= set_select('sts_aktif', 'nonaktif', isset($data->sts_aktif) && $data->sts_aktif == 'nonaktif'); ?>>Inactive                              
                            </option>                            
                        </select>
                    </div>                               
                </div>                

                <div class="box-footer">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" name="save" class="btn btn-primary" id="submit">Save</button>
                        <input type="button" name="btn_cancel" class="btn btn-danger" onclick="cancel()" value="Cancel">
                            
                    </div>
                </div>
                </div>

                </div>
            <?= form_close() ?>
            </div>
        <!-- Data Produk -->
        </div>          

        <div class="tab-pane" id="koli">
        <!-- Data Koli -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_koli','name'=>'frm_koli','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">
                
                <input type="hidden" id="barang" name="barang" value="<?php echo set_value('barang', isset($data->id_barang) ? $data->id_barang : ''); ?>">
                <input type="hidden" id="barang_nm" name="barang_nm" value="<?php echo set_value('barang_nm', isset($data->nm_barang) ? $data->nm_barang : ''); ?>"> 
                <?php  if(isset($data->nm_koli)){$type1='edit';}?>
                <input type="hidden" id="type1" name="type1" value="<?= isset($type1) ? $type1 : 'add' ?>"> 

                <div class="form-group ">
                    <label for="nm_koli" class="col-sm-2 control-label">Nama Koli <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="nm_koli" name="nm_koli" maxlength="45" value="<?php echo set_value('nm_koli', isset($data->nm_koli) ? $data->nm_koli : ''); ?>" placeholder="Nama Koli" required="">
                        </div>
                    </div>  

                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                        <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" style="margin: 0px; height: 49px; width: 216px;"><?php echo set_value('keterangan', isset($data->keterangan) ? $data->keterangan : ''); ?></textarea>
                        </div>
                    </div>             
                </div>                                                                    

                <div class="form-group "> 
                    <label for="qty" class="col-sm-2 control-label">Qty <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="qty" name="qty" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="5" value="<?php echo set_value('qty', isset($data->qty) ? $data->qty : ''); ?>" placeholder="Qty Koli" required="">
                        </div>
                    </div>  

                    <label for="sts_aktif" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-2">
                        <select id="sts_aktif" name="sts_aktif" class="form-control">
                            <option value="aktif" <?= set_select('sts_aktif', 'aktif', isset($data->sts_aktif) && $data->sts_aktif == 'aktif'); ?>>Active                              
                            </option>
                            <option value="nonaktif" <?= set_select('sts_aktif', 'nonaktif', isset($data->sts_aktif) && $data->sts_aktif == 'nonaktif'); ?>>Inactive                              
                            </option>                            
                        </select>
                    </div>                               
                </div>                

                <div class="box-footer">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" name="btnkoli" class="btn btn-primary" id="btnkoli">Save</button>
                        <input type="button" name="btn_cancel" class="btn btn-danger" onclick="cancel()" value="Cancel">
                            
                    </div>
                </div>
                </div>

                </div>
            <?= form_close() ?>
            </div>
            <div id="list_koli"></div>
        <!-- Data Koli -->
        </div>    

        <div class="tab-pane" id="komponen">
        <!-- Data komponen -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_komponen','name'=>'frm_komponen','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">
                               
                <?php  if(isset($data->id_komponen)){$type2='edit';}?>
                <input type="hidden" id="type2" name="type2" value="<?= isset($type2) ? $type2 : 'add' ?>"> 

                <div class="form-group ">
                    <label for="nm_komponen" class="col-sm-2 control-label">Nama Komponen <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="nm_komponen" name="nm_komponen" maxlength="45" value="<?php echo set_value('nm_komponen', isset($data->nm_komponen) ? $data->nm_komponen : ''); ?>" placeholder="Nama Komponen" required="">
                        </div>
                    </div>  

                    <label for="id_koli" class="col-sm-2 control-label">Nama Koli <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-btn">
                            <a class="btn btn-info" data-toggle="modal" title="Add Group Barang">
                            <i class="fa fa-truck">&nbsp;</i>
                            </a>
                            </div>
                            <select id="id_koli" name="id_koli" class="form-control pil_koli" style="width: 100%;" tabindex="-1" required>
                                <option value=""></option>
                                <?php foreach ($koli as $key => $st) : ?>
                                <option value="<?= $st->id_koli; ?>" <?= set_select('id_koli', $st->id_koli, isset($data->id_koli) && $data->id_koli == $st->id_koli) ?>>
                                <?= $st->nm_koli ?>
                                </option>
                                <?php endforeach; ?>
                            </select>                            
                        </div>
                    </div>                  
                </div>                                                                    

                <div class="form-group "> 
                    <label for="qty_kom" class="col-sm-2 control-label">Qty <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="qty_kom" name="qty_kom" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" maxlength="5" value="<?php echo set_value('qty_kom', isset($data->qty) ? $data->qty : ''); ?>" placeholder="Qty Komponen" required="">
                        </div>
                    </div>  

                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                        <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" style="margin: 0px; height: 49px; width: 216px;"><?php echo set_value('keterangan', isset($data->keterangan) ? $data->keterangan : ''); ?></textarea>
                        </div>
                    </div>                       
                </div>

                <div class="form-group ">                     
                    <label for="sts_aktif" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-2">
                        <select id="sts_aktif" name="sts_aktif" class="form-control">
                            <option value="aktif" <?= set_select('sts_aktif', 'aktif', isset($data->sts_aktif) && $data->sts_aktif == 'aktif'); ?>>Active                              
                            </option>
                            <option value="nonaktif" <?= set_select('sts_aktif', 'nonaktif', isset($data->sts_aktif) && $data->sts_aktif == 'nonaktif'); ?>>Inactive                              
                            </option>                            
                        </select>
                    </div>                               
                </div>                

                <div class="box-footer">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                        <button type="submit" name="btnkomponen" class="btn btn-primary" id="btnkomponen">Save</button>
                        <input type="button" name="btn_cancel" class="btn btn-danger" onclick="cancel()" value="Cancel">
                            
                    </div>
                </div>
                </div>

                </div>
            <?= form_close() ?>
            </div>
            <div id="list_komponen"></div>
            </div>
        <!-- Data Komponen -->
        </div>                          

    </div>
    <!-- /.tab-content -->
</div>
<!-- Modal Bidus-->
<div class="modal modal-info" id="add_gb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Tambah Data Group Barang</h4>
            </div>
            <div class="modal-body" id="MyModalBody">
            <form action="#" id="form_gb">
            <div class="form-group">                
                <label for="gb">Nama Group Barang <font size="4" color="red"><B>*</B></font></label>
                <input type="text" class="form-control" id="nm_group" name="nm_group" placeholder="Nama Group BArang" required>
            </div>            
            </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="javascript:save_gb();">Save</button>
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Bidus-->
<script type="text/javascript">

    function get_gb(){
        $.ajax({
            type:"GET",
            url:siteurl+"barang/get_gb",
            success:function(html){
               $("#id_group").html(html);
            }
        });
    }

    function get_koli(id_barang){
        $.ajax({
            type:"GET",
            url:siteurl+"barang/get_koli",
            data:"id_barang="+id_barang,
            success:function(html){
               $("#id_koli").html(html);
            }
        });
    }
    //save_gb
    function save_gb(){

    var nm_group=$("#nm_group").val();

    if(nm_group==''){
        swal({
          title: "Peringatan!",
          text: "Isi Data Dengan Lengkap!",
          type: "warning",
          confirmButtonText: "Ok"
        });
        //die;
    }else{
        $.ajax({
            type:"POST",
            url:siteurl+"barang/add_gb",
            data:"nm_group="+nm_group,
            dataType : "json",
            success:function(msg){
                $('#add_gb').modal('hide');
                $('#form_gb')[0].reset(); // reset form on modals
                get_gb();
            }
        });
    }

    }
    
    $(document).ready(function() {

        var type = $('#type').val();
            if(type=='edit'){
                ShowOtherButton();
            }else{
                HideOtherButton();
            }

        $(".pil_sup").select2({
            placeholder: "Pilih Supplier Produk",
            allowClear: true
        });

        $(".pil_gb").select2({
            placeholder: "Pilih Group Produk",
            allowClear: true
        });

        $(".pil_koli").select2({
            placeholder: "Pilih Data Koli",
            allowClear: true
        });

        //Date picker
        $('#tanggallahir').datepicker({
          format: 'dd-mm-yyyy',
          todayHighlight: true,
          //startDate: new Date(),
          autoclose: true
        });

        $('#data_koli').click(function(){
            var id = $('#id_barang').val();
            if(id==''){
                $("#list_koli").hide();
            }else{
                load_koli(id);
            }
        });

        $('#data_komponen').click(function(){
            var id = $('#id_barang').val();
            if(id==''){
                $("#list_komponen").hide();
            }else{
                load_komponen(id);
                get_koli(id);
            }
        });

    });

    //Data Koli
    function load_koli(id_barang){
    $.ajax({
        type:"GET",
        url:siteurl+"barang/load_koli",
        data:"id_barang="+id_barang,
        success:function(html){
            $("#list_koli").html(html);
        }
    })
    }

    //Data komponen
    function load_komponen(id_barang){
    $.ajax({
        type:"GET",
        url:siteurl+"barang/load_komponen",
        data:"id_barang="+id_barang,
        success:function(html){
            $("#list_komponen").html(html);
        }
    })
    }

    function cancel(){
        $(".box").show();
        $("#form_barang").hide();
        window.location.reload();
        //reload_table();
    }

    //Barang
    $('#frm_barang').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_barang").serialize();
        $.ajax({
            url: siteurl+"barang/save_data_ajax",
            dataType : "json",
            type: 'POST',
            data: formdata,
            //alert(msg);
            success: function(msg){
                if(msg['save']=='1'){
                    var barang =msg['barang'];
                    swal({
                      title: "Sukses!",
                      text: "Data Berhasil Disimpan, Lanjutkan pengisian data Koli",
                      type: "success",
                      showCancelButton: true,
                      confirmButtonColor: "Blue",
                      confirmButtonText: "Ya, Lanjutkan",
                      cancelButtonText: "Tidak, Lain waktu saja",
                      closeOnConfirm: true,
                      closeOnCancel: true
                    },
                    function(isConfirm){
                      if (isConfirm) {
                        $('[href="#koli"]').tab('show');
                        $('#barang').val(barang);
                        load_koli(barang);
                        ShowOtherButton();
                      } else {
                        window.location.reload();
                        cancel();
                      }
                    });
                } else {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Di Simpan",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                };//alert(msg);
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

    //Koli
    $('#frm_koli').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_koli").serialize();
        $.ajax({
            url: siteurl+"barang/save_data_koli",
            dataType : "json",
            type: 'POST',
            data: formdata,
            //alert(msg);
            success: function(msg){
                if(msg['save']=='1'){
                    var barang =msg['barang'];
                    swal({
                      title: "Sukses!",
                      text: "Data Berhasil Disimpan, Lanjutkan pengisian data Komponen",
                      type: "success",
                      showCancelButton: true,
                      confirmButtonColor: "Blue",
                      confirmButtonText: "Ya, Lanjutkan",
                      cancelButtonText: "Tidak, Lain waktu saja",
                      closeOnConfirm: true,
                      closeOnCancel: true
                    },
                    function(isConfirm){
                      if (isConfirm) {
                        $('[href="#komponen"]').tab('show');
                        get_koli(barang);
                        load_komponen(barang);
                        ShowOtherButton();
                      } else {
                        window.location.reload();
                        cancel();
                      }
                    });
                } else {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Di Simpan",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                };//alert(msg);
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

    //Komponen
    $('#frm_komponen').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_komponen").serialize();
        $.ajax({
            url: siteurl+"barang/save_data_komponen",
            dataType : "json",
            type: 'POST',
            data: formdata,
            //alert(msg);
            success: function(msg){
                var barang = msg['barang'];
                if(msg['save']=='1'){
                    swal({
                        title: "Sukses!",
                        text: "Data Berhasil Di Simpan",
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    load_komponen(barang);
                    //cancel();
                    //document.getElementById("frm_biodata").reset();
                } else {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Di Simpan",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                };//alert(msg);                
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

    function ShowOtherButton()
    {
        //after success saving then activate sumbit button on each tab
        $("#btnkoli").show();
        $("#btnkomponen").show();
    }

    function HideOtherButton()
    {
        //after success saving then activate sumbit button on each tab
        $("#btnkoli").hide();
        $("#btnkomponen").hide();
    }

    //Delete Koli
    function hapus_koli(id){
        //alert(id);
        swal({
          title: "Anda Yakin?",
          text: "Data Toko Akan Terhapus secara Permanen!",
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
                    url: siteurl+'barang/hapus_koli/'+id,
                    dataType : "json",
                    type: 'POST',
                    success: function(msg){
                        if(msg['delete']=='1'){
                            $("#dataku"+id).hide(2000);
                            //swal("Terhapus!", "Data berhasil dihapus.", "success");
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
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

    //Delete Komponen
    function hapus_komponen(id){
        //alert(id);
        swal({
          title: "Anda Yakin?",
          text: "Data Toko Akan Terhapus secara Permanen!",
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
                    url: siteurl+'barang/hapus_komponen/'+id,
                    dataType : "json",
                    type: 'POST',
                    success: function(msg){
                        if(msg['delete']=='1'){
                            $("#dataku"+id).hide(2000);
                            //swal("Terhapus!", "Data berhasil dihapus.", "success");
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
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
</script>
