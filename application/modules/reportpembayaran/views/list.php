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
                foreach(@$cabang as $k=>$v){
                  $selected = '';
                  $session = $this->session->userdata('app_session');
                  $kdcab = $session['kdcab'];
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
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <select class="form-control input-sm" id="filterbulan">
                <option value="">Pilih Bulan</option>
                <option value="All">All</option>
                <?php
                foreach(bulan() as $kb=>$vb){
                  $selectedbln = '';
                  if($this->uri->segment(4) == $kb){
                    $selectedbln='selected="selected"';
                  }
                ?>
                <option value="<?php echo $kb?>" <?php echo $selectedbln?>><?php echo $vb?></option>
                <?php } ?>
              </select>
              <span class="input-group-addon">Tahun</span>
              <?php
              $filter_th = date('Y');
              if($this->uri->segment(5) != ""){
                $filter_th = $this->uri->segment(5);
              }
              ?>
              <input value="<?php echo $filter_th?>" type="text" name="filtertahun" id="filtertahun" class="form-control input-sm" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
          </div>
          <input type="button" id="submit" class="btn btn-sm btn-warning" value="Tampilkan">
        </div>
      </div>
      <span class="pull-right">
      <?php echo anchor(site_url('reportpembayaran/downloadExcel_old').'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5),'<i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
      <!--<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>-->
      <?php if ($this->uri->segment(2) == 'get_filter') { ?>
      <a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Kembali Ke Kartu Piutang" onclick="window.location.href=siteurl+'report_kartupiutang'"><i class="fa fa-arrow-circle-left">&nbsp;</i>Kembali</a>
      <?php } ?>
    </span>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th width="15%">No. Pembayaran</th>
              <th>No. Invoice</th>
              <th>Customer</th>
              <th>Jenis Bayar</th>
              <th>Tanggal Pembayaran</th>
              <th>Status</th>
              <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
          if ($vr->jenis_reff == 'BG') {
            $jenis = 'GIRO';
            $class = 'badge bg-blue';
          }elseif ($vr->jenis_reff == 'TRANSFER') {
            $jenis = 'TRANSFER';
            $class = 'badge bg-orange';
          }elseif ($vr->jenis_reff == 'CASH') {
            $jenis = 'CASH';
            $class = 'badge bg-green';
          }

          if ($vr->status_bayar != 'LUNAS') {
            $status = 'BELUM LUNAS BAYAR';
            $cl = 'badge bg-red';
          }else {
            $status = 'LUNAS';
            $cl = 'badge bg-green';
          }
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><center><?php echo $vr->kd_pembayaran?></center></td>
          <td><?php echo $vr->no_invoice?></td>
          <td><?php echo $vr->nm_customer?></td>
          <td><center><span class="<?=$class?>"><?php echo $jenis?></span></center></td>
          <td><?php echo date("d M Y",strtotime($vr->tgl_pembayaran))?></td>
          <td><center><span class="<?=$cl?>"><?php echo $status?></span></center></td>
          <td class="text-right"><?php echo formatnomor($vr->jumlah_pembayaran)?></td>
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
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
  $(document).ready(function(){
    $("#submit").on('click', function(){
      var cabang = $("#filtercabang").val();
      var bln = $('#filterbulan').val();
      var thn = $('#filtertahun').val();
      if(cabang == "" || bln == "" || thn == ""){
        swal({
          title: "Peringatan!",
          text: "Filter Cabang, Bulan dan Tahun harus diisi",
          type: "warning",
          //timer: 2000,
          showConfirmButton: true
        });
      }else{
        window.location.href = siteurl+"reportpembayaran/filter/"+cabang+"/"+bln+"/"+thn;
      }
    });
  });

  $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

  function PreviewPdf(no_inv)
  {
    tujuan = 'reportar/print_request/'+no_inv;
    $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }

</script>
