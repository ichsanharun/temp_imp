<input type="hidden" name="filterby" value="<?php echo @$filter?>" id="filterby">
<?php if(@$selectfilter != "belum"){ ?>
<select name="filter-select" id="filter-select" class="form-control input-sm select2" style="width: 250px;">
  <option value="">Silahkan Pilih</option>
  <?php 
  $n=1;
  foreach(@$selectfilter as $k=>$v){ 
    $kk="";
    $vv="";
    $selected2 ='';
    
    if(@$filter == 'by_produk'){
      $kk=$v->id_barang;
      $vv=$v->id_barang.', '.$v->nm_barang;
      if($kk == $this->input->get('param')){
       $selected2 = 'selected="selected"';
      }
    }elseif(@$filter == 'by_customer'){
      $kk=$v->id_customer;
      $vv=$v->nm_customer;
      if($kk == $this->input->get('param')){
       $selected2 = 'selected="selected"';
      }
    }else{
      $kk=$v->id_karyawan;
      $vv=$v->nama_karyawan;
      if($kk == $this->input->get('param')){
       $selected2 = 'selected="selected"';
      }
    }
  ?>
  <option value="<?php echo $kk?>" <?php echo $selected2?>><?php echo $vv?></option>
  <?php } ?>
</select>
<script type="text/javascript">
  $('#filter-select').select2();
</script>
<?php }else{ echo "";}?>