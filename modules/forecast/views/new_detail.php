<?php echo form_open('forecast/', array('id'=>'FormForecast')); ?>
<table id="my-grid" class="table table-bordered table-striped table-condensed" style="width: 100%">
<thead>
	<tr>
		<th rowspan="2" class="no-sort" width="5" style="vertical-align: middle;">#</th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;">Cabang</th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;">Produk</th>
		<th colspan="4" class="no-sort"><center>Qty Demand</center></th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;">
			Forecast<br>
			<?php
			$fc_last = "01-".$periode;
			$fc_last = date('F', strtotime("-1 Month", strtotime($fc_last)));
			echo $fc_last;
			?>
		</th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;">Set Forecast<br><?php echo date('F');?></th>
		<th colspan="3" class="no-sort"><center>Waktu (Month)</center></th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;text-align: center;">Qty Safety Stock</th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;text-align: center;">Point (Month)</th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;text-align: center;">Qty Reorder Point</th>
		<th rowspan="2" class="no-sort" style="vertical-align: middle;text-align: center;">Qty Stock</th>
	</tr>
	<tr>
		<?php
		for($m=4; $m>=1; $m--)
		{
			$fc_last_month = "01-".$periode;
			$fc_last_month = date('F', strtotime("-".$m." Month", strtotime($fc_last_month)));
			echo "<th class='no-sort'>M-".$m."<br>".$fc_last_month."</th>";
		}
		?>
		<th class="no-sort">Waktu Produksi</th>
		<th class="no-sort">Waktu Pengiriman</th>
		<th class="no-sort">Safety Stock</th>
	</tr>
</thead>
<tbody>
	<?php
	if($forecast->num_rows() > 0)
	{
	$no = 1;
	foreach($forecast->result() as $f)
	{
		?>
		<tr>
		<td><?php echo $no; ?></td>
		<td><?php echo $f->kdcab; ?></td>
		<td style="white-space: nowrap;">
			<?php echo $f->nm_barang; ?>
			<input type="hidden" name="id_barang[]" value="<?php echo $f->id_barang; ?>">
			<input type="hidden" name="nm_barang[]" value="<?php echo $f->nm_barang; ?>">
		</td>
		<?php
		for($m=4; $m>=1; $m--)
		{
			$last_month = "01-".$periode;
			$last_month = date('Y-m', strtotime('-'.$m.' Month', strtotime($last_month)));
			$count_prd  = "
				SELECT 
					COUNT(b.id_barang) AS id_barang
				FROM 
					trans_so_header AS a 
					LEFT JOIN trans_so_detail AS b ON a.no_so = b.no_so 
					INNER JOIN barang_stock AS c ON b.id_barang = c.id_barang
				WHERE 1=1
					AND a.tanggal LIKE '".$last_month."%'
					AND c.kdcab = '".$f->kdcab."'
					AND b.id_barang = '".$f->id_barang."'
				";
			$count_prd = $this->db->query($count_prd)->row();
			echo "
				<td>
					".$count_prd->id_barang."
					<input type='hidden' name='qty_demand_m".$m."[]' value='".$count_prd->id_barang."'>
				</td>
			";
		}
		?>
		<td>
			<?php
			$last_forecast = "
				SELECT
					b.set_forecast 
				FROM 
					forecast_header AS a 
					LEFT JOIN forecast_detail AS b ON a.fc_id = b.fc_id_header
				WHERE
					a.fc_date = '".date('Y-m', strtotime("-1 Month", strtotime(date('Y-m-01'))))."'
					AND b.id_barang = '".$f->id_barang."'
				LIMIT 1
				";
			$last_forecast = $this->db->query($last_forecast)->row();
			echo @$last_forecast->set_forecast;
			?>
			<input type="hidden" name="forecast_m1[]" value="<?php echo @$last_forecast->set_forecast; ?>">
		</td>
		<td>
			<?php 
			echo form_input(array(
				'name' 	=> "set_forecast[]",
				'id' 	=> "set_forecast",
				'class' => "form-control input-sm",
				'size' 	=> "9",
				'onkeydown' => 'return check_int(this, event);',
				'maxlength' => '4',
				'value' => ''
			));
			?>
		</td>
		<td>
			<!-- <?php echo $f->wkt_produksi; ?> -->
			<?php 
			echo form_input(array(
				'name' 	=> "wkt_produksi[]",
				'id' 	=> "wkt_produksi",
				'class' => "form-control input-sm",
				'size' 	=> "4",
				'maxlength' => '4'
			));
			?>
		</td>
		<td>
			<!-- <?php echo $f->wkt_pengiriman; ?> -->
			<?php 
			echo form_input(array(
				'name' 	=> "wkt_pengiriman[]",
				'id' 	=> "wkt_pengiriman",
				'class' => "form-control input-sm",
				'size' 	=> "4",
				'maxlength' => '4'
			));
			?>
		</td>
		<td>
			<!-- <span id="SafetyStok"><?php echo $f->safety_stock; ?></span> -->
			<?php 
			echo form_input(array(
				'name' 	=> "safety_stock[]",
				'id' 	=> "safety_stock",
				'class' => "form-control input-sm",
				'size' 	=> "4",
				'maxlength' => '4'
			));
			?>
		</td>
		<td>
			<?php 
			echo form_input(array(
				'name' 	=> "qty_safety_stock[]",
				'id' 	=> "qty_safety_stock",
				'class' => "form-control input-sm",
				'size' 	=> "4",
				'maxlength' => '4',
				'readonly' => true
			));
			?>
		</td>
		<td>
			<?php 
			echo form_input(array(
				'name' 	=> "wkt_order_point[]",
				'id' 	=> "wkt_order_point",
				'class' => "form-control input-sm",
				'size' 	=> "4",
				'maxlength' => '4',
				'readonly' => true,
				'data-toggle' =>'tooltip',
				'data-placement' =>'top',
				'title' =>'(Waktu Produksi+Waktu Pengiriman+Safety Stok)'
			));
			?>
		</td>
		<td>
			<?php 
			echo form_input(array(
				'name' 	=> "qty_reorder_point[]",
				'id' 	=> "qty_reorder_point",
				'class' => "form-control input-sm",
				'size' 	=> "4",
				'maxlength' => '4',
				'readonly' => true,
				'data-toggle' =>'tooltip',
				'data-placement' =>'top',
				'title' =>'(Point (Month) * Set Forecast)'
			));
			?>
		</td>
		<td>
			<?php echo $f->qty_stock; ?>
			<input type="hidden" name="qty_stock[]" value="<?php echo $f->qty_stock; ?>">
		</td>
		</tr>
		<?php
		$no++;
	}
	}
	?>
</tbody>
</table>

<div class="box-header pull-right">
	&nbsp;<a href="<?php echo site_url('forecast'); ?>" class="btn btn-primary"><i class="fa fa-backward"></i>&nbsp; Kembali</a>
	&nbsp;
	<a href="#" class="btn btn-info" id="SaveForecast">
		Simpan &nbsp;<i class="fa fa-forward"></i>
	</a>
</div>
<?php echo form_close(); ?>
<?php
// echo "<pre>";print_r($this->session->userdata);
// echo "<br>";print_r($this->session->userdata('app_session')['nm_lengkap']);
?>
<script type="text/javascript">
	$(document).ready(function(){
		// $('body').addClass('sidebar-collapse');
		var dataTable = $('#my-grid').DataTable({
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
	});

	// input decimal number
	$("#wkt_produksi, #wkt_pengiriman, #safety_stock").keydown(function(event){
        if (event.shiftKey == true) {
            event.preventDefault();
        }
        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) 
        {

    	}else{
            event.preventDefault();
		}
        
        if($(this).val().indexOf('.') !== -1 && event.keyCode == 190){
            event.preventDefault();
        }
    });
</script>
<style type="text/css">
    .form-control[readonly]{
    	background: #f9f7f7 !important;
    	cursor: not-allowed;
    }
</style>