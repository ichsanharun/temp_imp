<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="box box-primary">
    <!-- form start -->
    <?= form_open($this->uri->uri_string(),array('id'=>'frm_users','name'=>'frm_users','role'=>'form','class'=>'form-horizontal')) ?>
    	<div class="box-header">
			<div class="form-group <?= form_error('username') ? ' has-error' : ''; ?>">
			    <label for="username" class="col-md-2 control-label"><?= lang('users_username') ?></label>
			    <div class="col-sm-3">
			    	<input type="text" class="form-control" id="username" name="username" maxlength="45" value="<?= set_value('username', isset($data->username) ? $data->username : ''); ?>" readonly />
			    </div>
		  	</div>
		  	<div class="form-group <?= form_error('nm_lengkap') ? ' has-error' : ''; ?>">
			    <label for="nm_lengkap" class="col-md-2 control-label"><?= lang('users_nm_lengkap') ?></label>
			    <div class="col-md-3">
			    	<input type="text" class="form-control" id="nm_lengkap" name="nm_lengkap" maxlength="100" value="<?= set_value('nm_lengkap', isset($data->nm_lengkap) ? $data->nm_lengkap : ''); ?>" readonly />
			    </div>
		  	</div>
	  	</div>
	  	<div class="box-body">
		<table id="example1" class="table table-bordered table-striped">
		<thead>
		<tr>
			<th>#</th>
			<th><?= lang('users_facility_name') ?></th>
			<?php foreach ($header as $key => $hd) : ?>
			<th class="text-center"><?= $hd ?></th>
			<?php endforeach ?>		
		</tr>
		</thead>
        
		<tbody>
		<?php 
			$no = 1;
			foreach ($permissions as $key => $pr) : 
		?>
		<tr>
			<td><?= $no ?></td>
			<td><?= $key ?></td>
			<?php foreach ($pr as $key2 => $action) : ?>
			<td class="text-center">
			<?php if($action == 0) : ?>
			-
			<?php else : ?>
			<input type="checkbox" name="id_permissions[]" value="<?= $action['perm_id'] ?>" title="<?= $action['action_name'] ?>" <?= ($action['value'] == 1) ? "checked='checked'" : '' ?> <?= ($action['is_role_permission'] == 1) ? "disabled='disabled'" : '' ?> />
			<?php endif ?>
			</td>
			<?php endforeach ?>	
		</tr>
		<?php $no++;endforeach ?>    
		</tbody>
		
		<tfoot>
		<tr>
			<th>#</th>
			<th><?= lang('users_facility_name') ?></th>
			<?php foreach ($header as $key => $hd) : ?>
			<th class="text-center"><?= $hd ?></th>
			<?php endforeach ?>		
		</tr>
		</tfoot>
		</table>
	  		</div>
			<div class="box-footer">
	  		<input type="submit" name="save" value="<?= lang('users_btn_save') ?>" class="btn btn-primary" />
	  		<?php
            	echo anchor('users/setting', lang('users_btn_cancel'),array('class' => 'btn btn-danger'));
            ?>
	  	</div>
	  	</div>
	  	
	<?= form_close() ?>
</div><!-- /.box -->

<!-- DataTables -->

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<!-- page script -->
<script>
	$(document).ready(function() {
	    $('#example1').DataTable( {
	        "lengthMenu": [[25, 50, -1], [25, 50, "All"]]
	    } );
	} );
</script>