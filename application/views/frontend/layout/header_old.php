<?php $this->load->view('layout/head');?>
<div class="container" id="header">
	<div class="w100">
		<div class="w100 p5" id="menu_header_top">
			<div class="w40">
				<form action="<?php echo site_url('frontends/ver_resultados');?>" method="POST">
						<input class="text" id="textbox" type="text" name="search" value="Ingrese una palabra clave o un # de producto" />
						<input class="btn_rojo" type="submit" value="BUSCAR" />
				</form>
			</div>
			<div class="w57 text_right p5" id="menu_header_top">
				<ul>
					<li><a href="<?php echo site_url('frontends/cotizacion_nueva');?>">Nueva cotizaci&oacute;n</a></li><div class="line"></div>
					<li><a href="<?php echo site_url('frontends/cotizacion');?>">Productos en la cotizaci&oacute;n (<?php echo (empty($num_productos))?'0':$num_productos;?>)</a></li><div class="line"></div>
					<li><a href=""><?php echo $usuario;?></a></li><div class="line"></div>
					<li><a href="<?php echo site_url('main/index');?>">Admin</a></li>
				</ul>
			</div>
		</div>
		<div  class="w100">
			<div class="w16 p5">
				<a href="<?php echo site_url('frontends/index');?>"><img src="<?php echo site_url('/thumbs/timthumb.php?src=img/layout/MieleImmerBesser.jpg&s=120&t='.time());?>" /></a>
			</div>
			<div class="w78 rojo_div  fl"></div>
		</div>
		<span class="clearblock"></span>
		<div class="w100" id="menu_header_bottom">
			<ul>
				<li><a href="<?php echo site_url('frontends/index');?>">HOME</a></li><div class="line"></div>
				<li><a href="<?php echo site_url('frontends/contacto');?>">CONT&Aacute;CTANOS</a></li><div class="line"></div>
				<li><a href="<?php echo site_url('frontends/legal');?>">AVISO LEGAL</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="container" id="menu">
	<ul>
		<?php foreach ($categorias_menu as $k=>$v):?>
			<li><a href="<?php echo site_url('frontends/categorias/'.$k); ?>" class="view"> <?php echo $v;?></a></li>
			<div class="line"></div>
		<?php endforeach;?>
	</ul>
</div> 
<?php $this->load->view('layout/flash'); ?>
<script type="text/javascript">
<!--//
	$(function(){
		var defaultText = "Ingrese una palabra clave o un # de producto";
		var searchBox = $('#textbox');
		
		searchBox.focus(function(){
			if($(this).attr("value") == defaultText) $(this).attr("value", "");
		});
		searchBox.blur(function(){
			if($(this).attr("value") == "") $(this).attr("value", defaultText);
		});
	});
//-->
</script>