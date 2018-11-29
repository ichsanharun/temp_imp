<form id="form-header-rec-mutasi" method="post">
<input type="hidden" name="no_mutasi" value="<?php echo $header->no_mutasi?>">
<input type="hidden" name="kdcab_asal" value="<?php echo $header->kdcab_asal?>">
<input type="hidden" name="kdcab_tujuan" value="<?php echo $header->kdcab_tujuan?>">
<table class="table table-bordered" width="100%">
	<tr>
		<td width="15%">NO. MUTASI</td>
		<td width="1%">:</td>
		<td colspan="2"><b><?php echo $header->no_mutasi?></b></td>
		<td width="15%">TGL MUTASI</td>
		<td width="1%">:</td>
		<td colspan="2"><?php echo date('d M Y',strtotime($header->tgl_mutasi)) ?></td>
	</tr>
	<tr>
		<td width="15%">CABANG ASAL</td>
		<td width="1%">:</td>
		<td colspan="2"><?php echo strtoupper(@$header->kdcab_asal.', '.@$header->cabang_asal)?></td>
		<td width="10%">NAMA SUPIR</td>
		<td width="1%">:</td>
		<td><?php echo $header->nm_supir?></td>
	</tr>
	<tr>
		<td width="15%">CABANG TUJUAN</td>
		<td width="1%">:</td>
		<td colspan="2"><?php echo strtoupper(@$header->kdcab_tujuan.', '.@$header->cabang_tujuan)?></td>
		<td width="10%">KENDARAAN</td>
		<td width="1%">:</td>
		<td><?php echo $header->ket_kendaraan?></td>
	</tr>
</table>
</form>
<form id="form-detail-rec-mutasi" method="post">
<table class="table table-bordered" width="100%">
	<tr class="bg-blue">
		<th colspan="5"><center><b>DETAIL MUTASI PRODUK</b></center></th>
	</tr>
	<tr class="bg-blue">
		<th width="2%"><center>NO</center></th>
		<th width="15%">KODE PRODUK</th>
		<th>PRODUK SET</th>
		<th width="15%"><center>QTY MUTASI</center></th>
		<th width="15%"><center>QTY RECEIVED</center></th>
	</tr>
	<?php 
		$n=1;
		foreach(@$detail as $krm=>$vrm){
			$no=$n++;
	?>
	<tr>
		<td><center><?php echo $no?></center></td>
		<td>
			<center>
			<?php echo $vrm->id_barang?>
			<input type="hidden" value="<?php echo $vrm->id_barang?>" name="id_barang_rec_mutasi[]" id="id_barang_rec_mutasi" style="text-align: center;" class="form-control input-sm">	
			</center>
		</td>
		<td><?php echo $vrm->nm_barang?></td>
		<td>
			<center>
			<input type="text" name="qty_mutasi[]" id="qty_mutasi_<?php echo $no?>" style="text-align: center;" class="form-control input-sm" value="<?php echo $vrm->qty_mutasi?>" readonly>
			</center>
		</td>
		<td>
			<input type="text" name="qty_rec_mutasi[]" id="qty_rec_mutasi_<?php echo $no?>" style="text-align: center;" class="form-control input-sm" onkeyup="cek_rec_mutasi('<?php echo $no?>')">
			<input type="hidden" name="sts_rec_mutasi[]" id="sts_rec_mutasi_<?php echo $no?>" style="text-align: center;" class="form-control input-sm">
		</td>
	</tr>
	<?php } ?>
</table>
</form>
