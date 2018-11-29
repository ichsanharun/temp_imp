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
              <select class="form-control input-sm" id="filtercabang" style="width: 200px;">
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
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <select class="form-control input-sm" id="filtersales" style="width: 200px;">
                <option value="">Pilih Sales</option>
                <?php 
                foreach(@$marketing as $km=>$vm){ 
                  $selected = '';
                  if($this->uri->segment(4) == $vm->id_karyawan){
                    $selected='selected="selected"';
                  }
                ?>
                <option value="<?php echo $vm->id_karyawan?>" <?php echo $selected?>><?php echo $vm->nama_karyawan?></option>
                <?php } ?>
              </select>
          </div>
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-users"></i></span>
              <select class="form-control input-sm" id="filtercustomer" style="width: 300px;">
                <option value="">Pilih Customer</option>
                <?php 
                foreach(@$customer as $kc=>$vc){ 
                  $selected = '';
                  if($this->uri->segment(5) == $vc->id_customer){
                    $selected='selected="selected"';
                  }
                ?>
                <option value="<?php echo $vc->id_customer?>" <?php echo $selected?>><?php echo $vc->id_customer.', '.$vc->nm_customer?></option>
                <?php } ?>
              </select>
          </div>
          <input type="button" id="submit" class="btn btn-md btn-warning" value="Tampilkan">
          <a class="btn btn-primary btn-md" data-toggle="modal" href="#dialog-rekap" title="PDF" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>CETAK</a>
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
    <table id="example1" class="table table-bordered table-striped table-condensed" width="100%">
        <thead>
          <tr>
              <th width="2%" rowspan="2">#</th>
              <th width="12%" rowspan="2">NO. Invoice</th>
              <th rowspan="2">Customer</th>
              <th rowspan="2">Salesman</th>
              <th rowspan="2">Tgl Invoice</th>
              <th rowspan="2">Umur Piutang</th>
              <th rowspan="2">Total Jual</th>
              <th rowspan="2">Piutang</th>
              <th colspan="5" class="text-center">Kategori Range Umur Piutang (Hari)</th>
          </tr>
          <tr>
              <?php
              foreach(kategori_umur_piutang() as $c=>$b){
              ?>
              <th><center><?php echo $b?></center></th>
              <?php } ?>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
          $ar = $this->Invoice_model->cek_data(array('no_invoice'=>$vr->no_invoice),'ar');
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
          <?php
          foreach(kategori_umur_piutang() as $cd=>$bd){
            $ex = explode('|',$cd);
            $um = selisih_hari($vr->tanggal_invoice,date('Y-m-d'));
            $ku = '-';
            if($ex[0] != 90){
              if($um >= $ex[0] && $um <= $ex[1]){
                //$ku = '<span class="fa fa-check"></span>';
                $ku = '<span class="badge bg-green" title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Saldo Akhir">'.formatnomor($ar->saldo_akhir).'</span>';
              }
            }else{
              if($um >= $ex[0]){
                //$ku = '<span class="fa fa-check"></span>';
                $ku = '<span class="badge bg-green" title="" data-toggle="tooltip" data-placement="bottom" data-original-title="Saldo Akhir">'.formatnomor($ar->saldo_akhir).'</span>';
              }
            }
          ?>
          <td><center><?php echo $ku?></center></td>
          <?php } ?>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        </table>
  </div>
  <!-- /.box-body -->
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-rekap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="MyModalBodyFilter">
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
    $("#filtercabang,#filtersales,#filtercustomer").select2();
    $("#submit").on('click', function(){
      var cabang = $("#filtercabang").val();
      var sales = $("#filtersales").val();
      var customer = $("#filtercustomer").val();
      window.location.href = siteurl+"reportpiutang/filter/"+cabang+"/"+sales+"/"+customer;
    });
  });

  $(function() {
    var dataTable = $('#example1').DataTable({
          "serverSide": false,
          "stateSave" : false,
          "bAutoWidth": true,
          "searching": false,
          "bLengthChange" : false,
          "bPaginate": false,
          "aaSorting": [[ 0, "asc" ]],
          "columnDefs": [ 
              {"aTargets":[0], "sClass" : "column-hide"},
              {"aTargets": 'no-sort', "orderable": false}
          ],
          "sPaginationType": "simple_numbers", 
          "iDisplayLength": 10,
          "aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]]
      });
    /*
      var dataTable = $("#example1").DataTable(
        "bAutoWidth": true
        ).draw();
        */
    });

  function PreviewRekap()
  {
    var kdcab = '<?php echo $this->uri->segment(3) ?>';
    var sales = '<?php echo $this->uri->segment(4) ?>';
    var customer = '<?php echo $this->uri->segment(5) ?>';
    tujuan = siteurl+'reportpiutang/print_request/'+kdcab+'/'+sales+'/'+customer;
    //alert(tujuan);
    $("#MyModalBodyFilter").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }

</script>
