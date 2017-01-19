<?php if (!is_ajax()) {
    $this->load->view('layout/header');
} ?>
    <div class="col-lg-12 formulario-head">
        <div class="row">
            <div class="col-lg-11 col-sm-10 col-xs-9">
                <h4><?php echo $calificados['nombre']; ?></h4>
            </div>
        </div>
    </div>
    <div class="" style="min-width: 1000px !important; min-height: 450px !important; ">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <div class="col-sm-6">Calificaci&oacute;n</div>
                <div class="col-sm-6">
                    <div id="<?php echo  @$calificados['result']->elementos_id ?>" class="rate_widget col-lg-6 pull-left" style="width: 180px; padding: 0px !important;" data-table="<?php echo $tabla;?>">
                        <div class="ratings_stars medianas" data-value="1"></div>
                        <div class="ratings_stars medianas" data-value="2"></div>
                        <div class="ratings_stars medianas" data-value="3"></div>
                        <div class="ratings_stars medianas" data-value="4"></div>
                        <div class="ratings_stars medianas" data-value="5"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 text-center">Reseñas del producto</div>
        </div>
        <br>
        <br>
        <hr>
        <div class="col-sm-12">
            <div class="col-sm-6 no-padding">
                <div class="col-sm-6 no-padding text-center">
                    <?php echo ($calificados['result'] && $calificados['result']->calificados>=1)? $calificados['result']->calificados.' Calificaciones.' : 'Sin Calificación.' ?>
                </div>
                <div class="col-sm-6 no-padding text-center">
                    <?php echo ($calificados['result'] && $calificados['result']->comentarios>=1)? $calificados['result']->comentarios.' Reseñas.' : 'Sin Comentarios.' ?>
                </div>
                &nbsp;
                <div class="col-sm-12 no-padding">
                    <div style="display:none;     margin-top: 100px;" id="sin_resultado">
                        <h4 class="text-center">No se ha encontrado ning&uacute;n registro para graficar.</h4>
                    </div>
                    <div id="graficas">
                        <div style="width: 400px; height: 200px; position: relative;">
                            <div id="calificaciones_div" style="width: 400px; height: 200px; top: 0; left: 0; position: absolute;" ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 no-padding">
                <div class="comentarios-container">

                    <div style="display:<?php echo ($calificados['result'] && $calificados['result']->comentarios>=1)? 'none':'block' ?>;margin-top: 135px;">
                        <h4 class="text-center">No se ha encontrado ning&uacute;n comentario para mostrar.</h4>
                    </div>

                    <?php foreach ($comentarios as $ro): ?>
                        <div class="col-sm-2 no-padding"></div>
                        <div class="col-sm-10 comentarios-div">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div style="float: left; width:50%;" id="<?php echo $ro->id ?>" class="rate_widget comentarios_rate_widget" data-table="<?php echo $tabla;?>" style="padding: 0px !important;">
                                        <div class="ratings_stars chicas" data-value="1"></div>
                                        <div class="ratings_stars chicas" data-value="2"></div>
                                        <div class="ratings_stars chicas" data-value="3"></div>
                                        <div class="ratings_stars chicas" data-value="4"></div>
                                        <div class="ratings_stars chicas" data-value="5"></div>
                                    </div>
                                </div>

                                <div class="col-sm-8 text-center">
                                    <p> <?php echo ver_fecha_hora($ro->created);?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php echo $ro->comentario;?>
                                </div>
                            </div>

                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <br>
        <hr>
    </div>

    <script type="text/javascript">
        <!--//
        $(function() {
            $('.rate_widget').each(function(i) {
                var widget = this;
                var out_data = {
                    id : $(widget).attr('id'),
                    tabla: $(widget).attr('data-table')
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
                if(avg){
                    $(widget).find("[data-value='" + avg + "']").prevAll().andSelf().addClass('ratings_vote');
                    $(widget).find("[data-value='" + avg + "']").nextAll().removeClass('ratings_vote');
                }
            }

            $('.comentarios_rate_widget').each(function(i) {
                var widget = this;
                var out_data = {
                    id : $(widget).attr('id'),
                    tabla: $(widget).attr('data-table')
                };
                $.post(
                    "<?php echo site_url('calificaciones/get_calificacion_comentario'); ?>",
                    out_data,
                    function(data) {
                        $(widget).data( 'calif', data);
                        set_votes_comentario(widget);
                    },
                    'json'
                );
            });

            function set_votes_comentario(widget) {
                var avg = $(widget).data('calif')?$(widget).data('calif'):null;
                if(avg){
                    $(widget).find("[data-value='" + avg + "']").prevAll().andSelf().addClass('ratings_vote');
                    $(widget).find("[data-value='" + avg + "']").nextAll().removeClass('ratings_vote');
                }
            }

            $('.medianas').css({'background-size': '18px 18px', 'width': '25px'});
            $('.chicas').css({'background-size': '13px 13px', 'width': '20px'});
            $('.comentarios_rate_widget').css({'width': '120px'});

            var chartsOptions = {
                pieSliceTextStyle: {
                    color: 'black'
                },
                colors: [
                    '#E60000'
                ],
                backgroundColor: '#d4d4d4',
                chartArea: {left:120, width: 250},
                width:500
            };

            function calificacionesChartData(data) {

                var chartData = google.visualization.arrayToDataTable([
                    ["Element", "Calificación" ],
                    ["5 estrellas", Number(data.calificacion5)],
                    ["4 estrellas", Number(data.calificacion4)],
                    ["3 estrellas", Number(data.calificacion3)],
                    ["2 estrellas", Number(data.calificacion2)],
                    ["1 estrella",  Number(data.calificacion1)]
                ]);

                return chartData;
            }

            function calificacionesChartDraw(data) {
                var data = calificacionesChartData(data);
                //Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.BarChart(document.getElementById('calificaciones_div'));
                chart.draw(data, chartsOptions);
                return true;
            }

            function draw(data) {
                if(!$.isEmptyObject(data)){
                    calificacionesChartDraw(data);
                    $('#sin_resultado').hide();
                    $('#graficas').show();
                    return;
                }
                else {
                    $('#sin_resultado').show();
                    $('#graficas').hide();
                    return;
                }
            }

            google.load("visualization", "1", {packages:["corechart"], callback : function() {
                var data = <?php echo json_encode($calificados['result']); ?>;
                draw(data);
            }});

        });
        //-->
    </script>

<?php if (!is_ajax()) {
    $this->load->view('layout/footer');
} ?>