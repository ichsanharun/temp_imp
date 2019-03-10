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
<div class="row">
  <div class="col-md-12">
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
                    <input type="checkbox" id="get_<?php echo $record->id_barang?>" name="id[]" class="checkbox_opsi" value="<?php echo $record->id_barang.";".$record->nm_barang.";".$record->qty_stock.";".$record->qty_avl?>">
                    <input type="hidden" id="nm_barang_<?php echo $record->id_barang?>" name="nm_barang[<?php echo $record->id_barang?>]" value="<?php echo $record->nm_barang?>">
                    <input type="hidden" id="qty_stock_<?php echo $record->id_barang?>" name="qty_stock[<?php echo $record->id_barang?>]" value="<?php echo $record->nm_barang?>">
                    <input type="hidden" id="qty_avl_<?php echo $record->id_barang?>" name="qty_avl[<?php echo $record->id_barang?>]" value="<?php echo $record->nm_barang?>">
                    <input type="hidden" id="id_barang_<?php echo $record->id_barang?>" name="id_barang[<?php echo $record->id_barang?>]" value="<?php echo $record->nm_barang?>">
                    <?php echo $n?>
                  </td>
          	      <td><?= $record->id_barang ?></td>
            			<td><?= $record->nm_barang ?></td>
            			<td><?= strtoupper($record->jenis) ?></td>
            			<td><?= $satuan ?></td>
            			<td><?= $record->qty_stock ?></td>
            			<td><?= $record->qty_avl ?></td>
            			<td><?= number_format($record->harga) ?></td>
            			<td>

            					<label id="label_<?php echo $record->id_barang?>" class="label label-danger">Tidak Terpilih</label>

            			</td>
                </tr>
              <?php }?>
            </tbody>
            </table>
            </form>
        </div>
    </div>
  </div>

</div>
<div class="row">
  <div class="col-md-12">
    <div class="box">
        <div class="box-body">
            <?php //print_r($detail)?>
            <form id="form-detail" method="post">

            <table id="list_added" class="table table-bordered table-striped table-hover dataTable" style="width:100% !important;color:#000">
            <thead>
                <tr>
                  <th class="text-right">
                    #
                  </th>
                  <th>Kode Produk</th>
                  <th>Nama Set</th>
                  <th>Qty Stock</th>
                  <th>Qty Available</th>
                  <th>Harga</th>
                  <th>Qty Order</th>
                  <th>Diskon SO</th>

                </tr>
            </thead>
            <tbody id="list_added_tbody">

            </tbody>
            </table>
            </form>
        </div>
    </div>
  </div>

</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script type="text/javascript">

var Table = $('#list_to_add,#list_added').dataTable( {
    pageLength : 5,
    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']]
  } );
  function get(id){
    if (document.getElementById('get_'+id).checked == true) {
      $('#get_'+id).prop('checked', false);
      $('#label_'+id).html('Tidak Terpilih');
      $('#label_'+id).removeClass('label-success');
      $('#label_'+id).removeClass('label-danger');
      $('#label_'+id).addClass('label-danger');
    }
    else{
      $('#get_'+id).prop('checked', true);
      $('#label_'+id).html('Terpilih');
      $('#label_'+id).removeClass('label-success');
      $('#label_'+id).removeClass('label-danger');
      $('#label_'+id).addClass('label-success');
    }
    var oTable = $('#list_to_add').dataTable();
    var tabdua = $('#list_added').DataTable();
    var arr = [];
    var i = 1;
    var e = oTable.$(".checkbox_opsi:checked", {"page": "all"});
    tabdua.rows().remove();
    if (true) {

    }
    e.each(function(index,elem,o) {
      arr.push($(elem).val());
      var arr_poin = $(elem).val().split(";");
      //alert(arr_poin[0]);
      tabdua.row.add([
        i++,
        arr_poin[0],
        arr_poin[1],
        arr_poin[2],
        arr_poin[3],
        '<input type="text" name="qty_order['+$(elem).val()+']" class="form-control input-sm">',
        '<input type="text" name="diskon_so['+$(elem).val()+']" class="form-control input-sm" value="">',
        '<div class="input-group">'+
          '<div class="input-group-btn">'+
            '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="tipe_disso" name="tipe_disso" value="%">%<span class="caret"></span></button>'+
            '<ul class="dropdown-menu bg-dark">'+
              '<li><a href="javascript:void(0)" onclick="getdisso("%")">Persen (%)</a></li>'+
              '<li><a href="javascript:void(0)" onclick="getdisso("Rp")">Rupiah (Rp)</a></li>'+
            '</ul>'+
          '</div><!-- /btn-group -->'+
          '<input type="text" class="form-control form_item_so" aria-label="" name="disso" id="disso" class="input-sm" value="0">'+
        '</div><!-- /input-group -->'+
        '<div class="radio_disso_rp">'+
          '<div class="radio-inline">'+
            '<label>'+
              '<input type="radio" value="tambah" name="radio_disso_rp">(+)'+
            '</label>'+
          '</div>'+
          '<div class="radio-inline">'+
            '<label>'+
              '<input type="radio" value="kurang" name="radio_disso_rp">(-)'+
            '</label>'+
          '</div>'+
        '</div>'
      ]).draw();
    });

    $('#input_edit').val(arr.join(';'));
    if ($('#input_edit').val() == "") {
      $('#list_added').DataTable().clear().draw();
      //alert('"'+$('#input_edit').val()+'"');
    }
  }

</script>
