<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div class="col-lg-12 formulario-head">
	<div class="row">
		<div class="col-lg-11 col-sm-10 col-xs-9">
			<h4><?php echo $titulo;?></h4>
		</div>
		<?php if($agregar):?>
		<div class="col-lg-1 col-sm-2 col-xs-3">
			<a role="button" href="<?php echo site_url('frontends/cotizacion_nueva');?>" class=" btn_default_admin btn btn-xs">Agregar</a>
		</div>
	<?php endif;?>
	</div>
</div>
<form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form" name="form">
	<div class=" col-lg-12">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Folio cotizaci&oacute;n:</label>
				<input type="text" class="form-control" id="folio_cuentas" name="folio_cuentas" value="<?php echo @$cond['folio_cuentas']; ?>" />
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Folio orden de compra:</label>
				<input type="text" class="form-control" id="folio_compra" name="folio_compra" value="<?php echo @$cond['folio_compra']; ?>" />
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
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Status:</label>
				<select class="form-control"  name="status" id="status">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($status as $k=>$v):?>
					<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['status'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Nombre del Vendedor:</label>
				<input type="text" class="form-control" id="nombre_vendedor" name="nombre_vendedor" value="<?php echo @$cond['nombre_vendedor']; ?>" />
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Nombre del Cliente:</label>
				<input type="text" class="form-control" id="nombre_comprador" name="nombre_comprador" value="<?php echo @$cond['nombre_comprador']; ?>" />
		  	</div>
		  	<?php if(!empty($cotizaciones_todas)):?>
			  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				  	<label>Cuenta:</label>
					<select class="form-control" name="cuentas_id" id="cuentas_id">
						<option value=""><?php echo $this->config->item('empty_select'); ?></option>
						<?php foreach($cuentas as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['cuentas_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
						<?php endforeach;?>
					</select>
				</div>
			<?php endif;?>
			<?php if($cotizaciones_externos_filtrar):?>
			  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				  	<label>Status Ventas Externos:</label>
					<select class="form-control" name="status_externo_id" id="status_externo_id">
						<option value=""><?php echo $this->config->item('empty_select'); ?></option>
						<option value="2" <?php echo @$cond["status_externo_id"]==2?'selected':'';?>>PAGO EN PROCESO</option>
						<option value="1" <?php echo @$cond["status_externo_id"]==1?'selected':'';?>>PAGADO</option>
					</select>
				</div>
			<?php endif;?>
			<?php if($cotizaciones_agregar_ibs):?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
		    	<label>Orden de Venta IBS:</label>
				<input type="text" class="form-control" id="ibs" name="ibs" value="<?php echo @$cond['ibs']; ?>" />
		  	</div>
		  	<?php endif;?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Productos:</label>
				<select class="form-control" name="productos_id" id="productos_id">
					<option value=""><?php echo $this->config->item('empty_select'); ?></option>
					<?php foreach($productos as $k=>$v):?>
						<option value="<?php echo $k; ?>" <?php echo ($k == @$cond['productos_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
					<?php endforeach;?>
				</select>
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
				<?php if ($puede_reporte): ?>
					<button type="submit" class="btn btn-primary pull-left" onclick="exp.value='2';">OV autorizadas</button>
				<?php endif; ?>
				<input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
			</div>
		</div>
	</div>
</form>
<?php if($total==0): ?>
<div class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
<div class="col-lg-12">
	<div class="table-responsive" style="overflow-y: auto">
		<table class="table table-hover texto_tabla">
			<tfoot>
				<tr>
				<?php $colspan=$ver_referencia?15:14;?>
				<?php $colspan=$ver_referencia?$colspan+1:$colspan+1;?>
					<td colspan="<?php echo $colspan;?>">
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
					<td class="data_first center">Id</td>
					<td class="center">Folio cotizaci&oacute;n</td>
					<td align="center">Folio orden de Compra</td>
					<td align="center">Cuenta</td>
					<td align="center">Cliente</td>
					<td align="center">Fecha cotizaci&oacute;n</td>
					<td align="center">Vendedor</td>
					<?php if($ver_referencia):?>
						<td align="center">Referenciado Por</td>
					<?php endif;?>
					<td align="center" width="9%">Valor</td>
					<td align="center">N&uacute;mero de productos</td>
					<td align="center">Ofreci&oacute; evento</td>
					<td align="center">Status</td>
					<td align="center">Status Pago</td>
					<?php if($cotizaciones_agregar_ibs):?>
					<td align="center">Orden de Venta IBS</td>
					<?php endif;?>
					<td align="center">Acciones</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td><?php echo $ro->id; ?></td>
					<td ><?php echo $ro->folio_cuentas; ?></td>
					<td><?php echo $ro->folio_compra; ?></td>
					<td><?php echo $ro->cuenta; ?></td>
					<td><?php echo $ro->cliente_nombre_completo; ?></td>
					<td><?php echo $ro->fecha; ?></td>
					<td><?php echo $ro->vendedor_nombre_completo;?></td>
					<?php if($ver_referencia):?>
						<td><?php echo $ro->referido;?></td>
					<?php endif;?>
					<td align="center"><?php echo $ro->valor?></td>
					<td align="center"><?php echo $ro->productos_total; ?></td>
					<td align="center"><?php echo $ro->ofrecio_evento; ?></td>
					<td align="center"><?php echo $ro->status; ?></td>
					<td align="center"><?php echo $ro->status_pago;?></td>
					<?php if($cotizaciones_agregar_ibs):?>
						<td align="center"><?php echo $ro->ibs;?></td>
					<?php endif;?>
					<td>
						<?php if($imprimir && $ro->puede_imprimir):?>
							<a href="<?php echo site_url('cotizaciones/imprimir/'.$ro->id); ?>" class="accion accion4">Imprimir</a>
						<?php endif;?>
						<?php if($cotizaciones_enviar_email && !$ro->externo):?>
							<?php if($ro->status_id==1 && @$ro->rol==2)://SI ES COTIZACION Y ES VENDEDOR PUEDE IMPRIMIR Y ENVIAR LA COTIZACIÓN?>
								<a href="<?php echo site_url('cotizaciones/cotizacion_enviar_email/'.$ro->id); ?>" class="accion accion5">Enviar Cotizaci&oacute;n</a>
							<?php elseif(@$ro->rol==1)://SI ES ADMINISTRADOR?>
								<a href="<?php echo site_url('cotizaciones/cotizacion_enviar_email/'.$ro->id); ?>" class="accion accion5">Enviar Cotizaci&oacute;n</a>
							<?php endif;?>
						<?php endif;?>
						<?php if($ro->status_id==3 && $revision):?>
							<a href="<?php echo site_url('frontends/enviar_compra/'.$ro->id); ?>" class="accion accion6">Revisi&oacute;n</a>			
						<?php endif;?>
						<?php if(($ro->status_id==1 || $ro->status_id==6) && $editar && $enviar):?>
							<a href="<?php echo site_url('frontends/cotizacion/'.$ro->id); ?>" class="accion accion1">Editar</a>	
							<a href="<?php echo site_url('frontends/enviar_compra/'.$ro->id); ?>" class="accion accion2">Generar orden Compra</a>
						<?php endif;?>
						<?php if($ro->status_id==2 && $revision && !$ro->externo):?>
							<a href="<?php echo site_url('frontends/enviar_compra/'.$ro->id); ?>" class="accion accion3">Enviar Compra</a>			
						<?php endif;?>
						<?php if(($ro->status_id<=4 || $ro->status_id==6 )&& $cancelar):?>
							<a href="<?php echo site_url('cotizaciones/cancelar/'.$ro->id); ?>" class="accion accion3">Cancelar</a>			
						<?php endif;?>
                        <?php if( $ro->cotizaciones_validar_pago && $ro->status_id!=5 ):?>
                            <a href="<?php echo site_url('cotizaciones/validar_pago/'.$ro->id); ?>" class="accion accion2">Validar pago</a>
                        <?php endif;?>
                        <?php if($cotizaciones_agregar_ibs && $ro->status_id==4):?>
                            <a href="<?php echo site_url('cotizaciones/ibs_agregar/'.$ro->id); ?>" class="accion accion5 bc_colorbox">IBS</a>
                        <?php endif;?>
						<?php if($descargar_archivos  && $ro->status_id==4):?>
							<a href="<?php echo site_url('cotizaciones/descargar_archivos/'.$ro->id); ?>" class="accion accion2">Archivos</a>
						<?php endif;?>
					</td>
				</tr>
				<?php $i++; endforeach;?>
			</tbody>
		</table>
	</div>
<?php endif;?>
</div>
<script type="text/javascript">
	<!--
	$(function(){
		convertir_campos();
	});
	//-->
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>