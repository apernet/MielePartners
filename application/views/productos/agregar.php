<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<p class="msg info">
		<span class="fa-stack fa-2x">
			<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		 	<i class="fa fa-info fa-stack-1x text-color-info"></i>	
		</span>
		Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
	</div>

	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
								<?php echo form_error('nombre');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Modelo:</label>
								<input class="form-control" id="modelo" name="modelo" value="<?php echo set_value('modelo',@$r->modelo); ?>" />
								<?php echo form_error('modelo');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Categor&iacute;a:</label>
								<select class="form-control" name="categorias_id" id="categorias_id">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<?php foreach($categorias as $k=>$v):?>
									<option value="<?php echo $k; ?>" <?php echo set_select('categorias_id', $k, ($k == @$r->categorias_id)); ?>> <?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('categorias_id');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Sku:</label>
								<input class="form-control" id="item" name="item" value="<?php echo set_value('item',@$r->item); ?>" />
								<?php echo form_error('item');?>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Precio:</label>
								<input class="form-control" id="precio" name="precio" value="<?php echo set_value('precio'); ?>" />
								<?php echo form_error('precio');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<label><span class="req">*</span>Unidad:</label>
								<select class="form-control" name="unidad_id" id="unidad_id">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<?php foreach($unidades as $k=>$v):?>
										<option value="<?php echo $k; ?>" <?php echo set_select('unidad_id', $k, ($k == @$r->unidad_id)); ?>> <?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('unidad_id');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="checkbox">
									<label>
										<input name="activo" value="0" type="hidden" />
										<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />Activo
										<?php echo form_error('activo');?>
									</label>
								</div>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="checkbox">
									<label>
										<input name="sin_envio" value="0" type="hidden" />
										<input type="checkbox" name="sin_envio" value="1" <?php echo set_checkbox('sin_envio','1',(@$r->sin_envio)?TRUE:FALSE); ?> />Sin env&iacute;o
										<?php echo form_error('sin_envio');?>
									</label>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
								<div class="checkbox">
									<label>
										<input name="ocultar" value="0" type="hidden" />
										<input type="checkbox" name="ocultar" value="1" <?php echo set_checkbox('ocultar','1',(@$r->ocultar)?TRUE:FALSE); ?> />Ocultar en cat&aacute;logo en l&iacute;nea
										<?php echo form_error('ocultar');?>
									</label>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="panel panel-default estados" style="border:0">
				<div class="panel panel-default">
					<div class="panel-heading" style="position: relative;">
						<span id="filas_estados" name="filas_estados" style="position: relative;"></span><?php echo form_error('filas_estados');?>
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
								Home Care Program
							</a>
						</h4>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="table-responsive front_table mt20" style="overflow-y: auto">
								<table class="table table-hover">
									<thead>
										<tr>
											<td><b>Estado</b></td>
											<td><b>Precio</b></td>
											<td><b>Horas <br> iniciales</b></td>
											<td><b>Horas <br> m&aacute;ximas</b></td>
											<td><b>Precio <br> hora extra</b></td>
										</tr>
									</thead>

									<tbody>
										<?php foreach($estados as $k=>$v): ?>
											<?php $postfijo = strtolower(str_replace(' ','_',$v)); ?>
											<tr>
												<td style="font-size: 12px; font-weight: bold"><?php echo $v; ?></td>
												<td>
                                                    <input class="form-control" id="precio_inicial_<?php echo $postfijo; ?>" name="precio_inicial_<?php echo $postfijo; ?>" value="<?php echo set_value('precio_inicial_'.$postfijo); ?>" />
                                                    <div style="position: relative;">&nbsp;<?php echo form_error('precio_inicial_'.$postfijo);?></div>
												</td>
												<td>
                                                    <input class="form-control" id="horas_iniciales_<?php echo $postfijo; ?>" name="horas_iniciales_<?php echo $postfijo; ?>" value="<?php echo set_value('horas_iniciales_'.$postfijo); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('horas_iniciales_'.$postfijo);?></div>
												</td>
												<td>
                                                    <input class="form-control" id="horas_maximas_<?php echo $postfijo; ?>" name="horas_maximas_<?php echo $postfijo; ?>" value="<?php echo set_value('horas_maximas_'.$postfijo); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('horas_maximas_'.$postfijo);?></div>
												</td>
												<td>
                                                    <input class="form-control" id="precio_horas_extra_<?php echo $postfijo; ?>" name="precio_horas_extra_<?php echo $postfijo; ?>" value="<?php echo set_value('precio_horas_extra_'.$postfijo); ?>" />
													<div style="position: relative;">&nbsp;<?php echo form_error('precio_horas_extra_'.$postfijo);?></div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- DESCRIPCION -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
						Descripci&oacute;n:
						</a>
					</h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
								<label><span class="req">*</span>Contenido</label>
								<textarea id="descripcion" name="descripcion" class="w90p form-control" " rows="6"><?php echo set_value('descripcion',@$r->descripcion); ?></textarea>
								<?php echo form_error('descripcion');?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- ACCESORIOS -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-lg-10 col-md-9 col-sm-9 col-xs-6">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
								Accesorios
								</a>
							</h4>
						</div>
						<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
							<a role="button" class=" btn-default btn btn-xs" href="#" id="accesorio_add">Agregar Tipo de Accesorio</a>
						</div>
					</div>
				</div>
				<div id="collapseFour" class="panel-collapse collapse in">
					<div class="col-lg-12">
						<div class="table-responsive">
							<table class="table table-hover" id="accesorios">
							<thead>
								<tr style="font-size: 12px">
									<td class="col-lg-8">Tipos de Accesorios</td>
									<td>Obligatorio</td>
									<td>Acciones</td>
								</tr>
							</thead>
							<tbody>
								<tr style="display: none;" class="bgcw">
									<td>
										<select class="form-control acc_select" name="accesorios[tipo_accesorio_id][]">
											<option value=""><?php echo $this->config->item('empty_select');?></option>
											<?php foreach($tipos_accesorios as $k=>$v): ?>
												<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
											<?php endforeach;?>
										</select>
										<?php echo form_error('accesorios[tipo_accesorio_id][]');?>
									</td>
									<td>
										<input class="acc_check" name="accesorios[obligatorio_id][]" value="0" type="hidden" />
										<input class="acc_check" type="checkbox" name="accesorios[obligatorio_id][]" value="1" <?php echo  set_checkbox('accesorios[obligatorio_id][]','1',(@$c->obligatorio_id)?TRUE:FALSE); ?> />
										<?php echo form_error('accesorios[obligatorio_id][]');?>
									</td>
									<td colspan="2" align="center"><a href="#" class="delete accion accion3">Eliminar</a></td>
								</tr>
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- FIN ACCESORIOS -->

			<div class="panel panel-default">
				<div class="panel-heading">
				  <h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
					  Gu&iacute;a Mec&aacute;nica
					</a>
				  </h4>
				</div>
				<div id="collapseFive" class="panel-collapse collapse in">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<p class="msg info">
						<span class="fa-stack fa-2x">
							<i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							<i class="fa fa-info fa-stack-1x text-color-info"></i>
						</span>
						Seleccione un archivo para agregar la gu&iacute;a mec&aacute;nica de este producto. </p>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
								<label>Gu&iacute;a Mec&aacute;nica:</label>
								<div class="fileinputs">
									<input id="guia_mecanica" name="guia_mecanica" value="<?php echo set_value('guia_mecanica'); ?>" type="file" accept="image/jpg, application/pdf"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i> Subir Archivo</a>
									</div>
								</div>
								<span><?php echo form_error('guia_mecanica');?></span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
							Manual
						</a>
					</h4>
				</div>
				<div id="collapseSix" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<p class="msg info">
								<span class="fa-stack fa-2x">
								  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
								  <i class="fa fa-info fa-stack-1x text-color-info"></i>
								</span>
									Seleccione un archivo para agregar el manual de este producto.</p>
							</div>
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
								<label>Manual:</label>
								<div class="fileinputs">
									<input id="manual" name="manual" value="<?php echo set_value('manual'); ?>" type="file" accept="image/jpg, application/pdf"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i> Subir Archivo</a>
									</div>
								</div>
								<span><?php echo form_error('manual');?></span>
							</div>
						</div>
					</div>
				</div>
		    </div>
                  
		    <div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">
							Autocad
						</a>
					</h4>
				</div>
				<div id="collapseSeven" class="panel-collapse collapse in">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<p class="msg info">
							<span class="fa-stack fa-2x">
							  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
							  <i class="fa fa-info fa-stack-1x text-color-info"></i>
							</span>
							Seleccione un archivo zip para agregar el archivo autocad de este producto.</p>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
								<label>Autocad:</label>
								<div class="fileinputs">
									<input id="autocad" name="autocad" value="<?php echo set_value('autocad'); ?>" type="file" accept="image/jpg, application/pdf"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i> Subir Archivo</a>
									</div>
								</div>
								<span><?php echo form_error('autocad');?></span>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 barra-btn">
			<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
			<a href="<?php echo site_url('productos/index');?>" class="btn btn-default pull-right">Cancelar</a>
		</div>

		</form>
	</div>

<script type="text/javascript">
<!--
$(function(){

	$(document).ready(function(){
		if($('#unidad_id').val() == 2)
			$('.estados').show();
		else
			$('.estados').hide();
	});

	$('#unidad_id').change(function(){
		if($('#unidad_id').val() == 2)
			$('.estados').show();
		else
			$('.estados').hide();
	});

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
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>