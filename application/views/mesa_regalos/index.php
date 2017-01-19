<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<a href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class="boton_a fr">Agregar</a>
<h1><?php echo $titulo?></h1>
<form action="<?php echo site_url(uri_string());?>" method="POST" id="form">
<table class="data_search">
<tbody>
	<tr>
		<td>
			<label><span class="req">*</span>Nombre</label>
			<input id="nombre" name="nombre" value="<?php echo @$cond['nombre']; ?>"/>
		</td>
		<td>
			<label><span class="req">*</span>Folio</label>
			<input id="folio" name="folio" value="<?php echo @$cond['folio']; ?>"/>
		</td>  
	</tr>
	<tr>
		<td>
			<label><span class="req">*</span>E-mail</label>
			<input id="email" name="email" value="<?php echo @$cond['email']; ?>"/>
		</td>
		<td>
			<label><span class="req">*</span>Contrase&ntilde;a</label>
			<input id="contrasena" name="contrasena" value="<?php echo @$cond['contrasena']; ?>"/>
		</td>  
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<span class="boton fr"><input type="submit" value="Buscar" id="buscar" /></span>
			<span class="boton gris fr mr5"><input type="reset" value="Limpiar" id="clear" class="clear" /></span>
		</td>
	</tr>
</tbody>
</table>
</form>
<?php if($total==0): ?>
<div class="sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<table class="data tr_over">
<thead>
	<tr>
		<td class="data_first">Id</td>
		<td>Folio</td>
		<td>Nombre</td>
		<td>Tel&eacute;fono</td>
		<td>E-mail</td>
		<td>Contrase&ntilde;a</td>
		<td>C&oacute;digo postal</td>
		<td>Activo</td>
		<td>Acciones</td>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="9">
			<div class="total fr">Se encontraron <?php echo $total; ?> resultados.</div>	
			<?php echo $paginador; ?>
		</td>
	</tr>
</tfoot>
<tbody>
	<?php $i=1; foreach($r as $ro):?>
	<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
		<td><?php echo $ro->id; ?></td>
		<td><?php echo $ro->folio; ?></td>
		<td><?php echo $ro->nombre; ?></td>
		<td><?php echo $ro->telefono; ?></td>
		<td><?php echo $ro->email; ?></td>
		<td><?php echo $ro->contrasena;?></td>
		<td><?php echo $ro->codigo_postal;?></td>
		<td align="center"><?php echo $ro->activo?'Si':'No'; ?></td>
		<td align="center">
			<a href="<?php echo site_url('mesa_regalos/editar/'.$ro->id); ?>" class="edit">Editar</a>
			<span class="cg">&nbsp;|&nbsp;</span>
			<a href="<?php echo site_url('mesa_regalos/activar/'.$ro->id.'/'.$ro->activo); ?>" class="view"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
			<span class="cg">&nbsp;|&nbsp;</span>
			<a onclick="return confirm('&iquest;Seguro que desea eliminar el registro # <?php echo $ro->id; ?>?');" href="<?php echo site_url('mesa_regalos/eliminar/'.$ro->id); ?>" class="delete">Eliminar</a>
			<span class="cg">&nbsp;|&nbsp;</span>
			<a class="morado ver_detalles" href="<?php echo site_url('mesa_regalos/ver_detalles/'.$ro->id); ?>">Ver detalles</a>
		</td>
	</tr>
	<?php $i++; endforeach;?>
</tbody>
</table>
<?php endif;?>
<script type="text/javascript">
<!--
$(function(){
	$('.ver_detalles').click(function(e){
		
		e.preventDefault();
		$.fn.colorbox({
			href: this.href,
			width: 850,
			height: 550
		});
	});
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>