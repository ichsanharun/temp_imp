<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<form>
<?php
if (isset($_GET['awal']) || isset($_GET['awal1'])) {
	$awal=$_GET['awal'];
    $akhir=$_GET['akhir'];
	$awal1=$_GET['awal1'];
	  $akhir1=$_GET['akhir1'];
} else {
	$awal=date('Y-m-d');
    $akhir=date('Y-m-d');
	$awal1='2000-01-01';
	  $akhir1=date('Y-m-d');

}
$sub_hpp_all =0;
$sub_omzet_all =0;
$sub_laba_all =0;
$sub_margin_all =0;
$sub_hpp =0;
$sub_omzet =0;
$sub_laba =0;
$sub_margin =0;
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#ALL" data-toggle="tab" aria-expanded="true" id="data_all">Laba Kotor</a></li>
        <li class=""><a href="#GROUP" data-toggle="tab" aria-expanded="false" id="data_group">By Group</a></li>

    </ul>
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="ALL">
            <!-- form start-->
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
														$kdcab = $this->auth->user_cab();
						                $query = $this->db->query("SELECT * FROM `trans_invoice_header` WHERE `tanggal_invoice` BETWEEN '$awal' AND '$akhir' AND `kdcab` = '$kdcab'");

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
																								$sub_hpp_all += $row->hargalandedtotal;
						                               ?>
						                           </td>
						                           <td class="text-right">
						                               <?php
						                                    echo formatnomor($row->hargajualtotal);
																								$sub_omzet_all += $row->hargajualtotal;
						                               ?>
						                           </td>
						                           <td class="text-right">
						                               <?php
						                                    echo formatnomor($row->hargajualtotal - $row->hargalandedtotal);
																								$sub_laba_all += ($row->hargajualtotal - $row->hargalandedtotal);
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

						                                    echo round($margin,2);
																								$sub_margin_all += $margin;
						                               ?>

						                           </td>
						                       </tr>
						                       <?php
						                   }
						                }
						                ?>
						            </tbody>

												<tfoot>
													<tr>
														<td colspan="5"><strong>TOTAL</strong></td>
														<td class="text-right">
															<?php
															echo number_format($sub_hpp_all,2,',','.');
															?>
														</td>
														<td class="text-right">
															<?php
															echo number_format($sub_omzet_all,2,',','.');
															?>
														</td>
														<td class="text-right">
															<?php
															echo number_format($sub_laba_all,2,',','.');
															?>
														</td>
														<td class="text-right">
															<?php

															echo number_format($sub_margin_all,2,',','.');
															?>

														</td>
													</tr>
												</tfoot>

						        </table>
						    </div>
						    <!-- /.box-body -->
						</div>
          <!-- Data Produk -->
        </div>

        <div class="tab-pane" id="GROUP">
            <!-- form start-->
						<div class="box">
						    <div class="box-header text-left">
						      <div class="form-inline">
						        <div class="form-group">
						          <div class="input-group">
						              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						              <input type="text" style="width: 300px;" required="" name="awal1" class="form-control input-sm datepicker" value="<?php echo $awal1?>">
						          </div>
						          <div class="input-group">
						              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						              <input type="text" style="width: 300px;" required="" name="akhir1" class="form-control input-sm datepicker" value="<?php echo $akhir1?>">
						          </div>
						          <button type="submit" class="btn btn-md btn-warning" value="Tampilkan">Tampilkan</button>

						        </div>
						      </div>
						    </div>
						    <!-- /.box-header -->
						    <div class="box-body">
						        <table id="example11" class="table table-bordered table-striped">
						            <thead>
						                <tr>
						                    <th width="20">#</th>
						                    <th >ID / Nama Group</th>
						                    <th>HPP</th>
						                    <th>Omzet</th>
						                    <th>Laba</th>
						                    <th>Margin</th>
						                </tr>
						            </thead>

						            <tbody>
						                <?php
						                $no=0;
														$kdcab = $this->auth->user_cab();
														$ambil_group = $this->db->get('barang_group')->result();
														//print_r($ambil_group);
														foreach ($ambil_group as $key => $value) {
															$ambil_inv_detail = $this->db
															->join('trans_invoice_header',"trans_invoice_header.no_invoice = trans_invoice_detail.no_invoice","left")
															->group_by('trans_invoice_detail.no_invoice')
															->where('tanggal_invoice BETWEEN "'.$awal1.'" AND "'.$akhir1.'"')
															->get_where('trans_invoice_detail', array('MID(id_barang,3,2)'=>$value->id_group,'kdcab'=>$kdcab))
															->result();
															$st_inv = 0;
															$hargalandedtotal = 0;
															$hargajualtotal = 0;
															$margin = 0;
															foreach ($ambil_inv_detail as $ka => $va) {
																$hargalandedtotal += $va->hargalandedtotal;
																$hargajualtotal += $va->hargajualtotal;
															}


															$query = $this->db->query("SELECT * FROM `trans_invoice_header` WHERE `tanggal_invoice` BETWEEN '$awal' AND '$akhir' AND `kdcab` = '$kdcab'");

															if ($query)
															{

																	$no++;
																	?>
																	<tr>
																		<td><?= $no ?></td>
																		<td>
																			<?php
																			echo $value->id_group." / ".$value->nm_group;
																			?>
																		</td>

																		<td class="text-right">
																			<?php
																			echo number_format($hargalandedtotal, 2, ',', '.');
																			$sub_hpp += $hargalandedtotal;
																			?>
																		</td>
																		<td class="text-right">
																			<?php
																			echo number_format($hargajualtotal, 2, ',', '.');
																			$sub_omzet += $hargajualtotal;
																			?>
																		</td>
																		<td class="text-right">
																			<?php
																			echo number_format($hargajualtotal - $hargalandedtotal, 2, ',', '.');
																			$sub_laba += ($hargajualtotal - $hargalandedtotal);
																			?>
																		</td>
																		<td class="text-right">
																			<?php
																			$laba=$hargajualtotal - $hargalandedtotal;
																			if ($hargalandedtotal==0) {
																				$margin=$laba*100;
																			}else {
																				$margin=($laba/$hargalandedtotal)*100;
																			}

																			echo number_format(round($margin,2), 2, ',', '.');
																			$sub_margin += $margin;
																			?>

																		</td>
																	</tr>

																	<?php

															}
														}
						                ?>
						            </tbody>
												<tfoot>
													<tr>
														<td colspan="2"><strong>TOTAL</strong></td>
														<td class="text-right">
															<?php
															echo number_format($sub_hpp,2,',','.');
															?>
														</td>
														<td class="text-right">
															<?php
															echo number_format($sub_omzet,2,',','.');
															?>
														</td>
														<td class="text-right">
															<?php
															echo number_format($sub_laba,2,',','.');
															?>
														</td>
														<td class="text-right">
															<?php
															$laba=$hargajualtotal - $hargalandedtotal;
															if ($hargalandedtotal==0) {
																$margin=$laba*100;
															}else {
																$margin=($laba/$hargalandedtotal)*100;
															}

															echo number_format($sub_margin,2,',','.');
															?>

														</td>
													</tr>
												</tfoot>

						        </table>
						    </div>
						    <!-- /.box-body -->
						</div>
          <!-- Data Produk -->
        </div>
    </div>
    <!-- /.tab-content -->
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
