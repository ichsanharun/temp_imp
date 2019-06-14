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
	<form action="<?= site_url(strtolower($this->uri->segment(1).'/index'))?>" method="POST" id='form_proses'>
		<div class="col-lg-12">
			<div class="box-header text-left"><b>Pilih Periode : </b>
				<div class="form-inline">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" id="periode_awal" name="periode_awal" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Awal Pencarian" value="<?php echo $periode_awal?>" readOnly>
						</div>
						s.d
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" id="periode_akhir" name="periode_akhir" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Akhir Pencarian" value="<?php echo $periode_akhir?>" readOnly>
						</div>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-share"></i></span>
							<select class="form-control input-md" id="filterby" name="filter_by">
								<option value="">Pilih Filter</option>
								<?php
								  $Arr_Filter	= array('by_customer'=>'Per Customer','by_sales'=>'Per Sales');
								  foreach($Arr_Filter as $key=>$val){
									  $yuup	=($key==$filter_by)?'selected':'';
									  echo"<option value='$key' $yuup>$val</option>";
								  }


								?>
							</select>
						</div>
						<div class="input-group" id="div-filter-by">
						<?php
							if(@$rows_filter){
								echo"<select name='filter_value' id='filter_value' class='form-control input-md'>";
								 echo"<option value=''>Silahkan Pilih</option>";
								foreach($rows_filter as $key=>$vals){
									 $yuup	=($key==$filter_value)?'selected':'';
									echo"<option value='$key' $yuup>$vals</option>";
								}
								echo"</select>";
							}

						?>
						</div>
						<input type="button" id="btn-submit" class="btn btn-md btn-warning" value="Tampilkan">
					</div>
				</div>
			</div>
		</div>
	</form>
	<!-- /.box-header -->
	<!--div class="col-sm-12" style="padding-bottom: 20px;">
		<span class="pull-right">
			<button type="button" id="btn-excel" class="btn btn-md btn-success"><i class="fa fa-download">&nbsp;</i>Excel</button>


		</span>
	</div-->
	<div class="col-sm-12" style="padding-bottom: 20px;">

			<span class="pull-right">

				<a data-toggle="modal" href="#dialog-rekap" class="btn btn-primary btn-sm" title="Excel"><i class="fa fa-download">&nbsp;</i>Excel  </a>
				<!--a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a-->
			</span>

    </span>
  </div>
	<div class="box-body" style="overflow-x:auto">
		<table id="example1" class="table table-bordered table-striped">
			<thead>
				<tr class="bg-blue">
					<th class="text-center">No</th>
					<th class="text-center">No Invoice</th>
					<th class="text-center">Tgl Invoice</th>
					<th class="text-center">Customer</th>
					<th class="text-center">Salesman</th>
					<th class="text-center">DPP</th>
					<th class="text-center">PPN</th>
					<th class="text-center">Materai</th>
					<th class="text-center">Total</th>
				</tr>
        </thead>
        <tbody>
        <?php
				$intL	= 0;
				$Total_DPP	= $Total_PPN	= $Grand_Total	= $Total_Materai = 0;
				if(@$results){
					foreach($results as $key=>$vals){
						$intL++;
						$DPP			= $vals->dpp;
						$PPN			= $vals->ppn;
						$Materai		= $vals->materai;
						$totals			= $vals->hargajualtotal;

						$Total_DPP		+=$DPP;
						$Total_PPN		+=$PPN;
						$Total_Materai	+=$Materai;
						$Grand_Total	+= $totals;
						echo"<tr>";
							echo"<td class='text-center'>".$intL."</td>";
							echo"<td class='text-center'>".$vals->no_invoice."</td>";
							echo"<td class='text-center'>".date('d M Y',strtotime($vals->tanggal_invoice))."</td>";
							echo"<td class='text-left'>".$vals->nm_customer."</td>";
							echo"<td class='text-left'>".$vals->nm_salesman."</td>";
							echo"<td class='text-right'>".number_format($DPP)."</td>";
							echo"<td class='text-right'>".number_format($PPN)."</td>";
							echo"<td class='text-right'>".number_format($Materai)."</td>";
							echo"<td class='text-right'>".number_format($totals)."</td>";
						echo"</tr>";
					}

				}
				echo"</tbody>";
				echo"<tfoot>";
					echo"<tr class='bg-gray text-red'>";
						echo"<td class='text-right' colspan='5'><b>Grand Total</b></td>";
						echo"<td class='text-right'><b>".number_format($Total_DPP)."</b></td>";
						echo"<td class='text-right'><b>".number_format($Total_PPN)."</b></td>";
						echo"<td class='text-right'><b>".number_format($Total_Materai)."</b></td>";
						echo"<td class='text-right'><b>".number_format($Grand_Total)."</b></td>";

					echo"</tr>";
				echo"</tfoot>";
				?>
    </table>
		<?php
		//echo $ket;
		if (!empty($ket)) {

		?>
		<div class="col-sm-12" style="padding-bottom: 20px;">

			<span class="pull-left">

				<a href="javascript:void(0)" class="btn btn-danger btn-lg" title="Kembali ke report" onclick="window.location.href=siteurl+'report_kartupiutang'"><i class="fa fa-arrow-circle-left">&nbsp;</i>Kembali  </a>
				<!--a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a-->
			</span>

  	</div>
		<?php } ?>
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
																<span class="input-group-addon">S.d</span>
																<input type="text" id="periode_akhir_ex" name="periode_akhir_ex" class="form-control input-md datepicker col-md-6" tabindex="-1" required placeholder="Tanggal Akhir Pencarian" value="<?php echo $pakhir?>">
									          </div>
													</div>
								        </div>
												<div class="form-horizontal">
														<div class="col-sm-6">
										          <div class="input-group ">
																<span class="input-group-addon"><i class="fa fa-share"></i></span>

																<select class="form-control input-md" id="filterby_ex">
																	<option value="">Pilih Filter</option>
																	<option value="all">All</option>
																	<option value="by_customer">Per Customer</option>
																	<option value="by_sales">Per Sales</option>

																</select>
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
		var dataTable = $("#example1").DataTable().draw();
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
		$("#btn-submit").on('click', function(){
			$('#form_proses').submit();
		});
		$("#filterby").on('change', function(){
			var filter_by = $('#filterby').val();
			$('#div-filter-by').empty();
			if(filter_by !='' && filter_by !=null){
				$.ajax({
					url			: siteurl+'reportpenjualan/get_filter_data/'+filter_by,
					type		: "GET",
					success		: function(data){
						$('#div-filter-by').html(data);
					},
					error: function() {
						swal({
						  title				: "Error Message !",
						  text				: 'An Error Occured During Process. Please try again..',
						  type				: "warning",
						  timer				: 7000,
						  showCancelButton	: false,
						  showConfirmButton	: false,
						  allowOutsideClick	: false
						});

						return false;
					}
				});
			}
		});

	});
	$('#btn-excel').click(function(){
		var periode_awal	= $('#periode_awal').val();
		var periode_akhir	= $('#periode_akhir').val();
		var filter_by		= $('#filterby').val();
		var filter_value	= $('#filter_value').val();
		if(filter_by=='' || filter_by==null){
			filter_by	= 'ALL';
		}
		if(filter_value=='' || filter_value==null){
			filter_value	= 'ALL';
		}
		var Links		= siteurl+'reportpenjualan/excel_penjualan/'+periode_awal+'/'+periode_akhir+'/'+filter_by+'/'+filter_value;
		//alert(Links);
		window.open(Links,'_blank');
	});


	function proses_ex()
	{
		var pawal = $("#periode_awal_ex").val();
		var pakhir = $("#periode_akhir_ex").val();
		var fb = $('#filterby_ex').val();
		window.location.href = siteurl+'reportpenjualan/downloadExcel_old/'+pawal+'/'+pakhir+'/'+fb;

		//	$(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
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
