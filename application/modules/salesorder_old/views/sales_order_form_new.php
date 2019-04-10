<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<!-- FORM HEADER SO-->
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-so" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <?php
                    $headersession = $this->session->userdata('header_so');
                    ?>
                    <hr>
                    <?php
                    $pic = $this->Salesorder_model->get_pic_customer($headersession['idcustomer'])->result();
                    ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idcustomer" class="col-sm-4 control-label">Nama Customer <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idcustomer" name="idcustomer" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getcustomer()">
                                <option value=""></option>
                                <?php
                                foreach(@$customer as $kc=>$vc){
                                ?>
                                <option value="<?php echo $vc->id_customer; ?>" <?php echo set_select('nm_customer', $vc->id_customer, isset($headersession['nmcustomer']) && $headersession['idcustomer'] == $vc->id_customer) ?>>
                                    <?php echo $vc->id_customer.' , '.$vc->nm_customer ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmcustomer" id="nmcustomer" class="form-control input-sm" value="<?php echo $headersession['nmcustomer']?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="idsalesman" class="col-sm-4 control-label">Nama Salesman <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <select id="idsalesman" name="idsalesman" class="form-control input-sm" style="width: 100%;" tabindex="-1" required onchange="getsalesman()">
                                <option value=""></option>
                                <?php
                                foreach(@$marketing as $km=>$vm){
                                    $selected = '';
                                    if($headersession['idsalesman'] == $vm->id_karyawan){
                                        $selected = 'selected="selected"';
                                    }
                                ?>
                                <option value="<?php echo $vm->id_karyawan; ?>" <?php echo $selected?>>
                                    <?php echo $vm->nama_karyawan ?>
                                </option>
                                <?php } ?>
                                </select>
                                <input type="hidden" name="nmsalesman" id="nmsalesman" class="form-control input-sm" value="<?php echo $headersession['nmsalesman']?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php
                            if($headersession){
                                $tglso=$headersession['tglso'];
                            }else{
                                $tglso=date('Y-m-d');
                            }
                            ?>
                            <label for="tglso" class="col-sm-4 control-label">Tanggal <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tglso" id="tglso" class="form-control input-sm datepicker" value="<?php echo $tglso?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group ">
                            <label for="pic" class="col-sm-4 control-label">PIC <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

									<?php
                                    if($headersession['pic']){
                                    ?>
                                    <select name="pic" id="pic" class="form-control input-sm select2">
                                        <?php
                                        foreach($pic as $kp=>$vp){
                                            $selected ='';
                                            if($headersession['pic'] == $vp->id_pic){
                                                $selected = 'selected="selected"';
                                            }
                                        ?>
                                        <option value="<?php echo $vp->id_pic?>" <?php echo $selected?>><?php echo $vp->nm_pic.' ('.$vp->divisi.'-'.$vp->jabatan.')'?></option>
                                        <?php } ?>
                                    </select>
                                    <?php
									}else{
										echo form_input(array('id'=>'kode_pic','name'=>'kode_pic','class'=>'form-control input-sm'));

									?>


                                    <?php } ?>
									<input type="hidden" name="pic_code" id="pic_code" class="form-control input-sm" readonly="readonly">
									<input type="hidden" name="pic_name" id="pic_name" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly">
                                    <input type="hidden" name="ppnso" id="ppnso" class="form-control input-sm" value="10" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <!--
                         <div class="form-group ">
                            <label for="dppso" class="col-sm-4 control-label">DPP <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="dppso" id="dppso" class="form-control input-sm" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        -->
                        <div class="form-group ">
                            <label for="flagppnso" class="col-sm-4 control-label">Flag PPN <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <?php
                                    if($headersession['nilaippn'] == 0){
                                    ?>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn"/>PPN (10%)
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn" checked="checked" />Tanpa PPN
                                    <input type="hidden" name="nilaippn" id="nilaippn" value="0" />
                                    <?php }else{ ?>
                                    <input type="radio" value="10" onclick="setppn(this.value)" name="ppn" checked="checked"/>PPN (10%)
                                    <input type="radio" value="0" onclick="setppn(this.value)" name="ppn"/>Tanpa PPN
                                    <input type="hidden" name="nilaippn" id="nilaippn" value="10" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="form-group ">
                            <label for="ppnso" class="col-sm-4 control-label">PPN<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="ppnso" id="ppnso" class="form-control input-sm" value="10" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="totalso" class="col-sm-4 control-label">Grand Total <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="totalso" id="totalso" class="form-control input-sm" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        -->
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
        <form id="form-detail-so" method="post">
        <table class="table table-bordered" width="100%">
            <tr>
                <th class="text-center" colspan="8">FORM ITEM DETAIL</th>
            </tr>
            <tr>
                <td width="13%"><b>PRODUCT SET</b></td>
                <td colspan="2" width="20%">
                    <select onchange="setitembarang()" id="item_brg_so" name="item_brg_so" class="form-control input-xs" style="width: 100%;" tabindex="-1" required>
                            <option value=""></option>
                            <?php
                            foreach(@$itembarang as $k=>$v){
                            ?>
                            <option value="<?php echo $v->id_barang; ?>" <?php echo set_select('nm_barang', $v->id_barang, isset($data->nm_barang) && $data->id_barang == $v->id_barang) ?>>
                                <?php echo $v->id_barang.' , '.$v->nm_barang ?>
                            </option>
                            <?php } ?>
                        </select>
                </td>
                <td width="5%" class="text-right"><b>HARGA</b></td>
                <td width="10%"><input type="text" name="harga" id="harga" class="form-control input-sm" readonly="readonly"></td>
                <td width="5%" class="text-right"><b>DISKON</b></td>
                <td width="7%">
                    <select class="form-control input-sm" name="tipe_diskon" id="tipe_diskon">
                        <option value="rp">Rp</option>
                        <option value="persen">%</option>
                    </select>
                </td>
                <td width="10%">
                    <input type="text" name="diskon" id="diskon" class="form-control input-sm" onkeyup="hitungso()" required="required" value="0">
                </td>
            </tr>
            <tr>
                <td></td>
                <td width="5%" class="text-center"><b>QTY ORDER</b></td>
                <td width="5%" class="text-center"><b>QTY AVL</b></td>
                <td width="5%" class="text-center"><b>QTY CONFIRM</b></td>
                <td width="5%" class="text-center"><b>QTY PENDING</b></td>
                <td width="5%" class="text-center"><b>QTY CANCEL</b></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td></td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_order" id="qty_order" class="form-control input-sm" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required="required">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_avl" id="qty_avl" class="form-control input-sm" readonly="readonly">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_supply" id="qty_supply" class="form-control input-sm" onkeyup="hitungso()" required="required">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_pending" id="qty_pending" class="form-control input-sm" onkeyup="hitungcancel()" required="required">
                </td>
                <td width="10%" class="text-center">
                    <input type="text" name="qty_cancel" id="qty_cancel" class="form-control input-sm" readonly="readonly">
                    <input type="hidden" name="nama_barang" id="nama_barang" class="form-control input-sm">
                    <input type="hidden" name="satuan" id="satuan" class="form-control input-sm">
                    <input type="hidden" name="jenis" id="jenis" class="form-control input-sm">
                    <input type="hidden" name="total" id="total" class="form-control input-sm">
                </td>
                <td class="text-center" colspan="2">
                    <button style="width: 100%;" class="btn btn-success btn-sm" type="submit" id="submit" name="save"><i class="fa fa-plus"></i> Tambah</button>
                </td>
            </tr>
        </table>
        </form>
        <div id="div-form">
        <table id="salesorderitemnya" class="table table-bordered table-striped" width="100%">
            <thead>
                <tr>
                    <th width="2%">#</th>
                    <th>Item Barang</th>
                    <th>Satuan</th>
                    <th>Stok Avl</th>
                    <th>Qty Order</th>
                    <th>Qty Confirm</th>
                    <th>Qty Pending</th>
                    <th>Qty Cancel</th>
                    <th>Harga</th>
                    <th>Diskon (%)</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand = 0;
                if(@$listitembarang){
                $n=1;
                foreach(@$listitembarang as $ks=>$vs){
                    $grand += $vs->subtotal;
                    $no = $n++;
                ?>
                <tr>
                    <td class="text-center"><?php echo $no?></td>
                    <td><?php echo $vs->id_barang.' / '.$vs->nm_barang?></td>
                    <td><?php echo $vs->satuan?></td>
                    <td><?php echo $vs->stok_avl?></td>
                    <td><?php echo $vs->qty_order?></td>
                    <td><?php echo $vs->qty_supply?></td>
                    <td><?php echo $vs->qty_pending?></td>
                    <td><?php echo $vs->qty_cancel?></td>
                    <td><?php echo formatnomor($vs->harga)?></td>
                    <td><?php echo $vs->diskon?></td>
                    <td class="text-right"><?php echo formatnomor($vs->subtotal)?></td>
                    <td class="text-center">
                        <a class="text-red" href="javascript:void(0)" title="Delete" onclick="delete_data('<?php echo $vs->no_so?>','<?php echo $vs->id_barang?>')"><i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="9" class="text-right">DPP : </th>
                    <th colspan="2" class="text-right"><?php echo formatnomor($grand)?>
                    <input type="hidden" name="grandtotalso" id="grandtotalso" value="<?php echo $grand?>"></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="9" class="text-right">PPN : </th>
                    <th colspan="2" class="text-right"><span id="ppnview"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="9" class="text-right">GRAND TOTAL : </th>
                    <th colspan="2" class="text-right"><span id="totalview"></span></th>
                    <th></th>
                </tr>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" type="button" onclick="saveheaderso()">
                            <i class="fa fa-save"></i><b> Simpan Data SO</b>
                        </button>
                    </th>
                </tr>
            </tfoot>

        </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
<style>
	.ui-autocomplete {
		  position: absolute;
		  top: 100%;
		  left: 0;
		  z-index: 1000;
		  float: left;
		  display: none;
		  min-width: 160px;
		  _width: 160px;
		  padding: 4px 0;
		  margin: 2px 0 0 0;
		  list-style: none;
		  background-color: #ffffff;
		  border-color: #ccc;
		  border-color: rgba(0, 0, 0, 0.2);
		  border-style: solid;
		  border-width: 1px;
		  -webkit-border-radius: 5px;
		  -moz-border-radius: 5px;
		  border-radius: 5px;
		  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		  -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		  -webkit-background-clip: padding-box;
		  -moz-background-clip: padding;
		  background-clip: padding-box;
		  *border-right-width: 2px;
		  *border-bottom-width: 2px;

		  .ui-menu-item > a.ui-corner-all {
			display: block;
			padding: 3px 15px;
			clear: both;
			font-weight: normal;
			line-height: 18px;
			color: #555555;
			white-space: nowrap;

			&.ui-state-hover, &.ui-state-active {
			  color: #ffffff;
			  text-decoration: none;
			  background-color: #0088cc;
			  border-radius: 0px;
			  -webkit-border-radius: 0px;
			  -moz-border-radius: 0px;
			  background-image: none;
			}
		  }
		}
</style>
<script type="text/javascript">
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
    $(document).ready(function() {
      
		$('#kode_pic').removeAttr('style', '').removeClass('chzn-done').data('chosen', null).next().remove();
		$('#kode_pic').autocomplete({
			source: function(request, response) {
				$.ajax({
					url: base_url + active_controller +'/get_pic_customer_new',
					dataType: "json",
					data: {
						company: $('#idcustomer').val(),
						term: request.term
					},
				  success: function (data){
					  response( data );
				   }
				});
			 },
			//source: base_url + active_controller +'/get_pic_customer_new/'+$('#idcustomer').val(),
			minLength: 3,
			autoFocus: true,
			select: function (event, ui) {
				//alert('Selcted : '+ui.item.id+' Val : '+ui.item.value);
				$('#pic_code').val(ui.item.id);
				$('#pic_name').val(ui.item.value);
			}
		});

        var gt = $('#grandtotalso').val();
        $('#dppso').val(gt);
        //console.log(gt);
        $("#item_brg_so,#idcustomer,#idsalesman,#pic").select2({
            placeholder: "Pilih",
            allowClear: true
        });
        $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
        var dataTableItemszzz = $('#salesorderitemnya').DataTable({
            "sDom": 'Bfrtip',
            "responsive": true,
            "bInfo":false,
            "bPaginate":false,
            "bFilter":false,
            "processing": false
        });
        sethitung();

    });
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function kembali(){
        window.location.href = siteurl+'salesorder';
    }
    function cancel(){
        $(".box").show();
        $("#form-area").hide();
    }
    function resetform(){
        $('#item_brg_so').val('');
        $('#nama_barang').val('');
        $('#harga').val(0);
        $('#satuan').val('');
        $('#jenis').val('');
        $('#qty_order').val(0);
        $('#qty_supply').val(0);
        $('#qty_pending').val(0);
        $('#qty_cancel').val(0);
        $('#qty_avl').val(0);
        $('#total').val(0);
    }
    function setppn(ppn){
        if(ppn == 10) {
            $('#nilaippn').val(10);
            sethitung();
        }else{
            $('#nilaippn').val(0);
            sethitung();
        }
    }
    function sethitung(){
        var gt = $('#grandtotalso').val();
        $('#dppso').val(gt);
        var npp = parseInt($('#nilaippn').val());//PPN APA TIDAK
        var dpp = parseInt($('#dppso').val());
        var ppn = 10*dpp*0.01;
        if(npp == 0){
            ppn = 0;
        }
        $('#ppnso').val(ppn);
        $('#flagppnso').val(ppn);
        $('#totalso').val(dpp+ppn);
        $('#ppnview').text(ppn);
        //$('#grandtotalso').val(dpp+ppn)
        $('#totalview').text(formatCurrency(dpp+ppn,',','.',0));
    }
    function setitembarang(){
        var idbarang = $('#item_brg_so').val();
        var qty = $('#qty_order').val();
        if(idbarang != ""){
            $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_item_barang",
                data:"idbarang="+idbarang,
                success:function(result){
                    var data = JSON.parse(result);
                    console.log(data);
                    $('#nama_barang').val(data.nm_barang)
                    $('#harga').val(data.harga);
                    $('#satuan').val(data.satuan);
                    $('#jenis').val(data.jenis);
                    $('#qty_avl').val(data.qty_avl);
                    $('#total').val(data.harga*qty);
                }
            });
        }
    }
    function getcustomer(){
        var idcus = $('#idcustomer').val();
        if(idcus != ''){
           $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_customer",
                data:"idcus="+idcus,
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nmcustomer').val(data.nm_customer);

                }
            });
        }
    }

    function getsalesman(){
        var idsls = $('#idsalesman').val();
        if(idsls != ''){
           $.ajax({
                type:"GET",
                url:siteurl+"salesorder/get_salesman",
                data:"idsales="+idsls,
                success:function(result){
                    var data = JSON.parse(result);
                    $('#nmsalesman').val(data.nama_karyawan);
                }
            });
        }
    }
    function hitungso(){
        var harga = parseInt($('#harga').val());
        var avl = parseInt($('#qty_avl').val());
        var qty = parseInt($('#qty_supply').val());
        var order = parseInt($('#qty_order').val());
        var diskon = parseInt($('#diskon').val());
        var blmdiskon = harga*qty;
        var diskonya;
        var total;
        if(filterAngka($('#qty_supply').val()) == 1 && filterAngka($('#diskon').val()) == 1){
        if($('#tipe_diskon').val() == 'persen'){
            if(diskon > 100){
                swal({
                        title: "Peringatan!",
                        text: "Diskon tipe persen",
                        type: "warning",
                        timer: 1500,
                        showConfirmButton: false
                    });
                $('#diskon').val('');
            }else{
                diskonya = blmdiskon*diskon/100;
                total = blmdiskon-diskonya;
                if(qty > avl){
                    swal({
                        title: "Peringatan!",
                        text: "Stok Available tidak mencukupi",
                        type: "warning",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#qty_supply').val(0);
                }else{
                    $('#total').val(total);
                }
            }
        }else{
            total = blmdiskon-diskon;
            if(qty > avl){
                    swal({
                        title: "Peringatan!",
                        text: "Stok Available tidak mencukupi",
                        type: "warning",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#qty_supply').val(0);
                }else{
                    $('#total').val(total);
                }
        }
        }else{
            var dis = $('#diskon').val();
            $('#diskon').val(dis.replace(/[^0-9]/g,''));
            var ang = $('#qty_supply').val();
            $('#qty_supply').val(ang.replace(/[^0-9]/g,''));
        }
        //
    }
    function hitungcancel(){
        var avl = parseInt($('#qty_avl').val());
        var qty = parseInt($('#qty_supply').val());
        var order = parseInt($('#qty_order').val());
        var pending = parseInt($('#qty_pending').val());
        var maks = qty+pending;
        var cancel = order-maks;
        if(filterAngka($('#qty_pending').val()) == 1){
            if(pending > avl){
                /*
                swal({
                    title: "Peringatan!",
                    text: "Qty available tidak cukup",
                    type: "warning",
                       timer: 1500,
                       showConfirmButton: false
                    });
                $('#qty_cancel').val(cancel);
                */
                swal({
                      title: "Peringatan !",
                      text: "Stok Available tidak cukup, lanjutkan ?",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Ya, Lanjutakan!",
                      cancelButtonText: "Tidak!",
                      closeOnConfirm: true,
                      closeOnCancel: true
                    },
                    function(isConfirm){
                      if (isConfirm) {
                        $('#qty_cancel').val(cancel);
                      }else{
                        //window.location.reload();
                      }
                    });
            }else{
                //if(pending != ""){
                    if(maks > order){
                        swal({
                            title: "Peringatan!",
                            text: "Qty melebihi order",
                            type: "warning",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        $('#qty_pending').val(0);
                    }else{
                        $('#qty_cancel').val(cancel);
                    }
                //}
            }
        }else{
            var ang = $('#qty_pending').val();
            $('#qty_pending').val(ang.replace(/[^0-9]/g,''));
        }
    }
    $('#form-detail-so').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#form-detail-so,#form-header-so").serialize();
        $.ajax({
            url: siteurl+"salesorder/saveitemso",
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
                    resetform();
                    setTimeout(function(){
                        window.location.reload();
                    },1600);
                    console.log(result.header);
                } else {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Di Simpan",
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
    });
    function delete_data(noso,id){
        //alert(id);
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
                    url: siteurl+'salesorder/hapus_item_so',
                    data :{"NO_SO":noso,"ID":id},
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

    function saveheaderso(){
        var formdata = $("#form-header-so").serialize();
        if($('#idcustomer').val() != ""){
        $.ajax({
            url: siteurl+"salesorder/saveheaderso",
            dataType : "json",
            type: 'POST',
            data: formdata,
            success: function(result){
                if(result.save=='1'){
                    swal({
                        title: "Sukses!",
                        text: JSON.stringify(result['msg']),
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(function(){
                        window.location.href=siteurl+'salesorder';
                    },1600);
                } else {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Di Simpan",
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
        }else{
           swal({
                title: "Peringatan!",
                text: "Silahkan pilih customer",
                type: "warning",
                timer: 1500,
                showConfirmButton: false
            });
        }
    }
    function formatCurrency(amount, decimalSeparator, thousandsSeparator, nDecimalDigits){
        var num = parseInt( amount );
        decimalSeparator = decimalSeparator || '.';
        thousandsSeparator = thousandsSeparator || ',';
        nDecimalDigits = nDecimalDigits == null? 2 : nDecimalDigits;
        var fixed = num.toFixed(nDecimalDigits);
        var parts = new RegExp('^(-?\\d{1,3})((?:\\d{3})+)(\\.(\\d{' + nDecimalDigits + '}))?$').exec(fixed);
        if(parts){
            return parts[1] + parts[2].replace(/\d{3}/g, thousandsSeparator + '$&') + (parts[4] ? decimalSeparator + parts[4] : '');
        }else{
            return fixed.replace('.', decimalSeparator);
        }
    }
</script>
