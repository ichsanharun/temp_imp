<style type="text/css">
  #tabel-costing-cabang tr td{
    padding: 2px;
  }
</style>
<div class="box-body" id="div-get-cabang">
<?php //print_r(@$costcabang)?>
<div class="col-sm-9">
<form id="form-cabang-costing" method="post">
  <table class="table table-bordered" width="80%" id="tabel-costing-cabang">
    <tr>
      <td width="35%">Biaya Trucking Lokal</td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" id="biayaloglokal_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" name="kdcab" id="kdcab" class="form-control input-sm" value="<?php echo @$cabang->kdcab?>">
        <input type="hidden" name="idbarangcabang" id="idbarangcabang" class="form-control input-sm" value="<?php echo $this->uri->segment(3)?>">
        <input type="hidden" name="biayaloglokal" id="biayaloglokal" class="form-control input-sm" value="<?php echo @$cabang->biaya_logistik_lokal?>">
      </td>
    </tr>
    <tr>
      <td width="35%">Cost Logistik Lokal / m<sup>3</sup></td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" id="biayalogm3lokal_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" name="biayalogm3lokal" id="biayalogm3lokal" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">CBM Logistik Lokal / pcs</td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" id="cbmlokalpcs_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" name="cbmlokalpcs" id="cbmlokalpcs" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">TOTAL LOGISTIK</td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" id="totallogistik_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" name="totallogistik" id="totallogistik" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">HPP</td>
      <td width="20%"></td>
      <td></td>
      <td> 
        <input type="text" id="hppcost_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" name="hppcost" id="hppcost" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">PROFIT</td>
      <td width="20%"></td>
      <td>
        <div class="input-group"  style="width: 100%;">
            <select name="persenprofit" id="persenprofit" class="form-control input-sm" onchange="hitungprofit()">
              <option value="">Pilih</option>
              <?php 
              foreach(@$profit as $k=>$v){ 
                $selected='';
                if(@$costcabang){
                  if($v->id_group.'-'.$v->budget_margin == @$group->id_group.'-'.@$group->budget_margin){
                    $selected = 'selected="selected"';
                  }
                }
              ?>
              <option value="<?php echo $v->id_group.'-'.$v->budget_margin?>" <?php echo $selected?>><?php echo $v->nm_group.' ('.$v->budget_margin.'%)'?></option>
              <?php } ?>
            </select>
        </div>
      </td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costcabang->profit)?>" id="profit_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costcabang->profit?>" name="profit" id="profit" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">Harga Produk</td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costcabang->harga_product)?>" id="hargaproduk_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costcabang->harga_product?>" name="hargaproduk" id="hargaproduk" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">Harga Produk (Adjusment)</td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costcabang->harga_product_adj)?>" id="hargaprodukadj_view" class="form-control input-sm" onkeyup="adjhargaproduk()">
        <input type="hidden" value="<?php echo @$costcabang->harga_product_adj?>" name="hargaprodukadj" id="hargaprodukadj" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">Diskon Toko</td>
      <td width="20%"></td>
      <td>
        <div class="input-group">
            <input type="text" value="<?php echo @$costcabang->persen_diskon_toko?>" name="persendiskontoko" id="persendiskontoko" class="form-control input-sm" onkeyup="hitungdiskontoko()">
            <span class="input-group-addon">%</span>
        </div>
      </td>
      <td>
        <input value="<?php echo formatnomor(@$costcabang->diskon_toko)?>" type="text" id="diskontoko_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costcabang->diskon_toko?>" name="diskontoko" id="diskontoko" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="35%">Harga Pricelist</td>
      <td width="20%"></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costcabang->harga_pricelist)?>" id="hargapricelist_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costcabang->harga_pricelist?>" name="hargapricelist" id="hargapricelist" class="form-control input-sm">
      </td>
    </tr>
  </table>
  </form>
  <div class="text-right">
    <button class="btn btn-primary" type="button" onclick="saveproductcosting()"><i class="fa fa-save"></i> SIMPAN DATA</button></div>
  </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function(){
      getdatacostingcabang();
    });

    function hitungprofit(){
      var hpp =  parseFloat($('#hppcost').val());
      var persen = $('#persenprofit').val().split('-');
      var persenprofit = parseFloat(persen[1]);
      //var persenprofit = parseFloat($('#persenprofit').val());
      var profit = hpp*persenprofit/100;
      var hargaproduk = hpp+profit;
      $('#profit').val(profit.toFixed());
      $('#profit_view').val(formatCurrency(profit.toFixed(),',','.',0));

      $('#hargaproduk').val(hargaproduk.toFixed());
      $('#hargaproduk_view').val(formatCurrency(hargaproduk.toFixed(),',','.',0));

      $('#hargaprodukadj').val(hargaproduk.toFixed());
      $('#hargaprodukadj_view').val(formatCurrency(hargaproduk.toFixed(),',','.',0));
    }

    function hitungdiskontoko(){
      var hargaproduk = parseFloat($('#hargaprodukadj').val());
      var persendisk = parseFloat($('#persendiskontoko').val());
      var diskontoko = hargaproduk*persendisk/100;
      var pricelist = hargaproduk+diskontoko;
      $('#diskontoko').val(parseFloat(diskontoko.toFixed()));
      $('#diskontoko_view').val(formatCurrency(diskontoko.toFixed(),',','.',0));

      $('#hargapricelist').val(parseFloat(pricelist.toFixed()));
      $('#hargapricelist_view').val(formatCurrency(pricelist.toFixed(),',','.',0));
    }

    function saveproductcosting(){
     // var formdata = $("#form-product-costing").serialize();
      var formdata = $("#form-product-costing,#form-cabang-costing").serialize();
        $.ajax({
            url: siteurl+"productcosting/savecosting/"+'<?php $this->uri->segment(2)?>',
            dataType : "json",
            type: 'POST',
            data: formdata,
            success: function(result){
                if(result.type == 'success'){
                  swal({
                      title: "Sukses!",
                      text: result.pesan,
                      type: "success",
                      timer: 1500,
                      showConfirmButton: false
                  });
                  setTimeout(function(){
                      window.location.href = siteurl+'productcosting';
                  },1600);
                }else{
                  swal({
                      title: "Gagal!",
                      text: result.pesan,
                      type: "error",
                      timer: 1500,
                      showConfirmButton: false
                  });
                }
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
    };
  
    function adjhargaproduk(){
      $('#hargaprodukadj').val($('#hargaprodukadj_view').cleanVal());
    }
  </script>