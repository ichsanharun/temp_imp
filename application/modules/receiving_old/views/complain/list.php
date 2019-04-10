<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box">
   
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="2%">#</th>
                <th>NO. PO</th>
                <th>Supplier</th>
                <th>No Container</th>
                <th>Tgl Receiving</th>
                <th>Status</th>
                <th width="10%">Aksi</th>
            </tr>
        </thead>
        <tbody>
           <?php
           $session = $this->session->userdata('app_session');
           $kd_cab=$session['kdcab'];
           
           $no=0;
           $query = $this->db->query("SELECT * FROM `receive_detail_barang` as a, trans_receive as b WHERE a.no_po=b.po_no AND b.kdcab='$kd_cab' AND a.rusak !='0' GROUP BY a.no_po " );

            foreach ($query->result() as $row)
            {
                $no ++;
           ?>
           <tr>
             <td><center><?php echo $no?></center></td>
             <td><center><?php echo $row->no_po?></center></td>
             <td><?php echo $row->id_supplier?></td>
             <td><?php echo $row->container_no?></td>
             <td><center><?php echo date('d/m/Y',strtotime($row->tglreceive))?></center></td>
             <td>
                 <?php
                 if ($row->status==0) {
                     echo "Proses";
                 }elseif ($row->status==1) {
                     echo "Diterima";
                 } 
                 else {
                     echo "Selesai";
                 }
                 
                 ?>
             </td>
             <td>
                <center>
                 <?php
                 if ($row->status==1) {
                     ?>
                     <a href="<?= base_url("receiving/receiving_complain/receiving/$row->no_po") ?>">Receiving</a> &nbsp;&nbsp;&nbsp;
                     <?php
                 }
                 ?>
                <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdfzx('<?php echo $row->no_po?>')">
                            <span class="glyphicon glyphicon-print"></span>
                        </a>
                </center>
             </td>
           </tr>
           <?php } ?>
          
        </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 1000px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;COMPLAIN</h4>
      </div>
      <div class="modal-body" style="width: 1000px">
    ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
    $(function() {
      var dataTable = $("#example1").DataTable();
    });
    function PreviewPdfzx(norec)
    {
        
      tujuansss = 'receiving_complain/print_complain/'+norec;
        $(".modal-body").html('<iframe src="'+tujuansss+'" frameborder="no" width="100%" height="400"></iframe>');
    }
    
</script>
