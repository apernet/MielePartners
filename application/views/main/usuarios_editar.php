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
		<input type="hidden" name="id" value="<?php echo set_value('id',@$r->id); ?>" />
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
				      <div>
				      <?php if($this->config->item('codigo_barras')): ?>
						<tr class="altrow">
							<td>
								<label><span class="req">*</span>Identificador &uacute;nico:</label>
								<div class="div_info"><?php echo set_value('unique_id', @$r->unique_id); ?></div>
							</td>
							<td>&nbsp;</td>
						</tr>	
						<?php endif; ?>
				      </div>
				      <div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Grupo:</label>
								<select class="form-control" id="grupos_id" name="grupos_id">
								<option value=""><?php echo $this->config->item('empty_select'); ?></option>
								<?php foreach($grupos as $k=>$v): ?>
									<option value="<?php echo $k; ?>" <?php echo set_select('grupos_id', $k, ($k == @$r->grupos_id)); ?>><?php echo $v; ?></option>
								<?php endforeach;?>
								</select>
								<?php echo form_error('grupos_id');?>
				        	</div>   
		     				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Cuenta:</label>
								<select class="form-control" id="cuentas_id" name="cuentas_id">
									<option value=""><?php echo $this->config->item('empty_select'); ?></option>
									<?php foreach($cuentas as $k=>$v): ?>
									<option value="<?php echo $k; ?>" <?php echo set_select('cuentas_id', $k, ($k == @$r->cuentas_id)); ?>><?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('cuentas_id');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Nombre de usuario:</label>
								<input class="form-control" id="usuario" name="usuario" value="<?php echo set_value('usuario', @$r->usuario); ?>" />
								<?php echo form_error('usuario');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Contrase&ntilde;a:</label>
								<input  id="cambiar_contrasena" type="checkbox"  />
								&iquest;Cambiar contrase&ntilde;a?
								<br/>
								<input class="form-control" id="contrasena" name="contrasena" type="password" disabled="disabled" class="opacity"/>
								<?php echo form_error('contrasena');?>
				        	</div>
				        </div>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Nombre(s):</label>
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre', @$r->nombre); ?>" />
								<?php echo form_error('nombre');?>
				        	</div>   
		     				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Apellido paterno:</label>
								<input class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo set_value('apellido_paterno', @$r->apellido_paterno); ?>" />
								<?php echo form_error('apellido_paterno');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Apellido materno:</label>
								<input class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo set_value('apellido_materno', @$r->apellido_materno); ?>" />
								<?php echo form_error('apellido_materno');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Correo electr&oacute;nico:</label>
								<input class="form-control" id="email" name="email" value="<?php echo set_value('email', @$r->email); ?>" />
								<?php echo form_error('email');?>
				        	</div>
				        </div>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Tel&eacute;fono:</label>
								<input class="form-control" id="telefono" name="telefono" value="<?php echo set_value('telefono', @$r->telefono); ?>" />
								<?php echo form_error('telefono');?>
				        	</div>   
		     				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>Celular:</label>
								<input class="form-control" id="celular" name="celular" value="<?php echo set_value('celular', @$r->celular); ?>" />
								<?php echo form_error('celular');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<div class="checkbox">
									<input name="activo" value="0" type="hidden" />
					        		<label>
										<input  type="checkbox" name="activo" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />
										Activo
									</label>
									<?php echo form_error('activo');?>
				        		</div>
				        	</div>
				        </div>
			        </div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				          Roles 
				        </a>
					</h4>
				</div>
		        <div id="collapseTwo" class="panel-collapse collapse in">
				      <div class="panel-body">
				      	<div class="row">
				      		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				      			<label class="checkbox-inline">
				      				<input type="checkbox" name="admin" id="admin" value="1" <?php echo set_checkbox('admin','1',(@$r->admin)?TRUE:FALSE); ?> />
				      				Administrador
				      			</label>
								<?php echo form_error('admin');?>
				      		</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label class="checkbox-inline" >
								<input name="vendedor" value="0" type="hidden" />
				        			<input type="checkbox" name="vendedor" id="vendedor"value="1" <?php echo set_checkbox('vendedor','1',(@$r->vendedor)?TRUE:FALSE); ?> />
									Vendedor
								</label>
								<?php echo form_error('vendedor');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label class="checkbox-inline" >
								<input name="cliente_externo" value="0" type="hidden" />
				        			<input type="checkbox" name="cliente_externo" id="cliente_externo" value="1" <?php echo set_checkbox('cliente_externo','1',(@$r->cliente_externo)?TRUE:FALSE); ?> />
									Cliente Externo
								</label>
								<?php echo form_error('cliente_externo');?>
				        	</div>
				        </div>
				      </div>
      			</div>
      		</div>
      		<div class="panel panel-default usuarios_cuentas" style="display:none;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
				          Cuentas que administra 
				        </a>
					</h4>
				</div>
		        <div id="collapseThree" class="panel-collapse collapse in">
				      <div class="panel-body">
				      	<div class="row">
				      		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">	
								<label>Cuentas:</label>
								<select class="multiple" class="form-control" name="usuarios_cuentas[]" multiple>
								<?php foreach($cuentas as $id => $c):?>
								    <option value="<?php echo $id; ?>" <?php echo @set_select('usuarios_cuentas[]', $id, @array_key_exists($id, $usuarios_cuentas)? TRUE : FALSE) ?> ><?php echo $id.' - '.$c; ?></option>
								<?php endforeach; ?>
								</select>	
							</div>
				        </div>
				      </div>
      			</div>
      		</div>
      		<div class="panel panel-default usuarios_facturacion" style="display:none;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
				          Datos de Facturaci&oacute;n 
				        </a>
					</h4>
				</div>
		        <div id="collapseFour" class="panel-collapse collapse in">
			      	<div class="panel-body">
			      		<div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->Facturaci&oacute;n para:</label>
								<select class="form-control" name="tipo_persona_id" id="tipo_persona_id" disabled="disabled">
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach(catalogo('tipo_persona_fiscal') as $k=>$v):?>
									<option value="<?php echo $k; ?>" <?php echo set_select('tipo_persona_id', $k, ($k == @$r->tipo_persona_id)); ?>><?php echo $v; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('tipo_persona_id');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
					        	<label><!-- <span class="req">*</span> -->Nombre o Raz&oacute;n social:</label>
								<input type="text" class="form-control" id="razon_social" name="razon_social" value="<?php echo set_value('razon_social',@$r->razon_social); ?>" disabled="disabled"/>
								<?php echo form_error('razon_social');?>
					        </div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>RFC:</label>
								<input type="text" class="form-control" id="rfc" name="rfc" value="<?php echo set_value('rfc',@$r->rfc); ?>" disabled="disabled" />
								<?php echo form_error('rfc');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->Estado:</label>
								<select class="form-control" name="estado" id="estado" disabled="disabled">
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo set_select('estado', $e, ($e == @$r->estado)); ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('estado');?>
				        	</div>
				        </div>
				       	<br>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->Delegaci&oacute;n o municipio:</label>
								<select class="form-control" name="municipio" id="municipio" disabled="disabled" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($municipios as $e):?>
									<option value="<?php echo $e; ?>" <?php echo set_select('municipio', $e, ($e == @$r->municipio)); ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('municipio');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->C&oacute;digo postal:</label>
								<div class="input-group">
									<input type="text" class="form-control" name="codigo_postal" id="codigo_postal" value="<?php echo set_value('codigo_postal',@$r->codigo_postal); ?>" disabled="disabled">
									<span class="input-group-addon">
									   <a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="dir_search"><i class="fa fa-search"></i></a>
									</span>
								</div>
								<?php echo form_error('codigo_postal');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->Colonia:</label>
								<input type="text" class="form-control" id="asentamiento" name="asentamiento" value="<?php echo set_value('asentamiento',@$r->asentamiento); ?>" disabled="disabled"/>
								<?php echo form_error('asentamiento');?>
				        	
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->Calle:</label>
								<input type="text" class="form-control" id="calle" name="calle" value="<?php echo set_value('calle',@$r->calle); ?>" disabled="disabled"/>
								<?php echo form_error('calle');?>	
				        	
				        	</div>
				        </div>
				       	<br>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label><!-- <span class="req">*</span> -->N&uacute;mero exterior:</label>
								<input  type="text" class="form-control" id="numero_exterior" name="numero_exterior" value="<?php echo set_value('numero_exterior',@$r->numero_exterior); ?>" disabled="disabled"/>
								<?php echo form_error('numero_exterior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>N&uacute;mero interior:</label>
								<input  type="text" class="form-control" id="numero_interior" name="numero_interior" value="<?php echo set_value('numero_interior',@$r->numero_interior); ?>" disabled="disabled" />
								<?php echo form_error('numero_interior');?>
				        	</div>
				        </div>
			      	</div>
      			</div>
      		</div>
      		
      		<div class="panel panel-default usuarios_entrega" style="display:none;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
				          Datos de Env&iacute;o 
				        </a>
					</h4>
				</div>
		        <div id="collapseFive" class="panel-collapse collapse in">
			      	<div class="panel-body">
			      		<div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Estado:</label>
								<select class="form-control" name="entrega_estado" id="entrega_estado" disabled="disabled">
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo set_select('entrega_estado', $e, ($e == @$r->entrega_estado)); ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('entrega_estado');?>
				        	</div>
				        </div>
				       	<br>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Delegaci&oacute;n o municipio:</label>
								<select class="form-control" name="entrega_municipio" id="entrega_municipio" disabled="disabled" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($entrega_municipios as $e):?>
									<option value="<?php echo $e; ?>" <?php echo set_select('entrega_municipio', $e, ($e == @$r->entrega_municipio)); ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('entrega_municipio');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>C&oacute;digo postal:</label>
								<div class="input-group">
									<input type="text" class="form-control" name="entrega_codigo_postal" id="entrega_codigo_postal" value="<?php echo set_value('entrega_codigo_postal',@$r->entrega_codigo_postal); ?>" disabled="disabled">
									<span class="input-group-addon">
									   <a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="entrega_dir_search"><i class="fa fa-search"></i></a>
									</span>
								</div>
								<?php echo form_error('entrega_codigo_postal');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Colonia:</label>
								<input type="text" class="form-control" id="entrega_asentamiento" name="entrega_asentamiento" value="<?php echo set_value('entrega_asentamiento',@$r->entrega_asentamiento); ?>" disabled="disabled"/>
								<?php echo form_error('entrega_asentamiento');?>
				        	
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Calle:</label>
								<input type="text" class="form-control" id="entrega_calle" name="entrega_calle" value="<?php echo set_value('entrega_calle',@$r->entrega_calle); ?>" disabled="disabled"/>
								<?php echo form_error('entrega_calle');?>	
				        	
				        	</div>
				        </div>
				       	<br>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>N&uacute;mero exterior:</label>
								<input  type="text" class="form-control" id="entrega_numero_exterior" name="entrega_numero_exterior" value="<?php echo set_value('entrega_numero_exterior',@$r->entrega_numero_exterior); ?>" disabled="disabled"/>
								<?php echo form_error('entrega_numero_exterior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>N&uacute;mero interior:</label>
								<input  type="text" class="form-control" id="entrega_numero_interior" name="entrega_numero_interior" value="<?php echo set_value('entrega_numero_interior',@$r->entrega_numero_interior); ?>" disabled="disabled"/>
								<?php echo form_error('entrega_numero_interior');?>
				        	</div>
				        </div>
			      	</div>
      			</div>
      		</div>
      		
      		<div class="panel panel-default usuarios_instalacion" style="display:none;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
				          Datos de Instalaci&oacute;n 
				        </a>
					</h4>
				</div>
		        <div id="collapseSix" class="panel-collapse collapse in">
			      	<div class="panel-body">
			      		<div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Estado:</label>
								<select class="form-control" name="instalacion_estado" id="instalacion_estado" disabled="disabled">
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo set_select('instalacion_estado', $e, ($e == @$r->instalacion_estado)); ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('instalacion_estado');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Delegaci&oacute;n o municipio:</label>
								<select class="form-control" name="instalacion_municipio" id="instalacion_municipio" disabled="disabled">
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($instalacion_municipios as $e):?>
									<option value="<?php echo $e; ?>" <?php echo set_select('instalacion_municipio', $e, ($e == @$r->instalacion_municipio)); ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('instalacion_municipio');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>C&oacute;digo postal:</label>
								<div class="input-group">
									<input type="text" class="form-control" name="instalacion_codigo_postal" id="instalacion_codigo_postal" value="<?php echo set_value('instalacion_codigo_postal',@$r->instalacion_codigo_postal); ?>" disabled="disabled">
									<span class="input-group-addon">
									   <a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="instalacion_dir_search"><i class="fa fa-search"></i></a>
									</span>
								</div>
								<?php echo form_error('instalacion_codigo_postal');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Colonia:</label>
								<input type="text" class="form-control" id="instalacion_asentamiento" name="instalacion_asentamiento" value="<?php echo set_value('instalacion_asentamiento',@$r->instalacion_asentamiento); ?>" disabled="disabled"/>
								<?php echo form_error('instalacion_asentamiento');?>
				        	
				        	</div>
        				</div>
				       	<br>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Calle:</label>
								<input type="text" class="form-control" id="instalacion_calle" name="instalacion_calle" value="<?php echo set_value('instalacion_calle',@$r->instalacion_calle); ?>" disabled="disabled"/>
								<?php echo form_error('instalacion_calle');?>	
				        	
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>N&uacute;mero exterior:</label>
								<input  type="text" class="form-control" id="instalacion_numero_exterior" name="instalacion_numero_exterior" value="<?php echo set_value('instalacion_numero_exterior',@$r->instalacion_numero_exterior); ?>" disabled="disabled" />
								<?php echo form_error('instalacion_numero_exterior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>N&uacute;mero interior:</label>
								<input  type="text" class="form-control" id="instalacion_numero_interior" name="instalacion_numero_interior" value="<?php echo set_value('instalacion_numero_interior',@$r->instalacion_numero_interior); ?>" disabled="disabled"/>
								<?php echo form_error('instalacion_numero_interior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Nombre de contacto:</label>
								<input  type="text" class="form-control" id="instalacion_nombre_contacto" name="instalacion_nombre_contacto" value="<?php echo set_value('instalacion_nombre_contacto',@$r->instalacion_nombre_contacto); ?>" disabled="disabled"/>
								<?php echo form_error('instalacion_nombre_contacto');?>
				        	</div>
				        </div>
				        <br>
				        <div class="row">
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Tel&eacute;fono particular:</label>
								<input type="text" class="form-control" id="instalacion_telefono_particular" name="instalacion_telefono_particular" value="<?php echo set_value('instalacion_telefono_particular',@$r->instalacion_telefono_particular); ?>" disabled="disabled"/>
								<?php echo form_error('instalacion_telefono_particular');?>	
				        	
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				        		<label>Tel&eacute;fono celular:</label>
								<input  type="text" class="form-control" id="instalacion_telefono_celular" name="instalacion_telefono_celular" value="<?php echo set_value('instalacion_telefono_celular',@$r->instalacion_telefono_celular); ?>" disabled="disabled"/>
								<?php echo form_error('instalacion_telefono_celular');?>
				        	</div>
				        </div>
			      	</div>
      			</div>
      		</div>
      		
      	</div>
     	<div class="col-lg-12 barra-btn">
     		<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
			<a href="<?php echo site_url('main/usuarios');?>" class="btn btn-default pull-right">Cancelar</a>
			<div class="clearblock">&nbsp;</div>
     	</div>
	</form>
	</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
<!--
$(function(){

	Direccion.set('');
	Direccion.set('instalacion_');
	Direccion.set('entrega_');
	
	if($('#admin').is(':checked'))
		$('.usuarios_cuentas').show();

	if($('#cliente_externo').is(':checked'))
	{
		$('.usuarios_facturacion').show();
		$('.usuarios_entrega').show();
		$('.usuarios_instalacion').show();
	}
	
	$('.multiple').multiSelect({
	    selectableHeader: "<div>Disponible:</div>",
	    selectionHeader: "<div>Seleccionados:</div>",
	});

	$('#admin').change(function(){
		if($(this).is(':checked'))
		{
			$('.usuarios_cuentas').show();
			$('#cliente_externo').removeAttr('checked');
			$('.usuarios_facturacion').hide();
			$('.usuarios_entrega').hide();
			$('.usuarios_instalacion').hide();
		}
		else
			$('.usuarios_cuentas').hide();
	});

	$('#vendedor').change(function(){
		if($(this).is(':checked'))
		{
			$('#cliente_externo').removeAttr('checked');
			$('.usuarios_facturacion').hide();
			$('.usuarios_entrega').hide();
			$('.usuarios_instalacion').hide();
		}
	});
	
	$('#cliente_externo').change(function(){
		if($(this).is(':checked'))
		{
			$('.usuarios_facturacion').show();
			$('.usuarios_entrega').show();
			$('.usuarios_instalacion').show();
			$('#admin').removeAttr('checked');
			$('#vendedor').removeAttr('checked');
			$('.usuarios_cuentas').hide();
			
		}
		else
		{
			$('.usuarios_facturacion').hide();
			$('.usuarios_entrega').hide();
			$('.usuarios_instalacion').hide();
		}
	});
	
	$('#cambiar_contrasena').change(function(){
		if(this.checked)
		{
			$('#contrasena').removeAttr('disabled');
			$('#contrasena').toggleClass('opacity');
		}			
		else
		{	
			$('#contrasena').attr('disabled','disabled');
			$('#contrasena').toggleClass('opacity');
		}
	});	
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>