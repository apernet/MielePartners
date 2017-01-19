<?php $this->load->view('layout/head'); ?>
	<div class="row no-margin">
		<div class="col-lg-4 col-lg-offset-4 admin-login">
			<div class="row">
				<div class="col-lg-12 tc admin-login-logo">
					<img  src="<?php echo site_url('img/admin_theme/miele-logo.png');?>" alt="<?php echo $this->config->item('proyecto'); ?>"/>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 admin-login-body backgroundGeneric ">
					<?php if(!$explorer):?>
					<?php $this->load->view('layout/flash'); ?>
						<p class="msg info mb40">
						<span class="fa-stack fa-2x">
						  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
						  <i class="fa fa-info fa-stack-1x text-color-info"></i>
						</span>
						 Los campos requeridos est&aacute;n marcados con <span class="req">*</span></p>
						<form method="post" id="form" action="<?php echo site_url(uri_string());?>">
							<div class="row">
								<div class="col-lg-12 mb20">
								 	<div class="input-group">
									  <span class="input-group-addon">
									  	<i class="fa fa-user text-color-icon"></i>
									  </span>
									  <input id="usuario" name="usuario" value="<?php echo set_value('usuario');?>" type="text" class="form-control" placeholder="Usuario">
									</div>
									<?php echo form_error('usuario');?>
								</div>
								<div class="col-lg-12 mb20">
									<div class="input-group">
									  <span class="input-group-addon">
									  	<i class="fa fa-lock text-color-icon"></i>
									  </span>
									  <input  id="contrasena" name="contrasena" type="password" class="form-control" placeholder="Contraseña">
									</div>
									<?php echo form_error('contrasena');?>
								</div>
								<div class="col-lg-6 mt20">
									<a href="<?php echo site_url('main/recuperar_contrasena'); ?>" id="recuperar_a" class="bc_fancybox" >&iquest;Olvid&oacute; su contrase&ntilde;a?</a>
								</div>
								<div class="col-lg-6 mt20">
									<input type="submit" value="Iniciar sesi&oacute;n" id="btn_siguiente" class="btn btn-primary pull-right" />
								</div>
							</div>
						</form>
					<?php else:?>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
</script>
<!--<?php// $this->load->view('layout/foot'); ?>-->