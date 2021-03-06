<div class="nav-tabs-supplier">

    <div class="tab-content">
        <div class="tab-pane active" id="supplier">

            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>

            <div class="box box-primary">
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_koli','name'=>'frm_koli','role'=>'form','class'=>'form-horizontal')) ?>
              <div class="box-body">

                <input type="hidden" id="id_koli" name="id_koli" value="<?php echo set_value('id_koli', isset($data->id_koli) ? $data->id_koli : ''); ?>">
                <?php  if(isset($data->id_koli)){$type='edit';}?>
                <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>">

                <div class="form-group col-sm-11">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="nm_koli" class="col-sm-4 control-label">Nama Koli <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-user"></i></span>
                          <input type="text" class="form-control" id="nm_koli" name="nm_koli" maxlength="45" value="<?php echo set_value('nm_koli', isset($data->nm_koli) ? $data->nm_koli : ''); ?>" placeholder="Nama Koli" required="" readonly>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="id_barang" class="col-sm-4 control-label">Nama Barang <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                          <div class="input-group">

                              <div class="input-group-btn">
                                <a class="btn btn-info" data-toggle="modal" title="Add Group Barang">
                                  <i class="fa fa-truck">&nbsp;</i>
                                </a>
                              </div>

                              <select id="id_barang" name="id_barang" class="form-control pil_barang" style="width: 100%;" tabindex="-1" required onchange="get_barang()">
                                  <option value=""></option>
                                <?php foreach ($barang as $key => $st) : ?>
                                  <option value="<?= $st->id_barang; ?>" <?= set_select('id_barang', $st->id_barang, isset($data->id_barang) && $data->id_barang == $st->id_barang) ?>>
                                    <?= $st->nm_barang ?>
                                  </option>
                                <?php endforeach; ?>
                              </select>

                              <input type="hidden" class="form-control" id="nm_barang" name="nm_barang" maxlength="45" value="<?php echo set_value('nm_barang', isset($data->nm_barang) ? $data->nm_barang : ''); ?>">

                          </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-sm-11">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="nm_koli" class="col-sm-4 control-label">Nama Model <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                        <div class="input-group">

                            <div class="input-group-btn">
                              <a href="#koli_model" data-toggle="tab" class="btn btn-info" title="Add Model">
                                <i class="fa fa-plus">&nbsp;M</i>
                              </a>
                            </div>

                            <select id="id_koli_model" name="id_koli_model" class="form-control pil_barang" style="width: 100%;" tabindex="-1" required onchange="get_barang()">
                                <option value=""></option>
                              <?php foreach ($model as $key => $st) : ?>
                                <option value="<?= $st->id_koli_model; ?>" <?= set_select('id_koli_model', $st->id_koli_model, isset($data->id_koli_model) && $data->id_koli_model == $st->id_koli_model) ?>>
                                  <?= $st->koli_model ?>
                                </option>
                              <?php endforeach; ?>
                            </select>

                            <input type="hidden" class="form-control" id="koli_model" name="koli_model" maxlength="45" value="<?php echo set_value('koli_model', isset($data->koli_model) ? $data->koli_model : ''); ?>">

                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="id_barang" class="col-sm-4 control-label">Nama Warna <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                        <div class="input-group">

                            <div class="input-group-btn">
                              <a class="btn btn-info" data-toggle="modal" title="Tambah Warna">
                                <i class="fa fa-plus">&nbsp;W</i>
                              </a>
                            </div>

                            <select id="id_koli_warna" name="id_koli_warna" class="form-control pil_barang" style="width: 100%;" tabindex="-1" onchange="get_barang()">
                                <option value=""></option>
                              <?php foreach ($warna as $key => $st) : ?>
                                <option value="<?= $st->id_koli_warna; ?>" <?= set_select('id_koli_warna', $st->id_koli_warna, isset($data->id_koli_warna) && $data->id_koli_warna == $st->id_koli_warna) ?>>
                                  <?= $st->koli_warna ?>
                                </option>
                              <?php endforeach; ?>
                            </select>

                            <input type="hidden" class="form-control" id="koli_warna" name="koli_warna" maxlength="45" value="<?php echo set_value('koli_warna', isset($data->koli_warna) ? $data->koli_warna : ''); ?>">

                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group col-sm-11">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="nm_koli" class="col-sm-4 control-label">Nama Varian <font size="4" color="red"><B>*</B></font></label>
                      <div class="col-sm-8">
                        <div class="input-group">

                            <div class="input-group-btn">
                              <a class="btn btn-info" data-toggle="modal" title="Tambah Varian">
                                <i class="fa fa-plus">&nbsp;V</i>
                              </a>
                            </div>

                            <select id="id_koli_varian" name="id_koli_varian" class="form-control pil_barang" style="width: 100%;" tabindex="-1" onchange="get_barang()">
                                <option value=""></option>
                              <?php foreach ($varian as $key => $st) : ?>
                                <option value="<?= $st->id_koli_varian; ?>" <?= set_select('id_koli_varian', $st->id_koli_varian, isset($data->id_koli_varian) && $data->id_koli_varian == $st->id_koli_varian) ?>>
                                  <?= $st->koli_varian ?>
                                </option>
                              <?php endforeach; ?>
                            </select>

                            <input type="hidden" class="form-control" id="koli_varian" name="koli_varian" maxlength="45" value="<?php echo set_value('koli_varian', isset($data->koli_varian) ? $data->koli_varian : ''); ?>">

                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="keterangan" class="col-sm-4 control-label">Keterangan</label>
                      <div class="col-sm-8">
                          <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                          <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan" style="margin: 0px; height: 49px; width: 216px;"><?php echo set_value('keterangan', isset($data->keterangan) ? $data->keterangan : ''); ?></textarea>
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
  $(document).ready(function(){
    $("#id_barang").select2({
        placeholder: "Pilih Barang",
        allowClear: true
    });

    $("#id_koli_model").select2({
        placeholder: "Pilih Model",
        allowClear: true
    });

    $("#id_koli_warna").select2({
        placeholder: "Pilih Warna",
        allowClear: true
    });

    $("#id_koli_varian").select2({
        placeholder: "Pilih Varian",
        allowClear: true
    });


  });

  $('#id_koli_model,#id_koli_warna,#id_koli_varian').on('change', function(){

    var model = $("#id_koli_model option:selected").text().split(' ').join('');
    var warna = $("#id_koli_warna option:selected").text().split(' ').join('');
    var varian = $("#id_koli_varian option:selected").text().split(' ').join('');
    console.log(model);
    $("#nm_koli").val(model+' '+warna+' '+varian);
  });



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
            url: siteurl+"koli/save_data_ajax",
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
