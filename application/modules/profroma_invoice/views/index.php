<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">

<div class="box">
    
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th width="50">#</th>
            <th>KODE PO</th>
            <th>KODE PI</th>
            <th>Supplier</th>
            <th width="25">Action</th>
        </tr>
        </thead>

        <tbody>
            <?php if ($results->num_rows() !== 0) {
    $num = 1;
    foreach ($results->result() as $row) : ?>
                <tr>
                    <td><?= $num; ?></td>
                    <td><?= $row->no_po; ?></td>
                    <td><?= $row->no_pi; ?></td>
                    <td><?= $row->id_supplier; ?> - <?php echo $row->nm_supplier; ?></td>
                    <td style="text-align: center">
                        <a href="<?= base_url("profroma_invoice/konfrimasi/$row->id_supplier/$row->no_po"); ?>">Confrim</a>
                    </td>
                </tr>
            <?php ++$num;
    endforeach;
} ?>
        </tbody>

        </table>
    </div>
    <!-- /.box-body -->
</div>

<div id="form-area">
</div>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js');?>"></script>

<!-- page script -->
<script type="text/javascript">

    $(function() {
        $("#example1").DataTable();
        $("#form-area").hide();
    });

    function add_data(){
        var url = 'cabang/create/';
        $(".box").hide();
        $("#form-area").show();
        $("#form-area").load(siteurl+url);
        $("#title").focus();
    }

    function edit_data(id){
        console.log(id);
        if(id!=""){
            var url = 'cbm/edit/'+id;
            $(".box").hide();
            $("#form-area").show();
            $("#form-area").load(siteurl+url);
            $("#title").focus();
        }
    }

    //Delete
    function delete_data(id){
        //alert(id);
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
          if (isConfirm) {
            $.ajax({
                    url: siteurl+'cbm/delete/'+id,
                    dataType : "json",
                    type: 'POST',
                    success: function(msg){
                        if(msg['delete']=='1'){                         
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            window.location.reload();
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

    function PreviewPdf(id)
    {
        param=id;
        tujuan = 'customer/print_request/'+param;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="570" height="400"></iframe>');
    }
    
    $('#frm_cbm').on('submit', function(e){
        e.preventDefault();
        
        var formdata = $("#frm_cbm").serialize();
        $.ajax({
            url: siteurl+"cbm/save",
            dataType : "json",
            type: 'POST',
            data: formdata,
            //alert(msg);
            success: function(msg){
                if(msg['save']=='1'){
                    document.getElementById("myModal").style.display="none";
                    swal({
                        title: "Sukses!",
                        text: "Data Berhasil Di Simpan",
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    window.location.reload();
                } else {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Di Simpan",
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                };//alert(msg);
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
    });

</script>
