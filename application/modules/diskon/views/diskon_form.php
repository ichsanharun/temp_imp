<div class="box box-primary">
	<div class="box-body">
		<form id="form-diskon" method="post" class="form-horizontal" role="form">
		<div class="form-group ">
			<label for="nm_customer" class="col-sm-2 control-label">Nama Diskon <font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="hidden" class="form-control" id="id_diskon" name="id_diskon" value="<?php echo @$detail->id_diskon?>">
                    <input type="text" class="form-control" id="diskon" name="diskon" value="<?php echo @$detail->diskon?>" required>
                </div>
            </div>
            <label for="nm_customer" class="col-sm-2 control-label">Persen Diskon <font size="4" color="red"><B>*</B></font></label>
            <div class="col-sm-3">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                     <input value="<?php echo @$detail->persen?>" type="text" class="form-control" id="persen_diskon" name="persen_diskon" placeholder="Persen Diskon" required="" onkeyup="this.value=this.value.replace(/[^0-9\.]/g,'');">
                </div>
            </div>
		</div>
		<div class="form-group ">
			<label for="sts_aktif" class="col-sm-2 control-label">Status</label>
            <div class="col-sm-2">
                <select id="sts_aktif" name="sts_aktif" class="form-control">
                <option value="aktif" <?php echo set_select('sts_aktif', 'aktif', isset($detail->sts_aktif) && $detail->sts_aktif == 'aktif'); ?>>Active
                    </option>
                    <option value="nonaktif" <?php echo set_select('sts_aktif', 'nonaktif', isset($detail->sts_aktif) && $detail->sts_aktif == 'nonaktif'); ?>>Inactive
                    </option>
                </select>
            </div>
		</div>
		<div class="box-footer">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">

                    <button type="submit" name="save" class="btn btn-success" id="submit"><i class="fa fa-save">&nbsp;</i>Save</button>
                    <a class="btn btn-danger" data-toggle="modal" onclick="cancel()"><i class="fa fa-minus-circle">&nbsp;</i>Cancel</a>
                    </div>
                </div>
                </div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker({
            format : "yyyy-mm-dd",
            showInputs: true,
            autoclose:true
        });
	});
	$('#form-diskon').on('submit', function(e){
        e.preventDefault();
        var formdata = $("#form-diskon").serialize();
        $.ajax({
            url: siteurl+"diskon/savediskon",
            dataType : "json",
            type: 'POST',
            data: formdata,
            success: function(result){
                if(result['save']=='1'){
                   swal({
                        title: "Sukses!",
                        text: result.msg,
                        type: "success",
                        timer: 1500,
                        showConfirmButton: false
                    });
                   cancel();
                } else {
                    swal({
                        title: "Gagal!",
                        text: result.msg,
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
    function cancel(){
        window.location.href = siteurl+"diskon";
    }
</script>