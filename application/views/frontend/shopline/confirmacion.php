<?php $this->load->view('frontend/layout/header');?>
<div class="col-lg-12 formulario-head">
	<div class="row">
		<div class="col-lg-11 col-sm-10 col-xs-9">
			<h4><?php echo $titulo;?></h4>
		</div>
	</div>
</div>
<div class="col-lg-12">
	<p class="msg info">
		<span class="fa-stack fa-2x">
		  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
		  <i class="fa fa-info fa-stack-1x text-color-info"></i>
		</span>
	 	A continuaci&oacute;n se muestra la información de su carrito ( <b>incluyendo gastos de env&iacute;o</b> ), por favor verifique que est&eacute; correctamente.
	 </p>
</div>

<form action="<?php echo site_url('frontends/confirmacion/'.$cotizaciones_id);?>" id="form" method="post" enctype="multipart/form-data">
    <!-- METODOS DE PAGO -->
    <?php $this->load->view('frontend/shopline/pago_metodos');?>
    <?php if(!empty($promociones)):// && ($this->session->userdata('generar_cotizacion') || @$status_id)):?>
        <?php //if(!@$r['promocion'] && !@$calculo['promocion']):?>
        <div class="panel-group front_panel" id="promociones">
            <div class="panel panel-default" id="productos_div">
                <div id="collapsePromociones" class="panel-collapse collapse in">
                    <a class="btn-front-primary bc_fancybox" href="<?php echo site_url('frontends/promocion_view'); ?>">
                        <img src="<?php echo site_url('img/promociones/promocion_disponible.png')?>" style="width: 100%"/>
                    </a>
                </div>
            </div>
        </div>
    <?php endif;?>
<div class="table-responsive front_table mt20" style="overflow-y: auto; background-color: white">
	<table class="table table-hover cotizacion">
		<thead>
			<tr>
				<td>Producto</td>
				<td>Modelo</td>
				<td>Nombre</td>
				<td>Cantidad</td>
				<td>Precio unitario (SIN IVA)</td>
				<?php if(INTERNO):?>
				    <td>Descuento Cliente</td>
				<?php endif;?>
				<td>Importe</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach($productos as $p):?>
			<tr>
				<td class="text-center">
					<?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$p['id']}/{$p['img_id']}.jpg"):"files/productos/{$p['id']}/{$p['img_id']}.jpg";?>
					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
						<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
					</a>
				</td>
				<td class="text-center"><?php echo $p['modelo'];?></td>

                <?php
                if(!empty($r['cotizacion']['evento_estado']) && $p['unidad_id']==2)
                    $p['nombre'] = $p['nombre'] . ' este servicio se realizará en el estado de ' . $r['cotizacion']['evento_estado'];
                ?>

				<td><?php echo $p['nombre'];?></td>
				<td class="text-center cantidad"><?php echo $p['cantidad'].' '.$p['unidad'];?></td>
				<td class="text-right"><?php echo moneda($p['precio']);?></td>
				<td class="text-right producto" id="prod_<?php echo $p['id'];?>"><?php echo moneda($p['importe_cliente']);?></td>
			</tr>
			<?php if(isset($p['accesorios'])):
			foreach($p['accesorios'] as $acc):?>
			<tr>
				<td class="text-center">
					<?php $orden = $acc['imagen_orden']?'_'.$acc['imagen_orden']:'';?>
					<?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$acc['accesorios_id']}{$orden}.jpg"):"files/productos/{$acc['accesorios_id']}.jpg";?>
					<a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
						<img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
					</a>
				</td>
				<td class="text-center"><?php echo $acc['modelo'];?></td>
				<td><?php echo $acc['nombre'];?></td>
				<td class="text-center cantidad"><?php echo $acc['cantidad'].' '.$acc['unidad'];?></td>
				<td class="text-right"><?php echo moneda($acc['precio']);?></td>
				<td class="text-right accesorio" id="accesorio_<?php echo $acc['accesorios_id'];?>"><?php echo moneda($acc['importe_cliente']);?></td>
			</tr>
			<?php endforeach;
			endif;?>
			<?php endforeach;?>
            <?php if(isset($accesorios_individuales)):
                foreach($accesorios_individuales as $acc):?>
                    <tr>
                        <td class="text-center">
                            <?php $orden = $acc->imagen_orden?'_'.$acc->imagen_orden:'';?>
                            <?php $path_acc=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$acc->accesorios_id}{$orden}.jpg"):"files/productos/{$acc->accesorios_id}.jpg";?>
                            <a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&zc=0&q=85&s=500'.'&t='.time());?>">
                                <img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_acc.'&s=400&t='.time());?>" />
                            </a>
                        </td>
                        <td class="text-center"><?php echo $acc->modelo;?></td>
                        <td><?php echo $acc->nombre;?></td>
                        <td class="text-center cantidad"><?php echo $acc->cantidad.' '.$acc->unidad;?></td>
                        <td class="text-right"><?php echo moneda($acc->precio);?></td>
                        <td class="text-right acc_individual" id="accindividual_<?php echo $acc->accesorios_id;?>"><?php echo moneda($acc->importe_cliente);?></td>
                    </tr>
                <?php endforeach;
            endif;?>
            <?php
            if($promociones):?><input type="hidden" id="promocion" name="promocion" value="<?php echo @$r['promocion']?$r['promocion']:@$promocion;?>"/>
                <?php if($promociones_productos):
                    foreach($promociones_productos as $k => $p):?>
                        <tr>
                            <td class="text-center" style="background-color: #f0f0f0">
                                <?php $path=$p['path'];?>
                                <div style="position: relative;">
                                    <a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
                                        <img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
                                        <?php $path_regalo=site_url('img/promociones/regalo.png');?>
                                        <img style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-8px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
                                    </a>
                                </div>
                            </td>
                            <td class="text-center"><?php echo $p['modelo'];?></td>
                            <td><?php echo $p['nombre'];?></td>
                            <td class="text-center cantidad"><?php echo $p['cantidad'];?></td>
                            <td class="text-right"><?php echo $p['precio']?moneda($p['precio']):moneda(0);?></td>
                            <td class="text-right"><?php echo $p['importe']?moneda($p['importe']):moneda(0);?></td>
                        </tr>
                    <?php endforeach;
                endif;
            endif;?>
            <?php if(!empty($producto_regalo)):?>
                <tr>
                    <td class="text-center" style="background-color: #f0f0f0">
                        <div style="position: relative;">
                            <input type="hidden" id="producto_regalo_id" name="producto_regalo_id" value="<?php echo $producto_regalo->id;?>">
                            <?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$producto_regalo->id}/{$producto_regalo->img_id}.jpg"):"files/productos/{$producto_regalo->id}/{$producto_regalo->img_id}.jpg";?>
                            <a class='imagen_fancybox' href="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&zc=0&q=85&s=500'.'&t='.time());?>">
                                <img class="img-thumbnail front_imgTabla" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&s=400&t='.time());?>" />
                                <?php $path_regalo=site_url('img/promociones/regalo.png');?>
                                <img style="height:50px !important;width:75px !important;position: absolute;z-index: 1;left:0;margin:-8px;top:0;"  src="<?php echo site_url('/thumbs/timthumb.php?src='.$path_regalo.'&s=400&t='.time());?>" />
                            </a>
                        </div>
                    </td>
                    <td class="text-center"><?php echo $producto_regalo->modelo;?></td>
                    <td><?php echo $producto_regalo->nombre;?></td>
                    <td class="text-center cantidad"><?php echo $producto_regalo->cantidad.' '.$producto_regalo->unidad;?></td>
                    <td class="text-right"><?php echo moneda(0);?></td>
                    <td class="text-right"><?php echo moneda(0);?></td>
                </tr>
            <?php endif;?>
		</tbody>
		<tfoot>
			<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">Importe Total:</td>
    			<td id="importe_total" class="front_datoImportante nowrap bc_total text-right"><?php echo moneda(@$r['cotizacion']['importe_total']); ?></td>
    		</tr>
            <tr>
                <td class="tr front_datoImportante nowrap" colspan="5">Descuento:</td>
                <td id="descuento_cliente" class="front_datoImportante nowrap bc_total text-right">
                    <?php $descuento_comercial = @$r['cotizacion']['descuento_cliente']?$r['cotizacion']['descuento_cliente']-@$r['cotizacion']['descuento_cliente_cupon']-@$r['cotizacion']['promocion_opcional_descuento']:0;
                          $descuento_comercial = $descuento_comercial < 0 ? 0: $descuento_comercial;
                          $descuento_comercial = @$r['cotizacion']['descuento_cliente_cupon'] && $descuento_comercial>@$r['cotizacion']['descuento_cliente_cupon']?$descuento_comercial-@$r['cotizacion']['descuento_cliente_cupon']-@$r['cotizacion']['promocion_opcional_descuento']:$descuento_comercial;
                    echo moneda($descuento_comercial);?>
                </td>
            </tr>
            <?php if(@$r['cotizacion']['promocion_porcentaje']):?>
                <tr>
                    <td class="tr front_datoImportante nowrap" colspan="5">Descuento de <b><?php echo @ver_num($r['cotizacion']['promocion_porcentaje']).'%';?></b> por promoci&oacute;n</td>
                    <td class="front_datoImportante nowrap text-right" id="promocion_descuento_porcentaje"><?php echo moneda(@$r['cotizacion']['promocion_porcentaje_monto'])?></td>
                </tr>
            <?php endif;?>
            <?php if(@$r['cotizacion']['promocion_fija']):?>
                <tr>
                    <td class="tr front_datoImportante nowrap" colspan="5">Descuento fijo por promoci&oacute;n</td>
                    <td class="front_datoImportante nowrap text-right" id="promocion_descuento_fijo"><?php echo moneda(@$r['cotizacion']['promocion_fija'])?></td>
                </tr>
            <?php endif;?>
            <?php if(@$r['cotizacion']['promocion_opcional_descuento']):?>
                <tr>
                    <td class="tr front_datoImportante nowrap" colspan="5">Descuento por promoci&oacute;n</td>
                    <td class="front_datoImportante nowrap text-right" id="promocion_opcional_descuento"><?php echo moneda(@$r['cotizacion']['promocion_opcional_descuento'])?></td>
                </tr>
            <?php endif;?>
            <?php if(@$r['cotizacion']['descuento_cupon'] || @$r['cotizacion']['descuento_cliente_cupon']):?>
                <tr>
                    <td class="tr front_datoImportante nowrap" colspan="5">Descuento por Cup&oacute;n:</td>
                    <td class="front_datoImportante nowrap bc_total text-right">
                        <?php echo moneda(@$r['cotizacion']['descuento_cliente_cupon']);?>
                    </td>
                </tr>
            <?php endif;?>
            <?php if(@$r['cotizacion']['envio']):?>
                <tr>
                    <td class="tr front_datoImportante nowrap" colspan="5">Cargo de recuperaci&oacute;n (incluye env&iacute;o e instalaci&oacute;n):</td>
                    <td id="envio" class="front_datoImportante nowrap bc_total text-right"><?php echo moneda(@$r['cotizacion']['envio']); ?></td>
                </tr>
            <?php endif;?>
    		<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">Subtotal:</td>
    			<td id="subtotal_cliente" class="front_datoImportante nowrap bc_total text-right"><?php echo moneda(@$r['cotizacion']['subtotal_cliente']);?></td>
   			</tr>
    		<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">IVA ( 16 % ):</td>
    			<td id="iva_cliente" class="front_datoImportante nowrap text-right"><?php echo moneda(@$r['cotizacion']['iva_cliente']);?></td>
   			</tr>
    		<tr>
    			<td class="tr front_datoImportante nowrap" colspan="5">TOTAL:</td>
    			<td id="total_cliente" class="front_datoImportante nowrap text-right"><?php echo moneda(@$r['cotizacion']['total_cliente']);?></td>
   			</tr>
            <?php if(@$r['cotizacion']['descuento_cupon'] && @$r['cotizacion']['opcion_cupon_id']==2):?>
                <tr>
                    <td class="tr front_datoImportante nowrap" colspan="5">Su pago a 12 meses sin intereses por Cup&oacute;n:</td>
                    <td class="front_datoImportante nowrap text-right"><?php echo moneda(@$r['cotizacion']['mensualidad_cliente_cupon'])?></td>
                </tr>
            <?php endif;?>
            <tr id="rw_pago_mensual" style="display: none;">
                <td class="tr front_datoImportante nowrap msi_msg" colspan="5"></td>
                <td id="pago_mensual" class="front_datoImportante nowrap text-right"></td>
            </tr>
            <tr><td class="tr front_datoImportante_negro nowrap" colspan="6">*Meses sin intereses no aplica con otras promociones.</td></tr>
            <tr><td class="tr front_datoImportante_negro nowrap" colspan="6">*Los precios de la cotizaci&oacute;n pudieran
                    variar durante el proceso de pre-compra, se considerarán los precios finales en el momento de la autorización de la orden de compra.</td></tr>
            <tr><td class="tr front_datoImportante_negro nowrap" colspan="6">*Los precios est&aacute;n sujetos a cambios o modificaciones de acuerdo a la pol&iacute;tica comercial vigente.</td></tr>
            <?php if(@$promociones_alianzas):foreach($promociones_alianzas as $a):?>
                <tr><td class="tr front_datoImportante_negro nowrap" colspan="6">*Regalo adquirido por ALIANZA <?php echo $a['nombre'];?>.
                        Para adquirir su códgio de reclamación debe realizar el pago de su compra.
                    </td></tr>
            <?php endforeach;endif;?>
		</tfoot>
	</table>
</div>
	<div class="row noMargin ">
		<div class="col-lg-3 form-group backgroundGeneric">
			<div class="checkbox">
				<input name="confirmado" value="0" type="hidden" />
				<label>
	    			<input type="checkbox" name="confirmado" value="1" <?php echo set_checkbox('confirmado','1',(@$r->confirmado)?TRUE:FALSE); ?>/>
                    Acepto que la informaci&oacute;n es correcta.
				</label>
				<?php echo form_error('acepta_terminos');?>
			</div>	
		</div>
    	<div class="col-lg-3 form-group backgroundGeneric">
			<div class="checkbox">
				<input name="acepta_terminos" value="0" type="hidden" />
				<label>
	    			<input type="checkbox" name="acepta_terminos" value="1" <?php echo set_checkbox('acepta_terminos','1',(@$r['cotizacion']['acepta_terminos'])?TRUE:FALSE); ?>/>
                    Acepto los t&eacute;rminos y condiciones.<a target="_blank" href=<?php echo site_url('files/terminos/terminos_venta_directa.pdf');?>> ( ver )</a>
				</label>
				<?php echo form_error('acepta_terminos');?>
			</div>	
		</div>
        <div class="col-lg-3 form-group backgroundGeneric">
            <div class="checkbox">
                <input name="acepta_disponibilidad" value="0" type="hidden" />
                <label>
                    <input type="checkbox" name="acepta_disponibilidad" value="1" <?php echo set_checkbox('acepta_disponibilidad','1',(@$r['cotizacion']['acepta_disponibilidad'])?TRUE:FALSE); ?>/>
                    Acepto que la disponibilidad del equipo depender&aacute; del inventario.
                </label>
                <?php echo form_error('acepta_disponibilidad');?>
            </div>
        </div>
        <div class="col-lg-3 front_btnsWrapper">
            <button class="btn btn-front-primary pull-right" type="submit" id="generar_cotizacion">Realizar Pago</button>
            <a class="btn btn-front-default pull-right" href="<?php echo site_url('frontends/cotizacion/'.$cotizaciones_id); ?>">Revisar</a>
        </div>
	</div>
</form>
<script src="/js/alertify/alertify.min.js"></script>
<script type="text/javascript">
    var descuento = <?php echo json_encode($descuento);?>;
    var sin_descuento = <?php echo json_encode($sin_descuento);?>;
    var url_base = '<?php echo site_url('frontends/confirmacion/'.$cotizaciones_id);?>';
$(function(){
    <?php if(empty($metodo_pago_id)):?>
    $('.cantidad').each(function(){
           if($(this).text().indexOf('Hora(s)') > - 1 )
           {
            alertify.set({labels: {ok: 'Cerrar'}});
            alertify.alert('Si desea agregar más horas en su programa de capacitación por favor dé clic en Revisar y proceda a añadirlas en el campo donde se muestra la cantidad de horas.');
           }
       });
    <?php endif;?>

    var radios = $('input:radio[name=metodo_pago_id]');
    <?php if($metodo_pago_id):?>
        $( "#form" ).attr("action",url_base+'/'+<?php echo $metodo_pago_id;?>);
        if(radios.is(':checked') === false)
            radios.filter('[value=<?php echo $metodo_pago_id;?>]').prop('checked', true);
        recalcular();
    <?php endif;?>
    $('a[href*=#]').click(function() {

        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
            && location.hostname == this.hostname)
        {
            var $target = $(this.hash);
            $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');

            if ($target.length)
            {
                var targetOffset = $target.offset().top;
                $('html,body').animate({scrollTop: targetOffset}, 2000);
                return false;
            }
        }
    });

    $('.container').on('click','.pago_metodos',function(){
        $.blockUI({message: '<h1><img src="<?php echo site_url('img/recalculando.gif'); ?>" /></h1>'});
        var pago_metodo_id = $(this).val();
        location.href=BASE_URL+'frontends/confirmacion/<?php echo $cotizaciones_id;?>/'+pago_metodo_id;
    });

    function recalcular(){//$('.container').on('change','.pago_metodos',function(){
        var opcion = $("input[type='radio']:checked").val();
        var resultado;
        switch (opcion) {
            case '1':
            case '2':
                // CON DESCUENTO
                resultado = descuento;
                break;
            case '3':
            case '4':
                // SIN DESCUENTO
                resultado = sin_descuento;
                break;
        }

        if ($(this).is(':checked')) {
            $('.label_radio').each(function () {
                $(this).removeClass('label_radio');
            });

            $(this).parent('div').addClass('label_radio');
        }

        $('.producto').each(function () {
            var id = $(this).attr('id').split('_');
            $(this).html(moneda(resultado.importe_cliente[id[1]]));
        });

        $('.accesorio').each(function () {
            var id = $(this).attr('id').split('_');
            $(this).html(moneda(resultado.importe_cliente_acc[id[1]]));
        });

        $('.acc_individual').each(function () {
            var id = $(this).attr('id').split('_');
            $(this).html(moneda(resultado.importe_cliente_acc_individual[id[1]]));
        });

        resultado.descuento_cliente = resultado.descuento_cliente != null ? resultado.descuento_cliente : 0;
        $('#importe_total').html(moneda(resultado.importe_total));
        $('#descuento_cliente').html(moneda(resultado.descuento_cliente));
        $('#promocion_descuento_porcentaje').html(moneda(resultado.promocion_porcentaje_monto));
        $('#promocion_descuento_fijo').html(moneda(resultado.promocion_fija));
        $('#subtotal_cliente').html(moneda(resultado.subtotal_cliente));
        $('#envio').html(moneda(resultado.envio));
        $('#iva_cliente').html(moneda(resultado.iva_cliente));
        $('#total_cliente').html(moneda(resultado.total_cliente));

        pago_mensual_set();
        $('#ancla').trigger('click');
    }//});

    function pago_mensual_set()
    {
        var opcion = $("input[type='radio']:checked").val();
        var msi = 0;
        var msg='';
        var resultado;
        switch(opcion)
        {
            case '1':
            case '2':
                // CON DESCUENTO
                mostrar = false;
                break;
            case '3':
                // SIN DESCUENTO
                msi=12;
                msg='Su pago a '+msi+' meses sin intereses con American Express.';
                mostrar = true;
                break;
            case '4':
                // SIN DESCUENTO
                msi=18;
                msg='Su pago a '+msi+' meses sin intereses con Banamex.';
                mostrar = true;
                break;
        }
        if(mostrar)
        {
            total = sin_descuento.total_cliente;
            pago_mensual = total/msi;
            $('#pago_mensual').html(moneda(pago_mensual));
            if(msg!='')
                $('#rw_pago_mensual').find('.msi_msg').html(msg);
            $('#rw_pago_mensual').show();
        }
        else
        {
            $('#rw_pago_mensual').hide();
        }
    }
});
</script>

<?php $this->load->view('frontend/layout/footer');?>