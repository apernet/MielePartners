<?php $this->load->view('frontend/layout/header');?>
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
<div class="mb60 minHeight">
	<form action="<?php echo site_url('frontends/mis_datos');?>" id="form" method="post" enctype="multipart/form-data">
		<div class="panel-group front_panel" id="accordion1">
	  		<div class="panel panel-default">
			    <div class="panel-heading">
			      	<h4 class="panel-title">
			        	<a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">
				          Datos Generales
				        </a>
			      	</h4>
			    </div>
				<div id="collapseOne" class="panel-collapse collapse in">
				      <div class="panel-body">
				        <div class="row">
							<input type="hidden" class="form-control" id="grupos_id" name="grupos_id" value="<?php echo set_value('grupos_id', @$r['grupos_id']); ?>" />
							<?php echo form_error('grupos_id');?>
							<input type="hidden" class="form-control" id="cuentas_id" name="cuentas_id" value="<?php echo set_value('cuentas_id', @$r['cuentas_id']); ?>" />
							<?php echo form_error('cuentas_id');?>
				        	<div class="col-lg-3 form-group">
				        		<label><span class="req">*</span>Nombre de usuario:</label>
								<input class="form-control" id="usuario" name="usuario" value="<?php echo set_value('usuario', @$r['usuario']); ?>" readonly/>
								<?php echo form_error('usuario');?>
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label><span class="req">*</span>Correo electr&oacute;nico:</label>
								<input class="form-control" id="email" name="email" value="<?php echo set_value('email', @$r['email']); ?>" readonly/>
								<?php echo form_error('email');?>
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Contrase&ntilde;a:</label>
								<input  id="cambiar_contrasena" type="checkbox"  />
								&iquest;Cambiar contrase&ntilde;a?
								<br/>
								<input class="form-control" id="contrasena" name="contrasena" type="password" disabled="disabled" class="opacity"/>
								<?php echo form_error('contrasena');?>
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Tel&eacute;fono:</label>
								<input class="form-control" id="telefono" name="telefono" value="<?php echo set_value('telefono', @$r['telefono']); ?>" />
								<?php echo form_error('telefono');?>
				        	</div>
				        </div>
				        <div class="row">
				        	<div class="col-lg-3 form-group">
				        		<label><span class="req">*</span>Nombre(s):</label>
								<input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre', @$r['nombre']); ?>" />
								<?php echo form_error('nombre');?>
				        	</div>   
		     				<div class="col-lg-3 form-group">
				        		<label><span class="req">*</span>Apellido paterno:</label>
								<input class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo set_value('apellido_paterno', @$r['apellido_paterno']); ?>" />
								<?php echo form_error('apellido_paterno');?>
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label><span class="req">*</span>Apellido materno:</label>
								<input class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo set_value('apellido_materno', @$r['apellido_materno']); ?>" />
								<?php echo form_error('apellido_materno');?>
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Celular:</label>
								<input class="form-control" id="celular" name="celular" value="<?php echo set_value('celular', @$r['celular']); ?>" />
								<?php echo form_error('celular');?>
				        	</div>
				        </div>
			        </div>
				</div>
			</div>
		</div>
		<div class="panel-group front_panel" id="accordion4">
  		<div class="panel panel-default">
		    <div class="panel-heading">
		      	<h4 class="panel-title">
		        	<a data-toggle="collapse" data-parent="#accordion4" href="#collapseFour">
			          Direcci&oacute;n de Env&iacute;o
			        </a>
		      	</h4>
		    </div>
		    <div id="collapseFour" class="panel-collapse collapse in">
		      	<div class="panel-body">
			        <div class="row">
						<div class="col-lg-3 form-group">
			        		<label><span class="req">*</span>Estado:</label>
								<select class="form-control envio <?php echo form_error('entrega_estado')?'rojo':'';?>" name="entrega_estado" id="entrega_estado" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo ($e==@$r['entrega_estado'])?'selected="selected"':''; ?> ><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
							<?php echo form_error('entrega_estado');?>
			        	</div>
			        	<div class="col-lg-3 form-group">
			        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
								<select class="form-control <?php echo form_error('entrega_municipio')?'rojo':'';?>" name="entrega_municipio" id="entrega_municipio" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($entrega_municipios as $mpio):?>
									<option value="<?php echo $mpio; ?>" <?php echo ($mpio==@$r['entrega_municipio'])?'selected="selected"':''; ?>><?php echo $mpio; ?></option>
									<?php endforeach;?>
								</select>
							<?php echo form_error('entrega_municipio');?>
					        </div>
					        <div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label><span class="req">*</span>C&oacute;digo postal:</label>
	        		       		<div class="input-group">
					        		<input class="form-control <?php echo form_error('entrega_codigo_postal')?'rojo':'';?>" type="text" id="entrega_codigo_postal"  name="entrega_codigo_postal"  value="<?php echo set_value('entrega_codigo_postal',@$r['entrega_codigo_postal']); ?>" />
				        		<span class="input-group-addon">
	    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="entrega_dir_search"><i class="fa fa-search"></i></a>
	    					   	</span>
							</div>
							<?php echo form_error('entrega_codigo_postal');?>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label><span class="req">*</span>Colonia:</label>
			        		<div class="input-group">
				        		<input class="form-control <?php echo form_error('entrega_asentamiento')?'rojo':'';?>" type="text" id="entrega_asentamiento"  name="entrega_asentamiento"  value="<?php echo set_value('entrega_asentamiento',@$r['entrega_asentamiento']); ?>" />
				        		<span class="input-group-addon">
	    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="entrega_cp_search"><i class="fa fa-search"></i></a>
		    					</span>
	    					   	</div>
								<?php echo form_error('entrega_asentamiento');?>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label><span class="req">*</span>Calle:</label>
			        		<input class="form-control <?php echo form_error('entrega_calle')?'rojo':'';?>" type="text" id="entrega_calle"  name="entrega_calle"  value="<?php echo set_value('entrega_calle',@$r['entrega_calle']); ?>" />
							<?php echo form_error('entrega_calle');?>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
			        		<input class="form-control <?php echo form_error('entrega_numero_exterior')?'rojo':'';?>" type="text" id="entrega_numero_exterior"  name="entrega_numero_exterior"  value="<?php echo set_value('entrega_numero_exterior',@$r['entrega_numero_exterior']); ?>" />
							<?php echo form_error('entrega_numero_exterior');?>
							<a href="#" id="datos_instalacion" title="De click sobre este enlace para copiar los datos de env&iacute;o a datos de instalaci&oacute;n" >
								Copiar datos de env&iacute;o a datos de Instalaci&oacute;n
							</a>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>N&uacute;mero interior:</label>
			        		<input class="form-control <?php echo form_error('entrega_numero_interior')?'rojo':'';?>" type="text" id="entrega_numero_interior"  name="entrega_numero_interior"  value="<?php echo set_value('entrega_numero_interior',@$r['entrega_numero_interior']); ?>" />
							<?php echo form_error('entrega_numero_interior');?>
							<a href="#" id="datos_envio" title="De click sobre este enlace para copiar los datos de env&iacute;o a datos de facturaci&oacute;n" >
								Copiar datos de env&iacute;o a datos de Facturaci&oacute;n
							</a>
				        </div>
				    </div>
		        </div>
			</div>
		</div>
     </div>
	<div class="panel-group front_panel" id="accordion9">
  		<div class="panel panel-default">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion9" href="#collapseNine">
		          Direcci&oacute;n de Instalaci&oacute;n
		        </a>
		      </h4>
		    </div>
		    <div id="collapseNine" class="panel-collapse collapse in">
		      	<div class="panel-body">
			        <div class="row">
			            <div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>Nombre contacto:</label>
			        		<input class="form-control <?php echo form_error('instalacion_nombre_contacto')?'rojo':'';?>" type="text" id="instalacion_nombre_contacto"  name="instalacion_nombre_contacto"  value="<?php echo set_value('instalacion_nombre_contacto',@$r['instalacion_nombre_contacto']); ?>" />
							<?php echo form_error('instalacion_nombre_contacto');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-4 form-group">
			        		<label>Tel&eacute;fono particular:</label>
			        		<input class="form-control <?php echo form_error('instalacion_telefono_particular')?'rojo':'';?>" type="text" id="instalacion_telefono_particular"  name="instalacion_telefono_particular"  value="<?php echo set_value('instalacion_telefono_particular',@$r['instalacion_telefono_particular']); ?>" />
							<?php echo form_error('instalacion_telefono_particular');?>
			        	</div>
			        	<div class="col-lg-3 col-md-3 col-sm-4 form-group">
			        		<label>Tel&eacute;fono celular:</label>
			        		<input class="form-control <?php echo form_error('instalacion_telefono_celular')?'rojo':'';?>" type="text" id="instalacion_telefono_celular"  name="instalacion_telefono_celular"  value="<?php echo set_value('instalacion_telefono_celular',@$r['instalacion_telefono_celular']); ?>" />
							<?php echo form_error('instalacion_telefono_celular');?>
			        	</div>
						<div class="col-lg-3 form-group">
			        		<label>Estado:</label>
							<select class="form-control envio <?php echo form_error('instalacion_estado')?'rojo':'';?>" name="instalacion_estado" id="instalacion_estado" >
								<option value=""><?php echo $this->config->item('empty_select');?></option>
								<?php foreach($estados as $e):?>
								<option value="<?php echo $e; ?>" <?php echo ($e==@$r['instalacion_estado'])?'selected="selected"':''; ?> ><?php echo $e; ?></option>
								<?php endforeach;?>
							</select>
							<?php echo form_error('instalacion_estado');?>
			        	</div>
			        	<div class="col-lg-3 form-group">
			        		<label>Delegaci&oacute;n o municipio:</label>
							<select class="form-control <?php echo form_error('instalacion_municipio')?'rojo':'';?>" name="instalacion_municipio" id="instalacion_municipio" >
								<option value=""><?php echo $this->config->item('empty_select');?></option>
								<?php foreach($instalacion_municipios as $mpio):?>
									<option value="<?php echo $mpio; ?>" <?php echo ($mpio==@$r['instalacion_municipio'])?'selected="selected"':''; ?>><?php echo $mpio; ?></option>
								<?php endforeach;?>
							</select>
							<?php echo form_error('instalacion_municipio');?>
				        </div>
				        <div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>C&oacute;digo postal:</label>
        		       		<div class="input-group">
				        		<input class="form-control <?php echo form_error('instalacion_codigo_postal')?'rojo':'';?>" type="text" id="instalacion_codigo_postal"  name="instalacion_codigo_postal"  value="<?php echo set_value('instalacion_codigo_postal',@$r['instalacion_codigo_postal']); ?>" />
				        		<span class="input-group-addon">
	    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="instalacion_dir_search"><i class="fa fa-search"></i></a>
	    					   	</span>
							</div>
							<?php echo form_error('instalacion_codigo_postal');?>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>Colonia:</label>
			        		<div class="input-group">
				        		<input class="form-control <?php echo form_error('instalacion_asentamiento')?'rojo':'';?>" type="text" id="instalacion_asentamiento"  name="instalacion_asentamiento"  value="<?php echo set_value('instalacion_asentamiento',@$r['instalacion_asentamiento']); ?>" />
				        		<span class="input-group-addon">
	    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="instalacion_cp_search"><i class="fa fa-search"></i></a>
	    					   	</span>
    					   	</div>
							<?php echo form_error('instalacion_asentamiento');?>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>Calle:</label>
			        		<input class="form-control <?php echo form_error('instalacion_calle')?'rojo':'';?>" type="text" id="instalacion_calle"  name="instalacion_calle"  value="<?php echo set_value('instalacion_calle',@$r['instalacion_calle']); ?>" />
							<?php echo form_error('instalacion_calle');?>
			        	</div>
			        	<div class="clearfix"></div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>N&uacute;mero exterior:</label>
			        		<input class="form-control <?php echo form_error('instalacion_numero_exterior')?'rojo':'';?>" type="text" id="instalacion_numero_exterior"  name="instalacion_numero_exterior"  value="<?php echo set_value('instalacion_numero_exterior',@$r['instalacion_numero_exterior']); ?>" />
							<?php echo form_error('instalacion_numero_exterior');?>
			        	</div>
			        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>N&uacute;mero interior:</label>
			        		<input class="form-control <?php echo form_error('instalacion_numero_interior')?'rojo':'';?>" type="text" id="instalacion_numero_interior"  name="instalacion_numero_interior"  value="<?php echo set_value('instalacion_numero_interior',@$r['instalacion_numero_interior']); ?>" />
							<?php echo form_error('instalacion_numero_interior');?>
				        </div>
				        <!-- <div class="col-lg-3 col-md-4 col-sm-4 form-group">
			        		<label>Fecha Tentativa de Instalaci&oacute;n:</label>
			        		<input id="entrega_fecha_instalacion_alt" type="hidden" name="entrega_fecha_instalacion" value="<?php echo @$r['entrega_fecha_instalacion']; ?>" readonly="readonly"/>
			        		<input class="form-control fecha <?php echo form_error('entrega_fecha_instalacion')?'rojo':'';?>" type="text" id="entrega_fecha_instalacion" value="<?php echo get_fecha(@$r['entrega_fecha_instalacion']); ?>" />
							<?php echo form_error('entrega_fecha_instalacion');?>
				        </div> -->
				        </div>
		        	</div>
		        </div>
			</div>
     	</div>
			<div class="panel-group front_panel" id="accordion5">
	  		<div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion5" href="#collapseFive">
			          Datos de Facturaci&oacute;n
			        </a>
			      </h4>
			    </div>
			    <div id="collapseFive" class="panel-collapse collapse in">
			      	<div class="panel-body">
				        <div class="row">
				            <div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>Facturaci&oacute;n para:</label>
				        		<select name="tipo_persona_id" id="tipo_persona_id" class="form-control <?php echo form_error('tipo_persona_id')?'rojo':'';?>">
	                				<option value=""><?php echo $this->config->item('empty_select');?></option>
	                				<?php foreach(catalogo('tipo_persona_fiscal',FALSE) as $k=>$v):?>
	                				<option value="<?php echo $k; ?>" <?php echo $k == @$r['tipo_persona_id']?'selected':''; ?> ><?php echo $v; ?></option>
	                				<?php endforeach;?>
	                			</select>
	                			<?php echo form_error('tipo_persona_id');?>
				        	</div>
				            <div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>Nombre o Raz&oacute;n social:</label>
				        		<input class="form-control <?php echo form_error('razon_social')?'rojo':'';?>" type="text" id="razon_social"  name="razon_social"  
				        			value="<?php echo set_value('razon_social',@$r['razon_social']); ?>"/>
								<?php echo form_error('razon_social');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>RFC:</label>
								<input class="form-control <?php echo form_error('rfc')?'rojo':'';?>" type="text" id="rfc" name="rfc"  
									value="<?php echo set_value('rfc',@$r['rfc']); ?>" />
								<?php echo form_error('rfc');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
	        	        		<label>Correo electr&oacute;nico:</label>
	    					    <input class="form-control <?php echo form_error('email')?'rojo':'';?>" id="email"  name="email"  
	    					    	value="<?php echo set_value('email',@$r['email']); ?>" readonly/>
	        					<?php echo form_error('email');?>
	        	        	</div>
							<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>Estado:</label>
								<select class="form-control <?php echo form_error('estado')?'rojo':'';?>" name="estado" id="estado" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo ($e==@$r['estado'])?'selected="selected"':''; ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('estado');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>Delegaci&oacute;n o municipio:</label>
								<select class="form-control <?php echo form_error('municipio')?'rojo':'';?>" name="municipio" id="municipio" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($municipios as $m):?>
									<option value="<?php echo $m; ?>" <?php echo ($m == @$r['municipio'])?'selected="selected"':''; ?>><?php echo $m; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('municipio');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>C&oacute;digo postal:</label>
	        		       		<div class="input-group">
					        		<input class="form-control <?php echo form_error('codigo_postal')?'rojo':'';?>" type="text" id="codigo_postal"  name="codigo_postal" 
					        			value="<?php echo set_value('codigo_postal',@$r['codigo_postal']); ?>"/>
					        		<span class="input-group-addon">
		    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="dir_search"><i class="fa fa-search"></i></a>
		    					   	</span>
								</div>
								<?php echo form_error('codigo_postal');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>Colonia:</label>
				        		<div class="input-group">
					        		<input class="form-control <?php echo form_error('asentamiento')?'rojo':'';?>" type="text" id="asentamiento"  name="asentamiento" 
					        		value="<?php echo set_value('asentamiento',@$r['asentamiento']); ?>"/>
					        		<span class="input-group-addon">
		    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="cp_search"><i class="fa fa-search"></i></a>
		    					   	</span>
	    					   	</div>
								<?php echo form_error('asentamiento');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>Calle:</label>
				        		<input class="form-control <?php echo form_error('calle')?'rojo':'';?>" type="text" id="calle" name="calle"  
				        			value="<?php echo set_value('calle',@$r['calle']); ?>"/>
								<?php echo form_error('calle');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>N&uacute;mero exterior:</label>
				        		<input class="form-control <?php echo form_error('numero_exterior')?'rojo':'';?>" type="text" id="numero_exterior" name="numero_exterior"  
				        			value="<?php echo set_value('numero_exterior',@$r['numero_exterior']); ?>"/>
								<?php echo form_error('numero_exterior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
				        		<label>N&uacute;mero interior:</label>
				        		<input class="form-control <?php echo form_error('numero_interior')?'rojo':'';?>" type="text" id="numero_interior" name="numero_interior"  
				        		value="<?php echo set_value('numero_interior',@$r['numero_interior']); ?>"/>
								<?php echo form_error('numero_interior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-4 col-sm-4 form-group">
	        	        		<label>Tel&eacute;fono:</label>
	        					<input class="form-control <?php echo form_error('telefono')?'rojo':'';?>" id="telefono"  name="telefono"  
	        						value="<?php echo set_value('telefono',@$r['telefono']); ?>"/>
	        					<?php echo form_error('telefono');?>
	        	        	</div>
				        </div>
		        	</div>
		        </div>
			</div>
     	</div>
		
		<div class="front_btnsWrapper mt20 mb100">
			<button class="btn btn-front-primary pull-right" type="submit" id="generar_cotizacion">Guardar</button>
		</div>
	</form>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
$(function(){
	Direccion.set('');
	Direccion.set('entrega_');
	Direccion.set('instalacion_');

	$('#datos_envio').click(function(e){/*Solicitante al inmueble*/
		e.preventDefault();
		if(confirm('¿Seguro qué desea copiar la dirección de envío a los datos de Facturación?'))
		{
			$('#razon_social').val($('#nombre_contacto').val());
			$('#telefono').val($('#telefono_particular').val());
			$('#estado').val($('#entrega_estado').val());
			Direccion.get_municipios('',$('#entrega_municipio').val());
			$('#asentamiento').val($('#entrega_asentamiento').val());
			$('#codigo_postal').val($('#entrega_codigo_postal').val());
			$('#calle').val($('#entrega_calle').val());
			$('#numero_exterior').val($('#entrega_numero_exterior').val());
			$('#numero_interior').val($('#entrega_numero_interior').val());
			$('#cambio').val('1');
			//$('#rfc').val('');
			//$('#email').val('');
			
		}
		return false;
	});

	$('#datos_instalacion').click(function(e){
		e.preventDefault();
		if(confirm('¿Seguro qué desea copiar la dirección de envío a los datos de Instalación?'))
		{
			$('#instalacion_nombre_contacto').val($('#nombre_contacto').val());
			$('#instalacion_telefono_particular').val($('#telefono_particular').val());
			$('#instalacion_telefono_celular').val($('#telefono_celular').val());
			$('#instalacion_estado').val($('#entrega_estado').val());
			Direccion.get_municipios('instalacion_',$('#entrega_municipio').val());
			$('#instalacion_asentamiento').val($('#entrega_asentamiento').val());
			$('#instalacion_codigo_postal').val($('#entrega_codigo_postal').val());
			$('#instalacion_calle').val($('#entrega_calle').val());
			$('#instalacion_numero_exterior').val($('#entrega_numero_exterior').val());
			$('#instalacion_numero_interior').val($('#entrega_numero_interior').val());
			$('#cambio').val('1');
			//$('.envio').change();
			$('#instalacion_dir_search').trigger('click');
		}
		return false;
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
</script>
<?php $this->load->view('frontend/layout/footer');?>