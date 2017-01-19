<?php if(file_exists(FCPATH.'files/categorias/'.$r->id.'.jpg')):?>
<div class="banner3 baner_center">
	<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/categorias/'.$r->id.'.jpg&s=573&t='.time())?>" />
</div>
<?php endif;?>

<?php if(!empty($categorias)):?>
<div class="both no_float">
	<div class="wrapper">
	<?php foreach($categorias as $c):?>
		<div class="grid3">
			<div class="img3">
				<a href="<?php echo site_url('frontends/categorias/'.$c->id); ?>" >
				<?php $file=FCPATH.'files/categorias/'.$c->id.'.jpg';?>
				<?php if(file_exists($file)):?>
					<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/categorias/'.$c->id.'.jpg&s=171&t='.time())?>" />&nbsp;
				<?php else:?>
					<img align="left" src="<?php echo site_url('/thumbs/timthumb.php?src=img/layout/imagen_no_disponible.jpg&s=171&t='.time());?>" class="img_thumb" />
				<?php endif;?>
				</a>
			</div>
			<div class="descripcion">
				<span class="letra_gris bold uppercase f10p pad-top-7px block"><?php echo $c->nombre;?></span>
			</div>
		</div>
	<?php endforeach;?>
	</div>
	<span class="clearblock"></span>
</div>
<?php endif;?>

<?php if(!empty($productos)):?>
	<div id="grid_productos"><?php echo $productos;?></div>
<?php endif;?>