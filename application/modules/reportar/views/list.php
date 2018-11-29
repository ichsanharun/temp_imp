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
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <select class="form-control input-sm" id="filterbulan">
                <option value="">Pilih Bulan</option>
                <?php 
                foreach(the_bulan() as $kb=>$vb){ 
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
      <?php echo anchor(site_url('reportar/downloadExcel').'?idcabang='.$this->uri->segment(3),'<i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
      <!--<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>-->
    </span>
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
              <th>Bulan</th>
              <th>Tahun</th>
              <th>Saldo Awal</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Saldo Akhir</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
          $debet=0;
          if($vr->debet != 0){
              $debet=formatnomor($vr->debet);
          }
          $kredit=0;
          if($vr->kredit != 0){
              $kredit=formatnomor($vr->kredit);
          }
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><center><?php echo $vr->no_invoice?></center></td>
          <td><?php echo $vr->customer_code.', '.$vr->customer?></td>
          <td><?php echo the_bulan($vr->bln)?></td>
          <td><?php echo $vr->thn?></td>
          <td class="text-right"><?php echo formatnomor($vr->saldo_awal)?></td>
          <td class="text-right"><?php echo $debet?></td>
          <td class="text-right"><?php echo $kredit?></td>
          <td class="text-right"><?php echo formatnomor($vr->saldo_akhir)?></td>
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
        window.location.href = siteurl+"reportar/filter/"+cabang+"/"+bln+"/"+thn;
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
