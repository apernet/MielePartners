
<div id="sidebar">
	<div class="w100 auto">
		<h1><?php echo $titulo ;?></h1>
	</div>
	<div class="w100 auto" id="menu_categorias">
	<?php if(!empty($subcategorias)):?>
		<?php foreach ($subcategorias as $sub):?>
			<?php if(empty($sub['subcategoria'])):?>
			<span class="categoria"><a href="<?php echo site_url('frontends/categorias/'.$sub['id']); ?>"><?php echo$sub['nombre'];?></a></span>
			<?php else:?>
				<span class="categoria"><?php echo$sub['nombre'];?></span>
				<?php if(!empty($sub['subcategoria'])):?>
				<ul>
					<?php foreach($sub['subcategoria'] as $s):?>
						<li><a href="<?php echo site_url('frontends/categorias/'.$s['id']); ?>"><?php echo $s['nombre'];?></a></li>
					<?php endforeach;?>
				</ul>	
				<?php endif;?>
			<?php endif;?>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
