	<div class="panel-group front_panel" id="accordion5">
	  		<div class="panel panel-default">
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion5" href="#collapseFive">
			          Datos de Facturaci&oacute;n
			        </a>
			      </h4>
			    </div>
			    <div id="collapseFive" class="panel-collapse collapse in">
			      	<div class="panel-body">
				        <div class="row">
				            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Facturaci&oacute;n para:</label>
				        		<select name="tipo_persona_id" id="tipo_persona_id" class="form-control <?php echo form_error('tipo_persona_id')?'rojo':'';?>">
	                				<option value=""><?php echo $this->config->item('empty_select');?></option>
	                				<?php foreach(catalogo('tipo_persona_fiscal',FALSE) as $k=>$v):?>
	                				<option value="<?php echo $k; ?>" <?php echo $k == @$r['tipo_persona_id']?'selected':''; ?> ><?php echo $v; ?></option>
	                				<?php endforeach;?>
	                			</select>
	                			<?php echo form_error('tipo_persona_id');?>
				        	</div>
				            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Nombre o Raz&oacute;n social:</label>
				        		<input class="form-control <?php echo form_error('razon_social')?'rojo':'';?>" type="text" id="razon_social"  name="razon_social"  
				        			value="<?php echo set_value('razon_social',@$r['razon_social']); ?>"/>
								<?php echo form_error('razon_social');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>RFC:</label>
								<input class="form-control <?php echo form_error('rfc')?'rojo':'';?>" type="text" id="rfc" name="rfc"  
									value="<?php echo @$r['rfc']?set_value('rfc',@$r['rfc']):'XAXX010101000'; ?>" />
								<?php echo form_error('rfc');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	        	        		<label><span class="req">*</span>Correo electr&oacute;nico:</label>
	    					    <input class="form-control <?php echo form_error('email')?'rojo':'';?>" id="email"  name="email"  
	    					    	value="<?php echo set_value('email',@$r['email']); ?>" readonly/>
	        					<?php echo form_error('email');?>
	        	        	</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Estado:</label>
								<select class="form-control <?php echo form_error('estado')?'rojo':'';?>" name="estado" id="estado" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($estados as $e):?>
									<option value="<?php echo $e; ?>" <?php echo ($e==@$r['estado'])?'selected="selected"':''; ?>><?php echo $e; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('estado');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Delegaci&oacute;n o municipio:</label>
								<select class="form-control <?php echo form_error('municipio')?'rojo':'';?>" name="municipio" id="municipio" >
									<option value=""><?php echo $this->config->item('empty_select');?></option>
									<?php foreach($municipios as $m):?>
									<option value="<?php echo $m; ?>" <?php echo ($m == @$r['municipio'])?'selected="selected"':''; ?>><?php echo $m; ?></option>
									<?php endforeach;?>
								</select>
								<?php echo form_error('municipio');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>C&oacute;digo postal:</label>
	        		       		<div class="input-group">
					        		<input class="form-control <?php echo form_error('codigo_postal')?'rojo':'';?>" type="text" id="codigo_postal"  name="codigo_postal" 
					        			value="<?php echo set_value('codigo_postal',@$r['codigo_postal']); ?>"/>
					        		<span class="input-group-addon">
		    					       <a  class="buscar_ref icono_info" title="Buscar direcci&oacute;n en base al c&oacute;digo postal." href="#" id="dir_search"><i class="fa fa-search"></i></a>
		    					   	</span>
								</div>
								<?php echo form_error('codigo_postal');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Colonia:</label>
				        		<div class="input-group">
					        		<input class="form-control <?php echo form_error('asentamiento')?'rojo':'';?>" type="text" id="asentamiento"  name="asentamiento" 
					        		value="<?php echo set_value('asentamiento',@$r['asentamiento']); ?>"/>
					        		<span class="input-group-addon">
		    					       <a  class="buscar_ref icono_info" title="Buscar c&oacute;digo postal en base a la colonia" href="#" id="cp_search"><i class="fa fa-search"></i></a>
		    					   	</span>
	    					   	</div>
								<?php echo form_error('asentamiento');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>Calle:</label>
				        		<input class="form-control <?php echo form_error('calle')?'rojo':'';?>" type="text" id="calle" name="calle"  
				        			value="<?php echo set_value('calle',@$r['calle']); ?>"/>
								<?php echo form_error('calle');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label><span class="req">*</span>N&uacute;mero exterior:</label>
				        		<input class="form-control <?php echo form_error('numero_exterior')?'rojo':'';?>" type="text" id="numero_exterior" name="numero_exterior"  
				        			value="<?php echo set_value('numero_exterior',@$r['numero_exterior']); ?>"/>
								<?php echo form_error('numero_exterior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
				        		<label>N&uacute;mero interior:</label>
				        		<input class="form-control <?php echo form_error('numero_interior')?'rojo':'';?>" type="text" id="numero_interior" name="numero_interior"  
				        		value="<?php echo set_value('numero_interior',@$r['numero_interior']); ?>"/>
								<?php echo form_error('numero_interior');?>
				        	</div>
				        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 form-group">
	        	        		<label>Tel&eacute;fono:</label>
	        					<input class="form-control <?php echo form_error('telefono')?'rojo':'';?>" id="telefono"  name="telefono"  
	        						value="<?php echo set_value('telefono',@$r['telefono']); ?>"/>
	        					<?php echo form_error('telefono');?>
	        	        	</div>
				        </div>
		        	</div>
		        </div>
			</div>
     	</div>