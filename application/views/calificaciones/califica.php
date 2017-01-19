<?php if(!$this->input->is_ajax_request()): ?>
<?php $this->load->view('layout/head'); ?>
<div class="row no-margin">
    <div class="col-lg-10 col-lg-offset-1  admin-login">
        <div class="row">
            <div class="col-lg-12 tc admin-login-logo">
                <img  src="<?php echo site_url('img/admin_theme/miele-logo.png');?>" alt="<?php echo $this->config->item('proyecto'); ?>"/>
            </div>
        </div>
        <div class="califica">
<?php endif; ?>
<?php if(!@$concluido && @$existe):?>
            <div class="row">
                <div class="col-lg-12 admin-login-body backgroundGeneric ">
                    <div class="row">
                        <h1>Su compra</h1>
                    </div>
                    <?php foreach($elementos as $p):?>
                        <div class="row calificacion-container">
                            <?php $this->load->view('calificaciones/container', array('p'=>$p));?>
                        </div>
                        &nbsp;
                    <?php endforeach;?>
                    <a style="margin-top: 10px;" class="enviar btn btn-primary pull-right">Enviar</a>
                </div>
            </div>
<?php elseif(@$concluido): ?>
            <div class="row">
                <div class="col-lg-12 admin-login-body backgroundGeneric ">
                    <h1><i class="fa fa-check fa-1x"></i>&nbsp;Gracias por su opinión. Su calificación sirve para mejorar nuestros productos.</h1>
                </div>
            </div>

<?php elseif(@!$existe):?>
            <div class="row">
                <div class="col-lg-12 admin-login-body backgroundGeneric ">
                    <h1><i class="fa fa-times fa-1x"></i>&nbsp;El pedido que esta buscando no existe.</h1>
                </div>
            </div>
<?php endif; ?>

<?php if(!$this->input->is_ajax_request()): ?>
        </div>
    </div>
</div>

<?php if(!@$concluido&& @$existe):?>
<script type="text/javascript">
    $(function(){
        $('.ratings_stars').hover(
            // Handles the mouseover
            function() {
                $(this).prevAll().andSelf().addClass('ratings_over');
                $(this).nextAll().removeClass('ratings_vote');
            },
            // Handles the mouseout
            function() {
                $(this).prevAll().andSelf().removeClass('ratings_over');
                set_votes($(this).parent());
            }
        );

        function set_votes(widget) {

            var avg = $(widget).data('fsr')?$(widget).data('fsr').calificacion:null;
            if(avg){
                $(widget).find("[data-value='" + avg + "']").prevAll().andSelf().addClass('ratings_vote');
                $(widget).find("[data-value='" + avg + "']").nextAll().removeClass('ratings_vote');
            }else{
                return
            }
        }

        $('.ratings_stars').on('click', function() {
            var star = this;
            var widget = $(this).parent();
            var clicked_data = {
                calificacion: $(star).attr('data-value'),
                widget_id: widget.attr('id'),
                tabla:widget.attr('data-table')
            };
            widget.data( 'fsr', clicked_data );
            set_votes(widget);

        });

        $('.enviar').click(function(e){
            e.preventDefault();
            var cotizacionId = "<?php echo $cotizacion_id?>";
            // enviamos a guardar la calificacion y comentario
            $('.rate_widget').each(function(i) {
                var widget = this;
                var id =$(widget).attr('id');
                var dataSave = {
                    id : id,
                    calificacion: $(widget).data('fsr')?$(widget).data('fsr').calificacion:null,
                    tabla:$(widget).attr('data-table'),
                    comentario:$('.'+id).val(),
                    cotizacion_id:cotizacionId
                };

                $.post(
                    "<?php echo site_url('calificaciones/set_calificacion'); ?>",
                    dataSave,
                    'json'
                );
            });


            // guardamos como calificada esta venta
//            $.post(
//                "<?php //echo site_url('calificaciones/set_concluida'); ?>//",
//                {cotizacion_id:cotizacionId},
//                'json'
//            );
            var data_cotizacion = {
                cotizacion_id:cotizacionId
            };

            $.post(
                '<?php echo site_url('calificaciones/califica/'.$cotizacion_id.'/'.sha1($cotizacion_id.$created)) ?>', data_cotizacion, function(res) {
                $('div.califica').html(res);
            });
        });

        $('.medianas').css({'background-size': '18px 18px', 'width': '25px'});
    });
</script>
<?php endif; ?>
<?php endif; ?>
