<?php $this->load->view('layout/head'); ?>
<div class="menu-principal">
	<div class="container">
		<nav class="navbar navbar-default" role="navigation">
		  <!-- Brand and toggle get grouped for better mobile display -->
		  <div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		      <span class="sr-only">Toggle navigation</span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		      <span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="#">
		    	<img alt="" src="<?php echo site_url('img/admin_theme/miele-logo.png');?>">
		    </a>
		  </div>
		
		  <!-- Collect the nav links, forms, and other content for toggling -->
		  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  	<form class="navbar-form navbar-left" role="search">
		      <div class="form-group">
		        <input type="text" class="form-control input-sm" placeholder="Search">
		      </div>
		      <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i></button>
		    </form>
		    <ul class="nav navbar-nav navbar-right">
		      
		      	<?php if($this->session->userdata('cuentas_id')==1 && $this->session->userdata('grupos_id')==1):?>
		      	<li>
					<a href="<?php echo site_url('main/usuarios_cambiar');?>" title="Cambiar de usuario" class="bc_colorbox"><?php echo $this->session->userdata('nombre'); ?></a>
		      	</li>
				<?php else: ?>
				<?php echo $this->session->userdata('nombre'); ?>
				<?php endif; ?>
				<?php $this->load->view('layout/user_menu'); ?>
		      <!-- 
		      <li class="dropdown">
		        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->session->userdata('nombre'); ?> <b class="caret"></b></a>
		        <ul class="dropdown-menu">
		          <li><a href="#">Action</a></li>
		          <li><a href="#">Another action</a></li>
		          <li><a href="#">Something else here</a></li>
		          <li class="divider"></li>
		          <li><a href="#">Separated link</a></li>
		        </ul>
		       -->
		      </li>
		      <li><a title="Cerrar sesi&oacute;n" id="logout_a" href="<?php echo site_url('main/logout');?>">
		      	<i class="fa fa-power-off text-color-primario"></i>
		      </a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
		    <?php if(tiene_permiso('menu_panel_control')):?>
		      <li class="dropdown">
		        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Panel de Control <b class="caret"></b></a>
		        <ul class="dropdown-menu">
		        	<?php if(tiene_permiso('grupos')):?> <li><a href="<?php echo site_url('main/grupos');?>">Grupos</a></li> <?php endif; ?>
					<?php if(tiene_permiso('permisos')):?> <li><a href="<?php echo site_url('main/permisos');?>">Permisos</a></li> <?php endif; ?>
					<?php if(tiene_permiso('catalogos')):?> <li><a href="<?php echo site_url('main/catalogos');?>">Catalogos</a></li> <?php endif; ?>
					<?php if(tiene_permiso('elementos')):?> <li><a href="<?php echo site_url('main/elementos');?>">Elementos de catalogos</a></li> <?php endif; ?>
					<?php if(tiene_permiso('cuentas')):?> <li><a href="<?php echo site_url('main/cuentas');?>">Cuentas</a></li> <?php endif; ?>
					<?php if(tiene_permiso('usuarios')):?> <li><a href="<?php echo site_url('main/usuarios');?>">Usuarios</a></li> <?php endif; ?>
					<?php if(tiene_permiso('noticias')):?> <li><a href="<?php echo site_url('noticias/index');?>">Noticias</a></li> <?php endif; ?>
					<?php if(tiene_permiso('logs')):?> <li><a href="<?php echo site_url('main/logs');?>">Logs</a></li> <?php endif; ?>
		        </ul>
		      </li>
		      <?php endif; ?>
		    <?php if(tiene_permiso('menu_miele')):?>
		    	<li class="dropdown">
			        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Miele (Web) <b class="caret"></b></a>
			        <ul class="dropdown-menu" >
			        	<?php if(tiene_permiso('categorias_productos')):?> <li><a href="<?php echo site_url('categorias/index');?>">Categorias</a></li> <?php endif; ?><?php if(tiene_permiso('productos')):?> <li><a href="<?php echo site_url('productos/index');?>">Productos</a></li> <?php endif; ?>
						<?php if(tiene_permiso('tipos_accesorios')):?> <li><a href="<?php echo site_url('accesorios/tipos_accesorios');?>">Tipos Accesorios</a></li> <?php endif; ?>
						<?php if(tiene_permiso('accesorios')):?> <li><a href="<?php echo site_url('accesorios/index');?>">Accesorios</a></li> <?php endif; ?>
						<?php if(tiene_permiso('banners')):?> <li><a href="<?php echo site_url('banners/index');?>">Banners</a></li> <?php endif; ?>
			        </ul>
		        </li>
		    <?php endif; ?>
		    <?php if(tiene_permiso('menu_administracion')):?>
		    	<li class="dropdown">
			        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Administraci&oacute;n <b class="caret"></b></a>
			        <ul class="dropdown-menu" >
			        	<?php if(tiene_permiso('cotizaciones')):?> <li><a href="<?php echo site_url('cotizaciones/index');?>">Cotizaciones</a></li> <?php endif; ?>
			        </ul>
			    </li>
			<?php endif; ?>
		    </ul>
		    
		    
		  </div><!-- /.navbar-collapse -->
		</nav>
	</div>
</div>
<?php $this->load->view('layout/foot'); ?>