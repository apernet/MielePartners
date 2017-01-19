<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url($this->uri->segment(1).'/tipos_accesorios_agregar');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
			</div>
		</div>
	</div>
	<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Nombre</label>
					<input class="form-control" id="nombre" name="nombre" value="<?php echo @$cond['nombre']; ?>" />
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Activo</label>
					<select class="form-control" name="activo" id="activo">
						<option value=""><?php echo $this->config->item('empty_select'); ?></option>
						<option value="1" <?php echo (1 == @$cond['activo'])?'selected="selected"':''; ?>>Activo</option>
						<option value="2" <?php echo (2 == @$cond['activo'])?'selected="selected"':''; ?>>Desactivado</option>
					</select>
				</div>
			</div>
		</div>		
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 barra-btn">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<button  type="submit" class="btn btn-primary pull-right">Buscar</button>
			  		<input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
				</div>
			</div>
		</div>
	</form>
	<?php if($total==0): ?>
		<p class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</p>
	<?php else: ?>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<td class="data_first">Id</td>
						<td>Imagen</td>
						<td>Nombre</td>
						<td>Descipci&oacute;n</td>
						<td>Activo</td>
						<td>Acceiones</td>
					</tr>
				</thead>
				<tfoot>
					<td colspan="11" class="p0">
						<div class="col-lg-4 pull-left">
							<?php echo $paginador; ?>
						</div>
						<div class="col-lg-4 pull-right">
							<p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
						</div>
					</td>
				</tfoot>
				<tbody>
					<?php $i=1; foreach($r as $ro):?>
					<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
						<td><?php echo $ro->id; ?></td>
						<td align="center">
							<?php if($this->config->item('cloudfiles')): 
								$orden = $ro->imagen_orden?'_'.$ro->imagen_orden:'';
								$path=$this->cloud_files->url_publica("files/tipos_accesorios/{$ro->id}{$orden}.jpg"); ?>
				            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
				                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla">
				            	</a>
			            	<?php else: ?>
		    					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src=files/tipos_accesorios/'.$ro->id.'.jpg&zc=0&q=85&s=700&t='.time());?>">
									<img class="img-thumbnail front_imgTabla"  src="<?php echo site_url('/thumbs/timthumb.php?src=files/tipos_accesorios/'.$ro->id.'.jpg&s=500&t='.time());?>" />
								</a>
		    				<?php endif;?>
						</td>
						<td><?php echo $ro->nombre; ?></td>
						<td align="center"><?php echo $ro->descripcion; ?></td>
						<td align="center"><?php echo $ro->activo?'Si':'No'; ?></td>
						<td align="center">
						<?php if($editar):?>
							<a href="<?php echo site_url("accesorios/tipos_accesorios_editar/{$ro->id}"); ?>" class="accion accion1">Editar</a>
						<?php endif;?>
						<?php if($activar):?>
							<a href="<?php echo site_url('accesorios/tipos_accesorios_activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
							<?php endif;?>
						</td>
					</tr>
					<?php $i++; endforeach;?>
				</tbody>
			</table>
		</div>
	<?php endif;?>
</div>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>