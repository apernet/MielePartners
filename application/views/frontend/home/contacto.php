<?php $this->load->view('frontend/layout/header');?>
<div class="minHeight">
	<div class="row noMargin mt20 mb20">
		<div class="front_subtituloSeccion">
			<p class=""><?php echo $titulo;?></p>
		</div>
	</div>
	<div class=" mt20">
		<div class="col-lg-4 backgroundGeneric">
			<div class="row">
				<div class="col-lg-12">
					<ul class="fa-ul">
						<?php if(INTERNO):?>
						<li>
							<i class="fa-li fa fa-chevron-right fa-lg mt5 text-color-primario"></i>
								<h3 class="no-margin">Comercial</h3><br>
								<span class="front_cuerpoTexto">Maud Bailhache<br>
								maud.bailhache@miele.com.mx<br>
								Tel. 8503-9870 ext. 401</span>
						</li>
						<li class="mt20">
								<span class="front_cuerpoTexto">Elisa Herrera<br>
								elisa.herrera@miele.com.mx<br>
								Tel. 8503-9870 ext. 431</span>
						</li>
						<?php else:?>
						<li>
							<i class="fa-li fa fa-chevron-right fa-lg mt5 text-color-primario"></i>
								<h3 class="no-margin">Comercial</h3><br>
								<span class="front_cuerpoTexto">Paola Mesura<br>
								Ventas<br>
								paola.mesura@miele.com.mx<br>
								Tel. 8503-9870 ext. 199</span>
						</li>
						<li class="mt20">
								<span class="front_cuerpoTexto">Montserrat Badiola<br>
								Own Retail Manager<br>
								montserrat.badiola@miele.com.mx<br>
								Tel. (55) 8503-9870 ext. 441</span>
						</li>
						<?php endif;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('frontend/layout/footer');?>