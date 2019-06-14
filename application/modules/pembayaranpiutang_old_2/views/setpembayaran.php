<?php //print_r($invoice)?>
<form id="form-pembayaran-piutang" method="post">
  <div class="form-horizontal">
    <div class="col-sm-6">
    <div class="form-group ">
      <label for="no_invoice" class="col-sm-4 control-label">No. Invoice </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="no_invoice" value="<?php echo @$invoice->no_invoice?>" class="form-control input-sm" readonly>
        <input type="hidden" name="kdcab" value="<?php echo @$invoice->kdcab?>" class="form-control input-sm" >
        <input type="hidden" name="idcus" value="<?php echo @$invoice->id_customer?>" class="form-control input-sm" >
        <input type="hidden" name="nmcus" value="<?php echo @$invoice->nm_customer?>" class="form-control input-sm" >
      </div>
    </div>
     <div class="form-group ">
      <label for="jml_piutang" class="col-sm-4 control-label">Nilai Invoice </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" value="<?php echo @$invoice->hargajualtotal?>" class="form-control input-sm" readonly>
      </div>
    </div>
    <div class="form-group ">
      <label for="jml_piutang" class="col-sm-4 control-label">Jumlah Piutang </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="jml_piutang" value="<?php echo @$invoice->piutang?>" class="form-control input-sm" readonly>
      </div>
    </div>
    <div class="form-group ">
      <?php $tglbyr=date('Y-m-d')?>
      <label for="tgl_bayar" class="col-sm-4 control-label">Tgl Pembayaran </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="tgl_bayar" id="tgl_bayar" class="form-control input-sm datepicker" value="<?php echo $tglbyr?>">
      </div>
    </div>
    </div>
    <div class="col-sm-6">

    <div class="form-group ">
      <label for="jenis_bayar" class="col-sm-4 control-label">Jenis Bayar </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <select class="form-control input-sm" name="jenis_bayar" id="jenis_bayar" onchange="setjenisbayar()">
          <option value="">Pilih Jenis Bayar</option>
          <?php
          foreach(is_jenis_bayar() as $kj=>$vj){
          ?>
          <option value="<?php echo $kj?>"><?php echo $vj?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group ">
      <label for="bank" class="col-sm-4 control-label">Bank </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <select class="form-control input-sm" name="bank" id="bank">
          <option value="">Pilih Bank</option>
          <?php
          foreach(@$bank as $kb=>$vb){
          ?>
          <option value="<?php echo $vb->kd_bank.'|'.$vb->nama_bank?>"><?php echo $vb->nama_bank?></option>
          <?php } ?>
        </select>
      </div>
    </div>

    <div class="form-group ">
      <label for="no_reff" class="col-sm-4 control-label">No. Reff </label>
      <div class="col-sm-8" style="padding-top: 8px;">
      <div class="input-group">
          <input type="text" name="no_reff" class="form-control input-sm" id="no_reff">
           <div class="input-group-btn">
             <button class="btn btn-sm btn-primary" type="button" title="Tambah Data Giro" onclick="showmodalgiro()" id="btn-giro">
             <i class="fa fa-plus">&nbsp;</i>
             </button>
          </button>
          </div>
        </div>
      </div>
    </div>

    <div class="form-group ">
      <label for="jml_bayar" class="col-sm-4 control-label">Jml Pembayaran </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="jml_bayar" id="jml_bayar" class="form-control input-sm" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
      </div>
    </div>

    </div>
  </div>
</form>
<table class="table table-bordered">
  <tr class="bg-blue">
    <th>No. Pembayaran</th>
    <th>No. Invoice</th>
    <th>No. Reff</th>
    <th>Jumlah Piutang</th>
    <th width="15%">Tgl Bayar</th>
    <th>Kode Bank</th>
    <th>Jumlah Bayar</th>
    <th width="5%"></th>
  </tr>
  <?php
  if(@$pembayaran){
    $total=0;
  foreach(@$pembayaran as $ki=>$vi){ 
    $total += $vi->jumlah_pembayaran;
  ?>
  <tr>
    <td><center><?php echo $vi->kd_pembayaran?></center></td>
    <td><center><?php echo $vi->no_invoice?></center></td>
    <td><center><?php echo $vi->no_reff?></center></td>
    <td class="text-right">
      <?php echo formatnomor($vi->jumlah_piutang)?>
    </td>
    <td><center><?php echo date('d-M-Y',strtotime($vi->tgl_pembayaran))?></center></td>
    <td><center><?php echo $vi->nm_bank?></center></td>
    <td class="text-right">
      <?php echo formatnomor($vi->jumlah_pembayaran)?>
    </td>
    <td>
      <center>
      <?php if($vi->is_cancel == 'N'){?>
      <button class="btn btn-xs btn-danger" type="button" title="Batal Pembayaran" onclick="batalbayar('<?php echo $vi->kd_pembayaran?>','<?php echo $vi->no_invoice?>')">
      <span class="fa fa-close"></span>
      </button>
      <?php }else{?>
      <span class="badge bg-green">Batal</span>
      <?php } ?>
      </center>
    </td>
  </tr>
  <?php } ?>
  <tr>
    <?php
    $total_view = formatnomor($total);
    if($total == 0){
      $total_view = 0;
    }
    ?>
    <td colspan="6"><center><b>TOTAL PEMBAYARAN</b></center></td>
    <td style="text-align: right;"><b><?php echo $total_view?></b></td>
    <td></td>
  </tr>
  <?php }else{ ?>
  <tr>
    <td colspan="8" class="bg-blue">Belum ada data Pembayaran</td>
  </tr>
  <?php } ?>
</table>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-detail-giro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabelGiro">Form Data Giro</h4>
      </div>
      <div class="modal-body" id="MyModalBodyGiro" style="background: #FFF !important;color:#000 !important;">
        <form id="form-data-giro" method="post">
          <div class="form-horizontal">
            <div class="row">
            <div class="col-sm-6">
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
                  <select class="form-control input-sm" name="girobank" id="girobank" readonly>
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
            <div class="col-sm-6">
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
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-8" style="padding-top: 8px;text-align: right;">
                  <button class="btn btn-success btn-sm" onclick="simpandatagiro()" type="button">Simpan Data Giro</button>
                </div>
              </div>

            </div>
            </div>
          </div>
        </form>
        <div id="div-tabel-data-giro"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="hidemodalgiro()">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('#bank').attr('disabled','disabled');
  $('#btn-giro').attr('disabled','disabled');
	$('#tgl_bayar').datepicker({
		startDate: 'm',
		endDate: '+0d',
		format : "yyyy-mm-dd",
		showInputs: true,
		autoclose:true,
		maxViewMode: 0
	});
  $('#tgl_transaksi_giro,#tgl_jth_tempo_giro').datepicker({
    format : "yyyy-mm-dd",
    showInputs: true,
    autoclose:true
  });
  function setjenisbayar() {
    var jb = $('#jenis_bayar').val();
    if(jb != ''){
      if(jb == 'CASH'){
        $('#bank').attr('disabled','disabled');
        $('#btn-giro').attr('disabled','disabled');
        $('#bank').val('');
      }else{
        $('#bank').removeAttr('disabled');
        $('#btn-giro').removeAttr('disabled');
      }
    }else{
      $('#bank').attr('disabled','disabled');
      $('#btn-giro').attr('disabled','disabled');
      $('#bank').val('');
    }
  }
  function batalbayar(id,inv){
    swal({
          title: "Peringatan !",
          text: "Yakin akan membatalkan Pembayaran?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, Batalkan!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            var url = siteurl+'pembayaranpiutang/batalpembayaranpiutang';
            $.post(url,{'ID':id,'INV':inv},function(result){
              if(result.cancel == 1){
                pembayaran(result.invoice);
              }
            },"json");
          }
        });
  }
  function showmodalgiro(){
    $('#dialog-detail-giro').modal('show');
    var bank = $('#bank').val();
    $('#girobank').val(bank);
    setdatagiro();
  }
  function hidemodalgiro(){
    var no_giro = $('#no_giro').val();
    $('#dialog-detail-giro').modal('hide');
    $('#no_reff').val(no_giro);
  }
  function setdatagiro(){
    var no_inv = '1';
    var url = siteurl+'pembayaranpiutang/setdatagiro';
    $.post(url,{'NO_INV':no_inv},function(result){
      $('#div-tabel-data-giro').html(result);
      $('#form-data-giro')[0].reset();
      var bank = $('#bank').val();
      $('#girobank').val(bank);
    });
  }
  function simpandatagiro(){
    var formdata = $('#form-data-giro').serialize();
    //alert(formdata);
    $.ajax({
      url: siteurl+"pembayaranpiutang/simpandatagiro",
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
          setdatagiro();
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
  function pilihgiro(no,nilai){
    //var no_giro = $('#no_giro').val();
    $('#dialog-detail-giro').modal('hide');
    $('#no_reff').val(no);
    $('#jml_bayar').val(nilai);
  }
</script>