<table class="table table-bordered table-hover">
  <tr class="bg-green">
    <th colspan="8"><center><?php echo @$invoice->id_customer.', '.@$invoice->nm_customer?></center></th>
  </tr>
  <tr class="bg-blue">
    <th>No. Pembayaran</th>
    <th>No. Invoice</th>
    <th>No. Reff</th>
    <th>Jumlah Piutang</th>
    <th width="15%">Tgl Bayar</th>
    <th>Kode Bank</th>
    <th>Jumlah Bayar</th>
    <th width="5%">Status</th>
  </tr>
  <?php
  
  if(@$lunas){
    $total_lunas=0;
    foreach(@$lunas as $lk=>$vk){
      $total_lunas += $vk->jumlah_pembayaran;
    }
  }
  if(@$pembayaran){
  ?>
  <tr>
    <td><center><?php echo $pembayaran->kd_pembayaran?></center></td>
    <td><center><?php echo $pembayaran->no_invoice?></center></td>
    <td><center><?php echo $pembayaran->no_reff?></center></td>
    <td class="text-right">
      <?php echo formatnomor($pembayaran->jumlah_piutang)?>
    </td>
    <td><center><?php echo date('d-M-Y',strtotime($pembayaran->tgl_pembayaran))?></center></td>
    <td><center><?php echo $pembayaran->nm_bank?></center></td>
    <td class="text-right">
      <?php echo formatnomor($pembayaran->jumlah_pembayaran)?>
    </td>
    <td><center><span class="badge bg-green"><?php echo $pembayaran->status_bayar?></span></center></td>
  </tr>
  <tr>
    <td colspan="3"><center><b>PEMBAYARAN SUDAH LUNAS =&nbsp;<?php echo formatnomor($total_lunas)?></b></center></td>
    <td></td>
    <td colspan="3"><center><b>TOTAL PEMBAYARAN =&nbsp;<?php echo formatnomor($pembayaran->jumlah_pembayaran)?></b></center></td>
    <td>
    <input type="hidden" name="nominal_pelunasan" id="nominal_pelunasan" value="<?php echo $pembayaran->jumlah_pembayaran?>">
    <input type="hidden" name="v_nominal_pelunasan" id="v_nominal_pelunasan" value="<?php echo formatnomor($pembayaran->jumlah_pembayaran)?>">
    </td>
  </tr>
  <?php } ?>
</table>

<script type="text/javascript">
  
  function prosespelunasan(){
    var jml = $('#v_nominal_pelunasan').val();
    swal({
          title: "Peringatan !",
          text: "Piutang akan berkurang "+jml,
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, Lanjutkan!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            var kdbyr = '<?php echo $pembayaran->kd_pembayaran?>';
            var inv = '<?php echo $pembayaran->no_invoice?>';
            var piutang = '<?php echo @$invoice->piutang?>';
            var nominal = '<?php echo $pembayaran->jumlah_pembayaran?>';
            var url = siteurl+'pelunasan/prosespelunasan';
            $.post(url,{'KD':kdbyr,'INV':inv,'PTG':piutang,'NML':nominal},function(result){
              if(result.save=='1'){
                swal({
                  title: "Sukses!",
                  text: result['msg'],
                  type: "success",
                  timer: 1500,
                  showConfirmButton: false
                });
                $('#dialog-popup-pelunasan').modal('hide');
                setTimeout(function(){
                  window.location.href=siteurl+'pelunasan';
                },3000);
              } else {
                swal({
                  title: "Gagal!",
                  text: "Data Gagal Di Simpan",
                  type: "error",
                  timer: 1500,
                  showConfirmButton: false
                });
              };
            },"json");
          }
        });
  }
</script>