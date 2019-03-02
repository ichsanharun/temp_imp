<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
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
              <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if(@$results){ ?>
            <?php 
            $n = 1;
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
              <td class="text-center">
                    <?php
                    if($vso->konfirm_do == "BELUM"){
                    ?>
                    <button type="button" class="btn btn-sm btn-success" onclick="konfirmdo('<?php echo $vso->no_do?>')">
                        <span class="glyphicon glyphicon-file"></span> KONFIRMASI
                    </button>
                    <?php }else{ ?>
                    <button type="button" class="btn btn-sm btn-warning">
                        <span class="glyphicon glyphicon-ok"></span> <?php echo $vso->konfirm_do?>
                    </button>
                    <?php } ?>
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
              <th>Aksi</th>
          </tr>
        </tfoot>
        </table>
    </div>
</div>

<!-- Modal -->
<!-- Modal -->
<div class="modal modal-primary" id="dialog-konfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok"></span>&nbsp;Konfirmasi Delivery Order (DO)</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12" id="div-konfirm-do-detail">
            
          </div>
          <div class="col-sm-12">
            <table width="100%" border="1" style="display: none;">
              <tr>
                <td>
                  <input type="hidden" name="nodo_konfirm" id="nodo_konfirm">
                  <select class="form-control" id="select-konfirm-do" onchange="setkonfirmasido(this.value)">
                    <option value="">Pilih</option>
                    <option value="CLOSE">CLOSE</option>
                    <option value="RETURN">RETURN</option>
                  </select>
                </td>
                <td>
                  <input onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" type="text" name="jumlah_return_do" id="jumlah_return_do" class="form-control" placeholder="Jumlah Return DO" disabled="disabled">
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" type="button" onclick="savekonfirmdo()">
        <span class="fa fa-save"></span>  Save Konfirmasi</button>
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

<!-- page script -->
<script type="text/javascript">
    $(function() {
      var dataTable = $("#example1").DataTable();
    });
    function setkonfirmasido(kf){
      if(kf != ""){
        if(kf == "CLOSE"){
          $('#jumlah_return_do').attr('disabled','disabled');
        }else{
          $('#jumlah_return_do').removeAttr('disabled');
        }
      }else{
        swal({
            title: "Peringatan!",
            text: "Silahkan pilih konfirmasi",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
        });
      }
    }
    function konfirmdo(nodo){
      var url = siteurl+'returndo/setkonfirmasido';
      $('#dialog-konfirm').modal('show');
      $('#select-konfirm-do').val('');
      $('#nodo_konfirm').val(nodo);
      $.post(url,{'NODO':nodo},function(result){
        $('#div-konfirm-do-detail').html(result);
      });
    }
    function savekonfirmdo(){
        var formdata = $("#form-detail-do-konfirm").serialize();
        $.ajax({
            url: siteurl+"returndo/savekonfirmdo",
            dataType : "json",
            type: 'POST',
            data: formdata,
            success: function(result){
                if(result.type == "success"){
                  swal({
                    title: "Sukses!",
                    text: result.pesan,
                    type: result.type,
                    timer: 2000,
                    showConfirmButton: false
                  });
                  setTimeout(function(){
                    $('#dialog-konfirm').modal('hide');
                    window.location.reload();
                  }, 2500);
                }else{
                  swal({
                    title: "Peringatan!",
                    text: result.pesan,
                    type: result.type,
                    timer: 2500,
                    showConfirmButton: false
                  });
                }
            },
            error: function(xhr,st,msg){
              console.log(xhr);
            }
        });
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function cekreturn(no){
      var qty_sup = parseInt($('#qty_supply_do'+no).val());
      var qty_ret = parseInt($('#return_do'+no).val());
      if(filterAngka($('#return_do'+no).val()) == 1){
        if(qty_ret > qty_sup){
          console.log("SUP=>"+qty_sup+", RET=>"+qty_ret+" LEBIH");
          swal({
            title: "Peringatan!",
            text: "Konfirm return melebihi qty DO",
            type: "warning",
            timer: 2500,
            showConfirmButton: false
          });
          $('#return_do'+no).val(0);
        }
      }else{
          var ang = $('#return_do'+no).val();
          $('#return_do'+no).val(ang.replace(/[^0-9]/g,''));
      }
    }
    
</script>