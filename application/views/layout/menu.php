<div class="front_footer ">
	<div class="row">
		<nav class="navbar navbar-default front_footer" role="navigation">
			<!--<div class="navbar-header">
			    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			      	<span class="sr-only">Toggle navigation</span>
			      	<span class="icon-bar"></span>
			      	<span class="icon-bar"></span>
			      	<span class="icon-bar"></span>
			    </button>
			    <a class="navbar-brand" href="#">
			    	<img alt="" src="<?php echo site_url('img/admin_theme/miele-logo.png');?>">
			    </a>
			</div>-->
			<div class="col-lg-1 col-md-4 col-sm-4 col-xs-6">
				<ul class="nav nav-pills">
					<li style="margin-top: -9px; margin-left: -16px;">
						<script type="text/javascript" src="https://seal.websecurity.norton.com/getseal?host_name=WWW.MIELEPARTNERS.COM.MX&amp;size=L&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=es"></script><br/>
						<!--					<a href="https://www.certsuperior.com/" target="_blank" class="front-link footerAdver" title="Certificados SSL" style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">-->
						<!--						Certificados SSL-->
						<!--						<img src="https://www.certsuperior.com/images/CS-verisign.jpg" width="130px" height="35px" border="0" />-->
						<!--					</a>-->
					</li>
				</ul>
			</div>
			<div class="col-lg-1 col-md-4 col-sm-2 col-xs-6">
				<ul class="nav nav-pills">
					<li style="margin-top: 10px; margin-left: 20px;"><a class="front-link footerAdver" href="<?php echo site_url('files/terminos/aviso_privacidad.docx');?>">Aviso de Privacidad</a></li>
				</ul>
			</div>
			<div class="col-lg-2 col-md-4 col-sm-4 col-xs-6">
				<ul class="nav nav-pills">
					<li style="margin-top: 10px;"><a class="front-link footerAdver"> © M&eacute;xico <?php echo date('Y') ?>. Todos los Derechos Reservados.</a></li>
				</ul>
			</div>
		   	<div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
    	    	<ul class="nav navbar-nav navbar-right">
    	    		<li class="admin_Footer adminMargin" style="margin-top: 30px;">
						<a id="ayuda" title="Cat&aacute;logo en l&iacute;nea" href="<?php echo site_url('frontends/index');?>">Cat&aacute;logo en L&iacute;nea</a> 
					</li>
    	      		<?php if($this->session->userdata('cuentas_id')==1 && $this->session->userdata('grupos_id')==1):?>
	    	      	<li class="admin_Footer adminMargin" style="margin-top: 30px;">
	    				<a href="<?php echo site_url('main/usuarios_cambiar');?>" title="Cambiar de usuario" class="bc_fancybox"><?php echo $this->session->userdata('nombre'); ?></a>
	    	      	</li>
    				<?php else: ?>
	    			<li class="admin_Footer adminMargin" style="margin-top: 30px;">
	    			     <a href="#"><?php echo $this->session->userdata('nombre'); ?></a>
	    			</li>
    				<?php endif; ?>
    				<?php //$this->load->view('layout/user_menu'); ?>
	    	      	<li class="adminMargin" style="margin-top: 30px;">
	    	      		<a title="Cerrar sesi&oacute;n" id="logout_a" href="<?php echo site_url('main/logout');?>"><i class="fa fa-power-off fa-lg text-color-primario"></i></a>
	    	      	</li>
    	    	</ul>
    	    	<ul class="nav navbar-nav navbar-right">
	    	    	<?php if(tiene_permiso('menu_panel_control')):?>
	    	      	<li class="dropdown admin_Footer adminMargin" style="margin-top: 30px !important;">
	    	        	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Panel de Control <i class="fa fa-caret-up"></i></a>
	    	        	<ul class="dropdown-menu drop-up" role="menu">
							<?php if(tiene_permiso('catalogos')):?> <li><a href="<?php echo site_url('main/catalogos');?>">Cat&aacute;logos</a></li> <?php endif; ?>
							<?php if(tiene_permiso('cuentas')):?> <li><a href="<?php echo site_url('main/cuentas');?>">Cuentas</a></li> <?php endif; ?>
							<?php if(tiene_permiso('elementos')):?> <li><a href="<?php echo site_url('main/elementos');?>">Elementos de cat&aacute;logos</a></li> <?php endif; ?>
							<?php if(tiene_permiso('grupos')):?> <li><a href="<?php echo site_url('main/grupos');?>">Grupos</a></li> <?php endif; ?>
							<?php if(tiene_permiso('logs')):?> <li><a href="<?php echo site_url('main/logs');?>">Logs</a></li> <?php endif; ?>
							<?php if(tiene_permiso('noticias')):?> <li><a href="<?php echo site_url('noticias/index');?>">Noticias</a></li> <?php endif; ?>
							<?php if(tiene_permiso('permisos')):?> <li><a href="<?php echo site_url('main/permisos');?>">Permisos</a></li> <?php endif; ?>
							<?php if(tiene_permiso('usuarios')):?> <li><a href="<?php echo site_url('main/usuarios');?>">Usuarios</a></li> <?php endif; ?>
		    	        </ul>
	    	      	</li>
	    	      	<?php endif; ?>
	    	    	<?php if(tiene_permiso('menu_miele')):?>
	    	    	<li class="dropdown admin_Footer adminMargin" style="margin-top: 30px;">
	    		        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Miele (Web) <i class="fa fa-caret-up"></i></a>
	    		        <ul class="dropdown-menu drop-up" role="menu" >
	    					<?php if(tiene_permiso('accesorios')):?> <li><a href="<?php echo site_url('accesorios/index');?>">Accesorios</a></li> <?php endif; ?>
	    					<?php if(tiene_permiso('alianzas_promociones')):?> <li><a href="<?php echo site_url('alianzas_promociones/index');?>">Alianzas promociones</a></li> <?php endif; ?>
	    					<?php if(tiene_permiso('banners')):?> <li><a href="<?php echo site_url('banners/index');?>">Banners</a></li> <?php endif; ?>
	    		        	<?php if(tiene_permiso('categorias_productos')):?> <li><a href="<?php echo site_url('categorias/index');?>">Categor&iacute;as</a></li> <?php endif; ?>
	    					<?php if(tiene_permiso('cupones')):?> <li><a href="<?php echo site_url('cupones/index');?>">Cupones</a></li> <?php endif; ?>
							<?php if(tiene_permiso('gastos_envio')):?> <li><a href="<?php echo site_url('gastos_envios/index');?>">Gastos de env&iacute;o</a></li> <?php endif; ?>
	    					<?php if(tiene_permiso('paquetes')):?> <li><a href="<?php echo site_url('paquetes/index');?>">Paquetes</a></li> <?php endif; ?>
	    		        	<?php if(tiene_permiso('productos')):?> <li><a href="<?php echo site_url('productos/index');?>">Productos</a></li> <?php endif; ?>
	    					<?php if(tiene_permiso('promociones')):?> <li><a href="<?php echo site_url('promociones/index');?>">Promociones</a></li> <?php endif; ?>
	    					<?php if(tiene_permiso('tipos_accesorios')):?> <li><a href="<?php echo site_url('accesorios/tipos_accesorios');?>">Tipos Accesorios</a></li> <?php endif; ?>
	    		        </ul>
	    	        </li>
	    	    	<?php endif; ?>
	    	    	<?php if(tiene_permiso('menu_administracion')):?>
	    	    	<li class="dropdown admin_Footer adminMargin" style="margin-top: 30px;">
	    		        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administraci&oacute;n <i class="fa fa-caret-up"></i></a>
	    		        <ul class="dropdown-menu drop-up" role="menu" >
                            <?php if(tiene_permiso('comentarios')):?> <li><a href="<?php echo site_url('comentarios/index');?>">Comentarios</a></li> <?php endif; ?>
	    		        	<?php if(tiene_permiso('comisiones')):?> <li><a href="<?php echo site_url('comisiones/index');?>">Comisiones</a></li> <?php endif; ?>
	    		        	<?php if(tiene_permiso('cotizaciones')):?> <li><a href="<?php echo site_url('cotizaciones/index');?>">Cotizaciones</a></li> <?php endif; ?>
	    		        	<?php if(tiene_permiso('referidos')):?> <li><a href="<?php echo site_url('referidos/index');?>">Referidos</a></li> <?php endif; ?>
							<?php if(tiene_permiso('reporte_calificaciones_elementos')):?> <li><a href="<?php echo site_url('calificaciones/reporte');?>">Reporte Calificaciones</a></li> <?php endif; ?>
							<?php if(tiene_permiso('reporte_calificaciones_general')):?> <li><a href="<?php echo site_url('calificaciones/reporte_general/table/productos');?>">Reporte General de Calif. Productos</a></li> <?php endif; ?>
							<?php if(tiene_permiso('reporte_calificaciones_general')):?> <li><a href="<?php echo site_url('calificaciones/reporte_general/table/accesorios');?>">Reporte General de Calif. Accesorios</a></li> <?php endif; ?>

	    		        </ul>
	    		    </li>
	    			<?php endif; ?>
    	    	</ul>
		  	</div>
		</nav>
	</div>
</div>

