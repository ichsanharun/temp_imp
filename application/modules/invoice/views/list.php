<?php
    $ENABLE_ADD     = has_permission('Invoice.Add');
    $ENABLE_MANAGE  = has_permission('Invoice.Manage');
    $ENABLE_VIEW    = has_permission('Invoice.View');
    $ENABLE_DELETE  = has_permission('Invoice.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box" style="overflow-x:auto">
	 <div class="box-header">
        <?php if ($ENABLE_ADD) : ?>
            <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
        <?php endif; ?>

        <span class="pull-right">
                <?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
        </span>
    </div>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
	        <tr>
	            <th width="2%">#</th>
              <th width="10%">NO. Invoice</th>
              <th width="10%">NO. SJ</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Jatuh Tempo</th>
              <th>Nama Salesman</th>
              <th>Total</th>
			        <th>Status</th>
              <th width="7%">Aksi</th>
	        </tr>
        </thead>
        <tbody>
          <?php
          $n=1;
          if(@$results){
          foreach(@$results as $kr=>$vr){
            $no = $n++;
            $cek_do = $this->db->query("SELECT no_do FROM trans_invoice_detail WHERE no_invoice = '$vr->no_invoice' GROUP BY no_do");
            $res = $cek_do->result();
            $num = $cek_do->num_rows();
          ?>
          <tr>
            <td><center><?php echo $no?></center></td>
            <td><center><?php echo $vr->no_invoice?></center></td>
            <?php if ($num>1) {
              foreach ($res as $key => $value) {
                ?>
                  <td>
                    <?php echo $value->no_do?><br>
                  </td>
                <?php
              }
            }else {
              ?>
              <td><?php echo $res[0]->no_do?></td>
              <?php
            } ?>
            <td><?php echo $vr->nm_customer?></td>
            <td><center><?php echo date('d M Y',strtotime($vr->tanggal_invoice))?></center></td>
            <td><center><?php echo date('d M Y',strtotime($vr->tgljatuhtempo))?></center></td>
            <td><?php echo $vr->nm_salesman?></td>
            <td class="text-right"><?php echo formatnomor($vr->hargajualtotal)?></td>
      		  <td class="text-center">
      			<?php
      				$OK		=1;
      				if($vr->flag_cancel == 'N'){
      					echo"<span class='badge bg-green'>OPEN</span>";
      				}else{
      					$OK		= 0;
      					echo"<span class='badge bg-red'>BATAL</span>";
      				}
      			?>
      		  </td>
            <td class="text-center">
              <?php if($OK==1){?>
              <a class="btn bg-primary btn-xs" onclick="CustomePrint('<?php echo $vr->no_invoice?>')">
                  <span class="glyphicon glyphicon-print"></span>
              </a>
              <!--<a href="#dialog-popup" data-toggle="modal" class="btn bg-primary" onclick="PreviewPdf('<?php //echo $vr->no_invoice?>')">
                  <span class="glyphicon glyphicon-print"></span>
              </a>-->
        			&nbsp;
        			<a href="#" class="btn bg-red btn-xs" onClick="return batalInvoice('<?php echo $vr->no_invoice?>')"> <i class="fa fa-trash-o"></i></a>

        			<?php } ?>
            </td>
          </tr>
          <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th width="2%">#</th>
              <th width="15%">NO. Invoice</th>
              <th width="">NO. SJ</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Jatuh Tempo</th>
              <th>Nama Salesman</th>
              <th>Total</th>
              <th>Status</th>
              <th>Aksi</th>
          </tr>
        </tfoot>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup-invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Invoice</h4>
      </div>
      <div class="modal-body" id="MyModalBodyPrintPreview">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-print-invoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-print"></span>&nbsp;Cetak Invoice</h4>
      </div>
      <div class="modal-body" id="MyModalBodyCustomPrint" style="background: #FFF !important;color:#000 !important;">
        <div class="form-group ">

          <div class="col-sm-12">
                <div class="radio-inline">
                  <label>
                    <input type="radio" value="lawas" name="radio_layout" checked>Tampilkan Layout Lawas
                  </label>
                </div>
                <div class="radio-inline">
                  <label>
                    <input type="radio" value="baru" name="radio_layout">Tampilkan Layout Terbaru
                  </label>
                </div>
                <input type="hidden" name="no_inv" id="no_inv" value="">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="CetakInvoice()">
        <span class="glyphicon glyphicon-print"></span>  Cetak Invoice</button>
      </div>
    </div>
  </div>
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(function() {
      var dataTable = $("#example1").DataTable();
    });
    function add_data(){
        window.location.href = siteurl+"invoice/create";
    }
    function PreviewPdf(noinv)
    {
      param=noinv;
      tujuan = 'invoice/print_request/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }

	function batalInvoice(kode){
		window.location.href = base_url + active_controller+'/cancel_invoice/'+kode;
	}


  function CustomePrint(inv){
    var noinv = $('#no_invoice').val();
    $('#no_inv').val(inv);
    var customediskon = $('#set_display_diskon').val();
    var url = siteurl+'invoice/print_custom/'+inv;
    //$('#dialog-print-invoice').modal('hide');

    //$.post(url,{'NO_INV':noinv,'DISPLAY_DISKON':customediskon},function(result){
    //$('#dialog-popup-invoice').modal('show');
    //$("#MyModalBodyPrintPreview").html('<iframe src="'+url+'" frameborder="no" width="100%" height="400"></iframe>');
    $('#dialog-print-invoice').modal('show');

	}

	function cekdiskon(diskon){
		var setdis = $('#set_display_diskon').val();
		var reason = [];
		$("#cek_diskon:checked").each(function() {
			reason.push($(this).val());
		});
		$('#set_display_diskon').val(reason.join('|'));
	}

  function CetakInvoice(){
    var noinv = $('#no_inv').val();
    var layout = $('input[name="radio_layout"]:checked').val();
    if (layout == 'lawas') {
      var url = siteurl+'invoice/print_custom_lawas/'+noinv;
    }else {
      var url = siteurl+'invoice/print_custom_new/'+noinv;
    }
    $('#dialog-print-invoice').modal('hide');

    //$.post(url,{'NO_INV':noinv,'DISPLAY_DISKON':customediskon},function(result){
    $('#dialog-popup-invoice').modal('show');
    $("#MyModalBodyPrintPreview").html('<iframe src="'+url+'" frameborder="no" width="100%" height="400"></iframe>');
    //});
  }
</script>
