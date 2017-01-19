<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url('main/elementos_agregar');?>" class=" btn_default_admin btn btn-xs" title="Crear un nuevo elemento.">Agregar</a>
			</div>
		</div>
	</div>
	<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
		<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
						<label>Valor:</label>
						<input id="valor" name="valor"  class="form-control" value="<?php echo @$cond['valor']; ?>" />
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
						<label>Cat&aacute;logo:</label>
						<select class="form-control" id="catalogos_id" name="catalogos_id">
							<option value=""><?php echo $this->config->item('empty_select');?></option>
							<?php foreach($catalogos as $k=>$v): ?>
							<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['catalogos_id'])?'selected="selected"':''; ?>><?php echo $v; ?></option>
							<?php endforeach;?>
						</select>
					</div>
				</div>
		</div>
		<div class="col-lg-12 barra-btn">
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
			<table class="table table_hover">
			<thead>
				<tr>
					<td>Id</td>
					<td>Clave</td>
					<td>Valor</td>
					<td>Cat&aacute;logo</td>
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
					<td><?php echo $ro->clave; ?></td>
					<td><?php echo $ro->valor; ?></td>
					<td><?php echo $catalogos[$ro->catalogos_id]; ?></td>
					<td><?php si_no($ro->activo); ?></td>
					<td nowrap="nowrap">
						<a href="<?php echo site_url('main/elementos_editar/'.$ro->id); ?>" class="accion accion1" title="Editar datos del registro.">Editar</a>
						<a href="<?php echo site_url('main/elementos_activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
					</td>
				</tr>
				<?php $i++; endforeach;?>	
			</tbody>
			</table>
		</div>
	</div>
	<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>