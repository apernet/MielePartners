<?php $this->load->view('layout/head');?>
<script type="text/javascript">
	ELIMINAR_COMENTARIOS='<?php echo tiene_permiso('ocultar_comentarios')?1:0; ?>';
</script>
<div class="background_header header one-edge-shadow mb60">
	<!--<div class="col-lg-12 front-menu-admin">
		<ul class="nav nav-pills pull-right">
		  <li><a class="front-link" href=""><?php echo $usuario;?></a></li>
		  <li><a class="front-link" href="<?php echo site_url('frontends/cotizacion_nueva');?>"><i class="fa fa-bolt"></i> Nueva Cotizaci&oacute;n</a></li>
		  <?php if(INTERNO):?>
		  <li><a class="front-link" href="<?php echo site_url('main/index');?>">Admin</a></li>
		  <?php endif;?>
		  <li><a class="front-link" href="<?php echo site_url('frontends/contacto');?>"><i class="fa fa-phone"></i> Contacto</a></li>
		  <li><a class="front-link" href="<?php echo site_url('frontends/cotizacion');?>"><i class="fa fa-shopping-cart fa-lg"></i>  <?php echo $num_productos;?></a></li>
		</ul>
		<div class="clear"></div>
	</div>-->
	<!--menu movil-->
	<div class="noMargin hidden-lg hidden-md hidden-sm">
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		  
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <img class="imgHeader" src="<?php echo site_url('img/admin_theme/Logo_MIELE.png');?>">
		    </div>


		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse navMenu" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav noPadding backgroundGeneric" id="myList">
			   	 	<li><a class="front-link-menu border_nav" href="<?php echo site_url('frontends/index');?>"><i class="fa fa-home fa-lg"></i></a></li>
			   		<?php if(!isset($status_id) || (isset($status_id) && in_array($status_id,array(1,6))) && !empty($categorias_menu)):?>
						<?php foreach($categorias_menu as $k=>$v):?>
							<li class="noMarginTop"><a class="front-link-menu paddingList tc" href="<?php echo site_url('frontends/categorias/'.$k); ?>"> <?php echo $v;?></a></li>
						<?php endforeach;?>
                        <li class="noMarginTop"><a class="front-link-menu paddingList tc" href="<?php echo site_url('frontends/cotizacion_agregar_producto'); ?>"> Accesorios</a></li>
						<li class="noMarginTop"><a class="front-link-menu paddingList tc" href="<?php echo site_url('frontends/consumibles'); ?>"> Consumibles</a></li>
			   		<?php endif;?>
					<li class="noMarginTop"><a class="front-link-menu tc" href="<?php echo site_url('frontends/paquetes_detalles'); ?>">Paquetes</a></li>
			    </ul>
		    </div><!-- /.navbar-collapse -->
		  
		</nav>					
	</div>
	<!-- FIN menu movil-->
	<!-- Menu pantalla normal-->
	<div class="row noMargin hidden-xs">
		<div class="mt20">
			<!--
			<div class="col-lg-10 col-md-9 col-sm-9 col-xs-8 front-pleca mt20">
			</div>
			<div class="col-lg-1 col-md-3 col-sm-3 col-xs-4 mt20">
				<img alt="" src="<?php echo site_url('img/admin_theme/Logo_MIELE.png');?>">
			</div>
			-->
			<div class="col-lg-11 col-md-10 col-sm-9 col-xs-5 front-pleca"></div>
			<div class="col-lg-1 col-md-1 col-sm-1 col-xs-6">
				<img class="" src="<?php echo site_url('img/admin_theme/Logo_MIELE.png');?>">
			</div>
			
		</div>
	</div>
	<div class="row hidden-xs">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt20">
			<div class="headerMenu">
				<nav class="navbar navbar-default" role="navigation">
				  	<!-- Collect the nav links, forms, and other content for toggling -->
				  	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
					    <ul class="nav navbar-nav noPadding" id="myList">
					   	 	<li><a class="front-link-menu border_nav tc" href="<?php echo site_url('frontends/index');?>"><i class="fa fa-home fa-lg"></i></a></li>
							<?php if(!isset($status_id) || (isset($status_id) && in_array($status_id,array(1,6))) && !empty($categorias_menu)):?>
								<?php foreach($categorias_menu as $k=>$v):?>
									<li class="noMarginTop"><a class="front-link-menu paddingList tc" href="<?php echo site_url('frontends/categorias/'.$k); ?>"> <?php echo $v;?></a></li>
								<?php endforeach;?>
                                <li class="noMarginTop"><a class="front-link-menu paddingList tc" href="<?php echo site_url('frontends/cotizacion_agregar_producto'); ?>">Accesorios</a></li>
								<li class="noMarginTop"><a class="front-link-menu paddingList tc" href="<?php echo site_url('frontends/consumibles'); ?>"> Consumibles</a></li>
					   		<?php endif;?>
							<li class="noMarginTop"><a class="front-link-menu tc" href="<?php echo site_url('frontends/paquetes_detalles'); ?>">Paquetes</a></li>
					    </ul>
					</div>
				</nav>
			</div>
		</div>
	</div>
</div>

</div><!-- cierra div header-->
<div class="container"> <!-- DIV QUE CONTIENE LA INFORMACION DE LA VISTA -->
	<div class="viewInfo mt100">
	<?php $this->load->view('layout/flash'); ?>
	<?php if(!INTERNO && in_array($this->uri->segment(2),array('cotizacion','informacion_cliente','confirmacion','pago','registro','autenticacion'))):?>
		<div id="shop_line">
			<ul class="listCotizacion barraCotizacion">
				<li class="<?php echo $this->uri->segment(2)=='cotizacion'?'seleccionado':'';?>"> <a class="btnCotizacion btn-front-cotizacion" href=""><i class="fa marginIconR"></i> Carrito</a> </li>
				<li class="<?php echo $this->uri->segment(2)=='registro' || $this->uri->segment(2)=='autenticacion'?'seleccionado':'';?>"><a class="btnCotizacion btn-front-cotizacion" href="<?php echo site_url('frontends/autenticacion')?>"><i class="fa marginIconR"></i> Login</a> </li>
				<li class="<?php echo $this->uri->segment(2)=='informacion_cliente'?'seleccionado':'';?>"> <a class="btnCotizacion btn-front-cotizacion"><i class="fa marginIconR"></i> Información (Envío, Instalación, Facturación)</a></li>
				<li class="<?php echo $this->uri->segment(2)=='confirmacion'?'seleccionado':'';?>"><a class="btnCotizacion btn-front-cotizacion"><i class="fa marginIconR"></i>Confirmaci&oacute;n de compra</a></li>
			</ul>
		</div>
	<?php endif;?>