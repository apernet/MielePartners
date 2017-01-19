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
	<div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre:</label>
				<input class="form-control"  id="nombre" name="nombre" value="<?php echo @$cond['nombre']; ?>" />
			</div>						
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Modelo:</label>
				<input class="form-control"  id="modelo" name="modelo" value="<?php echo @$cond['modelo']; ?>" />
			</div>	
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>SKU:</label>
				<input class="form-control"  id="item" name="item" value="<?php echo @$cond['item']; ?>" />
			</div>	
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Tipo de accesorio:</label>
				<select class="form-control"  name="tipos_accesorios_id" id="tipos_accesorios_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($tipos_accesorios as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['tipos_accesorios_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php  endforeach;?>
				</select>
			</div>	
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Accesorios / Consumibles:</label>
				<select class="form-control"  name="consumible" id="consumible">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<option value="0" <?php echo (isset($cond['consumible']) && $cond['consumible']==0)?'selected="selected"':''; ?>>Accesorios</option>
					<option value="1" <?php echo (isset($cond['consumible']) && $cond['consumible']==1)?'selected="selected"':''; ?>>Consumibles</option>
				</select>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Activo:</label>
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
				<input class="btn btn-primary pull-right" type="submit" value="Buscar" id="buscar" />
				<input class="btn btn-default pull-right bc_clear" type="reset" value="Limpiar"/>
			</div>
		</div>
	</div>
</form>
<?php if($total==0): ?>
<div class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<div class="col-lg-12">
	<div class="table-responsive">
		<table class="table table-hover">
			<tfoot>
				<tr>
					<td colspan="18">
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
					<td >Imagen</td>
					<td >SKU</td>
					<td >Nombre</td>
					<td >Modelos</td>
					<td >Tipo accesorio</td>
					<td >Precio</td>
					<td >Activo</td>
					<td >Acciones</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td>
						<?php if($this->config->item('cloudfiles')): 
							$orden = $ro->imagen_orden?'_'.$ro->imagen_orden:'';
							$path=$this->cloud_files->url_publica("files/accesorios/{$ro->id}{$orden}.jpg"); ?>
			            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
			                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla">
			            	</a>
		            	<?php else: ?>
	    					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src=files/accesorios/'.$ro->id.'.jpg&zc=0&q=85&s=700&t='.time());?>">
    							<img class="img-thumbnail front_imgTabla"  src="<?php echo site_url('/thumbs/timthumb.php?src=files/accesorios/'.$ro->id.'.jpg&s=500&t='.time());?>" />
    						</a>
	    				<?php endif;?>
					</td>
					<td ><?php echo $ro->item; ?></td>
					<td><?php echo $ro->nombre; ?></td>
					<td><?php echo $ro->modelo; ?></td>
					<td><?php echo ($ro->tipos_accesorios_id)?$tipos_accesorios[$ro->tipos_accesorios_id]:'SIN CATEGORIA'; ?></td>
					<td><?php echo moneda($ro->precio); ?></td>
					<td ><?php echo $ro->activo?'Si':'No'; ?></td>
					<td >
						<?php if($editar):?>
						<a href="<?php echo site_url('accesorios/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
						
						<?php endif;?>
						<?php if($activar):?>
						<a href="<?php echo site_url('accesorios/activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
						
						<?php endif;?>
						<?php if($eliminar):?>
						<a onclick="return confirm('&iquest;Seguro que desea eliminar el registro # <?php echo $ro->id; ?>?');" href="<?php echo site_url('accesorios/eliminar/'.$ro->id); ?>" class="accion accion3">Eliminar</a>	
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