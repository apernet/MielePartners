<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url('noticias/agregar');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
			</div>
		</div>
	</div>
	<?php if($total==0): ?>
	<div class="sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
	<?php else: ?>
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-hover">
			<thead>
				<tr>
					<td class="data_first">Id</td>
					<td>T&iacute;tulo</td>
					<td>Fecha</td>
					<td>Mostrar al inicio</td>
					<td>Activo</td>
					<td>Acciones</td>
				</tr>
			</thead>
			<tfoot>	
			<tr>
				<td colspan="6">
				<div class="col-lg-4 pull-left">
					<?php echo $paginador; ?>
				</div>
				<div class="col-lg-4 pull-right">
					<p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
				</div>
				</td>
			</tr>
			</tfoot>
			<tbody>
				<?php $i=1; foreach($r->result_object() as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td><?php echo $ro->titulo; ?></td>
					<td><?php echo $ro->fecha ?></td>
					<td><?php echo $ro->inicio? 'Si':'No';?></td>
					<td><?php echo $ro->activo?'Si':'No'; ?></td>
					<td>
					<a href="<?php echo site_url('noticias/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
					<span class="cg">&nbsp;</span>
					<a onclick="return confirm('&iquest;Seguro que desea eliminar el registro # <?php echo $ro->id; ?>?');" href="<?php echo site_url('noticias/eliminar/'.$ro->id); ?>" class="accion accion2">Eliminar</a>	
					</td>
				</tr>
				<?php $i++; endforeach;?>
			</tbody>
			</table>
		</div>
	</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>