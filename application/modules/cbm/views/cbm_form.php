<div class="nav-tabs-cabang">
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="cabang">
        <!-- Biodata Mitra -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(), array('id'=>'frm_cbmzz','class'=>'form-horizontal'))?>
                <div class="box-body">
                <input type="hidden" name="id" value="<?php echo set_value('id_cbm', isset($data->id_cbm) ? $data->id_cbm : ''); ?>" />
                <div class="form-group "> 

                    <label for="name_cbm" class="col-sm-2 control-label">Name CBM <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="name_cbm" name="name_cbm" maxlength="45" value="<?php echo set_value('name_cbm', isset($data->name_cbm) ? $data->name_cbm : ''); ?>" placeholder="Name CBM" required>
                        </div>
                    </div>                                   
                </div>


                <div class="box-footer">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                    <button type="submit" name="save" class="btn btn-success" id="submit"><i class="fa fa-save">&nbsp;</i>Save</button>
                    <a class="btn btn-danger" data-toggle="modal" onclick="cancel()"><i class="fa fa-minus-circle">&nbsp;</i>Cancel</a>
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


    //Biodata
    $('#frm_cbmzz').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_cbmzz").serialize();
        $.ajax({
            url: siteurl+"cbm/edit_save",
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
                    window.location.reload();
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

    function cancel(){
        $(".box").show();
        $("#form-area").hide();
        //window.location.reload();
        //reload_table();
    }

    //function reload_table(){
        //table.ajax.reload(null,false); //reload datatable ajax
     //   table.ajax.reload();
   // }

</script>
