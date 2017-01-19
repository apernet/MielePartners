<?php $this->load->view('frontend/layout/header');?>
<div class="container">
	<div id="sidebar">
		<div class="w100 auto">
			<h1 class="red"><?php echo $titulo ;?></h1>
		</div>
		<div class="w100 auto" id="menu_categorias">
			<span class="categoria"><a href=""></a></span>
			<ul>
			<?php if(!empty($resultado['categorias'])):?><li><a id="btn_categorias" href="#">Categor&iacute;as</a></li><?php endif;?>
			<?php if(!empty($resultado['productos'])):?><li><a id="btn_productos" href="#">Productos</a></li><?php endif;?>
			<?php if(!empty($resultado['designers'])):?><li><a id="btn_designers" href="#">Diseñadores</a></li><?php endif;?>
			<?php if(!empty($resultado['marcas'])):?><li><a id="btn_marcas" href="#">Marcas</a></li><?php endif;?>
				<li><a id="all" href="#">Todos...</a></li>
			</ul>
		</div>
	</div>
	<div id="container">
	<?php if(empty($resultado['categorias']) && empty($resultado['productos']) && empty($resultado['designers']) && empty($resultado['marcas'])): ?>
		<div class="wrapper f18p" style="height: 250px;">
			<h1 class="w50 no_float auto text_center" style="padding-top: 110px;">El contenido no existe...</h1>
		</div>
	<?php endif;?>
		<!-- SECCION DE CATEGORIAS -->
		<?php if(!empty($resultado['categorias'])):?>
		<div class="wrapper" id="box_categorias">
			<div class="w100 no_float auto border_vertical" style="margin: 10px auto; padding: 10px auto;">
				<br/>
				<h3 class="w90 no_float auto rojo bold f15p">CATEGOR&Iacute;AS</h3>
				<br/>
			</div>
			
			<?php foreach($resultado['categorias'] as $c):?>
			<div class="grid3" style="margin-top: -1px; margin-bottom: 1px;">
				<div class="img3">
					<a href="<?php echo site_url('frontends/categorias/'.$c->id);?>" >
						<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/categorias/'.$c->id.'.jpg&s=171&t='.time())?>" />&nbsp;
					</a>
				</div>
				<div class="descripcion">
					<span class="letra_gris bold uppercase f10p pad-top-7px block"><?php echo $c->nombre;?></span>
				</div>
			</div>
			<?php endforeach;?>
		</div>
		<?php endif;?>
		
		<!-- SECCION DE PRODUCTOS -->
		<?php if(!empty($resultado['productos'])):?>
		<div class="wrapper" id="box_productos">
			
			<div class="w100 no_float auto border_vertical" style="margin: 10px auto; padding: 10px auto;">
				<br/>
				<h3 class="w90 no_float auto rojo bold f15p">PRODUCTOS</h3>
				<br/>
			</div>
			
			<?php foreach($resultado['productos'] as $p):?>
			<div class="grid3" style="margin-top: -1px; margin-bottom: 1px;">
				<div class="img3">
					<a href="<?php echo site_url('frontends/productos/'.$p->id);?>" >
						<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$p->id.'/'.$p->imagen_producto.'.jpg&s=171&t='.time())?>" />&nbsp;
					</a>
				</div>
				<div class="descripcion">
					<span class="letra_gris bold uppercase f10p pad-top-7px block"><?php echo $p->nombre;?></span>
				</div>
			</div>
			<?php endforeach;?>
			
		</div>
		<?php endif;?>
		
	</div>
</div>
<script type="text/javascript">
<!--//
$(function(){
	$('#btn_categorias').click(function(e){
		e.preventDefault();
		$('#box_productos').hide();
		$('#box_designers').hide();
		$('#box_marcas').hide();
		$('#box_categorias').fadeIn();
	});
	$('#btn_productos').click(function(e){
		e.preventDefault();
		$('#box_categorias').hide();
		$('#box_designers').hide();
		$('#box_marcas').hide();
		$('#box_productos').fadeIn();
	});

	$('#all').click(function(e){
		e.preventDefault();
		$('#box_categorias,#box_productos,#box_designers,#box_marcas').fadeIn();
	});
});
//-->
</script>
<?php $this->load->view('frontend/layout/footer');?>