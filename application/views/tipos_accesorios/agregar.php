<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<p class="msg info">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		 	<i class="fa fa-info fa-stack-1x text-color-info"></i>	
		</span>
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
	</div>
	<div class="col-lg-12">
		<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
		<div class="panel-group">
			<div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
			          Informaci&oacute;n general
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Nombre:</label>
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre'); ?>" />
								<?php echo form_error('nombre');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Descripci&oacute;n:</label>
								<input class="form-control" id="descripcion" name="descripcion" value="<?php echo set_value('descripcion'); ?>" />
								<?php echo form_error('descripcion');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 checkbox">
								<input name="activo" value="0" type="hidden" />
								<div class="checkbox">
									<label>
										<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',TRUE); ?> />Activo
									</label>
								</div>
								<?php echo form_error('activo');?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Descuento Base ( venta individual ):</label>
								<div class="input-group">
									<input class="form-control text-right" id="descuento_base" name="descuento_base" value="<?php echo set_value('descuento_base'); ?>" />
									<span class="input-group-addon">&#37;</span>
								</div>
								<?php echo form_error('descuento_base');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Descuento Opcional ( venta individual ):</label>
								<div class="input-group">
									<input class="form-control text-right" id="descuento_opcional" name="descuento_opcional" value="<?php echo set_value('descuento_opcional'); ?>" />
									<span class="input-group-addon">&#37;</span>
								</div>
								<?php echo form_error('descuento_opcional');?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
					 <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
		          	Imagen 
			        </a>
			      </h4>
				</div>
				
				<div id="collapseThree" class="panel-collapse collapse in">
				    <div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Imagen:</label>
								<div class="fileinputs">
									<input id="imagen_rubrica" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
									</div>
								</div>
							</div>
							<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
								<?php if($imagen=TRUE):?>
								<a href="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("files/tipos_accesorios/".@$r->id.".jpg").'&s='.$this->config->item('avaluo_image_size'));?>" class="imagen_fancybox">
									<img class="img-thumbnail" src="<?php echo site_url('/thumbs/timthumb.php?src=files/tipos_accesorios/'.@$r->id.'.jpg&zc=0&q=85&w=126&h=86'.'&t='.time());?>" class="img_thumb" />
								</a>	
							<?php else:?>
								<img class="img-thumbnail" src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/layout/imagen_no_disponible.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" alt="SIN IMAGEN">
							<?php endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			<div class="col-lg-12 barra-btn">
				<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('accesorios/tipos_accesorios');?>"  class="btn btn-default pull-right">Cancelar</a>
			</div>
		</form>
	</div>

<script type="text/javascript">
<!--
$(function(){

		var swfu;
		function set_swfupload() 
		{
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "<?php echo site_url('/productos/fotos_agregar/'.$r->id); ?>",
				post_params: {"cookie_session": '<?php echo $sesion; ?>'},

				// File Upload Settings
				file_size_limit : "8 MB",
				file_types : "*.jpg;*.pdf",
				file_types_description : "JPG Images",
				file_upload_limit : 0,

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				
				button_placeholder_id : "agregar_btn",
				button_width: 100,
				button_height: 22,
				button_text_style : '.button {color:#FFFFFF;  font-family: sans-serif; font-size: 11px;}',
				button_text : '<span class="button"><b>Agregar fotos</b></span>',
				button_text_top_padding: 2,
				button_text_left_padding: 9,
				
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "<?php echo base_url(); ?>js/swfUpload/swfupload.swf",
				flash9_url : "<?php echo base_url(); ?>js/swfUpload/swfupload_FP9.swf",

				custom_settings : {
					upload_target : "div_progreso"
				},
				
				// Debug Settings
				<?php if($this->config->item('debug') && $this->session->userdata('grupos_id')==1): ?>
				debug: true
				<?php else:?>
				debug: false
				<?php endif; ?>
			});
		};
		set_swfupload();
		//ORDENAR FOTOS
		$("#fotos tbody").sortable({
			change:function(){
				$('#cambio').val('1');
			}
		});

		$('#todos').change(function(){
			$('input:checkbox.fotos_cb').attr('checked',$('#todos').attr('checked'));
		});

		function get_selected()
		{
			var allVals = [];
			$('input:checkbox.fotos_cb:checked').each(function(){
				if($(this).val()!='on')
					allVals.push($(this).val());
			});
			var ids=allVals.join(',');
			return ids;
		} 
		
		$('#eliminar').click(function(e){
			e.preventDefault();
			var ids=get_selected();
			if(!ids)
				alert('Debe seleccionar al menos un registro para procesar.');
			else
			{
			var action=$('#form').attr('action');
			<?php if(isset($acciones_fotos[1])): ?>
			if(confirm('¿Seguro que desea eliminar las imagenes seleccionados? ESTA ACCIÓN NO PUEDE DESHACERSE.'))
			{		
				$('#form').attr('action',BASE_URL+'productos/fotos_eliminar_varios/'+ids);
			}
			<?php endif; ?>				
			$('#form').submit();
			$('#form').attr('action',action);		
			}
		});		
});
//-->		
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>