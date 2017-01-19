<?php if(!is_ajax()){ $this->load->view('frontend/layout/header'); } ?>

	<div class="col-lg-12 formulario-head">
		<div class="row">
			<div class="col-lg-11 col-sm-10 col-xs-9">
				<h4><?php echo $titulo;?></h4>
			</div>
		</div>
	</div>
<form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form">
	<div class=" col-lg-12 backgroundGeneric panelBancos">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Folio Compra:</label>
				<input type="text" class="form-control" id="folio_compra" name="folio_compra" value="<?php echo set_value('folio_compra',@$cond['folio_compra']); ?>" />
		  	</div>
		  	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				<label>Status:</label>
					<select class="form-control" id="status_id" name="status_id">
						<option value=""><?php echo $this->config->item('empty_select');?></option>
						<option value="2" <?php echo @$cond['status_id']==2?'selected':'';?>>PAGO EN PROCESO</option>
						<option value="1" <?php echo @$cond['status_id']==1?'selected':'';?>>PAGADO</option>
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
<div class="row noMargin">
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
					<td class="data_first" align="center">Folio Compra</td>
					<td align="center">Sub-Total</td>
					<td align="center">IVA</td>
					<td align="center">Envio</td>
					<td align="center">Total</td>
					<td align="center">Status</td>
					<td align="center">Acciones</td>
				</tr>
			</thead>
			<tbody>
				<?php $i=1; foreach($r as $ro):?>
				<tr <?php echo ($i%2==0)?'class="altrow"':''?>>
					<td align="center"><?php echo $ro->folio_compra; ?></td>
					<td align="center"><?php echo moneda($ro->subtotal_cliente); ?></td>
					<td align="center"><?php echo moneda($ro->iva_cliente); ?></td>
					<td align="center"><?php echo moneda($ro->envio); ?></td>
					<td align="center"><?php echo moneda($ro->total_cliente); ?></td>
					<td align="center"><?php echo $ro->pago_realizado?'PAGADO':'PAGO EN PROCESO'; ?></td>
					<td align="center">
						<a href="<?php echo site_url('frontends/pedido_detalle/'.$ro->id); ?>" class="accion accion1 bc_fancybox">Ver</a>
                        <?php if($ro->pago_realizado):?>
                            <a href="<?php echo site_url('frontends/comprobante_compra_pdf/'.$ro->id); ?>" class="accion accion2">Imprimir</a>
                        <?php endif;?>
                        <?php if($ro->pago_realizado):?>
                            <a href="<?php echo site_url('frontends/comprobante_compra_mail/'.$ro->id); ?>" class="accion accion4">Enviar</a>
                        <?php endif;?>
					</td>
				</tr>
				<?php $i++; endforeach;?>
			</tbody>
		</table>
	</div>
</div>
<?php endif;?>
<?php if(!is_ajax()){ $this->load->view('frontend/layout/footer'); } ?>