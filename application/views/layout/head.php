<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MT66XLH');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src=“https://www.googletagmanager.com/ns.html?id=GTM-MT66XLH”
height=“0" width=“0” style=“display:none;visibility:hidden”></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->

	<title><?php echo $titulo;?> - <?php echo $this->config->item('proyecto');?></title>


	<link href="<?php echo base_url(); ?>img/admin_theme/favicon.ico" type="image/x-icon" rel="icon" />
 	<link type="text/css" href="<?php echo base_url(); ?>css/jquery/jquery-ui.min.css" rel="stylesheet" />
 	<link type="text/css" href="<?php echo base_url(); ?>css/admin_theme.css" rel="stylesheet" />
<!--    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome-4.1.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/multi-select.css">
	<link type="text/css" href="<?php echo base_url(); ?>css/front.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/alertify/themes/alertify.core.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/alertify/themes/alertify.default.css" id="toggleCSS" />
	<!--meta name="viewport" content="width=device-width"-->
	<style>
		.alertify-log-custom {background: blue;}
		.alertify-cover {background-color:black !important;filter:alpha(opacity=0) !important;opacity:0.7 !important; }
	</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery.min.js"></script> 
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/masonary/masonry.pkgd.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.multi-select.js"></script>
	<!-- MULTISELECT -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/quicksearch-master/jquery.quicksearch.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/bc_select_multiple.js"></script>

	<!-- MULTISELECT BOXES -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/select2/select2.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/select2/select2.css" />

	<!-- BlockUI -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery/jquery.blockUI.js"></script>

	<!-- FANCYBOX -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.js"></script>
	<!-- FIN FANCYBOX -->

    <!--COLORBOX -->
    <link type="text/css" href="<?php echo base_url(); ?>js/colorbox/colorbox.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo base_url(); ?>css/animate.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo base_url(); ?>js/colorbox/jquery.colorbox-min.js"></script>
    <!--FIN COLORBOX -->

	<!-- CALIFICACIONEs -->
	<link type="text/css" href="<?php echo base_url(); ?>css/calificaciones.css" rel="stylesheet" />

	<script type="text/javascript">
		BASE_URL='<?php echo base_url(); ?>';
		SITE_URL='<?php echo site_url(); ?>';
		THIS_URL='<?php echo $this->uri->uri_string(); ?>';
		MOSTAR_MENU='<?php echo $this->session->userdata('mostrar_menu');?>';
	</script>
	<?php if($this->config->item('firebug') && $this->session->userdata('grupos_id')==1):?>
	<script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>
	<?php endif;?>	
	<script src="<?php echo base_url();?>js/bc/bc.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(window).load(function() {
	    var container = $('.js-masonry');
	    container.masonry( container, {
		  // options
		  columnWidth: 100,
		  itemSelector: '.item'
		});
	});
	</script>
	<?php if($this->config->item('domain')=='www.mielepartners.com.mx'):?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-46981347-1', 'auto');
		ga('send', 'pageview');
	</script>
	<?php endif;?>
	<?php if($this->config->item('domain')=='shop.miele.com.mx'):?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-46981347-1', 'auto');
		ga('send', 'pageview');
	</script>
	<?php endif;?>
	<?php if($this->config->item('domain')=='localhost'|| $this->config->item('domain')=='preview.mieleshop.com.mx'):?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-11664114-9', 'auto');//UA-11664114-8 MielePartners UA-11664114-8 MieleShop
			/*ga('create', 'UA-11664114-9', {
				'cookieDomain': 'none'
			});*/
			ga('send', 'pageview');
		</script>
	<?php endif;?>
</head>
<body>
<div class="backGround">
    <div class="imgBack">

    </div>
</div>
<div class="headerHead"><!-- div del header -->
