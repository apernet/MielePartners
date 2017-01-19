<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div class="col-lg-12 formulario-head">
	<div class="row">
		<div class="col-lg-11 col-sm-10 col-xs-9">
			<h4><?php echo $titulo;?></h4>
		</div>
		<div class="col-lg-1 col-sm-2 col-xs-3">
			<a role="button" href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
		</div>
	</div>
</div>

<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Id</label>
				<input id="id" name="id" class="form-control" value="<?php echo @$cond['id']; ?>" />
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre:</label>
				<select name="nombre" id="nombre" class="form-control">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($alianzas as $k):?>
						<option value="<?php echo $k->nombre; ?>" <?php echo ($k->nombre == @$cond['nombre'])?'selected="selected"':''; ?>> <?php echo $k->nombre; ?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
        		<label>N&uacute;mero de folios:</label>
				<input name="numero_folios" id="numero_folios" class="form-control" type="text" value="<?php echo @$cond['numero_folios'];?>" />
        	</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Activo:</label>
				<select class="form-control" name="activo" id="activo">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<option value="1" <?php echo (@$cond['activo']==1)?'selected="selected"':'';?>> <?php echo 'Activo';?></option>
					<option value="2" <?php echo (@$cond['activo']==2)?'selected="selected"':'';?>> <?php echo 'Desactivado';?></option>
				</select>
			</div>
        </div>
	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12">
				<button type="submit" class="btn btn-primary pull-right">Buscar</button>
		  		<button type="reset" class="btn btn-default pull-right bc_clear">Limpiar</button>
			</div>
		</div>
	</div>	
</form>

<?php if($total==0): ?>
	<p class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</p>
<?php else: ?>
	<div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<td class="data_first">Id</td>
						<td>Nombre</td>
						<td>N&uacute;mero de folios</td>
						<td>Acciones</td>
					</tr>
				</thead>
				<tfoot>
					<td colspan="10" class="p0">
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
						<td align="left"><?php echo $ro->nombre; ?></td>
						<td align="left"><?php echo $ro->numero_folios; ?></td>
						<td align="left">
							<a href="<?php echo site_url('alianzas_promociones/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
							<a href="<?php echo site_url('alianzas_promociones/activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
							<?php if(tiene_permiso('alianzas_promociones_exportar') && $ro->numero_folios>0):?>
								<a href="<?php echo site_url('alianzas_promociones/exportar/'.$ro->id); ?>" class="accion accion3"><?php echo 'Exportar'; ?></a>
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
<!--
$(function(){
	convertir_campos();
});
//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>