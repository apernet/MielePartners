<?php $this->load->view('frontend/layout/header');?>
<div class="container index_bg">
	<div class="index_container">
		<div class="banner1 baner_center">
		<?php if(!empty($banner['principal'])):?>
			<a href="<?php echo !empty($banner['principal']->categorias_id)?site_url("frontends/categorias/{$banner['principal']->categorias_id}"):site_url("frontends/productos/{$banner['principal']->productos_id}"); ?>">
			<?php if(($banner['principal']->banner_principal) == 1):?>
				<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/banners/'.$banner['principal']->banner_principal.'/'.$banner['principal']->id.'.jpg&s=742&t='.time())?>"/>
			<?php endif;?>
			</a>
		<?php endif;?>
		</div>
	</div>
	<?php if(!empty($productos)):?>
		<div class="index_container">
			<div class="grid_box">
			<?php foreach($productos as $p):?>
				<div class="grid3">
					<div class="img">
						<a href="<?php echo site_url('frontends/productos/'.$p->id);?>">
							<img src="<?php echo site_url('/thumbs/timthumb.php?src=../files/productos/'.$p->id.'/'.$p->fotografias_id.'.jpg&s=228&t='.time())?>" />&nbsp;
						</a>
					</div>
					<a href="<?php echo site_url('frontends/productos/'.$p->id);?>"><div class="descripcion">&#8227; <?php echo $p->nombre;?></div></a>
				</div>
			<?php endforeach;?>
			</div>
		</div>
	<?php endif;?>
</div>
<?php $this->load->view('frontend/layout/footer');?>