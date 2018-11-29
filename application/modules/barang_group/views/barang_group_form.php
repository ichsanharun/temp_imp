<div class="nav-tabs-barang_group">
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="barang_group">
        <!-- Biodata Mitra -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_barang_group','name'=>'frm_barang_group','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">
                
                <input type="hidden" id="id_group" name="id_group" value="<?php echo set_value('id_group', isset($data->id_group) ? $data->id_group :''); ?>"> 
                <?php  if(isset($data->id_group)){$type='edit';}?>
                <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>"> 

                <div class="form-group ">
                    <label for="nm_group" class="col-sm-2 control-label">Nama Group Barang <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="nm_group" name="nm_group" maxlength="45" value="<?php echo set_value('nm_group', isset($data->nm_group) ? $data->nm_group : ''); ?>" placeholder="Nama Produk Group" required="">
                        </div>
                    </div>      

                    <label for="budget_margin" class="col-sm-2 control-label">Budget Margin <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="budget_margin" name="budget_margin" placeholder="Budget Margin in %" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?php echo set_value('budget_margin', isset($data->budget_margin) ? $data->budget_margin : ''); ?>" required>
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

                        <button type="submit" name="save" class="btn btn-primary" id="submit">Save</button>
                        <input type="button" name="btn_cancel" class="btn btn-danger" onclick="cancel()" value="Cancel">
                            
                    </div>
                </div>
                </div>

                </div>
            <?= form_close() ?>
            </div>
        <!-- Biodata Mitra -->
        </div>                                    

    </div>
    <!-- /.tab-content -->
</div>

<script type="text/javascript">

    function cancel(){
        $(".box").show();
        $("#form_barang").hide();
        window.location.reload();
        //reload_table();
    }

    //frm_barang_group
    $('#frm_barang_group').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_barang_group").serialize();
        $.ajax({
            url: siteurl+"barang_group/save_data_ajax",
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
</script>
