
<div class="col-lg-12 formulario-head">
    <div class="row">
        <div class="col-lg-11 col-sm-10 col-xs-9">
            <h4><?php echo @$promocion['nombre']?$titulo.' - '.@$promocion['nombre']:$titulo;?></h4>
        </div>
    </div>
</div>
<?php if(@empty($promocion) && !@$view):?>
<div class="col-lg-12">
    <p class="msg info">
    <span class="fa-stack fa-2x">
        <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
        <i class="fa fa-info fa-stack-1x text-color-info"></i>
    </span>
    ¡Felicidades! Usted ha adquirido los siguientes regalos por promoción MIELE:</p>
</div>
<?php elseif(@$view): ?>
    <div class="col-lg-12">
        <p class="msg info">
    <span class="fa-stack fa-2x">
        <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
        <i class="fa fa-info fa-stack-1x text-color-info"></i>
    </span>
            <?php echo $view;?>:</p>
    </div>
<?php endif; ?>

<div class="col-lg-12" id="regalos_div">
    <?php if(@!empty($promocion)):?>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#regalos_div" href="#promocion">
                        Especificaciones
                    </a>
                </h4>
            </div>
            <div id="promocion" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="row">
                        <?php $compra="";
                              $promocion_tipo=@!empty($promocion['promociones_categorias_ids'])?1:0;
                              $promocion_tipo=!$promocion_tipo && @!empty($promocion['promociones_productos_ids'])?2:$promocion_tipo;
                              $promocion_tipo=!$promocion_tipo && @!empty($promocion['promociones_accesorios_ids'])?3:$promocion_tipo;
                              $promocion_tipo=!$promocion_tipo && @!empty($promocion['promociones_consumibles_ids'])?4:$promocion_tipo;

                              if($promocion_tipo==1):
                                  $compra="<b>{$promocion['promociones_categorias_ids'][$promocion['id']]}</b> productos de la categoría <b>{$promocion['nombre']}</b>";
                                  $compra.=$promocion['monto_minimo']?' y si su compra es mayor o igual a <b>'.moneda($promocion['monto_minimo']).'</b>':'';
                              elseif($promocion_tipo==2):
                                  $producto = array_keys($promocion['promociones_productos_ids']);
                                  $producto = $this->base->read('productos',$producto[0],TRUE);
                                  $compra="<b>{$promocion['promociones_productos_ids'][$producto['id']]} {$producto['nombre']}</b> modelo <b>{$producto['modelo']}</b>";
                                  $compra.=$promocion['monto_minimo']?' y si su compra es mayor o igual a <b>'.moneda($promocion['monto_minimo']).'</b>':'';
                              elseif($promocion_tipo==3):
                                  $accesorio = array_keys($promocion['promociones_accesorios_ids']);
                                  $accesorio = $this->base->read('accesorios',$accesorio[0],TRUE);
                                  $compra="<b>{$promocion['promociones_accesorios_ids'][$accesorio['id']]} {$accesorio['nombre']}</b> modelo <b>{$accesorio['modelo']}</b>";
                                  $compra.=$promocion['monto_minimo']?' y si su compra es mayor o igual a <b>'.moneda($promocion['monto_minimo']).'</b>':'';
                              elseif($promocion_tipo==4):
                                  $consumible = array_keys($promocion['promociones_consumibles_ids']);
                                  $consumible = $this->base->read('accesorios',$consumible[0],TRUE);
                                  $compra="<b>{$promocion['promociones_consumibles_ids'][$consumible['id']]} {$consumible['nombre']}</b> modelo <b>{$consumible['modelo']}</b>";
                                  $compra.=$promocion['monto_minimo']?' y si su compra es mayor o igual a <b>'.moneda($promocion['monto_minimo']).'</b>':'';
                              endif;
                        ?>
                        <p style="margin:15px; font-size: xx-large;">En la compra de <?php echo $compra;?> podrá adquirir los siguientes regalos:</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>
    <div class="panel-group">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#regalos_div" href="#regalos">
                        Regalos
                    </a>
                </h4>
            </div>
            <div id="regalos" class="panel-collapse collapse in">
                <div class="panel-body">
                    <?php foreach($regalos as $promo_id=>$reg)://debug($reg);?>
                    <fieldset class="scheduler-border promociones">
                        <legend class="scheduler-border"><?php echo $reg['promocion']['nombre'];?>:</legend>
                        <div class="row">
                        <?php if(isset($reg['productos']) && !empty($reg['productos'])):?>
                        <?php foreach ($reg['productos'] as $p):?>
                            <div class="col-lg-6 form-group noMargin">
                                <div class="thumbnail front-cat-producto">
                                <table>
                                    <tr>
                                        <td class="img-producto">
                                            <?php if($this->config->item('cloudfiles')):
                                                $path=$this->cloud_files->url_publica("files/productos/{$p['id']}/{$p['foto_id']}.jpg"); ?>
                                                <img class="img_thumb" src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=200&mh=150&t='.time());?>"/>
                                            <?php else: ?>
                                                <img class="img_thumb" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$p['id']}/{$p['foto_id']}.jpg&mw=20&mh=150&t=".time());?>" />
                                            <?php endif;?>
                                        </td>
                                        <td class="info-producto">
                                            <p class="producto" style="margin: 5px;"><?php echo $p['nombre']; ?></p>
                                            <p class="modelo" style="margin: 5px;">Modelo: <?php echo $p['modelo']; ?></p>
                                            <?php if(@$p['porcentaje_descuento']<100):?>
                                                <p class="modelo" style="margin: 5px;text-decoration:line-through;"><?php echo moneda($p['precio']); ?></p>
                                                <p class="producto" style="margin: 5px;font-weight: bold;"><?php echo moneda($p['importe']); ?></p>
                                            <?php else:?>
                                                <p class="producto" style="margin: 5px;"><?php echo moneda($p['precio']);?></p>
                                            <?php endif;?>
                                            <p><?php echo $p['id'];?></p>
                                            <div style="margin: 5px;" id="<?php echo $p['id']?>" class="rate_widget" data-table="productos">
                                                <div class="ratings_stars" data-value="1"></div>
                                                <div class="ratings_stars" data-value="2"></div>
                                                <div class="ratings_stars" data-value="3"></div>
                                                <div class="ratings_stars" data-value="4"></div>
                                                <div class="ratings_stars" data-value="5"></div>
                                                &nbsp;
                                                <a href="<?php echo site_url("/calificaciones/comentarios/{$p['id']}/productos");?>" class="bc_comentarios">
                                                    <span style="float: right;" class="<?php echo $p['id'];?>"></span>
                                                </a>
                                            </div>&nbsp;
                                            <p class="modelo" style="margin: 5px;">
                                                <?php echo $p['descripcion'];?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                            </div>
                        <?php endforeach;?>
                        <?php endif;?>
                        <?php if(isset($reg['accesorios']) && !empty($reg['accesorios'])):?>
                            <?php foreach ($reg['accesorios'] as $p):?>
                                <div class="col-lg-6 form-group noMargin">
                                    <div class="thumbnail front-cat-producto">
                                    <table>
                                        <tr>
                                            <td class="img-producto">
                                                <?php $orden = $p['imagen_orden']?'_'.$p['imagen_orden']:'';?>
                                                <?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$p['id']}{$orden}.jpg"):"files/accesorios/{$p['id']}.jpg";?>
                                                <img class="img img_thumb" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=200&mh=150&t='.time());?>"/>
                                            </td>
                                            <td class="info-producto">
                                                <p class="producto" style="margin: 5px;"><?php echo $p['nombre']; ?></p>
                                                <p class="modelo" style="margin: 5px;">Modelo: <?php echo $p['modelo']; ?></p>
                                                <?php if(@$p['porcentaje_descuento']<100):?>
                                                    <p class="modelo" style="margin: 5px;text-decoration:line-through;"><?php echo moneda($p['precio']); ?></p>
                                                    <p class="producto" style="margin: 5px;font-weight: bold;"><?php echo moneda($p['importe']); ?></p>
                                                <?php else:?>
                                                    <p class="producto" style="margin: 5px;"><?php echo moneda($p['precio']);?></p>
                                                <?php endif;?>
                                                <div style="margin: 5px;" id="<?php echo $p['id']?>" class="rate_widget" data-table="productos">
                                                    <div class="ratings_stars" data-value="1"></div>
                                                    <div class="ratings_stars" data-value="2"></div>
                                                    <div class="ratings_stars" data-value="3"></div>
                                                    <div class="ratings_stars" data-value="4"></div>
                                                    <div class="ratings_stars" data-value="5"></div>
                                                    &nbsp;
                                                    <a href="<?php echo site_url("/calificaciones/comentarios/{$p['id']}/productos");?>" class="bc_comentarios">
                                                        <span style="float: right;" class="<?php echo $p['id'];?>"></span>
                                                    </a>
                                                </div>&nbsp;
                                                <p class="modelo" style="margin: 5px;">
                                                    <?php echo $p['descripcion'];?>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                        <?php if(isset($reg['consumibles']) && !empty($reg['consumibles'])):?>
                            <?php foreach ($reg['consumibles'] as $p):?>
                                <div class="col-lg-6 form-group noMargin">
                                    <div class="front-cat-producto thumbnail">
                                    <table>
                                        <tr>
                                            <td class="img-producto">
                                                <?php $orden = $p['imagen_orden']?'_'.$p['imagen_orden']:'';?>
                                                <?php $path=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$p['id']}{$orden}.jpg"):"files/accesorios/{$p['id']}.jpg";?>
                                                <img class="img img_thumb" src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=200&t='.time());?>"/>
                                            </td>
                                            <td class="info-producto">
                                                <p class="producto" style="margin: 5px;"><?php echo $p['nombre']; ?></p>
                                                <p class="modelo" style="margin: 5px;">Modelo: <?php echo $p['modelo']; ?></p>
                                                <p class="producto" style="margin: 5px;"><?php echo moneda($p['precio']);?></p>
                                                <div style="margin: 5px;" id="<?php echo $p['id']?>" class="rate_widget" data-table="productos">
                                                    <div class="ratings_stars" data-value="1"></div>
                                                    <div class="ratings_stars" data-value="2"></div>
                                                    <div class="ratings_stars" data-value="3"></div>
                                                    <div class="ratings_stars" data-value="4"></div>
                                                    <div class="ratings_stars" data-value="5"></div>
                                                    &nbsp;
                                                    <a href="<?php echo site_url("/calificaciones/comentarios/{$p['id']}/productos");?>" class="bc_comentarios">
                                                        <span style="float: right;" class="<?php echo $p['id'];?>"></span>
                                                    </a>
                                                </div>&nbsp;
                                                <p class="modelo" style="margin: 5px;">
                                                    <?php echo $p['descripcion'];?>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                        <?php if((isset($reg['promocion']['meses_sin_intereses']) && !empty($reg['promocion']['meses_sin_intereses'])) ||
                             (isset($reg['promocion']['porcentaje_descuento']) && !empty($reg['promocion']['porcentaje_descuento'])) ||
                             isset($reg['promocion']['monto_descuento']) && !empty($reg['promocion']['monto_descuento']) ||
                            isset($reg['alianzas']) && !empty($reg['alianzas'])):?>
                        <div class="row" style="height: auto;">
                            <?php if(isset($reg['promocion']['meses_sin_intereses']) && !empty($reg['promocion']['meses_sin_intereses'])):?>
                            <div class="col-lg-6 form-group noMargin">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Meses sin intereses:</legend>
                                    <table class="bg-blanco">
                                        <tr>
                                            <td class="img-producto" align="center" style="height: auto !important; width: 250px;">
                                                <p class="producto" style="margin: 5px;font-size: 4em;"><?php echo $reg['promocion']['meses_sin_intereses']; ?></p>
                                            </td>
                                            <td class="info-producto">
                                                <?php $path=site_url('img/promociones/meses_sin_intereses.png');?>
                                                <img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=200&t='.time());?>" class="img_thumb" />
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <?php endif;?>
                            <?php if(isset($reg['promocion']['porcentaje_descuento']) && !empty($reg['promocion']['porcentaje_descuento'])):?>
                            <div class="col-lg-6 form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Descuento en Porcentaje:</legend>
                                    <table>
                                        <tr>
                                            <td class="img-producto" align="center" style="height: auto !important; max-height: 300px !important; width: 250px;">
                                                <p class="producto" style="margin: 5px;font-size: 4em;"><?php echo $reg['promocion']['porcentaje_descuento']; ?></p>
                                            </td>
                                            <td class="info-producto">
                                                <?php $path=site_url('img/promociones/descuento_porcentaje.png');?>
                                                <img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=200&t='.time());?>" class="img_thumb" />
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <?php endif;?>
                            <?php if(isset($reg['promocion']['monto_descuento']) && !empty($reg['promocion']['monto_descuento'])):?>
                            <div class="col-lg-6 form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Descuento Fijo:</legend>
                                    <table>
                                        <tr>
                                            <td class="img-producto" align="center" style="height: auto !important; max-height: 300px !important; width: 250px;">
                                                <p class="producto" style="margin: 5px;font-size: 3.5em;"><?php echo moneda($reg['promocion']['monto_descuento']); ?></p>
                                            </td>
                                            <td class="info-producto">
                                                <?php $path=site_url('img/promociones/descuento_monto.png');?>
                                                <img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=200&t='.time());?>" class="img_thumb" />
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <?php endif;?>
                            <?php if(isset($reg['alianzas']) && !empty($reg['alianzas'])):?>
                            <?php foreach ($reg['alianzas'] as $p):?>
                            <div class="col-lg-6 form-group">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Alianza <?php echo $p['nombre']; ?>:</legend>
                                    <table>
                                        <tr>
                                            <td class="img-producto" style="height: auto !important; max-height: 300px !important; width: 250px;">
                                                <p class="producto" style="margin: 5px; font-size: 1.5em;"><?php echo $p['descripcion']; ?></p>
                                            </td>
                                            <td class="info-producto">
                                                <?php $path=site_url('img/promociones/alianza.png');?>
                                                <img src="<?php echo site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=200&t='.time());?>" class="img_thumb" />
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </div>
                            <?php endforeach;?>
                            <?php endif;?>
                        </div>
                        <?php endif;?>
                        </div>
                    </fieldset>
                    <?php endforeach;?>
                    <div class="front_btnsWrapper mt20 mb20">
                        <?php //if(@empty($promocion)):?>
                        <!--<button class="btn btn-front-primary pull-right" type="button" id="aceptar_promocion">Aceptar promoci&oacute;n</button>-->
                        <?php //else:?>
                        <button class="btn btn-front-primary pull-right" type="button" id="cerrar">Cerrar</button>
                        <?php //endif;?>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    ELIMINAR_COMENTARIOS='<?php echo tiene_permiso('ocultar_comentarios')?1:0; ?>';
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
$(function(){
    $('#aceptar_promocion').on('click', function(e) {
        e.preventDefault();
        $('#promocion').val(1);
        $.fancybox.close();
        //$('#promociones').hide();
        //$('#promociones_aplicadas').show();
        //$.fancybox.close();
        //$('#promocion').change();
    });

    $('#cerrar').on('click', function(e) {
        e.preventDefault();
        $.fancybox.close();
    });
    //Estrellas de la muerte....

    // trae la calificacion en el ready

    $('.rate_widget').each(function(i) {
        var widget = this;
        var out_data = {
            id : $(widget).attr('id'),
            tabla:$(widget).attr('data-table')
        };

        $.post(
            "<?php echo site_url('calificaciones/get_calificacion'); ?>",
            out_data,
            function(data) {
                $(widget).data( 'fsr', data);
                set_votes(widget);
            },
            'json'
        );
    });

    function set_votes(widget) {

        var avg = $(widget).data('fsr')?$(widget).data('fsr').promedio:null;
        var cmt = $(widget).data('fsr').comentarios?$(widget).data('fsr').comentarios:'0';

        if(avg){
            $(widget).find("[data-value='" + avg + "']").prevAll().andSelf().addClass('ratings_vote');
            $(widget).find("[data-value='" + avg + "']").nextAll().removeClass('ratings_vote');
        }
        $('span.'+$(widget).attr('id')).text(cmt+" comentarios");
    }

    $('.bc_comentarios').fancybox({
        type: 'ajax'
    });
});


</script>