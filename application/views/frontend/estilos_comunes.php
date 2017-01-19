<?php $this->load->view('frontend/layout/header');?>
	<div class="container bg-blanco">
		<div class="row ">
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
			
				<p class="front_tituloSeccion">Titulo de secci&oacute;n</p>
				
				<div class="row">
					<div class="col-lg-12 col-sm-6">
						<div class="front_subtituloSeccion">
							<p class="">Subtitulo de Secci&oacute;n</p>
						</div>
					</div>
				</div>
				
				<p class="front_tituloProducto">
					Titulo de producto, tambien para el panel
				</p>
				
				<p class="front_cuerpoTexto">
					Lorem ipsum dolor sit amet, sea ad iudico graece ullamcorper. Vix eu illum essent. Mei accusata dissentiunt id, sea zril sadipscing no. Veri elitr animal ea ius, eum diam porro dicit ex, eos saepe mediocrem ut. Te nam utinam intellegam quaerendum, an his saperet verterem honestatis.
				</p>
				
			</div>
			<div class="col-lg-2 col-md-6 col-sm-6 col-xs-6">
				<img class="img-thumbnail front_thumbnail" alt="" src="<?php echo site_url('img/bc/no_image.jpg')?>">
			</div>
			<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
				<div class="table-responsive front_table">
					<table class="table table-hover">
						<thead>
							<tr>
								<td>titulo 1</td>
								<td>titulo 2</td>
								<td>titulo 3</td>
								<td>titulo 4</td>
								<td>titulo 5</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img class="" alt="" src="<?php echo site_url('img/bc/no_image.jpg')?>">
								</td>
								<td>veniam scripserit</td>
								<td>veniam scripserit</td>
								<td>veniam scripserit</td>
								<td class="front_datoImportante">Dato Importante</td>
							</tr>
							<tr>
								<td>
									<img class="" alt="" src="<?php echo site_url('img/bc/no_image.jpg')?>">
								</td>
								<td>veniam scripserit</td>
								<td>veniam scripserit</td>
								<td>veniam scripserit</td>
								<td class="front_datoImportante">Dato Importante</td>
							</tr>
							<tr>
								<td>
									<img class="" alt="" src="<?php echo site_url('img/bc/no_image.jpg')?>">
								</td>
								<td>veniam scripserit</td>
								<td>veniam scripserit</td>
								<td>veniam scripserit</td>
								<td class="front_datoImportante">Dato Importante</td>
							</tr>
							<tr>
								<td class="tr" colspan="4">Dato</td>
								<td class="front_datoImportante">
									veniam scripserit
								</td>
							</tr>
							<tr>
								<td class="tr" colspan="4">Dato</td>
								<td class="front_datoImportante">
									veniam scripserit
								</td>
							</tr>
							<tr>
								<td class="tr" colspan="4">Dato</td>
								<td class="front_datoImportante">
									veniam scripserit
								</td>
							</tr>
							<tr>
								<td class="tr front_datoImportante" colspan="4">Dato</td>
								<td class="front_datoImportante">
									veniam scripserit
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="panel-group front_panel" id="accordion">
				  <div class="panel panel-default">
				    <div class="panel-heading">
				      <h4 class="panel-title">
				        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
				          Ejemplo de Panel colapsable
				        </a>
				      </h4>
				    </div>
				    <div id="collapseOne" class="panel-collapse collapse in">
				      <div class="panel-body">
				        <div class="row">
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        	<div class="col-lg-3 form-group">
				        		<label>Campo 1</label>
				        		<input class="form-control" type="text">
				        	</div>
				        </div>
				      </div>
				    </div>
				  </div>
				</div>
			</div>
			<div class="col-lg-6 front_btnsWrapper">
				<a class="btn btn-front-default" href="">Boton</a>
				<a class="btn btn-front-primary" href="">Boton</a>
				<a class="front-link" href=""><i class="fa fa-asterisk"></i> Link Pelon</a>
				<a class="front-link-menu" href=""><i class="fa fa-asterisk"></i> Link Pelon de menu</a>
				<div class="row">
					<div class="col-lg-12">
						<div class="color-borde">
							&nbsp;
						</div>
						<div class="color-gris">
							&nbsp;
						</div>
						<div class="color-texto">
							&nbsp;
						</div>
						<div class="color-rojo">
							&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->load->view('frontend/layout/footer');?>