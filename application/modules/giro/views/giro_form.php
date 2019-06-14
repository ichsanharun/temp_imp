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
                  <div class="input-group">
                    <input type="text" class="form-control input-sm" name="girobank" id="girobank">
                    <!--
                    <select class="form-control input-sm select2" name="girobank" id="girobank">
                      <option value="">Pilih Bank</option>
                      <?php
                      foreach(@$bank as $kb=>$vb){
                        ?>
                        <option value="<?php echo $vb->kd_bank.'|'.$vb->nama_bank?>"><?php echo $vb->nama_bank?></option>
                        <?php } ?>
                    </select>
                    <div class="input-group-btn">
                        <button class="btn btn-md btn-primary" type="button" title="Tambah Data Giro" onclick="showmodalgiro()" id="btn-giro">
                        <i class="fa fa-plus">&nbsp;</i>
                        </button>
                    </div>
                  -->
                  </div>

                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-group ">
                <label for="nilai_fisik_giro" class="col-sm-4 control-label">Nilai Fisik </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm" name="nilai_fisik_giro" id="nilai_fisik_giro" onkeyup="filterAngka1(this.id);document.getElementById(this.id).value = formatCurrency(this.value);">
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

<div class="modal modal-primary" id="dialog-bank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabelGiro">Data Bank</h4>
      </div>
      <div class="modal-body" id="MyModalBodyGiro" style="background: #FFF !important;color:#000 !important;">
        <form id="form-data-bank" method="post" >
          <div class="form-horizontal">
            <div class="row">
            <div class="col-sm-5">
              <div class="form-group ">
                <label for="tgl_transaksi_giro" class="col-sm-4 control-label">Kode Bank/Jasa </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm" name="id_bank" id="id_bank" value="<?php echo $this->Giro_model->generate_bank()?>" readonly>
                </div>
              </div>


            </div>
            <div class="col-sm-7">
              <div class="form-group ">
                <label for="nilai_fisik_giro" class="col-sm-4 control-label">Nama Bank/Jasa </label>
                <div class="col-sm-8" style="padding-top: 8px;">
                  <input type="text" class="form-control input-sm" name="nama_bank" id="nama_bank">
                </div>
              </div>
            </div>
            </div>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="hidemodalgiro()">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>

        <button type="button" class="btn btn-success" onclick="simpandatabank()">
        <span class="glyphicon glyphicon-save"></span>  Simpan Data Giro</button>


      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
 $(document).ready(function() {
    $("#customer_giro").select2({
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

function showmodalgiro(){
  $('#dialog-bank').modal('show');
  setdatagiro();
}
function hidemodalgiro(){
  var no_giro = $('#no_giro').val();
  $('#dialog-bank').modal('hide');
  $('#no_reff').val(no_giro);
}

function formatCurrency(c){
    n = c.replace(/,/g, "");
  var s=n.split(',')[1];
  (s) ? s=","+s : s="";
  n=n.split(',')[0]
  while(n.length>3){
      s=","+n.substr(n.length-3,3)+s;
      n=n.substr(0,n.length-3)
  }
  return n+s

  }
  function filterAngka1(a){
      document.getElementById(a).value = document.getElementById(a).value.replace(/[^\d]/g,"");
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

  function simpandatabank(){
      var formdata = $('#form-data-bank').serialize();
      $.ajax({
        url: siteurl+"giro/simpandatabank",
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
              window.location.reload();
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
