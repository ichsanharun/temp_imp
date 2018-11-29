<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<div class="nav-tabs-po">
    <div class="tab-content">
        <div class="tab-pane active" id="po">
            <div class="box box-primary">
                <form id="form-header-po" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-4 control-label">Nama Supplier <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idsupplier" name="idsupplier" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getsupplier()">
                                <option value=""></option>
                                <?php
                                foreach(@$supplier as $kc=>$vc){
                                ?>
                                <option value="<?php echo $vc->id_supplier; ?>" <?php echo set_select('nm_supplier', $vc->id_supplier, isset($data->nm_supplier) && $data->id_supplier == $vc->id_supplier) ?>>
                                    <?php echo $vc->id_supplier.' , '.$vc->nm_supplier ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmsupplier" id="nmsupplier" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tglpo=date('Y-m-d')?>
                            <label for="tglpo" class="col-sm-4 control-label">Tanggal PO<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglpo" id="tglpo" class="form-control input-sm datepicker" value="<?php echo $tglpo?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ">
                             <?php $plandeliverypo=date('Y-m-d')?>
                            <label for="plandeliverypo" class="col-sm-4 control-label">Plan Delivery <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="plandeliverypo" id="plandeliverypo" class="form-control input-sm datepicker" value="<?php echo $plandeliverypo?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $realdeliverypo=date('Y-m-d')?>
                            <label for="realdeliverypo" class="col-sm-4 control-label">Real Delivery <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="realdeliverypo" id="realdeliverypo" class="form-control input-sm datepicker" value="<?php echo $realdeliverypo?>">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END FORM HEADER SO-->
<div class="box box-default ">
    <div class="box-body">
        <form id="form-detail-po" method="post">
        <table class="table table-bordered" width="100%">
            <tr>
                <th class="text-center" colspan="8">FORM ITEM DETAIL</th>
            </tr>
            <tr>
                <td width="13%"><b>PRODUCT SEAT</b></td>
                <td colspan="3" width="30%">
                    <select onchange="setitembarang()" id="item_brg_po" name="item_brg_po" class="form-control input-xs" style="width: 100%;" tabindex="-1" required>
                            <option value=""></option>
                            <?php
                            foreach(@$itembarang as $k=>$v){
                                $selected ='';
                                if($this->uri->segment(3) == $v->id_barang){
                                    $selected='selected="selected"';
                                }
                            ?>
                            <option value="<?php echo $v->id_barang;?>" <?php echo set_select('nm_barang', $v->id_barang, isset($data->nm_barang) && $data->id_barang == $v->id_barang) ?> <?php echo $selected?>>
                                <?php echo $v->id_barang.' , '.$v->nm_barang?>
                            </option>
                            <?php } ?>
                        </select>
                </td>
                <td width="10%" class="text-right"><b>HARGA BELI</b></td>
                <td width="13%"><input type="text" name="harga" id="harga" class="form-control input-sm"></td>
                <td width="7%" class="text-right"><b>QTY BELI</b></td>
                <td width="7%"><input type="text" name="qty_po" id="qty_po" class="form-control input-sm"></td>
                <td width="10%">
                    <button class="btn btn-sm btn-success" style="width: 100%;"><i class="fa fa-plus"></i> Tambah</button>
                </td>
            </tr>
        </table>
        </form>
        <?php if($this->uri->segment(3) != ""){ ?>
        <?php
            $colly = $this->Purchaseorder_model->get_data(array('id_barang' => $this->uri->segment(3)),'barang_koli');
        ?>
        <table class="table table-bordered" width="100%">
            <tr>
                <th colspan="4"><center>DATA COLLY PRODUK :  <?php echo $this->uri->segment(3)?></center></th>
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
        <table id="podetailitem" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th>Qty Beli</th>
                    <th>Harga</th>
                    <th>Diskon (%)</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="saveheaderpo()">
                            <i class="fa fa-save"></i><b> Simpan Data PO</b>
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
    $(document).ready(function() {
        $("#idsupplier,#item_brg_po").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        var dataTableItem = $('#podetailitem').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false,
        });
        sethitung();
    });
    function kembali(){
        window.location.href = siteurl+'purchaseorder';
    }
    function setitembarang(){
        var idbarang = $('#item_brg_po').val();
        window.location.href = siteurl+'purchaseorder/create/'+idbarang;
    }
    function getsupplier(){
        var idsup = $('#idsupplier').val();
        if(idsup != ''){
           $.ajax({
                type:"GET",
                url:siteurl+"purchaseorder/get_supplier",
                data:"idsup="+idsup,
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nmsupplier').val(data.nm_supplier);
                }
            }); 
        }
    }
</script>
