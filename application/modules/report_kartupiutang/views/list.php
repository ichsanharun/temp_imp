<?php
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

    <div class="box-header text-left">
      <div class="form-inline">
        <div class="form-group">
          
        </div>
      </div>
      <span class="pull-right">
      <?php echo anchor(site_url('reportar/downloadExcel').'?idcabang='.$this->uri->segment(3).'&bln='.$this->uri->segment(4).'&thn='.$this->uri->segment(5),'<i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
      <!--<a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a>-->
    </span>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th>ID Customer</th>
              <th>Customer</th>
              <th>Jumlah Invoice</th>
              <th width="15%">Total Penjualan</th>
              <th>Total Piutang</th>
              <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$customer){
        foreach(@$customer as $kr=>$vr){
          $no = $n++;
          $total_penjualan  = 0;
          $total_piutang  = 0;
          $total_inv = 0;
          $data_inv = $this->db->where(array('id_customer'=>$vr->id_customer,'flag_cancel'=>'N'))->get('trans_invoice_header')->result();
          foreach ($data_inv as $key => $value) {
            $total_inv++;
            $total_penjualan += $value->hargajualtotal;
            $total_piutang += $value->piutang;
          }
          $penjualan += $total_penjualan;
          $piutang += $total_penjualan;
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><?php echo $vr->id_customer?></td>
          <td><?php echo $vr->nm_customer?></td>
          <td><center><?php echo $total_inv?></center></td>
          <td><center><?php echo "Rp ".formatnomor($total_penjualan)?></center></td>
          <td><center><?php echo "Rp ".formatnomor($total_piutang)?></center></td>
          <td style="padding-left:20px">
            <center>
              <a class="" href="javascript:void(0)" title="print" onclick="print('<?=$vr->id_customer?>')">
                <i class="fa fa-print"></i>
              </a>
              <a class="detail" href="javascript:void(0)" title="Detail" style="color:red">
                <i class="fa fa-eye"></i>
              </a>
            </center>
          </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th colspan="3">Total:</th>
              <th><center><?php echo "Rp ".formatnomor($penjualan)?></center></th>
              <th><center><?php echo "Rp ".formatnomor($piutang)?></center></th>

              <th>Aksi</th>
          </tr>
        </tfoot>
      </table>

    <?php //print_r($customer); ?>
    <!--table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th>Tanggal</th>
              <th>TP</th>
              <th width="15%">NO. Reff</th>
              <th>Jatuh Tempo</th>
              <th>Mata Uang</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Saldo Akhir</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $n=1;
        if(@$results){
        foreach(@$results as $kr=>$vr){
          $no = $n++;
          $debet=0;
          if($vr->debet != 0){
              $debet=formatnomor($vr->debet);
          }
          $kredit=0;
          if($vr->kredit != 0){
              $kredit=formatnomor($vr->kredit);
          }
        ?>
        <tr>
          <td><center><?php echo $no?></center></td>
          <td><center><?php echo $vr->tanggal_invoice?></center></td>
          <td><center>INV</center></td>
          <td><center><?php echo $vr->no_invoice?></center></td>
          <td><?php echo date("d m Y", strtotime($vr->tgljatuhtempo))?></td>
          <td>IDR</td>
          <td class="text-right"><?php echo formatnomor($vr->hargajualtotal)?></td>
          <td class="text-right"><?php echo ''?></td>
          <td class="text-right"><?php echo formatnomor($vr->saldo_akhir)?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
      </table>-->
  </div>
  <!-- /.box-body -->
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="MyModalBody">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
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
    var table = $('#example1').DataTable();

    // Add event listener for opening and closing details
    $('#example1 tbody').on('click', 'a.detail', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            var d = row.data();

            $.ajax({
              type:"GET",
              url:siteurl+"report_kartupiutang/get_pilihan",
              data:"customer="+d[1],
              success:function(result){
                //var data = JSON.parse(result);
                //cetak(result,row,tr);
                //console.log(data.satuan);
                // `d` is the original data object for the row
                row.child( result ).show();
                tr.addClass('shown');
                }
              });


        }
    } );

    $("#submit").on('click', function(){
      var cabang = $("#filtercabang").val();
      var bln = $('#filterbulan').val();
      var thn = $('#filtertahun').val();
      if(cabang == "" || bln == "" || thn == ""){
        swal({
          title: "Peringatan!",
          text: "Filter Cabang, Bulan dan Tahun harus diisi",
          type: "warning",
          //timer: 2000,
          showConfirmButton: true
        });
      }else{
        window.location.href = siteurl+"reportar/filter/"+cabang+"/"+bln+"/"+thn;
      }
    });
  });

  $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

  function get_payment(id)
  {
    $.ajax({
      type:"GET",
      url:siteurl+"reportpenjualan/index",
      data:"periode_awal='2000-01-01';periode_akhir='<?=date("Y-m-d")?>';filter_by='by_customer';filter_value="+d[1],
      success:function(result){
        //var data = JSON.parse(result);
        //cetak(result,row,tr);
        //console.log(data.satuan);
        // `d` is the original data object for the row
        row.child( result ).show();
        tr.addClass('shown');
        }
      });
  }
  function print(id)
  {
    tujuan = 'report_kartupiutang/print_trans/'+id;
    window.open(tujuan);
  }

</script>
