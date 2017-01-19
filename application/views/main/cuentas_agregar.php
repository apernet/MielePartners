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
		<input type="hidden" name="id" value="<?php echo set_value('id'); ?>" />
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
									<input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo set_value('nombre',@$r->nombre); ?>" />
									<?php echo form_error('nombre');?>
					        	</div>
					       		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>C&oacute;digo Interno:</label>
									<input class="form-control" type="text" id="clave" name="clave" value="<?php echo set_value('clave',@$r->clave);?>" />
									<?php echo form_error('clave');?>
							  	</div>
					       		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Tel&eacute;fono:</label>
									<input class="form-control" type="text" id="telefono" name="telefono" value="<?php echo set_value('telefono',@$r->telefono); ?>" />
									<?php echo form_error('telefono');?>
					        	</div>
					       		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>Correo electr&oacute;nico:</label>
									<input  class="form-control" type="text" id="email" name="email" value="<?php echo set_value('email',@$r->email);?>" />
					        	</div>
							</div>

							<div class="row">
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group checkbox">
					        		<label>Sucursal f&iacute;sica:</label>
									<input class="form-control" type="text" id="sucursal_fisica" name="sucursal_fisica" value="<?php echo set_value('sucursal_fisica',@$r->sucursal_fisica);?>" />
									<?php echo form_error('sucursal_fisica');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group checkbox">
									<input name="activo" value="0" type="hidden" />
									<label>Activo</label>
									<input type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />
									<?php echo form_error('activo');?>
						     	</div>
						     	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group checkbox">
									<input name="distribuidor" value="0" type="hidden" />
									<label>Distribuidor</label>
									<input type="checkbox" id="distribuidor" name="distribuidor" value="1" <?php echo set_checkbox('distribuidor','1',(@$r->distribuidor)?TRUE:FALSE); ?> />
									<?php echo form_error('distribuidor');?>
    							</div>
    							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group checkbox">
									<input name="venta_directa" value="0" type="hidden" />
									<label>Venta Directa</label>
									<input type="checkbox" name="venta_directa" value="1" <?php echo set_checkbox('venta_directa','1',(@$r->venta_directa)?TRUE:FALSE); ?> />
									<?php echo form_error('venta_directa');?>
    							</div>
							</div>

							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-2 form-group"></div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 form-group distribuidor_logo" style="display:none;">
									<p class="msg info">Deje el campo de imagen en blanco para conservar la imagen actual o seleccione un archivo para reemplazar la imagen actual con una imagen nueva.</p>
									<div class="fileinputs">
										<input type="file" class="file" id="distribuidor_logo" name="distribuidor_logo" value=""/>
										<div class="fakefile">
											<a href="" class="btn btn-front-primary"><i class="fa fa-upload"></i>  Logotipo de Distribuidor</a>
										</div>
									</div>
									<?php echo form_error('distribuidor_logo');?>
								</div>
							</div><!-- row -->
				    	</div> <!-- panel-body -->
				    </div> <!-- collapse one -->
				</div> <!-- panel default -->

				<div class="panel panel-default">
					<div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				          Informaci&oacute;n bancaria
				        </a>
				      </h4>
				    </div>
				    <div id="collapseTwo" class="panel-collapse collapse in">
				      <div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Cuenta Bancaria:</label>
								<input class="form-control" type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="<?php echo set_value('cuenta_bancaria',@$r->cuenta_bancaria);?>" />
								<?php echo form_error('cuenta_bancaria');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Clabe</label>
								<input class="form-control" type="text" id="cuenta_clabe" name="cuenta_clabe" value="<?php echo set_value('cuenta_clabe',@$r->cuenta_clabe);?>" />
								<?php echo form_error('cuenta_clabe');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Sucursal:</label>
								<input class="form-control" type="text" id="sucursal" name="sucursal" value="<?php echo set_value('sucursal',@$r->sucursal);?>" />
								<?php echo form_error('sucursal');?>
				        	</div>
				        </div>
				      </div>
				    </div> <!-- collapseTwo -->
				</div> <!-- panel default -->

				<div class="panel panel-default">
				    <div class="panel-heading">
						 <h4 class="panel-title">
						 	<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
							Direcci&oacute;n F&iacute;sica
				        	</a>
				     	 </h4>
				    </div>
					<div id="collapseFive" class="panel-collapse collapse in">
						<div class="panel-body">
					        <div class="row">
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>Estado:</label>
									<select class="form-control" name="sucursal_estado" id="sucursal_estado" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($estados as $e):?>
										<option value="<?php echo $e; ?>" <?php echo set_select('sucursal_estado', $e, ($e == @$r->sucursal_estado)); ?>><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('sucursal_estado');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
									<select class="form-control" name="sucursal_municipio" id="sucursal_municipio" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($sucursal_municipios as $e):?>
											<option value="<?php echo $e; ?>" <?php echo set_select('sucursal_municipio', $e, ($e == @$r->sucursal_municipio)); ?>><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('sucursal_municipio');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>C&oacute;digo postal:</label>
									<div class="input-group">
										<input type="text" class="form-control" name="sucursal_codigo_postal" id="sucursal_codigo_postal" value="<?php echo set_value('sucursal_codigo_postal',@$r->sucursal_codigo_postal); ?>">
										<span class="input-group-addon">
										   <a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="sucursal_dir_search"><i class="fa fa-search"></i></a>
										</span>
									</div>
									<?php echo form_error('sucursal_codigo_postal');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>Colonia:</label>
									<input type="text" class="form-control" id="sucursal_asentamiento" name="sucursal_asentamiento" value="<?php echo set_value('sucursal_asentamiento',@$r->sucursal_asentamiento); ?>" />
									<?php echo form_error('sucursal_asentamiento');?>
					        	</div>
				        	</div>
					        <br>
					        <div class="row">
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>Calle:</label>
									<input type="text" class="form-control" id="sucursal_calle" name="sucursal_calle" value="<?php echo set_value('sucursal_calle',@$r->sucursal_calle); ?>" />
									<?php echo form_error('sucursal_calle');?>	
					        	
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
									<input  type="text" class="form-control" id="sucursal_numero_exterior" name="sucursal_numero_exterior" value="<?php echo set_value('sucursal_numero_exterior',@$r->sucursal_numero_exterior); ?>" />
									<?php echo form_error('sucursal_numero_exterior');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label>N&uacute;mero interior:</label>
									<input  type="text" class="form-control" id="sucursal_numero_interior" name="sucursal_numero_interior" value="<?php echo set_value('sucursal_numero_interior',@$r->sucursal_numero_interior); ?>" />
									<?php echo form_error('sucursal_numero_interior');?>
					        	</div>
					        </div>
				        </div>
		        	</div>
		        </div>
				<div class="panel panel-default">
				    <div class="panel-heading">
						 <h4 class="panel-title">
						 	<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
							Datos fiscales
				        	</a>
				     	 </h4>
				    </div>
				    <div id="collapseThree" class="panel-collapse collapse in">
						<div class="panel-body">
					        <div class="row">
						        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        		<label><span class="req">*</span>Facturaci&oacute;n para:</label>
									<select class="form-control" name="tipo_persona_id" id="tipo_persona_id" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach(catalogo('tipo_persona_fiscal') as $k=>$v):?>
										<option value="<?php echo $k; ?>" <?php echo set_select('tipo_persona_id', $k, ($k == @$r->tipo_persona_id)); ?>><?php echo $v; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('tipo_persona_id');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Nombre o Raz&oacute;n social:</label>
									<input class="form-control" type="text" id="razon_social" name="razon_social" value="<?php echo set_value('razon_social',@$r->razon_social); ?>" />
									<?php echo form_error('razon_social');?>
					        	</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>RFC:</label>
									<input class="form-control" type="text" id="rfc" name="rfc" value="<?php echo set_value('rfc',@$r->rfc); ?>" />
									<?php echo form_error('rfc');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Estado:</label>
									<select class="form-control" name="estado" id="estado" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($estados as $e):?>
											<option value="<?php echo $e; ?>" <?php echo set_select('estado', $e, ($e == @$r->estado)); ?>><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('estado');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
									<select class="form-control" name="municipio" id="municipio" >
										<option value=""><?php echo $this->config->item('empty_select');?></option>
										<?php foreach($municipios as $e):?>
										<option value="<?php echo $e; ?>" <?php echo set_select('municipio', $e, ($e == @$r->municipio)); ?>><?php echo $e; ?></option>
										<?php endforeach;?>
									</select>
									<?php echo form_error('municipio');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>C&oacute;digo postal:</label>
									<div class="input-group ">
										  <input type="text" class="form-control" type="text" id="codigo_postal" name="codigo_postal" value="<?php echo set_value('codigo_postal',@$r->codigo_postal);?>">
										  <span class="input-group-addon">
										      <a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="dir_search"><i class="fa fa-search"></i></a>
										  </span>
									</div>
									<?php echo form_error('codigo_postal');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Colonia:</label>
									<input class="form-control" type="text" id="asentamiento" name="asentamiento" value="<?php echo set_value('asentamiento',@$r->asentamiento); ?>" />
									<?php echo form_error('asentamiento');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>Calle:</label>
									<input class="form-control" type="text" id="calle" name="calle" value="<?php echo set_value('calle',@$r->calle); ?>" />
									<?php echo form_error('calle');?>	
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
									<input class="form-control" type="text" id="numero_exterior" name="numero_exterior" value="<?php echo set_value('numero_exterior',@$r->numero_exterior); ?>" />
									<?php echo form_error('numero_exterior');?>
					        	</div>
					        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					        		<label>N&uacute;mero interior:</label>
									<input class="form-control" type="text" id="numero_interior" name="numero_interior" value="<?php echo set_value('numero_interior',@$r->numero_interior); ?>" />
									<?php echo form_error('numero_interior');?>
					        	</div>
					        </div>
					    </div>
				    </div>
				</div> <!-- panel defautl -->
				<div class="panel panel-default">
					<div class="panel-heading">
						 <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
							Datos distribuidor
							</a>
						 </h4>
					</div>
					<div id="collapseFour" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
									<label>Categor&iacute;as en exhibici&oacute;n:</label>
									<select class="multiple" class="form-control" name="categorias_exhibicion[]" multiple>
									<?php foreach($categorias as $id => $c): ?>
										<option value="<?php echo $id; ?>" <?php echo @set_select('categorias_exhibicion[]', $id) ?> ><?php echo $c; ?></option>
									<?php endforeach; ?>
									</select>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
									<label>Paquetes en exhibici&oacute;n:</label>
									<select class="multiple" class="form-control" name="paquetes_exhibicion[]" multiple>
									<?php foreach($paquetes as $id => $p): ?>
										<option value="<?php echo $id; ?>" <?php echo @set_select('paquetes_exhibicion[]', $id) ?>><?php echo $p; ?></option>
									<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<label>Porcentaje descuento (espacio exhibici&oacute;n):</label>
									<div class="input-group">
									  <input class="form-control text-right" id="descuento_espacio" name="descuento_espacio" value="<?php echo set_value('descuento_espacio',@($r->descuento_espacio? $r->descuento_espacio : 0)); ?>" />
									  <span class="input-group-addon">&#37;</span>
									</div>
									<?php echo form_error('descuento_espacio');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<label>Porcentaje descuento (monto ventas):</label>
									<div class="input-group">
									  <input class="form-control text-right" id="descuento_monto" name="descuento_monto" value="<?php echo set_value('descuento_monto',@($r->descuento_monto? $r->descuento_monto : 0)); ?>" />
									  <span class="input-group-addon">&#37;</span>
									</div>
									<?php echo form_error('descuento_monto');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<label>Porcentaje descuento (cooperaci&oacute;n):</label>
									<div class="input-group">
									  <input class="form-control text-right" id="descuento_cooperacion" name="descuento_cooperacion" value="<?php echo set_value('descuento_cooperacion',@($r->descuento_cooperacion? $r->descuento_cooperacion : 0)); ?>" />
									  <span class="input-group-addon">&#37;</span>
									</div>
									<?php echo form_error('descuento_cooperacion');?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<label>Porcentaje descuento (transici&oacute;n):</label>
									<div class="input-group">
									  <input class="form-control text-right" id="descuento_transicion" name="descuento_transicion" value="<?php echo set_value('descuento_transicion',@($r->descuento_transicion? $r->descuento_transicion : 0)); ?>" />
									  <span class="input-group-addon">&#37;</span>
									</div>
									<?php echo form_error('descuento_transicion');?>
								</div>
							</div>
							<div class="row checkbox">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
									<input name="credito" value="0" type="hidden" />
									<label>Cr&eacute;dito </label>
									<input type="checkbox" name="credito" value="1" <?php echo set_checkbox('credito','1',(@$r->credito)?TRUE:FALSE); ?> />
									<?php echo form_error('credito');?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 barra-btn">
					<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
					<a href="<?php echo site_url('main/cuentas');?>" class=" btn btn-default pull-right">Cancelar</a>
				</div>
			</div>
		</form>
	</div>
				
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">

<!--
$(function(){
	Direccion.set();
	Direccion.set('sucursal_');
	$('.multiple').multiSelect({
	    selectableHeader: "<div>Disponible:</div>",
	    selectionHeader: "<div>Seleccionados:</div>"
	});

	function distribuidor_logo()
	{
		if($('#distribuidor').is(':checked'))
		{
			$('.distribuidor_logo').show();
			$('#venta_directa').attr('checked', false);
		}
		else
			$('.distribuidor_logo').hide();
	}

	distribuidor_logo();
	
	$('#distribuidor').change(function(){
		distribuidor_logo();
	});

	$('#venta_directa').change(function(){
		if($(this).is(':checked'))
		{
			$('#distribuidor').attr('checked', false);
			$('#distribuidor').change();
		}
	});
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>