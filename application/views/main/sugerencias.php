<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<p class="msg info">Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
<form action="<?php echo  site_url('main/sugerencias');?>" id="form" method="post">
<!-- INFORMACION GENERAL -->
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Sugerencia</div>
<div>
<table class="table_form">
	<tbody>
	<tr>
		<td>
			<label><span class="req">*</span>Asunto:</label>
			<input id="titulo" name="titulo" class="input_doble">
			<?php echo  form_error('titulo');?>
		</td>
	</tr>	
	<tr class="altrow">
		<td>
			<label><span class="req">*</span>Sugerencias y comentarios:</label>
			<textarea rows="6" class="w50p" name="sugerencia" id="sugerencia"><?php echo set_value('sugerencia');?> </textarea>
			<?php echo  form_error('sugerencia');?>
		</td>		
	</tr>
	</tbody>
</table>
</div>

<!-- FIN REGISTROS -->

<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Enviar" id="enviar" /></span>
	<a href="<?php echo site_url('main/index');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>