<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<p class="msg info">Los campos requeridos est&aacute;n marcados con <span class="req">*</span>.</p>
<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post">
<input type="hidden" name="id" value="<?php echo set_value('id',@$r->id); ?>" />
<!-- INFORMACION GENERAL -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Informaci&oacute;n general</div>
<div>
<table class="table_form">
	<tbody>
	<tr>
		<td>
			<label><span class="req">*</span>Nombre de la unidad:</label>
			<input id="nombre" name="nombre" value="<?php echo set_value('nombre', @$r->nombre); ?>" />
			<?php echo  form_error('nombre');?>
		</td>
		<td>&nbsp;</td>	
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Tel&eacute;fono(s):</label>
			<input id="telefono" name="telefono" value="<?php echo set_value('telefono', @$r->telefono); ?>" />
			<?php echo  form_error('telefono');?>
		</td>
		<td>
			<label><span class="req">*</span>Correo electr&oacute;nico:</label>
			<input id="email" name="email" value="<?php echo set_value('email', @$r->email); ?>" />
			<?php echo  form_error('email');?>
		</td>
	</tr>
	</tbody>
</table>	
</div>
<!-- FIN INFORMACION GENERAL -->


<!-- DATOS FISCALES -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Datos fiscales</div>
<div>
<table class="table_form">
	<tbody>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Raz&oacute;n social:</label>
			<input id="razon_social" name="razon_social" value="<?php echo set_value('razon_social',@$r->razon_social); ?>" />
			<?php echo  form_error('razon_social');?>
		</td>
		<td>
			<label><span class="req">*</span>RFC:</label>
			<input id="rfc" name="rfc" value="<?php echo set_value('rfc',@$r->rfc); ?>" />
			<?php echo  form_error('rfc');?>
		</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>Estado:</label>
			<select name="estado" id="estado" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($estados as $e):?>
				<option value="<?php echo $e; ?>" <?php echo set_select('estado', $e, ($e == @$r->estado)); ?>><?php echo $e; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo  form_error('estado');?>
		</td>
		<td>
			<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
			<select name="municipio" id="municipio" >
				<option value=""><?php echo $this->config->item('empty_select');?></option>
				<?php foreach($municipios as $e):?>
				<option value="<?php echo $e; ?>" <?php echo set_select('municipio', $e, ($e == @$r->municipio)); ?>><?php echo $e; ?></option>
				<?php endforeach;?>
			</select>
			<?php echo  form_error('municipio');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>C&oacute;digo postal:</label>
			<input id="codigo_postal" name="codigo_postal" value="<?php echo set_value('codigo_postal',@$r->codigo_postal);?>"/>
			<a class="search_t icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="dir_search"><img src="<?php echo site_url('img/sax/search3.jpg');?>" alt="Buscar"/></a>
			<?php echo  form_error('codigo_postal');?>
		</td>
		<td>
			<label><span class="req">*</span>Colonia:</label>
			<input id="asentamiento" name="asentamiento" value="<?php echo set_value('asentamiento',@$r->asentamiento); ?>" />
			<?php echo  form_error('asentamiento');?>
		</td>
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>Calle:</label>
			<input id="calle" name="calle" value="<?php echo set_value('calle',@$r->calle); ?>" />
			<?php echo  form_error('calle');?>	
		</td>
		<td>
			<label><span class="req">*</span>N&uacute;mero exterior:</label>
			<input id="numero_exterior" name="numero_exterior" value="<?php echo set_value('numero_exterior',@$r->numero_exterior); ?>" />
			<?php echo  form_error('numero_exterior');?>
		</td>
	</tr>
	<tr class="altrow">
		<td>
			<label>N&uacute;mero interior:</label>
			<input id="numero_interior" name="numero_interior" value="<?php echo set_value('numero_interior',@$r->numero_interior); ?>" />
			<?php echo  form_error('numero_interior');?>
		</td>
		<td>&nbsp;</td>
	</tr>
</tbody>
</table>
</div>
<!-- FIN DATOS FISCALES -->

<!-- WS INFONAVIT -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Webservice INFONAVIT</div>
<div>
<table class="table_form">
	<tbody>
	<tr>
		<td>
			<label>Usuario:</label>
			<input id="infonavit_usuario" name="infonavit_usuario" value="<?php echo set_value('infonavit_usuario', @$r->infonavit_usuario); ?>" />
			<?php echo  form_error('infonavit_usuario');?>
		</td>
		<td>
			<label>Contrase&ntilde;a:</label>
			<input id="infonavit_contrasena" name="infonavit_contrasena" value="<?php echo set_value('infonavit_contrasena', @$r->infonavit_contrasena); ?>" />
			<?php echo  form_error('infonavit_contrasena');?>
		</td>
	</tr>
	</tbody>
</table>	
</div>
<!-- FIN WS INFONAVIT -->

<!-- WS SHF -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Webservice SHF</div>
<div>
<table class="table_form">
	<tbody>
	<tr class="altrow">
		<td>
			<label>Usuario:</label>
			<input id="shf_usuario" name="shf_usuario" value="<?php echo set_value('shf_usuario', @$r->shf_usuario); ?>" />
			<?php echo  form_error('shf_usuario');?>
		</td>
		<td>
			<label>Contrase&ntilde;a:</label>
			<input id="shf_contrasena" name="shf_contrasena" value="<?php echo set_value('shf_contrasena', @$r->shf_contrasena); ?>" />
			<?php echo  form_error('shf_contrasena');?>
		</td>
	</tr>
	<tr>
		<td>
			<label>Clave unidad:</label>
			<input id="shf_unidad" name="shf_unidad" value="<?php echo set_value('shf_unidad', @$r->shf_unidad); ?>" />
			<?php echo  form_error('shf_unidad');?>
		</td>
		<td>&nbsp;</td>
	</tr>
	</tbody>
</table>	
</div>
<!-- FIN WS SHF -->

<!-- DECLARACIONES Y ADVERTENCIAS -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Declaraciones y advertencias</div>
<div class="panel_content">
<fieldset>
<legend>Declaraciones</legend>
<table class="table_form">
	<tbody>
	<tr>
		<td colspan="4">
			<textarea id="declaraciones" name="declaraciones" class="w100p" rows="6"><?php echo set_value('declaraciones', @$r->declaraciones); ?></textarea>
			<?php echo  form_error('declaraciones');?>
		</td>
	</tr>
	</tbody>
</table>
</fieldset>

<fieldset class="mt5">
<legend>Advertencias</legend>
<table class="table_form">
	<tbody>
	<tr>
		<td colspan="4">
			<textarea id="advertencias" name="advertencias" class="w100p" rows="6"><?php echo set_value('advertencias', @$r->advertencias); ?></textarea>
			<?php echo  form_error('advertencias');?>
		</td>
	</tr>
	</tbody>
</table>
</fieldset>
</div><!-- DECLARACIONES Y ADVERTENCIAS -->


<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Guardar" id="guardar" /></span>
	<a href="<?php echo site_url('main/index');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>
<script type="text/javascript" src="<?php echo base_url(); ?>js/direccion.js"></script>
<script type="text/javascript">
<!--
$(function(){
	Direccion.set();
	
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>