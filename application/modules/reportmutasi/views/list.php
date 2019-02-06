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
              <select class="form-control input-sm" id="filtercabang" style="width: 200px;" disabled="disabled">
                <option value="">Pilih Cabang</option>
                <?php 
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
              <select class="form-control input-sm" id="filterbarang" style="width: 300px;">
                <option value="">Pilih Produk</option>
                <?php 
                foreach(@$barang as $kb=>$vb){ 
                  $selected = '';
                  if($this->uri->segment(4) == $vb->id_barang){
                    $selected='selected="selected"';
                  }
                ?>
                <option value="<?php echo $vb->id_barang?>" <?php echo $selected?>><?php echo $vb->id_barang.', '.$vb->nm_barang?></option>
                <?php } ?>
              </select>
          </div>
          <input type="button" id="submit" class="btn btn-md btn-warning" value="Tampilkan">
          <a class="btn btn-primary btn-md" data-toggle="modal" href="#dialog-rekap" title="PDF" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>CETAK</a>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th>NO. Mutasi</th>
              <th>Tgl Kirim</th>
              <th>Kode Produk</th>
              <th>Nama Produk</th>
              <th>Qty Mutasi</th>
              <th>Qty Terima</th>
              <th>Cabang Asal</th>
              <th>Cabang Tujuan</th>
              <th>Tgl Terima</th>
          </tr>
        </thead>
        <tbody>
          <?php if(@$results){ ?>
            <?php 
            $n = 1;
            foreach(@$results as $kso=>$vso){ 
                $no = $n++;
            ?>
            <tr>
              <td><center><?php echo $no?></center></td>
              <td><?php echo $vso->no_mutasi?></td>
              <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_mutasi))?></td>
              <td><center><?php echo $vso->id_barang?></center></td>
              <td><?php echo $vso->nm_barang?></td>
              <td><center><?php echo $vso->qty_mutasi?></center></td>
              <td><center><?php echo $vso->qty_received?></center></td>
              <td><center><?php echo $vso->cabang_asal?></center></td>
              <td><center><?php echo $vso->cabang_tujuan?></center></td>
              <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->received_on))?></td>
            </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th width="2%">#</th>
              <th>NO. Mutasi</th>
              <th>Tgl Kirim</th>
              <th>Kode Produk</th>
              <th>Nama Produk</th>
              <th>Qty Mutasi</th>
              <th>Qty Terima</th>
              <th>Cabang Asal</th>
              <th>Cabang Tujuan</th>
              <th>Tgl Terima</th>
          </tr>
        </tfoot>
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
    $("#filtercabang,#filterbarang").select2();
    $("#submit").on('click', function(){
      var cabang = $("#filtercabang").val();
      var barang = $("#filterbarang").val();
      window.location.href = siteurl+"reportmutasi/filter/"+cabang+"/"+barang;
    });
    var dataTable = $("#example1").DataTable();
  });

  function PreviewRekap(id)
  {
    var kdcab = '<?php echo $this->uri->segment(3) ?>';
    var barang = '<?php echo $this->uri->segment(4) ?>';
    tujuan = siteurl+'reportmutasi/print_request/'+kdcab+'/'+barang;
    //alert(tujuan);
    $("#MyModalBodyFilter").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }

</script>
