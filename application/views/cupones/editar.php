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
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span>, el cupón tiene que asignarse al menos a una categor&iacute;a, producto, accesorio o consumible.</p>
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
									<label><span class="req">*</span>Alianzas:</label>
									<select class="form-control" name="alianza_id" id="alianza_id">
										<option value=""><?php echo $this->config->item('empty_select'); ?></option>
										<?php foreach($alianzas as $k=>$v):?>
										<option value="<?php echo $k; ?>" <?php echo ($k == @$cupon->alianza_id)?'selected="selected"':''; ?>> <?php echo $v; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo  form_error('alianza_id');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>V&aacute;lido desde:</label>
					        		<input  id="vigencia_desde_alt" name="vigencia_desde" value="<?php echo set_value('vigencia_desde',@$cupon->vigencia_desde); ?>" type="hidden" />
									<input id="vigencia_desde" class="form-control fecha" type="text" value="<?php echo get_fecha(set_value('vigencia_desde',@$cupon->vigencia_desde));?>" />
									<?php echo  form_error('vigencia_desde');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>V&aacute;lido hasta:</label>
					        		<input  id="vigencia_hasta_alt" name="vigencia_hasta" value="<?php echo set_value('vigencia_hasta',@$cupon->vigencia_hasta); ?>" type="hidden" />
									<input id="vigencia_hasta" class="form-control fecha" type="text" value="<?php echo get_fecha(set_value('vigencia_hasta',@$cupon->vigencia_hasta));?>" />
									<?php echo  form_error('vigencia_hasta');?>
					        	</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label>Meses sin intereses:</label>
									<input type="text" class="form-control" id="meses_sin_intereses" name="meses_sin_intereses" value="<?php echo@$cupon->meses_sin_intereses; ?>" />
									<?php echo  form_error('meses_sin_intereses');?>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>N&uacute;mero de folios:</label>
									<input type="text" class="form-control" id="numero_folios" name="numero_folios" value="<?php echo @$cupon->numero_folios; ?>" readonly />
									<?php echo  form_error('numero_folios');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Porcentaje de descuento:</label>
									<input type="text" class="form-control" id="porcentaje_descuento" name="porcentaje_descuento" value="<?php echo @$cupon->porcentaje_descuento; ?>" />
									<?php echo  form_error('porcentaje_descuento');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									<label><span class="req">*</span>Porcentaje de descuento a distribuidores:</label>
									<input type="text" class="form-control" id="descuento_distribuidor" name="descuento_distribuidor" value="<?php echo @$cupon->descuento_distribuidor; ?>" />
									<?php echo  form_error('descuento_distribuidor');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
									&nbsp;
								</div>
							</div>

							<div id="entrega_email">
								<fieldset class="scheduler-border">
									<legend class="scheduler-border">Im&aacute;genes para cupones:</legend>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">#</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Monto inicial</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Monto final</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">Regalo</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Imagen</div>
											<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
												<input type="button" value="+" class="agregarCupon" style="width: 25px; height: 25px; float: right;">
												<span id="cupones_imagenes" name="cupones_imagenes"></span><?php echo form_error('cupones_imagenes');?>
											</div>
										</div>
									</div>

									<div id="elementos_agregar">
										<div class="row" style="display:none;">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><label class="numeracion">0</label></div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
													<input type="text" class="form-control" id="montosIniciales[]" name="montosIniciales[]" value="" />
													<?php echo form_error('montosIniciales[]'); ?>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
													<input type="text" class="form-control" id="montosFinales[]" name="montosFinales[]" value="" />
													<?php echo form_error('montosFinales[]'); ?>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
													<select id="idsProductos[]" name="idsProductos[]" class="form-control">
														<option value="0"><?php echo $this->config->item('empty_select'); ?></option>
														<?php foreach($productosRegalo as $k=>$v):?>
															<option value="<?php echo $k;?>"><?php echo $v;?></option>
														<?php endforeach;?>
													</select>
													<?php echo form_error('idsProductos[]'); ?>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
													<div class="fileinputs">
														<input type="file" id="imagenes[]" name="imagenes[]" value="<?php echo set_value('imagenes[]'); ?>" accept=".jpg, .jpeg" />
														<div class="fakefile">
															<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
														</div>
													</div>
													<?php echo form_error('imagenes[]'); ?>
												</div>
												<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input type="button" value="-" class="eliminarCupon" style="width: 25px; height: 25px; float: right;"></div>
											</div>
										</div>

										<?php if(@$cupones_imagenes):
											$i = 1;
											?>
											<?php foreach($cupones_imagenes as $clave=>$valor): ?>
												<?php if(!$valor->eliminado): ?>
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><label class="numeracion">0</label></div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																<input type="text" class="form-control" id="montosIniciales[<?php echo $i;?>]" name="montosIniciales[<?php echo $i;?>]" value="<?php echo $valor->monto_inicial;?>" />
																<?php echo form_error('montosIniciales['.$i.']'); ?>
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																<input type="text" class="form-control" id="montosFinales[<?php echo $i;?>]" name="montosFinales[<?php echo $i;?>]" value="<?php echo $valor->monto_final;?>" />
																<?php echo form_error('montosFinales['.$i.']'); ?>
															</div>
															<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3">
																<select id="idsProductos[]" name="idsProductos[]" class="form-control">
																	<option value="0"><?php echo $this->config->item('empty_select'); ?></option>
																	<?php foreach($productosRegalo as $k=>$v):?>
																		<option value="<?php echo $k;?>" <?php echo ($k == @$valor->productos_id)?'selected="selected"':''; ?>><?php echo $v;?></option>
																	<?php endforeach;?>
																</select>
																<?php echo form_error('idsProductos['.$i.']'); ?>
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
																<?php if($this->config->item('cloudfiles')):
																	if($valor->extension=='jpg' && !empty($valor->id)):
																		$path=$this->cloud_files->url_publica("files/cupones/{$valor->cupones_id}/{$valor->id}.jpg"); ?>
																		<input type="hidden" id="idsImagenes[<?php echo $i;?>]" name="idsImagenes[<?php echo $i;?>]" value="<?php echo $valor->id;?>"/>
																		<input type="hidden" id="extencionesImagenes[<?php echo $i;?>]" name="extensionesImagenes[<?php echo $i;?>]" value="<?php echo $valor->extension;?>"/>
																		<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
																			<img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla"/>
																		</a>
																	<?php elseif($valor->extension=='jpeg' && !empty($valor->id)):
																		$path=$this->cloud_files->url_publica("files/cupones/{$valor->cupones_id}/{$valor->id}.jpeg"); ?>
																		<input type="hidden" id="idsImagenes[<?php echo $i;?>]" name="idsImagenes[<?php echo $i;?>]" value="<?php echo $valor->id;?>"/>
																		<input type="hidden" id="extencionesImagenes[<?php echo $i;?>]" name="extensionesImagenes[<?php echo $i;?>]" value="<?php echo $valor->extension;?>"/>
																		<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
																			<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla"/>
																		</a>
																	<?php endif;?>
																<?php endif;?>
																<div class="fileinputs">
																	<input type="file" id="imagenes[<?php echo $i;?>]" name="imagenes[<?php echo $i;?>]" value="<?php echo $valor->extension; ?>" accept=".jpg, .jpeg" />
																	<div class="fakefile">
																		<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
																	</div>
																</div>
																<?php echo form_error('imagenes['.$i.']'); ?>
															</div>
															<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input type="button" value="-" class="eliminarCupon" style="width: 25px; height: 25px; float: right;"></div>
														</div>
													</div>
												<?php endif;?>
											<?php $i++; endforeach; ?>
										<?php endif; ?>
									</div>

								</fieldset>
							</div>
							
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<fieldset class="scheduler-border">
	    								<legend class="scheduler-border">Categor&iacute;as: </legend>
										<span id="cupones_descuentos" name="cupones_descuentos" style="position: relative; float: right;"><?php echo form_error('cupones_descuentos');?></span>
										<select id="categorias_ids" name="categorias_ids[]" class="bc_multiselect" multiple>
											<?php foreach($categorias as $k=>$v):?>
												<option value="<?php echo $k;?>" <?php echo (@$categorias_cupon)?in_array($k,$categorias_cupon)?'selected="selected"':'':''; ?>><?php echo $v;?></option>
											<?php endforeach;?>
										</select>
									</fieldset>
									<?php echo  form_error('categorias_ids[]');?> 
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<fieldset class="scheduler-border">
	    								<legend class="scheduler-border">Productos:</legend>
										<span id="cupones_descuentos" name="cupones_descuentos" style="position: relative; float: right;"><?php echo form_error('cupones_descuentos');?></span>
										<select id="productos_ids" name="productos_ids[]" class="bc_multiselect" multiple>
											<?php foreach($productos as $k=>$v):?>
												<option value="<?php echo $k;?>" <?php echo (@$productos_cupon)?in_array($k,$productos_cupon)?'selected="selected"':'':''; ?>><?php echo $v;?></option>
											<?php endforeach;?>
										</select>
									</fieldset>
									<?php echo form_error('productos_ids[]');?>
								</div>
							</div>
							</br>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<fieldset class="scheduler-border">
    								<legend class="scheduler-border">Accesorios:</legend>
									<span id="cupones_descuentos" name="cupones_descuentos" style="position: relative; float: right;"><?php echo form_error('cupones_descuentos');?></span>
									<select id="accesorios_ids" name="accesorios_ids[]" class="bc_multiselect" multiple>
										<?php foreach($accesorios as $k=>$v):?>
											<option value="<?php echo $k;?>" <?php echo (@$accesorios_cupon)?in_array($k,$accesorios_cupon)?'selected="selected"':'':''; ?>><?php echo $v;?></option>
										<?php endforeach;?>
									</select>
									</fieldset>
									<?php echo form_error('accesorios_ids[]');?>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<fieldset class="scheduler-border">
    								<legend class="scheduler-border">Consumibles:</legend>
									<span id="cupones_descuentos" name="cupones_descuentos" style="position: relative; float: right;"><?php echo form_error('cupones_descuentos');?></span>
									<select id="consumibles_ids" name="consumibles_ids[]" class="bc_multiselect" multiple>
										<?php foreach($consumibles as $k=>$v):?>
											<option value="<?php echo $k;?>" <?php echo (@$consumibles_cupon)?in_array($k,@$consumibles_cupon)?'selected="selected"':'':''; ?>><?php echo $v;?></option>
										<?php endforeach;?>
									</select>
									</fieldset>
									<?php echo form_error('consumibles_ids[]');?>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<fieldset class="scheduler-border">
										<legend class="scheduler-border">Cuentas participantes: </legend>
										<select id="cuentas_ids" name="cuentas_ids[]" class="bc_multiselect" multiple>
											<?php foreach($cuentas as $k=>$v):?>
												<option value="<?php echo $k;?>" <?php echo (@$cuentas_cupon)?in_array($k,$cuentas_cupon)?'selected="selected"':'':''; ?>><?php echo $v;?></option>
											<?php endforeach;?>
										</select>
									</fieldset>
									<?php echo  form_error('cuentas_ids[]');?>
								</div>
								<div class="col-lg-6"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-12 barra-btn">
				<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
				<a href="<?php echo site_url('cupones/index');?>"  class="btn btn-default pull-right">Cancelar</a>
			</div>
				
		</form>
	</div>

<script type="text/javascript">
<!--
$(function(){

	/* AGREGAR ACCESORIO */

	$('#accesorio_add').click(function(e){
		var row = $('#accesorios tbody>tr:first').clone(true);
		var i = $('#accesorios tbody>tr').length;
		row.find(".acc_select").attr('name', 'accesorios[tipo_accesorio_id]['+i+']');
		row.find(".acc_check").attr('name', 'accesorios[obligatorio_id]['+i+']');
		row.find('input:text').val("");
		row.find('td.c_id').html('&nbsp;');
		row.insertAfter('#accesorios tbody>tr:last');
		row.show();
		$('#cambio').val('1');
		return false;
	});
	/* FIN AGREGAR ACCESORIO */

	/* ELIMINAR ACCESORIO */
	$('.col_d').click(function(){
		if(confirm('¿Seguro que desea eliminar este tipo de accesorio?'))
		{
			$('#cambio').val('1');
			$(this).closest('tr').remove();
		}	
		return false;
	});
	/* FIN ELIMINAR ACCESORIO */
});
//-->
</script>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>/js/tiny_mce/basic_config.js"></script>
<script type="text/javascript">
	<!--
	$(function(){
		convertir_campos();
	});
	//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>