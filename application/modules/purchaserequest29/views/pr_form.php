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
                    </div>
                </div>
                </div>
                <?php
                  $total=0;
                  $cbm_sub=0;
                  $cbm_tot=0;
                  if(@$detailprtmp){
                  foreach(@$detailprtmp as $kp=>$vp){
                     $no=$n++;
                     $total += $vp->sub_total_pr;
                     $cbm_sub = $vp->cbm_each*$vp->qty_pr;
                     $cbm_tot += $cbm_sub;
                  }}?>
                   <input type="hidden" name="cbm_tot" value="<?php echo $cbm_tot;?>">

                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
    <div class="box-body">
        <?php
        if($this->uri->segment(4) != ""){
            $brg = $this->Purchaserequest_model->cek_data(array('id_barang'=>$this->uri->segment(4)),'barang_master');
            $stok = $this->Purchaserequest_model->cek_data(array('id_barang'=>$this->uri->segment(4)),'barang_stock');
        }
        ?>
        <form id="form-detail-pr" method="post">
        <table class="table table-bordered" width="100%">
            <tr>
                <th class="text-center" colspan="8">FORM ITEM DETAIL</th>
            </tr>
            <tr>
                <td width="13%"><b>PRODUCT SEAT</b></td>
                <td colspan="3" width="30%">
                    <select onchange="setitembarang()" id="item_brg_pr" name="item_brg_pr" class="form-control input-xs" style="width: 100%;" tabindex="-1" required>
                            <option value=""></option>
                            <?php
                            foreach(@$itembarang as $k=>$v){
                              //$qty_safety = $v->qty_safety_stock;
                              $qty_stok = 0;
                                $selected ='';
                                if($this->uri->segment(4) == $v->id_barang){
                                  $selected='selected="selected"';
                                  //$qty_safety = $v->qty_safety_stock;
                                  $qty_stok = $v->qty_stock;
                                }else {
                                  $qty_safety = '';
                                  $qty_stok = '0';
                                  }
                            ?>
                            <option value="<?php echo $v->id_barang;?>" <?php echo set_select('nm_barang', $v->id_barang, isset($data->nm_barang) && $data->id_barang == $v->id_barang) ?> <?php echo $selected?>>
                                <?php echo $v->id_barang.' , '.$v->nm_barang.' , '.$v->qty_stock.'/'.$v->qty_avl;?>
                            </option>
                            <?php } ?>
                        </select>
                    <input type="hidden" name="nmbarang" id="nmbarang" class="form-control input-sm" value="<?php echo $brg->nm_barang?>">
                    <input type="hidden" name="satuan" id="satuan" class="form-control input-sm" value="<?php echo $brg->satuan?>">
                </td>
                <td width="10%" class="text-right"><b><?php echo "QTY STOK : ".$stok->qty_stock;?></b></td>
                <td width="13%"></td>
                <td width="7%" class="text-right"><b>QTY BELI</b></td>
                <td width="7%"><input type="text" name="qty_pr" id="qty_pr" onkeyup="get_total()" onchange="get_total()" class="form-control input-sm"></td>
                <td width="10%">
                    <button class="btn btn-sm btn-success" style="width: 100%;" type="button" onclick="savedetailpr()"><i class="fa fa-plus"></i> Tambah</button>
                </td>
            </tr>
        </table>
        </form>
        <?php if($this->uri->segment(4) != ""){ ?>
        <?php
            $colly = $this->Purchaserequest_model->get_data(array('id_barang' => $this->uri->segment(4)),'barang_koli');
            $mastah = $this->Purchaserequest_model->get_data(array('id_barang' => $this->uri->segment(4)),'barang_master');
        ?>
        <table class="table table-bordered" width="100%">
            <tr>
                <th colspan="4"><center>DATA DETAIL PRODUK</center></th>
            </tr>
            <tr>
                <th width="1%"><center>NO</center></th>
                <th width="30%">NAMA PRODUK</th>
                <th width="7%"><center>CBM EACH</center></th>
                <th width="7%"><center>CBM SUB TOTAL</center></th>
                <th width="7%"><center>G. W.(KGS)</center></th>
                <th width="7%"><center>G. W. TOTAL(KGS)</center></th>
            </tr>
            <?php
            $n=1;
            foreach($mastah as $kc=>$vc){
                $no=$n++;
                $tc=count($mastah);
            ?>
            <tr>
                <td><center><?php echo $no?></center></td>
                <td><?php echo $vc->nm_barang?></td>
                <td><center><input type="text" class="form-control input-sm" id="cbm_each" name="cbm_each" value="<?php echo $vc->cbm_each?>" readonly></center></td>
                <td><center><input type="text" class="form-control input-sm" id="cbm_tot" name="cbm_tot" readonly></center></td>
                <td><center><input type="text" class="form-control input-sm" id="gw_each" name="gw_each" value="<?php echo $vc->gross_weight?>" readonly></center></td>
                <td><center><input type="text" class="form-control input-sm" id="gw_tot" name="gw_tot" readonly></center></td>
            </tr>
            <tr>
              <th colspan="3">CBM TOTAL</th>
              <th colspan="3">CBM: <span id="cbm_tot_tmp2">0</span></th>
            </tr>
            <?php } ?>
        </table>
        <table class="table table-bordered" width="100%">
            <tr>
                <th colspan="4"><center>DATA COLLY PRODUK</center></th>
            </tr>
            <tr>
                <th width="1%"><center>NO</center></th>
                <th width="30%">NAMA COLLY PRODUK</th>
                <th width="7%"><center>QTY COLLY</center></th>
            </tr>
            <?php
            $n=1;
            foreach($colly as $kc=>$vc){
                $no=$n++;
                $tc=count($colly);
            ?>
            <tr>
                <td><center><?php echo $no?></center></td>
                <td><?php echo $vc->nm_koli?></td>
                <td><center><?php echo $vc->qty?></center></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
        <table id="prdetailitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th width="40%">Item Barang</th>
                    <th>Satuan</th>
                    <th>Qty Beli</th>
                    <th>Subtotal CBM</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
               <?php
                $n=1;
                $total=0;
                $cbm_sub=0;
                $cbm_tot=0;
                if(@$detailprtmp){
                foreach(@$detailprtmp as $kp=>$vp){
                    $no=$n++;
                    $total += $vp->sub_total_pr;
                    $cbm_sub = $vp->cbm_each*$vp->qty_pr;
                    $cbm_tot += $cbm_sub;
               ?>
               <tr>
                   <td><center><?php echo $no?></center></td>
                   <td><?php echo $vp->id_barang.' / '.$vp->nm_barang?></td>
                   <td><center><?php echo $vp->satuan?></center></td>
                   <td><center><?php echo $vp->qty_pr?></center></td>
                   <td><center><?php echo $vp->cbm_each*$vp->qty_pr?></center></td>
                   <td>
                        <center>
                            <a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vp->id_detail_pr?>')"><i class="fa fa-trash"></i>
                            </a>
                        </center>
                   </td>
               </tr>
               <?php } ?>
               <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="4">TOTAL</th>
                    <th class="text-right">CBM:<div id="cbm_tot_tmp"><?php echo $cbm_tot;?></div></th>
                    <!--input type="number" id="cbm_tot_tmp2" name="cbm_tot_tmp2" value="<?php echo $cbm_tot;?>"-->
                    <th class="text-right" colspan="2">
                        <?php echo formatnomor($total)?>
                        <input type="hidden" id="totalpr_view" class="form-control input-sm" value="<?php echo $total?>">
                    </th>

                </tr>
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
    </div>
</div>

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
        window.location.href = siteurl+'purchaserequest/create/'+uri3+'/'+idbarang;
    }
    function getsupplier(){
        var idsup = $('#idsupplier').val();
        window.location.href = siteurl+'purchaserequest/create/'+idsup;
    }

    function resetform(){
        $('#form-detail-pr')[0].reset();
    }
    function savedetailpr(){
        var formdetail = $('#form-detail-pr').serialize();
        $.ajax({
            url: siteurl+"purchaserequest/savedetailpr",
            dataType : "json",
            type: 'POST',
            data: formdetail,
            success: function(result){
                if(result.save=='1'){
                    swal({
                        title: "Sukses!",
                        text: result['msg'],
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    resetform();
                    setTimeout(function(){
                        window.location.reload();
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
    function delete_data(id){
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
                    url: siteurl+'purchaserequest/hapus_item_pr',
                    data :{"ID":id},
                    dataType : "json",
                    type: 'POST',
                    success: function(result){
                        if(result.delete == '1'){
                            swal({
                              title: "Terhapus!",
                              text: "Data berhasil dihapus",
                              type: "success",
                              timer: 1500,
                              showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.reload();
                            },1600);
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

    function saveheaderpr(){
        var formdata = $("#form-header-pr").serialize();
        $.ajax({
            url: siteurl+"purchaserequest/saveheaderpr",
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
