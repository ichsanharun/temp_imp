<?php
    $ENABLE_ADD     = has_permission('Deliveryorder.Add');
    $ENABLE_MANAGE  = has_permission('Deliveryorder.Manage');
    $ENABLE_VIEW    = has_permission('Deliveryorder.View');
    $ENABLE_DELETE  = has_permission('Deliveryorder.Delete');
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#DO" data-toggle="tab" aria-expanded="true" id="data">List SJ</a></li>
        <li class=""><a href="#INV" data-toggle="tab" aria-expanded="false" id="data_inv">List SJ->INV</a></li>
        <li class=""><a href="#CCL" data-toggle="tab" aria-expanded="false" id="data_ccl">List CCL</a></li>
    </ul>
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="DO">
            <!-- form start-->
            <div class="box">
              	<div class="box-header">
                      <?php if ($ENABLE_ADD) : ?>
                          <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New SJ</a>
                          <!--a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data_from_pending()"><i class="fa fa-plus">&nbsp;</i>New From Pending</a-->
                      <?php endif; ?>

                      <span class="pull-right">
                              <?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
                      </span>
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                    <thead>
            	        <tr class="bg-blue">
            	            <th width="2%">#</th>
                          <th>NO. SJ</th>
                          <th>NO. SO</th>
            	            <th>Nama Customer</th>
                          <th>Tanggal</th>
                          <th>Nama Salesman</th>
                          <th>Nama Supir</th>
                          <th>Helper</th>
            	            <th>Kendaraan</th>
                          <th>Status</th>
            	            <th>Aksi</th>
                          <th>Cetak</th>
            	        </tr>
                    </thead>
                    <tbody>
                      <?php if(@$results){ ?>
                        <?php
                        $n = 1;
                        foreach(@$results as $kso=>$vso){
                          $noso = $this->db->query("SELECT no_so FROM trans_do_detail WHERE no_do = '$vso->no_do' GROUP BY no_so")->row();
                            $no = $n++;
                            $cancel = '-';
                            if($vso->status != 'INV'){
                              if($vso->status != "CCL"){
                                $cancel = '<span class="badge bg-orange" style="cursor:pointer;" onclick="setcanceldo(\''.$vso->no_do.'\')">CANCEL DO</span>';
                              }else if($vso->status == "CCL"){
                                $cancel = '<span class="badge bg-green">YA</span>';
                              }
                            }
                        ?>
                        <tr>
                          <td><center><?php echo $no?></center></td>
                          <td><?php echo $vso->no_do?></td>
                          <td><?php echo $noso->no_so?></td>
                          <td><?php echo $vso->nm_customer?></td>
                          <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_do))?></td>
                          <td><?php echo $vso->nm_salesman?></td>
                          <td><?php echo $vso->nm_supir?></td>
                          <td><?php echo $vso->nm_helper?></td>
                          <td><center><?php echo $vso->ket_kendaraan?></center></td>
                          <td><center><?php echo $vso->status?></center></td>
                          <!--<td><center><?php //echo $cancel?></center></td>-->
                            <td class="text-center">
                              <?php if($vso->status != "CCL"){ ?>
                                 <?php if($vso->status != "INV"){ ?>
                                 <a title="Edit DO" class="text-green" href="#dialog-edit" data-toggle="modal" onclick="editheader('<?php echo $vso->no_do?>')">
                                  <span class="glyphicon glyphicon-edit"></span>
                                  </a>
                                  <?php } ?>
                                 <?php if($vso->status != "INV"){ ?>

                                <a class="text-red" href="javascript:void(0)" title="Cancel DO" onclick="setcanceldo('<?php echo $vso->no_do?>')"><i class="fa fa-times"></i>
                                </a>
                                <?php } ?>
                              <?php } ?>
                            </td>
                            <td>
                              <?php if($vso->status != "CCL"){ ?>
                                <?php if($ENABLE_VIEW) { ?>
                                  <!--a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vso->no_do?>')">
                                  <span class="glyphicon glyphicon-print"></span>
                                </a-->
                                  <a href="#dialog-a" data-toggle="modal" class="btn bg-primary btn-xs" onclick="CustomePrint('<?php echo $vso->no_do?>')">
                                      <span class="glyphicon glyphicon-print"></span>
                                  </a>
                                <?php } ?>
                                <?php if($ENABLE_VIEW) { ?>
                                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewProforma('<?php echo $vso->no_do?>')" title="Proforma Invoice">
                                  <span class="fa fa-ticket"></span>
                                  </a>
                                <?php } ?>
                              <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr class="bg-blue">
                        <th width="2%">#</th>
                        <th>NO. SJ</th>
                        <th>NO. SO</th>
                        <th>Nama Customer</th>
                        <th>Tanggal</th>
                        <th>Nama Salesman</th>
                        <th>Nama Supir</th>
                        <th>Helper</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Cetak</th>
                      </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
          <!-- Data Produk -->
        </div>

        <div class="tab-pane" id="INV">
            <!-- form start-->
            <div class="box">
              	<div class="box-header">
                      <?php if ($ENABLE_ADD) : ?>
                          <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New SJ</a>
                          <!--a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data_from_pending()"><i class="fa fa-plus">&nbsp;</i>New From Pending</a-->
                      <?php endif; ?>

                      <span class="pull-right">
                              <?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
                      </span>
                </div>
                <div class="box-body">
                    <table id="tbl_inv" class="table table-bordered table-striped">
                    <thead>
            	        <tr>
            	            <th width="2%">#</th>
                          <th>NO. SJ</th>
                          <th>NO. SO</th>
            	            <th>Nama Customer</th>
                          <th>Tanggal</th>
                          <th>Nama Salesman</th>
                          <th>Nama Supir</th>
                          <th>Helper</th>
            	            <th>Kendaraan</th>
                          <th>Status</th>
            	            <th>Aksi</th>
                          <th>Cetak</th>
            	        </tr>
                    </thead>
                    <tbody>
                      <?php if(@$results_INV){ ?>
                        <?php
                        $n = 1;
                        foreach(@$results_INV as $kso=>$vso){
                          $noso = $this->db->query("SELECT no_so FROM trans_do_detail WHERE no_do = '$vso->no_do' GROUP BY no_so")->row();
                            $no = $n++;
                            $cancel = '-';
                            if($vso->status != 'INV'){
                              if($vso->status != "CCL"){
                                $cancel = '<span class="badge bg-orange" style="cursor:pointer;" onclick="setcanceldo(\''.$vso->no_do.'\')">CANCEL DO</span>';
                              }else if($vso->status == "CCL"){
                                $cancel = '<span class="badge bg-green">YA</span>';
                              }
                            }
                        ?>
                        <tr>
                          <td><center><?php echo $no?></center></td>
                          <td><?php echo $vso->no_do?></td>
                          <td><?php echo $noso->no_so?></td>
                          <td><?php echo $vso->nm_customer?></td>
                          <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_do))?></td>
                          <td><?php echo $vso->nm_salesman?></td>
                          <td><?php echo $vso->nm_supir?></td>
                          <td><?php echo $vso->nm_helper?></td>
                          <td><center><?php echo $vso->ket_kendaraan?></center></td>
                          <td><center><?php echo $vso->status?></center></td>
                          <!--<td><center><?php //echo $cancel?></center></td>-->
                            <td class="text-center">
                              <?php if($vso->status != "CCL"){ ?>
                                 <?php if($vso->status != "INV"){ ?>
                                 <a title="Edit DO" class="text-green" href="#dialog-edit" data-toggle="modal" onclick="editheader('<?php echo $vso->no_do?>')">
                                  <span class="glyphicon glyphicon-edit"></span>
                                  </a>
                                  <?php } ?>
                                 <?php if($vso->status != "INV"){ ?>

                                <a class="text-red" href="javascript:void(0)" title="Cancel DO" onclick="setcanceldo('<?php echo $vso->no_do?>')"><i class="fa fa-times"></i>
                                </a>
                                <?php } ?>
                              <?php } ?>
                            </td>
                            <td>
                              <?php if($vso->status != "CCL"){ ?>
                                <?php if($ENABLE_VIEW) { ?>
                                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vso->no_do?>')">
                                  <span class="glyphicon glyphicon-print"></span>
                                  </a>
                                <?php } ?>
                                <?php if($ENABLE_VIEW) { ?>
                                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewProforma('<?php echo $vso->no_do?>')" title="Proforma Invoice">
                                  <span class="fa fa-ticket"></span>
                                  </a>
                                <?php } ?>
                              <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                          <th width="2%">#</th>
                          <th>NO. SJ</th>
                          <th>Nama Customer</th>
                          <th>Tanggal</th>
                          <th>Nama Salesman</th>
                          <th>Nama Supir</th>
                          <th>Helper</th>
                          <th>Kendaraan</th>
                          <th>Status</th>
                          <th>Aksi</th>
                      </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
          <!-- Data Produk -->
        </div>

        <div class="tab-pane" id="CCL">
            <!-- form start-->
            <div class="box">
              	<div class="box-header">
                      <?php if ($ENABLE_ADD) : ?>
                          <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New SJ</a>
                          <!--a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data_from_pending()"><i class="fa fa-plus">&nbsp;</i>New From Pending</a-->
                      <?php endif; ?>

                      <span class="pull-right">
                              <?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
                      </span>
                </div>
                <div class="box-body">
                    <table id="tbl_ccl" class="table table-bordered table-striped">
                    <thead>
            	        <tr>
            	            <th width="2%">#</th>
                          <th>NO. SJ</th>
                          <th>NO. SO</th>
            	            <th>Nama Customer</th>
                          <th>Tanggal</th>
                          <th>Nama Salesman</th>
                          <th>Nama Supir</th>
                          <th>Helper</th>
            	            <th>Kendaraan</th>
                          <th>Status</th>
            	            <th>Aksi</th>
                          <th>Cetak</th>
            	        </tr>
                    </thead>
                    <tbody>
                      <?php if(@$results_CCL){ ?>
                        <?php
                        $n = 1;
                        foreach(@$results_CCL as $kso=>$vso){
                          $noso = $this->db->query("SELECT no_so FROM trans_do_detail WHERE no_do = '$vso->no_do' GROUP BY no_so")->row();
                            $no = $n++;
                            $cancel = '-';
                            if($vso->status != 'INV'){
                              if($vso->status != "CCL"){
                                $cancel = '<span class="badge bg-orange" style="cursor:pointer;" onclick="setcanceldo(\''.$vso->no_do.'\')">CANCEL DO</span>';
                              }else if($vso->status == "CCL"){
                                $cancel = '<span class="badge bg-green">YA</span>';
                              }
                            }
                        ?>
                        <tr>
                          <td><center><?php echo $no?></center></td>
                          <td><?php echo $vso->no_do?></td>
                          <td><?php echo $noso->no_so?></td>
                          <td><?php echo $vso->nm_customer?></td>
                          <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_do))?></td>
                          <td><?php echo $vso->nm_salesman?></td>
                          <td><?php echo $vso->nm_supir?></td>
                          <td><?php echo $vso->nm_helper?></td>
                          <td><center><?php echo $vso->ket_kendaraan?></center></td>
                          <td><center><?php echo $vso->status?></center></td>
                          <!--<td><center><?php //echo $cancel?></center></td>-->
                            <td class="text-center">
                              <?php if($vso->status != "CCL"){ ?>
                                 <?php if($vso->status != "INV"){ ?>
                                 <a title="Edit DO" class="text-green" href="#dialog-edit" data-toggle="modal" onclick="editheader('<?php echo $vso->no_do?>')">
                                  <span class="glyphicon glyphicon-edit"></span>
                                  </a>
                                  <?php } ?>
                                 <?php if($vso->status != "INV"){ ?>

                                <a class="text-red" href="javascript:void(0)" title="Cancel DO" onclick="setcanceldo('<?php echo $vso->no_do?>')"><i class="fa fa-times"></i>
                                </a>
                                <?php } ?>
                              <?php } ?>
                            </td>
                            <td>
                              <?php if($vso->status != "CCL"){ ?>
                                <?php if($ENABLE_VIEW) { ?>
                                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vso->no_do?>')">
                                  <span class="glyphicon glyphicon-print"></span>
                                  </a>
                                <?php } ?>
                                <?php if($ENABLE_VIEW) { ?>
                                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewProforma('<?php echo $vso->no_do?>')" title="Proforma Invoice">
                                  <span class="fa fa-ticket"></span>
                                  </a>
                                <?php } ?>
                              <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                          <th width="2%">#</th>
                          <th>NO. SJ</th>
                          <th>Nama Customer</th>
                          <th>Tanggal</th>
                          <th>Nama Salesman</th>
                          <th>Nama Supir</th>
                          <th>Helper</th>
                          <th>Kendaraan</th>
                          <th>Status</th>
                          <th>Aksi</th>
                      </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
          <!-- Data Produk -->
        </div>


    </div>
    <!-- /.tab-content -->
</div>

<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Surat Jalan (SJ)</h4>
      </div>
      <div class="modal-body" id="MyModalBodyPrintPreview">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<div class="modal modal-primary" id="dialog-a" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Surat Jalan (SJ)</h4>
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
                <input type="hidden" name="no_do" id="no_do" value="">
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

<div class="modal modal-primary" id="dialog-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file"></span>&nbsp;Surat Jalan (SJ)</h4>
      </div>
      <div class="modal-body" id="MyModalBodyEdit">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="edit_header_do()">
        <span class="glyphicon glyphicon-save"></span> Simpan Data</button>
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
      var dataTable = $("#example1").DataTable();
      var tbl_inv = $("#tbl_inv").DataTable();
      var tbl_ccl = $("#tbl_ccl").DataTable();
    });

    function add_data(){
        window.location.href = siteurl+"deliveryorder_2/create";
    }
    function add_data_from_pending(){
        window.location.href = siteurl+"pickinglistdop";
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
          text: "Yakin Cancel Surat Jalan "+nodo+"?",
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
                                window.location.reload();
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
    function CustomePrint(inv){
      $('#no_do').val(inv);

      $('#dialog-print-invoice').modal('show');

  	}
    function CetakInvoice(){
      var noinv = $('#no_do').val();
      var layout = $('input[name="radio_layout"]:checked').val();
      if (layout == 'lawas') {
        var url = siteurl+'deliveryorder_2/print_custom_lawas/'+noinv;
      }else {
        var url = siteurl+'deliveryorder_2/print_custom_baru/'+noinv;
      }
      $('#dialog-a').modal('hide');

      //$.post(url,{'NO_INV':noinv,'DISPLAY_DISKON':customediskon},function(result){
      $('#dialog-popup').modal('show');
      $("#MyModalBodyPrintPreview").html('<iframe src="'+url+'" frameborder="no" width="100%" height="400"></iframe>');
      //});
    }
    function PreviewProforma(nodo)
    {
      param=nodo;
      tujuan = 'deliveryorder_2/print_proforma/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
    function editheader(nodo){
      url = siteurl+'deliveryorder_2/getdatado';
      $.post(url,{'NODO':nodo},function(result){
        $("#MyModalBodyEdit").html(result);
      });
    }
    function edit_header_do(){
        console.log("OKKK");
        var formedit = $('#form-edit-header-do').serialize();
        $.ajax({
          url: siteurl+"deliveryorder_2/edit_header_do",
          dataType : "json",
          type: 'POST',
          data: formedit,
          success: function(result){
            console.log(result);
            if(result.edit=='1'){
              swal({
                title: "Sukses!",
                text: result['msg'],
                type: "success",
                timer: 1500,
                showConfirmButton: false
              });
              setTimeout(function(){
                window.location.href=siteurl+'deliveryorder_2';
              },1600);
            } else {
              swal({
                title: "Gagal!",
                text: "Data Gagal Di Simpan",
                type: "error",
                timer: 1500,
                showConfirmButton: false
              });
            };
          },
          error: function(){
            swal({
              title: "Gagal!",
              text: "Ajax Data Gagal Di Proses",
              type: "error",
              timer: 1500,
              showConfirmButton: false
            });
          }
        });
    }
</script>
