<div class="nav-tabs-supplier">
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="supplier">
        <!-- Biodata Mitra -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_komponen','name'=>'frm_komponen','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">
                
                <input type="hidden" id="id_komponen" name="id_komponen" value="<?php echo set_value('id_komponen', isset($data->id_komponen) ? $data->id_komponen : ''); ?>"> 
                <?php  if(isset($data->id_komponen)){$type='edit';}?>
                <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>"> 

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
                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                        <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" style="margin: 0px; height: 49px; width: 216px;"><?php echo set_value('keterangan', isset($data->keterangan) ? $data->keterangan : ''); ?></textarea>
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
        </div>                                    

    </div>
    <!-- /.tab-content -->
</div>

<script type="text/javascript">

    $(".pil_koli").select2({
        placeholder: "Pilih Koli",
        allowClear: true
    });

    function cancel(){
        $(".box").show();
        $("#frm_komponen").hide();
        window.location.reload();
        //reload_table();
    }

    //KOmponen
    $('#frm_komponen').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_komponen").serialize();
        $.ajax({
            url: siteurl+"komponen/save_data_ajax",
            dataType : "json",
            type: 'POST',
            data: formdata,
            //alert(msg);
            success: function(msg){
                if(msg['save']=='1'){
                    swal({
                        title: "Sukses!",
                        text: "Data Berhasil Di Simpan",
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    cancel();
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

    function get_barang(){
        var id_barang=$("#id_barang").val();
        $.ajax({
            type:"GET",
            url:siteurl+"koli/get_barang",
            data:"id_barang="+id_barang,
            dataType : "json",
            success:function(msg){
               $("#nm_barang").val(msg['nm_barang']);
            }
        });
    }
</script>
