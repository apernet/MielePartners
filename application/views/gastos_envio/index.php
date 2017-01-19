<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div class="col-lg-12 formulario-head">
	<div class="row">
		<div class="col-lg-11 col-sm-10 col-xs-9">
			<h4><?php echo $titulo;?></h4>
		</div>
	</div>
</div>
<form action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12 ">
				<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="guardar" />
			</div>
		</div>
	</div>

	<div class="table-responsive front_table mt20" style="overflow-y: auto">
		<table class="table table-hover cotizacion" style="border-bottom: 2px solid #e6e7ec;">

			<thead>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3">Productos</td>
					<td colspan="3">Accesorios</td>
					<td colspan="3">Consumibles</td>
				</tr>

				<tr>
					<td>Estado</td>

					<td>Porcentaje (%)</td>
					<td>Monto Fijo ($)</td>
					<td># de productos mínimos para cobro de envío</td>

					<td>Porcentaje (%)</td>
					<td>Monto Fijo ($)</td>
					<td># de productos mínimos para cobro de envío</td>

					<td>Porcentaje (%)</td>
					<td>Monto mínimo para aplicar % ($)</td>
					<td>Monto Fijo ($)</td>
				</tr>

			</thead>

			<tbody>
				<?php foreach($gastos_envios as $ro):?>
					<tr>
						<td>
							<?php echo $ro['estado']; ?>
							<input type="hidden" name="gastos_envios[<?php echo $ro['id'];?>][id]" value="<?php echo set_value('id',@$ro['id']); ?>"/>
							<input type="hidden" name="gastos_envios[<?php echo $ro['id'];?>][estado]" value="<?php echo set_value('estado',@$ro['estado']); ?>"/>
						</td>

						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][productos_porcentaje]" name="gastos_envios[<?php echo $ro['id'];?>][productos_porcentaje]" value="<?php echo set_value('productos_porcentaje',@$ro['productos_porcentaje']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][productos_porcentaje]");?>
						</td>
						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][productos_monto_fijo]" name="gastos_envios[<?php echo $ro['id'];?>][productos_monto_fijo]" value="<?php echo set_value('productos_monto_fijo',@$ro['productos_monto_fijo']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][productos_monto_fijo]");?>
						</td>
						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][productos_numero]" name="gastos_envios[<?php echo $ro['id'];?>][productos_numero]" value="<?php echo set_value('productos_numero',@$ro['productos_numero']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][productos_numero]");?>
						</td>

						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][accesorios_porcentaje]" name="gastos_envios[<?php echo $ro['id'];?>][accesorios_porcentaje]" value="<?php echo set_value('accesorios_porcentaje',@$ro['accesorios_porcentaje']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][accesorios_porcentaje]");?>
						</td>
						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][accesorios_monto_fijo]" name="gastos_envios[<?php echo $ro['id'];?>][accesorios_monto_fijo]" value="<?php echo set_value('accesorios_monto_fijo',@$ro['accesorios_monto_fijo']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][accesorios_monto_fijo]");?>
						</td>
						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][accesorios_numero]" name="gastos_envios[<?php echo $ro['id'];?>][accesorios_numero]" value="<?php echo set_value('accesorios_numero',@$ro['accesorios_numero']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][accesorios_numero]");?>
						</td>

						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][consumibles_porcentaje]" name="gastos_envios[<?php echo $ro['id'];?>][consumibles_porcentaje]" value="<?php echo set_value('consumibles_porcentaje',@$ro['consumibles_porcentaje']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][consumibles_porcentaje]");?>
						</td>
						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][consumibles_monto_minimo_porcentaje]" name="gastos_envios[<?php echo $ro['id'];?>][consumibles_monto_minimo_porcentaje]" value="<?php echo set_value('consumibles_monto_minimo_porcentaje',@$ro['consumibles_monto_minimo_porcentaje']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][consumibles_monto_minimo_porcentaje]");?>
						</td>
						<td>
							<input type="text" class="form-control" id="gastos_envios[<?php echo $ro['id'];?>][consumibles_monto_fijo]" name="gastos_envios[<?php echo $ro['id'];?>][consumibles_monto_fijo]" value="<?php echo set_value('consumibles_monto_fijo',@$ro['consumibles_monto_fijo']); ?>"/>
							<?php echo form_error("gastos_envios[{$ro['id']}][consumibles_monto_fijo]");?>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>

		</table>
	</div>

	<div class="col-lg-12 barra-btn">
		<div class="row">
			<div class="col-lg-12 ">
				<input class="btn btn-primary pull-right" type="submit" value="Guardar" id="Guardar" />
			</div>
		</div>
	</div>
</form>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>