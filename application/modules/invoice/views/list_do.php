<?php
    $ENABLE_ADD     = has_permission('Deliveryorder.Add');
    $ENABLE_MANAGE  = has_permission('Deliveryorder.Manage');
    $ENABLE_VIEW    = has_permission('Deliveryorder.View');
    $ENABLE_DELETE  = has_permission('Deliveryorder.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form action="<?= site_url(strtolower($this->uri->segment(1).'/create'))?>" method="POST" id='form_proses'>
<div class="box">
	  <div class="box-header text-right"><b>Filter Customer : </b>
      <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 20%;" tabindex="-1" required>
          <option value=""></option>
          <?php
            $cus='';
            foreach(@$customer as $kc=>$vc){
              $selected = '';
              if($kode_customer == $vc->id_customer){
                   $selected = 'selected="selected"';
                   $cus = $vc->nm_customer;
              }
              ?>
          <option value="<?php echo $vc->id_customer; ?>" <?php echo set_select('nm_customer', $vc->id_customer, isset($data->nm_customer) && $data->id_customer == $vc->id_customer) ?> <?php echo $selected?>>
            <?php echo $vc->id_customer.' , '.$vc->nm_customer ?>
          </option>
          <?php } ?>
      </select>
    </div>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
	        <tr>
	            <th width="2%">#</th>
              <th>NO. DO</th>
              <th>NO. DO LAMA</th>
	            <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Nama Supir</th>
	            <th>Detail</th>
	            <th>Status</th>
	            <th>Aksi</th>
	        </tr>
        </thead>
        <tbody id='list_detail'>
          <?php if(@$results){ ?>
            <?php
            $n = 1;

            foreach(@$results as $kso=>$vso){
                $no = $n++;
            ?>
            <tr>
              <td><center><?php echo $no?></center></td>
              <td><?php echo $vso->no_do?></td>
              <td><?php echo $vso->nd?></td>
              <td><?php echo $vso->nm_customer?></td>
              <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_do))?></td>
              <td><?php echo $vso->nm_salesman?></td>
              <td><?php echo $vso->nm_supir?></td>
              <td>
                <a href="#dialog-popup" data-toggle="modal" onclick="PreviewProforma('<?php echo $vso->no_do?>')" title="Proforma Invoice">
                <span class="fa fa-ticket"></span>
                </a>
              </td>
              <td><center><?php echo $vso->status?></center></td>
              <td class="text-center">
                <input type="checkbox" class="set_choose_do" name="set_choose_invoice[<?php echo $no;?>]" id="set_choose_invoice<?php echo $no?>" value="<?php echo $vso->no_do?>" onclick="cekcusx('<?php echo $vso->id_customer?>','<?php echo $no?>')">
              </td>
            </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th width="2%">#</th>
              <th>NO. DO</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Nama Supir</th>
              <th>Kendaraan</th>
              <th>Status</th>
              <th>Aksi</th>
          </tr>
        </tfoot>
        </table>
    </div>
	<div class="box-footer">
		<input type="hidden" name="cekcus" id="cekcus" class="form-control input-sm">
      	<input type="hidden" id="cekcustomer" class="form-control input-sm">
		<button class="btn btn-primary" id="btn-proses-do" type="button"> Proses Invoice</button>&nbsp;&nbsp;<button class="btn btn-danger" id="btn-proses-back" type="button"> Kembali</button>
	</div>
</div>

</form>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Delivery Order (DO)</h4>
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
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Delivery Order (DO)</h4>
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
<link type="text/css" rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.css')?>">
<script src="<?= base_url('assets/plugins/sweetalert/dist/sweetalert.min.js')?>"></script>
<!-- page script -->
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(document).ready(function(){
      $("#idcustomer").select2({
          placeholder: "Pilih",
          allowClear: true
      });
      $("#example1").DataTable();
	  $('#idcustomer').change(function(){
			$('#form_proses').submit();
	  });
	  $('#btn-proses-do').click(function(e){
			e.preventDefault();
			var customer	= $('#idcustomer').val();
			var ints		=0;
			$('#list_detail').find('input[type="checkbox"]').each(function(){
				if($(this).is(':checked')){
					ints++;
				}
			});
			/*
			if(customer=='' || customer==null){
				swal({
				  title: "Peringatan!",
				  text: 'Empty Customer. Please Choose Customer First....',
				  type: "warning",
				  timer: 5000
				});

				return false;
			}
			*/
			if(ints==0){
				swal({
				  title: "Peringatan!",
				  text: 'Silahkan pilih data terlebih dahulu',
				  type: "warning",
				  timer: 5000
				});
				return false;
			}
			var links	= base_url+active_controller+'/proses';
			$('#form_proses').attr('action',links);
			$('#form_proses').submit();

	  });
	  $('#btn-proses-back').click(function(){
		 window.location =  base_url+active_controller;
	  });

    });
    function cekcusx(idcus,no){
	    var customer = $('#cekcustomer').val();
	    var reason = [];
	    if($('#cekcustomer').val() == ""){
	        $('#cekcustomer').val(idcus);
          $('#idcustomer').val(idcus);
	    }else{
	        if(customer != idcus){
	          swal({
	              title: "Peringatan!",
	              text: "Customer tidak boleh berbeda",
	              type: "error",
	              timer: 1500,
	              showConfirmButton: false
	          });
	          $("#set_choose_invoice"+no).attr("checked", false);
	        }
	    }
	    var jumcus = 0;
	    $(".set_choose_do:checked").each(function() {
	        reason.push($(this).val());
	        jumcus++;
	    });
	    $('#cekcus').val(reason.join(';'));
	    if(jumcus == 0){
	      $('#cekcustomer').val('');
	    }
  }
  function PreviewProforma(nodo)
  {
    param=nodo;
    tujuan = siteurl+'deliveryorder_2/print_proforma/'+param;

      $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
  }
</script>
