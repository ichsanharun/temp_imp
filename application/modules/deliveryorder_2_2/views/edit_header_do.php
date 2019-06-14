<div class="form-horizontal">
    <div class="box-body" style="border:solid 1px #fff;">
        <form id="form-edit-header-do" method="post">
            <div class="col-sm-6">
                <div class="form-group">
                <label for="idcustomer" class="col-sm-4 control-label">Nama Customer </label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                        <?php echo ": ".$do_data->nm_customer?>
                        <input type="hidden" name="no_do_edit" id="no_do_edit" value="<?php echo $do_data->no_do?>">
                    </div>
                </div>
                <div class="form-group ">
                    <label for="idsalesman" class="col-sm-4 control-label">Nama Salesman </label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                        <?php echo ": ".strtoupper($do_data->nm_salesman)?>
                    </div>
                </div>
                <div class="form-group ">
                    <?php $tglso=date('Y-m-d')?>
                    <label for="tgldo" class="col-sm-4 control-label">Tanggal DO </label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                        <?php echo ": ".date('d M Y',strtotime($do_data->tgl_do))?>
                    </div>
                </div>
            </div>  
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="tipekirim_edit" class="col-sm-4 control-label">Tipe Kirim</label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <select name="tipekirim_edit" id="tipekirim_edit" class="form-control input-sm">
                                <option value="">Pilih</option>
                                <?php
                                foreach(tipe_pengiriman() as $k=>$v){
                                    $selected = '';
                                    if($do_data->tipe_pengiriman == $k){
                                        $selected='selected="selected"';
                                    }
                                    ?>
                                <option value="<?php echo $k?>" <?php echo $selected?>><?php echo $v?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php if($do_data->tipe_pengiriman == "SENDIRI"){ ?>
                <div class="form-group">
                    <label for="supir_do_edit" class="col-sm-4 control-label">Nama Supir </label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <select name="supir_do_edit" id="supir_do_edit" class="form-control input-sm">
                                <option value="">Pilih</option>
                                <?php
                                foreach(@$driver as $kd=>$vd){
                                    $selected = '';
                                    if($vd->id_karyawan.'|'.$vd->nama_karyawan == $do_data->id_supir.'|'.$do_data->nm_supir){
                                        $selected='selected="selected"';
                                    }
                                    ?>
                                    <option value="<?php echo $vd->id_karyawan.'|'.$vd->nama_karyawan?>" <?php echo $selected?>><?php echo $vd->nama_karyawan?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php $tglso=date('Y-m-d')?>
                        <label for="kendaraan_do_edit" class="col-sm-4 control-label">Kendaraan </label>
                        <div class="col-sm-8" style="padding-top: 8px;">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                                <select name="kendaraan_do_edit" id="kendaraan_do_edit" class="form-control input-sm">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach(@$kendaraan as $kk=>$vk){
                                        $selected = '';
                                        if($vk->id_kendaraan.'|'.$vk->nm_kendaraan == $do_data->id_kendaraan.'|'.$do_data->ket_kendaraan){
                                            $selected='selected="selected"';
                                        }
                                        ?>
                                        <option value="<?php echo $vk->id_kendaraan.'|'.$vk->nm_kendaraan?>" <?php echo $selected?>><?php echo $vk->nm_kendaraan?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                <?php }else{ ?>
                <div class="form-group">
                    <label for="supir_do_edit" class="col-sm-4 control-label">Nama Supir </label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                    	<div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        	<input type="text" name="supir_do_edit" id="supir_do_edit" class="form-control input-sm" value="<?php echo $do_data->nm_supir?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php $tglso=date('Y-m-d')?>
                    <label for="tgldo" class="col-sm-4 control-label">Kendaraan </label>
                    <div class="col-sm-8" style="padding-top: 8px;">
                    	<div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                        	<input type="text" name="kendaraan_do_edit" id="kendaraan_do_edit" class="form-control input-sm" value="<?php echo $do_data->ket_kendaraan?>">
                        </div>
                    </div>
                </div>
                <?php } ?>

                <div class="form-group">
                    <label for="helper_do_edit" class="col-sm-4 control-label">Nama Helper</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" name="helper_do_edit" id="helper_do_edit" class="form-control input-sm" value="<?php echo $do_data->nm_helper?>">
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
