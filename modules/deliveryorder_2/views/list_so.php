<?php
    $ENABLE_ADD     = has_permission('Salesorder.Add');
    $ENABLE_MANAGE  = has_permission('Salesorder.Manage');
    $ENABLE_VIEW    = has_permission('Salesorder.View');
    $ENABLE_DELETE  = has_permission('Salesorder.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-header text-right"><b>Filter Customer : </b>
      <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 25%;" tabindex="-1" required onchange="getcustomer()">
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
              <th>NO. SO</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Total</th>
              <!--<th>Status</th>-->
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
                <td class="text-center"><?php echo $no?></td>
                <td class="text-center"><?php echo $vso->no_so?></td>
                <td><?php echo $vso->nm_customer?></td>
                <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tanggal))?></td>
                <td><?php echo $vso->nm_salesman?></td>
                <td class="text-right"><?php echo formatnomor($vso->total)?></td>
                <!--<td class="text-right">
                    <?php // echo $vso->stsorder?>
                </td>-->
                <td class="text-center">
                    <input <?php //echo $disabled?> type="checkbox" class="set_choose_do" name="set_choose_do" id="set_choose_do<?php echo $no?>" value="<?php echo $vso->no_so?>" onclick="cekcus('<?php echo $vso->id_customer?>','<?php echo $no?>','<?php echo $vso->nm_customer?>','<?php echo "set_choose_do".$no?>')">
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
      <input type="hidden" name="cekcus" id="cekcus" class="form-control input-sm">
      <input type="hidden" id="cekcustomer" class="form-control input-sm">
      <button onclick="proses_do()" class="btn btn-primary" id="btn-proses-do" <?php // echo $disabled?> type="button"> Proses DO</button>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Sales Order (SO)</h4>
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
  });
  function getcustomer(){
        var idcus = $('#idcustomer').val();
        window.location.href = siteurl+"deliveryorder_2/create/"+idcus;
      }
  function proses_do(){
    var param = $('#cekcus').val();
    var uri3 = '<?php echo $this->uri->segment(3)?>';
    window.location.href = siteurl+"deliveryorder_2/proses/"+uri3+"?param="+param;
  }
  $(function() {
      $("#example1").DataTable();
      $("#form-area").hide();
    });

  function cekcus(idcus,no,id,set){
    var table = $('#example1').DataTable();
    var cek = $('#'+set);
    //alert(cek.value);
    if (cek.is(":checked")) {
      table.column(2).search( id ).draw();
    }
    else{
      table.column(2).search('').draw();
    }

    var customer = $('#cekcustomer').val();
    var reason = [];
    if($('#cekcustomer').val() == ""){
        $('#cekcustomer').val(idcus);
    }else{
        if(customer != idcus){
          swal({
              title: "Peringatan!",
              text: "Customer tidak boleh berbeda",
              type: "error",
              timer: 1500,
              showConfirmButton: false
          });
          $("#set_choose_do"+no).attr("checked", false);
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
    /*
    $("#set_choose_do").on('click',function(){
      var status = this.checked;
      $(".chooseRow").each( function() {
        $(this).prop("checked",status);
      });
    });
    $('#btn-proses-do').on("click", function(event){
      if( $('.chooseRow:checked').length > 0 ){
        var ids = [];
        $('.chooseRow').each(function(){
          if($(this).is(':checked')) {
            ids.push($(this).val());
          }
        });
        console.log(ids);
      }
    });
    */
    function add_data(){
        window.location.href = siteurl+"salesorder/create";
    }
    function edit_data(noso){
        window.location.href = siteurl+"salesorder/edit/"+noso;
    }
    function delete_data(noso){
        swal({
          title: "Anda Yakin?",
          text: "Data Akan Terhapus secara Permanen!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, delete!",
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm) {
            $.ajax({
                    url: siteurl+'salesorder/hapus_item_so',
                    data :{"NO_SO":noso,"ID":id},
                    dataType : "json",
                    type: 'POST',
                    success: function(result){
                        if(result.delete=='1'){
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.reload();
                            },1600);
                        } else {
                            swal({
                              title: "Gagal!",
                              text: "Data gagal dihapus",
                              type: "error",
                              timer: 1500,
                              showConfirmButton: false
                            });
                        };
                    },
                    error: function(){
                        swal({
                          title: "Gagal!",
                          text: "Gagal Eksekusi Ajax",
                          type: "error",
                          timer: 1500,
                          showConfirmButton: false
                        });
                    }
                });
          } else {
            //cancel();
          }
        });
    }
    function PreviewPdf(noso)
    {
      param=noso;
      tujuan = 'salesorder/print_request/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>
