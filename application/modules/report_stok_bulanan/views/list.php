<?php
    $ENABLE_ADD     = has_permission('Report_stok_bulanan.Add');
    $ENABLE_MANAGE  = has_permission('Report_stok_bulanan.Manage');
    $ENABLE_VIEW    = has_permission('Report_stok_bulanan.View');
    $ENABLE_DELETE  = has_permission('Report_stok_bulanan.Delete');
?>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<section id="up">
<div class="box">

    <div class="col-lg-12">
      <div class="box-header text-left"><b>Pilih Periode : </b>
        <?php
        $session = $this->session->userdata('app_session');
        $periode_th = $this->db->where('kdcab = '.$session["kdcab"].' AND bulan is not null')->group_by(array("bulan","tahun"))->get('barang_stock_bulanan')->result();

        //print_r($periode_th);
         ?>
        <select id="periode" name="periode" class="form-control input-sm" style="width: 25%;" tabindex="-1" required>
            <option value=""></option>
            <?php
            foreach($periode_th as $kso=>$vso){
              $selected = '';
              ?>
              <option value="<?php echo date("m/Y", strtotime($vso->tahun."-".$vso->bulan)); ?>" <?php echo $selected?>>
            <?php echo date("M Y", strtotime($vso->tahun."-".$vso->bulan)); ?>
            <?php } ?>
            <option value="All">All</option>
        </select>
        <?php if ($ENABLE_VIEW) : ?>
    			<span class="pull-right">

            <a class="btn btn-primary btn-sm" title="Excel" onclick="getexcel()"><i class="fa fa-download">&nbsp;</i>Excel  </a>
    			</span>
    		<?php endif; ?>
      </div>
    </div>

    <div class="box-body">
  		<table id="example1" class="table table-bordered table-striped">
  		<thead>
  		<tr>
  			<th width="5">#</th>
        <th>Cabang</th>
  			<th>Kode Produk</th>
  			<th>Nama Set</th>
  			<th>Jenis Produk</th>
  			<th>Satuan</th>
  			<th>Qty Stock</th>
  			<th>Qty Available</th>
  			<th>Qty Rusak</th>
  			<th>Landed Cost</th>
  			<th>Periode</th>
  			<th>Status</th>

  		</tr>
  		</thead>

  		<tbody>
  		<?php
  		//print_r($results);
  		if(empty($results)){
  		}else{
  			$numb=0; foreach($results AS $key => $record){ $numb++; ?>
  		<tr>
  			<?php
  				if($record->satuan==''){
  					$satuan = $record->setpcs;
  				}else{
  					$satuan = $record->satuan;
  				}
  			?>
  		    <td><?= $numb; ?></td>
          <td><?= $record->kdcab." , ".$record->namacabang ?></td>
  	    <td><?= $record->id_barang ?></td>
  			<td><?= $record->nm_barang ?></td>
  			<td><?= strtoupper($record->jenis) ?></td>
  			<td><?= $satuan ?></td>
  			<td><?= $record->qty_stock ?></td>
  			<td><?= $record->qty_avl ?></td>
  			<td><?= $record->qty_rusak ?></td>
  			<td><?= number_format($record->landed_cost) ?></td>
  			<td><?= date("m/Y", strtotime($record->tahun."-".$record->bulan)) ?></td>
  			<td>
  				<?php if($record->sts_aktif == 'aktif'){ ?>
  					<label class="label label-success">Aktif</label>
  				<?php }else{ ?>
  					<label class="label label-danger">Non Aktif</label>
  				<?php } ?>
  			</td>

  		</tr>
  		<?php } }  ?>
  		</tbody>

  		<tfoot>
  		<tr>
  			<th width="5">#</th>
        <th>Cabang</th>
  			<th>Kode Produk</th>
  			<th>Nama Set</th>
  			<th>Jenis Produk</th>
  			<th>Satuan</th>
  			<th>Qty Stock</th>
  			<th>Qty Available</th>
  			<th>Qty Rusak</th>
  			<th>Landed Cost</th>
  			<th>Periode</th>
  			<th>Status</th>
  			<?php if($ENABLE_MANAGE) : ?>

  			<?php endif; ?>
  		</tr>
  		</tfoot>
  		</table>
  	</div>
</div>
</section>



<!-- Modal -->
<div class="modal modal-primary" id="dialog-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" id="repso">
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
  $(document).ready(function(){
      $("#type_so").select2({
          placeholder: "Pilih",
          allowClear: true
      });
      $("#periode").select2({
          placeholder: "Pilih",
          allowClear: true
      });
      var table = $('#example1').DataTable();
      $('#periode').on( 'change', function () {
        if (this.value == "All") {
          var value = "";
        }else {
          var value = this.value;
        }
      table.search( value ).draw();
      } );
      var tabledetail = $('#detailreportso').DataTable();
      // Add smooth scrolling to all links
      $("a").on('click', function(event) {

        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
          // Prevent default anchor click behavior
          event.preventDefault();

          // Store hash
          var hash = this.hash;

          // Using jQuery's animate() method to add smooth page scroll
          // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
          $('html, body').animate({
            scrollTop: $(hash).offset().top
          }, 800, function(){

            // Add hash (#) to URL when done scrolling (default click behavior)
            //window.location.hash = hash;
          });
        } // End if
      });
  });
  function gettype(){
        var type_so = $('#type_so').val();
        window.location.href = siteurl+"reportso/filter/"+type_so;
  }
  function getdetail(a){
        var detail = $('#detail_'+a).val();
        var tabledetail = $('#detailreportso').DataTable();
        //alert(detail);
        tabledetail.column(1).search(a).draw();
        //window.location.href = siteurl+"reportso/filter/"+type_so;
      }

      function getexcel(){
            var tgl = $('#periode').val();
            console.log(tgl);
            if (tgl == "All") {
              var tglso = "All";
              var t = "All";
              var b = "All";
            }else {
              var explode = tgl.split('/');
              var tglso = explode[1].concat('-',explode[0]);
              var t = explode[1];
              var b = explode[0];
            }
            var uri3 = '<?php echo $this->uri->segment(3)?>';
            var uri4 = '<?php echo $this->uri->segment(4)?>';
            //window.location.href =
            window.location.href = siteurl+"report_stok_bulanan/downloadExcel_old/"+t+"/"+b;
            //$('#myModalLabel').html('<span class="fa fa-file-pdf-o"></span> Sales Order (SO)');
            //param=noso;
            //tujuan = siteurl+'reportso/print_request/'+param;

              //$("#repso").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
          }

    function PreviewPdf(noso)
    {
      $('#myModalLabel').html('<span class="fa fa-file-pdf-o"></span> Sales Order (SO)');
      param=noso;
      tujuan = siteurl+'reportso/print_request/'+param;

        $("#repso").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }

</script>
