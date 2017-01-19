<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class="btn_default_admin btn btn-xs">Agregar</a>
			</div>
		</div>
	</div>
	<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class=" col-lg-12">
		<div class="row">
			<div class="col-lg-3 form-group">
				<label>Categor&iacute;a</label>
				<select class="form-control"  name="categorias_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($categorias as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['categorias_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
				<?php echo form_error('categorias_id');?>
			</div>		
			<div class="col-lg-3 form-group">
				<label>Producto</label>
				<select class="form-control"  name="productos_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($productos as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$c['productos_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
				<?php echo form_error('productos_id');?>
			</div>				
		</div>
	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<input class="btn btn-primary pull-right" type="submit" value="Buscar" id="buscar" />
			<input class="btn btn-default pull-right bc_clear" type="reset" value="Limpiar" />
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
							<p class="pull-right">Se encontraron <?php echo $total; ?> resultados.
						</div>
						
					</td>
				</tr>
			</tfoot>
			<thead>
				<tr>
					<td class="data_first">Id</td>
					<td>Imagen</td>
					<td>Categor&iacute;a</td>
					<td>Producto</td>
					<td>Activo</td>
					<td>Acciones</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td>
						<?php if($this->config->item('cloudfiles')): 
							$orden = $ro->imagen_orden?'_'.$ro->imagen_orden:'';
							$path=$this->cloud_files->url_publica("files/banners/{$ro->id}{$orden}.jpg"); ?>
			            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
			                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla">
			            	</a>
		            	<?php else: ?>
	    					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src=files/banners/'.$ro->id.'.jpg&zc=0&q=85&s=700&t='.time());?>">
								<img src="<?php echo site_url('/thumbs/timthumb.php?src=files/banners/'.$ro->id.'.jpg&s=500&t='.time());?>" class="img-thumbnail front_imgTabla" />
							</a>
	    				<?php endif;?>
					</td>
					<td><?php echo ($ro->categorias_id)?$categorias[$ro->categorias_id]:'SIN CATEGOR&Iacute;A'; ?></td>
					<td><?php echo ($ro->productos_id)?$productos[$ro->productos_id]:'SIN PRODUCTO'; ?></td>
					<td ><?php echo $ro->activo?'Si':'No'; ?></td>
					<td >
						<a href="<?php echo site_url('banners/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
						
						<span class="cg">&nbsp;</span>
						<a href="<?php echo site_url('banners/activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
						
						<span class="cg">&nbsp;</span>
						<a onclick="return confirm('&iquest;Seguro que desea eliminar el registro # <?php echo $ro->id; ?>?');" href="<?php echo site_url('banners/eliminar/'.$ro->id); ?>" class="accion accion3">Eliminar</a>	
					</td>
				</tr>
				<?php $i++; endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>