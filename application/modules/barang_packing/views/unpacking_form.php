<?php
    $ENABLE_ADD     = has_permission('Setup_stock.Add');
    $ENABLE_MANAGE  = has_permission('Setup_stock.Manage');
    $ENABLE_VIEW    = has_permission('Setup_stock.View');
    $ENABLE_DELETE  = has_permission('Setup_stock.Delete');
?>
<style type="text/css">
thead input {
	width: 100%;
}
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">

<div class="box">
	<div class="box-header">
		<?php
      if (condition) {
        // code...
      }
     ?>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
    <form id="form-unpacking" method="post">
		<table id="example1" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th width="5">#</th>
			<th>Kode Produk</th>
			<th>Nama Set</th>
			<th>Jenis Produk</th>
			<th>Satuan</th>
			<th>Qty Stock</th>
			<th>Qty Available</th>
			<th>Qty Rusak</th>
			<th>Landed Cost</th>
			<th>Harga</th>
			<th>Status</th>
			<th width="50">Qty Unpacking</th>
		</tr>
		</thead>

		<tbody>
		<?php
		//print_r($unpack);
		if(empty($unpack)){
		}else{
			$numb=0; foreach($unpack AS $record){ $numb++; ?>
		<tr>
			<?php
				if($record->satuan==''){
					$satuan = $record->setpcs;
				}else{
					$satuan = $record->satuan;
				}
			?>
		    <td><?= $numb; ?></td>
	        <td>
            <?= $record->id_barang ?>
            <input type="hidden" name="id_barang" id="id_barang" class="form-control input-sm" onkeyup="this.value = this.value.match(/^[0-9]+$/)" value="<?= $record->id_barang ?>">
          </td>
			<td><?= $record->nm_barang ?></td>
			<td><?= strtoupper($record->jenis) ?></td>
			<td><?= $satuan ?></td>
			<td><?= $record->qty_stock ?></td>
			<td><?= $record->qty_avl ?></td>
			<td><?= $record->qty_rusak ?></td>
			<td><?= number_format($record->landed_cost) ?></td>
			<td><?= formatnomor($record->harga_stock) ?></td>
			<td>
				<?php if($record->sts_aktif == 'aktif'){ ?>
					<label class="label label-success">Aktif</label>
				<?php }else{ ?>
					<label class="label label-danger">Non Aktif</label>
				<?php } ?>
			</td>
			<td style="padding-left:20px">
			  <input type="text" name="qty_unpacking" id="qty_unpacking" class="form-control input-sm" onkeyup="this.value = this.value.match(/^[0-9]+$/)">
			</td>
		</tr>
		<?php } }  ?>
		</tbody>


		</table>
    </form>
	</div>
	<!-- /.box-body -->
</div>




<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">




</script>
