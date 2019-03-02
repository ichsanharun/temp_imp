<?php
    $ENABLE_ADD     = has_permission('Deliveryorder.Add');
    $ENABLE_MANAGE  = has_permission('Deliveryorder.Manage');
    $ENABLE_VIEW    = has_permission('Deliveryorder.View');
    $ENABLE_DELETE  = has_permission('Deliveryorder.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
	  <div class="box-header text-right"><b>Filter Customer : </b>
      <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 20%;" tabindex="-1" required onchange="getcustomer()">
          <option value=""></option>
          <?php
            $cus='';
            foreach(@$customer as $kc=>$vc){
              $selected = '';
              if($this->uri->segment(3) == $vc->id_customer){
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
	            <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Nama Supir</th>
	            <th>Kendaraan</th>
	            <th>Status</th>
	            <th>Aksi</th>
	        </tr>
        </thead>
        <tbody>
          <?php if(@$results){ ?>
            <?php 
            $n = 1;
            $disabled='disabled="disabled"';
            if($this->uri->segment(3) != ""){
              $disabled="";
            }
            foreach(@$results as $kso=>$vso){ 
                $no = $n++;
            ?>
            <tr>
              <td><center><?php echo $no?></center></td>
              <td><?php echo $vso->no_do?></td>
              <td><?php echo $vso->nm_customer?></td>
              <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_do))?></td>
              <td><?php echo $vso->nm_salesman?></td>
              <td><?php echo $vso->nm_supir?></td>
              <td><?php echo $vso->id_kendaraan?></td>
              <td><center><?php echo $vso->status?></center></td>
              <td class="text-center">
                <input <?php echo $disabled?> type="checkbox" name="set_choose_invoice" id="set_choose_invoice" value="<?php echo $vso->no_do?>" onclick="setinvoice()">
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
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
      <input type="hidden" name="cekchoose" id="cekchoose" class="form-control input-sm">
      <button onclick="proses_invoice()" class="btn btn-primary" id="btn-proses-do" <?php echo $disabled?> type="button"> Proses Invoice</button> 
    </div>
  </div>
</div>
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

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">
    $(document).ready(function(){
      $("#idcustomer").select2({
          placeholder: "Pilih",
          allowClear: true
      });
      $("#example1").DataTable();
    });
    function getcustomer(){
      var idcus = $('#idcustomer').val();
      window.location.href = siteurl+"invoice/create/"+idcus;
    }
    function proses_invoice(){
      var param = $('#cekchoose').val();
      var uri3 = '<?php echo $this->uri->segment(3)?>';
      window.location.href = siteurl+"invoice/proses/"+uri3+"?param="+param;
    }
    function setinvoice(){
      var reason = [];
      $("#set_choose_invoice:checked").each(function() {
        reason.push($(this).val());
      });
      $('#cekchoose').val(reason.join(';'));
    }
</script>