<?php print_r($invoice)?>
<form id="form-pembayaran-piutang" method="post">
  <div class="form-horizontal">
    <div class="col-sm-6">
    <div class="form-group ">
      <label for="no_invoice" class="col-sm-4 control-label">No. Invoice </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="no_invoice" value="<?php echo @$invoice->no_invoice?>" class="form-control input-sm" readonly>
        <input type="hidden" name="kdcab" value="<?php echo @$invoice->kdcab?>" class="form-control input-sm" >
        <input type="hidden" name="idcus" value="<?php echo @$invoice->id_customer?>" class="form-control input-sm" >
        <input type="hidden" name="nmcus" value="<?php echo @$invoice->nm_customer?>" class="form-control input-sm" >
      </div>
    </div>
    <div class="form-group ">
      <label for="jml_piutang" class="col-sm-4 control-label">Jumlah Piutang </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="jml_piutang" value="<?php echo @$invoice->piutang?>" class="form-control input-sm" readonly>
      </div>
    </div>
    <div class="form-group ">
      <?php $tglbyr=date('Y-m-d')?>
      <label for="tgl_bayar" class="col-sm-4 control-label">Tgl Pembayaran </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="tgl_bayar" id="tgl_bayar" class="form-control input-sm datepicker" value="<?php echo $tglbyr?>">
      </div>
    </div>
    </div>
    <div class="col-sm-6">
    <div class="form-group ">
      <label for="bank" class="col-sm-4 control-label">Bank </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <select class="form-control input-sm" name="bank">
          <option value="">Pilih Bank</option>
          <?php
          foreach(@$bank as $kb=>$vb){
          ?>
          <option value="<?php echo $vb->kd_bank.'|'.$vb->nama_bank?>"><?php echo $vb->nama_bank?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group ">
      <label for="jml_bayar" class="col-sm-4 control-label">Jml Pembayaran </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="jml_bayar" class="form-control input-sm" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
      </div>
    </div>
    <div class="form-group ">
      <label for="no_reff" class="col-sm-4 control-label">No. Reff </label>
      <div class="col-sm-8" style="padding-top: 8px;">
        <input type="text" name="no_reff" class="form-control input-sm">
      </div>
    </div>
    </div>
  </div>
</form>
<table class="table table-bordered">
  <tr class="bg-blue">
    <th>No. Pembayaran</th>
    <th>No. Invoice</th>
    <th>No. Reff</th>
    <th>Jumlah Piutang</th>
    <th width="15%">Tgl Bayar</th>
    <th>Kode Bank</th>
    <th>Jumlah Bayar</th>
  </tr>
  <?php
  if(@$pembayaran){
  foreach(@$pembayaran as $ki=>$vi){ 
  ?>
  <tr>
    <td><center><?php echo $vi->kd_pembayaran?></center></td>
    <td><center><?php echo $vi->no_invoice?></center></td>
    <td><center><?php echo $vi->no_reff?></center></td>
    <td class="text-right">
      <?php echo formatnomor($vi->jumlah_piutang)?>
    </td>
    <td><center><?php echo date('d-M-Y',strtotime($vi->tgl_pembayaran))?></center></td>
    <td><center><?php echo $vi->nm_bank?></center></td>
    <td class="text-right">
      <?php echo formatnomor($vi->jumlah_pembayaran)?>
    </td>
  </tr>
  <?php } ?>
  <?php }else{ ?>
  <tr>
    <td colspan="7" class="bg-blue">Belum ada data Pembayaran</td>
  </tr>
  <?php } ?>
</table>

<script type="text/javascript">
	$('#tgl_bayar').datepicker({
		startDate: 'm',
		endDate: '+0d',
		format : "yyyy-mm-dd",
		showInputs: true,
		autoclose:true,
		maxViewMode: 0
	});
</script>