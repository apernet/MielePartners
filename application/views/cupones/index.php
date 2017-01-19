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
				<label>Alianza:</label>
				<select name="alianzas_id" id="alianzas_id" class="form-control">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($alianzas as $k=>$v):?>
					<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['alianzas_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
        		<label>V&aacute;lido desde:</label>
        		<input  id="vigencia_desde_alt" name="vigencia_desde" value="<?php echo set_value('vigencia_desde',@$cond['vigencia_desde']); ?>" type="hidden" />
				<input id="vigencia_desde" class="form-control fecha" type="text" value="<?php echo get_fecha(set_value('vigencia_desde',@$cond['vigencia_desde']));?>" />
        	</div>
        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
        		<label>V&aacute;lido hasta:</label>
        		<input  id="vigencia_hasta_alt" name="vigencia_hasta" value="<?php echo set_value('vigencia_hasta',@$cond['vigencia_hasta']); ?>" type="hidden" />
				<input id="vigencia_hasta" class="form-control fecha" type="text" value="<?php echo get_fecha(set_value('vigencia_hasta',@$cond['vigencia_hasta']));?>" />
        	</div>
        </div>
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
        		<label>Porcentaje de descuento:</label>
        		<input type="text" class="form-control" id="porcentaje_descuento" name="porcentaje_descuento" value="<?php echo @$cond['porcentaje_descuento']; ?>" />
        	</div>
        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Vigente:</label>
				<select name="vigente" id="vigente" class="form-control">
        			<option value=""><?php echo $this->config->item('empty_select'); ?></option>
        			<option value="Sí" <?php echo ("Sí" == @$cond['vigente'])?'selected="selected"':''; ?>>Sí</option>
        			<option value="No" <?php echo ("No" == @$cond['vigente'])?'selected="selected"':''; ?>>No</option>
        		</select>
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
						<td>Alianza</td>
						<td>V&aacute;lido desde</td>
						<td>V&aacute;lido hasta</td>
						<td>Vigente</td>
						<td>Porcentaje descuento</td>
						<td>N&uacute;mero de folios</td>
						<td>Meses sin intereses</td>
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
						<td><?php echo elemento('alianzas',$ro->alianza_id); ?></td>
						<td align="center"><?php echo $ro->vigencia_desde; ?></td>
						<td align="center"><?php echo $ro->vigencia_hasta; ?></td>
						<td align="center"><?php echo ($ro->vigente)?'Sí':'No'; ?></td>
						<td align="center"><?php echo $ro->porcentaje_descuento." %"; ?></td>
						<td align="center"><?php echo $ro->numero_folios; ?></td>
						<td align="center"><?php echo $ro->meses_sin_intereses; ?></td>
						<td align="center">
							<a href="<?php echo site_url('cupones/editar/'.$ro->id); ?>" class="accion accion1">Editar</a>
							<a href="<?php echo site_url('cupones/activar/'.$ro->id.'/'.$ro->activo); ?>" class="accion accion2"><?php echo ($ro->activo)?'Desactivar':'Activar'; ?></a>
							<?php if(tiene_permiso('cupones_exportar')):?>
								<a href="<?php echo site_url('cupones/exportar/'.$ro->id); ?>" class="accion accion3"><?php echo 'Exportar'; ?></a>
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