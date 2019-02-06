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
              <select class="form-control input-sm" id="filtercabang">
                <option value="">Pilih Cabang</option>
                <?php 
                foreach(@$cabang as $k=>$v){ 
                  $selected = '';
                  if($this->uri->segment(3) == $v->kdcab){
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
          <td><center><?php echo selisih_hari($vr->tanggal_invoice,date('Y-m-d'))?></center></td>
          <td class="text-right"><?php echo formatnomor($vr->hargajualtotal)?></td>
          <td class="text-right"><?php echo formatnomor($vr->piutang)?></td>
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
      window.location.href = siteurl+"reportpiutang/filter/"+cabang;
    });
  });

  $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

  function PreviewPdf(no_inv)
  {
    tujuan = 'reportpiutang/print_request/'+no_inv;
    $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }

</script>
