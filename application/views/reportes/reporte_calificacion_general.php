<?php if(!is_ajax()){ $this->load->view('layout/header'); } ?>
<div class="col-lg-12 formulario-head">
    <div class="row">
        <div class="col-lg-11 col-sm-10 col-xs-9">
            <h4><?php echo $titulo;?></h4>
        </div>
        <?php if($exportar):?>
            <div class="col-lg-1 col-sm-2 col-xs-3">
                <a class="btn_default_admin btn btn-xs" role="button" id="exportar" href="#" id="exportar">Exportar</a>
            </div>
        <?php endif;?>
    </div>
</div>

<form role="form" action="<?php echo site_url(uri_string());?>" method="post" id="form" name="form">
    <div class=" col-lg-12">
        <div class="row">
            <div>
                <input type="hidden" class="form-control" id="table" name="table" value="<?php echo $tabla;?>"/>
            </div>

            <?php if($tabla == 'accesorios'):?>
                <div class="col-lg-3 form-group">
                    <label>Accesorio:</label>
                    <select class="form-control"  name="accesorios_id" id="accesorios_id">
                        <option value=""><?php echo $this->config->item('empty_select'); ?></option>
                        <?php foreach($accesorios as $k=>$v):?>
                            <option value="<?php echo $k; ?>" <?php echo ($k == @$cond['accesorios_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            <?php endif;?>
            <?php if($tabla == 'productos'):?>
                <div class="col-lg-3 form-group">
                    <label>Producto:</label>
                    <select class="form-control"  name="producto_id" id="producto_id">
                        <option value=""><?php echo $this->config->item('empty_select'); ?></option>
                        <?php foreach($productos as $k=>$v):?>
                            <option value="<?php echo $k; ?>" <?php echo ($k == @$cond['producto_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <label>Categor&iacute;a:</label>
                    <select class="form-control"  name="categorias_id" id="categrias_id">
                        <option value=""><?php echo $this->config->item('empty_select'); ?></option>
                        <?php foreach($categorias as $k=>$v):?>
                            <option value="<?php echo $k; ?>" <?php echo ($k == @$cond['categorias_id'])?'selected="selected"':''; ?>> <?php echo $v; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="col-lg-3 form-group">
                <label>Folio orden de compra:</label>
                <input type="text" class="form-control" id="folio_compra" name="folio_compra" value="<?php echo @$cond['folio_compra']; ?>" />
            </div>
            <div class="col-lg-3 form-group">
                <label>SKU:</label>
                <input type="text" class="form-control" id="sku" name="sku" value="<?php echo @$cond['sku']; ?>" />
            </div>
            <div class="col-lg-3 form-group">
                <label>Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo @$cond['modelo']; ?>" />
            </div>
            <div class="col-lg-3 form-group">
                <label>Fecha inicial calificaci&oacute;n:</label>
                <input type="hidden" class="form-control" id="fecha_inicial_alt" name="fecha_inicial" value="<?php echo @$cond['fecha_inicial']; ?>" readonly="readonly"/>
                <input type="text" class="form-control fecha" id="fecha_inicial" value="<?php echo get_fecha(@$cond['fecha_inicial']); ?>"/>
            </div>
            <div class="col-lg-3 form-group">
                <label>Fecha final calificaci&oacute;n:</label>
                <input type="hidden" class="form-control" id="fecha_final_alt" name="fecha_final" value="<?php echo @$cond['fecha_final']; ?>" readonly="readonly"/>
                <input type="text" class="form-control fecha" id="fecha_final" value="<?php echo get_fecha(@$cond['fecha_final']); ?>"/>
            </div>
            <div class="col-lg-3 form-group">
                <label>Calificaci&oacute;n:</label>
                <input type="text" class="form-control" id="calificacion" name="calificacion" value="<?php echo @$cond['calificacion']; ?>" />
            </div>
            <div class="col-lg-3 form-group">
                <label>Comentario:</label>
                <input type="text" class="form-control" id="comentario" name="comentario" value="<?php echo @$cond['comentario']; ?>" />
            </div>
            <div class="col-lg-3 form-group">
                <label>Comentario Oculto:</label>
                <select class="form-control"  name="ocultar_comentario" id="ocultar_comentario">
                    <option value=""><?php echo $this->config->item('empty_select');?></option>
                    <?php foreach($ocultar_comentario as $k=>$v): ?>
                        <option value="<?php echo $k; ?>" <?php echo (isset($cond['ocultar_comentario']))?($k == @$cond['ocultar_comentario']?'selected="selected"':''):" "; ?>><?php echo $v; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-lg-12 barra-btn">
        <div class="row">
            <button type="submit" class="btn btn-primary pull-right">Buscar</button>
            <input type="reset" data-table="<?php echo $tabla; ?>" class="btn btn-default pull-right reset" value="Limpiar"/>
        </div>
    </div>
</form>

<?php if($total==0): ?>
    <div class="msg sin_resultados">No se ha encontrado ning&uacute;n registro.</div>
<?php else: ?>
    <div class="col-lg-12">
    <div class="table-responsive">
        <table class="table table-hover texto_tabla">
            <tfoot>
                <tr>
                    <td colspan="15">
                        <div class="col-lg-4 pull-left">
                            <?php echo $paginador; ?>
                        </div>
                        <div class="col-lg-4 pull-right">
                            <p class="pull-right"><?php echo $total>1?'Se encontraron '.$total.' resultados.':'Se encontr&oacute; 1 resultado.'?></p>
                        </div>
                    </td>
                </tr>
            </tfoot>
            <thead>
                <tr>
                    <td class="data_first center">Orden de Compra</td>
                    <td align="center">SKU</td>
                    <td align="center">Modelo</td>
                    <?php if($tabla == 'accesorios'): ?>
                        <td align="center">Accesorio</td>
                    <?php endif;?>
                    <?php if($tabla == 'productos'): ?>
                        <td align="center">Producto</td>
                        <td align="center">Categor&iacute;a de Producto</td>
                    <?php endif;?>
                    <td align="center">Calificaci&oacute;n</td>
                    <td align="center">Tel&eacute;fono</td>
                    <td align="center">Email</td>
                    <td align="center">Comentarios</td>
                    <td align="center">Fecha de Calificaci&oacute;n</td>
                    <?php if($puede_eliminar):?>
                        <td align="center">Acci&oacute;n</td>
                    <?php endif;?>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($r as $ro):?>
                    <tr <?php echo ($i%2==0)?'class="altrow"':''?>>
                        <td><?php echo $ro->folio_compra; ?></td>
                        <td><?php echo $ro->sku; ?></td>
                        <td><?php echo $ro->modelo; ?></td>
                        <?php if($tabla == 'accesorios'): ?>
                            <td><?php echo $ro->nombre_accesorio; ?></td>
                        <?php endif;?>
                        <?php if($tabla == 'productos'): ?>
                        <td><?php echo $ro->nombre_producto; ?></td>
                        <td align="center"><?php echo $ro->categoria; ?></td>
                        <?php endif; ?>
                        <td align="center"><?php echo $ro->calificacion; ?></td>
                        <td align="center"><?php echo $ro->telefono; ?></td>
                        <td><?php echo $ro->email; ?></td>
                        <td><?php echo $ro->comentario; ?></td>
                        <td align="center"><?php echo ver_fecha_hora($ro->modified); ?></td>
                        <?php if($puede_eliminar): ?>
                            <td align="center">
                                <a href="<?php echo site_url('calificaciones/ocultar_comentarios/'.$ro->id.'/'.$tabla.'/'.$ro->ocultar_comentario); ?>" class="view accion accion3"><?php echo ($ro->ocultar_comentario)?'Mostrar':'Ocultar'; ?></a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php $i++; endforeach;?>
            </tbody>
        </table>
    </div>
    </div>
<?php endif;?>

<script type="text/javascript">
    $(function(){
        convertir_campos();
    });
    $('.reset').click(function(e){
        e.preventDefault();
        var table = $(this).attr('data-table');
        var form=$(this).parents('form:first');
        form.clearForm();
        $("#table").val(table);
    });
    $('#exportar').click(function(e){
        e.preventDefault();
        var action=$('#form').attr('action');
        $('#form').attr('action',BASE_URL+'calificaciones/reporte_exportar_general');
        $('#form').submit();
        $('#form').attr('action',action);
    });
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>