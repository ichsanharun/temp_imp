<div class="nav-tabs-supplier">

    <div class="tab-content">
        <div class="tab-pane active" id="supplier">

            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>

            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_koli','name'=>'frm_koli','role'=>'form','class'=>'form-horizontal')) ?>
              <div class="box-body">

                <input type="hidden" id="id_koli" name="id_koli" value="<?php echo set_value('id_koli', isset($data->id_koli) ? $data->id_koli : ''); ?>">
                <?php  if(isset($data[0]->nama_koli)){$type='edit';}?>
                <input type="hidden" id="type" name="type_form" value="<?= isset($type) ? $type : 'add' ?>">

                <div class="form-group col-sm-11">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="id_koli_<?=$tipe?>" class="col-sm-4 control-label">ID <?=$tipe?> <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-user"></i></span>
                          <input type="text" class="form-control" id="id_koli_<?=$tipe?>" name="id_koli_<?=$tipe?>" maxlength="45" value="<?php echo $id?>" placeholder="ID" required="" readonly>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="koli_<?=$tipe?>" class="col-sm-4 control-label">Nama <?=$tipe?> <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                          <div class="input-group">

                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" id="koli_<?=$tipe?>" name="koli_<?=$tipe?>" maxlength="45" value="<?php echo $data[0]->nama_koli?>" placeholder="Nama <?=$tipe?>" required="">

                            <input type="hidden" name="tipe" value="<?php echo $tipe?>">
                          </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-sm-11">
                  <div class="row">
                    <div class="col-sm-6">
                      <label for="sts_aktif" class="col-sm-4 control-label">Status</label>
                      <div class="col-sm-8">
                          <select id="sts_aktif" name="sts_aktif" class="form-control">
                              <option value="aktif" <?= set_select('sts_aktif', 'aktif', isset($data->sts_aktif) && $data->sts_aktif == 'aktif'); ?>>Active
                              </option>
                              <option value="nonaktif" <?= set_select('sts_aktif', 'nonaktif', isset($data->sts_aktif) && $data->sts_aktif == 'nonaktif'); ?>>Inactive
                              </option>
                          </select>
                      </div>
                    </div>
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


    function cancel(){
        $(".box").show();
        $("#form_barang").hide();
        window.location.reload();
        //reload_table();
    }

    //Barang
    $('#frm_koli').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_koli").serialize();
        $.ajax({
            url: siteurl+"koli/save_data_anak",
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
