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
              <?php
              $dis ='';
              $cab ='';
              if ($this->auth->user_cab() != "100") {
                $dis = "disabled";
                $cab = $this->auth->user_cab();
              } ?>
              <select class="form-control input-sm" id="filtercabang" <?=$dis?>>
                <option value="">Pilih Cabang</option>
                <?php
                foreach(@$cabang as $k=>$v){
                  $selected = '';
                  if($this->auth->user_cab() == $v->kdcab){
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
        <!--
      <?php echo anchor(site_url('reportpembelian/downloadExcel').'?tglawal='.$pawal.'&tglakhir='.$pakhir.'&idcabang='.$cab, ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
      <!<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>-->
      <a data-toggle="modal" href="#dialog-rekap" class="btn btn-primary btn-sm" title="Excel"><i class="fa fa-download">&nbsp;</i>Excel  </a>
    </span>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th width="15%">NO. PO</th>
              <th width="15%">Tgl PO</th>
              <th>Supplier</th>
              <th>Cabang</th>
              <th>Total PO</th>
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
          <td><center><?php echo $vr->no_po?></center></td>
          <td><center><?php echo date('d M Y',strtotime($vr->tgl_po))?></center></td>
          <td><?php echo $vr->id_supplier.', '.$vr->nm_supplier?></td>
          <td><?php echo $vr->kdcab.', '.$vr->nm_cabang?></td>
          <td class="text-right"><?php echo formatnomor($vr->total_po)?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        </table>
  </div>
  <!-- /.box-body -->
</div>

<div class="modal modal-primary" id="dialog-rekap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="repso">
				<div class="form-horizontal">
				    <div class="box-body" style="border:solid 1px #fff;">
				            <div class="col-sm-12">
											<div class="row">
												<div class="form-horizontal">
													<div class="col-sm-6">
									          <div class="input-group ">
									              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									              <input type="text" id="periode_awal_ex" name="periode_awal_ex" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Awal Pencarian" value="<?php echo $pawal?>">
									          </div>
													</div>
								        </div>
												<div class="form-horizontal">
														<div class="col-sm-6">
										          <div class="input-group ">
                              <span class="input-group-addon">S.d</span>
                              <input type="text" id="periode_akhir_ex" name="periode_akhir_ex" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Akhir Pencarian" value="<?php echo $pakhir?>">
										          </div>
														</div>
									      </div>
											</div>
										</div>
        		</div>
				</div>
			</div>
			<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="proses_ex()">
        <span class="glyphicon glyphicon-save"></span> Export Excel</button>
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
      window.location.href = siteurl+"reportpembelian/filter/"+pawal+"/"+pakhir+"/"+cabang;
    });
  });

  $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

    function proses_ex()
  	{
  		var pawal = $("#periode_awal_ex").val();
  		var pakhir = $("#periode_akhir_ex").val();
  		//var fb = $('#filterby_ex').val();
  		window.location.href = siteurl+'reportpembelian/downloadExcel/'+pawal+'/'+pakhir;

  		//	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  	}

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
