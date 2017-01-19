<?php
require_once('main.php');
class Pruebas extends Main {
    
    function descuentos_cliente()
    {
        $this->load->model('descuento');
        $productos = $this->session->userdata('productos');

        debug($productos);
        
        $descuentos = $this->descuento->descuentos_cliente($productos);
        
        debug($descuentos);
        
    }
    
    function actualiza_precios()
    {
    	$this->load->model('Prueba');
    	$datos['productos']=$this->Prueba->actualiza_precios();
    	
    	$tabla=$this->load->view('productos/actualizar_precios',$datos,TRUE);
    	header("Content-type: application/vnd.ms-excel");
    	header("Content-Disposition: attachment; filename=actualizar_precios.xls");
    	echo $tabla;
    }

    function american($id) {
        $this->load->model('Pago');
        $rs = $this->Pago->ae_payment_session_create($id);
        print_r($rs);
    }

    function meses() {

        debug("Creando sesion");

        $session = curl_init("https://gateway-na.americanexpress.com/api/rest/version/20/merchant/TEST9352568548/session");
        $data = "";
        $headers = array("Content-Length: " . strlen($data));

        curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($session, CURLOPT_USERPWD, "merchant.TEST9352568548:1165374ef7c047a1ec33314a3873a1b2");
        curl_setopt($session, CURLOPT_POST, 1);

        curl_setopt($session, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($session, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($session);

        $sess = json_decode($result);

        debug("Resultado de crear session: ");
        debug($sess);


        $process = curl_init('https://gateway-na.americanexpress.com/api/rest/version/20/merchant/TEST9352568548/order/BLACK0000114007/transaction/2');

        $data = json_encode(array(
           "apiOperation"=>"AUTHORIZE",
           "transaction" => array(
               "id" => 1,
               "amount" => 3749,
               "currency" => "MXN"
           ),
           "sourceOfFunds" => array(
               "type" => "CARD"
           ),
           "session"=> $sess->session
        ));

        //debug("Creando transaccion: ");
        //debug($data);

        $headers = array("Content-Length: " . strlen($data));

        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_USERPWD, "merchant.TEST9352568548:1165374ef7c047a1ec33314a3873a1b2");
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, "PUT");

        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);

        curl_setopt($process, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($process, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($process);

        debug("Creando transaccion: ");
        debug(json_decode($result));

        if (!curl_errno($process)) {
            //parse_str($result, $rs);
        } else {
            debug("Error");
        }
        curl_close($process);
    }

    function phpin()
    {
    	phpinfo();
    	exit;
    }

    /**
     * FUNCION QUE CARGA LOS ESTADOS PARA CONFIGURAR GASTOS DE ENVIO
     * CORRER SOLO LA PRIMERA VEZ AAR INICIALIZAR LA TABLA
     */
    function inserta_estados_envio()
    {
        $this->load->Model('Sepomex_model','SP');
        $estados = $this->SP->get_estados();
        $estados_miele = catalogo('estados_envio',FALSE);
        debug($estados_miele);
        foreach($estados as $k=>$e)
        {
            $data=array();
            $data['estado']=$e;
            $data['productos_porcentaje'] = in_array($e,$estados_miele)?0.00:8.00;
            $data['accesorios_porcentaje'] = in_array($e,$estados_miele)?0.00:8.00;
            $data['consumibles_porcentaje'] = in_array($e,$estados_miele)?0.00:8.00;
            $data['consumibles_monto_fijo'] = $e=='DISTRITO FEDERAL'?100.00:200.00;
            $data['consumibles_monto_minimo_porcentaje'] = 1250.00;

            $data['productos_numero'] = 2;
            $data['accesorios_numero'] = 2;
            $data['consumibles_numero'] = 2;


            $data['created']=date('Y-m-d H:i:s');
            $data['created_by']=$this->session->userdata('id');

            $this->db->insert('gastos_envios',$data);
        }
        debug($estados);exit;
    }

    function comisiones($cotizaciones_id)
    {
        $this->load->model('Cotizacion');
        $this->load->model('Descuento');

        $productos=$this->Cotizacion->get_productos_cotizacion($cotizaciones_id);
        $venta_directa=$this->Cotizacion->es_venta_directa($this->base->value_get('cotizaciones',$cotizaciones_id,'cuentas_id'));
        $cotizacion = $this->base->read('cotizaciones',$cotizaciones_id);
        $data= array(
            'descuento_opcional'=>$cotizacion->descuento_opcional,
            'descuento_paquete' =>$cotizacion->descuento_paquete,
            'descuento_cupon'	=>$cotizacion->descuento_cupon
        );
        $this->Descuento->genera_comisiones($cotizaciones_id,$data,$productos,$venta_directa,TRUE);

        debug('COMISIONES RECALCULADAS CORRECTAMENTE');exit;
    }

    function compra_mail($cotizaciones_id)
    {
        $this->load->model('Payment');
        $rs = $this->Payment->compra_mail($cotizaciones_id);
        debug($rs);exit;
    }

    function mail_calificacion($cotizaciones_id){
        $this->load->model('Calificacion');
        $rs = $this->Calificacion->mail_calificacion($cotizaciones_id);
        debug($rs);exit;
    }
    function get_data_cron(){
        $this->load->model('Calificacion');
        $rs = $this->Calificacion->get_mail_calificacion();
        debug($rs);exit;
    }

    function califica_producto($id){
        $this->load->model('Calificacion');
        $data = (object)array(
            'calificacion' => 3,
            'comentario' => 'esta gacho el producto no me lleno mis espectativas'
        );
        $rs = $this->Calificacion->save_calificacion_productos($id, $data);
        debug($rs);exit;
    }

    function califica_accesorio($id){
        $this->load->model('Calificacion');
        $data = (object)array(
            'calificacion' => 1,
            'comentario' => 'El accesorio esta muy sencillo, no se me hace de buena calidad para el precio'
        );
        $rs = $this->Calificacion->save_calificacion_accesorios($id, $data);
        debug($rs);exit;
    }
    function autorizar($id)
    {
        $this->load->model('Cotizacion');
        $rs = $this->Cotizacion->mail_compra($id,4);
        debug($rs);exit;
    }

    function reporte_general()
    {
        $this->load->model('Calificacion');
        $rs = $this->Calificacion->reporte_general(0,0,'calificaciones_productos');
        debug($rs);exit;
    }

    function get_calificacion($id,$tabla)
    {
        $this->load->model('Calificacion');
        $rs = $this->Calificacion->get_calificacion($id,$tabla);
        debug($rs);exit;
    }

    function update_fecha_envio($id)
    {

        $this->load->model('Base');
        $id = $this->base->get_dato('id', 'calificaciones_email', array('cotizaciones_id' => $id));
        $data = array(
            'id' => $id,
            'fecha_envio' => date("Y-m-d 00:00:00"),
            'concluido' => 0,
            'enviado' => 0
        );
        $rs = $this->Base->guarda('calificaciones_email', $data);
        debug($rs);exit;
    }

    function send_mail_intentos()
    {
        $this->load->model('Calificacion');
        $rs = $this->Calificacion->send_mail_calificaciones_intentos();
        debug($rs);exit;
    }

    function send_mail_cupon()
    {
        $this->load->model('Cupon');

        $email = 'r_m_h_x@hotmail.com, roger@blackcore.com.mx';
        $cupon_folio_id = 20;
        $cupon_folio = 'AUDILYZSI020';
        $cupon_id = 1;
        $total_cliente = 499;

        $imagenes_cupones = $this->Cupon->get_imagenes($cupon_id);

        foreach($imagenes_cupones as $clave=>$valor)
        {
            // Obtiene id de la imagen del cupón
            if($total_cliente>=$valor->monto_inicial && $total_cliente<=$valor->monto_final)
            {
                $imagen_id = $valor->id;
                break;
            }
        }

        $data=array();
        $data['cupon_ruta'] = $cupon_ruta_imagen = $this->cloud_files->url_publica("files/cupones/{$cupon_id}/{$imagen_id}.jpg");
        $data['cupon_folio'] = $cupon_folio;
        $data['folio_compra'] = $folio_compra = 'EXTERN16183';
        $cotizaciones_id = 1;
        $data['visualizar_cupon'] = $this->cupon_visualizar_get($folio_compra, $cupon_id, $imagen_id, $cupon_folio_id);

        $this->load->library('email');
        $email_sax=$this->config->item('email');
        $this->email->from($email_sax[0], $email_sax[1]);

        $cliente_mail = $email;

        $vendedor=$this->base->value_get('usuarios',$this->session->userdata('id'),'email');
        $this->email->to($cliente_mail);
        $this->email->bcc($vendedor);

        $this->email->subject('Cupón: '.$cupon_folio);
        $mensaje=$this->load->view('email/cotizacion_cupon',$data,TRUE);
        $this->email->message($mensaje);
        $this->email->send();
    }

    function cupon_visualizar_get($folio_compra, $cupon_id, $imagen_id, $cupon_folio_id)
    {
        $datos_encriptados = $folio_compra.','.$cupon_id.','.$imagen_id.','.$cupon_folio_id;

        $cadena = $this->encrypt_string($datos_encriptados);

        $ruta_enlace = $this->config->item('url').'cupones/visualizar_cupon/'.$cadena;

        return $ruta_enlace;
    }

    public function encrypt_string($string)
    {
        $algorithm = MCRYPT_BLOWFISH;
        $key = $this->config->item('clave_codificacion');
        $mode = MCRYPT_MODE_CBC;
        $iv = base64_decode($this->config->item('iv'));

        $encrypt_string = mcrypt_encrypt($algorithm, $key, $string, $mode, $iv);
        $encode_url_string = urlencode($encrypt_string);

        return $encode_url_string;
    }

    public function decrypt_string($string)
    {
        $decode_url_string = urldecode($string);

        $algorithm = MCRYPT_BLOWFISH;
        $key = $this->config->item('clave_codificacion');
        $mode = MCRYPT_MODE_CBC;
        $iv = base64_decode($this->config->item('iv'));

        $decrypt_string = mcrypt_decrypt($algorithm, $key, $decode_url_string, $mode, $iv);

        return $decrypt_string;
    }

    public function actualizar_paquetes_id($limit=1000)
    {
        $this->db->from('cotizaciones');
        $this->db->where('eliminado',0);
        $this->db->where('descuento_paquete_id IS NULL');
        $this->db->where('descuento_paquete',1);
        $r1 = $this->db->count_all_results();

        debug('Totales al iniciar '.$r1);

        $this->db->select('id');
        $this->db->from('cotizaciones');
        $this->db->where('eliminado',0);
        $this->db->where('descuento_paquete_id IS NULL');
        $this->db->where('descuento_paquete',1);
        $this->db->limit($limit);
        $r = $this->db->get()->result_object();

        if(!empty($r))
        {
            $this->load->Model('descuento');
            foreach($r as $k=>$v)
            {

                $productos_ids = $this->get_productos_id($v->id);
                $paquete_id = $this->descuento->paquete_adquirido($productos_ids);

                if(isset($paquete_id))
                {
                    $datos['descuento_paquete_id']=$paquete_id;
                    $this->db->where('id',$v->id);
                    $this->db->update('cotizaciones',$datos);
                }
            }
        }

        $this->db->from('cotizaciones');
        $this->db->where('eliminado',0);
        $this->db->where('descuento_paquete_id IS NULL');
        $this->db->where('descuento_paquete',1);
        $r2 = $this->db->count_all_results();
        debug('Totales al terminar '.$r2);
    }

    public function get_productos_id($cotizacion_id)
    {
        $this->db->select('productos_id, cantidad');
        $this->db->where('cotizaciones_id', $cotizacion_id);
        $this->db->where('eliminado', 0);
        $this->db->where('(promocion IS NULL OR promocion=0)');
        $productos = $this->db->get('cotizaciones_productos')->result_array();

        $productos_id = array();
        foreach($productos as $k=>$v)
            $productos_id[$v['productos_id']]['cantidad'] = $v['cantidad'];

        return $productos_id;
    }

    public function cupon_reenviar_email($cotizaciones_id, $email='')
    {
        $cotizacion = $this->base->get_datos('total_cliente, cupon_folio_enviado_id, cupon_enviado_id, folio_cupon, cupon_cliente_email, folio_compra','cotizaciones',array('id'=>$cotizaciones_id));

        $cupon_id = $cotizacion->cupon_enviado_id;
        $cupon_folio = $cotizacion->folio_cupon;
        $cupon_folio_id = $cotizacion->cupon_folio_enviado_id;
        $folio_compra = $cotizacion->folio_compra;
        $total_cliente = $cotizacion->total_cliente;
        if(empty($email))
            $email = $cotizacion->cupon_cliente_email;

        // Creación de la imagen
        $this->load->model('Cupon');
        $imagenes_cupones = $this->Cupon->get_imagenes($cupon_id);

        foreach($imagenes_cupones as $clave=>$valor)
        {
            // Obtiene id de la imagen del cupón
            if($total_cliente>=$valor->monto_inicial && $total_cliente<=$valor->monto_final)
            {
                $imagen_id = $valor->id;
                $producto_regalo_id = $valor->productos_id;
                break;
            }
        }

        $data = array();
        $cupon_ruta = $this->cloud_files->url_publica("files/cupones/{$cupon_id}/{$imagen_id}.jpg");

        // Set Path to Font File
        $font_path = FCPATH.'fonts/HCBSe.ttf';

        // Create Image From Existing File
        $jpg_image = imagecreatefromjpeg($cupon_ruta);

        // Allocate A Color For The Text
        $color = imagecolorallocate($jpg_image, 0, 0, 0);

        // Print Text On Image
        imagettftext($jpg_image, 17, 0, 774, 396, $color, $font_path, $cupon_folio);

        // Send Image to Browser
        $file = '/var/tmp/cupon_'.$cupon_folio.'.jpg';
        imagejpeg($jpg_image,$file);

        $cupon_ruta = $this->base->guarda_imagen($file,'files/cupones/'.$cupon_id,$cupon_folio.'-recuperacion',FALSE);

        // Clear Memory
        imagedestroy($jpg_image);

        $data['cupon_ruta'] = $cupon_ruta;
        $data['folio_compra'] = $folio_compra;

        // Ruta encriptada
        $datos_encriptados = $folio_compra.','.$cupon_id.','.$cupon_folio_id;
        $cadena = $this->encrypt_string($datos_encriptados);
        $ruta_enlace = $this->config->item('url').'frontends/visualizar_cupon/'.$cadena;

		$data['visualizar_cupon'] = $ruta_enlace;

		$this->load->library('email');
		$email_sax = $this->config->item('email');
		$this->email->from($email_sax[0], $email_sax[1]);

		$vendedor = $this->base->value_get('usuarios',$this->session->userdata('id'),'email');
		$this->email->to($email);
		$this->email->bcc($vendedor);

		$this->email->subject('Miele agradece su preferencia.');
		$mensaje = $this->load->view('email/cotizacion_cupon',$data, TRUE);

		$this->email->message($mensaje);
		$result = $this->email->send();

        return $result;
    }

}
