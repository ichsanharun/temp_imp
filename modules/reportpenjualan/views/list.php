<?php
print_r(@$results);
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
          <input type="button" id="submit" class="btn btn-sm btn-warning" value="Cari">
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
              <th width="15%">NO. Invoice</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Total</th>
			        <th>Status</th>
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
          <td><center><?php echo date('d M Y',strtotime($vr->tanggal_invoice))?></center></td>
          <td><center><?php echo date('d M Y',strtotime($vr->tgljatuhtempo))?></center></td>
          <td><?php echo $vr->nm_salesman?></td>
          <td class="text-right"><?php echo formatnomor($vr->hargajualtotal)?></td>
		  <td class="text-center">
			<?php
				$OK		=1;
				if($vr->flag_cancel == 'N'){
					echo"<span class='badge bg-green'>OPEN</span>";
				}else{
					$OK		= 0;
					echo"<span class='badge bg-red'>BATAL</span>";
				}
			?>
		  </td>
          <td class="text-center">
            <?php if($OK==1){?>
            <a href="#dialog-popup" data-toggle="modal" class="btn bg-primary" onclick="PreviewPdf('<?php echo $vr->no_invoice?>')">
                <span class="glyphicon glyphicon-print"></span>
            </a>
			&nbsp;&nbsp;
			<a href="#" class="btn bg-red" onClick="return batalInvoice('<?php echo $vr->no_invoice?>')"> <i class="fa fa-trash-o"></i></a>
		    
			<?php } ?>
          </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th colspan="5" class="text-right">TOTAL</th>
              <th colspan="2"></th>
          </tr>
        </tfoot>
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
      window.location.href = siteurl+"reportpenjualan/filter/"+pawal+"/"+pakhir;
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
  /*
  function filterby(){
    var fb = $('#filterby').val();
    var url = siteurl+'reportpenjualan/getfilterby';
    $.post(url,{'FILTER':fb},function(result){
      alert(result);
    });
  }
  */
</script>
