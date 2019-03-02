<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form>
<?php
if (isset($_GET['awal'])) {
	$awal=$_GET['awal'];
    $akhir=$_GET['akhir'];
} else {
	$awal=date('Y-m-d');
    $akhir=date('Y-m-d');
}

?>
<div class="box">
    <div class="box-header text-left">
      <div class="form-inline">
        <div class="form-group">
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" style="width: 300px;" required="" name="awal" class="form-control input-sm datepicker" value="<?php echo $awal?>">
          </div>
          <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input type="text" style="width: 300px;" required="" name="akhir" class="form-control input-sm datepicker" value="<?php echo $akhir?>">
          </div>
          <button type="submit" class="btn btn-md btn-warning" value="Tampilkan">Tampilkan</button> 
          
        </div>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="20">#</th>
                    <th width="100">No. Invoice</th>
                    <th>Costomer</th>
                    <th>Tanggal</th>
                    <th>Salesmen</th>
                    <th>HPP</th>
                    <th>Omzet</th>
                    <th>Laba</th>
                    <th>Margin</th>
                </tr>
            </thead>
    
            <tbody>
                <?php
                $no=0;
                $query = $this->db->query("SELECT * FROM `trans_invoice_header` WHERE `tanggal_invoice` BETWEEN '$awal' AND '$akhir'");

                if ($query->num_rows() > 0)
                {
                   foreach ($query->result() as $row)
                   {
                       $no++;
                       ?>
                       <tr>
                           <td><?= $no ?></td>
                           <td>
                               <?php 
                                    echo $row->no_invoice; 
                               ?>
                           </td>
                           <td>
                               <?php 
                                    echo $row->nm_customer; 
                               ?>
                           </td>
                           <td>
                               <?php 
                                    echo date('d M Y',strtotime($row->tanggal_invoice)); 
                               ?>
                           </td>
                           <td>
                               <?php 
                                    echo $row->nm_salesman; 
                               ?>
                           </td>
                           <td class="text-right">
                               <?php 
                                    echo formatnomor($row->hargalandedtotal); 
                               ?>
                           </td>
                           <td class="text-right">
                               <?php 
                                    echo formatnomor($row->hargajualtotal); 
                               ?>
                           </td>
                           <td class="text-right">
                               <?php 
                                    echo formatnomor($row->hargajualtotal - $row->hargalandedtotal); 
                               ?>
                           </td>
                           <td class="text-right">
                               <?php 
                               $laba=$row->hargajualtotal - $row->hargalandedtotal;
                               if ($row->hargalandedtotal==0) {
                                   $margin=$laba*100;
                               }else {
                                   $margin=($laba/$row->hargalandedtotal)*100;
                               }
                               
                                    echo $margin; 
                               ?>
                               
                           </td>
                       </tr>
                       <?php
                   }
                } 
                ?>
            </tbody>
        
        </table>
    </div>
    <!-- /.box-body -->
</div>

</form>

<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
    });
</script>
<script type="text/javascript">

     $(function() {
    var dataTable = $('#example1').DataTable({
          "serverSide": false,
          "stateSave" : false,
          "bAutoWidth": true,
          "searching": false,
          "bLengthChange" : false,
          "bPaginate": false,
          "aaSorting": [[ 0, "asc" ]],
          "columnDefs": [ 
              {"aTargets":[0], "sClass" : "column-hide"},
              {"aTargets": 'no-sort', "orderable": false}
          ],
          "sPaginationType": "simple_numbers", 
          "iDisplayLength": 10,
          "aLengthMenu": [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]]
      });
    /*
      var dataTable = $("#example1").DataTable(
        "bAutoWidth": true
        ).draw();
        */
    });
 </script>