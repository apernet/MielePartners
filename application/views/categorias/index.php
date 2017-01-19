<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class="btn_default_admin btn btn-xs">Agregar</a>
			</div>
		</div>
	</div>
	<form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class=" col-lg-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre:</label>
				<input  class="form-control" id="nombre" name="nombre" value="<?php echo @$cond['nombre']; ?>" />
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Parent:</label>
				<select class="form-control" name="parent_id">
					<option value=""></option>
					<?php foreach($categorias as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo $k==@$cond['parent_id']?'selected="selected"':''; ?>><?php echo $v; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Activo:</label>
				<select class="form-control" name="activo" id="activo">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<option value="1" <?php echo ('1' == @$cond['activo'])?'selected="selected"':''; ?>>Activo</option>
					<option value="2" <?php echo ('2' == @$cond['activo'])?'selected="selected"':''; ?>>Desactivado</option>
				</select>
			</div>
		</div>
	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12">
				<input class="btn btn-primary pull-right" type="submit" value="Buscar" id="buscar" />
				<input class="btn btn-default pull-right bc_clear" type="reset" value="Limpiar"/>
			</div>
			
		</div>
	</div>
</form>

<?php if($total==0): ?>
<div class="sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-hover">
			<tfoot>
				<tr>
					<td colspan="8">
						<div class="col-lg-4 pull-left">
							<?php echo $paginador; ?>
						</div>
						<div class="col-lg-4 pull-right">
							<p class="pull-right">Se encontraron <?php echo $total; ?> resultados.</p>
						</div>
					</td>
				</tr>
			</tfoot>
			<thead>
				<tr>
					<td class="data_first">Id</td>
					<td>Imagen</td>
					<td>Nombre</td>
					<td>Parent</td>
					<td>Activo</td>
					<td>Acciones</td>
				</tr>
			</thead>
			
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td class="tc" style="max-width:150px;">
					
						<?php if($this->config->item('cloudfiles')): 
							$orden = $ro->imagen_orden?'_'.$ro->imagen_orden:'';
							$path=$this->cloud_files->url_publica("files/categorias/{$ro->id}{$orden}.jpg"); ?>
			            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
			                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&s=500&t='.time());?>" class="img-thumbnail front_imgTabla"/>
			            	</a>
		            	<?php else: ?>
	    					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src=files/categorias/'.$ro->id.'.jpg&zc=0&q=85&s=700&t='.time());?>">
								<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src=files/categorias/'.$ro->id.'.jpg&s=500&t='.time());?>"  class="img_thumb"/>
							</a>
	    				<?php endif;?>
					</td>
					<td><?php echo $ro->nombre; ?></td>
					<td><?php echo ($ro->parent_id)?$categorias[$ro->parent_id]:'SIN PARENT'; ?></td>
					<td ><?php echo $ro->activo?'Si':'No'; ?></td>
					<td >
						<?php if($editar):?>
						<a href="<?php echo site_url('categorias/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
						<span class="cg">&nbsp;</span>
						<?php endif;?>
						<?php if($activar):?>
						<a href="<?php echo site_url('categorias/activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
						<?php endif;?>
					</td>
				</tr>
				<?php $i++; endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>