<div class="nav-tabs-supplier">
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="supplier">
        <!-- Biodata Mitra -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            <!-- form start-->
            <div class="box box-primary">
            <form id="form-data-giro" method="post">
          <div class="form-horizontal">
            <div class="row">
            <div class="col-sm-5">
              <div class="form-group ">
                <label for="tgl_transaksi_giro" class="col-sm-4 control-label">Tanggal Transaksi </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm datepicker" name="tgl_transaksi_giro" id="tgl_transaksi_giro">
                </div>
              </div>

              <div class="form-group ">
                <label for="no_giro" class="col-sm-4 control-label">Nomor Giro </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm" name="no_giro" id="no_giro">
                </div>
              </div>

              <div class="form-group ">
                <label for="girobank" class="col-sm-4 control-label">Nama Bank </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <select class="form-control input-sm select2" name="girobank" id="girobank">
                    <option value="">Pilih Bank</option>
                    <?php
                    foreach(@$bank as $kb=>$vb){
                      ?>
                      <option value="<?php echo $vb->kd_bank.'|'.$vb->nama_bank?>"><?php echo $vb->nama_bank?></option>
                      <?php } ?>
                    </select>
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-group ">
                <label for="nilai_fisik_giro" class="col-sm-4 control-label">Nilai Fisik </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm" name="nilai_fisik_giro" id="nilai_fisik_giro" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                </div>
              </div>

              <div class="form-group ">
                <label for="tgl_jth_tempo_giro" class="col-sm-4 control-label">Tgl Jatuh Tempo</label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm datepicker" name="tgl_jth_tempo_giro" id="tgl_jth_tempo_giro">
                </div>
              </div>

              <div class="form-group ">
                <label for="tgl_jth_tempo_giro" class="col-sm-4 control-label">Customer</label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <select class="form-control input-sm select2" name="customer_giro" id="customer_giro">
                      <option value="">Pilih Customer</option>
                      <?php foreach(@$customer as $kc=>$vc){ ?>
                      <option value="<?php echo $vc->id_customer.'|'.$vc->nm_customer?>"><?php echo $vc->id_customer.', '.$vc->nm_customer?></option>
                      <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            </div>
          </div>

          <div class="box-footer">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8" style="text-align: right;">
                        <input type="button" name="btn_cancel" class="btn btn-danger" onclick="cancel()" value="Cancel">
                        <button class="btn btn-primary" onclick="simpandatagiro()" type="button">Simpan Data Giro</button>
                    </div>
                </div>
                </div>
        </form>
            </div>
        </div>                                    

    </div>
    <!-- /.tab-content -->
</div>

<script type="text/javascript">
 $(document).ready(function() {
    $("#customer_giro,#girobank").select2({
        allowClear: true
    });
    $('#tgl_transaksi_giro,#tgl_jth_tempo_giro').datepicker({
        format : "yyyy-mm-dd",
        showInputs: true,
        autoclose:true
      });
});

function cancel(){
    var url = siteurl+'giro';
    window.location.href = url;
}

function simpandatagiro(){
    var formdata = $('#form-data-giro').serialize();
    $.ajax({
      url: siteurl+"giro/simpandatagiro",
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
            window.location.href=siteurl+'giro';
          },2000);
        } else {
          swal({
            title: "Gagal!",
            text: result['msg'],
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
