<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css'); ?>">
<form id="form-header-po" method="post">
<div class="nav-tabs-receiving">
    <div class="tab-content">
        <div class="tab-pane active" id="receiving">
            <div class="box box-primary">
                <div class="form-horizontal">
                <div class="box-body">
                    <?php
                              $session = $this->session->userdata('app_session');
                              $caba = $this->Purchaserequest_model->cek_data(array('kdcab' => $session['kdcab']), 'cabang');
                               ?>
                               <input type="hidden" name="kdcab" id="kdcab" class="form-control input-sm" value="<?php echo $caba->kdcab; ?>">
                              <input type="hidden" name="namacabang" id="namacabang" class="form-control input-sm" value="<?php echo $caba->namacabang; ?>">
                    <?php
                    $querys = $this->db->query("SELECT * FROM `supplier` WHERE id_supplier='$supplier'");

                    $rows = $querys->row();
                    ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="idsupplier" class="col-sm-4 control-label">Nama Supplier :</font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
                            <?php echo @$rows->id_supplier.' / '.@$rows->nm_supplier; ?>
                            <input type="hidden" name="idsupplier" id="idsupplier" value="<?php echo @$rows->id_supplier; ?>">
                            <input type="hidden" name="nmsupplier" id="nmsupplier" value="<?php echo @$rows->nm_supplier; ?>">
                            <input id="no_pr" type="hidden" name="no_pr" value="<?= $no_pr; ?>"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tglreceive" class="col-sm-4 control-label">Tanggal Receive :</font></label>
                            <div class="col-sm-8">
                              <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                  <input type="text" name="tglreceive" id="tglreceive" class="form-control input-sm datepicker" value="<?php echo date('Y-m-d'); ?>">
                              </div>

                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="tipekirim" class="col-sm-4 control-label">Container No :</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="container_no" id="container_no" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tipekirim" class="col-sm-4 control-label">Administrator :</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="administrator" id="administrator" value="<?= $cabang[0]->admcabang; ?>" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tipekirim" class="col-sm-4 control-label">Head :</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="head" id="head" value="<?= $cabang[0]->gudang; ?>" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                         <div class="form-group ">
                            <label for="tipekirim" class="col-sm-6 control-label">No. DO Supplier :</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="no_do_supplier" id="no_do_supplier" class="form-control input-sm">
                                </div>
                            </div>
                         </div>
                        <div class="form-group ">
                            <?php $tgldosupp = date('Y-m-d'); ?>
                            <label for="tgldosupp" class="col-sm-6 control-label">Tanggal DO Supplier :</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="tgldosupp" id="tgldosupp" class="form-control input-sm datepicker" value="<?php echo $tgldosupp; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tgldosupp = date('Y-m-d'); ?>
                            <label for="tgldosupp" class="col-sm-6 control-label">Date of unloading :</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="date_unloading" id="date_unloading" class="form-control input-sm datepicker" value="<?php echo $tgldosupp; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <?php $tgldosupp = date('Y-m-d'); ?>
                            <label for="tgldosupp" class="col-sm-6 control-label">Check Date :</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="date_check" id="date_check" class="form-control input-sm datepicker" value="<?php echo $tgldosupp; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="tipekirim" class="col-sm-6 control-label">Branch Manager :</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="branch" id="branch" value="<?= $cabang[0]->kepalacabang; ?>" class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="box box-default ">
    <div class="box-body">

       <table class="table table-bordered" width="100%" id="tambahan_receiving">
           <thead >
               <tr>
                   <th>NO</th>
                   <th>Nama Barang</th>
                   <th>QTY Invoice</th>
                   <th>QTY Picking List</th>
                   <th>Bagus</th>
                   <th>Rusak</th>
                   <th>Kosong</th>
                   <th>Keterangan</th>
               </tr>
           </thead>
           <tbody>
               <?php
               $no = 0;
                foreach (@$itembarang as $data => $datas) {
                  if ($datas->qty_acc == 0) {
                    $harga = 0;
                  }
                  else {
                    $harga = $datas->harga_rp / $datas->qty_acc;
                  }
                    ++$no; ?>
                    <input type="hidden" name="idet[]" value="<?= $datas->id_detail_po; ?>" />
                    <input type="hidden" name="idet_barang[]" value="<?= $datas->id_barang; ?>" />
                    <input type="hidden" name="nm_barangb[]" value="<?= $datas->nm_barang; ?>" />
                    <input type="hidden" name="qty_ib[]" value="<?= $datas->qty_acc; ?>" />
                    <input type="hidden" name="hargab[]" value="<?= $harga ?>" />
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $datas->nm_barang; ?></td>
                        <td><?= $datas->qty_acc; ?></td>
                        <td>
                            <input onkeyup="pl(<?= $no; ?>,this.value,'')" name="qty_plb[]" value="<?= $datas->qty_acc; ?>" class="form-control"/>
                        </td>
                        <td>
                            <input class="form-control input-sm qty_bagus<?= $no; ?>" readonly="" name="qty_bagus_t[]" type="text" value="<?= $datas->qty_acc; ?>" />
                        </td>
                        <td>
                            <input class="form-control input-sm qty_rusak<?= $no; ?>" readonly="" name="qty_rusak_t[]" type="text" value="0"/>
                        </td>
                        <td>
                            <input class="form-control input-sm qty_kosong<?= $no; ?>" readonly="" name="qty_kosong_t[]" type="text" value="0"/>
                        </td>
                        <td>
                        </td>
                    </tr>
               <?php
                    $query = $this->db->query("SELECT * FROM `barang_koli` WHERE id_barang='$datas->id_barang' AND deleted = '0'");

                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $row) {
                            ?>
                          <input type="hidden" name="id_barangc[]" value="<?= $datas->id_barang; ?>"/>
                          <input type="hidden" name="id_koli[]" value="<?= $row->id_koli; ?>"/>
                          <input type="hidden" name="nama_koli[]" value="<?= $row->nm_koli; ?>"/>
                          <input type="hidden" name="qty_i[]" value="<?= $datas->qty_acc; ?>" />
                          <tr>
                              <td></td>
                              <td>
                                  - <?= $row->nm_koli; ?>
                              </td>
                              <td><?= $datas->qty_acc; ?></td>
                              <td>
                                    <input class="form-control qty<?= $no; ?>" name="qty_pl[]" value="<?= $datas->qty_acc; ?>" />
                                </td>
                                <td>
                                    <input onkeyup="cek(<?= $no; ?>,<?= $query->num_rows(); ?>)" class="form-control barang<?= $no; ?>" name="qty_bagus[]" value="<?= $datas->qty_acc; ?>" />
                                </td>
                                <td>
                                    <input class="form-control rusak<?= $no; ?>" name="qty_rusak[]" value="0"/>
                                </td>
                                <td>
                                    <input class="form-control kosong<?= $no; ?>" name="qty_kosong[]" value="0"/>
                                </td>
                                <td>
                                    <input name="keterangan[]" />
                                </td>
                          </tr>
                          <?php
                        }
                    }
                } ?>
                <div style="display:none">
                <form id="tambahan_receiving_form" method="post">
                <tr>
                  <td colspan="8">TAMBAHAN:<hr></td>
                </tr>
                <div id="tambahan">
                  <input type="hidden" name="tambahan" id="tambahan_receiving_input" value="0">
                </div>
                </form>
           </tbody>
           <tfoot>
             <tr>
               <td>
                 KETERANGAN TAMBAHAN
               </td>
               <td colspan="7">
                 <textarea name="keterangan_tambahan" class="form-control textarea"></textarea>
               </td>
             </tr>
           </tfoot>
       </table>
        <div style="display:none">
         <button class="btn btn-success" data-toggle="" title="Add" onclick="add_list()" id="button_add_list" type="button"><i class="fa fa-plus">&nbsp;</i>Tambah Item</button>
         <a class="btn btn-danger" href="javascript:void(0)" data-toggle="" title="Add" onclick="remove_list()"><i class="fa fa-minus">&nbsp;</i>Kurangi Item</a>
         <button class="btn btn-primary" data-toggle="" title="Proses Tambahan" onclick="savetam()" id="button_set_list" type="button"><i class="fa fa-save">&nbsp;</i>Proses Tambahan</button>
        </div>
     </div>
    </div>
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
<script>
function add_list(){
  var tabdua = $('#tambahan_receiving');
  var jum_tam = parseInt($('#tambahan_receiving_input').val());
  jum_tam++;
  document.getElementById('tambahan_receiving_input').value = jum_tam;
  //tabdua.rows().remove();

  $(tabdua).append(
    '<tr><td><a class="text-red hapus_item_js" href="#" title="Hapus Item"><i class="fa fa-times"></i></a>'+jum_tam+'</td>'+

    '<td><select onkeyup="setitembarang(this.value,this.id)" id="item_brg_so" name="tambahan_id[]" class="form-control input-xs form_item_so select2" style="width: 100%;" tabindex="-1">'+
    '<option value=""></option>'+
    '<?php
    foreach(@$barang as $k=>$v){
      ?>'+
      '<option value='+'<?php echo $v->id_barang; ?>'+'>'+
      '<?php echo $v->id_barang ?>'+' , '+'<?php echo $v->nm_barang?>'+
      '</option>'+'<?php } ?>'+
      '</select></td>'+
      '<td><input name="qty_inv['+(jum_tam-1)+']" readonly></td>'+
      '<td><input onkeyup="pl(\'tam'+(jum_tam-1)+'\',this.value,\'tam\')" name="qty_plb_tambahan['+(jum_tam-1)+']" value="0" class="form-control" readonly></td>'+
      '<td><input class="form-control input-sm qty_bagus<?= $no; ?>" readonly="" name="qty_bagus_t_tambahan['+(jum_tam-1)+']" type="text" value="0" /></td>'+
      '<td><input class="form-control input-sm qty_rusak<?= $no; ?>" readonly="" name="qty_rusak_t_tambahan['+(jum_tam-1)+']" type="text" value="0"/></td>'+
      '<td><input class="form-control input-sm qty_kosong<?= $no; ?>" readonly="" name="qty_kosong_t_tambahan['+(jum_tam-1)+']" type="text" value="0"/></td>'+
      '<td><input name="keterangan_tambahan['+(jum_tam-1)+']" readonly /></td></tr>'


    );
    $(".form_item_so").select2({
      placeholder: "Pilih",
      allowClear: true
    });
  }

  function remove_list(){
    var tabdua = $('#listadjust').DataTable();
    tabdua.row($('#isi_so tr').length-1).remove().draw();
    var jum_tam = parseInt($('#tambahan_receiving_input').val());
    --jum_tam;
    document.getElementById('tambahan_receiving_input').value = jum_tam;
  }


  $(document).ready(function() {

    $("#tambahan_receiving").on("click",".hapus_item_js", function(e){ //user click on remove text
      e.preventDefault(); $(this).parent().parent().remove();
      console.log("111");
    });

  });


  $('#listadjust tbody').on( 'change', 'select.form_item_so', function () {
    var tabb = $('#listadjust').DataTable();
    var a = $(this).val();
    var satuan = 0;
    var stok = 0;
    var xxc = tabb.row( $(this).parents('tr') );

    if(a != ""){
      console.log('1');
      $.ajax({
        type:"GET",
        url:siteurl+"receiving/get_item_barang",
        data:"idbarang="+a,
        success:function(result){
          var data = JSON.parse(result);
          console.log(data);
          var idbar = "'"+data.id_barang+"'";
          var harga_net = (data.harga*((100-data.diskon_standar_persen)/100))*((100-data.diskon_promo_persen)/100)-(data.diskon_promo_rp);
          var satuan = data.satuan;
          var stok = 'Qty Stock:'+data.qty_stock+'<br>'+
          'Qty Avl&emsp;&nbsp;:'+data.qty_avl+'<br>';


          ;
          console.log(data.satuan);
          }
        });
      }

  } );

  function savetam(){

              var formdata = $("#form-header-po").serialize();
              console.log(formdata);
              $.ajax({
                  url: siteurl+"receiving/konfrimasi_tam/<?=$this->uri->segment(4)?>",
                  dataType : "json",
                  type: 'POST',
                  data: formdata,
                  success: function(result){
                      //console.log(result['msg']);
                      if(result.save=='1'){
                          swal({
                              title: "Sukses!",
                              text: result['msg'],
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
    function saveheaderpr(){

                var formdata = $("#form-header-po").serialize();
               // console.log(formdata);
                $.ajax({
                    url: siteurl+"receiving/konfrimasi_save",
                    dataType : "json",
                    type: 'POST',
                    data: formdata,
                    success: function(result){
                        //console.log(result['msg']);
                        if(result.save=='1'){
                            swal({
                                title: "Sukses!",
                                text: result['msg'],
                                type: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });
                            setTimeout(function(){
                                window.location.href=siteurl+'receiving';
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

    $(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });

    function pl(num, val, tam){
      if (tam != '') {

      }else {
        console.log(num);
        $('.qty'+num).val(val);
      }
    }

    function cek(num,jum){
        var valune = [];
        for (var i = 0; i < jum; i++) {
            valune.push(parseInt($('.barang'+num).eq(i).val()));

        }
        hasilMin=Math.min.apply(Math, valune);
        qtyy=parseInt($('.qty'+num).val());

        $('.qty_bagus'+num).val(hasilMin);

        console.log($('.qty'+num).val());

        for (var i = 0; i < jum; i++) {
            qty=parseInt($('.qty'+num).eq(i).val());
            bagus=parseInt($('.barang'+num).eq(i).val());

        }
    }
</script>
