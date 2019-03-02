<script src="<?php echo base_url('assets/js/jquery.mask.min.js');?>"></script>
<style type="text/css">
  #tabel-costing tr td{
    padding: 2px;
  }
  #tabel-info-kurs tr td{
    padding: 2px;
    background-color: #f2f2f2;
  }
  #tabel-info-kurs tr th{
    padding: 5px;
    background-color: #3C8DBC;
    color : #fff;
  }
</style>
<div class="box box-primary">
<div class="box-body">
<div class="col-sm-12">
  <?php //print_r(@$costing)?>
  <!--
  <div class="form-group ">
    <label for="nm_customer" class="col-sm-2 control-label">Nama Customer <font size="4" color="red"><B>*</B></font></label>
    <div class="col-sm-3">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" class="form-control" id="nm_customer" name="nm_customer" required="">
        </div>
    </div>
  </div>
  -->
  
  <div class="col-sm-9">
  <form id="form-product-costing" method="post">
  <table width="100%" id="tabel-costing" class="table table-bordered">
    <tr>
      <th colspan="4" style="background-color: #f2f2f2;"><i class="fa fa-plus"></i> PRODUK </th>
    </tr>
    <tr>
      <td width="35%">Kode Produk</td>
      <td colspan="3">
        <select name="idbarang" id="idbarang" class="form-control input-sm select2" style="width: 100%;" onchange="setproductcosting()">
          <option value="">Pilih</option>
          <?php 
          foreach(@$barang as $kb=>$vb){ 
            $selected ='';
            if($this->uri->segment(3) != "" && $this->uri->segment(3) == $vb->id_barang){
              $selected = 'selected="selected"';
            }
          ?>
          <option value="<?php echo $vb->id_barang?>" <?php echo $selected?>><?php echo $vb->id_barang.' / '.$vb->model.' / '.$vb->brand?></option>
          <?php } ?>
        </select>
      </td>
    </tr>
    <tr>
      <td width="35%">Harga Beli Real</td>
      <td width="20%">
        <select class="form-control input-sm" id="matauang" name="matauang" onchange="hitungidrreal()">
          <option value="">Pilih</option>
          <?php 
          $hargareal = '';
          $text='';
          if(@$costing){
          if(@$costing->mata_uang == "USD"){
            $hargareal = @$costing->harga_beli_us;
            $text='USD';
          ?>
          <option value="RMB">YUAN</option>
          <option value="USD" selected="selected">USD</option>
          <?php }elseif(@$costing->mata_uang == "RMB"){ $hargareal = @$costing->harga_beli_yuan;$text='RMB';?>
          <option value="RMB" selected="selected">YUAN</option>
          <option value="USD">USD</option>
          <?php } ?>
          <?php }else{ ?>
          <option value="RMB">YUAN</option>
          <option value="USD">USD</option>
          <?php } ?>
        </select>
      </td>
      <td>
        <div class="input-group">
            <span class="input-group-addon" id="span-mu"><?php echo $text?></span>
            <input type="text" id="hargabelireal_view" class="form-control input-sm" onkeyup="hitungidrreal()" value="<?php echo formatnomor($hargareal)?>">
            <input type="hidden" id="hargabelireal" name="hargabelireal" class="form-control input-sm" value="<?php echo $hargareal?>">
        </div>
      </td>
      <td>
        <input type="text" id="hargabelirealidr_view" class="form-control input-sm" readonly="readonly" value="<?php echo formatnomor(@$costing->harga_beli_rp)?>">
        <input type="hidden" name="hargabelirealidr" id="hargabelirealidr" class="form-control input-sm" value="<?php echo @$costing->harga_beli_rp?>">
      </td>
    </tr>
    <tr>
      <td width="20%">Harga Beli Invoice</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" id="hargabeliinvc_view" class="form-control input-sm" onkeyup="hitungtotal()" value="<?php echo formatnomor(@$costing->harga_beli_invoice)?>">
        <input type="hidden" name="hargabeliinvc" id="hargabeliinvc" class="form-control input-sm" value="<?php echo @$costing->harga_beli_invoice?>">
      </td>
    </tr>
    <tr>
      <td width="20%">PPN (10%)</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" id="ppnprodcost_view" class="form-control input-sm" readonly="readonly" value="<?php echo formatnomor(@$costing->ppn)?>">
        <input type="hidden" name="ppnprodcost" id="ppnprodcost" class="form-control input-sm" value="<?php echo @$costing->ppn?>">
      </td>
    </tr>
    <tr>
      <td width="20%">PPH (10%)</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" id="pphprodcost_view" class="form-control input-sm" readonly="readonly" value="<?php echo formatnomor(@$costing->pph)?>">
        <input type="hidden" name="pphprodcost" id="pphprodcost" class="form-control input-sm" value="<?php echo @$costing->pph?>">
      </td>
    </tr>
    <tr>
      <td width="20%">TOTAL</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" id="totalhargabeli_view" class="form-control input-sm" readonly="readonly" value="<?php echo formatnomor(@$costing->total_harga_beli)?>">
        <input type="hidden" name="totalhargabeli" id="totalhargabeli" class="form-control input-sm" value="<?php echo @$costing->total_harga_beli?>">
      </td>
    </tr>
    <tr>
      <th colspan="4" style="background-color: #f2f2f2;"><i class="fa fa-plus"></i> LOGISTIK INTERNATIONAL</th>
    </tr>
    <tr>
      <td width="20%">Ocean Feight (Biaya Kapal)</td>
      <td></td>
      <td>
        <div class="input-group">
            <span class="input-group-addon">USD</span>
            <input type="text" id="biayakapal_view" class="form-control input-sm" onkeyup="hitungbykapal()" value="<?php echo formatnomor(@$costing->log_biaya_kapal_usd)?>">
            <input type="hidden" name="biayakapal" id="biayakapal" class="form-control input-sm" value="<?php echo @$costing->log_biaya_kapal_usd?>">
        </div>
      </td>
      <td>
        <input type="text" id="biayakapalidr_view" class="form-control input-sm" readonly="readonly" value="<?php echo formatnomor(@$costing->log_biaya_pengapalan)?>">
        <input type="hidden" name="biayakapalidr" id="biayakapalidr" class="form-control input-sm" value="<?php echo @$costing->log_biaya_kapal?>">
      </td>
    </tr>
    <tr>
      <td width="20%">Shipping Cost (Biaya Pengkapalan)</td>
      <td></td>
      <td>
        <div class="input-group">
            <span class="input-group-addon">USD</span>
            <input type="text" id="biayapengkapalan_view" class="form-control input-sm" onkeyup="hitungbypengkapalan()" value="<?php echo formatnomor(@$costing->log_biaya_pengapalan_usd)?>">
            <input type="hidden" name="biayapengkapalan" id="biayapengkapalan" class="form-control input-sm" value="<?php echo @$costing->log_biaya_pengapalan_usd?>">
        </div>
      </td>
      <td>
        <input type="text" id="biayapengkapalanidr_view" class="form-control input-sm" readonly="readonly" value="<?php echo formatnomor(@$costing->log_biaya_pengapalan)?>">
        <input type="hidden" name="biayapengkapalanidr" id="biayapengkapalanidr" class="form-control input-sm" value="<?php echo @$costing->log_biaya_pengapalan?>">
      </td>
    </tr>
    <tr>
      <td width="20%">Fee Agent</td>
      <td></td>
      <td>
        <div class="input-group">
            <span class="input-group-addon">USD</span>
            <input type="text" value="<?php echo formatnomor(@$costing->log_fee_agent_china_usd)?>" id="biayafeeagent_view" class="form-control input-sm" onkeyup="hitungbyfeeagent()">
            <input type="hidden" value="<?php echo @$costing->log_fee_agent_china_usd?>" name="biayafeeagent" id="biayafeeagent" class="form-control input-sm">
        </div>
      </td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_fee_agent_china)?>" id="biayafeeagentidr_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->log_fee_agent_china_usd?>" name="biayafeeagentidr" id="biayafeeagentidr" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="20%">PPJK (Handling Pabean)</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_ppjk)?>" id="biayappjk_view" class="form-control input-sm" onkeyup="hitungrealinvoice()">
        <input type="hidden" value="<?php echo @$costing->log_ppjk?>" name="biayappjk" id="biayappjk" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="20%">TOTAL REAL INVOICE</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_total_invoice)?>" id="totalrealinvoice_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->log_total_invoice?>" name="totalrealinvoice" id="totalrealinvoice" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="20%">Biaya Tidak Terduga</td>
      <td>
        <select name="jenisbiayatt" id="jenisbiayatt" class="form-control input-sm">
          <option value="">Pilih</option>
          <option value="1">Notul</option>
          <option value="2">Detention</option>
          <option value="3">Penalty</option>
        </select>
      </td>
      <td>
        <div class="input-group">
            <input type="text" value="<?php echo @$costing->persen_tak_terduga?>" name="persenbiayatt" id="persenbiayatt" class="form-control input-sm" onkeyup="hitungpersentt()">
            <span class="input-group-addon">%</span>
        </div>
      </td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_biaya_tdk_terduga)?>" id="biayatakterduga_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->log_biaya_tdk_terduga?>" name="biayatakterduga" id="biayatakterduga" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="20%">Total Biaya Logistik</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->logistic_cost)?>" id="totalbiayalog_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->logistic_cost?>" name="totalbiayalog" id="totalbiayalog" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="20%">Total CBM 1 Container</td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo @$costing->log_cbm_1_container?>" name="cbm1container" id="cbm1container" class="form-control input-sm" onkeyup="hitunglogm3()">
      </td>
    </tr>
    <tr>
      <td width="20%">Logistik Cost / m<sup>3</sup></td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_cost_per_m3)?>" id="biayalogm3_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->log_cost_per_m3?>" name="biayalogm3" id="biayalogm3" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <th colspan="4" style="background-color: #f2f2f2;"><i class="fa fa-plus"></i> BIAYA LOGISTIK INTERNATIONAL</th>
    </tr>
    <tr>
      <td width="20%">Logistik Cost / m<sup>3</sup></td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_cost_per_m3)?>" id="biayalogm3next_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->log_cost_per_m3?>" name="biayalogm3next" id="biayalogm3next" class="form-control input-sm">
      </td>
    </tr>
    <tr>
      <td width="20%">Volume Produk (CBM)</sup></td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo @$costing->volume_produk_cbm?>" name="volumeprodukcbm" id="volumeprodukcbm" class="form-control input-sm" onkeyup="hitungbiayalogpcs()">
      </td>
    </tr>
    <tr>
      <td width="20%">Biaya Logistik / pcs</sup></td>
      <td></td>
      <td></td>
      <td>
        <input type="text" value="<?php echo formatnomor(@$costing->log_cost_pcs)?>" id="biayalogpcs_view" class="form-control input-sm" readonly="readonly">
        <input type="hidden" value="<?php echo @$costing->log_cost_pcs?>" name="biayalogpcs" id="biayalogpcs" class="form-control input-sm">
      </td>
    </tr>
  </table>
  </div>
  
  <div class="col-sm-3">
    <table id="tabel-info-kurs" class="table table-bordered" width="100%">
      <tr>
        <th class="text-center">KURS</th>
        <th class="text-center">1 YUAN</th>
        <th class="text-center">1 USD</th>
      </tr>
      <tr>
        <td><center><b>IDR</b></center></td>
        <td class="text-center"><?php echo formatnomor(@$yuan->kurs)?></td>
        <td class="text-center"><?php echo formatnomor(@$usd->kurs)?></td>
      </tr>
    </table>
  </div>
</div>
</div>
</div>

<div class="nav-tabs-custom" id="costing-cabang">
    <ul class="nav nav-tabs">
      <?php
        $n=1;
        foreach(@$cabang as $kc=>$vc){
          $no = $n++;
          $activ = '';
          if($no == 1){
            $activ = 'active';
          }
      ?>
        <li class="<?php echo $activ?>"><a href="<?php echo site_url('productcosting/costingcabang/'.$this->uri->segment(3).'/'.$vc->kdcab)?>" aria-expanded="true" id="<?php echo $vc->kdcab?>_tab" data-toggle="tabajax" data-target="#<?php echo $vc->kdcab?>"> <?php echo $vc->namacabang?> </a></li>
      <?php } ?>
    </ul>
    <div class="tab-content">
        <?php
        $v = 1;
        foreach(@$cabang as $kc=>$vc){
          $vo = $v++;
          $activ = '';
          if($vo == 1){
            $activ = 'active';
          }
        ?>
        <div class="tab-pane <?php echo $activ?>" id="<?php echo $vc->kdcab?>">
          
        </div>
        <?php } ?>
    </div>
</div>
</form>

<script type="text/javascript">
  $('[data-toggle="tabajax"]').click(function(e){
    var idbrg = $('#idbarang').val();
    if(idbrg != ""){
      var $this = $(this),
          loadurl = $this.attr('href'),
          targ = $this.attr('data-target');
      $.get(loadurl, function(data) {
          $('#div-get-cabang').remove();
          $(targ).html(data);
      });

      $this.tab('show');
      getdatacostingcabang();
    }else{
      swal({
            title: "Peringatan!",
            text: "Silahkan pilih Produk",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
        });

    }
    return false;
  });

  function hitungidrreal(){
    var mt = $('#matauang').val();
    $('#hargabelireal').val($('#hargabelireal_view').cleanVal());
    var rl = $('#hargabelireal').val();
    console.log(rl);
    //var rl = $('#hargabelireal').val();$('#hargabelireal').val($('#hargabelireal_view').cleanVal());
    //$('#totalamount').html(formatCurrency(jumTotal, ',', '.', 2));
    var yuan = '<?php echo @$yuan->kurs?>';
    var usd = '<?php echo @$usd->kurs?>';
    if(mt != ""){
      if(mt == "RMB"){
        $('#span-mu').text('RMB');
        $('#hargabelirealidr_view').val(formatCurrency(yuan*rl,',','.',0));
        $('#hargabelirealidr').val(yuan*rl);

        $('#ppnprodcost_view').val(formatCurrency(yuan*rl*0.1,',','.',0));
        $('#pphprodcost_view').val(formatCurrency(yuan*rl*0.1,',','.',0));
        $('#ppnprodcost').val(yuan*rl*0.1);
        $('#pphprodcost').val(yuan*rl*0.1);
        hitungtotal();
      }else if(mt == "USD"){
        $('#span-mu').text('USD');
        $('#hargabelirealidr_view').val(formatCurrency(usd*rl,',','.',0));
        $('#hargabelirealidr').val(usd*rl);

        $('#ppnprodcost_view').val(formatCurrency(usd*rl*0.1,',','.',0));
        $('#pphprodcost_view').val(formatCurrency(usd*rl*0.1,',','.',0));
        $('#ppnprodcost').val(usd*rl*0.1);
        $('#pphprodcost').val(usd*rl*0.1);
        hitungtotal();
      }
    }else{
      $('#span-mu').text('');
    }
  }

  function hitungtotal(){
    var rl = parseFloat($('#hargabelirealidr').val());
    var ppn = parseFloat($('#ppnprodcost').val());
    var pph = parseFloat($('#pphprodcost').val());
    var tt = rl+ppn+pph;
    $('#totalhargabeli').val(parseFloat(tt));
    $('#totalhargabeli_view').val(formatCurrency(parseFloat(tt),',','.',0));
    $('#hargabeliinvc').val($('#hargabeliinvc_view').cleanVal());
  }

  function hitungbykapal(){
    var usd = '<?php echo @$usd->kurs?>';
    $('#biayakapal').val($('#biayakapal_view').cleanVal());
    var bk = $('#biayakapal').val();
    $('#biayakapalidr').val(usd*bk);
    $('#biayakapalidr_view').val(formatCurrency(usd*bk,',','.',0));
  }
  function hitungbypengkapalan(){
    var usd = '<?php echo @$usd->kurs?>';
    $('#biayapengkapalan').val($('#biayapengkapalan_view').cleanVal());
    var bpk = $('#biayapengkapalan').val();
    $('#biayapengkapalanidr').val(usd*bpk);
    $('#biayapengkapalanidr_view').val(formatCurrency(usd*bpk,',','.',0));
  }
  function hitungbyfeeagent(){
    var usd = '<?php echo @$usd->kurs?>';
    $('#biayafeeagent').val($('#biayafeeagent_view').cleanVal());
    var bfa = $('#biayafeeagent').val();
    $('#biayafeeagentidr').val(usd*bfa);
    $('#biayafeeagentidr_view').val(formatCurrency(usd*bfa,',','.',0));
  }
  function hitungrealinvoice(){
    var kpl = parseFloat($('#biayakapalidr').val());
    var pkpl = parseFloat($('#biayapengkapalanidr').val());
    var fee = parseFloat($('#biayafeeagentidr').val());
    $('#biayappjk').val($('#biayappjk_view').cleanVal());
    var ppjk = parseFloat($('#biayappjk').val());
    var totrealinvc = kpl+pkpl+fee+ppjk;
    $('#totalrealinvoice').val(parseFloat(totrealinvc));
    $('#totalrealinvoice_view').val(formatCurrency(parseFloat(totrealinvc),',','.',0));
  }
  function hitungpersentt(){
    var tivc = parseFloat($('#totalrealinvoice').val());
    var persen = parseFloat($('#persenbiayatt').val());
    $('#biayatakterduga').val(parseFloat(tivc*persen/100));
    var biayatt = parseFloat($('#biayatakterduga').val());

    var totbylog = tivc+biayatt;
    $('#totalbiayalog').val(parseFloat(totbylog));

    $('#biayatakterduga_view').val(formatCurrency(parseFloat(tivc*persen/100),',','.',0));
    $('#totalbiayalog_view').val(formatCurrency(parseFloat(tivc)+parseFloat(biayatt),',','.',0));
  }
  function hitunglogm3(){
    var totlog = parseFloat($('#totalbiayalog').val());
    var cbm = parseFloat($('#cbm1container').val());
    $('#biayalogm3').val(parseFloat(totlog/cbm).toFixed());
    $('#biayalogm3_view').val(formatCurrency(parseFloat(totlog/cbm).toFixed(),',','.',0));
    
    $('#biayalogm3next').val(parseFloat(totlog/cbm).toFixed());
    $('#biayalogm3next_view').val(formatCurrency(parseFloat(totlog/cbm).toFixed(),',','.',0));
  }
  function hitungbiayalogpcs(){
    var bym3 = parseFloat($('#biayalogm3next').val());
    var vol = parseFloat($('#volumeprodukcbm').val());
    $('#biayalogpcs').val(parseFloat(bym3*vol).toFixed());
    $('#biayalogpcs_view').val(formatCurrency(parseFloat(bym3*vol).toFixed(),',','.',0));
  }

  jQuery(function($){
    $('#hargabelireal_view').mask('000.000.000.000.000', {reverse: true});
    $('#hargabeliinvc_view').mask('000.000.000.000.000', {reverse: true});
    $('#biayakapal_view').mask('000.000.000.000.000', {reverse: true});
    $('#biayapengkapalan_view').mask('000.000.000.000.000', {reverse: true});
    $('#biayafeeagent_view').mask('000.000.000.000.000', {reverse: true});
    $('#biayappjk_view').mask('000.000.000.000.000', {reverse: true});
    $("#idbarang").select2({
          placeholder: "Pilih",
          allowClear: true
      });
  });

  function formatCurrency(amount, decimalSeparator, thousandsSeparator, nDecimalDigits){  
    var num = parseFloat( amount );
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

  function getdatacostingcabang(){
      $('#hargaprodukadj_view').mask('000.000.000.000.000', {reverse: true});
      $('#biayaloglokal_view').val(formatCurrency($('#biayaloglokal').val(),',','.',0));
      var truclokal = parseFloat($('#biayaloglokal').val());
      var cbm1 = parseFloat($('#cbm1container').val());
      var vol = parseFloat($('#volumeprodukcbm').val());
      var logpcs = parseFloat($('#biayalogpcs').val());
      var hargabeli = parseFloat($('#totalhargabeli').val());
      
      if(!isNaN(cbm1)){
        var m3lokal = truclokal/cbm1;
        $('#biayalogm3lokal').val(m3lokal.toFixed());
        $('#biayalogm3lokal_view').val(formatCurrency(m3lokal.toFixed(),',','.',0));
        //formatCurrency($('#biayaloglokal').val(),',','.',2)
        var biayalokalm3 = parseFloat($('#biayalogm3lokal').val());
        var logpcslokal = biayalokalm3*vol;
        $('#cbmlokalpcs').val(logpcslokal.toFixed());
        $('#cbmlokalpcs_view').val(formatCurrency(logpcslokal.toFixed(),',','.',0));
        var logistik = parseFloat(logpcs+logpcslokal);
        var hpp = parseFloat(hargabeli+logistik);
        $('#totallogistik').val(logistik.toFixed());
        $('#totallogistik_view').val(formatCurrency(logistik.toFixed(),',','.',0));
        $('#hppcost').val(hpp.toFixed());
        $('#hppcost_view').val(formatCurrency(hpp.toFixed(),',','.',0));
        /*
        console.log("TRUK  => "+truclokal);
        console.log("CBM => "+cbm1);
        console.log("VOL => "+vol);
        console.log("LOKAL M3 => "+biayalokalm3);
        */
      }else{
        swal({
            title: "Peringatan!",
            text: "Lengkapi data Logistik Internasional",
            type: "warning",
            timer: 1500,
            showConfirmButton: false
        });
      }
    }

  function setproductcosting(){
    var idbarang = $('#idbarang').val();
    window.location.href = siteurl+"productcosting/setdata/"+idbarang;
  }
</script>