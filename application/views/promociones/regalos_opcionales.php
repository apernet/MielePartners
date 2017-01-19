
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
    Por la compra de su <b><?php echo @$producto['nombre'];?></b>, tiene disponibles las siguientes promociones, si alguna es de su agrado, selecciónela y de click en el botón "Agregar" para añadirla a su carrito. </br></br><b>IMPORTANTE: Esta promoción no es acumulable, reemplazará cualquier otra promoción, descuento o meses sin intereses aplicados.</b></p>
</div>
<?php foreach ($regalos as $promo_id=>$k):?>
<div class="col-lg-12" id="regalos_div">
    <div class="panel-group">
        <div class="panel panel-default">
            <div id="regalos" class="panel-collapse collapse in">
                <div class="panel-body">
                    <fieldset class="scheduler-border promocion_regalo">
                        <input name="promocion_id" value="0" type="hidden" />
                        <legend class="scheduler-border legend"><?php echo $k['promocion']['nombre']?>:</legend>
                        <table style="width: 100%;">
                            <tr>
                                <td valign="middle" align="center" style="width: 5%;">
                                    <input class="alinear_radio pago_metodos promocion_radio" type="radio" name="promocion_id" value="<?php echo @$promo_id?>" <?php echo (@$cotizacion['promociones_id']==$promo_id)?'checked="checked"':''; ?>  />
                                </td>
                                <td style="width: 95%;">
                                    <div class="row">
                                        <?php if(@!empty($k['productos'])): foreach($k['productos'] as $producto):?>
                                            <div class="col-lg-4 form-group noMargin">
                                                <div class="thumbnail front-cat-producto">
                                                    <table>
                                                        <tr>
                                                            <td class="regalo">
                                                                <div class="img_regalo">
                                                                    <?php if($this->config->item('cloudfiles')):
                                                                        $path=$this->cloud_files->url_publica("files/productos/{$producto['id']}/{$producto['foto_id']}.jpg"); ?>
                                                                        <img class="img_thumb" src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=200&mh=150&t='.time());?>"/>
                                                                    <?php else: ?>
                                                                        <img class="img_thumb" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$producto['id']}/{$producto['foto_id']}.jpg&mw=200&mh=150&t=".time());?>" />
                                                                    <?php endif;?>
                                                                </div>
                                                                <p class="producto" style="margin: 5px;font-weight: bold;"><?php echo $producto['nombre']; ?></p>
                                                                <p class="modelo" style="margin: 5px;">Modelo: <?php echo $producto['modelo']; ?></p>
                                                                <p class="modelo" style="margin: 5px;text-decoration:line-through;"><?php echo moneda($producto['precio']); ?></p>
                                                                <p class="producto" style="margin: 5px;font-weight: bold;"><?php echo moneda($producto['importe']); ?></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach;endif;?>
                                        <?php if(@!empty($k['accesorios'])): foreach($k['accesorios'] as $accesorio):?>
                                            <div class="col-lg-4 form-group noMargin">
                                                <div class="thumbnail front-cat-producto">
                                                    <table>
                                                        <tr>
                                                            <td class="regalo">
                                                                <div class="img_regalo">
                                                                    <?php $path=$accesorio['path'];//$this->cloud_files->url_publica("files/productos/{$accesorio['id']}/{$accesorio['foto_id']}.jpg"); ?>
                                                                    <img class="img_thumb" src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=200&mh=150&t='.time());?>"/>
                                                                </div>
                                                                <p class="producto" style="margin: 5px;font-weight: bold;"><?php echo $accesorio['nombre']; ?></p>
                                                                <p class="modelo" style="margin: 5px;">Modelo: <?php echo $accesorio['modelo']; ?></p>
                                                                <p class="modelo" style="margin: 5px;text-decoration:line-through;"><?php echo moneda($accesorio['precio']); ?></p>
                                                                <p class="producto" style="margin: 5px;font-weight: bold;"><?php echo moneda($accesorio['importe']); ?></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach;endif;?>
                                        <?php if(@!empty($k['consumibles'])): foreach($k['consumibles'] as $consumible):?>
                                            <div class="col-lg-4 form-group noMargin">
                                                <div class="thumbnail front-cat-producto promocion_regalo">
                                                    <table>
                                                        <tr>
                                                            <td class="img-producto">
                                                                <?php if($this->config->item('cloudfiles')):
                                                                    $path=$this->cloud_files->url_publica("files/productos/{$consumible['id']}/{$consumible['foto_id']}.jpg"); ?>
                                                                    <img class="img_thumb" src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=250&t='.time());?>"/>
                                                                <?php else: ?>
                                                                    <img class="img_thumb" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$consumible['id']}/{$consumible['foto_id']}.jpg&mw=250&mh=250&t=".time());?>" />
                                                                <?php endif;?>
                                                                <p class="producto" style="margin: 5px;"><?php echo $consumible['nombre']; ?></p>
                                                                <p class="modelo" style="margin: 5px;">Modelo: <?php echo $consumible['modelo']; ?></p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach;endif;?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div></br>
<?php endforeach;?>
<div class="front_btnsWrapper mt20 mb20">
    <button class="btn btn-front-primary pull-right" type="button" id="agregar_promocion_opcional">Agregar</button>
    <button class="btn btn-front-primary pull-right" type="button" id="cerrar">Cerrar</button>
    <div class="clear"></div>
</div>
<script type="text/javascript">
    $(function(){
        var promocion_id = false;
        $('.promocion_regalo').on('click', function() {

            $('.promocion_regalo').each(function(){
                $(this).find('.promocion_radio').prop('checked', false);
                $(this).removeClass('promo_selected');
                $(this).find('.legend').removeClass('promo_legend');
            });

            var promo_radio = $(this).find('.promocion_radio');
            promocion_id = promo_radio.val();
            promo_radio.prop('checked', true);
            $(this).addClass('promo_selected');
            $(this).find('.legend').addClass('promo_legend');
        });

        $('#agregar_promocion_opcional').on('click',function(){
            if(promocion_id)
            {
                $('#promociones_id').val(promocion_id);
                $.ajax({
                    url: '<?php echo site_url('frontends/promocion_set') ?>',
                    dataType: 'json',
                    data: {promocion_id : promocion_id, promocion_opcional : promocion_id},
                    type: 'post',
                    success: function(data) {
                        if(data)
                        {
                            //$('#descuento_opcional').prop('checked', false);
                            //$('#descuento_paquete').prop('checked', false);
                            $('#promocion_msi_amex').prop('checked', false);
                            $('#promocion_msi_banamex').prop('checked', false);
                            $('#cupon').prop('checked', false);
                            $('#promociones_id').trigger('change');
                            $.fancybox.close();
                        }
                    }
                });
            }
            else
            {
                alert('Por favor, elija alguna de las promociones disponibles.');
            }
        });

        $('#cerrar').on('click', function(e) {
            e.preventDefault();
            $.fancybox.close();
        });
    });
</script>