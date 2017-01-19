<div class="col-lg-12 formulario-head">
    <div class="row">
        <div class="col-lg-11 col-sm-10 col-xs-9">
            <h4><?php echo $titulo;?></h4>
        </div>
    </div>
</div>

<div class="table-responsive front_table mt20">
    <table class="table table-hover cotizacion" cellspacing="0">
        <thead>
        <tr>
            <td align="center" style="width: 6%;border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Modelo</b></td>
            <td align="center" style="width: 10%;border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Nombre</b></td>
            <td align="center" style="width: 2%;border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Cantidad</b></td>
            <td align="center" style="width: 10%;border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Precio unitario (Sin IVA)</b></td>
            <td align="center" style="width: 10%;border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Importe</b></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach($productos as $p):?>
            <tr>
                <td style=" border-spacing: 0;"><?php echo $p['modelo'];?></td>

                <?php
                if(!empty($cotizaciones->evento_estado) && $p['unidad_id']==2)
                    $p['nombre'] = $p['nombre'] . ' este servicio se realizará en el estado de ' . $cotizaciones->evento_estado;
                ?>

                <td style=" border-spacing: 0;"><?php echo $p['nombre'];?></td>
                <td align="center" style=" border-spacing: 0;"><?php echo $p['cantidad'].' '.$p['unidad'];?></td>
                <td align="right" style=" border-spacing: 0;"><?php echo moneda($p['precio']);?></td>
                <td align="right" style=" border-spacing: 0;"><?php echo moneda($p['importe_cliente']);?></td>
            </tr>
            <?php if(isset($p['accesorios'])):
                foreach($p['accesorios'] as $acc):?>
                    <tr>
                        <td style=" border-spacing: 0;"><?php echo $acc['modelo'];?></td>
                        <td style=" border-spacing: 0;"><?php echo $acc['nombre'];?></td>
                        <td align="center" style=" border-spacing: 0;"><?php echo $acc['cantidad'].' '.$acc['unidad'];?></td>
                        <td align="right" style=" border-spacing: 0;"><?php echo moneda($acc['precio']);?></td>
                        <td align="right" style=" border-spacing: 0;"><?php echo moneda($acc['importe_cliente']);?></td>
                    </tr>
                <?php endforeach;
            endif;?>
        <?php endforeach;?>
        <?php if(isset($accesorios_individuales)):
            foreach($accesorios_individuales as $acc):?>
                <tr>
                    <td style=" border-spacing: 0;"><?php echo $acc->modelo;?></td>
                    <td><?php echo $acc->nombre;?></td>
                    <td align="center" style=" border-spacing: 0;"><?php echo $acc->cantidad.' '.$acc->unidad;?></td>
                    <td align="right" style=" border-spacing: 0;"><?php echo moneda($acc->precio);?></td>
                    <td align="right" style=" border-spacing: 0;"><?php echo moneda($acc->importe_cliente);?></td>
                </tr>
            <?php endforeach;
        endif;?>
        <?php if(isset($regalos)):?>
            <tr>
                <td align="center" style="border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Regalos</b></td>
                <td style="border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;" colspan="4">&nbsp;</td>
            </tr>
            <?php foreach($regalos as $reg):?>
                <tr>
                    <td style=" border-spacing: 0;"><?php echo $reg['modelo'];?></td>
                    <td style=" border-spacing: 0;"><?php echo $reg['nombre'];?></td>
                    <td align="center" style=" border-spacing: 0;"><?php echo $reg['cantidad'];?></td>
                    <td align="right" style=" border-spacing: 0;"><?php echo @$reg['precio']?moneda($reg['precio']):moneda(0);?></td>
                    <td align="right" style=" border-spacing: 0;"><?php echo @$reg['importe_cliente']?moneda($reg['importe_cliente']):moneda(0);?></td>
                </tr>
            <?php endforeach;
        endif;?>
        <?php if(@$cupones_regalos):?>
            <tr>
                <td colspan="2" align="center" style="border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;"><b>Regalos por cup&oacute;n</b></td>
                <td style="border-bottom: 1px solid #E0E0E0; border-top: 1px solid #E0E0E0; border-spacing: 0;" colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td style=" border-spacing: 0;"><?php echo $cupones_regalos->modelo;?></td>
                <td style=" border-spacing: 0;"><?php echo $cupones_regalos->nombre;?></td>
                <td align="center" style=" border-spacing: 0;">1 Pieza(s)</td>
                <td align="right" style=" border-spacing: 0;"><?php echo moneda(0);?></td>
                <td align="right" style=" border-spacing: 0;"><?php echo moneda(0);?></td>
            </tr>
        <?php endif;?>
        </tbody>
        <tfoot>
        <tr>
            <td style="border-top: 1px solid #E0E0E0;" colspan="4" align="right"></br><b>Importe Total:</b></td>
            <td style="border-top: 1px solid #E0E0E0;" id="importe_total" align="right"></br><?php echo moneda(@$r['importe_total']); ?></td>
        </tr>
        <?php if(@$r['descuento_cliente']):?>
            <tr>
                <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>Descuento:</b></td>
                <td id="descuento_cliente" class="front_datoImportante nowrap bc_total" align="right">
                    <?php $descuento_comercial = @$r['descuento_cliente']?$r['descuento_cliente']-@$r['descuento_cliente_cupon']-@$r['promocion_opcional_descuento']:0;
                    $descuento_comercial = $descuento_comercial < 0 ? 0: $descuento_comercial;
                    $descuento_comercial = @$r['descuento_cliente_cupon'] && $descuento_comercial>@$r['descuento_cliente_cupon']?$descuento_comercial-@$r['descuento_cliente_cupon']:$descuento_comercial;
                    echo moneda($descuento_comercial);?>
                </td>
            </tr>
        <?php endif;?>
        <?php if(@$r['promocion_opcional_descuento']):?>
            <tr>
                <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>Descuento por promoci&oacute;n:</b></td>
                <td id="promocion_opcional_descuento" class="front_datoImportante nowrap bc_total" align="right"><?php echo moneda(@$r['promocion_opcional_descuento']); ?></td>
            </tr>
        <?php endif;?>
        <?php if(@$r['descuento_cupon'] || @$r['descuento_cliente_cupon']):?>
            <tr>
                <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>Descuento por Cup&oacute;n:</b></td>
                <td class="front_datoImportante nowrap bc_total" align="right">
                    <?php echo moneda(@$r['descuento_cliente_cupon']);?>
                </td>
            </tr>
        <?php endif;?>
        <?php if(@$r['envio']):?>
            <tr>
                <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>Cargo de recuperaci&oacute;n (incluye env&iacute;o e instalaci&oacute;n):</b></td>
                <td id="envio" class="front_datoImportante nowrap bc_total" align="right"><?php echo moneda(@$r['envio']); ?></td>
            </tr>
        <?php endif;?>
        <tr>
            <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>Subtotal:</b></td>
            <td id="subtotal_cliente" class="front_datoImportante nowrap bc_total" align="right"><?php echo moneda(@$r['subtotal_cliente']);?></td>
        </tr>
        <tr>
            <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>IVA 16 % :</b></td>
            <td id="iva_cliente" class="front_datoImportante nowrap" align="right"><?php echo moneda(@$r['iva_cliente']);?></td>
        </tr>
        <tr>
            <td class="tr front_datoImportante nowrap" colspan="4" align="right"><b>TOTAL:</b></td>
            <td id="total_cliente" class="front_datoImportante nowrap" align="right"><b><?php echo moneda(@$r['total_cliente']);?></b></td>
        </tr>
        <?php if(@$r['descuento_cupon'] && @$r['opcion_cupon_id']==2):?>
            <tr>
                <td align="right" colspan="4"><b>Su pago a 12 meses sin intereses por Cup&oacute;n:</b></td>
                <td align="right"><b><?php echo moneda(@$r['mensualidad_cliente_cupon'])?></b></td>
            </tr>
        <?php endif;?>
        <?php if(isset($mensualidades) && $mensualidades!==false && @$r['promocion_msi']):?>
            <tr id="rw_pago_mensual">
                <td align="right"colspan="4"><b>Su pago a <?php echo $mensualidades['mensualidades'];?> meses sin intereses con <?php echo $mensualidades['mensualidades']==18?'Banamex':'American Express';?>:</b></td>
                <td align="right"><b><?php echo moneda($mensualidades['monto_mensual']);?></b></td>
            </tr>
        <?php endif;?>
        <?php if(@$alianzas):foreach($alianzas as $a):
              $alianza = elemento('promociones_alianzas',$a['alianzas_id']);?>
            <tr>
                <td id="total_cliente" class="front_datoImportante_negro nowrap" colspan="6">*Regalo adquirido por ALIANZA <b><?php echo $alianza;?></b>, Para reclamar su regalo, deberá presentar el siguiente <b>CÓDIGO: <?php echo $a['codigo'];?></b> directamente con el proveedor.<br></td>
            </tr>
        <?php endforeach;endif;?>
        <tr>
            <td id="total_cliente" class="front_datoImportante_negro nowrap" colspan="6"></br>*Para más información de este pedido contacte a <?php echo $this->config->item('nombre_venta_cliente_externo');?>: 01800 MIELE 00</td>
        </tr>
        </tfoot>
    </table>
</div>