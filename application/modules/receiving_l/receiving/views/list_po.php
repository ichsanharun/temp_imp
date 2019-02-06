<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    
    <div class="box-body">
        <table id="listpotorec" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th>NO. PO</th>
                <th> Supplier</th>
                <th>Tanggal PO</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
           <?php
           $n=1;
           
           foreach(@$results as $kp => $vp){
            $no = $n++;
           ?> 
           <tr>
             <td><center><?php echo $no?></center></td>
             <td><center><?php echo $vp->no_po?></center></td>
             <td><?php echo $vp->id_supplier?></td>
             <td><center><?php echo date('d/m/Y',strtotime($vp->tgl_po))?></center></td>
             <td style="text-align: center">
                <a href="<?= base_url("receiving/konfrimasi/$vp->id_supplier/$vp->no_po") ?>">Confrim</a>
            </td>
           </tr>
           <?php } ?>
        </tbody>
        </table>
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