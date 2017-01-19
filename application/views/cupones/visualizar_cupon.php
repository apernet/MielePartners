<html>
<head>
	<title>Vista cup&oacute;n</title>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: <?php echo $this->config->item('header_color');?>; padding-left: 5%">
				<?php $path =  $this->config->item('url').'img/admin_theme/Logo_MIELE.png';?>
				<a href="<?php echo $this->config->item('url').'frontends/index';?>">
					<img src="<?php echo $path;?>" alt="<?php echo $this->config->item('proyecto');?>"/>
				</a>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
		</div>

		<div class="row">
			<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12" style="padding-left: 5%;">
				<p>Estimad@ Usuario,</p>
				<p>Adjunto encontrar&aacute; el cup&oacute;n obtenido por la compra que realiz&oacute; con folio <strong><?php echo $folio_compra?></strong>.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
				<img id="img_cupon" style="padding-left: 2.5%;" class="img-responsive" src="<?php echo $cupon_ruta; ?>">
			</div>
		</div>

		<div class="row">
			<div class="col-lg-10 col-md-12 col-sm-12 col-xs-12" style="padding-left: 5%;">
				<p>Un cordial saludo,</p>
				<p>Administrador de <?php echo $this->config->item('proyecto');?></p>
				<p>
					Para regresar a MIELE por favor d&eacute; clic <a href="<?php echo $this->config->item('url').'frontends/index';?>">aqu&iacute;</a>.
				</p>
			</div>
		</div>

	</div>
</body>
</html>