<?php
require_once('base.php');
class Calificacion extends Base
{

    public function __construct()
    {
        parent::__construct();
    }

    var $ocultar_comentario = array(
        '1' =>'Mostrar',
        '0' =>'Ocultar',
    );

    var $tabla = array('accesorios','productos');

    var $join_filtros = array(
        'cotizaciones' => array(
            'table' => 'cotizaciones as c',
            'condition' => 'c.id = calificaciones.cotizaciones_id',
            'type' => 'left outer'
        ),
        'productos' => array(
            'table' => 'productos as p',
            'condition' => 'p.id = calificaciones.elementos_id',
            'type' => 'left outer'
        ),
        'accesorios' => array(
            'table' => 'accesorios as a',
            'condition' => 'a.id = calificaciones.elementos_id',
            'type' => 'left outer'
        )
    );

    private function pconditions($cond, $tabla=FALSE)
    {

        $join = array();
        // FILTRO PRODUCTO
        if (! empty($cond ['producto_id']))
            $this->db->where('elementos_id', $cond ['producto_id']);

        if (! empty($cond ['ocultar_comentario']))
            $this->db->where('ocultar_comentario', $cond ['ocultar_comentario']);

        // FILTRO CALIFICACION
        if (! empty($cond ['calificacion']))
            $this->db->where('calificacion', $cond ['calificacion']);

        // FILTRO COMENTARIO
        if (! empty($cond ['comentario']))
            $this->db->like('comentario', $cond['comentario']);

        // FILTRO FECHA
        if (! empty($cond ['fecha_inicial']) || ! empty($cond ['fecha_final']))
        {
            if (! empty($cond ['fecha_inicial']) && empty($cond ['fecha_final']))
                $this->db->where("DATE_FORMAT(modified,'%Y-%m-%d') >= '{$cond['fecha_inicial']}'");
            if (empty($cond ['fecha_inicial']) && ! empty($cond ['fecha_final']))
                $this->db->where("DATE_FORMAT(modified,'%Y-%m-%d') <= '{$cond['fecha_final']}'");
            if (! empty($cond ['fecha_inicial']) && ! empty($cond ['fecha_final']))
            {
                $this->db->where("DATE_FORMAT(modified,'%Y-%m-%d') >= '{$cond['fecha_inicial']}'");
                $this->db->where("DATE_FORMAT(modified,'%Y-%m-%d') <= '{$cond['fecha_final']}'");
            }
        }

        if (! empty($cond ['folio_compra'])){
            $this->db->like('folio_compra', $cond ['folio_compra']);
            $join[]='cotizaciones';
        }

        if (! empty($cond ['sku'])){
            if($tabla == 'productos'){
                $this->db->like('p.item', $cond ['sku']);
                $join[]='productos';
            } else {
                $this->db->like('a.item', $cond ['sku']);
                $join[]='accesorios';
            }

        }

        if (! empty($cond ['modelo'])){
            if($tabla == 'productos'){
                $this->db->like('p.modelo', $cond ['modelo']);
                $join[]='productos';
            } else {
                $this->db->like('a.modelo', $cond ['modelo']);
                $join[]='accesorios';
            }

        }

        // FILTRO CATEGORIA
        if (! empty($cond ['categorias_id'])) {
            $this->db->where('p.categorias_id', $cond ['categorias_id']);
            $join[]='productos';
        }

        if (! empty($cond ['accesorios_id'])){
            $this->db->where('a.id', $cond ['accesorios_id']);
            $join[]='accesorios';
        }

        $join_filtros = $this->join_filtros;

        // SOLO LAS QUE NECESITA
        $join = array_unique($join);
        foreach($join as $j)
        {
            $jfs = $join_filtros[$j];
            $this->db->join($jfs['table'], $jfs['condition'], $jfs['type']);
        }

    }

    public function mail_calificacion($cotizaciones_id){

        $datos['r']=$this->base->read('cotizaciones',$cotizaciones_id,TRUE);
        $datos['fecha_compra']=$datos['r']['modified'];

        $datos['hash'] = sha1($cotizaciones_id.$datos['r']['created']);
        $datos['cotizacion_id']=$cotizaciones_id;
        $this->db->where('id', $cotizaciones_id);
        $datos['externo']=TRUE;
        $datos['cotizaciones'] = $this->db->get('cotizaciones')->row();
        if($datos['cotizaciones']->status_id == 4)
        {

            $CI = &get_instance();
            $CI->load->Model('Cotizacion');
            $venta_directa = $this->Cotizacion->es_venta_directa($datos['cotizaciones']->cuentas_id);

            // si es venta interna se envia a los datos del comprador
            if($venta_directa && !empty($datos['cotizaciones']->recibo_pago_orden))
            {
                $datos['cotizaciones']->email=$datos['cotizaciones']->email_comprador;
                $datos['nombre']=$datos['cotizaciones']->nombre_comprador.' '.$datos['cotizaciones']->paterno_comprador;
            }else if(!$venta_directa && !empty($datos['cotizaciones']->folio) && !empty($datos['cotizaciones']->recibo_pago_orden) && !empty($datos['cotizaciones']->orden_firmada_orden))
            {
                $datos['cotizaciones']->email=$datos['cotizaciones']->email_comprador;
                $datos['nombre']=$datos['cotizaciones']->nombre_comprador.' '.$datos['cotizaciones']->paterno_comprador;
            }else// si es venta externa  se envian a los datos del usuario
            {
                $usuario_data = $this->base->get_datos('nombre, apellido_paterno,email','usuarios',array('id'=>$datos['cotizaciones']->usuario_id));
                $datos['cotizaciones']->email=$usuario_data->email;
                $datos['nombre']=$usuario_data->nombre.' '.$usuario_data->apellido_paterno;
            }

            $this->load->library('email');
            $email_sax = $this->config->item('email');
            $this->email->from($email_sax [0], $email_sax [1]);


            $this->email->to($datos['cotizaciones']->email);


            $bcc = $this->config->item('mail_calificacion_compra');
            if (! empty($bcc))
                $this->email->bcc($bcc);

            $this->email->subject("Experiencia con sus equipos Miele");
            $mensaje = $this->load->view('email/calificacion', $datos, true);

            $this->email->message($mensaje);
            $this->email->send();
            if ($this->config->item('debug_mail')) // DEBUG DE MAIL
            {
                debug($this->email->print_debugger(), null, 1);
            }


            $data_enviado = array(
                'id'=>$this->base->get_dato('id','calificaciones_email',array('cotizaciones_id'=>$cotizaciones_id)),
                'enviado' => 1
            );
            parent::guarda('calificaciones_email', $data_enviado);

            return TRUE;
        }
    }

    /**
     * Funcion para guardar el registro para controlar el envio del correo.
    **/
    public function set_calificacion_mail_productos($data)
    {
        $r = new stdClass();
        $data_calificaciones_email = array(
            'cotizaciones_id' => $data->id,
            'fecha_compra' => $data->entrega_fecha_tentativa,
            'fecha_envio' => date('Y-m-d', strtotime ('+30 days' ,strtotime ($data->entrega_fecha_tentativa))),
            'enviado' => 0,
            'concluido' => 0
        );
        parent::guarda('calificaciones_email', $data_calificaciones_email);

        $this->db->where('cotizaciones_id', $data->id);
        $this->db->where('eliminado', 0);
        $rca = $this->db->get('cotizaciones_accesorios')->result();
        if(!empty($rca))
        {
            foreach($rca as $r){
                $data_calificaciones_accesorios = array(
                    'cotizaciones_id' => $r->cotizaciones_id,
                    'elementos_id' => $r->accesorios_id,
                    'tabla' => 'accesorios',
                    'calificado' => 0

                );
                parent::guarda('calificaciones', $data_calificaciones_accesorios);
            }
        }

        $this->db->where('cotizaciones_id', $data->id);
        $this->db->where('eliminado', 0);
        $rcp = $this->db->get('cotizaciones_productos')->result();
        if(!empty($rcp))
        {
            foreach($rcp as $r){
                $data_calificaciones_productos = array(
                    'cotizaciones_id' => $r->cotizaciones_id,
                    'elementos_id' => $r->productos_id,
                    'tabla' => 'productos',
                    'calificado' => 0
                );
                parent::guarda('calificaciones', $data_calificaciones_productos);
            }
        }
        $r->exito = TRUE;
        $r->mensaje = "Se han guardado correctamente los datos.";
        return $r;
    }

    /**
     * Funcion para recuperar datos para enviar con el cron.
     **/
    public function get_mail_calificacion()
    {
        $r = new stdClass();
        $fecha = date('Y-m-d');
        $this->db->where('enviado', 0);
        $this->db->where('concluido', 0);
        $this->db->where('fecha_envio', $fecha);
        $res = $this->db->get('calificaciones_email')->result();

        foreach($res as $r) {
            $rs = $this->mail_calificacion($r->cotizaciones_id);
            if(!empty($rs))
            {
                $data_update_calificaciones_email = array(
                    'id' => $r->id,
                    'enviado' => 1
                );
                parent::guarda('calificaciones_email', $data_update_calificaciones_email);
                $r->exito = TRUE;
                $r->mensaje = "Se ha enviado correctamente el correo al cliente para calificar los productos.";
            } else {
                $r->exito = FALSE;
                $r->mensaje = "No se ha enviado correctamente el correo al cliente para calificar los productos.";
            }
        }
        return $r;
    }

    /**
     * Funcion para guardar datos de la calificación de los productos.
     **/
    public function set_calificacion($id, $data)
    {
        $this->db->where('id',$data['cotizacion_id']);
        $cotizacion = $this->db->get('cotizaciones')->row();

        if(!empty($cotizacion->folio) && !empty($cotizacion->recibo_pago_orden) && !empty($cotizacion->orden_firmada_orden))
        {
            $cotizacion =$this->base->get_datos('email_comprador as email, telefono_comprador as telefono','cotizaciones',array('id'=>$data['cotizacion_id']));
        } else {
            $cotizacion =$this->base->get_datos('email, telefono_particular as telefono','cotizaciones',array('id'=>$data['cotizacion_id']));
        }

        $data_contacto = array(
            'id' => $id,
            'calificacion' => $data['calificacion'],
            'telefono' => $cotizacion->telefono,
            'email' => $cotizacion->email,
            'comentario' => $data['comentario'],
            'calificado' => 1
        );

        parent::guarda('calificaciones', $data_contacto);
        return TRUE;
    }

    /**
     * Funcion para generar reporte general.
     **/
    public function reporte_general($cond, $limit=FALSE, $offset=FALSE, $tabla)
    {
        $this->pconditions($cond, $tabla);
        $this->db->where('calificado',1);
        $this->db->where('tabla', $tabla);
        if(count($cond) == 1)
            $this->db->order_by('modified','DESC');
        if ($limit)
            $this->db->limit($limit, $offset);
        $result = $this->db->get('calificaciones')->result();

        foreach($result as $r => $v){
            $result[$r]->folio_compra = $this->base->get_dato('folio_compra','cotizaciones',array('id' => $v->cotizaciones_id));
            if($tabla == 'productos')
            {
                $producto = $this->base->get_datos('nombre, modelo, categorias_id, item','productos',array('id' => $v->elementos_id));
                $categoria = $this->base->get_datos('nombre','productos_categorias',array('id' => $producto->categorias_id));

                $result[$r]->sku = $producto->item;
                $result[$r]->modelo = $producto->modelo;
                $result[$r]->categoria = $categoria->nombre;
                $result[$r]->nombre_producto = $producto->nombre;
            } else {
                $accesorio = $this->base->get_datos('nombre, modelo, item','accesorios',array('id' => $v->elementos_id));
                $result[$r]->sku = $accesorio->item;
                $result[$r]->modelo = $accesorio->modelo;
                $result[$r]->nombre_accesorio = $accesorio->nombre;
            }
        }

        return $result;

    }

    /**
     * Funcion para generar reporte de productos.
     **/
    public function reporte_elementos($cond, $limit=FALSE, $offset=FALSE)
    {
        $tabla = isset($cond['tabla'])? $cond['tabla'] : FALSE;
        $this->pconditions($cond, $tabla);
        $this->db->select('SUM(IF(calificacion = 1, 1, 0)) calificacion1,
            SUM(IF(calificacion = 2, 1, 0)) calificacion2,
            SUM(IF(calificacion = 3, 1, 0)) calificacion3,
            SUM(IF(calificacion = 4, 1, 0)) calificacion4,
            SUM(IF(calificacion = 5, 1, 0)) calificacion5,
            avg(calificacion) promedio,
            count(elementos_id) vendidos,
            SUM(IF(calificado = 1, 1, 0)) calificados,
            SUM(IF(calificado = 0 , 1, 0)) no_calificados,
            SUM(IF(comentario IS NOT NULL, 1, 0)) comentarios,
            elementos_id,
            tabla', FALSE);
        $this->db->group_by('elementos_id');
        if(isset($cond['tabla']))
            $this->db->where('tabla',$cond['tabla']);
        if ($limit)
            $this->db->limit($limit, $offset);
        $productos = $this->db->get('calificaciones')->result();

        foreach($productos as $k => $prod) {
            if($prod->tabla == 'productos'){
                $producto = $this->base->get_datos('nombre, modelo, categorias_id, item','productos',array('id' => $prod->elementos_id));
                $categoria = $this->base->get_datos('nombre','productos_categorias',array('id' => $producto->categorias_id));
                $productos[$k]->sku = $producto->item;
                $productos[$k]->modelo = $producto->modelo;
                $productos[$k]->categoria = $categoria->nombre;
                $productos[$k]->nombre_producto = $producto->nombre;
            } else {
                $accesorio = $this->base->get_datos('nombre, modelo, item','accesorios',array('id' => $prod->elementos_id));
                $productos[$k]->sku = $accesorio->item;
                $productos[$k]->modelo = $accesorio->modelo;
                $productos[$k]->nombre_accesorio = $accesorio->nombre;
            }


        }

        return $productos;
    }

    /**
     * Funcion para contar los registros para reporte general.
     **/
    public function count_general($cond, $tabla)
    {
        $rs = $this->reporte_general($cond, 0, 0, $tabla);
        return count($rs);
    }

    /**
     * Funcion para contar los registros para reporte de productos.
     **/
    public function count_elementos($cond)
    {
        $rs = $this->reporte_elementos($cond);
        return count($rs);
    }

    /**
     * Funcion para recuperar productos y accesorios de la cotizacion para calificar
     **/

    public function get_no_calificados($cotizaciones_id)
    {
        $this->db->select('id, elementos_id, tabla, cotizaciones_id' ,FALSE);
        $this->db->where('cotizaciones_id', $cotizaciones_id);
        $this->db->where('calificado', 0);
        $todos = $this->db->get('calificaciones')->result();

        $CI =& get_instance();
        $CI->load->model('Producto');
        $CI->load->model('Accesorio');

        foreach($todos as &$p)
        {
            if($p->tabla == 'productos'){
                $p->nombre=$this->base->get_dato('nombre','productos', array('id'=>$p->elementos_id));
                $p->img_id=$this->Producto->get_imagen_principal($p->elementos_id);
            } else {
                $p->nombre=$this->base->get_dato('nombre','accesorios', array('id'=>$p->elementos_id));
                $p->img_id=$this->Accesorio->get_imagen_principal($p->elementos_id);
            }
        }

        return $todos;
    }
    

    /**
     * Funcion para exportar reporte de productos en excel.
     **/
    public function exportar_elementos($datos)
    {
        require_once(APPPATH.'libraries/Excel/PHPExcel.php');
        require_once(APPPATH.'libraries/Excel/PHPExcel/IOFactory.php');

        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Blackcore")
            ->setLastModifiedBy("Blackcore")
            ->setTitle("Reporte Calificaciones")
            ->setSubject("Reporte Calificaciones")
            ->setDescription("Reporte Calificaciones")
            ->setKeywords("Reporte Calificaciones")
            ->setCategory("Reporte Calificaciones");

        $worksheet = $objPHPExcel->getActiveSheet();

        // FORMATO ENCABEZADOS
        $encabezado_bold=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BE0712'),),//ROJO
            'font'=>array('name' => 'Arial', 'bold' => TRUE, 'size' => 10, 'color'=> array('rgb' => 'FFFFFF'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $worksheet->getStyle('A1')->applyFromArray($encabezado_bold);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'TIPO')
            ->setCellValue('B1', 'SKU')
            ->setCellValue('C1', 'MODELO')
            ->setCellValue('D1', 'NOMBRE')
            ->setCellValue('E1', 'CATEGORÍA')
            ->setCellValue('F1', '1 ESTRELLA')
            ->setCellValue('G1', '2 ESTRELLAS')
            ->setCellValue('H1', '3 ESTRELLAS')
            ->setCellValue('I1', '4 ESTRELLAS')
            ->setCellValue('J1', '5 ESTRELLAS')
            ->setCellValue('K1', 'PROMEDIO')
            ->setCellValue('L1', 'NO. DE COMENTARIOS')
            ->setCellValue('M1', 'VENDIDOS')
            ->setCellValue('N1', 'CALIFICADOS')
            ->setCellValue('O1', 'NO. DE CALIFICADOS');

        $worksheet->getStyle('A1:O1')->applyFromArray($encabezado_bold);

        $blanco=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFFFF'),),//BLANCO
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $gris=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BFBFBF'),),//GRIS
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $blanco_center=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFFFF'),),//BLANCO
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $gris_center=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BFBFBF'),),//GRIS
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $i=0;
        $y=2;
        foreach($datos as $p=>$v)
        {
            if($i%2==0){
                $worksheet->getStyle("A{$y}:B{$y}")->applyFromArray($blanco_center);
                $worksheet->getStyle("C{$y}:E{$y}")->applyFromArray($blanco);
                $worksheet->getStyle("F{$y}:O{$y}")->applyFromArray($blanco_center);

            }
            else{
                $worksheet->getStyle("A{$y}:B{$y}")->applyFromArray($gris_center);
                $worksheet->getStyle("C{$y}:E{$y}")->applyFromArray($gris);
                $worksheet->getStyle("F{$y}:O{$y}")->applyFromArray($gris_center);
            }

            $objPHPExcel->getActiveSheet()->setCellValueExplicit("A{$y}", mayuscula($v->tabla), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("B{$y}", $v->sku, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("C{$y}", $v->modelo, PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("D{$y}", !empty($v->nombre_producto)?$v->nombre_producto : $v->nombre_accesorio, PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("E{$y}", !empty($v->categoria)?$v->categoria : 'NO APLICA', PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("F{$y}", $v->calificacion1, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("G{$y}", $v->calificacion2, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("H{$y}", $v->calificacion3, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("I{$y}", $v->calificacion4, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("J{$y}", $v->calificacion5, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("K{$y}", $v->promedio, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("L{$y}", $v->comentarios, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("M{$y}", $v->vendidos, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("N{$y}", $v->calificados, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit("O{$y}", $v->no_calificados, PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $y++;
            $i++;
        }

        $worksheet->getColumnDimension('A')->setAutoSize(true);
        $worksheet->getColumnDimension('B')->setAutoSize(true);
        $worksheet->getColumnDimension('C')->setAutoSize(true);
        $worksheet->getColumnDimension('D')->setAutoSize(true);
        $worksheet->getColumnDimension('E')->setAutoSize(true);
        $worksheet->getColumnDimension('F')->setAutoSize(true);
        $worksheet->getColumnDimension('G')->setAutoSize(true);
        $worksheet->getColumnDimension('H')->setAutoSize(true);
        $worksheet->getColumnDimension('I')->setAutoSize(true);
        $worksheet->getColumnDimension('J')->setAutoSize(true);
        $worksheet->getColumnDimension('K')->setAutoSize(true);
        $worksheet->getColumnDimension('L')->setAutoSize(true);
        $worksheet->getColumnDimension('M')->setAutoSize(true);
        $worksheet->getColumnDimension('N')->setAutoSize(true);
        $worksheet->getColumnDimension('O')->setAutoSize(true);

        // Nombre de la hoja
        $worksheet->setTitle('Reporte Productos - Miele');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.'calificaciones_elementos.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }

    /**
     * Funcion para marcar la cotizacion como concluida
     **/
    public function set_concluida($cotizacion_id)
    {
        $id = $this->base->get_dato('id', 'calificaciones_email', array('cotizaciones_id'=>$cotizacion_id,'enviado'=>1));
        $data = array(
            'id'=>$id,
            'concluido'=>1
        );

        return parent::guarda('calificaciones_email', $data);
    }

    public function exportar_general($datos, $tabla)
    {
        require_once(APPPATH.'libraries/Excel/PHPExcel.php');
        require_once(APPPATH.'libraries/Excel/PHPExcel/IOFactory.php');

        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Blackcore")
            ->setLastModifiedBy("Blackcore")
            ->setTitle("Reporte General de Calificaciones de los".($tabla == 'productos')? 'Productos' : 'Accesorios')
            ->setSubject("Reporte General de Calificaciones de los".($tabla == 'productos')? 'Productos' : 'Accesorios')
            ->setDescription("Reporte General de Calificaciones de los".($tabla == 'productos')? 'Productos' : 'Accesorios')
            ->setKeywords("Reporte General de Calificaciones de los".($tabla == 'productos')? 'Productos' : 'Accesorios')
            ->setCategory("Reporte General de Calificaciones de los".($tabla == 'productos')? 'Productos' : 'Accesorios');

        $worksheet = $objPHPExcel->getActiveSheet();

        // FORMATO ENCABEZADOS
        $encabezado_bold=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BE0712'),),//ROJO
            'font'=>array('name' => 'Arial', 'bold' => TRUE, 'size' => 10, 'color'=> array('rgb' => 'FFFFFF'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $worksheet->getStyle('A1')->applyFromArray($encabezado_bold);

        if($tabla == 'accesorios'){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ORDEN DE COMPRA')
                ->setCellValue('B1', 'SKU')
                ->setCellValue('C1', 'MODELO')
                ->setCellValue('D1', 'ACCESORIO')
                ->setCellValue('E1', 'CALIFICACIÓN')
                ->setCellValue('F1', 'TELÉFONO')
                ->setCellValue('G1', 'EMAIL')
                ->setCellValue('H1', 'COMENTARIOS')
                ->setCellValue('I1', 'FECHA DE CALIFICACIÓN');
        } else {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ORDEN DE COMPRA')
                ->setCellValue('B1', 'SKU')
                ->setCellValue('C1', 'MODELO')
                ->setCellValue('D1', 'PRODUCTO')
                ->setCellValue('E1', 'CATEGORÍA DE PRODUCTO')
                ->setCellValue('F1', 'CALIFICACIÓN')
                ->setCellValue('G1', 'TELÉFONO')
                ->setCellValue('H1', 'EMAIL')
                ->setCellValue('I1', 'COMENTARIOS')
                ->setCellValue('J1', 'FECHA DE CALIFICACIÓN');
        }

        if($tabla == 'accesorios'){
            $worksheet->getStyle('A1:I1')->applyFromArray($encabezado_bold);
        } else{
            $worksheet->getStyle('A1:J1')->applyFromArray($encabezado_bold);
        }

        $blanco=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFFFF'),),//BLANCO
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $gris=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BFBFBF'),),//GRIS
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $blanco_center=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFFFF'),),//BLANCO
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $gris_center=array(
            'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BFBFBF'),),//GRIS
            'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
        );

        $i=0;
        $y=2;

        foreach($datos as $p=>$v)
        {
            if($tabla == 'accesorios') {
                if ($i % 2 == 0){

                    $worksheet->getStyle("A{$y}:C{$y}")->applyFromArray($blanco_center);
                    $worksheet->getStyle("D{$y}")->applyFromArray($blanco);
                    $worksheet->getStyle("E{$y}:G{$y}")->applyFromArray($blanco_center);
                    $worksheet->getStyle("H{$y}:I{$y}")->applyFromArray($blanco);
                }
                else{
                    $worksheet->getStyle("A{$y}:C{$y}")->applyFromArray($gris_center);
                    $worksheet->getStyle("D{$y}")->applyFromArray($gris);
                    $worksheet->getStyle("E{$y}:G{$y}")->applyFromArray($gris_center);
                    $worksheet->getStyle("H{$y}:I{$y}")->applyFromArray($gris);
                }

                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A{$y}", $v->folio_compra, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B{$y}", $v->sku, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C{$y}", $v->modelo, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("D{$y}", $v->nombre_accesorio, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E{$y}", $v->calificacion, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F{$y}", $v->telefono, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("G{$y}", $v->email, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("H{$y}", $v->comentario, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("I{$y}", $v->modified, PHPExcel_Cell_DataType::TYPE_STRING);
                $y++;
                $i++;
            } else {
                if ($i % 2 == 0){

                    $worksheet->getStyle("A{$y}:C{$y}")->applyFromArray($blanco_center);
                    $worksheet->getStyle("D{$y}:E{$y}")->applyFromArray($blanco);
                    $worksheet->getStyle("F{$y}")->applyFromArray($blanco_center);
                    $worksheet->getStyle("G{$y}:J{$y}")->applyFromArray($blanco);
                }
                else{
                    $worksheet->getStyle("A{$y}:C{$y}")->applyFromArray($gris_center);
                    $worksheet->getStyle("D{$y}:E{$y}")->applyFromArray($gris);
                    $worksheet->getStyle("F{$y}")->applyFromArray($gris_center);
                    $worksheet->getStyle("G{$y}:J{$y}")->applyFromArray($gris);
                }

                $objPHPExcel->getActiveSheet()->setCellValueExplicit("A{$y}", $v->folio_compra, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("B{$y}", $v->sku, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("C{$y}", $v->modelo, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("D{$y}", $v->nombre_producto, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("E{$y}", $v->categoria, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("F{$y}", $v->calificacion, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("G{$y}", $v->telefono, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("H{$y}", $v->email, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("I{$y}", $v->comentario, PHPExcel_Cell_DataType::TYPE_STRING);
                $objPHPExcel->getActiveSheet()->setCellValueExplicit("J{$y}", $v->modified, PHPExcel_Cell_DataType::TYPE_STRING);
                $y++;
                $i++;
            }
        }
        if($tabla == 'accesorios') {
            $worksheet->getColumnDimension('A')->setAutoSize(true);
            $worksheet->getColumnDimension('B')->setAutoSize(true);
            $worksheet->getColumnDimension('C')->setAutoSize(true);
            $worksheet->getColumnDimension('D')->setAutoSize(true);
            $worksheet->getColumnDimension('E')->setAutoSize(true);
            $worksheet->getColumnDimension('F')->setAutoSize(true);
            $worksheet->getColumnDimension('G')->setAutoSize(true);
            $worksheet->getColumnDimension('H')->setAutoSize(true);
            $worksheet->getColumnDimension('I')->setAutoSize(true);
        } else {
            $worksheet->getColumnDimension('A')->setAutoSize(true);
            $worksheet->getColumnDimension('B')->setAutoSize(true);
            $worksheet->getColumnDimension('C')->setAutoSize(true);
            $worksheet->getColumnDimension('D')->setAutoSize(true);
            $worksheet->getColumnDimension('E')->setAutoSize(true);
            $worksheet->getColumnDimension('F')->setAutoSize(true);
            $worksheet->getColumnDimension('G')->setAutoSize(true);
            $worksheet->getColumnDimension('H')->setAutoSize(true);
            $worksheet->getColumnDimension('I')->setAutoSize(true);
            $worksheet->getColumnDimension('J')->setAutoSize(true);
        }

        $title = ($tabla == 'accesorios')? 'General Accesorios - Miele' : 'General Productos - Miele';
        // Nombre de la hoja
        $worksheet->setTitle($title);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $file_name = ($tabla == 'accesorios')? 'calificaciones_accesorios.xls"' : 'calificaciones_productos.xls"';
        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name);
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }

    /**
     * Funcion para mostrar la calificación en el catalogos de productos
     **/

    public function get_calificacion($id, $tabla)
    {
        $this->db->select('elementos_id, ROUND(avg(calificacion)) promedio,  SUM(IF(ocultar_comentario = 0, 1, 0) AND IF(comentario IS NOT NULL, 1, 0)) comentarios', FALSE);
        $this->db->where('elementos_id', $id);
        $this->db->where('tabla', $tabla);
        $this->db->group_by('elementos_id');
        $data = $this->db->get('calificaciones')->row();
        return $data;

    }

    /**
     * Funcion para obtener productos o accesorios calificados para datos de la vista de comentarios.
     **/
    public function calificados($id, $tabla)
    {
        $calificados = array();
        if($tabla == 'productos')
            $calificados['nombre'] = $this->base->get_dato('nombre', 'productos', array('id' => $id));
        else
            $calificados['nombre'] = $this->base->get_dato('nombre', 'accesorios', array('id' => $id));
        $this->db->select('SUM(IF(calificacion = 1, 1, 0)) calificacion1,
        SUM(IF(calificacion = 2, 1, 0)) calificacion2,
        SUM(IF(calificacion = 3, 1, 0)) calificacion3,
        SUM(IF(calificacion = 4, 1, 0)) calificacion4,
        SUM(IF(calificacion = 5, 1, 0)) calificacion5,
        avg(calificacion) promedio,
        SUM(IF(calificado = 1, 1, 0)) calificados,
        SUM(IF(comentario IS NOT NULL, 1, 0) AND IF(ocultar_comentario = 0, 1, 0)) comentarios,
        elementos_id', FALSE);
        $this->db->where('calificado', 1);
        $this->db->where('elementos_id', $id);
        $this->db->where('tabla', $tabla);
        $this->db->group_by('elementos_id');

        $calificados['result'] = $this->db->get('calificaciones')->row();

        return $calificados;
    }

    /**
     * Funcion para obtener comentarios de un producto.
     **/
    public function comentarios($id, $tabla)
    {
        $this->db->select('id, comentario, created, elementos_id', FALSE);
        $this->db->where('calificado', 1);
        $this->db->where('comentario IS NOT NULL', NULL);
        $this->db->where('ocultar_comentario', 0);
        $this->db->where('tabla', $tabla);
        $this->db->where('elementos_id', $id);
        $result = $this->db->get('calificaciones')->result();
        return $result;

    }

    /**
     * Funcion para reenviar mail cuando no han calificado.
     **/
    public function send_mail_calificaciones_intentos()
    {
        $r = new stdClass();
        $this->db->where('enviado', 1);
        $this->db->where('concluido', 0);
        $res = $this->db->get('calificaciones_email')->result();

        foreach($res as $rs) {
            if($rs->intentos < $this->config->item('calificaciones_email_intentos')) {
                $send = $this->mail_calificacion($rs->cotizaciones_id);
                if(!empty($send))
                {
                    $data_update_calificaciones_email = array(
                        'id' => $rs->id,
                        'intentos' => $rs->intentos +1
                    );
                    parent::guarda('calificaciones_email', $data_update_calificaciones_email);
                    $r->exito = TRUE;
                    $r->mensaje = "Se ha enviado correctamente el correo al cliente para calificar los productos.";
                } else {
                    $r->exito = FALSE;
                    $r->mensaje = "No se ha enviado correctamente el correo al cliente para calificar los productos.";
                }
            } else {
                $r->exito = FALSE;
                $r->mensaje = "No se podra reenviar el correo al cliente por que ya supero su maximo numero de intentos.";
            }
        }
        return $r;
    }
}