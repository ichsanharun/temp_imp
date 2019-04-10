<?php
    $ENABLE_ADD     = has_permission('Deliveryorder.Add');
    $ENABLE_MANAGE  = has_permission('Deliveryorder.Manage');
    $ENABLE_VIEW    = has_permission('Deliveryorder.View');
    $ENABLE_DELETE  = has_permission('Deliveryorder.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">


  <div class="col-lg-12">

       <div class="box-header text-left"><b>Pilih Periode : </b>
         <?php
         $periode_th = $this->Deliveryorder_model->select('LEFT(tgl_do,7) as tgl')->group_by('LEFT(tgl_do,7)')->find_all();
          ?>
         <select id="periode" name="periode" class="form-control input-sm" style="width: 25%;" tabindex="-1" required>
             <option value=""></option>
             <?php
             foreach($periode_th as $kso=>$vso){
               $selected = '';
               ?>
               <option value="<?php echo date("m/Y", strtotime($vso->tgl)); ?>" <?php echo $selected?>>
             <?php echo date("M Y", strtotime($vso->tgl)); ?>
             <?php } ?>
             <option value="All">All</option>
         </select>
         <?php if ($ENABLE_VIEW) : ?>
     			<span class="pull-right">

             <a class="btn btn-primary btn-sm" title="Excel" onclick="getexcel()"><i class="fa fa-download">&nbsp;</i>Excel  </a>
     			</span>
     		<?php endif; ?>
       </div>

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
	        </tr>
        </thead>
        <tbody>
          <?php if(@$results){ ?>
            <?php
            $n = 1;
            foreach(@$results as $kso=>$vso){
                $no = $n++;
                $cancel = '-';
                if($vso->status != "CCL"){
                  $cancel = '<span class="badge bg-orange" style="cursor:pointer;" onclick="setcanceldo(\''.$vso->no_do.'\')">CANCEL DO</span>';
                }else if($vso->status == "CCL"){
                  $cancel = '<span class="badge bg-green">YA</span>';
                }
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
          </tr>
        </tfoot>
        </table>
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
$(document).ready(function() {
  $(".datepicker").datepicker({
      format : "yyyy-mm-dd",
      showInputs: true,
      autoclose:true
  });
  $("#submit").on('click', function(){
    var pawal = $("#periode_awal").val();
    var pakhir = $("#periode_akhir").val();
    window.location.href = siteurl+"reportdo/filter/"+pawal+"/"+pakhir;
  });
  var table = $('#example1').DataTable();
  $('#periode').on( 'change', function () {
    if (this.value == "All") {
      var value = "";
    }else {
      var value = this.value;
    }
  table.search( value ).draw();
  } );
  $("#periode").select2({
      placeholder: "Pilih",
      allowClear: true
  });
});


function getexcel(){
      var tgl = $('#periode').val();
      console.log(tgl);
      if (tgl == "All") {
        var tglso = "All";
      }else {
        var explode = tgl.split('/');
        var tglso = explode[1].concat('-',explode[0]);

      }
      var uri3 = '<?php echo $this->uri->segment(3)?>';
      var uri4 = '<?php echo $this->uri->segment(4)?>';
      window.location.href= siteurl+"reportdo/downloadExcel_old/"+tglso;

    }


    $(function() {
      var dataTable = $("#example1").DataTable().draw();
    });

    function add_data(){
        window.location.href = siteurl+"deliveryorder_2/create";
    }
    function add_data_from_pending(){
        window.location.href = siteurl+"deliveryorder_2/createpending";
    }
/*
	  $(function() {
    	$("#example1").DataTable();
    	$("#form-area").hide();
  	});

    function edit_data(noso){
        window.location.href = siteurl+"salesorder/edit/"+noso;
    }
    */
    function delete_data_do(nodo){
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
                    url: siteurl+'deliveryorder/hapus_header_do',
                    data :{"NO_DO":nodo},
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
    function setcanceldo(nodo){
      swal({
          title: "Peringatan!",
          text: "Yakin Cancel Delivery Order "+nodo+"?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, Cancel DO",
          cancelButtonText: "Tidak!",
          closeOnConfirm: true,
          closeOnCancel: true
        },
        function(isConfirm){
          if(isConfirm){
            $.ajax({
                    url: siteurl+'deliveryorder_2/set_cancel_do',
                    data :{"NO_DO":nodo},
                    dataType : "json",
                    type: 'POST',
                    success: function(result){
                        if(result.cancel=='1'){
                            swal({
                              title: "Sukses!",
                              text: "Data berhasil dicancel",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            setTimeout(function(){
                                //window.location.reload();
                            },1600);
                        } else {
                            swal({
                              title: "Gagal!",
                              text: "Data gagal dicancel",
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
          }
      });
    }
    function PreviewPdf(nodo)
    {
      param=nodo;
      tujuan = 'deliveryorder_2/print_request/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }

</script>
