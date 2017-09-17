<?php
require_once('main.php');
class Cotizaciones extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('cotizaciones');
		$this->load->model('Cotizacion');
		$this->load->model('Cupon');
	}
	
	public function index()
	{
		$b=bc_buscador();

		if (!empty($b['cond']['exp']))
		{
			$_POST['fecha_inicial'] = @$b['cond']['fecha_inicial'];
			$_POST['fecha_final'] = @$b['cond']['fecha_final'];
			if (!$this->form_validation->run('cotizaciones_exportar'))
				$datos['flashdata']['error']='Por favor verifique los datos.';
			elseif($b['cond']['exp']==1)
			{
				$this->base->verifica('cotizaciones_exportar');
				unset($b['cond']['exp']);
				$this->Cotizacion->exportar($b['cond']);
			}elseif($b['cond']['exp']==2)
			{
				$this->base->verifica('cotizaciones_autorizadas_reporte');
				unset($b['cond']['exp']);
				$this->Cotizacion->reporte($b['cond']);
			}
		}

		$datos['r']=$this->Cotizacion->find($b['cond'],$this->config->item('por_pagina'),$b['offset']); 
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Cotizacion->count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		
		$datos['titulo']='Cotizaciones';
		$datos['productos']=$this->base->lista('productos','id','nombre',TRUE,'nombre','ASC');
		$datos['cuentas']=$this->base->lista('cuentas','id','nombre',FALSE,'nombre','ASC');
		$datos['status']=$this->Cotizacion->status;
		$datos['agregar']=$this->base->tiene_permiso('cotizaciones_agregar');
		$datos['cancelar']=$this->base->tiene_permiso('cotizaciones_eliminar');
		$datos['editar']=$this->base->tiene_permiso('cotizaciones_editar');
		$datos['imprimir']=$this->base->tiene_permiso('cotizaciones_imprimir');
		$datos['enviar']=$this->base->tiene_permiso('cotizaciones_enviar_compra');
		$datos['documentacion']=$this->base->tiene_permiso('cotizaciones_agregar_documentacion');
		$datos['revision']=$this->base->tiene_permiso('cotizaciones_revision_orden_compra');
		$datos['ver_referencia']=$this->base->tiene_permiso('cotizaciones_ver_referencia');
		$datos['cotizaciones_agregar_ibs']=$this->base->tiene_permiso('cotizaciones_agregar_ibs');
		$datos['puede_exportar']=$this->base->tiene_permiso('cotizaciones_exportar');
		$datos['puede_reporte']=$this->base->tiene_permiso('cotizaciones_autorizadas_reporte');
		$datos['cotizaciones_todas']=$this->base->tiene_permiso('cotizaciones_todas');
		$datos['cotizaciones_externos_filtrar']=$this->base->tiene_permiso('cotizaciones_externos_filtrar');
		$datos['cotizaciones_enviar_email']=$this->base->tiene_permiso('cotizaciones_enviar_email');
		$datos['cotizaciones_validar_pago']=$this->base->tiene_permiso('cotizaciones_validar_pago');
		$datos['descargar_archivos']=$this->base->tiene_permiso('cotizaciones_descargar_archivos');
		$datos['vendedor']=$this->base->value_get('usuarios',$this->session->userdata('id'),'vendedor');
		$this->session->unset_userdata('cotizacion_productos');
		$this->load->view('cotizaciones/index',$datos);
	}

    public function eliminar($productos_id)
    {
    	$this->base->verifica('productos_eliminar');
    	$data['id']=$productos_id;
    	$data['eliminado']=1;
    	$this->base->guarda($this->Cotizacion->table,$data);
    	bc_log(1,'Elimino cotizacion id: '.$productos_id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('cotizaciones/index');
    }

    public function set_session()
	{
		$this->session->set_userdata('cotizacion', $_POST);	
		if(isset($_POST['id']))
			$this->session->set_userdata('cotizaciones_id', $_POST['id']);
		unset($_POST);
		
		redirect('cotizaciones/agregar_productos');
	}
	
	public function agregar_productos()
	{		
		$b=bc_buscador();
		$datos['r']=$this->Cotizacion->productos_find($b['cond'],$this->config->item('por_pagina'),$b['offset']);		
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['uri_segment']=$b['uri_segment'];
		$paginador['total_rows'] = $datos['total'] = $this->Cotizacion->productos_count($b['cond']);		
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['paginador'] = $this->pagination->create_links();
		$datos['cond']=$b['cond'];
		$datos['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'DESC',array('ocultar'=>0));
		$facturas_id=$this->session->userdata('factura_id');
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['titulo']='Agregar producto';
		
		$this->load->view('cotizaciones/agregar_productos',$datos);	
	}
	
	public function productos_add($ids)
	{	
		$productos=$this->session->userdata('cotizacion_productos');	
		$ids=explode(',',$ids);
		foreach($ids as $id)
		{
			$productos[]=$id;			
		}
		$this->session->set_userdata('cotizacion_productos', $productos);
		$this->load->library('user_agent');	
		$this->session->set_flashdata('done', 'El producto ha sido agregado correctamente.');
		redirect($this->agent->referrer());
	}
	
	public function productos_denegar($productos_id)
	{
		$productos=$this->session->userdata('cotizacion_productos');
		foreach($productos as $k=>$c)
		{
			if($c==$productos_id)
				unset($productos[$k]);
		}
		
		$this->session->set_userdata('cotizacion_productos', $productos);
		$this->load->library('user_agent');
		$this->session->set_flashdata('done', 'El producto ha sido denegado correctamente.');

		redirect($this->agent->referrer());	
	}
	
	public function lista_productos()
	{
		$this->load->model('Producto');
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['r']=$this->Producto->find($_POST,50,0);	
		
		$this->load->view('cotizaciones/lista_productos',$datos);	
	}
	
	public function item($productos_id)
	{
		$this->load->Model('Producto');
		$this->load->Model('Accesorio');
		$ts=time();
		$r[$ts]['producto']=$this->base->read('productos',$productos_id);
		$r[$ts]['producto']->img_id=$this->Producto->get_imagen_principal($productos_id);
		$r[$ts]['tipos_accesorios']=$this->Producto->get_accesorios_producto($productos_id);
		$datos['p']=$r;		
		$this->load->view('cotizaciones/item',$datos);
	}
	
	function imprimir($cotizaciones_id,$output='I',$path=NULL,$status_id=NULL)
	{
		$this->Cotizacion->imprimir($cotizaciones_id, $output, $path, $status_id);
	} 
	
 	public function cancelar($cotizaciones_id)
    {
    	$status_actual_id = $this->Cotizacion->get_status($cotizaciones_id);
    	$data=array();
    	//Cancelar Cotizacion-Orden de pago.
    	switch($status_actual_id)
    	{
    		case 1:
    			$data['status_id']=7; //Cotizacion Cancelada 
    		break;
    		case 2:
    		case 3:
    		case 4:
    			$data['status_id']=5; //Orden de pago cancelada
    			break;
    	}
		$data['id']=$cotizaciones_id;
		$this->db->where('id',$cotizaciones_id);
		$this->base->guarda('cotizaciones',$data);
  		$this->session->set_flashdata('done', 'El registro fue cancelado correctamente.');
		redirect('cotizaciones/index');
		return TRUE;
    }

    public function get_datos_accesorios($accesorios_id)
    {
    	if($this->base->verifica())
    	{
    		$datos=$this->Cotizacion->get_datos_accesorios($accesorios_id);
    		$msg=json_encode($datos);
    		$this->output->set_output($msg);
    	}
    }
    
   
    public function generar_cotizacion()
    {
	   	$productos=$this->session->userdata('productos');
	   	foreach($productos as $k=>$p)
	   	{
	   		$datos[$k]['productos_id']=$p['productos_id'];
	   		$datos[$k]['cantidad']=$p['cantidad'];
	   		if(!empty($p['accesorios']))
	   		{
		   		foreach($p['accesorios'] as $acc)
		   		{
		   			$d_acc=$this->Cotizacion->get_accesorio($acc);
		   			$datos[$k]['tipo_accesorios_id'][]=$d_acc->tipos_accesorios_id;	
		   			$datos[$k]['accesorio_cantidad'][]=$p['cantidad'];
		   		}
		   		$datos[$k]['accesorio_seleccionado_id']=$p['accesorios'];	
	   		}
	   	}
	   	$this->session->set_userdata('productos',array());
		$this->agregar($datos);	
    }
    
    public function autorizar($cotizaciones_id,$debug=FALSE)
    {
    	$this->load->model('Descuento');
    	$this->load->model('Referido');

    	$data['status_id']=4;
    	$data['id']=$cotizaciones_id;
    	$data['referido_porcentaje_comision']=$this->input->post('referido_porcentaje_comision');
    	$this->db->where('id',$cotizaciones_id);
    	$id=$this->base->guarda('cotizaciones',$data);
    	
    	if($this->input->post('referido_id'))
    		$fecha=$this->Referido->actualizar_vigencia($this->input->post('referido_id'));
    	
    	$productos=$this->Cotizacion->get_productos_cotizacion($cotizaciones_id);
    	$venta_directa=$this->Cotizacion->es_venta_directa($this->base->value_get('cotizaciones',$cotizaciones_id,'cuentas_id'));
		$cotizacion = $this->base->read('cotizaciones',$cotizaciones_id);
		$data= array(
			'descuento_opcional'=>$cotizacion->descuento_opcional,
			'descuento_paquete' =>$cotizacion->descuento_paquete,
			'descuento_cupon'	=>$cotizacion->descuento_cupon
		);
    	$this->Descuento->genera_comisiones($cotizaciones_id,$data,$productos,$venta_directa,$debug);
		
    	$this->Cotizacion->mail_compra($cotizaciones_id,4,$venta_directa);
    	$this->session->set_userdata('productos',array());
    	$this->session->set_userdata('cotizaciones_id',NULL);
    	$this->session->set_flashdata('done', 'La autorización fue realizada con éxito.');
    	redirect('cotizaciones/index');
    }
    
    
    public function rechazar($cotizaciones_id)
    {
        $this->Cotizacion->cambia_status($cotizaciones_id, 6);
        $this->session->set_userdata('productos',array());
        $this->session->set_userdata('cotizaciones_id',NULL);
		$this->session->set_flashdata('done', 'La orden de compra fue rechazada con éxito.');
        redirect('cotizaciones/index');
    }
    
    public function enviar_compra($cotizaciones_id)
    {
        $this->load->Model('Sepomex_model','SP');
        if(!empty($_POST))
        {
            $valida=$this->form_validation->run('orden_compra');
            $fecha_entrega=$this->input->post('fecha_entrega');
            $fecha_actual=date('Y-M-d');
            $datetime1 = new DateTime($fecha_actual);
            $datetime2 = new DateTime($fecha_entrega);
            $intervalo = $datetime1->diff($datetime2);
            $dias=$intervalo->format('%a');
            $valida_fecha=TRUE;
             
            if($dias<15 || $dias>180)
                $valida_fecha=FALSE;

            if($valida  && $valida_fecha)
            {
                $data=array(
                    'id'=>$this->input->post('id'),
                    'fecha_compra'=>$this->input->post('fecha'),
                    'fecha_entrega'=>$this->input->post('fecha_entrega'),
                    'cuentas_id'=>$this->session->userdata('cuentas_id'),
                    'razon_social'=>$this->input->post('razon_social'),
                    'apellido_paterno'=>$this->input->post('apellido_paterno'),
                    'apellido_materno'=>$this->input->post('apellido_materno'),
                    'rfc'=>$this->input->post('rfc'),
                    'estado'=>$this->input->post('estado'),
                    'municipio'=>$this->input->post('municipio'),
                    'codigo_postal'=>$this->input->post('codigo_postal'),
                    'asentamiento'=>$this->input->post('asentamiento'),
                    'calle'=>$this->input->post('calle'),
                    'numero_exterior'=>$this->input->post('numero_exterior'),
                    'numero_interior'=>$this->input->post('numero_interior'),
                    'email'=>$this->input->post('email'),
                    'telefono'=>$this->input->post('telefono'),
                    'nombre_contacto'=>$this->input->post('nombre_contacto'),
                    'telefono_particular'=>$this->input->post('telefono_particular'),
                    'telefono_celular'=>$this->input->post('telefono_celular'),
                    'entrega_estado'=>$this->input->post('entrega_estado'),
                    'entrega_municipio'=>$this->input->post('entrega_municipio'),
                    'entrega_codigo_postal'=>$this->input->post('entrega_codigo_postal'),
                    'entrega_asentamiento'=>$this->input->post('entrega_asentamiento'),
                    'entrega_calle'=>$this->input->post('entrega_calle'),
                    'entrega_numero_exterior'=>$this->input->post('entrega_numero_exterior'),
                    'entrega_numero_interior'=>$this->input->post('entrega_numero_interior'),
                    'forma_pago_id'=>$this->input->post('forma_pago'),
                    'condiciones_pago_id'=>$this->input->post('condiciones_pago_id'),
                    'observaciones'=>$this->input->post('observaciones'),
                    'nombre_comprador'=>$this->input->post('nombre_comprador'),
                    'paterno_comprador'=>$this->input->post('paterno_comprador'),
                    'materno_comprador'=>$this->input->post('materno_comprador'),
                    'email_comprador'=>$this->input->post('email_comprador'),
                    'telefono_comprador'=>$this->input->post('telefono_comprador'),
                    'fecha_nacimiento_comprador'=>$this->input->post('fecha_nacimiento_comprador'),
                    'anio_nacimiento_comprador'=>$this->input->post('anio_nacimiento_comprador'),
                    'fecha_aniversario_comprador'=>$this->input->post('fecha_aniversario_comprador'),
                    'tipo_persona_id'=>$this->input->post('tipo_persona_id'),
                );
    
                $cotizaciones_id=$this->base->guarda('cotizaciones',$data);
    
                $status_id=$this->Cotizacion->get_status($cotizaciones_id);
                if($status_id==1)
                    $this->Cotizacion->cambia_status($cotizaciones_id);
    
                if($_FILES)
                    $this->Cotizacion->guarda_documentacion($cotizaciones_id,$_FILES);
    
                $this->session->set_flashdata('done', 'El registro fue procesado correctamente.');
                redirect('cotizaciones/index');
            }
            else
            {
                $mensaje='Verifique los datos.';
    
                if(empty($valida_fecha))
                {
                    $mensaje.=' La fecha de entrega no puede ser menor a 10 dias habiles y menor a 6 meses, verifique los datos.';
                }
                $this->session->set_flashdata('error', $mensaje);
                $datos['municipios']=$this->SP->get_municipios($_POST['estado']);
                $datos['entrega_municipios']=$this->SP->get_municipios($_POST['entrega_estado']);
            }
        }else
        {
            $datos['r']=$this->Cotizacion->get_cotizacion($cotizaciones_id);
            $productos_id=array();
            $datos['municipios']=$this->SP->get_municipios($datos['r']['cotizacion']->estado);
            $datos['entrega_municipios']=$this->SP->get_municipios($datos['r']['cotizacion']->entrega_estado);
        }
    
        $datos['cotizaciones_id']=$cotizaciones_id;
        $datos['estados']=$this->SP->get_estados();
        $datos['r']=$this->Cotizacion->get_cotizacion($cotizaciones_id);
        $datos['status_id']=$this->Cotizacion->get_status($cotizaciones_id);
        $datos['revision']=$this->base->tiene_permiso('cotizaciones_revision_orden_compra');
        $datos['forma_pago']=$this->Cotizacion->forma_pago;
        $datos['condiciones_pago']=$this->Cotizacion->condiciones_pago;
        $datos['titulo']='Orden de Compra';
        $datos['cuentas']=$this->base->lista('cuentas','id','nombre',TRUE,'nombre','ASC');
        $this->load->view('cotizaciones/orden_compra',$datos);
    
    }
    
    function cotizacion_enviar_email($cotizaciones_id)
    {
    	$this->base->verifica('cotizaciones_enviar_email');
		$externos_ids=$this->base->usuarios_externos_ids_get();
		$data=array();
		$data['cotizacion'] = $cotizacion = $this->base->read('cotizaciones',$cotizaciones_id);
		if(in_array($cotizacion->usuario_id, $externos_ids))
		{
			$this->session->set_flashdata('error', 'La cotizaci&oacute;n no puede ser enviada por ser venta externa.');
			redirect('cotizaciones/index');
		}

    	$this->Cotizacion->imprimir($cotizaciones_id,'F',TRUE,1);

    	$this->load->library('email');
    	$url=$this->config->item('url');
    	$email_sax=$this->config->item('email');
    	$this->email->from($email_sax[0], $email_sax[1]);
    	
    	$cliente=$this->base->value_get('cotizaciones',$cotizaciones_id,'email_comprador');
    	$vendedor=$this->base->value_get('usuarios',$this->session->userdata('id'),'email');
    	$this->email->to($cliente);
    	$this->email->bcc($vendedor);

    	$path = APPPATH."files/cotizaciones_pdfs/{$cotizacion->folio_cuentas}.pdf";
    	$this->email->attach($path);
    	$this->email->subject('PDF - Cotización: '.$cotizacion->folio_cuentas);
    	$mensaje=$this->load->view('email/cotizacion_pdf',$data,TRUE);
    	$this->email->message($mensaje);
    	$result = $this->email->send();
    		
    	if($this->config->item('debug_mail')) // DEBUG DE MAIL
    	{
    		debug($this->email->print_debugger(),null,1);
    	}
    	
    	if($result)
    	{
    		$this->session->set_flashdata('done', 'La cotizaci&oacute;n ha sido enviada correctamente.');
    		unlink($path);
    	}
    	else
    		$this->session->set_flashdata('error', 'La cotizaci&oacute;n no ha sido enviada correctamente, por favor int&eacute;ntelo nuevamente m&aacute;s tarde. Si el problema persiste p&oacute;ngase en contacto con el administrador.');
    	
    	redirect('cotizaciones/index');
    }

    /**
     * Verificar pago manualmente
     */
    public function validar_pago($cotizaciones_id)
    {
        $this->base->verifica('cotizaciones_validar_pago');

        $data=array(
            'id'=>$cotizaciones_id,
        	'status_id'=>4,
            'pago_realizado'=>1,
        );
        $this->base->guarda('cotizaciones', $data);
        $this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
        redirect('cotizaciones/index/');
    }
    
    /**
     * FUNCION PARA AGREGAR EL NUMERO IBS DE UNA COTIZACION
     */
    function ibs_agregar($cotizaciones_id)
    {
    	$this->base->verifica('cotizaciones_agregar_ibs');
		$cotizacion = $this->base->get_datos('total_cliente, cupon_folio_enviado_id, ibs, cupon_cliente_email, guia_mecanica_cliente_email','cotizaciones',array('id'=>$cotizaciones_id));
		$datos['cupones_disponibles'] = $this->Cupon->cupones_disponibles($cotizacion->total_cliente);
		$datos['cupon_folio_enviado_id'] = $cotizacion->cupon_folio_enviado_id;

		$reglas = $this->form_validation->_config_rules['cotizaciones_ibs'];

		if(!empty($datos['cupones_disponibles']) && empty($datos['cupon_folio_enviado_id']))
		{
			$reglas[]=array(
				'field' => 'enviar_mail',
				'label' => 'Enviar cupón por email',
				'rules' => 'trim'
			);
			$reglas[]=array(
				'field' => 'guia_mecanica_mail',
				'label' => 'Enviar guía mecánica por email',
				'rules' => 'trim'
			);

			if(!empty($_POST['enviar_mail']))
			{
				$reglas[]=array(
					'field' => 'cupon_cliente_email',
					'label' => 'Email del cliente para envío de cupón',
					'rules' => 'required|valid_email'
				);
			}

			if(!empty($_POST['guia_mecanica_mail']))
			{
				$reglas[]=array(
					'field' => 'guia_mecanica_cliente_email',
					'label' => 'Email del cliente para envío de guías mecánicas y carta bienvenida',
					'rules' => 'required|valid_email'
				);
			}
		}

		$this->form_validation->set_rules($reglas);
    	if($this->form_validation->run())
    	{
			$mensaje_done = FALSE;
			$mensaje_error = FALSE;
			$cotizaciones_id=$this->Cotizacion->ibs_agregar($cotizaciones_id);

			if($cotizaciones_id)
				$mensaje_done .='El IBS ha sido agregado correctamente.';
			else
				$mensaje_error .='El IBS no se guard&oacute; correctamente. Por favor intente nuevamente.';

			// ENVÍO DE CUPON POR EMAIL AL CLIENTE
			$res=NULL;
			if(empty($cotizacion->cupon_folio_enviado_id))
				$res = $this->cupon_enviar_email($cotizaciones_id, $_POST['cupon_cliente_email'], $datos['cupones_disponibles'], $cotizacion->total_cliente);

			if(@$res['exito'] && @$res['mensaje'])
				$mensaje_done .= $mensaje_done?'<br/>'.$res['mensaje']:$res['mensaje'];
			elseif(!@$res['exito'] && @$res['mensaje'])
				$mensaje_error .= $mensaje_error?'<br/>'.$res['mensaje']:$res['mensaje'];

			// ENVÍO DE GUÍAS MECÁNICAS Y CARTA BIENVENIDA POR EMAIL AL CLIENTE
			$res=NULL;
			if(@$_POST['guia_mecanica_mail'])
				$res = $this->guia_mecanica_enviar_email($cotizaciones_id, $_POST['guia_mecanica_cliente_email']);

			if(@$res['exito'] && @$res['mensaje'])
				$mensaje_done .= $mensaje_done?'<br/>'.$res['mensaje']:$res['mensaje'];
			elseif(!@$res['exito'] && @$res['mensaje'])
				$mensaje_error .= $mensaje_error?'<br/>'.$res['mensaje']:$res['mensaje'];

			if($mensaje_done)
    			$this->session->set_flashdata('done', $mensaje_done);
    		if($mensaje_error)
    			$this->session->set_flashdata('error', $mensaje_error);

			return;
    	}

		$datos['ibs'] = (!empty($_POST['ibs'])) ? $_POST['ibs'] : $cotizacion->ibs;
		$datos['cupon_cliente_email'] = (!empty($_POST['cupon_cliente_email'])) ? $_POST['cupon_cliente_email'] : $cotizacion->cupon_cliente_email;
		$datos['guia_mecanica_cliente_email'] = (!empty($_POST['guia_mecanica_cliente_email'])) ? $_POST['guia_mecanica_cliente_email'] : $cotizacion->guia_mecanica_cliente_email;
    	$datos['cotizaciones_id'] = $cotizaciones_id;
    	$datos['titulo']='Agregar Orden de Venta IBS';

    	$this->load->view('cotizaciones/ibs',$datos);
    }

	/**
	 * PERMITE DESCARGAR LA ORDEN FIRMADA Y EL RECIBO DE PAGO DE UNA COTIZACIÓN U ORDEN DE COMPRA
	 */
	function descargar_archivos($cotizaciones_id)
	{
		$zip = new ZipArchive();
		$folio_compra = $this->base->get_dato('folio_compra','cotizaciones',array('id'=>$cotizaciones_id));
		$zip_name = "/var/tmp/miele_{$folio_compra}.zip";

		if ($zip->open($zip_name, ZipArchive::CREATE)===TRUE)
		{
			$orden_firmada = $this->base->get_dato('orden_firmada_cdn','cotizaciones',array('id'=>$cotizaciones_id));
			$orden_firmada_extension = substr($orden_firmada,-3);
			$recibo_pago = $this->base->get_dato('recibo_pago_cdn','cotizaciones',array('id'=>$cotizaciones_id));
			$recibo_pago_extension = substr($recibo_pago,-3);

			$zip_content = FALSE;
			if($orden_firmada)
			{
				$orden_firmada = file_get_contents($orden_firmada);
				$add = $zip->addFromString("{$folio_compra}/orden_firmada.{$orden_firmada_extension}",$orden_firmada);
				if($add)
					$zip_content = TRUE;
			}

			if($recibo_pago)
			{
				$recibo_pago = file_get_contents($recibo_pago);
				$add = $zip->addFromString("{$folio_compra}/recibo_pago.{$recibo_pago_extension}",$recibo_pago);
				if($add)
					$zip_content = TRUE;
			}

			$zip->close();

			if($this->session->userdata('usuario')=='fquiroz')
			{
//				debug($orden_firmada);
//				debug($recibo_pago);
//				debug($folio_compra);
//				debug($zip_name);
//				debug(file_exists($zip_name));
			}

			if($zip_content)
			{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.$zip_name);
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($zip_name));
				readfile($zip_name);

//				if($this->session->userdata('usuario')=='fquiroz')
//					debug($zip_content);exit;

				unlink($zip_name);
				exit;
			}
			else
			{
				$this->session->set_flashdata('error', 'No fue posible descargar los archivos de la orden de compra, por favor intente nuevamente.');
				redirect('cotizaciones/index/id/'.$cotizaciones_id);
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'No fue posible crear el fichero para descargar los archivos, por favor intente nuevamente.');
			redirect('cotizaciones/index/id/'.$cotizaciones_id);
		}
	}

	function cupon_enviar_email($cotizaciones_id, $email=false, $cupones_folios, $total_cliente)
	{
		$debug=FALSE;
		if($this->session->userdata('usuario')=='fquiroz')
			$debug=FALSE;

		if(empty($email))
			return false;

		$cupon_folio_id = $cupones_folios[0]->id;
		$cupon_folio = $cupones_folios[0]->folio;
		$cupon_id = $cupones_folios[0]->cupones_id;

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

        if($this->config->item('cloudfiles'))
		    $cupon_ruta = $this->cloud_files->url_publica("files/cupones/{$cupon_id}/{$imagen_id}.jpg");
        else
            $cupon_ruta = site_url("files/cupones/{$cupon_id}/{$imagen_id}.jpg");

		$data['folio_compra'] = $folio_compra = $this->base->get_dato('folio_compra', 'cotizaciones', array('id'=>$cotizaciones_id));
		$data['cupon_folio'] = $cupon_folio;

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

		if($debug)
		{
			debug($cupon_ruta);
			debug(file_exists($file));
		}

		$cupon_ruta = $this->base->guarda_imagen($file,'files/cupones/'.$cupon_id,$cupon_folio.'-reenvio',FALSE);

		// Clear Memory
		imagedestroy($jpg_image);

		if($debug)
			debug($cupon_ruta);

		$data['cupon_ruta'] = $cupon_ruta;
		$data['visualizar_cupon'] = $this->cupon_visualizar_get($folio_compra, $cupon_id, $cupon_folio_id);

		$this->load->library('email');
		$email_sax = $this->config->item('email');
		$this->email->from($email_sax[0], $email_sax[1]);

		$vendedor = $this->base->value_get('usuarios',$this->session->userdata('id'),'email');
		$this->email->to($email);
		$this->email->bcc($vendedor);

		$this->email->subject('Miele agradece su preferencia.');
		$mensaje = $this->load->view('email/cotizacion_cupon',$data, TRUE);

		if($debug)
		{
			debug($mensaje);
			exit;
		}

		$this->email->message($mensaje);
		$result = $this->email->send();

		// Actualiza las tablas cotizaciones
		$data=array();
		$data['id']=$cotizaciones_id;
		$data['cupon_enviado_id']=$cupon_id;
		$data['cupon_folio_enviado_id']=$cupon_folio_id;
		$data['folio_cupon']=$cupon_folio;
		$data['cupon_cliente_email']=$email;
		$this->base->guarda('cotizaciones', $data);

		// Actualiza las tablas cupones_folios
		$data=array();
		$data['id']=$cupon_folio_id;
		$data['cotizaciones_id']=$cotizaciones_id;
		$datos['cupon_enviado_url']=$cupon_ruta;
		$data['producto_regalo_id']=$producto_regalo_id;
		$data['fecha_envio']=date('Y-m-d H:i:s');
		$this->base->guarda('cupones_folios', $data);

		if($this->config->item('debug_mail')) // DEBUG DE MAIL
		{
			debug($this->email->print_debugger(),null,1);
		}

		$res = array();
		if($result)
		{
			$res['mensaje'] = 'El cup&oacute;n de la cotizaci&oacute;n ha sido enviado correctamente.';
			$res['exito'] = TRUE;
		}
		else
		{
			$res['mensaje'] = 'El cup&oacute;n de la cotizaci&oacute;n no ha sido enviado correctamente, por favor int&eacute;ntelo nuevamente m&aacute;s tarde. Si el problema persiste p&oacute;ngase en contacto con el administrador.';
			$res['exito'] = FALSE;
		}

		return $res;
	}

	function cupon_visualizar_get($folio_compra, $cupon_id, $cupon_folio_id)
	{
		$datos_encriptados = $folio_compra.','.$cupon_id.','.$cupon_folio_id;

		$cadena = $this->encrypt_string($datos_encriptados);

		$ruta_enlace = $this->config->item('url').'frontends/visualizar_cupon/'.$cadena;

		return $ruta_enlace;
	}

	function guia_mecanica_enviar_email($cotizaciones_id, $email=false)
	{
		if(empty($email))
			return FALSE;

		$guias_mecanicas = array();
		$cdn = $this->config->item('cdn');
		$carta_bienvenida = FCPATH.'files/terminos/carta_bienvenida.pdf';
		$carta_servicio = FCPATH.'files/terminos/carta_servicio.pdf';

		$guias_productos = array();
		$productos_ids = $this->Cotizacion->get_productos_ids($cotizaciones_id);
		foreach($productos_ids as $productos_id)
		{
			$producto = $this->base->get_datos(array('guia_mecanica_orden','guia_mecanica_extension','item'),'productos',array('id'=>$productos_id));
			if($producto->guia_mecanica_orden && $producto->guia_mecanica_extension)
			{
				$guias_productos [$productos_id] = array(
					'path' => $cdn."/files/productos/{$productos_id}/guia_mecanica_{$producto->guia_mecanica_orden}.{$producto->guia_mecanica_extension}",
					'nombre' => 'guia_mecanica_'.$producto->item
				);
			}
		}

		$guias_accesorios = array();
		$accesorios_ids = $this->Cotizacion->get_accesorios_ids($cotizaciones_id);
		foreach($accesorios_ids as $accesorio_id)
		{
			$accesorio = $this->base->get_datos(array('guia_mecanica_orden','guia_mecanica_extension'),'accesorios',array('id'=>$accesorio_id));
			if($accesorio->guia_mecanica_orden && $accesorio->guia_mecanica_extension)
			{
				$guias_accesorios [$accesorio_id] = array(
					'path' => $cdn."/files/accesorios/{$accesorio_id}/guia_mecanica_{$accesorio->guia_mecanica_orden}.{$accesorio->guia_mecanica_extension}",
					'nombre' => 'guia_mecanica_'.$accesorio->item
				);
			}
		}

		if($guias_productos)
		{
			foreach ($guias_productos as $gp)
				$guias_mecanicas [] = $gp;
		}

		if($guias_accesorios)
		{
			foreach ($guias_accesorios as $ga)
				$guias_mecanicas [] = $ga;
		}

		$mensaje_done = 'La carta bienvenida y la carta de servicio han sido enviadas correctamente.';
		$res = array();
		$ibs = $this->base->get_dato('ibs','cotizaciones',array('id'=>$cotizaciones_id));
		$zip_name = "/var/tmp/miele_guias_mecanicas_{$ibs}.zip";
		if(!empty($guias_mecanicas))
		{
			$zip = new ZipArchive();
			if ($zip->open($zip_name, ZipArchive::CREATE)===TRUE)
			{
				$zip_content = FALSE;
				foreach ($guias_mecanicas as $gm)
				{
					$guia_path = $gm['path'];
					$guia_extension = substr($gm['path'],-3);

					$guia_mecanica = file_get_contents($guia_path);
					$add = $zip->addFromString("GUIAS_MECANICAS/{$gm['nombre']}.{$guia_extension}",$guia_mecanica);
					if($add)
						$zip_content = TRUE;
				}

				$zip->close();

				if($zip_content && file_exists($zip_name))
				{
					if(filesize($zip_name) > $this->config->item('guias_mecanicas_zip_size'))
					{
						$res['mensaje'] = 'El tamaño de los archivos es superior a 5MB, por favor verifique las guías mecánicas e inténtelo más tarde.';
						$res['exito'] = FALSE;
						return $res;
					}
				}
				else
				{
					$res['mensaje'] = 'No se pudo leer el fichero con los archivos de las guías mecánicas, por favor intente nuevamente.';
					$res['exito'] = FALSE;
					return $res;
				}
			}
			else
			{
				$res['mensaje'] = 'No fue posible crear el fichero para descargar los archivos, por favor intente nuevamente.';
				$res['exito'] = FALSE;
				return $res;
			}

		}

		$this->load->library('email');
		$email_sax = $this->config->item('email');
		$this->email->from($email_sax[0], $email_sax[1]);
		$this->email->to($email);

		if(file_exists($zip_name) && !empty($guias_mecanicas))
		{
			$this->email->attach($zip_name);
			$mensaje_done = 'La carta bienvenida, la carta de servicio y las guías mecánicas han sido enviadas correctamente.';
		}

		$this->email->attach($carta_bienvenida);
		$this->email->attach($carta_servicio);
		$this->email->subject('Carta bienvenida y guías mecánicas - Orden de venta: '.$ibs.'.');

		$data['ibs']=$ibs;
		$mensaje = $this->load->view('email/guias_mecanicas',$data, TRUE);

		$this->email->message($mensaje);
		$result = $this->email->send();

		unlink($zip_name);

		// Actualiza las tablas cotizaciones
		$data=array();
		$data['id']=$cotizaciones_id;
		$data['guia_mecanica_cliente_email']=$email;
		$this->base->guarda('cotizaciones', $data);

		if($this->config->item('debug_mail')) // DEBUG DE MAIL
		{
			debug($this->email->print_debugger(),null,1);
		}

		if($result)
		{
			$res['mensaje'] = $mensaje_done;
			$res['exito'] = TRUE;
			return $res;
		}
		else
		{
			$res['mensaje'] = 'La carta bienvenida y/o las guías mecánicas no han sido enviadas correctamente, por favor int&eacute;ntelo nuevamente m&aacute;s tarde. Si el problema persiste p&oacute;ngase en contacto con el administrador.';
			$res['exito'] = FALSE;
			return $res;
		}
	}

}