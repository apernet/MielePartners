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
            <div class="col-lg-3 form-group">
                <label>Tipo:</label>
                <select class="form-control"  name="tabla" id="tabla">
                    <option value=""><?php echo $this->config->item('empty_select');?></option>
                    <?php foreach($calificacion_tablas as $k=>$v): ?>
                        <option value="<?php echo $v; ?>" <?php echo (isset($cond['tabla']))?($v == @$cond['tabla']?'selected="selected"':''):" "; ?>><?php echo $v; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-lg-3 form-group">
                <label>SKU:</label>
                <input type="text" disabled="disabled" class="form-control disabled" id="sku" name="sku" value="<?php echo @$cond['sku']; ?>" />
            </div>
            <div class="col-lg-3 form-group">
                <label>Modelo:</label>
                <input type="text" disabled="disabled" class="form-control disabled" id="modelo" name="modelo" value="<?php echo @$cond['modelo']; ?>" />
            </div>
        </div>
    </div>
    <div class="col-lg-12 barra-btn">
        <div class="row">
            <button type="submit" class="btn btn-primary pull-right"">Buscar</button>
            <input type="reset" class="btn btn-default pull-right bc_clear" value="Limpiar"/>
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
                    <td class="data_first center">Tipo</td>
                    <td class="center">SKU</td>
                    <td class="center">Modelo</td>
                    <td align="center">Nombre</td>
                    <td align="center">Categor&iacute;a de Producto</td>
                    <td align="center">1 estrella</td>
                    <td align="center">2 estrellas</td>
                    <td align="center">3 estrellas</td>
                    <td align="center">4 estrellas</td>
                    <td align="center">5 estrellas</td>
                    <td align="center">Promedio</td>
                    <td align="center">No. de Comentarios</td>
                    <td align="center">Vendidos</td>
                    <td align="center">Calificados</td>
                    <td align="center">No. de Calificados</td>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($r as $elemento):?>
                    <tr <?php echo ($i%2==0)?'class="altrow"':''?>>
                        <td><?php echo mayuscula($elemento->tabla); ?></td>
                        <td><?php echo $elemento->sku; ?></td>
                        <td><?php echo !empty($elemento->nombre_producto)? $elemento->nombre_producto : $elemento->nombre_accesorio; ?></td>
                        <td><?php echo $elemento->modelo; ?></td>
                        <td><?php echo !empty($elemento->categoria)? $elemento->categoria : 'NO APLICA'; ?></td>
                        <td align="center"><?php echo $elemento->calificacion1; ?></td>
                        <td align="center"><?php echo $elemento->calificacion2; ?></td>
                        <td align="center"><?php echo $elemento->calificacion3; ?></td>
                        <td align="center"><?php echo $elemento->calificacion4; ?></td>
                        <td align="center"><?php echo $elemento->calificacion5; ?></td>
                        <td align="center"><?php echo !empty($elemento->promedio) ? $elemento->promedio : 0; ?></td>
                        <td align="center"><?php echo $elemento->comentarios; ?></td>
                        <td align="center"><?php echo $elemento->vendidos; ?></td>
                        <td align="center"><?php echo $elemento->calificados; ?></td>
                        <td align="center"><?php echo $elemento->no_calificados; ?></td>
                    </tr>
                    <?php $i++; endforeach;?>
            </tbody>
        </table>
    </div>
    </div>
<?php endif;?>
<script type="text/javascript">
    $('#exportar').click(function(e){
        e.preventDefault();
        var action=$('#form').attr('action');
        $('#form').attr('action',BASE_URL+'calificaciones/reporte_exportar_elementos');
        $('#form').submit();
        $('#form').attr('action',action);
    });
    $('#tabla').on( "change", function(e){
        var val = $(this).val();
        if(val){
            $('.disabled').prop('disabled', false);
        } else{
            $('.disabled').prop('disabled', true);
        }

    });
</script>
<?php if(!is_ajax()){ $this->load->view('layout/footer'); } ?>