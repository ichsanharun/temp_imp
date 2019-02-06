<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<div class="nav-tabs-pr">
    <div class="tab-content">
        <div class="tab-pane active" id="pr">
            <div class="box box-primary">
                <form id="form-header-pr" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label for="idcabang" class="col-sm-4 control-label">Nama Cabang <font size="4" color="red"><B>*</B></font></label>
                          <div class="col-sm-8" style="padding-top: 11px;">
                              <?php
                              $session = $this->session->userdata('app_session');
                              $caba = $this->Purchaserequest_model->cek_data(array('kdcab'=>$session['kdcab']),'cabang');
                               echo ": ". $caba->kdcab.", ".$caba->namacabang; ?>
                               <input type="hidden" name="kdcab" id="kdcab" class="form-control input-sm" value="<?php echo $caba->kdcab?>">
                              <input type="hidden" name="namacabang" id="namacabang" class="form-control input-sm" value="<?php echo $caba->namacabang?>">
                          </div>
                        </div>
                        <?php
                        if($this->uri->segment(3) != ""){
                            $supp = $this->Purchaserequest_model->cek_data(array('id_supplier'=>$this->uri->segment(3)),'supplier');

                        }
                        ?>
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-4 control-label">Nama Supplier <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idsupplier" name="idsupplier" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getsupplier()">
                                <option value=""></option>
                                <?php
                                foreach(@$supplier as $kc=>$vc){
                                    $selected ='';
                                    if($this->uri->segment(3) == $vc->id_supplier){
                                        $selected='selected="selected"';
                                    }
                                ?>
                                <option value="<?php echo $vc->id_supplier; ?>" <?php echo set_select('nm_supplier', $vc->id_supplier, isset($data->nm_supplier) && $data->id_supplier == $vc->id_supplier) ?> <?php echo $selected?>>
                                    <?php echo $vc->id_supplier.' , '.$vc->nm_supplier ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmsupplier" id="nmsupplier" class="form-control input-sm" value="<?php echo $supp->nm_supplier?>">
                                </div>
                            </div>
                            <?php //print_r($supp)?>
                        </div>
                        <div class="form-group ">
                            <label for="alamat" class="col-sm-4 control-label">Alamat</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <textarea rows="1" cols="30" class="form-control"><?php echo $supp->alamat?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ">
                            <?php $tglpr=date('Y-m-d')?>
                            <label for="tglpr" class="col-sm-4 control-label">Tanggal PR<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglpr" id="tglpr" class="form-control input-sm datepicker" value="<?php echo $tglpr?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                             <?php $plandeliverypr=date('Y-m-d')?>
                            <label for="plandeliverypr" class="col-sm-4 control-label">Plan Delivery <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="plandeliverypr" id="plandeliverypr" class="form-control input-sm datepicker" value="<?php echo $plandeliverypr?>">
                                    <input type="hidden" name="totalpr" id="totalpr" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="plandeliverypr" class="col-sm-4 control-label">CBM <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                    <input type="text" name="cbm" id="cbm" onkeyup="TotalContainer()" class="form-control input-sm " value="<?php echo $supp->cbm?>">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <?php
                  $total=0;
                  $cbm_sub=0;
                  $cbm_tot=0;
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
        if($this->uri->segment(3) == ""){
            ?>
            <center><b>Silahkan pilih Supplier terlebih dahulu.</b></center>
            <?php
        }else {
        
        ?>
        <input type="hidden" name="total_barang" value="<?= count($itembarang) ?>" />
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
            $noor=0;
            foreach(@$itembarang as $data => $datas){
                $noor ++;
                ?>
                <tr>
                    <td>
                        <?php echo $noor; ?>
                    </td>
                    <td>
                        <?php echo $datas->nm_barang; ?>
                    </td>
                    <td>
                        <input name="qty<?= $noor ?>" id="qty<?= $noor ?>" onkeyup="sum<?= $noor ?>(); HitungTotal('<?php echo $noor ?>')" value="0" />
                    </td>
                    <td>
                        <input name="cbm<?= $noor ?>" type="hidden" id="cbm<?= $noor ?>" value="<?php echo $datas->cbm_each?>" />
                        <?php echo $datas->cbm_each?>
                    </td>
                    <input type="hidden" id="cbmTtl<?= $noor ?>" />
                    <td id="cbmTotal<?= $noor ?>">
                        
                    </td>
                    <td>
                        <input type="hidden" id="kgs<?= $noor ?>" value="<?php echo $datas->gross_weight?>" />
                        <?php echo $datas->gross_weight?>
                    </td>
                    <input type="hidden" id="kgsTtl<?= $noor ?>" />
                    <td id="kgsTotal<?= $noor ?>">
                        
                    </td>
                </tr>
                <script>
                    function sum<?= $noor ?>() {
                          var result = parseInt(document.getElementById('qty<?= $noor ?>').value) * parseFloat(document.getElementById('cbm<?= $noor ?>').value);
                          if (!isNaN(result)) {
                             document.getElementById("cbmTotal<?= $noor ?>").innerHTML = result.toFixed(2);
                             document.getElementById("cbmTtl<?= $noor ?>").value = result.toFixed(2);
                          }
                          
                          var resultzz = parseInt(document.getElementById('qty<?= $noor ?>').value) * parseFloat(document.getElementById('kgs<?= $noor ?>').value);
                          if (!isNaN(resultzz)) {
                             document.getElementById("kgsTotal<?= $noor ?>").innerHTML = resultzz.toFixed(2);
                             document.getElementById("kgsTtl<?= $noor ?>").value = resultzz.toFixed(2);
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
                <td id="totalQtyy">0</td>
                <td></td>
                <td id="totalCbmsuub"></td>
                <td></td>
                <td id="totalKgs"></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right">
                    Total Jumlah Container
                </td>
                <td id="totalContainer" colspan="4">
                </td>
            </tr>
        </table>
        
        <script>
            function TotalContainer()
            {
                
                sumCBM = parseFloat(document.getElementById("totalCbmsuub").innerHTML);
                cbmY=parseInt(document.getElementById('cbm').value);
                if (isNaN(SubTotalKgs)) {
                    document.getElementById("totalContainer").innerHTML =sumCBM/cbmY;
                }
                
                
            }
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
                
                cbmY=parseInt(document.getElementById('cbm').value);
                //console.log(cbmY);
                //console.log(sum);
                if (sum > cbmY ) {
                    
                    swal({
                        title: "Peringatan!",
                        text: "MAKSIMUM CBM ADALAH "+cbmY,
                        type: "error",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    htl=document.getElementById("cbmTtl"+id).value;
                    document.getElementById("cbm_tot").value = sum-parseFloat(htl);
                    document.getElementById('qty'+id).value = "0";
                    document.getElementById("cbmTotal"+id).innerHTML = '';
                    document.getElementById("cbmTtl"+id).value = "0";
                    document.getElementById("kgsTotal"+id).innerHTML = '';
                    document.getElementById("kgsTtl"+id).value = "0";
                  }
                  
                var i;
                for (i=1;i<=<?= count($itembarang) ?>;i++)
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
                
                //total kontainer
                
                document.getElementById("totalContainer").innerHTML =sum/cbmY;
            }
            
           
        </script>
        
        <table id="prdetailitem" class="table table-bordered table-striped" width="100%">
            
            <tfoot>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="saveheaderpr()">
                            <i class="fa fa-save"></i><b> Simpan Data PR</b>
                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>
       </form>
       
       <?php } ?>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>


<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->


<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
    var uri3 = '<?php echo $this->uri->segment(3)?>';
    var uri4 = '<?php echo $this->uri->segment(4)?>';
    $(document).ready(function() {
        var total = parseFloat($('#totalpr_view').val());
        $('#totalpr').val(total);
        $("#idsupplier,#item_brg_pr").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        /*var Item = $('#prdetailitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false
        });*/
    });
    function get_total(){
      var qty = document.getElementById("qty_pr").value;
      var cbm = document.getElementById("cbm_each").value;
      var gw = document.getElementById("gw_each").value;
      document.getElementById("cbm_tot").value = qty*cbm;
      document.getElementById("gw_tot").value = qty*gw;
      var cbm_tot_akhir = parseInt(document.getElementById("cbm_tot_tmp").innerHTML) + parseInt(document.getElementById("cbm_tot").value);
      document.getElementById("cbm_tot_tmp2").innerHTML = cbm_tot_akhir;
      if (document.getElementById("cbm_tot").value > 60 || cbm_tot_akhir > 60) {
        swal({
            title: "Peringatan!",
            text: "MAKSIMUM CBM ADALAH 60",
            type: "error",
            timer: 1500,
            showConfirmButton: false
        });
        document.getElementById("qty_pr").value = "0";
        var cbm_tot_akhir = 0;
        document.getElementById("cbm_tot_tmp2").innerHTML = 0;
      }
    }
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
        var Total = 0;
        sum = 0;
        $('#dtazz tr').each(function(){
            SubTotal = parseFloat($(this).find("td:eq(4)").text());
            if (isNaN(SubTotal)) {
                datass  =0;
              } else {
                datass  =SubTotal;
              }
              
              sum += parseFloat(datass);
            
        });
    
        if (sum <= 0 ) {
                swal({
                    title: "Peringatan!",
                    text: "Masukan QTY terlebih dahulu",
                    type: "error",
                    timer: 1500,
                    showConfirmButton: false
                });
          }else{
                var formdata = $("#form-header-pr").serialize();
                $.ajax({
                    url: siteurl+"purchaserequest/save_new_create",
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
        
        
    }
</script>
