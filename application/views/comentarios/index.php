<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
    <div class="col-lg-12 formulario-head">
        <div class="row">
            <div class="col-lg-11 col-sm-10 col-xs-9">
                <h4><?php echo $titulo;?></h4>
            </div>
        </div>
    </div>
    <form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form" name="form">
        <div class=" col-lg-12">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo @$cond['nombre']; ?>" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Apellido paterno:</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo @$cond['apellido_paterno']; ?>" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Apellido materno:</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo @$cond['apellido_materno']; ?>" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Correo electr&oacute;nico:</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?php echo @$cond['email']; ?>" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Tel&eacute;fono:</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo @$cond['telefono']; ?>" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Celular:</label>
                    <input type="text" class="form-control" id="celular" name="celular" value="<?php echo @$cond['celular']; ?>" />
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
                    <label>Comentario:</label>
                    <input type="text" class="form-control" id="comentario" name="comentario" value="<?php echo @$cond['comentario']; ?>" />
                </div>
            </div>
        </div>
        <div class="col-lg-12 barra-btn">
            <div class="row">
                <button  type="submit" class="btn btn-primary pull-right">Buscar</button>
                <input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
            </div>
        </div>
    </form>
<?php if($total==0): ?>
    <div class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
    <div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-hover">
            <tfoot>
            <tr>
                <td colspan="9">
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
                <td class="data_first">Id</td>
                <td>Nombre</td>
                <td>Apellido paterno</td>
                <td>Apellido materno</td>
                <td>Correo electr&oacute;nico</td>
                <td>Tel&eacute;fono</td>
                <td>Celular</td>
                <td>Comentario</td>
                <td>Acciones</td>
            </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach($r as $ro):?>
                <tr <?php echo ($i%2==0)?'class="altrow"':''?>>
                    <td><?php echo $ro->id; ?></td>
                    <td><?php echo $ro->nombre; ?></td>
                    <td><?php echo $ro->apellido_paterno; ?></td>
                    <td><?php echo $ro->apellido_materno; ?></td>
                    <td><?php echo $ro->email; ?></td>
                    <td><?php echo $ro->telefono; ?></td>
                    <td><?php echo $ro->celular; ?></td>
                    <td><?php echo $ro->comentario; ?></td>
                    <td>
                        <?php if( $puede_eliminar ):?>
                            <a href="<?php echo site_url('comentarios/eliminar/'.$ro->id); ?>" class="accion accion3">Eliminar</a>
                        <?php endif;?>
                    </td>
                </tr>
                <?php $i++; endforeach;?>
            </tbody>
        </table>
    </div>
<?php endif;?>
</div>
    <script type="text/javascript">
        $(function(){
            convertir_campos();
        });
    </script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>