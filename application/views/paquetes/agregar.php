<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div class="col-lg-12 formulario-head">
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
								<label><span class="req">*</span>Descuento (%):</label>
								<input class="form-control" id="descuento" name="descuento" value="<?php echo set_value('descuento',@$r->descuento); ?>" />
								<?php echo form_error('descuento');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Comisi&oacute;n distribuidor (%):</label>
								<input class="form-control" id="descuento_distribuidor" name="descuento_distribuidor" value="<?php echo set_value('descuento_distribuidor',@$r->descuento_distribuidor); ?>" />
								<?php echo form_error('descuento_distribuidor');?>
							</div>
							
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label><span class="req">*</span>Comisi&oacute;n vendedor (%) :</label>
								<input class="form-control" id="comision_vendedor" name="comision_vendedor" value="<?php echo set_value('comision_vendedor',@$r->comision_vendedor); ?>" />
								<?php echo form_error('comision_vendedor');?>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
								<label>Descuento por exhibici&oacute;n (%) :</label>
								<input class="form-control" id="descuento_exhibicion" name="descuento_exhibicion" value="<?php echo set_value('descuento_exhibicion',@$r->descuento_exhibicion); ?>" />
								<?php echo form_error('descuento_exhibicion');?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Descripcion -->
			<div class="panel panel-default">
			  	<div class="panel-heading">
			     	<h4 class="panel-title">
			       		<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				        Descripci&oacute;n
				       	</a>
			     	</h4>
			  	</div>
			  	<div id="collapseTwo" class="panel-collapse collapse in">
			     	<div class="panel-body">
			        	<div class="row">
				        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					        	<label><span class="req">*</span>Contenido</label>
								<textarea id="descripcion" name="descripcion" class="w90p form-control" " rows="6"><?php echo set_value('descripcion',@$r->descripcion); ?></textarea>
								<?php echo form_error('descripcion');?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--  Fin descripcion-->
			<!-- Produtos del paquete -->
			<div class="panel panel-default">
			  	<div class="panel-heading">
			     	<h4 class="panel-title">
			       		<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
				        Elementos
				       	</a>
			     	</h4>
			  	</div>
				<div id="collapseThree" class="panel-collapse collapse in">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="row mb10">
							<div class="col-lg-4 pull-right">
								<a role="button" class=" btn-default btn btn-xs pull-right" href="#" id="categorias_add">Agregar Categor&iacute;a</a>
							</div>
						</div>
						<div class="table-responsive">	
							<table class="table table-hover" id="categorias">
							<thead>
								<tr>
									<td class="tc">Categor&iacute;as</td>
									<td class="tc">Cantidad</td>
									<td class="tc">Acciones</td>
								</tr>
							</thead>
							<tbody>
								<tr style="display: none;" class="bgcw">
									<td colspan="2">
										<div class="row clone">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
												<input type="hidden" name="categorias[indice][]" value="0" class="indice"/>
												<select class="form-control" name="categorias[categorias_id][]" >
													<option value=""><?php echo $this->config->item('empty_select'); ?></option>
													<?php foreach($categorias as $k=>$v):?>
													<option value="<?php echo $k; ?>"> <?php echo $v; ?></option>
													<?php endforeach;?>
												</select>
												<?php echo form_error('categorias[categorias_id][]');?>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
												<input class="form-control" type="text" name="categorias[cantidad][]" value=""/>
												<?php echo form_error('categorias[cantidad][]');?>
											</div>
										</div>
									</td>
									<td class="tc">
										<a href="#" class="delete accion accion3 eliminar">Eliminar</a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a href="#" class="delete accion accion2 opcional_add">Agregar Opcional</a>
									</td>
								</tr>
								<!-- Recueprar datos -->
								<?php if(!empty($_POST['categorias']['categorias_id'])):?>
								<?php  $i=0 ;foreach($_POST['categorias']['categorias_id'] as $k=>$v):?>
									<?php if($_POST['categorias']['categorias_id'][$i] !=0):?>
										<tr class="bgcw">
											<td colspan="2">
												<div class="row clone">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
														<input class="id" type="hidden" name="categorias[id][] " value="<?php echo $_POST['categorias']['categorias_id'][$i];?>" />
														<select class="form-control" name="categorias[categorias_id][]" >
															<option value=""><?php echo $this->config->item('empty_select'); ?></option>
															<?php foreach($categorias as $k=>$v):?>
															<option value="<?php echo $k; ?>" <?php echo ($k ==  $_POST['categorias']['categorias_id'][$i])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
															<?php endforeach;?>
														</select>
														<?php echo form_error('categorias[categorias_id][]');?>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-group">
													<input class="form-control" type="text" name="categorias[cantidad][]" value="<?php echo $_POST['categorias']['cantidad'][$i];?>"/>
													<?php echo form_error('categorias[cantidad][]');?>
												</div>
											</td>
											<td class="tc">
												<?php if($puede_eliminar_categoria):?>
												<a href="#" class="delete accion accion3 eliminar">Eliminar</a>&nbsp;&nbsp;|&nbsp;&nbsp;
												<?php endif;?>
												<a href="#" class="delete accion accion2 opcional_add">Agregar Opcional</a>
											</td>
										</tr>
									<?php endif;?>
								<?php $i++; endforeach;?>
								<?php endif;?>
								<!-- Fin de recuperar datos -->
							</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
				          Imagen 
				        </a>
				      </h4>
				</div>
				<div id="collapseFour" class="panel-collapse collapse in">
				   	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<p class="msg info">
						<span class="fa-stack fa-2x">
						  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
						  <i class="fa fa-info fa-stack-1x text-color-info"></i>
						</span>
						Seleccione un archivo de imagen para agregarla como  imagen del paquete.</p>
					</div>
					<div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
				        		<label>Agregar imagen:</label>
								<div class="fileinputs">
									<input id="imagen" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
									<div class="fakefile">
										<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Subir Archivo</a>
									</div>
								</div>
								<input id="imagen" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
								<span class="ml80"><?php echo form_error('imagen');?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 barra-btn">
			<input  class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
			<a href="<?php echo site_url('paquetes/index');?>"  class="btn btn-default pull-right">Cancelar</a>
		</div>
	</form>
</div>

<script type="text/javascript">
<!--
$(function(){
	var indice=0;
	
	$('#categorias_add').click(function(e){
		var row = $('#categorias tbody>tr:first').clone(true);
		//indice=row.find('input.indice').val();
		indice++;
		row.find('input.indice').val(indice);
		//row.find('td.c_id').html('&nbsp;');
		row.insertAfter('#categorias tbody>tr:last');
		row.show();
		$('#cambio').val('1');
		return false;
	});

	$('.opcional_add').click(function(e){
		var row = $("<div class='clearfix'></div>");
		row.append('<p class="tc opcional">- o -</p>');
		row.append($('#categorias tbody>tr:first').find('.clone').children().clone(true));
		row.find('input.indice').val(indice);
		$(this).parent().parent().find('.clone').append(row);
		$('#cambio').val('1');
		return false;
	});
	
	$('.eliminar').click(function(){
		if(confirm('¿Seguro qué desea eliminar esta categoría?'))
		{
			$('#cambio').val('1');
			$(this).closest('tr').remove();
		}	
		return false;
	});
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>