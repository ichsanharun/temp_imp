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
    <div class="box-header text-left"><b>Pilih Periode : </b>
      <?php
      if (!empty($this->uri->segment(3)) AND !empty($this->uri->segment(4))) {
        $pawal = $this->uri->segment(3);
        $pakhir = $this->uri->segment(4);
      }
      else {
        $pawal = "";
        $pakhir = "";
      }
       ?>
      <div class="form-inline">
        <div class="form-group">
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" id="periode_awal" name="periode_awal" class="form-control input-sm datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Awal Pencarian" value="<?php echo $pawal?>">
          </div>
          s.d
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" id="periode_akhir" name="periode_akhir" class="form-control input-sm datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Akhir Pencarian" value="<?php echo $pakhir?>">
          </div>
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-share"></i></span>
              <select class="form-control input-sm" id="filtercabang">
                <option value="">Pilih Cabang</option>
                <?php 
                foreach(@$cabang as $k=>$v){ 
                  $selected = '';
                  if($this->uri->segment(5) == $v->kdcab){
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
      <span class="pull-right">
      <?php // echo anchor(site_url('reportpenjualan/downloadExcel').'?tglawal='.$pawal.'&tglakhir='.$pakhir.'&idcabang='.$this->uri->segment(5), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
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
              <th>Tgl Invoice</th>
              <th>Customer</th>
              <th>Salesman</th>
              <th>Produk Set</th>
              <th>Jumlah</th>
              <th>Harga</th>
              <th>Diskon</th>
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
          <td><center><?php echo date('d M Y',strtotime($vr->tanggal_invoice))?></center></td>
          <td><?php echo $vr->nm_customer?></td>
          <td><?php echo $vr->nm_salesman?></td>
          <td><?php echo $vr->id_barang.', '.$vr->nm_barang?></td>
          <td class="text-center"><?php echo $vr->jumlah.' '.$vr->satuan?></td>
          <td class="text-right"><?php echo formatnomor($vr->hargajual)?></td>
          <td class="text-right"><?php echo formatnomor($vr->diskon)?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        </table>
  </div>
  <!-- /.box-body -->
</div>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
  $(document).ready(function(){
    $(".datepicker").datepicker({
        todayHighlight: true,
        format : "yyyy-mm-dd",
        showInputs: true,
        autoclose:true
    });
    $("#submit").on('click', function(){
      var pawal = $("#periode_awal").val();
      var pakhir = $("#periode_akhir").val();
      var cabang = $("#filtercabang").val();
      window.location.href = siteurl+"reportdetailpenjualan/filter/"+pawal+"/"+pakhir+"/"+cabang;
    });
  });

  $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

  function PreviewPdf(id)
  {
    param=id;
    tujuan = 'setup_stock/print_request/'+param;

    $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
  }

  function PreviewRekap()
  {
    tujuan = 'setup_stock/print_rekap';
    $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }

</script>
