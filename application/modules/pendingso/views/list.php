<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th width="15%">NO. CO</th>
                <th width="10%">Tanggal SO</th>
                <th width="5%">Status</th>
                <th>Nama Customer</th>
                <th width="10%"><center>Total Order</center></th>
                <th width="10%"><center>Total Confirm</center></th>
                <th width="10%"><center>Total Pending</center></th>
                <th width="10%"><center>Total Cancel</center></th>
                <th width="15%"><center>Total<br>Available</center></th>
                <th width="10%"><center>Aksi</center></th>
            </tr>
        </thead>
        <tbody>
            <?php if(@$results){ ?>
            <?php 
            $n = 1;
            foreach(@$results as $kso=>$vso){ 
                $no = $n++;
                $st = "OPEN";
                if($vso->stsorder == "CLS"){
                    $st = "CLOSE";
                }
                
                $getdetail = $this->Salesorder_model->get_data(array('no_so'=>$vso->no_so),'trans_so_detail');
                $c='';
                $stock_avl = 0;
                foreach($getdetail as $k=>$v){
                  $getstokavl = $this->Salesorder_model->cek_data(array('id_barang'=>$v->id_barang),'barang_stock');
                  $stock_avl += $getstokavl->qty_avl;
                  $c .= $getstokavl->qty_avl.' + ';
                }
            ?>
            <tr>
                <td class="text-center"><?php echo $no?></td>
                <td class="text-center"><?php echo $vso->no_so?></td>
                <td class="text-center"><?php echo date('d-M-Y',strtotime($vso->tanggal))?></td>
                <td class="text-center"><?php echo $st?></td>
                <td><?php echo $vso->nm_customer?></td>
                
                <td class="text-center"><?php echo $vso->qty_order?></td>
                <td class="text-center"><?php echo $vso->qty_supply?></td>
                <td class="text-center"><?php echo $vso->qty_pending?></td>
                <td class="text-center"><?php echo $vso->qty_cancel?></td>
                <td class="text-center"><?php echo $stock_avl?></td>
                <td class="text-center">
                    <a href="#" data-toggle="modal" onclick="prosespendingso('<?php echo $vso->no_so?>')" title="Proses">
                    <span class="glyphicon glyphicon-share"></span>
                    </a>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
              <th width="2%">#</th>
                <th width="15%">NO. CO</th>
                <th width="10%">Tanggal SO</th>
                <th width="5%">Status</th>
                <th>Nama Customer</th>
                <th width="10%"><center>Total Order</center></th>
                <th width="10%"><center>Total Confirm</center></th>
                <th width="10%"><center>Total Pending</center></th>
                <th width="10%"><center>Total Cancel</center></th>
                <th width="15%"><center>Total<br>Available</center></th>
                <th width="10%"><center>Aksi</center></th>
            </tr>
        </tfoot>
        </table>
    </div>
</div>
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
    $(function() {
        $("#example1").DataTable();
    });
    function prosespendingso(noso){
        window.location.href = siteurl+"pendingso/proses/"+noso;
    }
</script>