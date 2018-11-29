<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
	        <tr>
	            <th width="2%">#</th>
              <th>NO. Mutasi</th>
	            <th>Tgl Mutasi</th>
              <th>Asal</th>
              <th>Tujuan</th>
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
            foreach(@$results as $kso=>$vso){ 
                $no = $n++;
                $status = '<span class="badge bg-green">IN TRANSIT</span>';
                $disabled='';
                if($vso->status_mutasi == "REC"){
                  $status = '<span class="badge bg-red">RECEIVED</span>';
                  $disabled='disabled="disabled"';
                }
            ?>
            <tr>
              <td><center><?php echo $no?></center></td>
              <td><?php echo $vso->no_mutasi?></td>
              <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tgl_mutasi))?></td>
              <td><?php echo $vso->cabang_asal?></td>
              <td><?php echo $vso->cabang_tujuan?></td>
              <td><?php echo $vso->nm_supir?></td>
              <td><?php echo $vso->ket_kendaraan?></td>
              <td><center><?php echo $status?></center></td>
              <td class="text-center">
                  <button type="button" title="Proses Receiving" class="btn btn-primary btn-sm" onclick="prosesrecmutasi('<?php echo $vso->no_mutasi?>')" <?php echo $disabled?>>
                    <i class="fa fa-check"></i>
                  </button>
                  <button type="button" title="Cetak Receiving" class="btn btn-success btn-sm" onclick="PreviewPdf('<?php echo $vso->no_mutasi?>')">
                    <i class="fa fa-print"></i>
                  </button>
                  <!--
                    <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php //echo $vso->no_mutasi?>')">
                    <span class="glyphicon glyphicon-print"></span>
                    </a>
                    -->
              </td>
            </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr>
              <th width="2%">#</th>
              <th>NO. Mutasi</th>
              <th>Tgl Mutasi</th>
              <th>Asal</th>
              <th>Tujuan</th>
              <th>Nama Supir</th>
              <th>Kendaraan</th>
              <th>Status</th>
              <th>Aksi</th>
          </tr>
        </tfoot>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup-rec-mutasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-check"></span>&nbsp;Receiving Mutasi Produk</h4>
      </div>
      <div class="modal-body" id="MyModalBodyProsesRecMutasi" style="background: #FFF !important;color :#000 !important;"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="saverecmutasi()">
        <span class="glyphicon glyphicon-save"></span>  Simpan Data</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup-cetak" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-check"></span>&nbsp;Receiving Mutasi Produk</h4>
      </div>
      <div class="modal-body" id="MyModalBodyCetakRecMutasi"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="saverecmutasi()">
        <span class="glyphicon glyphicon-save"></span>  Simpan Data</button>
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
    function add_data(){
        window.location.href = siteurl+"mutasi/create";
    }
    function PreviewPdf(nomutasi)
    {
      tujuan = 'mutasi/print_request/'+nomutasi;
      $("#MyModalBodyCetakRecMutasi").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
    function prosesrecmutasi(no){
      $('#dialog-popup-rec-mutasi').modal('show');
      var url = siteurl+'receivingmutasi/getdetailmutasi';
      $.post(url,{'NO_MT':no},function(result){
        $('#MyModalBodyProsesRecMutasi').html(result);
      });
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function cek_rec_mutasi(no){
        var mutasi = parseInt($('#qty_mutasi_'+no).val());
        var rec_mutasi = parseInt($('#qty_rec_mutasi_'+no).val());
        if(filterAngka($('#qty_rec_mutasi_'+no).val()) == 1){
            if(rec_mutasi > mutasi){
                swal({
                    title: "Peringatan!",
                    text: "Qty Received Mutasi melebihi Qty Mutasi",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#qty_rec_mutasi_'+no).val(0);
            }else if(rec_mutasi == mutasi){
              $('#sts_rec_mutasi_'+no).val('CLOSE');
            }else if(rec_mutasi < mutasi){
              $('#sts_rec_mutasi_'+no).val('OPEN');
            }else if(rec_mutasi == ""){
              $('#sts_rec_mutasi_'+no).val('OPEN');
              $('#qty_rec_mutasi_'+no).val(0);
            }
        }else{
            var ang = $('#qty_rec_mutasi_'+no).val();
            $('#qty_rec_mutasi_'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }
    function saverecmutasi(){
      var formdata = $("#form-header-rec-mutasi,#form-detail-rec-mutasi").serialize();
      $.ajax({
        url: siteurl+"receivingmutasi/savereceivingmutasi",
        dataType : "json",
        type: 'POST',
        data: formdata,
        success: function(result){
          console.log(result);
          if(result.save=='1'){
            swal({
              title: "Sukses!",
              text: result['msg'],
              type: "success",
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(function(){
              window.location.href=siteurl+'receivingmutasi';
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