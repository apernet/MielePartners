<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url('main/catalogos_agregar');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
			</div>
		</div>
	</div>
	
	<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Nombre</label>
					<input id="nombre" name="nombre"  class="form-control"value="<?php echo @$cond['nombre']; ?>" />
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
					<label>Descripci&oacute;n</label>
					<input id="descripcion" name="descripcion"  class="form-control"value="<?php echo @$cond['descripcion']; ?>" />
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 barra-btn">
			<div class="row">
				<div class="col-lg-12">
					<button  type="submit" class="btn btn-primary pull-right">Buscar</button>
			  		<input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
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
			<thead>
				<tr>
					<td class="data_first">Id</td>
					<td>Nombre</td>
					<td>Descripci&oacute;n</td>
					<td>Acciones</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4">
					<div class="col-lg-4 pull-left" ><?php echo $paginador; ?></div>
					<p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php $i=1; foreach($r->result_object() as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td><?php echo $ro->nombre; ?></td>
					<td><?php echo $ro->descripcion; ?></td>
					<td>
						<a href="<?php echo site_url('main/catalogos_editar/'.$ro->id); ?>" class="accion accion1" title="Editar datos del registro.">Editar</a>
					</td>
				</tr>
				<?php $i++; endforeach;?>	
			</tbody>
			</table>
		</div>
	</div>
	<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>