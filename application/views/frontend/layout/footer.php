</div>
</div>
<div class="front_footer">
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">
			<ul class="nav nav-pills">
				<li><a class="front-link footerAdver" href="<?php echo site_url('files/terminos/aviso_privacidad.docx');?>">Aviso de Privacidad</a></li>
			</ul>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 hidden-xs">
			<ul class="nav nav-pills">
				<li><a class="front-link footerAdver"> © M&eacute;xico <?php echo date('Y') ?>. Todos los Derechos Reservados.</a></li>
			</ul>
		</div>
		<div class="col-lg-1 col-md-3 col-sm-3 hidden-xs">
			<div style="margin-top: -15px;">
				<ul class="nav nav-pills" style="text-align: center;">
					<li style="width: 100%;">
						<a class="front-link footerAdver">
							<img style="max-width: 130px;" src="<?php echo site_url('img/layout/sitio_seguro.png');?>"/>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-6 col-md-4 col-sm-4 hidden-xs">
			<ul class="nav nav-pills pull-right">
			  <?php if($this->session->userdata('logged')):?>
			  <li><a <?php echo !INTERNO?'href="'.site_url('frontends/mis_datos').'"':'';?>class="front-link footPadd"><i class="fa fa-user footIcon fa-2x"></i><?php echo $usuario;?></a></li>
			  <?php endif;?>
			  <?php if(INTERNO):?>
			  <li><a class="front-link footPadd" href="<?php echo site_url('frontends/cotizacion_nueva');?>"><i class="fa fa-bolt fa-2x footIcon"></i> Nueva Cotizaci&oacute;n</a></li>
			  <li><a class="front-link footPadd" href="<?php echo site_url('main/index');?>"><i class="fa fa-share-square-o fa-2x footIcon"></i>Admin</a></li>
			  <?php endif;?>
			  <?php if($this->session->userdata('logged') && !INTERNO):?>
				  <li>
                      <a class="front-link footPadd" href="<?php echo site_url('frontends/mis_pedidos');?>">
                          <i class="fa fa-cube footIcon fa-2x"></i>
                          Mis pedidos
                      </a>
                  </li>
			  <?php endif;?>
              <li><a class="front-link footPadd" href="<?php echo site_url('frontends/comentarios');?>"><i class="fa fa-comments-o footIcon fa-2x"></i>Cont&aacute;ctanos</a></li>
			  <li><a class="front-link footPadd" href="<?php echo site_url('frontends/contacto');?>"><i class="fa fa-headphones footIcon fa-2x"></i>Atenci&oacute;n al cliente</a></li>
			  <li><a class="front-link tc footPadd" href="<?php echo site_url('frontends/cotizacion');?>"><i class="fa fa-shopping-cart footIcon fa-2x"></i>  <?php echo $num_productos;?></a></li>
			  <?php if($this->session->userdata('logged')):?>
              	<li><a class="footPadd" title="Cerrar sesi&oacute;n" href="<?php echo INTERNO? site_url('main/logout') : site_url('frontends/logout');?>"><i class="fa fa-power-off fa-lg text-color-primario"></i></a></li>
              <?php else:?>
            	<li><a class="footPadd" title="Iniciar sesi&oacute;n" href="<?php echo INTERNO? site_url('main/login') : site_url('frontends/autenticacion');?>"><i class="fa fa-power-off fa-lg text-color-primario"></i></a></li>
              <?php endif;?>
			</ul>
		</div>
		<div class="hidden-lg hidden-md hidden-sm col-xs-12">
			<ul class="xtrasmall nav nav-pills" style="width:100%;">
			  <li><p class="front-link" href=""><?php echo $usuario;?></p></li>
			  <?php if(INTERNO):?>
			  <li><p class="front-link" href="<?php echo site_url('frontends/cotizacion_nueva');?>"><i class="fa fa-bolt"></i> Nueva Cotizaci&oacute;n</p></li>
			  <li><p class="front-link" href="<?php echo site_url('main/index');?>">Admin</p></li>
			  <?php endif;?>
			  <?php if($this->session->userdata('logged') && !INTERNO):?>
				  <!-- <li><a class="front-link" href="<?php echo site_url('main/index');?>">Mis datos</a></li> -->
				  <li><a class="front-link" href="<?php echo site_url('main/index');?>">Mis pedidos</a></li>
			  <?php endif;?>
			  <li><p class="front-link" href="<?php echo site_url('frontends/contacto');?>"><i class="fa fa-phone"></i> Contacto</p></li>
			  <li><p class="front-link" href="<?php echo site_url('frontends/cotizacion');?>"><i class="fa fa-shopping-cart fa-lg"></i>  <?php echo $num_productos;?></p></li>
              <?php if($this->session->userdata('logged')):?>
              	<li><a title="Cerrar sesi&oacute;n" href="<?php echo INTERNO? site_url('main/logout') : site_url('frontends/logout');?>"><i class="fa fa-power-off fa-lg text-color-primario"></i></a></li>
              <?php else:?>
            	<li><a title="Iniciar sesi&oacute;n" href="<?php echo INTERNO? site_url('main/login') : site_url('frontends/autenticacion');?>"><i class="fa fa-power-off fa-lg text-color-primario"></i></a></li>
              <?php endif;?>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
<!--
$(function(){
	<?php if($mesa_logged):?>
	$('.check').live('click',function(e){
		e.preventDefault();
		var span=$(this).children('span');

		var producto_id=this.id.split('_');
		producto_id=producto_id[1];

		if(span.hasClass('mark'))
		{
			var theUrl='<?php echo site_url('frontends/mesa_set_producto/0');?>/'+producto_id;
			$.ajax({
				  url: theUrl,
				  success: function(data){
				  	if(data.res)
						span.removeClass('mark');
				  },
				  dataType:'json'
			});
		}
		else
		{
			var theUrl='<?php echo site_url('frontends/mesa_set_producto/1');?>/'+producto_id;
			$.ajax({
				  url: theUrl,
				  success: function(data){
					if(data.res)
						span.addClass('mark');
				  },
				  dataType:'json'
			});
		}
	});
	<?php endif;?>

	$('.seleccionado').children('a').children('i').addClass('fa-spinner fa-spin')
	$('.seleccionado').prevAll().children('a').addClass('btn-front-primary');
	$('.seleccionado').prevAll().children('a').removeClass('btn-front-cotizacion');
	$('.seleccionado').prevAll().children('a').children('i').removeClass('fa-spinner fa-spin marginIconR');
	$('.seleccionado').prevAll().children('a').children('i').addClass('fa-check marginIcon');

	$('div.promocion_categoria').mouseenter(function(){
		href = $(this).find('a.link_categoria').attr('href');
		$(this).find('a.link_categoria').attr('href','#');
		$(this).find('a.link_categoria').click(function(e){e.preventDefault();});

        $(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('trasintion_5');
        $(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').addClass('fade');
        $(this).find('.tr_foot_promocion').addClass('foot_promocion');
        $(this).find('.tr_top_promocion').addClass('top_promocion');
	});

	$('div.promocion_categoria').mouseleave(function(){
		$(this).removeClass('heightAcc-large');
		$(this).find('a.link_categoria').attr('href',href);

        $(this).find('.banda_promo, .info-producto .modelo, .info-producto .producto').removeClass('fade');
        $(this).find('.tr_foot_promocion').removeClass('foot_promocion');
        $(this).find('.tr_top_promocion').removeClass('top_promocion');
	});
});
//-->
</script>

</body>
</html>