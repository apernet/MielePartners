<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<h1><?php echo $titulo?></h1>
<p class="msg info">Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post" enctype="multipart/form-data">
<input name="id" value="<?php echo set_value('id',@$cotizaciones_id);?>" type="hidden" />
<div class="panel panel_up" title="De click para mostrar u ocultar la secci&oacute;n.">Informaci&oacute;n general</div>
<div>
<table class="table_form">
<tbody>
	<tr>
		<td width="50%" align="center">
		<?php  if(@$imagen):?>	
			<?php $file='files/terminos/'.$cotizaciones_id.'.jpg';?>
				<img src="<?php echo site_url('/thumbs/timthumb.php?src='.$file.'&s=80&t='.time());?>" class="img_thumb" />			
		<?php else:?>
			<img src="<?php echo site_url('/scripts/timthumb.php?src='.site_url("img/layout/no_image.jpg").'&s='.$this->config->item('logo_thumb_size'));?>" alt="SIN IMAGEN">
		<?php endif;?>
		</td>
		<td align="left">
			<p class="msg info">Deje el campo de imagen en blanco para conservar la imagen actual o seleccione un archivo para reemplazar la imagen actual con una imagen nueva.</p>
			<br/>
			<label>T&eacute;rminos:</label>
			<input id="imagen" name="imagen" value="<?php echo set_value('imagen'); ?>" type="file"/>
			<span class="ml80"><?php echo  form_error('imagen');?></span>
		</td>
	</tr>
</tbody>
</table>
</div>
<div class="footer_form">		
	<span class="boton fl"><input type="submit" value="Guardar" id="guardar" /></span>
	<a href="<?php echo site_url('cotizaciones/index');?>" class="fl p5 ml5">Cancelar</a>
	<div class="clearblock">&nbsp;</div>
</div>
</form>

<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>