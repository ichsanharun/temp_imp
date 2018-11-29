<input type="hidden" name="filterby" value="<?php echo @$filter?>">
<select name="filter-select" class="form-control input-sm">
  <option value="">Silahkan Pilih</option>
  <?php 
  $n=1;
  foreach(@$selectfilter as $k=>$v){ 
    $kk="";
    $vv="";
    if(@$filter == 'by_produk'){
      $kk=$v->id_barang;
      $vv=$v->id_barang.', '.$v->nm_barang;
    }elseif(@$filter == 'by_customer'){
      $kk=$v->id_customer;
      $vv=$v->nm_customer;
    }else{
      $kk=$v->id_karyawan;
      $vv=$v->nama_karyawan;
    }
  ?>
  <option value="<?php echo $kk?>"><?php echo $vv?></option>
  <?php } ?>
</select>