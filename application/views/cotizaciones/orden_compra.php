<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<?php if(empty($r['cotizacion']->fecha_compra)):?>
	<?php $r['cotizacion']->fecha_compra=date('Y-m-d');?>
<?php endif;?>

<h1><?php echo $titulo.' - '. @$r['cotizacion']->folio?></h1>
<p class="msg info">Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
<input name="id" value="<?php echo set_value('id',@$r['cotizacion']->id);?>" type="hidden" />
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Informaci&oacute;n general</div>
<div>
<table class="table_form">
<tbody>
	<tr class="altrow" >
		<td>
			<label><span class="req">*</span>Fecha compra:</label>
				<input id="fecha_alt" type="hidden" name="fecha" value="<?php echo set_value('fecha',@$r['cotizacion']->fecha_compra); ?>" readonly="readonly"/>
				<input id="fecha"  class="fecha"  value="<?php ver_fecha(set_value('fecha',@$r['cotizacion']->fecha_compra)); ?>" />
			<?php echo form_error('fecha');?>
		</td>
		<td>
			<label><span class="req">*</span>Fecha entrega:</label>
				<input id="fecha_entrega_alt" type="hidden" name="fecha_entrega" value="<?php echo set_value('fecha_entrega',@$r['cotizacion']->fecha_entrega); ?>" readonly="readonly"/>
				<input id="fecha_entrega"  class="fecha"  value="<?php ver_fecha(set_value('fecha_entrega',@$r['cotizacion']->fecha_entrega)); ?>" />
			<?php echo form_error('fecha_entrega');?>
		</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>Forma de pago:</label>
			<select name="forma_pago" id="forma_pago" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($forma_pago as $k=>$v):?>
				<option value="<?php echo $k; ?>" <?php echo set_select('forma_pago', $k, ($k == @$r['cotizacion']->forma_pago_id)); ?> ><?php echo $v; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('forma_pago');?>
		</td>
		<td>
			<label><span class="req">*</span>Condiciones de pago:</label>
			<select name="condiciones_pago_id" id="condiciones_pago_id" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($condiciones_pago as $k=>$v):?>
				<option value="<?php echo $k; ?>" <?php echo set_select('condiciones_pago_id', $k, ($k == @$r['cotizacion']->condiciones_pago_id)); ?> ><?php echo $v; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('condiciones_pago_id');?>
		</td>
	</tr>
	</tbody>
</table>
</div>

<!-- DATOS DEL COMPRADOR -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">DATOS DEL COMPRADOR</div>
<div>
<table class="table_form">
	<tbody>	
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Nombre:</label>
			<input id="nombre_comprador"  name="nombre_comprador"  value="<?php echo set_value('nombre_comprador',@$r['cotizacion']->nombre_comprador); ?>"/>
			<?php echo form_error('nombre_comprador');?>	
		</td>
		<td>
			<label><span class="req">*</span>Apellido Paterno:</label>
			<input id="paterno_comprador"  name="paterno_comprador"  value="<?php echo set_value('paterno_comprador',@$r['cotizacion']->paterno_comprador); ?>"/>
			<?php echo form_error('paterno_comprador');?>	
		</td>
		<td>
			<label><span class="req">*</span>Apellido Materno:</label>
			<input id="materno_comprador"  name="materno_comprador"  value="<?php echo set_value('materno_comprador',@$r['cotizacion']->materno_comprador); ?>"/>
			<?php echo form_error('materno_comprador');?>	
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>Correo Electr&oacute;nico:</label>
			<input id="email_comprador"  name="email_comprador"  value="<?php echo set_value('email_comprador',@$r['cotizacion']->email_comprador); ?>"/>
			<?php echo form_error('email_comprador');?>	
		</td>
		<td>
			<label><span class="req">*</span>Tel&eacute;fono:</label>
			<input id="telefono_comprador"  name="telefono_comprador"  value="<?php echo set_value('telefono_comprador',@$r['cotizacion']->telefono_comprador); ?>"/>
			<?php echo form_error('telefono_comprador');?>	
		</td>
		<td>
			<label>Fecha de Nacimiento (DD/MM):</label>
			<input id="fecha_nacimiento_comprador" name="fecha_nacimiento_comprador" value="<?php echo set_value('fecha_nacimiento_comprador',@$r['cotizacion']->fecha_nacimiento_comprador);?>"/>
			<?php echo form_error('fecha_nacimiento_comprador');?>	
		</td>
		<td>
			<label>A&ntilde;o de Nacimiento (AAAA):</label>
			<input id="anio_nacimiento_comprador" name="anio_nacimiento_comprador" value="<?php echo set_value('anio_nacimiento_comprador',@$r['cotizacion']->anio_nacimiento_comprador);?>"/>
			<?php echo form_error('anio_nacimiento_comprador');?>	
		</td>
	</tr>
	<tr class="altrow">
		<td colspan="3">
			<label>Fecha de Aniversario (DD/MM):</label>
			<input id="fecha_aniversario_comprador"  name="fecha_aniversario_comprador"  value="<?php echo set_value('fecha_aniversario_comprador',@$r['cotizacion']->fecha_aniversario_comprador); ?>"/>
			<?php echo form_error('fecha_aniversario_comprador');?>	
		</td>
				<td>&nbsp;</td>
	</tr>
	</tbody>
</table>			
</div>
<!-- FIN DATOS DEL COMPRADOR -->

<!-- DATOS FISCALES -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Datos fiscales</div>
<div>
<table class="table_form">
	<tbody>	
	<tr>
		<td>
			<label><span class="req">*</span>Facturaci&oacute;n para:</label>
			<select name="tipo_persona_id" id="tipo_persona_id" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach(catalogo('tipo_persona_fiscal',FALSE) as $k=>$v):?>
				<option value="<?php echo $k; ?>" <?php echo set_select('tipo_persona_id', $k, ($k == @$r['cotizacion']->tipo_persona_id)); ?> ><?php echo $v; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('tipo_persona_id');?>
		</td>
		<td>
			<label>Copiar datos del comprador:</label>
			<input name="copiar_datos_comprador" value="0" type="hidden" />
			<input type="checkbox" name="copiar_datos_comprador" id="copiar_datos_comprador" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />
		</td>
	</tr>
	</tbody>
</table>
<br/>
<div class="datos">
<fieldset><legend id="etiqueta_principal"></legend>
<table class="table_form">
<tbody>
	<tr class="altrow">
		<td>
			<label id="etiqueta_nombre"></label>
			<input id="razon_social"  name="razon_social"  value="<?php echo set_value('razon_social',@$r['cotizacion']->razon_social); ?>"/>
			<?php echo form_error('razon_social');?>
		</td>
		<td>
			<label id="etiqueta_rfc"></label>
			<input id="rfc"  name="rfc"  value="<?php echo set_value('rfc',@$r['cotizacion']->rfc); ?>"/>
			<?php echo form_error('rfc');?>
		</td>	
	</tr>
	<tr>
		<td>
			<label id="etiqueta_paterno"></label>
			<input id="apellido_paterno"  name="apellido_paterno"  value="<?php echo set_value('apellido_paterno',@$r['cotizacion']->apellido_paterno); ?>"/>
			<?php echo form_error('apellido_paterno');?>
		</td>
		<td>
			<label id="etiqueta_materno"></label>
			<input id="apellido_materno"  name="apellido_materno"  value="<?php echo set_value('apellido_materno',@$r['cotizacion']->apellido_materno); ?>"/>
			<?php echo form_error('apellido_materno');?>
		</td>	 
	</tr>
	</tbody>
</table>
</fieldset><br/>
<fieldset><legend>Direcci&oacute;n</legend>
<table class="table_form">
<tbody>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Estado:</label>
			<select name="estado" id="estado" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($estados as $e):?>
				<option value="<?php echo $e; ?>" <?php echo set_select('estado', $e,($e==@$r['cotizacion']->estado)); ?> ><?php echo $e; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('estado');?>
		</td>
		<td>
			<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
			<select name="municipio" id="municipio" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($municipios as $e):?>
				<option value="<?php echo $e; ?>" <?php echo set_select('municipio', $e,($e==@$r['cotizacion']->municipio)); ?>><?php echo $e; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('municipio');?>
		</td>
	</tr>	
	<tr>
		<td>
			<label><span class="req">*</span>C&oacute;digo postal:</label>
			<input id="codigo_postal"  name="codigo_postal"  value="<?php echo set_value('codigo_postal',@$r['cotizacion']->codigo_postal); ?>"/>
			<a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="dir_search"><img src="<?php echo site_url('img/bc/search3.jpg');?>" alt="Buscar"/></a>
			<?php echo form_error('codigo_postal');?>
		</td>
		<td>
			<label><span class="req">*</span>Colonia:</label>
			<input id="asentamiento"  name="asentamiento"  value="<?php echo set_value('asentamiento',@$r['cotizacion']->asentamiento); ?>"/>
			<a class="search_t icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="cp_search"><img src="<?php echo site_url('img/bc/search3.jpg');?>" alt="Buscar"/></a>
			<?php echo form_error('asentamiento');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Calle:</label>
			<input id="calle"  name="calle"  value="<?php echo set_value('calle',@$r['cotizacion']->calle); ?>"/>
			<?php echo form_error('calle');?>
		</td>
		<td>
			<label><span class="req">*</span>N&uacute;mero exterior:</label>
			<input id="numero_exterior"  name="numero_exterior"  value="<?php echo set_value('numero_exterior',@$r['cotizacion']->numero_exterior); ?>"/>
			<?php echo form_error('numero_exterior');?>
		</td>
	</tr>
	<tr>
		<td>
			<label>N&uacute;mero interior:</label>
			<input id="numero_interior"  name="numero_interior"  value="<?php echo set_value('numero_interior',@$r['cotizacion']->numero_interior); ?>"/>
			<?php echo form_error('numero_interior');?>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr class="altrow">
		<td>
			<label>Correo electr&oacute;nico:</label>
			<input id="email"  name="email"  value="<?php echo set_value('email',@$r['cotizacion']->email); ?>"/>
			<?php echo form_error('email');?>
		</td>
		<td>
			<label><span class="req">*</span>Tel&eacute;fono:</label>
			<input id="telefono"  name="telefono"  value="<?php echo set_value('telefono',@$r['cotizacion']->telefono); ?>"/>
			<?php echo form_error('telefono');?>
		</td>
	</tr>
	</tbody>
</table>
</fieldset><br/>
</div>
</div>

<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Datos Entrega</div>
<div>
<table class="table_form">
<tbody>
	<tr class="altrow" >
		<td>
			<label><span class="req">*</span>Nombre de contacto:</label>
			<input id="nombre_contacto"  name="nombre_contacto"  value="<?php echo set_value('nombre_contacto',@$r['cotizacion']->nombre_contacto); ?>"/>
			<?php echo form_error('nombre_contacto');?>
		</td>
		<td>
			<label>Copiar direccion fiscal:</label>
			<input name="copia_direccion" value="0" type="hidden" />
			<input type="checkbox" name="copia_direccion" id="copia_direccion" value="1" <?php echo set_checkbox('activo','1',(@$r->activo)?TRUE:FALSE); ?> />
			<?php echo form_error('copia_direccion');?>
		</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>Tel&eacute;fono particular:</label>
			<input id="telefono_particular"  name="telefono_particular"  value="<?php echo set_value('telefono_particular',@$r['cotizacion']->telefono_particular); ?>"/>
			<?php echo form_error('telefono_particular');?>
		</td>	
		<td>
			<label><span class="req">*</span>Tel&eacute;fono celular:</label>
			<input id="telefono_celular"  name="telefono_celular"  value="<?php echo set_value('telefono_celular',@$r['cotizacion']->telefono_celular); ?>"/>
			<?php echo form_error('telefono_celular');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Estado:</label>
			<select name="entrega_estado" id="entrega_estado" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($estados as $e):?>
				<option value="<?php echo $e; ?>" <?php echo set_select('entrega_estado', $e,($e==@$r['cotizacion']->entrega_estado)); ?> ><?php echo $e; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('entrega_estado');?>
		</td>
		<td>
			<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
			<select name="entrega_municipio" id="entrega_municipio" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($entrega_municipios as $e):?>
				<option value="<?php echo $e; ?>" <?php echo set_select('entrega_municipio', $e,($e==@$r['cotizacion']->entrega_municipio)); ?>><?php echo $e; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo form_error('entrega_municipio');?>
		</td>
	</tr>	
	<tr >
		<td>
			<label><span class="req">*</span>C&oacute;digo postal:</label>
			<input id="entrega_codigo_postal"  name="entrega_codigo_postal"  value="<?php echo set_value('entrega_codigo_postal',@$r['cotizacion']->entrega_codigo_postal); ?>"/>
			<a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="entrega_dir_search"><img src="<?php echo site_url('img/bc/search3.jpg');?>" alt="Buscar"/></a>
			<?php echo form_error('entrega_codigo_postal');?>
		</td>
		<td>
			<label><span class="req">*</span>Colonia:</label>
			<input id="entrega_asentamiento"  name="entrega_asentamiento"  value="<?php echo set_value('entrega_asentamiento',@$r['cotizacion']->entrega_asentamiento); ?>"/>
			<a class="search_t icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="entrega_cp_search"><img src="<?php echo site_url('img/bc/search3.jpg');?>" alt="Buscar"/></a>
			<?php echo form_error('entrega_asentamiento');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Calle:</label>
			<input id="entrega_calle"  name="entrega_calle"  value="<?php echo set_value('entrega_calle',@$r['cotizacion']->calle); ?>"/>
			<?php echo form_error('entrega_calle');?>
		</td>
		<td>
			<label><span class="req">*</span>N&uacute;mero exterior:</label>
			<input id="entrega_numero_exterior"  name="entrega_numero_exterior"  value="<?php echo set_value('entrega_numero_exterior',@$r['cotizacion']->numero_exterior); ?>"/>
			<?php echo form_error('entrega_numero_exterior');?>
		</td>
	</tr>
	<tr>
		<td>
			<label>N&uacute;mero interior:</label>
			<input id="entrega_numero_interior"  name="entrega_numero_interior"  value="<?php echo set_value('entrega_numero_interior',@$r['cotizacion']->numero_interior); ?>"/>
			<?php echo form_error('entrega_numero_interior');?>
		</td>
		<td>&nbsp;</td>
	</tr>
	</tbody>
</table>
</div>
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Observaciones</div>
<div>
<table class="table_form">
	<tbody>	
	<tr class="altrow">
		<td align="center">
			<textarea id="observaciones" name="observaciones" class="w90p" rows="6"><?php echo set_value('observaciones',@$r['cotizacion']->observaciones); ?></textarea>
			<?php echo form_error('observaciones');?>
		</td>
	</tr>
	</tbody>
</table>			
</div>
<?php if($status_id==2 || $status_id==3 && $revision):?>
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Documentaci&oacute;n</div>
<div>
	<div><p class="msg info">Seleccione un archivo de imagen para agregarla a destacados.</p></div>
	<div>
		<table class="table_form">
			<tbody>
				<tr class="altrow">
					<td>
						<label>Recibo de pago:</label>
						<input id="recibo_pago" name="recibo_pago" value="<?php echo set_value('recibo_pago'); ?>" type="file"/>
						<span class="ml80"><?php echo form_error('recibo_pago');?></span>
					</td>
					<td>
					<?php if(file_exists(FCPATH."files/cotizaciones/{$cotizaciones_id}/recibo_pago.jpg") ):?>
						<a href=<?php echo site_url('files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.jpg');?>>
							<img src="<?php echo site_url('/thumbs/timthumb.php?src=files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.jpg&zc=0&q=85&w=126&h=86'.'&t='.time());?>" class="img_thumb" />
						</a>
					<?php endif;?>	
					<?php if(file_exists(FCPATH."files/cotizaciones/{$cotizaciones_id}/recibo_pago.pdf")):?>
						<a href=<?php echo site_url('files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.pdf');?>>
							<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
						</a>	
					<?php endif;?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>	
<div><p class="msg info">Seleccione un archivo de imagen para agregarla a destacados.</p></div>
<div>
	<table class="table_form">
		<tbody>
			<tr>
				<td>
					<label>T&eacute;rminos y Condiciones:</label>
					<input id="terminos" name="terminos" value="<?php echo set_value('terminos'); ?>" type="file"/>
					<span class="ml80"><?php echo form_error('terminos');?></span>
				</td>
				<td>
				<?php if(file_exists(FCPATH."files/cotizaciones/{$cotizaciones_id}/terminos.jpg")):?>
					<a href=<?php echo site_url('files/cotizaciones/'.$cotizaciones_id.'/terminos.jpg');?>>
						<img src="<?php echo site_url('/thumbs/timthumb.php?src=files/cotizaciones/'.$cotizaciones_id.'/terminos.jpg&zc=0&q=85&w=126&h=86'.'&t='.time());?>" class="img_thumb" />
					</a>	
				<?php endif;?>
				<?php if(file_exists(FCPATH."files/cotizaciones/{$cotizaciones_id}/terminos.pdf")):?>
					<a href=<?php echo site_url('files/cotizaciones/'.$cotizaciones_id.'/terminos.pdf');?>>
						<img src="<?php echo site_url('/thumbs/timthumb.php?src='.site_url("img/bc/icono_pdf.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" class="img_thumb" />
					</a>	
				<?php endif;?>
				</td>
			</tr>
		</tbody>
	</table>
</div>	
</div>
<?php endif;?>

<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Guardar" id="guardar" /></span>
	<?php if($status_id==3 && $revision):?>
		<a href="<?php echo site_url('cotizaciones/autorizar/'.$cotizaciones_id);?>" class="fl p5 ml5">Autorizar</a>
	<?php endif;?>
	<a href="<?php echo site_url('cotizaciones/index');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>js/bc/direccion.js"></script>
<script type="text/javascript">
<!--
$(function(){
	Direccion.set('');
	Direccion.set('entrega_');

	$('#copia_direccion').click(function(){
		var estado=$('#estado').val();
		var municipio=$('#municipio').val();
		var codigo_postal=$('#codigo_postal').val();
		var asentamiento=$('#asentamiento').val();
		var numero_interior=$('#numero_interior').val();
		var numero_exterior=$('#numero_exterior').val();
		var calle=$('#calle').val();
        $('#entrega_estado').val(estado);
        var entrega_estado=$('#entrega_estado').val();
        Direccion.get_municipios('entrega_',municipio);	
        $('#entrega_codigo_postal').val(codigo_postal);
        $('#entrega_asentamiento').val(asentamiento);
        $('#entrega_calle').val(calle);
        $('#entrega_numero_exterior').val(numero_exterior);
        $('#entrega_numero_interior').val(numero_interior);
		
	});

	$('#fecha_aniversario_comprador').mask('99/99');
	$('#fecha_nacimiento_comprador').mask('99/99');
	$('#anio_nacimiento_comprador').mask('9999');

	$('#tipo_persona_id').change(function(e){
		e.preventDefault();

		if($('#tipo_persona_id').val()=='')
		{
			$('.datos').hide();
		}
		if($('#tipo_persona_id').val()==1)
		{
			$('.datos').show();
			$('#etiqueta_principal').html("Persona Física");
			$('#etiqueta_nombre').html("<span class='req'>*</span>Nombre:");
			$('#etiqueta_rfc').html("RFC:");
			$('#etiqueta_paterno').html("<span class='req'>*</span>Apellido Paterno:");
			$('#etiqueta_materno').html("<span class='req'>*</span>Apellido Materno:");
		
		}
		if($('#tipo_persona_id').val()==2)
		{
			$('.datos').show();
			$('#etiqueta_principal').html("Persona Física con Actividad Empresarial");
			$('#etiqueta_nombre').html("<span class='req'>*</span>Nombre:");
			$('#etiqueta_rfc').html("<span class='req'>*</span>RFC:");
			$('#etiqueta_paterno').html("<span class='req'>*</span>Apellido Paterno:");
			$('#etiqueta_materno').html("<span class='req'>*</span>Apellido Materno:");
		}
		if($('#tipo_persona_id').val()==3)
		{
			$('.datos').show();
			$('#etiqueta_principal').html("Persona Moral");
			$('#etiqueta_nombre').html("<span class='req'>*</span>Nombre o Razón Social:");
			$('#etiqueta_rfc').html("<span class='req'>*</span>RFC:");
			$('#etiqueta_paterno').html("Apellido Paterno:");
			$('#etiqueta_materno').html("Apellido Materno:");
		}
	});
	
	$(document).ready(function(){
		if($('#tipo_persona_id').val()=='')
		$('.datos').hide();
		if($('#tipo_persona_id').val()==1)
		{
			$('.datos').show();
			$('#etiqueta_principal').html("Persona Física");
			$('#etiqueta_nombre').html("<span class='req'>*</span>Nombre:");
			$('#etiqueta_rfc').html("RFC:");
			$('#etiqueta_paterno').html("<span class='req'>*</span>Apellido Paterno:");
			$('#etiqueta_materno').html("<span class='req'>*</span>Apellido Materno:");
		}
		if($('#tipo_persona_id').val()==2)
		{
			$('.datos').show();
			$('#etiqueta_principal').html("Persona Física con Actividad Empresarial");
			$('#etiqueta_nombre').html("<span class='req'>*</span>Nombre:");
			$('#etiqueta_rfc').html("<span class='req'>*</span>RFC:");
			$('#etiqueta_paterno').html("<span class='req'>*</span>Apellido Paterno:");
			$('#etiqueta_materno').html("<span class='req'>*</span>Apellido Materno:");
		}
		if($('#tipo_persona_id').val()==3)
		{
			$('.datos').show();
			$('#etiqueta_principal').html("Persona Moral");
			$('#etiqueta_nombre').html("<span class='req'>*</span>Nombre o Razón Social:");
			$('#etiqueta_rfc').html("<span class='req'>*</span>RFC:");
			$('#etiqueta_paterno').html("Apellido Paterno:");
			$('#etiqueta_materno').html("Apellido Materno:");
		}
	});

	$('#copiar_datos_comprador').click(function(){
		var nombre=$('#nombre_comprador').val();
		var paterno=$('#paterno_comprador').val();
		var materno=$('#materno_comprador').val();
		//var rfc=$('#rfc_comprador').val();
		
        $('#razon_social').val(nombre);
        $('#apellido_paterno').val(paterno);
        $('#apellido_materno').val(materno);
        //$('#rfc').val(rfc);
	});
});

//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } 