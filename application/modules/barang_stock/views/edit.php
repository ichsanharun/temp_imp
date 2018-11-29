<form id="frm-barang" class="form-horizontal" method="post">
  <div class="box-body" style="border: solid 1px #fff">
    <input type="hidden" name="id_brg" id="id_brg" value="<?php echo $data->id_barang;?>">
    <div class="col-lg-6">

      <div class="form-group">
        <label for="sts_aktif" class="col-sm-5 control-label">Status<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="hidden" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="sts_aktif_" name="sts_aktif_" placeholder="Status" value="<?php echo set_value('sts_aktif', isset($data->sts_aktif) ? $data->sts_aktif : ''); ?>" required>

            <select id="sts_aktif" name="sts_aktif" class="form-control" style="width: 100%;" tabindex="-1" required>
                <option value=""></option>
                <option value="aktif" <?php if ($data->sts_aktif == "aktif") {echo "selected";} ?>>
                  Aktif
                </option>
                <option value="nonaktif" <?php if ($data->sts_aktif == "nonaktif") {echo "selected";} ?>>
                  Tidak Aktif
                </option>
            </select>
            </div>
        </div>
      </div>

      <div class="form-group">
        <label for="poin_per_item" class="col-sm-5 control-label">Poin per Item<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="poin_per_item" name="poin_per_item" placeholder="Input POIN" value="<?php echo set_value('poin_per_item', isset($data->poin_per_item) ? $data->poin_per_item : ''); ?>" required>
            </div>
        </div>
      </div>
      <div class="form-group">
        <label for="diskon_promo_rp" class="col-sm-5 control-label">Diskon Promo (Rp)<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_promo_rp" name="diskon_promo_rp" placeholder="Input Diskon Promo (Rp)" value="<?php echo set_value('diskon_promo_rp', isset($data->diskon_promo_rp) ? $data->diskon_promo_rp : ''); ?>" required>
            </div>
        </div>
      </div>
      <div class="form-group">
        <label for="diskon_jika_qty" class="col-sm-5 control-label">Diskon Jika Qty<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_jika_qty" name="diskon_jika_qty" placeholder="Input Leadtime Produksi" value="<?php echo set_value('diskon_jika_qty', isset($data->diskon_jika_qty) ? $data->diskon_jika_qty : ''); ?>" required>
            </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">

      <div class="form-group">
        <label for="diskon_persen" class="col-sm-5 control-label">Diskon Standard (%)<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_persen" name="diskon_persen" placeholder="Input Diskon Standard" value="<?php echo set_value('diskon_persen', isset($data->diskon_standar_persen) ? $data->diskon_standar_persen : ''); ?>" required>
            </div>
        </div>
      </div>
      <div class="form-group">
        <label for="diskon_promo_persen" class="col-sm-5 control-label">Diskon Promo (%)<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_promo_persen" name="diskon_promo_persen" placeholder="Input Diskon Promo (%)" value="<?php echo set_value('diskon_promo_persen', isset($data->diskon_promo_persen) ? $data->diskon_promo_persen : ''); ?>" required>
            </div>
        </div>
      </div>
      <div class="form-group">
        <label for="diskon_qty_gratis" class="col-sm-5 control-label">Diskon Qty Gratis<font size="4" color="red"><B>*</B></font></label>
        <div class="col-sm-7">
            <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
            <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_qty_gratis" name="diskon_qty_gratis" placeholder="Input Leadtime Produksi" value="<?php echo set_value('diskon_qty_gratis', isset($data->diskon_qty_gratis) ? $data->diskon_qty_gratis : ''); ?>" required>
            </div>
        </div>
      </div>
    </div>



    <div class="form-group">
        <div class="col-sm-offset-9 col-sm-10">



        </div>
    </div>
  </div>

</form>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script type="text/javascript">
function savebarang(){
    var formdata = $("#frm-barang").serialize();
    $.ajax({
        url: siteurl+"barang_stock/savebarang_diskon/"+$('#id_brg').val(),
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
                    window.location.href=siteurl+'barang_stock';
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
</script>
