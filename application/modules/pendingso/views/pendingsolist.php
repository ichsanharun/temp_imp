<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-header">
            <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
        
    </div>
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th>NO. SO</th>
                <th>NO. SO Pending</th>
                <th>Nama Customer</th>
                <th>Tanggal</th>
                <th>Nama Salesman</th>
                <!--<th>Total</th>
                <th width="5%">Status</th>-->
                <th width="5%">Picking</th>
            </tr>
        </thead>
        <tbody>
            <?php if(@$results){ ?>
            <?php 
            $n = 1;
            foreach(@$results as $kso=>$vso){ 
                $no = $n++;
                $sts = "OPEN";
                $badge = "bg-green";
                $disbtn = '';
                if($vso->stsorder == "CLS"){
                  $sts = "CLOSE";
                  $disbtn = 'style="cursor: not-allowed;';
                  //$disbtn = 'disabled="disabled"';
                  $badge = "bg-red";
                }
            ?>
            <tr>
                <td class="text-center"><?php echo $no?></td>
                <td class="text-center"><?php echo $vso->no_so?></td>
                <td class="text-center"><?php echo $vso->no_so_pending?></td>
                <td><?php echo $vso->nm_customer?></td>
                <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tanggal))?></td>
                <td><?php echo $vso->nm_salesman?></td>
                <td class="text-right">
                  <center>
                    <a href="#dialog-popup" data-toggle="modal" onclick="PickingList('<?php echo $vso->no_so_pending?>')" title="Picking List Pending">
                    <span class="glyphicon glyphicon-file"></span>
                  </center>
                    <!--
                    <select class="form-control input-sm width-100" id="status_so" onchange="setstatusso()">
                      <option value="0">-</option>
                      <option value="1">Konfirm</option>
                      <option value="2">Pending</option>
                      <option value="3">Cancel</option>
                    </select>
                    -->
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
            <th width="50">#</th>
            <th>NO. SO</th>
            <th>NO. SO Pending</th>
            <th>Nama Customer</th>
            <th>Tanggal</th>
            <th>Nama Salesman</th>
            <th>Picking</th>
        </tr>
        </tfoot>
        </table>
    </div>
</div>
<div id="form-area">
<?php //$this->load->view('salesorder/salesorder_form') ?>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
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
        $("#example1").DataTable();
        $("#form-area").hide();
    });
    function add_data(){
        window.location.href = siteurl+"pendingso/newpendingso";
    }
    
    function PickingList(no_so_pending)
    {
      $('#myModalLabel').html('<span class="fa fa-file-pdf-o"></span> Picking List Pending SO');
      tujuan = 'pendingso/print_picking_list/'+no_so_pending;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>