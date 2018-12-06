<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<?php
    $ENABLE_ADD     = has_permission('Setup_stock.Add');
    $ENABLE_MANAGE  = has_permission('Setup_stock.Manage');
    $ENABLE_VIEW    = has_permission('Setup_stock.View');
    $ENABLE_DELETE  = has_permission('Setup_stock.Delete');
?>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
  <div class="box-header">
		<?php if ($ENABLE_ADD) : ?>
			<a class="btn btn-success" href="#dialog-popup" data-toggle="modal" title="Add" onclick="add_list()"><i class="fa fa-plus">&nbsp;</i>Add List</a>

		<?php endif; ?>
	</div>
  <form id="form-adjus" method="post">
    <div class="box-body">
        <div id="div-form">
        <table id="listadjust" class="table table-bordered table-striped" width="100%">
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
                  <th>Tipe Adj.</th>
                  <th>Qty</th>
                  <th>Harga</th>
                </tr>
            </thead>
            <tbody id="isi_adjust">

            </tbody>
            <tfoot id="input_tambahan">

                <tr>
                    <th class="text-right" colspan="11">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="save()">
                            <i class="fa fa-save"></i><b> Simpan Data Adjusment</b>
                        </button>
                    </th>
                </tr>
            </tfoot>

        </table>
        </div>
    </div>
  </form>
</div>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Adjusment Stock</h4>
      </div>
      <div class="modal-body" id="ModalList">
		...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        <button type="button" class="btn btn-warning" onclick="tambah_data()" data-dismiss="modal">
        <span class="glyphicon glyphicon-save"></span> Tambah Data</button>
        </div>
    </div>
  </div>
</div>


<script type="text/javascript">

    $(document).ready(function() {
      var table = $('#listadjust').DataTable();
    });
    function add_list()
    {
      tujuan = siteurl+'adjusment/list_barang';
      $.post(tujuan,function(result){
        $("#ModalList").html(result);
      });
    }
    function tambah_data(){
      var list_mentah = $('#input_edit').val();
      var list_arr = list_mentah.split(";");
      if(list_mentah != ""){
          $.ajax({
              type:"POST",
              url:siteurl+"adjusment/get_item_barang",
              data:"idbarang="+list_mentah,
              success:function(result){
                  var data = JSON.parse(result);
                  var dt = $('#listadjust').DataTable();
                  dt.rows().remove();
                  for (var i = 0; i < list_arr.length; i++) {
                    dt.row.add([
                      i+1,
                      data[list_arr[i]].id_barang,
                      data[list_arr[i]].nm_barang,
                      data[list_arr[i]].jenis,
                      data[list_arr[i]].kategori,
                      data[list_arr[i]].qty_stock,
                      data[list_arr[i]].qty_avl,
                      data[list_arr[i]].qty_rusak,
                      '<select name="tipe['+data[list_arr[i]].id_barang+']" class="form-control input-sm">'+
                      '<option value="IN">IN</option>'+
                      '<option value="OUT">OUT</option>'+
                      '</select>',
                      '<input type="text" name="qty_adjus['+data[list_arr[i]].id_barang+']" class="form-control input-sm">',
                      '<input type="text" name="harga['+data[list_arr[i]].id_barang+']" class="form-control input-sm" value="'+data[list_arr[i]].harga+'">'
                    ]).draw(false);

                  }
              }
          });
      }
    }

    function kembali(){
        window.location.href = siteurl+'adjusment';
    }

    function save(){
        var formdata = $("#form-adjus").serialize();
        $.ajax({
            url: siteurl+"adjusment/save_data_adjus",
            dataType : "json",
            type: 'POST',
            data: formdata,
            success: function(result){
                if(result.save=='1'){
                    swal({
                        title: "Sukses!",
                        text: result['msg'],
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(function(){
                      window.location.href = siteurl+"adjusment/";
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
