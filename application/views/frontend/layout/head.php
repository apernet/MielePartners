<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php echo $this->config->item('proyecto'); ?> - <?php echo $titulo; ?></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="generator" content="Geany 0.20" />
		
		<link href="<?php echo base_url(); ?>img/favicon.ico" type="image/x-icon" rel="icon" />
	 	<link type="text/css" href="<?php echo base_url(); ?>css/jquery/jquery-ui.min.css" rel="stylesheet" />
	 	<link type="text/css" href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css"/>

		<link type="text/css" href="<?php echo base_url(); ?>css/bc.css" rel="stylesheet" />
		
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/masonary/masonry.pkgd.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap.min.js"></script>
		
		<script type="text/javascript">
			BASE_URL='<?php echo base_url(); ?>';
			SITE_URL='<?php echo site_url(); ?>';
			THIS_URL='<?php echo $this->uri->uri_string(); ?>';
			MOSTAR_MENU='<?php echo $this->session->userdata('mostrar_menu');?>';
		</script>	
		<?php if($this->config->item('firebug') && $this->session->userdata('grupos_id')==1):?>
		<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>
		<?php endif;?>	
		
		<link type="text/css" href="<?php echo base_url(); ?>frontend/css/reset.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url(); ?>frontend/css/layout.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url(); ?>frontend/css/general.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url(); ?>frontend/css/metrics.css" rel="stylesheet" /> 
		<!-- 
		<link type="text/css" href="<?php echo base_url(); ?>js/jquery/css/redmond/jquery-ui-1.8.15.custom.css" rel="stylesheet" />
		
		-->
		
		<!--
		<script src="<?php echo base_url();?>frontend/js/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/jquery/jquery-1.6.2.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/jquery/jquery-ui-1.8.15.custom.min.js" type="text/javascript"></script>
		<script src="<?php echo base_url();?>js/jquery/jquery.ui.datepicker-es.js" type="text/javascript"></script>
		-->
		<script src="<?php echo base_url();?>js/bc/bc.js" type="text/javascript"></script>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-11664114-9', 'auto');
			ga('send', 'pageview');
		</script>
	</head>
	<body>
		<div id="wrapper">
			