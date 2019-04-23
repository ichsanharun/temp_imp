 <table class="table table-bordered" width="100%" id="list_item_stok">
  <thead>
	  <tr>
		  <th width="75%">Produk Set</th>
		  <th width="10%">Stok Avl</th>
		  <th width="10%">Stok Real</th>
		  <th width="2%" class="text-center">Aksi</th>
	  </tr>
  </thead>
  <tbody>
	  <?php				  
	  if($rows_data){
		foreach($rows_data as $ks=>$vs){
	  ?>
			  <tr>
				  <td><?php echo $vs->id_barang.', '.$vs->nm_barang?></td>
				  <td><center><?php echo $vs->qty_avl?></center></td>
				  <td><center><?php echo $vs->qty_stock?></center></td>
				  <td>
					<center>
						<button id="btn-<?php echo $vs->id_barang?>" class="btn btn-warning btn-sm" type="button" onclick="startmutasi('<?php echo $vs->id_barang?>','<?php echo $vs->nm_barang?>','<?php echo $vs->qty_avl?>','<?php echo $vs->qty_stock?>')">
							Pilih
						</button>
					</center>
				  </td>
			  </tr>
	  <?php 
			}
		  }
	  
	  ?>
  </tbody>
</table>