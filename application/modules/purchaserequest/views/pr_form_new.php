<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
<!-- FORM HEADER SO-->

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
                        <?php
                        if ($this->uri->segment(3) != '') {
                            $supp = $this->Purchaserequest_model->cek_data(array('id_supplier' => $this->uri->segment(3)), 'supplier');
                        }
                        ?>

                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-4 control-label">Supplier <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idsupplier" name="idsupplier" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getsupplier()">
                                <option value=""></option>
                                <?php
                                foreach (@$supplier as $kc => $vc) {
                                    $selected = '';
                                    if ($this->uri->segment(3) == $vc->id_supplier) {
                                        $selected = 'selected="selected"';
                                    } ?>
                                <option value="<?php echo $vc->id_supplier; ?>" <?php echo set_select('nm_supplier', $vc->id_supplier, isset($data->nm_supplier) && $data->id_supplier == $vc->id_supplier); ?> <?php echo $selected; ?> >

                                    <?php echo $vc->id_supplier; ?> - <?php echo strtoupper($vc->group_produk); ?>

                                </option>
                                <?php
                                } ?>
                                </select>
                                <input type="hidden" name="nmsupplier" id="nmsupplier" class="form-control input-sm" value="<?php echo $supp->nm_supplier; ?>">
                                </div>
                            </div>
                            <?php //print_r($supp)?>
                        </div>


                            <?php $tglpr = date('Y-m-d'); ?>
                            <input type="hidden" name="tglpr" id="tglpr" class="form-control input-sm datepicker" value="<?php echo $tglpr; ?>">


                             <?php $plandeliverypr = date('Y-m-d'); ?>
                            <input type="hidden" name="plandeliverypr" id="plandeliverypr" class="form-control input-sm datepicker" value="<?php echo $plandeliverypr; ?>">
                            <input type="hidden" name="totalpr" id="totalpr" class="form-control input-sm">

                    </div>
                </div>
                </div>
                <?php
                  $total = 0;
                  $cbm_sub = 0;
                  $cbm_tot = 0;
                  ?>
                   <input id="cbm_tot" type="hidden" name="cbm_tot" >


            </div>
        </div>
    </div>
</div>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
    <div class="box-body">
        <?php
        if ($this->uri->segment(3) == '') {
            ?>
            <center><b>Silahkan pilih Supplier terlebih dahulu.</b></center>
            <?php
        } else {
            ?>
        <input type="hidden" name="total_barang" value="<?= count($itembarang); ?>" />
        <table id="TabelTransaksi" class="table table-bordered" width="100%">
            <tr>
                <th width="1%"><center>NO</center></th>
                <th width="30%">NAMA PRODUK</th>
                <td>QTY</td>
                <th width="7%"><center>CBM EACH</center></th>
                <th width="7%"><center>CBM SUB TOTAL</center></th>
                <th width="7%"><center>G. W.(KGS)</center></th>
                <th width="7%"><center>G. W. TOTAL(KGS)</center></th>
            </tr>
            <tbody id="dtazz">
            <?php
            $noor = 0;
            foreach (@$itembarang as $data => $datas) {
                ++$noor; ?>
                <tr>
                    <td>
                        <?php echo $noor; ?>
                    </td>
                    <td>
                        <?php echo $datas->nm_barang; ?>
                    </td>
                    <td>
                        <input name="qty<?= $noor; ?>" id="qty<?= $noor; ?>" onkeyup="sum<?= $noor; ?>(); HitungTotal('<?php echo $noor; ?>')" value="0" />
                    </td>
                    <td>
                        <input name="cbm<?= $noor; ?>" type="hidden" id="cbm<?= $noor; ?>" value="<?php echo $datas->cbm_each; ?>" />
                        <?php echo $datas->cbm_each; ?>
                    </td>
                    <input type="hidden" id="cbmTtl<?= $noor; ?>" />
                    <td id="cbmTotal<?= $noor; ?>">

                    </td>
                    <td>
                        <input type="hidden" id="kgs<?= $noor; ?>" value="<?php echo $datas->gross_weight; ?>" />
                        <?php echo $datas->gross_weight; ?>
                    </td>
                    <input type="hidden" id="kgsTtl<?= $noor; ?>" />
                    <td id="kgsTotal<?= $noor; ?>">

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
            } ?>

            </tbody>
            <tr>
                <td></td>
                <td></td>
                <td id="totalQtyy">0</td>
                <td></td>
                <td id="totalCbmsuub"></td>
                <td></td>
                <td id="totalKgs"></td>
            </tr>
        </table>

        <script>

            function HitungTotal(id)
            {

                sumKgs      = 0;
                $('#dtazz tr').each(function(){
                    SubTotalKgs = parseFloat($(this).find("td:eq(6)").text());
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

                    SubTotal = parseFloat($(this).find("td:eq(4)").text());
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


        </script>

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
                                    <input type="radio" value="<?= $data_cbm->id_cbm; ?>" id="test<?= $ncb; ?>" name="radio-group"  checked>
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
                            <td  style="text-align: center">
                                Jumlah Kontainer = <b id="hasilctn<?= $bgh; ?>">0</b>
                            </td>
                            <td id="hasilckgs<?= $bgh; ?>" style="text-align: center">

                            </td>
                            <?php
            } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <form id="tambahaan" method="post">
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
                        <div class="col-xs-2">
                          <button class="add_field_button btn btn-primary"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3">
                          <input type="text" name="komponen[]" class="form-control" placeholder="komponen">
                        </div>
                        <div class="col-xs-2">
                          <input type="text" name="qtyt[]" class="form-control" placeholder="qty">
                        </div>
                        <div class="col-xs-5">
                            <select id="idbarangmx" name="barang_t[]" class="form-control input-sm" style="width: 100%;" tabindex="-1" >
                                <option value=""></option>
                                <?php
                                    foreach (@$itembarang as $rowxx) {
                                        ?>
                                <option value="<?php echo $rowxx->id_barang; ?>">
                                    <?php echo $rowxx->nm_barang; ?>
                                </option>
                                <?php
                                    } ?>
                            </select>
                        </div>
                        <div class="col-xs-2">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $("#idbarangmx").select2({
                    placeholder: "Pilih",
                    allowClear: true
                });

                $('.my-select').select2({
                    placeholder: "Pilih",
                    allowClear: true
                });



                var max_fields      = 10; //maximum input boxes allowed
                var wrapper         = $(".input_fields_wrap"); //Fields wrapper
                var add_button      = $(".add_field_button"); //Add button ID

                var x = 1; //initlal text box count
                $(add_button).click(function(e){ //on add input button click
                    e.preventDefault();
                    if(x < max_fields){ //max input box allowed
                        x++; //text box increment



                        $(wrapper).append('<div class="row">'+
                                                '<div class="col-xs-3">'+
                                                  '<input type="text" name="komponen[]" class="form-control" placeholder="komponen">'+
                                                '</div>'+
                                                '<div class="col-xs-2">'+
                                                  '<input type="text" name="qtyt[]" class="form-control" placeholder="qty">'+
                                                '</div>'+
                                                '<div class="col-xs-5">'+
                                                    '<select id="select2" name="barang_t[]"  class="my-select form-control input-sm " style="width: 100%;" tabindex="-1" >'+
                                                        '<option value=""></option>'+
                                                        <?php
                                                            foreach (@$itembarang as $rowxx) {
                                                                ?>
                                                        '<option value="<?php echo $rowxx->id_barang; ?>">'+
                                                            '<?php echo $rowxx->nm_barang; ?>'+
                                                        '</option>'+
                                                        <?php
                                                            } ?>
                                                    '</select>'+
                                                '</div>'+
                                                '<a href="#" class="remove_field">Remove</a>'+
                                           '</div>'); //add input box
                    }
                });

                $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                    e.preventDefault(); $(this).parent('div').remove(); x--;
                })


            });
        </script>



        <table id="prdetailitem" class="table table-bordered table-striped" width="100%">
           </form>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" onclick="saveheaderpr()" >
                            <i class="fa fa-save"></i><b> Simpan Data PR</b>
                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>


       <?php
        } ?>
    </div>
</div>



<script type="text/javascript">
    var uri3 = '<?php echo $this->uri->segment(3); ?>';
    var uri4 = '<?php echo $this->uri->segment(4);?>';

    function kembali(){
        window.location.href = siteurl+'purchaserequest';
    }

    function setitembarang(){
        var idbarang = $('#item_brg_pr').val();
        window.location.href = siteurl+'purchaserequest/new_create/'+uri3+'/'+idbarang;
    }
    function getsupplier(){
        var idsup = $('#idsupplier').val();
        window.location.href = siteurl+'purchaserequest/new_create/'+idsup;
    }

    function resetform(){
        $('#form-detail-pr')[0].reset();
    }


    function saveheaderpr(){

                var formdata = $("#form-header-pr, #tambahaan").serialize();
               // console.log(formdata);
                $.ajax({
                    url: siteurl+"purchaserequest/save_new",
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
