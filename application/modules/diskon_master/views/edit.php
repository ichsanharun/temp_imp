<form id="frm-diskon" class="form-horizontal" method="post">
  <div class="box-body" style="border: solid 1px #fff">

    <?php if ($tipe == "barang") {
      ?>
        <div class="col-lg-12">

          <div class="form-group">
            <label for="diskon_persen" class="col-sm-6 control-label">Diskon Standard (%)<font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-6">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_standar_persen" name="diskon_standar_persen" placeholder="Input Diskon Standard" value="<?php echo set_value('diskon_standar_persen', isset($data->diskon_standar_persen) ? $data->diskon_standar_persen : ''); ?>" required maxsize="100">
                <input type="hidden" name="id" value="<?= $data->id_barang ?>">
                <input type="hidden" name="tipe" value="barang_stock">
                </div>
            </div>
          </div>

          <div class="form-group">
            <label for="diskon_persen" class="col-sm-6 control-label">Diskon Promo (%)<font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-6">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_promo_persen" name="diskon_promo_persen" placeholder="Input Diskon Standard" value="<?php echo set_value('diskon_promo_persen', isset($data->diskon_promo_persen) ? $data->diskon_promo_persen : ''); ?>" required>
                </div>
            </div>
          </div>

        </div>
      <?php
    }elseif ($tipe == "customer") {
      ?>
        <div class="col-lg-12">

          <div class="form-group">
            <label for="sts_aktif" class="col-sm-5 control-label">Bidang Usaha<font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-7">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>

                <select id="bidang_usaha" name="bidang_usaha" class="form-control" style="width: 100%;" tabindex="-1" required>
                    <option value=""></option>
                    <?php foreach ($bidus as $key => $value) {?>
                      <option value="<?=$value->bidang_usaha?>" <?php if ($data->bidang_usaha == $value->bidang_usaha) {echo "selected";} ?>>
                        <?=$value->bidang_usaha?>
                      </option>
                    <?php } ?>
                </select>
                <input type="hidden" name="id" value="<?= $data->id_customer ?>">
                <input type="hidden" name="tipe" value="customer">
                </div>
            </div>
          </div>

          <div class="form-group">
            <label for="diskon_persen" class="col-sm-5 control-label">Diskon Customer (%)<font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-7">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="diskon_toko" name="diskon_toko" placeholder="Input Diskon Standard" value="<?php echo set_value('diskon_toko', isset($data->diskon_toko) ? $data->diskon_toko : ''); ?>" required>
                </div>
            </div>
          </div>

        </div>
      <?php
    }else {
      ?>
        <div class="col-lg-12">
          <div class="form-group">
            <label for="sts_aktif" class="col-sm-5 control-label">Nama Diskon<font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-7">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                <input type="text" class="form-control" id="diskon" name="diskon" placeholder="Input Diskon Standard" value="<?php echo set_value('diskon', isset($data->diskon) ? $data->diskon : ''); ?>" required>
                <input type="hidden" name="id" value="<?= $data->id_diskon ?>">
                <input type="hidden" name="tipe" value="diskon">
                </div>
            </div>
          </div>

          <div class="form-group">
            <label for="diskon_persen" class="col-sm-5 control-label">Persen Diskon (%)<font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-7">
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');" id="persen" name="persen" placeholder="Input Diskon Khusus" value="<?php echo set_value('persen', isset($data->persen) ? $data->persen : ''); ?>" required>
                </div>
            </div>
          </div>

        </div>
      <?php
    } ?>



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
