<link rel="stylesheet" href="<?php echo base_url('assets/css/radiobutton.css'); ?>">
<link href="<?= base_url(); ?>assets/css/switch.css" rel="stylesheet" />
<div class="nav-tabs-pr">
    <div class="tab-content">
        <div class="tab-pane active" id="pr">
            <div class="box box-primary">
                <form id="form-header-pr"  method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label for="idcabang" class="col-sm-4 control-label">Nama Cabang <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8" style="padding-top: 11px;">
                              <?php
                              $session = $this->session->userdata('app_session');
                              $caba = $this->Purchaserequest_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
                               echo ': '.$caba->kdcab.', '.$caba->namacabang; ?>
                               <input type="hidden" name="kdcab" id="kdcab" class="form-control input-sm" value="<?php echo $caba->kdcab; ?>">
                              <input type="hidden" name="namacabang" id="namacabang" class="form-control input-sm" value="<?php echo $caba->namacabang; ?>">
                          </div>
                        </div>

                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-4 control-label">Supplier <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8" style="padding-top: 11px;">
                                <?= $this->uri->segment(4); ?>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-2 control-label">Keterangan Revisi <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-10" style="padding-top: 11px;">
                                <?= $pr_hader->keterangan; ?>
                            </div>
                        </div>

                    </div>
                </div>
                </div>
                   <input id="cbm_tot" type="hidden" name="cbm_tot" value="<?= $pr_hader->total_cbm; ?>">
                    <input id="no_pr" type="hidden" name="no_pr" value="<?= $no_pr; ?>"/>
                    <input id="supplier" type="hidden" name="idsupplier" value="<?= $supplier; ?>"/>
                    <input type="hidden" name="id_revisi" value="<?= $pr_hader->id; ?>" />
            </div>
        </div>
    </div>
</div>

<div class="box box-default ">
    <div class="box-body">
        <table id="TabelTransaksi" class="table table-bordered" width="100%">
            <tr>
                <th width="1%"><center>NO</center></th>
                <th width="30%">NAMA PRODUK</th>
                <td>QTY</td>
                <td>REVISI</td>
                <td>KONFRIM QTY</td>
                <th width="7%"><center>CBM EACH</center></th>
                <th width="7%"><center>CBM SUB TOTAL</center></th>
                <th width="7%"><center>G. W.(KGS)</center></th>
                <th width="7%"><center>G. W. TOTAL(KGS)</center></th>
            </tr>
            <tbody id="dtazz">
            <?php
            $noor = 0;
            $total_cbmx = 0;
            $total_khsx = 0;
            $total_qtyx = 0;
            $total_revisi = 0;
            foreach (@$itembarang as $data => $datas) {
                $query_det = $this->db->query("SELECT * FROM `trans_pr_detail` WHERE no_pr='$no_pr' AND id_barang='$datas->id_barang' ");
                $row_det = $query_det->row();
                if (!empty($row_det->qty_pr)) {
                    $qtyx = $row_det->qty_pr;
                    $cbmx = $datas->cbm_each * $qtyx;
                    $kgsx = $datas->gross_weight * $qtyx;
                } else {
                    $qtyx = 0;
                    $cbmx = 0;
                    $kgsx = 0;
                }
                $total_qtyx += $qtyx;
                $total_cbmx += $cbmx;
                $total_khsx += $kgsx;
                ++$noor;

                $id_rev = $pr_hader->id;
                $query_rev = $this->db->query("SELECT * FROM `revisi_pr_detail` WHERE  id_barang='$datas->id_barang' and id_revisi_pr='$id_rev'");
                $row_rev = $query_rev->row();
                $total_revisi += $row_rev->qty; ?>
                <tr>
                    <td>
                        <?php echo $noor; ?>
                    </td>
                    <td>
                        <?php echo $datas->nm_barang; ?>
                    </td>
                    <td>
                        <input type="hidden" name="qty<?= $noor; ?>" value="<?= $qtyx; ?>" />
                        <?= $qtyx; ?>
                    </td>
                    <td>
                        <?= $row_rev->qty; ?>
                    </td>
                    <td>
                        <input name="qty_c<?= $noor; ?>" id="qty<?= $noor; ?>" onkeyup="sum<?= $noor; ?>(); HitungTotal('<?php echo $noor; ?>')" value="<?= $qtyx; ?>" />
                    </td>
                    <td>
                        <input name="cbm<?= $noor; ?>" type="hidden" id="cbm<?= $noor; ?>" value="<?php echo $datas->cbm_each; ?>" />
                        <?php echo $datas->cbm_each; ?>
                    </td>
                    <input type="hidden" id="cbmTtl<?= $noor; ?>" />
                    <td id="cbmTotal<?= $noor; ?>">
                        <?= $cbmx; ?>
                    </td>
                    <td>
                        <input type="hidden" id="kgs<?= $noor; ?>" value="<?php echo $datas->gross_weight; ?>" />
                        <?php echo $datas->gross_weight; ?>
                    </td>
                    <input type="hidden" value="<?= $kgsx; ?>" id="kgsTtl<?= $noor; ?>" />
                    <td id="kgsTotal<?= $noor; ?>">
                        <?= $kgsx; ?>
                    </td>
                </tr>
                <script>
                    function sum<?= $noor; ?>() {
                          var result = parseInt(document.getElementById('qty<?= $noor; ?>').value) * parseFloat(document.getElementById('cbm<?= $noor; ?>').value);
                          if (!isNaN(result)) {
                             document.getElementById("cbmTotal<?= $noor; ?>").innerHTML = result.toFixed(2);
                             document.getElementById("cbmTtl<?= $noor; ?>").value = result.toFixed(2);
                          }

                          var resultzz = parseInt(document.getElementById('qty<?= $noor; ?>').value) * parseFloat(document.getElementById('kgs<?= $noor; ?>').value);
                          if (!isNaN(resultzz)) {
                             document.getElementById("kgsTotal<?= $noor; ?>").innerHTML = resultzz.toFixed(2);
                             document.getElementById("kgsTtl<?= $noor; ?>").value = resultzz.toFixed(2);
                          }
                    }
                </script>
                <?php
            }
            ?>

            </tbody>
            <tr>
                <td></td>
                <td></td>
                <td><?= $total_qtyx; ?></td>
                <td><?= $total_revisi; ?></td>
                <td id="totalQtyy"><?= $total_qtyx; ?></td>
                <td></td>
                <td id="totalCbmsuub"><?= $total_cbmx; ?></td>
                <td></td>
                <td id="totalKgs"><?= $total_khsx; ?></td>
            </tr>
        </table>
    </div>

    <div class="box-body">
    <div class="col-sm-12">
        <center><b>PILIH CONTAINER</b></center>
        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <?php
                    $ncb = 0;
                    foreach (@$cbm_sup as $data => $data_cbm) {
                        ++$ncb; ?>
                    <th style="text-align: center" colspan="2">
                        <p>
                            <input type="radio" value="<?= $data_cbm->id_cbm; ?>" id="test<?= $ncb; ?>" name="radio-group" <?= $pr_hader->id_cbm == $data_cbm->id_cbm ? 'checked' : ''; ?>  >
                            <label for="test<?= $ncb; ?>"><?= $data_cbm->name_cbm; ?></label>
                          </p>

                    </th>
                    <?php
                    } ?>
                </tr>
                <tr>
                    <?php
                    $nxg = 0;
                    foreach (@$cbm_sup as $data => $data_cbm) {
                        ++$nxg; ?>
                    <script>

                        function losefocus<?= $nxg; ?>($id, tkgs) {

                            $cbmmm=parseFloat($id)/<?= $data_cbm->cbm; ?>;
                            document.getElementById("hasilctn<?= $nxg; ?>").innerHTML =$cbmmm.toFixed(2);

                            ckgs=parseFloat(tkgs)/1000;
                            document.getElementById("hasilckgs<?= $nxg; ?>").innerHTML =ckgs.toFixed(2);

                        }
                    </script>
                    <th style="text-align: center">
                        CBM
                    </th>
                    <th style="text-align: center">
                        KGS (TON)
                    </th>
                    <?php
                    } ?>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <?php
                    foreach (@$cbm_sup as $data => $data_cbm) {
                        ?>
                    <td style="text-align: center">
                        <?= $data_cbm->cbm; ?>
                    </td>
                    <td style="text-align: center">
                        <?= $data_cbm->kgs; ?>
                    </td>
                    <?php
                    } ?>
                </tr>
                <tr>
                    <?php
                    $bgh = 0;
                    foreach (@$cbm_sup as $data => $data_cbm) {
                        ++$bgh; ?>
                    <td id="hasilctn<?= $bgh; ?>" style="text-align: center">
                        <?php
                        echo number_format($total_cbmx / $data_cbm->cbm, 2); ?>
                    </td>
                    <td id="hasilckgs<?= $bgh; ?>" style="text-align: center">
                         <?php
                        echo number_format($total_khsx / 1000, 2); ?>
                    </td>
                    <?php
                    } ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<!--div class="box-body">
    <div class="col-sm-12">
        <center><b>PR (Purchase Request) Tambahan</b></center>
        <div class="input_fields_wrap">


            <div class="row">
                <div class="col-xs-3">
                  Nama Komponen
                </div>
                <div class="col-xs-2">
                  QTY
                </div>
                <div class="col-xs-5">
                  Nama Produk
                </div>
            </div>
            <?php
            if ($pr_tambahan->num_rows() > 0) {
                $nx = 0;
                foreach ($pr_tambahan->result() as $row_tamb) {
                    ++$nx; ?>
            <div class="row" id="div<?= $nx; ?>">
                <div class="col-xs-3">
                  <input type="text" name="komponen[]" class="form-control" value="<?= $row_tamb->nm_komponen; ?>" placeholder="komponen">
                </div>
                <div class="col-xs-2">
                  <input type="text" name="qtyt[]" class="form-control" value="<?= $row_tamb->qty; ?>" placeholder="qty">
                </div>
                <div class="col-xs-5">
                    <select id="idbarangmx" name="barang_t[]" class="form-control input-sm" style="width: 100%;" tabindex="-1" >

                        <?php
                            foreach (@$itembarang as $rowxx) {
                                ?>
                        <option  value="<?php echo $rowxx->id_barang; ?>" <?=  $row_tamb->id_barang == $rowxx->id_barang ? 'selected="selected"' : ''; ?>>
                            <?php echo $rowxx->nm_barang; ?>
                        </option>
                        <?php
                            } ?>
                    </select>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div-->


    </form>

    <table id="prdetailitem" class="table table-bordered table-striped" width="100%">

            <tfoot>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" onclick="saveheaderpr()"  >
                            <i class="fa fa-save"></i><b> Simpan Konfirmasi</b>
                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>
</div>

<br>
<script>

function HitungTotal(id)
{

    sumKgs      = 0;
    $('#dtazz tr').each(function(){
        SubTotalKgs = parseFloat($(this).find("td:eq(8)").text());
        console.log(SubTotalKgs);
        if (isNaN(SubTotalKgs)) {
            datasKgs  =0;
          } else {
            datasKgs  =SubTotalKgs;
          }

          sumKgs += parseFloat(datasKgs);
    });
    document.getElementById("totalKgs").innerHTML = sumKgs.toFixed(2);


    var Total   = 0;
    sum         = 0;
    qtytotal    =0;
    $('#dtazz tr').each(function(){

        SubTotal = parseFloat($(this).find("td:eq(6)").text());
        if (isNaN(SubTotal)) {
            datass  =0;
          } else {
            datass  =SubTotal;
          }

          sum += parseFloat(datass);
    });
    //console.log($('#dtazz tr').length);

    document.getElementById("totalCbmsuub").innerHTML = sum.toFixed(2);
    document.getElementById("cbm_tot").value = sum;
     <?php
    $nxg = 0;
    foreach (@$cbm_sup as $data => $data_cbm) {
        ++$nxg; ?>
    losefocus<?= $nxg; ?>(sum.toFixed(2), sumKgs.toFixed(2));
    <?php
    } ?>


        var i;
        for (i=1;i<=<?= count($itembarang); ?>;i++)
        {

            SubQtys = parseInt(document.getElementById('qty'+i).value);
            if (isNaN(SubQtys)) {
                datassQ  =0;
              } else {
                datassQ  =SubQtys;
              }

              qtytotal += parseInt(datassQ);
        }

        document.getElementById("totalQtyy").innerHTML =qtytotal;
    }


    function saveheaderpr(){

                var formdata = $("#form-header-pr").serialize();
               // console.log(formdata);
                $.ajax({
                    url: siteurl+"purchaserequest/revisi_save",
                    dataType : "json",
                    type: 'POST',
                    data: formdata,
                    success: function(result){
                        console.log(result['msg']);
                        if(result.save=='1'){
                            swal({
                                title: "Sukses!",
                                text: result['msg'],
                                type: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.href=siteurl+'purchaserequest';
                            },1600);
                        } else {
                            swal({
                                title: "Gagal!",
                                text: result['msg'],
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
