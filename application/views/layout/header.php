<?php $this->load->view('layout/head'); ?>
<!-- DIV HEADER-->
<div class="background_header header one-edge-shadow">
	<div class="row noMargin mt20 mb20">
		<div class="col-lg-11 col-md-10 col-sm-9 col-xs-5 front-pleca"></div>
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6">
			<a id="dashboard" title="Mis actividades en el sistema" href="<?php echo site_url('main/index');?>">
				<img class="" src="<?php echo site_url('img/admin_theme/Logo_MIELE.png');?>"/>
			</a>
		</div>
	</div>
</div>
<!-- FIN DIV HEADER-->

<div class="container minHeight">
	<div class="viewInfo">
	<div class="row admin-wrapper mt60 mb60">
		<div class="col-lg-12">
			<?php $this->load->view('layout/flash'); ?>
		</div>
