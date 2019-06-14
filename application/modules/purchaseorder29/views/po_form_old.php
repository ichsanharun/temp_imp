<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-header text-left"><b>Pilih Cabang : </b>
      <select id="kdcab" name="kdcab" class="form-control input-sm" style="width: 25%;" tabindex="-1" required onchange="getcabang()">
          <option value=""></option>
          <?php
            $cab='';
            foreach(@$cabang as $kc=>$vc){
              $selected = '';
              if($this->uri->segment(3) == $vc->kdcab){
                   $selected = 'selected="selected"';
                   $cab = $vc->namacabang;
              }
              ?>
          <option value="<?php echo $vc->kdcab; ?>" <?php echo set_select('namacabang', $vc->kdcab, isset($data->namacabang) && $data->kdcab == $vc->kdcab) ?> <?php echo $selected?>>
            <?php echo $vc->kdcab.' , '.$vc->namacabang ?>
          </option>
          <?php } ?>
      </select>
    </div>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th>NO. PR</th>
              <th>Nama Supplier</th>
              <th>Tanggal PR</th>
              <th>Plan Delivery</th>
              <th>Total</th>
              <th width="7%">Aksi</th>
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
              <td><center><?php echo $vso->no_pr?></center></td>
              <td><?php echo $vso->id_supplier.' / '.$vso->nm_supplier?></td>
              <td><center><?php echo date('d/m/Y',strtotime($vso->tgl_pr))?></center></td>
              <td><center><?php echo date('d/m/Y',strtotime($vso->plan_delivery_date))?></center></td>
              <td class="text-right"><?php echo formatnomor($vso->total_pr)?></td>
                <!--<td class="text-right">
                    <?php // echo $vso->stsorder?>
                </td>-->
                <td class="text-center">
                    <input <?php echo $disabled?> type="checkbox" class="set_choose_po" name="set_choose_po" id="set_choose_po<?php echo $no?>" value="<?php echo $vso->no_pr?>" onclick="cekcab('<?php echo $vso->kdcab?>','<?php echo $no?>')">
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
      <input type="hidden" name="cekcab" id="cekcab" class="form-control input-sm">
      <input type="hidden" id="cekcabang" class="form-control input-sm">
      <button onclick="proses_po()" class="btn btn-primary" id="btn-proses-do" <?php  echo $disabled?> type="button"> Proses PO</button>
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
      $("#kdcab").select2({
          placeholder: "Pilih",
          allowClear: true
      });
  });
  function getcabang(){
        var kdcab = $('#kdcab').val();
        window.location.href = siteurl+"purchaseorder/create/"+kdcab;
      }
  function proses_po(){
    var param = $('#cekcab').val();
    var uri3 = '<?php echo $this->uri->segment(3)?>';
    window.location.href = siteurl+"purchaseorder/proses/"+uri3+"?param="+param;
  }
  $(function() {
      $("#example1").DataTable();
      $("#form-area").hide();
    });

  function cekcab(kdcab,no){
    var cabang = $('#cekcabang').val();
    var reason = [];
    if($('#cekcabang').val() == ""){
        $('#cekcabang').val(kdcab);
    }else{
        if(cabang != kdcab){
          swal({
              title: "Peringatan!",
              text: "Cabang tidak boleh berbeda",
              type: "error",
              timer: 1500,
              showConfirmButton: false
          });
          $("#set_choose_po"+no).attr("checked", false);
        }
    }
    var jumcus = 0;
    $(".set_choose_po:checked").each(function() {
        reason.push($(this).val());
        jumcus++;
    });
    $('#cekcab').val(reason.join(';'));
    if(jumcus == 0){
      $('#cekcabang').val('');
    }
  }
    /*
    $("#set_choose_po").on('click',function(){
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
