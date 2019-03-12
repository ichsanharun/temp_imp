<?php
//print_r(@$results);
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
              <input type="text" id="periode_awal" name="periode_awal" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Awal Pencarian" value="<?php echo $pawal?>">
          </div>
          s.d
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" id="periode_akhir" name="periode_akhir" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Akhir Pencarian" value="<?php echo $pakhir?>">
          </div>
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-share"></i></span>
              <select class="form-control input-md" id="filterby" onchange="filterby()">
                <option value="">Pilih Filter</option>
                <?php
                  foreach(is_filter_report_jual() as $kf=>$vf){
                    $selected ='';
                    if($kf == $this->input->get('filter')){
                      $selected = 'selected="selected"';
                    }
                ?>
                <option value="<?php echo $kf?>" <?php echo $selected?>><?php echo $vf?></option>
                <?php } ?>
              </select>
          </div>
          <div class="input-group" id="div-filter-by"></div>
          <input type="button" id="submit" class="btn btn-md btn-warning" value="Tampilkan">
        </div>
      </div>
    </div>
  </div>
	<!-- /.box-header -->
  <div class="col-sm-12" style="padding-bottom: 20px;">
    <span class="pull-right">
      <?php echo anchor(site_url('reportpenjualan/downloadExcel_old/').$this->uri->segment(3).'/'.$this->uri->segment(4).'?filter='.$this->input->get('filter').'&param='.$this->input->get('param'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary"'); ?>
        <!--a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a-->
    </span>
  </div>
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
        $total=0;
				if(@$results){
					$nos = '';
					$jns = '';
					$cjns = 0;
					$arr_nama = array();
					$tc = count($results);
					$exe ='';
				foreach(@$results as $kr=>$vr){
					$so = $this->Salesorder_model->select('no_so')->cek_data(array('no_do'=>$vr->no_do),'trans_do_detail');
					$no = $n++;
					$total += $vr->hargajualtotal;
					if ($jns != $vr->nm_jenis) {
						//$exe .= 'PENJUALAN '.$jns.' = '.($cjns*100/$tc).'%<br>';
						$cjns = 0;
					}
				?>
				<tr>
					<td><center><?php echo $no?></center></td>


					<td><center><?php echo $vr->no_invoice?></center></td>
					<td><center><?php echo $so->no_so?></center></td>
					<td><?php echo $vr->nm_customer?></td>
					<td><center><?php echo $vr->tanggal_invoice?></center></td>
					<td><?php echo $vr->nm_salesman?></td>
					<td class="text-right"><?php echo $vr->hargajualtotal?></td>

					<td><?= $vr->nm_barang ?> </td>
					<td><?= $vr->nm_jenis ?> </td>
					<td><?= $vr->nm_group ?> </td>
					<td><?= $vr->satuan ?> </td>
					<td><?= $vr->jumlah ?> </td>

					<td><?= $vr->hargajual ?> </td>
					<td><?= $vr->persen_diskon_stdr ?> </td>
					<td><?= $vr->harga_after_diskon_stdr ?> </td>
					<td><?= $vr->diskon_promo_persen ?> </td>
					<td><?php echo $vr->diskon_so."(".$vr->tipe_diskon_so.")" ?> </td>
					<td><?= $vr->harga_nett ?> </td>
					<td><?= $vr->ppn ?> </td>
					<td><?= $vr->subtot_after_diskon ?> </td>
			<td class="text-center">
			<?php
				$OK		=1;
				if($vr->flag_cancel == 'N'){
					echo"TIDAK BATAL";
				}else{
					$OK		= 0;
					echo"BATAL";
				}
			?>
				</tr>
				<?php
				if (!empty($vr->no_invoice)) {
					$arr_nama[$vr->nm_jenis]['penjualan'] += $vr->jumlah;
					$arr_nama[$vr->nm_jenis]['hpp'] += $vr->hargalanded*$vr->jumlah;
				}else {
					$arr_nama[$vr->nm_jenis]['penjualan'] += 0;
					$arr_nama[$vr->nm_jenis]['hpp'] += 0;
				}
				$exe = 'PENJUALAN '.$jns.' = '.($cjns*100/$tc).'%<br>';
			 } ?>
				<?php } ?>

        </tbody>
        <tfoot>
          <tr>
              <th colspan="5" class="text-right">TOTAL</th>
              <th style="text-align: right;"><?php echo formatnomor($total)?></th>
              <th></th>
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
      var fb = $('#filterby').val();
      var sf = $('#filter-select').val();
      window.location.href = siteurl+"reportpenjualan/filter/"+pawal+"/"+pakhir+"?filter="+fb+"&param="+sf;
    });
    filterby();
    //var param = '<?php echo $this->input->get('param')?>';
    //console.log(param);
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

  function filterby(){
    var fb = $('#filterby').val();
    var param = '<?php echo $this->input->get('param')?>';
    var url = siteurl+'reportpenjualan/getfilterby?param='+param;
    $.post(url,{'FILTER':fb},function(result){
      //console.log(result);
      $('#div-filter-by').html(result);
    });
  }
</script>
