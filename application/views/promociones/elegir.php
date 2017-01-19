<?php if(!is_ajax()){$this->load->view('frontend/layout/header');}?>
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
    ¡Felicidades! Usted puede adquirir alguna de las siguientes promociones otorgadas por MIELE. Por favor, elija la que más sea de su agrado:</p>
</div>

<div class="col-lg-12" id="regalos_div">
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
                    <?php foreach($regalos as $promocion_id=>$r):?>
                    <fieldset class="scheduler-border promocion_regalo">
                        <input name="promocion_id" value="0" type="hidden" />
                        <legend class="scheduler-border legend"><?php echo $r['promocion']['nombre']?>:</legend>
                        <table style="width: 100%;">
                            <tr>
                                <td valign="middle" align="center" style="width: 5%;">
                                    <input class="alinear_radio pago_metodos promocion_radio" type="radio" name="promocion_id" value="<?php echo $promocion_id?>" <?php echo (@$r['cotizacion']['promociones_id']==$promocion_id)?'checked="checked"':''; ?>  />
                                </td>
                                <td style="width: 95%;">
                                <div class="row">
                                    <?php if(@!empty($r['productos'])): foreach($r['productos'] as $producto):?>
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
                                                                <img class="img_thumb" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$producto['id']}/{$producto['foto_id']}.jpg&mw=250&mh=250&t=".time());?>" />
                                                            <?php endif;?>
                                                        </div>
                                                        <p class="producto" style="margin: 5px;"><?php echo $producto['nombre']; ?></p>
                                                        <p class="modelo" style="margin: 5px;">Modelo: <?php echo $producto['modelo']; ?></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php endforeach;endif;?>
                                    <?php if(@!empty($r['accesorios'])): foreach($r['accesorios'] as $accesorio):?>
                                        <div class="col-lg-4 form-group noMargin">
                                            <div class="thumbnail front-cat-producto promocion_regalo">
                                                <table>
                                                    <tr>
                                                        <td class="img_regalo">
                                                            <?php if($this->config->item('cloudfiles')):
                                                                $path=$this->cloud_files->url_publica("files/productos/{$accesorio['id']}/{$accesorio['foto_id']}.jpg"); ?>
                                                                <img class="img_thumb" src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=250&t='.time());?>"/>
                                                            <?php else: ?>
                                                                <img class="img_thumb" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$accesorio['id']}/{$accesorio['foto_id']}.jpg&mw=250&mh=250&t=".time());?>" />
                                                            <?php endif;?>
                                                            <p class="producto" style="margin: 5px;"><?php echo $accesorio['nombre']; ?></p>
                                                            <p class="modelo" style="margin: 5px;">Modelo: <?php echo $accesorio['modelo']; ?></p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach;endif;?>
                                    <?php if(@!empty($r['consumibles'])): foreach($r['consumibles'] as $consumible):?>
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
                                    <?php if(@!empty($r['alianzas'])): foreach($r['alianzas'] as $alianza):?>
                                        <div class="col-lg-4 form-group noMargin">
                                            <div class="thumbnail front-cat-producto promocion_regalo">
                                                <table>
                                                    <tr>
                                                        <td class="img-producto">
                                                            <?php if($this->config->item('cloudfiles')):
                                                                $path=$this->cloud_files->url_publica("files/productos/{$alianza['id']}/{$alianza['foto_id']}.jpg"); ?>
                                                                <img class="img_thumb" src="<?php echo  site_url('/thumbs/timthumb.php?src='.$path.'&mw=250&mh=250&t='.time());?>"/>
                                                            <?php else: ?>
                                                                <img class="img_thumb" src="<?php echo site_url("/thumbs/timthumb.php?src=files/productos/{$alianza['id']}/{$alianza['foto_id']}.jpg&mw=250&mh=250&t=".time());?>" />
                                                            <?php endif;?>
                                                            <p class="producto" style="margin: 5px;"><?php echo $alianza['nombre']; ?></p>
                                                            <p class="modelo" style="margin: 5px;">Modelo: <?php echo $alianza['modelo']; ?></p>
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
                    <?php endforeach;?>
                    <div class="front_btnsWrapper mt20 mb20">
                        <button class="btn btn-front-primary pull-right" type="button" id="elegir">Elegir promoción</button>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        $('#elegir').on('click',function(){
            if(promocion_id)
            {
                <?php if(is_ajax()):?>
                $('#promociones_id').val(promocion_id);
                $.ajax({
                    url: '<?php echo site_url('frontends/promocion_set') ?>',
                    dataType: 'json',
                    data: {promocion_id : promocion_id},
                    type: 'post',
                    success: function(data) {
                        if(data)
                        {
                            $('#promociones_id').trigger('change');
                            $.fancybox.close();
                        }
                    }
                });
                <?php else:?>
                var url =BASE_URL+'frontends/promocion_change/'+promocion_id+'/'+<?php echo $cotizaciones_id?$cotizaciones_id:0;?>;
                location.href=url;
                <?php endif;?>
            }
            else
            {
                alert('Por favor, elija alguna de las promociones disponibles.');
            }
        });
    });
</script>
<?php if(!is_ajax()){$this->load->view('frontend/layout/footer');}?>