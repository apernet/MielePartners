<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
			<!-- <div class="col-lg-1 col-sm-2 col-xs-3">
				<a role="button" href="<?php echo site_url($this->uri->segment(1).'/agregar');?>" class="btn-default btn btn-xs">Agregar</a>
			</div> -->
		</div>
	</div>
	<form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class=" col-lg-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Folio:</label>
				<input  class="form-control" id="folio" name="folio" value="<?php echo @$cond['folio']; ?>" />
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Distribuidor:</label>
				<select class="form-control" name="distribuidor_id">
					<option value=""></option>
					<?php foreach($distribuidores as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo $k==@$cond['distribuidor_id']?'selected="selected"':''; ?>><?php echo $v; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Nombre del Vendedor:</label>
				<input type="text" class="form-control" id="nombre_vendedor" name="nombre_vendedor" value="<?php echo @$cond['nombre_vendedor']; ?>" />
				<!-- <label>Vendedor:</label>
				<select class="form-control" name="vendedor_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($vendedores as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo $k==@$cond['vendedor_id']?'selected="selected"':''; ?>><?php echo $v; ?></option>
					<?php endforeach; ?>
				</select> -->
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Status:</label>
				<select class="form-control" name="status_id" id="status_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($status as $k=>$v): ?>
						<option value="<?php echo $k; ?>" <?php echo $k==@$cond['status_id']?'selected="selected"':''; ?>><?php echo $v; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Fecha inicial:</label>
				<input type="hidden" id="fecha_inicial_alt" name="fecha_inicial" value="<?php echo @$cond['fecha_inicial']; ?>"/>
				<input type="text" class="form-control fecha" id="fecha_inicial" value="<?php echo get_fecha(@$cond['fecha_inicial']); ?>"/>
				<?php echo form_error('fecha_inicial');?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Fecha final:</label>
				<input type="hidden" id="fecha_final_alt" name="fecha_final" value="<?php echo @$cond['fecha_final']; ?>"/>
				<input type="text" class="form-control fecha" id="fecha_final" value="<?php echo get_fecha(@$cond['fecha_final']); ?>"/>
				<?php echo form_error('fecha_final');?>
			</div>
		</div>
	</div>
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12">
				<input type="hidden" name="exp" id="exp" value="0" />
				<button  type="submit" class="btn btn-primary pull-right" onclick="exp.value='0';">Buscar</button>
				<?php if ($puede_exportar): ?>
					<button type="submit" class="btn btn-primary pull-left" onclick="exp.value='1';">Exportar</button>
				<?php endif; ?>
				<input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
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
					<td colspan="11">
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
					<td>Folio cotizaci&oacute;n</td>
					<td>Distribuidor</td>
					<td>Vendedor</td>
					<td>Porcentaje</td>
					<td>Monto</td>
					<td>IBS</td>
					<td>Paquete</td>
					<td>Fecha de Autorización</td>
					<td>Status</td>
					<td>Acciones</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td><?php echo $ro->folio; ?></td>
					<td><?php echo $ro->distribuidor; ?></td>
					<td><?php echo $ro->vendedor; ?></td>
					<td align="center"><?php echo $ro->porcentaje; ?></td>
					<td align="right"><?php echo $ro->monto; ?></td>
					<td><?php echo $ro->ibs; ?></td>
					<td><?php echo $ro->paquete; ?></td>
					<td><?php echo @$ro->fecha_autorizacion; ?></td>
					<td><?php echo $ro->status; ?></td>
					<td>
						<?php if($puede_pagar && $ro->status_id==1):?>
						<a href="<?php echo site_url('comisiones/cambiar_status/'.$ro->id.'/2'); ?>" class="accion accion1">Marcar Pagada</a>
						<span class="cg">&nbsp;</span>
						<?php endif;?>
						
						<?php if($puede_cancelar && $ro->status_id!=3):?>
						<a href="<?php echo site_url('comisiones/cambiar_status/'.$ro->id.'/3'); ?>" class="accion accion3">Cancelar</a>
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