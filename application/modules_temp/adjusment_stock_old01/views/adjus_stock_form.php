    <!-- /.tab-content -->    
    <div class="tab-content">        
        <div class="tab-pane active" id="produk">            
        <!-- Data Produk -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Add Data</h3>
            </div>
            <?= form_open($this->uri->uri_string(),array('id'=>'frm_adjusment','name'=>'frm_adjusment','role'=>'form','class'=>'form-horizontal')) ?>
                <div class="box-body">

                <?php  if(isset($data->id_barang)){$type='edit';}?>
                <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>"> 

                <div class="form-group ">
                    <label for="id_barang" class="col-sm-2 control-label">Set Produk<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <div class="input-group-btn">
                            <a class="btn btn-info" title="Set Produk">
                            <i class="fa fa-plus">&nbsp;</i>
                            </a>
                        </div>
                        <select id="id_barang" name="id_barang" class="form-control pil_brg" style="width: 100%;" tabindex="-1" required onchange="set_data()">
                            <option value=""></option>
                            <?php foreach ($barang as $key => $st) : ?>
                            <option value="<?= $st->id_barang; ?>" <?= set_select('id_barang', $st->id_barang, isset($data->id_barang) && $data->id_barang == $st->id_barang) ?>>
                            <?= $st->nm_barang?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>

                   <label for="kategori" class="col-sm-2 control-label">Kategori<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <select id="kategori" name="kategori" class="form-control">
                            <option value="set" <?= set_select('kategori', 'set', isset($data->kategori) && $data->kategori == 'set'); ?>>Set                              
                            </option>
                            <option value="colly" <?= set_select('kategori', 'colly', isset($data->kategori) && $data->kategori == 'colly'); ?>>Colly                              
                            </option>                            
                            <option value="component" <?= set_select('kategori', 'component', isset($data->kategori) && $data->kategori == 'component'); ?>>Component                              
                            </option>
                        </select>
                        <input type="hidden" class="form-control" id="nm_barang" name="nm_barang" maxlength="45" value="<?php echo set_value('nm_barang', isset($data->nm_barang) ? $data->nm_barang : ''); ?>" placeholder="Nama Set" required readonly style="text-transform:uppercase">
                        </div>
                    </div>                            
                </div>                                                                    

                <div class="form-group ">
                    <label for="nm_barang" class="col-sm-2 control-label">Jenis <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" id="jenis" name="jenis" maxlength="45" value="<?php echo set_value('jenis', isset($data->jenis) ? $data->jenis : ''); ?>" placeholder="Jenis" required readonly style="text-transform:uppercase">
                        </div>
                    </div>    

                    <label for="brand" class="col-sm-2 control-label">Brand <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="Brand Produk" value="<?php echo set_value('brand', isset($data->brand) ? $data->brand : ''); ?>" style="text-transform:uppercase" readonly required>
                        </div>
                    </div>
                </div>
               
                <div class="form-group "> 
                    <label for="satuan" class="col-sm-2 control-label">Satuan <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan Produk" value="<?php echo set_value('satuan', isset($data->satuan) ? $data->satuan : ''); ?>" style="text-transform:uppercase" readonly required>
                        </div>
                    </div>

                    <label for="noreff" class="col-sm-2 control-label">No Referensi</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="noreff" name="noreff" placeholder="Referensi Adjusmen" value="<?php echo set_value('noreff', isset($data->noreff) ? $data->noreff : ''); ?>" style="text-transform:uppercase">
                        </div>
                    </div>                                                               
                </div>

                <div class="form-group "> 
                    <label for="qty" class="col-sm-2 control-label">Qty <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="qty" name="qty" placeholder="Qty Adjusment" value="<?php echo set_value('qty', isset($data->qty) ? $data->qty : ''); ?>" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" required>
                        </div>
                    </div>

                    <label for="nilai_barang" class="col-sm-2 control-label">Nilai Barang <font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                        <input type="text" class="form-control" id="nilai_barang" name="nilai_barang" placeholder="Nilai Adjusment" value="<?php echo set_value('nilai_barang', isset($data->nilai_barang) ? $data->nilai_barang : ''); ?>" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                        </div>
                    </div>                                                               
                </div>

                <div class="form-group ">
                    <label for="tipe_adjusment" class="col-sm-2 control-label">Tipe Adjusment<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-at"></i></span>
                        <select id="tipe_adjusment" name="tipe_adjusment" class="form-control">
                            <option value="IN" <?= set_select('tipe_adjusment', 'IN', isset($data->tipe_adjusment) && $data->tipe_adjusment == 'IN'); ?>>IN                              
                            </option>
                            <option value="OUT" <?= set_select('tipe_adjusment', 'OUT', isset($data->tipe_adjusment) && $data->tipe_adjusment == 'OUT'); ?>>OUT                              
                            </option>
                        </select>                        
                        </div>
                    </div>   

                    <label for="notes" class="col-sm-2 control-label">Catatan</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-street-view"></i></span>
                        <textarea class="form-control" id="notes" name="notes" style="text-transform:uppercase" placeholder="Catatan Adjusmnet" style="margin: 0px; height: 49px; width: 216px;"><?php echo set_value('notes', isset($data->notes) ? $data->notes : ''); ?></textarea>
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
        <!-- Data Produk -->
        </div>                  
    </div>
    <!-- /.tab-content -->

<script type="text/javascript">

    $("#id_barang").select2({
        placeholder: "Pilih Produk",
        allowClear: true
    });
    
    function set_data(){
        var id_barang = $('#id_barang').val();
        //alert(id_barang);
        if(id_barang!=''){
            $.ajax({
                type:"POST",
                url:siteurl+"adjusment_stock/get_data",
                data:{"id_barang":id_barang},
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nm_barang').val(data.nm_barang);
                    $('#jenis').val(data.jenis);
                    $('#brand').val(data.brand);
                    $('#satuan').val(data.satuan);                
                }
            })
        }else{
            $('#nm_barang').val('');
            $('#jenis').val('');
            $('#brand').val('');    
            $('#satuan').val(''); 
        }        
    }

    //frm_setupstock
    $('#frm_adjusment').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#frm_adjusment").serialize();

        $.ajax({
            url: siteurl+"adjusment_stock/save_data_ajax",
            dataType : "json",
            type: 'POST',
            //processData:false,
            //contentType:false,
            //cache:false,
            //async:false,
            data: formdata,
            //data: new FormData(this),
            //alert(msg);
            success: function(msg){
                if(msg['save']=='1'){
                    var barang =msg['barang'];
                    swal({
                      title: "Sukses!",
                      text: "Data Berhasil Disimpan",
                      type: "success",
                      timer: 1500,
                      showConfirmButton: false                  
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
</script>
