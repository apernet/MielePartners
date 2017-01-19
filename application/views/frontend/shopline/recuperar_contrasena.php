<?php if(!is_ajax()) $this->load->view('layout/head'); ?>
<div class="row no-margin">
	<div class="col-lg-4 col-lg-offset-4 admin-login">
		<div class="row">
			<div class="col-lg-12 tc admin-login-logo">
				<img  src="<?php echo site_url('img/admin_theme/miele-logo.png');?>" alt="<?php echo $this->config->item('proyecto'); ?>"/>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 admin-login-body backgroundGeneric">
				<?php $this->load->view('layout/flash'); ?>
				<h4><?php echo $titulo?></h4>
				<p class="msg info mb40">
					<span class="fa-stack fa-2x">
					  <i class="fa fa-circle fa-stack-2x text-color-blanco"></i>
					  <i class="fa fa-info fa-stack-1x text-color-info"></i>
					</span>
					Para recuperar su contrase&ntilde;a, capture su correo electr&oacute;nico y el sistema le generar&aacute; y enviar&aacute; una nueva contrase&ntilde;a a la cuenta de correo especificada.
					Los campos requeridos est&aacute;n marcados con <span class="req">*</span>
				</p>
				<form action="<?php echo site_url(uri_string()); ?>" id="form" method="post">
				<div class="row">
					<div class="col-lg-12 mb20">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-envelope text-color-icon"></i>
							</span>				
							<input id="email" name="email" value="<?php echo set_value('email'); ?>" type="text" class="form-control" placeholder="Correo Electronico">
						</div>
						<?php echo  form_error('email');?>
					</div>	
					<div class="col-lg-8 mb20 barra-btn">
						<input class="btn btn-primary pull-right" type="submit" value="Enviar" id="enviar" />
						<a href="<?php echo site_url('frontends/autenticacion');?>" class="btn btn-default pull-right" id="cancelar">Cancelar</a>
					</div>	
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<?php if(!is_ajax()) $this->load->view('layout/foot'); ?>