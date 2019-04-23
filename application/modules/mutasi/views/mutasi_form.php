<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css')?>">
<div class="nav-tabs-salesorder">
    <div class="tab-content">
        <div class="tab-pane active" id="salesorder">
            <div class="box box-primary">
                <form id="form-header-mutasi" method="post">
                <div class="form-horizontal">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="cabang_asal" class="col-sm-4 control-label">Cabang Asal </font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
								<?php
									if($cabs_user=='100'){
										echo"<select name='cabang_asal' id='cabang_asal' class='form-control input-sm'>";
											echo"<option value=''>PILIH</option>";
											if($Arr_Cabang){
												foreach($Arr_Cabang as $key=>$val){
													$kode_Cabang	= $key.'|'.$val;
													echo"<option value='$kode_Cabang'>$val</option>";
												}
											}
										echo"</select>";
									}else{
										$nm_cabang		= $Arr_Cabang[$cabs_user];
										$kode_Cabang	= $cabs_user.'|'.$nm_cabang;
										echo"<input type='hidden' name='cabang_asal' id='cabang_asal' value='$kode_Cabang'>";
										echo"<span class='badge bg-green'>$nm_cabang</span>";
									}
								?>
                                
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="cabang_tujuan" class="col-sm-4 control-label">Cabang Tujuan<font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8" style="padding-top: 8px;">
								<?php
									echo"<select name='cabang_tujuan' id='cabang_tujuan' class='form-control input-sm'>";
										echo"<option value=''>PILIH</option>";
										if($Arr_Cabang){
											foreach($Arr_Cabang as $key=>$val){												
												if($key  != $cabs_user){
													$kode_Cabang	= $key.'|'.$val;
													echo"<option value='$kode_Cabang'>$val</option>";
												}
											}
										}
									echo"</select>";
								
								?>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                         <div class="form-group ">
                            <label for="tipekirim" class="col-sm-4 control-label">Tipe Pengiriman <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <select name="tipekirim" id="tipekirim" class="form-control input-sm">
                                        <option value="">Pilih</option>
                                       
                                        <?php
                                        foreach(tipe_pengiriman() as $k=>$v){
                                        ?>
                                        <option value="<?php echo $k?>"><?php echo $v?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="supir_do" class="col-sm-4 control-label">Nama Supir <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">
                                <div class="input-group" id="list_supir">

                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="kendaraan_do" class="col-sm-4 control-label">Kendaraan <font size="4" color="red"><B>*</B></font></label>
                            <div class="col-sm-8">                                
                                <div class="input-group" id="list_kendaraan">

                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="supir_do" class="col-sm-4 control-label">Nama Helper</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="helper_do" id="helper_do" class="form-control input-sm">
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
<div class="box box-default ">
	<div class="box-header">
		<h4 class="box-title">Detail Item Mutasi</h4>
		<div class="box-tools pull-right">
			<button class="btn btn-sm btn-success" id="tambah" type="button" style="width: 100%;">
				<i class="fa fa-plus"></i> Add Item
			</button>
		</div>
	</div>
    <div class="box-body">
        <form id="form-detail-mutasi" method="post">
            <table class="table table-bordered" width="100%" id="tabel-detail-mutasi">
				<thead>
					<tr class="bg-blue">
						<th class="text-center">Kode Produk</th>
						<th class="text-center">Nama Produk</th>
						<th class="text-center">Stok Avl</th>
						<th class="text-center">Stok Real</th>
						<th class="text-center">Qty Mutasi</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody id="list_item_mutasi">
				
				</tbody>
            </table>
        </form>
    </div>
</div>
<div class="text-right">
  <div class="box active">
    <div class="box-body">
        <button class="btn btn-danger" onclick="kembali_mutasi()">
           <i class="fa fa-refresh"></i><b> Kembali</b>
        </button>
        <button class="btn btn-primary" type="button" onclick="savemutasi()">
            <i class="fa fa-save"></i><b> Simpan Data Mutasi</b>
        </button>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal modal-primary" id="dialog-item-do" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data SO untuk Delivery Order (DO)</h4>
      </div>
      <div class="modal-body" id="MyModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>

<div class="modal modal-primary" id="dialog-data-stok" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="fa fa-file-pdf-o"></span>&nbsp;Data Stok</h4>
      </div>
      <div class="modal-body" id="MyModalBodyStok" style="background: #FFF !important;color:#000 !important;">
		<?php
		if($cabs_user !='100'){
		?>
          <table class="table table-bordered" width="100%" id="list_item_stok">
              <thead>
                  <tr>
                      <th width="75%">Produk Set</th>
                      <th width="10%">Stok Avl</th>
                      <th width="10%">Stok Real</th>
                      <th width="2%" class="text-center">Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  <?php				  
				  if($stok_cabang){
					foreach($stok_cabang as $ks=>$vs){
                  ?>
						  <tr>
							  <td><?php echo $vs->id_barang.', '.$vs->nm_barang?></td>
							  <td><center><?php echo $vs->qty_avl?></center></td>
							  <td><center><?php echo $vs->qty_stock?></center></td>
							  <td>
								<center>
									<button id="btn-<?php echo $vs->id_barang?>" class="btn btn-warning btn-sm" type="button" onclick="startmutasi('<?php echo $vs->id_barang?>','<?php echo $vs->nm_barang?>','<?php echo $vs->qty_avl?>','<?php echo $vs->qty_stock?>')">
										Pilih
									</button>
								</center>
							  </td>
						  </tr>
                  <?php 
						}
					  }
				  
				  ?>
              </tbody>
          </table>
		  <?php
			}
		  ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
        <span class="glyphicon glyphicon-remove"></span>  Tutup</button>
        </div>
    </div>
  </div>
</div>
<!-- Modal -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js')?>"></script>

<script type="text/javascript">
	//$('#input-kendaraan').hide();
	var base_url			= '<?php echo base_url(); ?>';
	var active_controller	= '<?php echo($this->uri->segment(1)); ?>';
	var cabang_user			= '<?php echo $cabs_user;?>';
	var arr_cabang			= <?php echo json_encode($Arr_Cabang);?>;
				
	$(document).ready(function(){
		$('#tipekirim').change(get_driver_kbm);
		$('#cabang_asal').change(function(){
			var cabang_asal = $('#cabang_asal').val();
			$('#MyModalBodyStok, #list_item_mutasi').empty();
			if(cabang_asal !='' && cabang_asal!=null){
				var cabang_tujuan	= $('#cabang_tujuan').val();
				if(cabang_asal==cabang_tujuan){
					$('#cabang_tujuan').val('');
				}
				
			}
		});
		$('#cabang_tujuan').change(function(){
			var cabang_tujuan = $('#cabang_tujuan').val();
			if(cabang_tujuan !='' && cabang_tujuan !=null){
				var cabang_asal	= $('#cabang_asal').val();
				if(cabang_asal==cabang_tujuan){
					swal({
						title: "Peringatan!",
						text: "Cabang Tujuan tidak boleh sama dengan cabang Asal",
						type: "warning"
					});
					$('#cabang_tujuan').val('');
				}
				
			}
		});
		 $("#tambah").click(function(){
			if(cabang_user !='100'){
				$('#dialog-data-stok').modal('show');
			}else{
				var cabang_asal	= $('#cabang_asal').val();
				if(cabang_asal=='' || cabang_asal==null){
					swal({
						title: "Peringatan!",
						text: "Cabang Asal belum dipilih. Mohon pilih cabang asal terlebih dahulu...",
						type: "warning"
					});
				}else{
					var kode_pecah	= cabang_asal.split('|');
					var baseurl=base_url + active_controller +'/get_stock_item';				
					$.ajax({
						'url'		: baseurl,
						'type'		: 'post', 
						'data'		: {'cabang':kode_pecah[0]},
						'success'	: function(data){
							$('#MyModalBodyStok').html(data);
							$('#dialog-data-stok').modal('show');
							$("#list_item_stok").DataTable({lengthMenu:[5,10,15,20]}).draw();
						}, 
						'error'		: function(data){
							alert('An error occured, please try again.');						
						}
					});
				}
			}
			
		});
		$("#list_item_stok").DataTable({lengthMenu:[5,10,15,20]}).draw();
	});
    
    function startmutasi(id,nm,avl,real){
       //  Cek Ada Data Gagal
	   var Cek_OK		= 1;
	   var Urut			= 1;
	   var total_row	= $('#list_item_mutasi').find('tr').length;
	   if(total_row > 0){
		  var kode_tr_akhir= $('#list_item_mutasi tr:last').attr('id');
		  var row_akhir		= kode_tr_akhir.split('_');
		  var Urut			= parseInt(row_akhir[1]) + 1;
		  $('#list_item_mutasi').find('tr').each(function(){
			  var kode_row	= $(this).attr('id');
			  var id_row	= kode_row.split('_');
			  var kode_produknya	= $('#kode_produk_'+id_row[1]).val();
			  if(id==kode_produknya){
				  Cek_OK	= 0;
			  }
		  });
	   }
	   if(Cek_OK==1){	   
			var idnya = "'"+id+"'";
			html='<tr id="tr_'+Urut+'">'
				+ '<td style="padding:3px;">'
				+ '<input type="text" class="form-control input-sm kode-produk" name="kode_produk[]" id="kode_produk_'+Urut+'" readonly value="'+id+'">'
				+ '</td>'
				+ '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="nama_produk[]" id="nama_produk_'+Urut+'" readonly value="'+nm+'"></td>'
				+ '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="stok_avl[]" id="stok_avl_'+Urut+'" style="text-align:center;" readonly value="'+avl+'"></td>'
				+ '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="stok_real[]" id="stok_real'+Urut+'" style="text-align:center;" value="'+real+'" readonly></td>'
				+ '<td style="padding:3px;"><input type="text" class="form-control input-sm" name="qty_mutasi[]" id="qty_mutasi_'+Urut+'" style="text-align:center;" onkeyup="cekqtymutasi('+Urut+')"></td>'
				+ '<td style="padding:3px;"><center><div class="btn-group" style="margin:0px;">'
				+ '<button type="button" onclick="deleterow('+Urut+','+idnya+')" id="delete-row" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>'
				+ '</div></center></td>'
				+ '</tr>';
			$("#tabel-detail-mutasi").append(html);
			$("#btn-"+id).removeClass('btn-warning');
			$("#btn-"+id).addClass('btn-danger');
			$("#btn-"+id).attr('disabled',true);
			$("#btn-"+id).text('Sudah');
	   }
        
    }
   
    function deleterow(tr,id){
        $('#tr_'+tr).remove();
        $("#btn-"+id).removeClass('btn-danger');
        $("#btn-"+id).addClass('btn-warning');
        $("#btn-"+id).attr('disabled',false);
        $("#btn-"+id).text('Pilih');
    }
    function savemutasi(){
		var asal = $('#cabang_asal').val();
        var tujuan = $('#cabang_tujuan').val();
        var supir = $('#supir_mutasi').val();
        var mobil = $('#kendaraan_mutasi').val();
        if(tujuan == "" || supir == "" || mobil == "" || asal==''){
            swal({
                title: "Peringatan!",
                text: "Data harus lengkap",
                type: "warning",
                timer: 1500,
                showConfirmButton: false
            });
			return false
        }
		var total_row	= $('#list_item_mutasi').find('tr').length;
		if(total_row < 1){
			swal({
                title: "Peringatan!",
                text: "Daata Item Mutasi belum dipilih. Mohon pilih data item terlebih dahulu..",
                type: "warning",
                timer: 1500,
                showConfirmButton: false
            });
			return false
		}
		
        swal({
          title: "Peringatan !",
          text: "Pastikan data sudah lengkap dan benar",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Ya, simpan!",
          cancelButtonText: "Batal!",
          closeOnConfirm: false,
          closeOnCancel: true
        },
        function(isConfirm){
			if(isConfirm) {
				var formdata = $("#form-header-mutasi,#form-detail-mutasi").serialize();
				$.ajax({
					url: siteurl+"mutasi/savemutasi",
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
								window.location.href=siteurl+'mutasi';
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
			}
        });
    
    }
    function kembali_mutasi(){
        window.location.href = siteurl+"mutasi";
    }
    function filterAngka(a){
        if(!a.match(/^[0-9]+$/)){
            return 0;
        }else{
            return 1;
        }
    }
    function cekqtymutasi(no){
        var mutasi = parseInt($('#qty_mutasi_'+no).val());
        var avl = parseInt($('#stok_avl_'+no).val());
        if(filterAngka($('#qty_mutasi_'+no).val()) == 1){
            if(mutasi > avl){
                swal({
                    title: "Peringatan!",
                    text: "Qty Mutasi tidak boleh melebihi Stok Avl",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                $('#qty_mutasi_'+no).val(0);
            }
        }else{
            var ang = $('#qty_mutasi_'+no).val();
            $('#qty_mutasi_'+no).val(ang.replace(/[^0-9]/g,''));
        }
    }
	
	function get_driver_kbm(){
		var cabang_asal		= $('#cabang_asal').val();
		var kirim			= $('#tipekirim').val();
		if(kirim=='' || kirim==null){
			$('#list_kendaraan, #list_supir').empty();
		}else{
			 var Template   ='<span class="input-group-addon"><i class="fa fa-user"></i></span>';
			 var Kendaraan	='<span class="input-group-addon"><i class="fa fa-car"></i></span>';
			if(kirim=='SENDIRI' && cabang_asal !='' && cabang_asal !=null){
				var pecah_cabang	= cabang_asal.split('|');
				// AMBIL DATA DRIVER 
				var baseurl=base_url + active_controller +'/get_Driver/'+pecah_cabang[0];				
				$.ajax({
					'url'		: baseurl,
					'type'		: 'get', 
					'success'	: function(data){
						var datas	= $.parseJSON(data);
						Template	+='<select name="supir_do" id="supir_do" class="form-control input sm">';
						if(!$.isEmptyObject(datas)){
							  $.each(datas,function(key,value){
								  Template    +='<option value="'+key+'^_^'+value+'">'+value+'</option>';
							  });
						  }
					   Template   +='</select>';
					   $('#list_supir').html(Template);
					   $("#supir_do").select2({
						  placeholder: "Pilih",
						  allowClear: true
					   });
					}, 
					'error'		: function(data){
						alert('An error occured, please try again.');						
					}
				});
				
				// AMBIL DATA KBM				
				var baseurl=base_url + active_controller +'/get_Kendaraan/'+pecah_cabang[0];				
				$.ajax({
					'url'		: baseurl,
					'type'		: 'get', 
					'success'	: function(data){
						var datas	= $.parseJSON(data);
						Kendaraan	+='<select name="kendaraan_do" id="kendaraan_do" class="form-control input sm">';
						if(!$.isEmptyObject(datas)){
							  $.each(datas,function(key,value){
								  Kendaraan    +='<option value="'+key+'^_^'+value+'">'+value+'</option>';
							  });
						  }
					   Kendaraan   +='</select>';
					   $('#list_kendaraan').html(Kendaraan);
					   $("#kendaraan_do").select2({
						  placeholder: "Pilih",
						  allowClear: true
					   });
					}, 
					'error'		: function(data){
						alert('An error occured, please try again.');						
					}
				});
			}else{
				Template	+='<input type="text" name="supir_do" id="supir_do" class="form-control input-sm">';
				$('#list_supir').html(Template);

			   Kendaraan   +='<input type="text" name="kendaraan_do" id="kendaraan_do" class="form-control input-sm">';
			   $('#list_kendaraan').html(Kendaraan);
			}
		}
	}
</script>
