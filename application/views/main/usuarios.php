<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>

	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url('main/usuarios_agregar');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
			</div>
		</div>
	</div>
<form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class=" col-lg-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre:</label>
				<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo @$cond['nombre']; ?>" />
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Usuario:</label>
		    	<input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo @$cond['usuario']; ?>" />
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Grupo:</label>
					<select class="form-control" id="grupos_id" name="grupos_id">
						<option value=""><?php echo $this->config->item('empty_select');?></option>
						<?php foreach($grupos as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['grupos_id'])?'selected="selected"':''; ?>><?php echo $v; ?></option>
						<?php endforeach;?>
					</select>
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Cuenta:</label>
			    	<select class="form-control" id="cuentas_id" name="cuentas_id">
						<option value=""><?php echo $this->config->item('empty_select');?></option>
						<?php foreach($cuentas as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['cuentas_id'])?'selected="selected"':''; ?>><?php echo $v; ?></option>
						<?php endforeach;?>
					</select>
		  	</div>
		</div>
  	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12 ">
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
			<tfoot>
				<tr>
					<td colspan="8">
						<div class="col-lg-4 pull-left">
							<?php echo $paginador; ?>
						</div>
						<div class="col-lg-4 pull-right">
							<p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
						</div>
					</td>
				</tr>
			</tfoot>
			<thead>
				<tr>
					<td class="data_first">Id</td>
					<td>Usuario</td>
					<td>Nombre</td>
					<td>Correo electr&oacute;nico</td>
					<td>Cuenta</td>
					<td>Grupo</td>
					<td>Activo</td>
					<td>Acciones</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td><?php echo $ro->usuario; ?></td>
					<td><?php echo $ro->nombre.' '.$ro->apellido_paterno.' '.$ro->apellido_materno; ?></td>
					<td><?php echo safe_mailto($ro->email,$ro->email); ?></td>
					<td><?php echo $ro->cuentas_id?$cuentas[$ro->cuentas_id]:'NO DISPONIBLE'; ?></td>
					<td><?php echo $grupos[$ro->grupos_id]; ?></td>
					<td><?php si_no($ro->activo); ?></td>
					<!-- <td><?php si_no($ro->suspendido_pago); ?></td> -->
					<td>
					<a href="<?php echo site_url('main/usuarios_editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
					<a href="<?php echo site_url('main/usuarios_activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
					<a href="<?php echo site_url('main/usuarios_admin_enviar_contrasena/'.$ro->id); ?>" class="accion accion4">Reenviar contrase&ntilde;a</a>
					</td>
				</tr>
				<?php $i++; endforeach;?>	
			</tbody>
		</table>
	</div>
</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>