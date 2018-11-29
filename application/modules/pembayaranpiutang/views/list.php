<?php
/*
    $ENABLE_ADD     = has_permission('Reportstok.Add');
    $ENABLE_MANAGE  = has_permission('Reportstok.Manage');
    $ENABLE_VIEW    = has_permission('Reportstok.View');
    $ENABLE_DELETE  = has_permission('Reportstok.Delete');
    */
?>
<style type="text/css">
thead input {
  width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
  <div class="col-lg-12">
  
    <div class="box-header text-left">
      <div class="form-inline">
        <div class="form-group">
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-share"></i></span>
              <select class="form-control input-sm" id="filtercabang" disabled="disabled">
                <option value="">Pilih Cabang</option>
                <?php 
                $session = $this->session->userdata('app_session');
                $kdcab = $session['kdcab'];
                foreach(@$cabang as $k=>$v){ 
                  $selected = '';
                  if($this->uri->segment(3) == $v->kdcab){
                    $selected='selected="selected"';
                  }
                  if($kdcab == $v->kdcab){
                    $selected='selected="selected"';
                  }
                ?>
                <option value="<?php echo $v->kdcab?>" <?php echo $selected?>><?php echo $v->kdcab.', '.$v->namacabang?></option>
                <?php } ?>
              </select>
          </div>
          <input type="button" id="submit" class="btn btn-sm btn-warning" value="Tampilkan">
        </div>
      </div>
      <!--<span class="pull-right">
      <?php //echo anchor(site_url('reportpenjualan/downloadExcel').'?tglawal='.$pawal.'&tglakhir='.$pakhir.'&idcabang='.$this->uri->segment(5), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
      <!--<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>-->
    <!--</span>-->
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th width="15%">NO. Invoice</th>
              <th>Customer</th>
              <th>Salesman</th>
              <th>Tgl Invoice</th>
              <th>Umur Piutang</th>
              <th>Total Jual</th>
              <th>Piutang</th>
              <th width="10%">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><center><?php echo $vr->no_invoice?></center></td>
          <td><?php echo $vr->nm_customer?></td>
          <td><?php echo $vr->nm_salesman?></td>
          <td><center><?php echo date('d M Y',strtotime($vr->tanggal_invoice))?></center></td>
          <td><center><?php echo selisih_hari($vr->tanggal_invoice,date('Y-m-d')).' hari'?></center></td>
          <td class="text-right"><?php echo formatnomor($vr->hargajualtotal)?></td>
          <td class="text-right"><?php echo formatnomor($vr->piutang)?></td>
          <td>
            <center>
              <a href="#dialog-pembayaran" title="Pembayaran" data-toggle="modal" class="btn sm-primary" onclick="pembayaran('<?php echo $vr->no_invoice?>')">
                <span class="glyphicon glyphicon-file"></span>
              </a>
              <a href="#dialog-popup" data-toggle="modal" class="btn sm-primary" onclick="PreviewPdf('<?php echo $vr->no_invoice?>')">
                <span class="glyphicon glyphicon-print"></span>
              </a>
            </center>
          </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        </table>
  </div>
  <!-- /.box-body -->
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="MyModalBody">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-pembayaran" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabelbayar">Pembayaran Piutang</h4>
      </div>
      <div class="modal-body" id="MyModalBodyPembayaran" style="background: #FFF !important;color:#000 !important;">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="simpanpembayaran()">
        <span class="glyphicon glyphicon-save"></span>  Simpan Data</button>
      </div>
    </div>
  </div>
</div>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
  $(document).ready(function(){
    $("#submit").on('click', function(){
      var cabang = $("#filtercabang").val();
      window.location.href = siteurl+"pembayaranpiutang/filter/"+cabang;
    });
    /*
    $('#dialog-pembayaran').on('shown.bs.modal',function(){
        
    });
    */
  });

  $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

  function PreviewPdf(no_inv)
  {
    tujuan = 'pembayaranpiutang/print_request/'+no_inv;
    $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }
  function pembayaran(no_inv){
    var url = siteurl+'pembayaranpiutang/setpembayaran';
    $.post(url,{'NO_INV':no_inv},function(result){
      $('#MyModalBodyPembayaran').html(result);
    });
  }
  function simpanpembayaran(){
    var no_inv = $('#no_invoice').val();
    var formdata = $('#form-pembayaran-piutang').serialize();
    //console.log(formdata);
    $.ajax({
      url: siteurl+"pembayaranpiutang/simpanpembayaran",
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
          pembayaran(result.no_inv);
          setTimeout(function(){
            window.location.href=siteurl+'pembayaranpiutang';
          },3000);
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
