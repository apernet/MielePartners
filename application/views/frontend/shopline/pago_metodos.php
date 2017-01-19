<div class="col-lg-12 backgroundGeneric panelBancos">
    <div class="panel panel-group noBottom">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Elija su forma de pago:
                    </a>
                </h4>
                <?php $puede_meses = @$r['cotizacion']['total_cliente'] > $this->config->item('cuota_minima_meses_sin_intereses');?>
                <?php $puede_meses = !@$r['cotizacion']['descuento_cupon'] && $puede_meses?TRUE:FALSE;?>
                <?php $cuota_minima_amex = @$r['cotizacion']['total_cliente'] > 10?TRUE:FALSE;?>
                <?php if(!$cuota_minima_amex):?>
                    <div class="col-lg-12">
                        <p class="msg info">
						<span class="fa-stack fa-2x">
						  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
						  <i class="fa fa-info fa-stack-1x text-color-info"></i>
						</span>
                            Para realizar su pago con American Express, la compra debe ser mayor a <?php echo moneda(10);?>.
                        </p>
                    </div>
                <?php endif;?>
                <?php if(!$puede_meses):?>
                <div class="col-lg-12">
					<p class="msg info">
						<span class="fa-stack fa-2x">
						  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
						  <i class="fa fa-info fa-stack-1x text-color-info"></i>
						</span>
					 	Para realizar su pago a meses sin intereses con American Express, la compra debe ser mayor a <?php echo moneda($this->config->item('cuota_minima_meses_sin_intereses'));echo $cuota_minima_amex;?>.
					 </p>
				</div>
				<?php endif;?>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <input name="metodo_pago_id" value="0" type="hidden" />
                        <a href="#generar_cotizacion" id="ancla"></a>
                        <?php
                            if(empty($cuota_minima_amex) && empty($puede_meses) && empty($banamex_msi_vigencia))
                                $grid_system = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                            elseif((!empty($cuota_minima_amex) && empty($puede_meses) && empty($banamex_msi_vigencia)) || (empty($cuota_minima_amex) && !empty($puede_meses) && empty($banamex_msi_vigencia)) || (empty($cuota_minima_amex) && empty($puede_meses) && !empty($banamex_msi_vigencia)))
                                $grid_system = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
                            elseif((!empty($cuota_minima_amex) && !empty($puede_meses) && empty($banamex_msi_vigencia)) || (empty($cuota_minima_amex) && !empty($puede_meses) && !empty($banamex_msi_vigencia)) || (!empty($cuota_minima_amex) && empty($puede_meses) && !empty($banamex_msi_vigencia)))
                                $grid_system = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
                            else
                                $grid_system = 'col-lg-3 col-md-3 col-sm-6 col-xs-12';
                        ?>

                        <?php if($cuota_minima_amex):?>
                            <div class="<?php echo $grid_system;?> form-group">
                                <div class="row noMargin form-group text-center">
                                    <label for="american_express" class="imgAmex"></label>
                                    <br><input class="alinear_radio pago_metodos" type="radio" name="metodo_pago_id" value="1" id="american_express" <?php echo (@$r['cotizacion']['metodo_pago_id']=='1')?'checked="checked"':''; ?>  />
                                </div>
                                <?php echo form_error('metodo_pago_id');?>
                            </div>
                        <?php endif;?>

                        <?php if($puede_meses):?>
                            <div class="<?php echo $grid_system;?> form-group">
                                <div class="row noMargin form-group text-center">
                                    <label for="american_express_2" class="imgAmexMeses" style="margin: 8px;"></label>
                                    </br><input class="alinear_radio pago_metodos" type="radio" name="metodo_pago_id" value="3" id="american_express_2" <?php echo (@$r['cotizacion']['metodo_pago_id']=='3')?'checked="checked"':''; ?>  />
                                </div>
                                <?php echo form_error('metodo_pago_id');?>
                            </div>
                        <?php endif;?>

                        <div class="<?php echo $grid_system;?> form-group">
                            <div class="row noMargin form-group text-center">
                                <label for="banamex" class="imgBanamex"></label>
                                <br><input class="alinear_radio pago_metodos" type="radio" name="metodo_pago_id" value="2" id="banamex" <?php echo (@$r['cotizacion']['metodo_pago_id']=='2')?'checked="checked"':''; ?>  />
                            </div>
                            <?php echo form_error('metodo_pago_id');?>
                        </div>

                        <?php if($banamex_msi_vigencia):?>
                        <div class="<?php echo $grid_system;?> form-group">
                            <div class="row noMargin form-group text-center">
                                <label for="banamex_2" class="imgBanamexMeses" style="margin: 8px;"></label>
                                </br><input class="alinear_radio pago_metodos" type="radio" name="metodo_pago_id" value="4" id="banamex_2" <?php echo (@$r['cotizacion']['metodo_pago_id']=='4')?'checked="checked"':''; ?>  />
                            </div>
                            <?php echo form_error('metodo_pago_id');?>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

