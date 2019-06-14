<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-header text-right"><b>Filter Supplier : </b>
      <select id="idsupplier" name="idsupplier" class="form-control input-sm" style="width: 25%;" tabindex="-1" required onchange="getsupplier()">
                                <option value=""></option>
                                <?php
                                foreach(@$supplier as $kc=>$vc){
                                    $selected ='';
                                    if($this->uri->segment(3) == $vc->id_supplier){
                                        $selected='selected="selected"';
                                    }
                                ?>
                                <option value="<?php echo $vc->id_supplier; ?>" <?php echo set_select('nm_supplier', $vc->id_supplier, isset($data->nm_supplier) && $data->id_supplier == $vc->id_supplier) ?> <?php echo $selected?>>
                                    <?php echo $vc->id_supplier.' , '.$vc->nm_supplier ?>
                                </option>
                                <?php } ?>
                                </select>
    </div>
    <div class="box-body">
        <table id="listpotorec" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th>NO. PO</th>
                <th>Nama Supplier</th>
                <th>Tanggal PO</th>
                <th>Plan Delivery</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
           <?php
           $n=1;
           if(@$results){
            $disabled='disabled="disabled"';
            if($this->uri->segment(3) != ""){
              $disabled="";
            }
           foreach(@$results as $kp => $vp){
            $no = $n++;
           ?> 
           <tr>
             <td><center><?php echo $no?></center></td>
             <td><center><?php echo $vp->no_po?></center></td>
             <td><?php echo $vp->id_supplier.' / '.$vp->nm_supplier?></td>
             <td><center><?php echo date('d/m/Y',strtotime($vp->tgl_po))?></center></td>
             <td><center><?php echo date('d/m/Y',strtotime($vp->plan_delivery_date))?></center></td>
             <td class="text-right"><?php echo formatnomor($vp->total_po)?></td>
             <td class="text-center">
                    <input <?php echo $disabled?> type="checkbox" name="set_choose_po" id="set_choose_po" value="<?php echo $vp->no_po?>" onclick="cekcuspo()">
                </td>
           </tr>
           <?php } ?>
           <?php } ?>
        </tbody>
        <tfoot>
            <tr>
              <th width="2%">#</th>
              <th>NO. PO</th>
              <th>Nama Supplier</th>
              <th>Tanggal</th>
              <th>Plan Delivery</th>
              <th>Total</th>
              <th>Aksi</th>
            </tr>
        </tfoot>
        </table>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
      <input type="hidden" name="cekcuspo" id="cekcuspo" class="form-control input-sm">
      <button onclick="proses_receiving()" class="btn btn-primary" id="btn-proses-do" <?php echo $disabled?> type="button"> Proses Receiving</button> 
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Purchase Order (PO)</h4>
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
    $(function() {
        $("#listpotorec").DataTable();
        $("#idsupplier").select2({
            placeholder: "Pilih",
            allowClear: true
        });
    });
    function getsupplier(){
        var idsup = $('#idsupplier').val();
        window.location.href = siteurl+"receiving/create/"+idsup;
    }
    function cekcuspo(){
      var reason = [];
      $("#set_choose_po:checked").each(function() {
        reason.push($(this).val());
      });
      $('#cekcuspo').val(reason.join(';'));
    }
    function proses_receiving(){
      var param = $('#cekcuspo').val();
      var uri3 = '<?php echo $this->uri->segment(3)?>';
      if(param != ""){
        window.location.href = siteurl+"receiving/proses/"+uri3+"?param="+param;
      }else{
        swal({
            title: "Peringatan!",
            text: "Silahkan pilih data yang akan diproses",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
          });
      }
    }
</script>