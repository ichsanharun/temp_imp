<?php
    $ENABLE_ADD     = has_permission('Reportso.Add');
    $ENABLE_MANAGE  = has_permission('Reportso.Manage');
    $ENABLE_VIEW    = has_permission('Reportso.View');
    $ENABLE_DELETE  = has_permission('Reportso.Delete');
?>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<section id="up">
<div class="box">
    <div class="col-lg-6">
      <div class="box-header text-left"><b>Pilih Status Order : </b>
        <select id="type_so" name="type_so" class="form-control input-sm" style="width: 25%;" tabindex="-1" required onchange="gettype()">
            <option value=""></option>
            <?php
              $cab='';
              foreach(@$type as $kso=>$vso){
                echo ".".$kso;
                $selected = '';
                if($this->uri->segment(3) == $vso['kdtype']){
                     $selected = 'selected="selected"';
                     //$typ = $vso->nmtype;
                }
                ?>
            <option value="<?php echo $vso['kdtype']; ?>" <?php echo $selected?>>
              <?php echo $vso['kdtype'] ?>
            </option>
            <?php } ?>
        </select>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="box-header text-left"><b>Pilih Periode : </b>
        <select id="periode" name="periode" class="form-control input-sm" style="width: 25%;" tabindex="-1" required>
            <option value=""></option>
            <?php
              for ($i=1; $i < 13; $i++) {
                if ($i < 10) {
                  $bln = "0".$i;
                  $per = date("Y")."-0".$i;
                  $valper = "0".$i."/".date("Y");
                }else {
                  $bln = $i;
                  $per = date("Y")."-".$i;
                  $valper = $i."/".date("Y");
                }
                if (date("m") == $bln) {
                  $selected = 'selected="selected"';
                }
                ?>
            <option value="<?php echo $valper; ?>" id="<?php echo $per; ?>">
              <?php echo date("M Y", strtotime($per)); ?>
            </option>
            <?php } ?>
            <option value="<?php echo "/".date("Y"); ?>"><?php echo date("Y"); ?></option>
        </select>
        <?php if ($ENABLE_VIEW) : ?>
    			<span class="pull-right">

            <a class="btn btn-primary btn-sm" title="Excel" onclick="getexcel()"><i class="fa fa-download">&nbsp;</i>Excel  </a>
    				<!--a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a-->
    			</span>
    		<?php endif; ?>
      </div>
    </div>

    <div class="box-body">
        <table id="reportso" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th>NO. SO</th>
              <th>Nama Customer</th>
              <th>Tanggal</th>
              <th>Nama Salesman</th>
              <th>Total</th>
              <th width="5%">Status</th>
              <th>Aksi</th>
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
                <td><?php echo $vso->nm_customer?></td>
                <td class="text-center"><?php echo date('d/m/Y',strtotime($vso->tanggal))?></td>
                <td><?php echo $vso->nm_salesman?></td>
                <td class="text-right"><?php echo formatnomor($vso->total)?></td>
                <td class="text-center"><span class="badge <?php echo $badge?>"><?php echo $sts?></span></td>
                <td class="text-center"><span class="badge"><a href="#detail" id="detail_<?php echo $vso->no_so?>" onclick="getdetail('<?php echo $vso->no_so?>')" style="text-decoration:none !important;color:#fff">Detail</a></span></td>
                <!--td class="text-right">
                  <?php if($ENABLE_VIEW) { ?>
                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vso->no_so?>')">
                  <span class="glyphicon glyphicon-print"></span>
                  </a>
                  <?php } ?>
                </td-->

            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>
</section>

<div class="box">
<section id="detail">
    <div class="col-lg-11" >
      <div class="box-header text-left"><h3>List Detail SO : </h3>
      </div>
    </div>

    <div class="col-lg-1" >
      <div class="box-header">
      <?php if ($ENABLE_VIEW) : ?>
        <span class="pull-right mt-3">

          <a class="btn btn-primary btn-sm" title="Up" href="#up"><i class="fa fa-upload">&nbsp;</i>Kembali ke atas  </a>
          <!--a class="btn btn-primary btn-sm" data-toggle="modal" href="#dialog-rekap" title="Pdf" onclick="PreviewRekap()"><i class="fa fa-print">&nbsp;</i>PDF</a-->
        </span>
      <?php endif; ?>
      </div>
    </div>
    <div class="box-body">
        <table id="detailreportso" class="table table-bordered table-striped">
        <thead>
          <tr>
              <th width="2%">#</th>
              <th>NO. SO</th>
              <th>Nama Product</th>
              <th>Satuan</th>
              <th>Qty Order</th>
              <th>Qty Pending</th>
              <th>Qty Cancel</th>
              <th>Qty Confirm</th>
              <th>Qty Supply</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
            <?php if(@$detail){ ?>
            <?php
            $n = 1;
            foreach(@$detail as $kso=>$vso){
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
                <td><?php echo $vso->nm_barang?></td>
                <td class="text-center"><?php echo $vso->satuan?></td>
                <td><?php echo $vso->qty_order?></td>
                <td><?php echo $vso->qty_pending?></td>
                <td><?php echo $vso->qty_cancel?></td>
                <td><?php echo $vso->qty_booked?></td>
                <td><?php echo $vso->qty_supply?></td>
                <td><?php echo $vso->harga?></td>
                <td><?php echo $vso->diskon?></td>
                <td><?php echo $vso->subtotal?></td>
                <!--td class="text-right">
                  <?php if($ENABLE_VIEW) { ?>
                  <a href="#dialog-popup" data-toggle="modal" onclick="PreviewPdf('<?php echo $vso->no_so?>')">
                  <span class="glyphicon glyphicon-print"></span>
                  </a>
                  <?php } ?>
                </td-->

            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
        </table>
    </div>
</section>
</div>

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
      var table = $('#reportso').DataTable();
      $('#periode').on( 'change', function () {
      table.search( this.value ).draw();
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
            var explode = tgl.split('/');
            var tglso = explode[1].concat('-',explode[0]);
            var uri3 = '<?php echo $this->uri->segment(3)?>';
            var uri4 = '<?php echo $this->uri->segment(4)?>';
            window.location.href = siteurl+"reportso/downloadExcel/"+uri3+"/"+tglso;
          }

    function PreviewPdf(noso)
    {
      $('#myModalLabel').html('<span class="fa fa-file-pdf-o"></span> Sales Order (SO)');
      param=noso;
      tujuan = siteurl+'reportso/print_request/'+param;

        $("#repso").html('<iframe src="'+tujuan+'" frameborder="no" width="100%" height="400"></iframe>');
    }

</script>
