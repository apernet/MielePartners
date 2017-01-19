<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div class="">
	<div class="col-lg-12">
		<div class="row dashboard-head">
			<div class="col-lg-2 dashboard-user">
				<p>Bienvenido, <strong><?php echo $this->session->userdata('nombre'); ?></strong></p>
			</div>
			<?php if($this->session->userdata('cuentas_id')==1 && $this->session->userdata('grupos_id')==1):?>
			<div class="col-lg-2 pull-right dashboard-user-cambio">
				<a title="Cambiar de usuario" class="bc_fancybox pull-right btn btn-transparent" href="<?php echo site_url('main/usuarios_cambiar');?>"><i class="fa fa-exchange"></i>   Cambiar usuario</a>
			</div>
			<?php else: ?>
			&nbsp;
			<?php endif; ?>
		</div>
		<div class="row mt10 mb10">
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
			  &nbsp;
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
				<a class="btn btn-dashboard btn-lg btn-block" href="<?php echo site_url('comisiones/index');?>"><i class="fa fa-check"></i>  Comisiones</a>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 border-left">
				<a class="btn btn-dashboard btn-lg btn-block" href="<?php echo site_url('referidos/index');?>"><i class="fa fa-tags"></i>  Referidos</a>
			</div>
			<!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 border-left">
				<a class="btn btn-dashboard btn-lg btn-block" href="<?php echo site_url('accesorios/agregar');?>"><i class="fa fa-archive"></i>  Agregar Accesorio</a>
			</div> -->
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 border-left">
				<a class="btn btn-dashboard btn-lg btn-block" href="<?php echo site_url('cotizaciones/index');?>"><i class="fa fa-suitcase"></i>  Cotizaciones</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 formulario-head">
				<div class="row">
					<div class="col-lg-11 col-sm-10 col-xs-9">
						<h4>&Uacute;ltimas noticias</h4>
					</div>
				</div>
			</div>
			<?php if(!empty($noticias)):?>
			<?php $i=1; ?>
			<?php foreach($noticias as $n):?>
			<div class="col-lg-1 tc">
				<i class="fa fa-file-text fa-5x text-color-back"></i>
			</div>
			<div class="col-lg-11" <?php echo $i%2==0?'class="noticias_altrow"':'';?>>	
				<div><span class="noticias_subtitulo"><?php echo $n->titulo; ?></span> <span class="noticias_fecha"><?php ver_fecha($n->fecha);?></span></div>
				<div class="noticias_contenido"><?php echo $n->contenido; ?></div>
				<br/>
			</div>
			<?php $i++;?>
			<?php endforeach;?>
			<?php else: ?>
				<div class="msg sin_resultados">No se ha encontrado ninguna noticia</div>
			<?php endif;?>
		</div>
	</div>
</div>
<?php if(!empty($noticias_inicio)):?>
<script type="text/javascript">
<!--
$(function(){
	/*$('#noticias_inicio').show();
	$.fancybox({
		<?php //if($this->config->item('bloquear_noticias')):?>
		onComplete: function() {
	    	$('#cboxClose').slideUp(300).delay(5000).fadeIn(400);
		},
		<?php //endif; ?>
		hideOnOverlayClick: false,
		hideOnContentClick: false,
		enableEscapeButton: false,
		//type: inline,
		href:"#noticias_inicio",
		onCleanup: function(){
			$('#noticias_inicio').remove();
		}
	});*/
});
//-->
</script>
<?php endif;?>
<!-- SCRIPT PARA EL REPORTE INICIAL -->

<script type="text/javascript">
<!--
$(function(){
	
});
//-->
</script>

<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>