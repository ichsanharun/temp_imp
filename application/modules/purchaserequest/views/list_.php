<?php
    $ENABLE_ADD     = has_permission('Purchaserequest.Add');
    $ENABLE_MANAGE  = has_permission('Purchaserequest.Manage');
    $ENABLE_VIEW    = has_permission('Purchaserequest.View');
    $ENABLE_DELETE  = has_permission('Purchaserequest.Delete');
?>
<div class="box">
    <div class="box-header">
        <?php if ($ENABLE_ADD) : ?>
            <a class="btn btn-success" href="javascript:void(0)" title="Add" onclick="add_data()"><i class="fa fa-plus">&nbsp;</i>New</a>
        <?php endif; ?>

        <span class="pull-right">
                <?php //echo anchor(site_url('customer/downloadExcel'), ' <i class="fa fa-download"></i> Excel ', 'class="btn btn-primary btn-sm"'); ?>
        </span>
    </div>
    <div class="box-body">
                <link rel="stylesheet" href="<?= base_url()?>assets/datatables/css/dataTables.bootstrap.css"/>
                <table id="my-grid" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 55px">No.</th>
                            <th class='no-sort'>NO.PR</th>
                            <th class='no-sort'>Cabang</th>
                            <th class='no-sort'>Tgl. PR</th>
                            <th class='no-sort'>Plan Delivery</th>
                            <th class='no-sort'>Nama Supplier</th>
                            <th class='no-sort' style="width: 70px">Aksi</th>
                        </tr>
                    </thead>
                </table>
      
    </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Purchase Order (PR)</h4>
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
<?php
$tambahan = '';
?>

<script type="text/javascript" language="javascript" >
    $(document).ready(function() {
        var dataTable = $('#my-grid').DataTable( {
            "serverSide": true,
            "stateSave" : false,
            "bAutoWidth": true,
            "oLanguage": {
                "sSearch": "<i class='fa fa-search fa-fw'></i> &nbsp; Pencarian : ",
                "sLengthMenu": "_MENU_ &nbsp;&nbsp;Data Per Halaman <?php echo $tambahan; ?>",
                "sInfo": "Menampilkan _START_ s/d _END_ dari <b>_TOTAL_ data</b>",
                "sInfoFiltered": "(difilter dari _MAX_ total data)", 
                "sZeroRecords": "Pencarian tidak ditemukan", 
                "sEmptyTable": "Data kosong", 
                "sLoadingRecords": "Harap Tunggu...", 
                "oPaginate": {
                    "sPrevious": "Prev",
                    "sNext": "Next"
                }
            },
            "aaSorting": [[ 0, "desc" ]],
            "columnDefs": [ 
                {
                    "targets": 'no-sort',
                    "orderable": false,
                },
                {
                    "targets": [1, 3, 4, 6],
                    "className": "text-center",
                }
            ],
            "sPaginationType": "simple_numbers", 
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]],
            "ajax":{
                url : siteurl+"purchaserequest/list_pr_json",
                type: "post",
                error: function(){ 
                    $(".my-grid-error").html("");
                    $("#my-grid").append('<tbody class="my-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#my-grid_processing").css("display","none");
                }
            }
        } );
        
    });


 

    
</script>

<script type="text/javascript">
    function add_data(){
        window.location.href = siteurl+"purchaserequest/new_create";
    }
    function PreviewPdf(nopr)
    {
      tujuan = 'purchaserequest/print_request/'+nopr;

        $(".modal-body").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }
</script>
<script type="text/javascript" language="javascript" src="<?= base_url()?>assets/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="<?= base_url()?>assets/datatables/js/dataTables.bootstrap.js"></script>