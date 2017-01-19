<?php if (!is_ajax()) {
    $this->load->view('frontend/layout/header');
} ?>
    <div class="minHeight">
        <div class="row noMargin">
            <div class="front_subtituloSeccion">
                <p><?php echo $titulo; ?></p>
            </div>
        </div>
        <div class="col-lg-12">
            <p class="msg info">
			<span class="fa-stack fa-2x">
			  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
			  <i class="fa fa-info fa-stack-1x text-color-info"></i>
			</span>
                Los campos requeridos est&aacute;n marcados con
                <span class="req">*</span>
                .
                <!--		 	Si a&uacute;n no se encuentra registrado de clic <a href="--><?php //echo site_url('frontends/registro');?><!--">aqu&iacute;</a>.-->
            </p>
        </div>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-lg-6">
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                            Por favor, ingrese su correo electr&oacute;nico y contrase&ntilde;a
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="row">
                                            <form action="<?php echo site_url('frontends/autenticacion'); ?>" id="form" method="post" enctype="multipart/form-data">
                                                <div class="col-lg-12 form-group">
                                                    <label>
                                                        <span class="req">*</span>
                                                        Correo electr&oacute;nico:
                                                    </label>
                                                    <input class="form-control" id="email" name="email" value="<?php //echo set_value('email'); ?>"/>
                                                    <?php echo form_error('email'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                    <label>
                                                        <span class="req">*</span>
                                                        Contrase&ntilde;a:
                                                    </label>
                                                    <input class="form-control" id="contrasena" name="contrasena" type="password"/>
                                                    <?php echo form_error('email'); ?>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="col-lg-12 barra-btn">
													<a href="<?php echo site_url('frontends/recuperar_contrasena'); ?>" id="recuperar_a" >&iquest;Olvid&oacute; su contrase&ntilde;a?</a>
                                                    <input class="btn btn-primary pull-right" type="submit" value="Iniciar Sesión" id="guardar"/>
                                                    <a href="" class="btn btn-default pull-right" id="cancelar">Cancelar</a>
                                                    <a id="registro" class="btn btn-front-primary pull-right" href="<?php echo site_url('frontends/registro'); ?>" style="display: none;">Registro</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                            Si a&uacute;n no se encuentra registrado, llene este formulario.
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="row">
                                            <form action="<?php echo site_url('frontends/registro'); ?>" id="form" method="post" enctype="multipart/form-data">
                                                <div class="col-lg-12 form-group">
                                                    <label>
                                                        <span class="req">*</span>
                                                        Correo electr&oacute;nico:
                                                    </label>
                                                    <input class="form-control" id="email_registro" name="email_registro" value="<?php echo set_value('email_registro', @$r->email); ?>"/>
                                                    <?php echo form_error('email_registro'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                	<label>
                                                    	<span class="req">*</span>
                                                           Contrase&ntilde;a:
                                                    </label>
                                                    <input class="form-control" id="contrasena_registro" name="contrasena_registro" type="password"/>
                                                    <?php echo form_error('contrasena_registro'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                	<label>
                                                		<span class="req">*</span>
                                                        Confirmar Contrase&ntilde;a:
                                               		</label>
                                                    <input class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" type="password"/>
                                                    <?php echo form_error('confirmar_contrasena'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                    <label>
                                                        <span class="req">*</span>
                                                        Nombre(s):
                                                    </label>
                                                    <input class="form-control" id="nombre" name="nombre" value="<?php echo set_value('nombre', @$r->nombre); ?>"/>
                                                    <?php echo form_error('nombre'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                    <label>
                                                        <span class="req">*</span>
                                                        Apellido paterno:
                                                    </label>
                                                    <input class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo set_value('apellido_paterno', @$r->apellido_paterno); ?>"/>
                                                    <?php echo form_error('apellido_paterno'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                    <label>
                                                        <span class="req">*</span>
                                                        Apellido materno:
                                                    </label>
                                                    <input class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo set_value('apellido_materno', @$r->apellido_materno); ?>"/>
                                                    <?php echo form_error('apellido_materno'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                    <label>Tel&eacute;fono:</label>
                                                    <input class="form-control" id="telefono" name="telefono" value="<?php echo set_value('telefono', @$r->telefono); ?>"/>
                                                    <?php echo form_error('telefono'); ?>
                                                </div>
                                                <div class="col-lg-12 form-group">
                                                    <label>Celular:</label>
                                                    <input class="form-control" id="celular" name="celular" value="<?php echo set_value('celular', @$r->celular); ?>"/>
                                                    <?php echo form_error('celular'); ?>
                                                </div>
                                                <div class="clear"></div>
                                                <div class="col-lg-12 barra-btn">
                                                    <input class="btn btn-primary pull-right" type="submit" value="Registrarse" id="guardar"/>
                                                    <a href="" class="btn btn-default pull-right" id="cancelar">Cancelar</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if (!is_ajax()) {
    $this->load->view('frontend/layout/footer');
} ?>