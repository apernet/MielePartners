<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<?php if($puede_agregar):?>
					<a role="button" href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
				<?php endif;?>
			</div>
		</div>
	</div>
<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre</label>
				<input id="nombre" name="nombre" class="form-control" value="<?php echo @$cond['nombre']; ?>" />
			</div>
		</div>
	</div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 barra-btn">
		<div class="row">
			<div class="col-lg-12">
				<button type="submit" class="btn btn-primary pull-right">Buscar</button>
		  		<button type="reset" class="btn btn-default pull-right bc_clear" >Limpiar</button>
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
						<td>Acciones</td>
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
						<td>
							<?php if($this->config->item('cloudfiles')): 
								//$orden = $ro->imagen_orden?'_'.$ro->imagen_orden:'';
								$orden = @$r->imagen_orden?'/paquete'.$r->imagen_orden:'';
								$path=$this->cloud_files->url_publica("files/paquetes/{$ro->id}{$orden}.jpg"); ?>
				            	<a href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=700&t='.time());?>" class="imagen_fancybox">
				                	<img src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500&t='.time());?>" class="img-thumbnail front_imgTabla">
				            	</a>
			            	<?php else: ?>
		    					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src=files/paquetes/'.$ro->id.'.jpg&zc=0&q=85&s=700&t='.time());?>">
									<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src=files/paquetes/'.$ro->id.'.jpg&zc=0&q=85&s=500&t='.time());?>" />
								</a>
		    				<?php endif;?>
						</td>
						<td><?php echo $ro->nombre; ?></td>
						<td>
							<?php if($puede_editar):?>
								<a href="<?php echo site_url('paquetes/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
							<?php endif;?>
							<?php if($puede_eliminar):?>
								<a href="<?php echo site_url('paquetes/paquetes_eliminar/'.$ro->id); ?>" class="accion accion2 ">Eliminar</a>
							<?php endif;?>
						</td>
					</tr>
					<?php $i++; endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif;?>
<script type="text/javascript">
$(function(){
});
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>