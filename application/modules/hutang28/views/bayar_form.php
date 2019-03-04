<div class="nav-tabs-cabang">
    <!-- /.tab-content -->
    <div class="tab-content">
        <div class="tab-pane active" id="cabang">
        <!-- Biodata Mitra -->
            <div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;">
            </div>
            
            <script>
                var currentValue = 0;
                function handleClick(myRadio) {
                    currentValue = myRadio.value;
                    if( currentValue=="2" ){
                        document.getElementById( 'pilih_bank' ).style.display = 'block';
                        document.getElementById( 'bukti' ).style.display = 'block';
                    }else{
                        document.getElementById( 'pilih_bank' ).style.display = 'none';
                        document.getElementById( 'bukti' ).style.display = 'none';
                    }
                }
            </script>
            <!-- form start-->
            <div class="box box-primary">
            <form id="input" method="post" class="form-horizontal">
                <div class="box-body">
                <?php
                $query = $this->db->query("SELECT * FROM `trans_po_payment` as a, trans_po_header as b WHERE a.id='$id' AND a.no_po=b.no_po  ");

                $row = $query->row();

                $querycek = $this->db->query("SELECT * FROM `trans_po_payment` WHERE no_po='$row->no_po'  ORDER BY `trans_po_payment`.`perkiraan_bayar`  ASC ");
                if ($querycek->num_rows() > 1) {
                    $row_c = $querycek->row();
                    if ($id == $row_c->id) {
                        ?>
                        <input type="hidden" name="keterangan" value="Uang Muka" />
                        <?php
                    } else {
                        $querycek_re = $this->db->query("SELECT * FROM `trans_receive` WHERE po_no='$row->no_po'");
                        if ($querycek->num_rows() > 0) {
                            ?>
                                <input type="hidden" name="keterangan" value="Hutang" />
                            <?php
                        } else {
                            ?>
                                <input type="hidden" name="keterangan" value="Pelunasan Uang Muka" />
                            <?php
                        }
                    }
                } else {
                    ?>
                    <input type="hidden" name="keterangan" value="Pelunasan Uang Muka" />
                    <?php
                }

                ?>
                <input type="hidden" name="id" value="<?= $id; ?>" />
                <input type="hidden" name="no_po" value="<?= $row->no_po; ?>" />
                <div class="form-group "> 
                    <label for="name_cbm" class="col-sm-2 control-label">KD Supplier<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                        <input type="text" class="form-control"  name="id_supplier" readonly="" value="<?= $row->id_supplier; ?>"  >
                        </div>
                    </div>                                   
                </div>
                <div class="form-group "> 
                    <label for="name_cbm" class="col-sm-2 control-label">Supplier<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                        <input type="text" class="form-control"  name="supplier" readonly="" value="<?= $row->nm_supplier; ?>"  >
                        </div>
                    </div>                                   
                </div>
                <div class="form-group "> 
                    <label for="name_cbm" class="col-sm-2 control-label">No. Invoice<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                        <input type="text" class="form-control"  name="no_invoice" readonly="" value="<?= @get_invoice($row->no_po); ?>"  >
                        </div>
                    </div>                                   
                </div>
                <div class="form-group "> 
                    <label for="name_cbm" class="col-sm-2 control-label">Tipe Pembayaran<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            
                            <div class="radio">
                                <label>
                                  <input type="radio" name="myRadios" onclick="handleClick(this);"  value="1">
                                  KAS
                                </label>
                              </div>
                              <div class="radio">
                                <label>
                                  <input type="radio" name="myRadios" onclick="handleClick(this);"  value="2">
                                  BANK
                                </label>
                              </div>
                        </div>
                    </div>                                   
                </div>
                <div id="pilih_bank" class="form-group " style="display: none"> 
                    <label for="name_cbm" class="col-sm-2 control-label">Pilih Bank<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                            <select class="form-control" name="no_perkiraan">
                                <?php
                                $bln = date('n');
                                $thn = date('Y');
                                $session = $this->session->userdata('app_session');
                                $kdcab = $session['kdcab'];
                                $query = $this->db->query("SELECT * FROM `coa` WHERE `kdcab` LIKE '%$kdcab-A%' AND `level` LIKE '%5%' AND `no_perkiraan` LIKE '%1102%' AND `bln` LIKE '%$bln%' AND `thn` LIKE '%$thn%' ");

                                foreach ($query->result() as $row) {
                                    ?>
                                <option value="<?= $row->no_perkiraan; ?>"><?= $row->nama; ?></option>
                                <?php
                                } ?>
                              </select>
                        </div>
                    </div>                                   
                </div>
                <div id="bukti" class="form-group " style="display: none"> 
                    <label for="name_cbm" class="col-sm-2 control-label">Nomor bukti transfer<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                        <input type="text" class="form-control"  name="bukti"  >
                        </div>
                    </div>                                   
                </div>
                <div class="form-group "> 
                    <label for="name_cbm" class="col-sm-2 control-label">Jumlah<font size="4" color="red"><B>*</B></font></label>
                    <div class="col-sm-5">
                        <div class="input-group">
                        <input type="text" class="form-control" name="jumlah" maxlength="45" >
                        </div>
                    </div>                                   
                </div>

                </div>
            <?= form_close(); ?>
            </div>
        <!-- Biodata Mitra -->
        <table id="prdetailitem" class="table table-bordered table-striped" width="100%">
            
            <tfoot>
                <tr>
                    <th class="text-right" colspan="13">
                        <button class="btn btn-danger" onclick="kembali()">
                            <i class="fa fa-refresh"></i><b> Kembali</b>
                        </button>
                        <button class="btn btn-primary" onclick="save()"  >
                            <i class="fa fa-save"></i><b> Simpan Konfirmasi</b>
                        </button>
                    </th>
                </tr>
            </tfoot>
        </table>
        </div>                                    
        
    </div>
    <!-- /.tab-content -->
</div>
<script>
    function save(){
            
                var formdata = $("#input").serialize();
               // console.log(formdata);
                $.ajax({
                    url: siteurl+"hutang/bayar_save",
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
                                window.location.href=siteurl+'hutang';
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