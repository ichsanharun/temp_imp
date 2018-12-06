<style>
  .button-list:hover td{
    cursor: pointer;
    border: solid 1px #dfdfdf !important;

  }
  .label_call{
    text-transform: none;
    text-decoration: none !important;
    font-weight: normal;
    text-align: left;
  }
  .label_call:hover{
    text-decoration: underline !important;
    color: #235a81;;
    cursor: pointer;
  }
  .label_call:hover #get_all{
    color: #235a81;;
  }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
    <div class="box-body">
        <?php //print_r($detail)?>
        <form id="form-detail-so-pending" method="post">
          <input type="hidden" name="input_edit" id="input_edit">
        <table id="list_to_add" class="table table-bordered table-striped table-hover dataTable" style="width:100% !important;color:#000">
        <thead>
            <tr>
              <th class="text-right">
                #
              </th>
              <th>Kode Produk</th>
              <th>Nama Set</th>
              <th>Jenis Produk</th>
              <th>Satuan</th>
              <th>Qty Stock</th>
              <th>Qty Available</th>
              <th>Qty Rusak</th>
              <th>Landed Cost</th>
              <th>Harga</th>
              <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n=0;
            foreach($itembarang as $key=>$record){
                $no=$n++;


            ?>
            <tr class="button-list" onclick="get('<?= $record->id_barang?>')" id="got_<?= $record->id_barang?>">
              <td class="text-center">
                <input type="checkbox" id="get_<?php echo $record->id_barang?>" name="id[]" class="checkbox_opsi" value="<?php echo $record->id_barang?>">
                <?php echo $n?>
              </td>
      	      <td><?= $record->id_barang ?></td>
        			<td><?= $record->nm_barang ?></td>
        			<td><?= strtoupper($record->jenis) ?></td>
        			<td><?= $satuan ?></td>
        			<td><?= $record->qty_stock ?></td>
        			<td><?= $record->qty_avl ?></td>
        			<td><?= $record->qty_rusak ?></td>
        			<td><?= number_format($record->landed_cost) ?></td>
        			<td><?= number_format($record->harga) ?></td>
        			<td>
        				<?php if($record->sts_aktif == 'aktif'){ ?>
        					<label class="label label-success">Aktif</label>
        				<?php }else{ ?>
        					<label class="label label-danger">Non Aktif</label>
        				<?php } ?>
        			</td>
            </tr>
          <?php }?>
        </tbody>
        </table>
        </form>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">

var oTable = $('#list_to_add').dataTable();
  function get(id){
    if (document.getElementById('get_'+id).checked == true) {
      $('#get_'+id).prop('checked', false);
    }
    else{
      $('#get_'+id).prop('checked', true);
    }
    var oTable = $('#list_to_add').dataTable();
    var arr = [];
    var e = oTable.$(".checkbox_opsi:checked", {"page": "all"});
    e.each(function(index,elem,o) {
      arr.push($(elem).val());
    });

    $('#input_edit').val(arr.join(';'));
  }

</script>
