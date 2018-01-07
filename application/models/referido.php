<?php
require_once('base.php');
class Referido extends Base {	
	
	var $table='referidos';
	
	public function __construct()
	{
		parent::__construct();    
	}
	
	private function pconditions($cond)
    {
    	if(!$this->base->tiene_permiso('referidos_mostrar_todos'))
    	{
	    	if($this->base->tiene_permiso('referidos_mostrar_por_distribuidor'))
	    	{
	    		$distribuidores_id=array();
	    		$distribuidores_id[]=$this->session->userdata('cuentas_id');
	    		
	    		$this->db->select('cuentas_id');
	    		$this->db->where('usuarios_id',$this->session->userdata('id'));
	    		$cuentas_ids=$this->db->get('usuarios_cuentas')->result();
	    		foreach($cuentas_ids as $k=>$v)
	    		{
	    			if(!in_array($v->cuentas_id,$distribuidores_id))
	    				$distribuidores_id[]=$v->cuentas_id;
	    		}
	    		
	    		$this->db->where_in('distribuidores_id',$distribuidores_id);
	    	}
	    	else
	    		$this->db->where('vendedores_id',$this->session->userdata('id'));
    	}
    	
    	if(!empty($cond['vendedor']))
			$this->db->where("CONCAT_WS(' ',r.vendedor_nombre,r.vendedor_paterno,r.vendedor_materno) like '%{$cond['vendedor']}%'");
    	if(!empty($cond['distribuidores_id']))
    		$this->db->where('distribuidores_id',$cond['distribuidores_id']);
    	if(!empty($cond['cliente']))
			$this->db->where("CONCAT_WS(' ',r.nombre,r.apellido_paterno,r.apellido_materno) like '%{$cond['cliente']}%'");
    	if(!empty($cond['email']))
    		$this->db->like('email',$cond['email']);
    	if(!empty($cond['instalacion_estado']))
    		$this->db->where('instalacion_estado',$cond['instalacion_estado']);
    	if(!empty($cond['instalacion_municipio']))
    		$this->db->where('instalacion_municipio',$cond['instalacion_municipio']);
    	if(!empty($cond['instalacion_codigo_postal']))
    		$this->db->like('instalacion_codigo_postal',$cond['instalacion_codigo_postal']);
    	if(!empty($cond['instalacion_asentamiento']))
    		$this->db->like('instalacion_asentamiento',$cond['instalacion_asentamiento']);
    	if(!empty($cond['instalacion_calle']))
    		$this->db->like('instalacion_calle',$cond['instalacion_calle']);
    	if(!empty($cond['instalacion_numero_exterior']))
    		$this->db->like('instalacion_numero_exterior',$cond['instalacion_numero_exterior']);
    	if(!empty($cond['instalacion_numero_interior']))
    		$this->db->like('instalacion_numero_interior',$cond['instalacion_numero_interior']);

		// FILTRO FECHA DE VIGENCIA
		if (!empty($cond['fecha_inicial']) || !empty($cond['fecha_final']))
		{
			if (!empty($cond['fecha_inicial']) && empty($cond['fecha_final']))
				$this->db->where("DATE_FORMAT(vigencia,'%Y-%m-%d') >= '{$cond['fecha_inicial']}'");
			if (empty($cond['fecha_inicial']) && !empty($cond['fecha_final']))
				$this->db->where("DATE_FORMAT(vigencia,'%Y-%m-%d') <= '{$cond['fecha_final']}'");
			if (!empty($cond['fecha_inicial']) && !empty($cond['fecha_final']))
			{
				$this->db->where("DATE_FORMAT(vigencia,'%Y-%m-%d') >= '{$cond['fecha_inicial']}'");
				$this->db->where("DATE_FORMAT(vigencia,'%Y-%m-%d') <= '{$cond['fecha_final']}'");
			}
		}

		// FILTRO FECHA DE CREACIÓN
		if (!empty($cond['fecha_inicial_creacion']) || !empty($cond['fecha_final_creacion']))
		{
			if (!empty($cond['fecha_inicial_creacion']) && empty($cond['fecha_final_creacion']))
				$this->db->where("DATE_FORMAT(r.created,'%Y-%m-%d') >= '{$cond['fecha_inicial_creacion']}'");
			if (empty($cond['fecha_inicial_creacion']) && !empty($cond['fecha_final_creacion']))
				$this->db->where("DATE_FORMAT(r.created,'%Y-%m-%d') <= '{$cond['fecha_final_creacion']}'");
			if (!empty($cond['fecha_inicial_creacion']) && !empty($cond['fecha_final_creacion']))
			{
				$this->db->where("DATE_FORMAT(r.created,'%Y-%m-%d') >= '{$cond['fecha_inicial_creacion']}'");
				$this->db->where("DATE_FORMAT(r.created,'%Y-%m-%d') <= '{$cond['fecha_final_creacion']}'");
			}
		}

		// FILTRO VIGENTE
		if (@$cond['vigente']==1)
			$this->db->where('r.vigencia >= NOW()');
		elseif(@$cond['vigente']==2)
			$this->db->where('r.vigencia < NOW()');
    }
    
    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);

		$this->db->select("r.id,
						   CONCAT_WS(' ',r.nombre, r.apellido_paterno, r.apellido_materno) as cliente_nombre_completo,
						   r.email,
						   r.distribuidores_id,
						   c.nombre as distribuidor_nombre,
						   r.vendedores_id,
						   CONCAT_WS(' ',r.vendedor_nombre, r.vendedor_paterno, r.vendedor_materno) as vendedor_nombre_completo,
						   r.vigencia as fecha_vigencia,
						   r.created as fecha_referido,
						   IF(r.vigencia>=NOW(),1,2) as vigente",false);
		$this->db->from('referidos as r');
		$this->db->join('cuentas as c','r.distribuidores_id = c.id','left outer');
		$this->db->where('r.eliminado',0);
    	$this->db->order_by('id','DESC');

		if($limit)
			$this->db->limit($limit,$offset);
		$r=$this->db->get()->result();
    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('referidos as r');
    	$r=$this->db->count_all_results();	
    	
    	return $r;
    }
    
    public function get_distribuidores()
    {
    	$this->db->select('id,nombre');
    	$this->db->where('distribuidor',1);
    	$this->db->where('eliminado',0);
    	$d = $this->db->get('cuentas')->result();
    	
    	$distribuidores= array();
    	foreach($d as &$k)
    	{
    		$distribuidores[$k->id] =$k->nombre;
    	}
    	
    	return $distribuidores;
    }
    
    public function get_vendedor($cuentas_id=NULL,$json=FALSE)
    {
    	if(!empty($cuentas_id))
    		$this->db->where('distribuidores_id',$cuentas_id);
    	$this->db->select('vendedor');
    	$this->db->where('eliminado',0);
    	$this->db->order_by('created','ASC');
    	$this->db->limit(1);
    	$u = $this->db->get('referidos')->row();
    	
    	if($json)
    	{
    		$msg = json_encode($u->vendedor);
    		$this->output->set_output($msg);
    	}
    	
    	return $u->vendedor;
    }
    
    public function es_distribuidor($cuentas_id)
    {
    	//Revaisamos is la cuenta al que pertenece el  usuario  es distribuidor o no.
    	$this->db->where('id',$cuentas_id);
    	$this->db->where('distribuidor',1);
    	$d = $this->db->count_all_results('cuentas');
    	$distribuidor= false;
    	if($d>0)
    		$distribuidor= true;
    	return $distribuidor;
    }
    
    public function get_vigencia($referidos_id, $campo)
    {
    	//Obtenemos la vigencia del referido...
    	$this->db->select("DATE_ADD({$campo},INTERVAL {$this->config->item('dias_vigencia')} DAY) as vigencia",FALSE);
    	$this->db->where('id',$referidos_id);
    	$fecha_vigencia =$this->db->get('referidos')->row('vigencia');
    	
    	return $fecha_vigencia;
    }
    public function referidos_existe($email)
    {
    	//Revisamos que el usuario que se esta capturando no exista o si  es el  caso  que no este vigente para poder agregarlo
    	$this->db->select('email,vigencia');
    	$this->db->where('email',$email);
    	$this->db->where('eliminado',0);
    	$this->db->order_by('created','DESC');
    	$datos = $this->db->get('referidos')->row();
 
		$existe= false;	
    	if(!empty($datos->email))
    	{
	    	$fecha_Actual= date("Y-m-d H:i:s");
	    	if($fecha_Actual < $datos->vigencia)
	    		$vigente =true;
	    	else
	    		$vigente =false;
	
	    	if($vigente && $datos->email) //Esta vigente y  existe correo
	    		$existe=true;
	    	elseif(!$vigente && $datos->email) //Esta no esta vigente y  existe correo
	    		$existe=false;
	    	else
	    		$existe=$existe;
    	}
    	return $existe;
    }
    
    public function por_email($email)
    {
        $fecha = date('Y-m-d');
        $this->db->select('MIN(id) as id, distribuidores_id,r.vendedor_nombre,r.vendedor_paterno,r.vendedor_materno');
        $this->db->where('email', $email);
        $this->db->where('eliminado', 0);
        $this->db->where("vigencia >= {$fecha}");
        $this->db->order_by("created", "desc");
        $min_email=$this->db->get('referidos')->row();
        
        $data = array('eliminado' => 1);
        $this->db->where('email',$email);
        $this->db->update('referidos',$data);
        
        $data = array('eliminado' => 0);
        $this->db->where('id',$min_email->id);
        $this->db->update('referidos',$data);
        
        return $min_email;
        
    }
    
    public function actualizar_vigencia($referidos_id)
    {
    	//Capturamos la vigencia del referido
    	$fecha_Actual= date("Y-m-d H:i:s");
    	$data=array('vigencia'=>$fecha_Actual);
    	$this->db->where('id',$referidos_id);
    	$this->db->update('referidos',$data);
    	$fecha_vigencia = $this->get_vigencia($referidos_id,'vigencia');

    	$vigencia['vigencia'] = $fecha_vigencia;
    	$vigencia['id']= $referidos_id;
    	$this->base->guarda($this->table,$vigencia);
    	
    	return $fecha_vigencia;
    }

	public function exportar($cond)
	{
		$data = $this->find($cond,null,null);

		/** Generar Excel */
		require_once(APPPATH . 'libraries/Excel/PHPExcel.php');
		require_once(APPPATH . 'libraries/Excel/PHPExcel/IOFactory.php');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator('Blackcore')
			->setLastModifiedBy('Blackcore')
			->setTitle('Referidos')
			->setSubject('Referidos')
			->setDescription('Referidos')
			->setKeywords('Referidos')
			->setCategory('Referidos');

		$columnas = array(
			array(
				'columna' => 'A',
				'titulo' => 'Id',
				'campo' => 'id',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'B',
				'titulo' => 'Distribuidor',
				'campo' => 'distribuidor_nombre',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'C',
				'titulo' => 'Nombre del Cliente',
				'campo' => 'cliente_nombre_completo',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'D',
				'titulo' => 'Email',
				'campo' => 'email',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'E',
				'titulo' => 'Vendedor',
				'campo' => 'vendedor_nombre_completo',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'F',
				'titulo' => 'Fecha vigencia',
				'campo' => 'fecha_vigencia',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'G',
				'titulo' => 'Fecha referido',
				'campo' => 'fecha_referido',
				'encabezado' => 'rojo_miele',
			),
		);

		$tipo_encabezado = array(
			'rojo_miele' => array(
				'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '8c0014')),
				'font' => array('size' => 11, 'color' => array('rgb' => 'FFFFFF')),
				'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			)
		);

//		$texto_negro = array('font'  => array('bold'  => false, 'color' => array('rgb' => '000000'), 'size'  => 11));
		$texto_rojo = array('font'  => array('bold'  => false, 'color' => array('rgb' => 'FF8080'), 'size'  => 11));

		/** Encabezado */
		foreach ($columnas as $c)
		{
			if (isset($tipo_encabezado[$c['encabezado']]))
			{
				$objPHPExcel->getActiveSheet()->getStyle($c['columna'] . '1')->applyFromArray(($tipo_encabezado[$c['encabezado']]));
			}
			$objPHPExcel->getActiveSheet()->setCellValue($c['columna'] . '1', $c['titulo']);
			$objPHPExcel->getActiveSheet()->setCellValue($c['columna'] . '1', $c['titulo']);
		}

		/** Contenido */
		if (!empty($data))
		{
			$row = 1;
			foreach ($data as $d)
			{
				$row++;
				foreach ($columnas as $c)
				{
					$celda = $c['columna'] . $row;
					if (isset($c['campo']) && $c['campo'] != '')
					{
						$celda_valor = $d->{$c['campo']};
						$objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $celda_valor, PHPExcel_Cell_DataType::TYPE_STRING);

						if($d->vigente==2)
							$objPHPExcel->getActiveSheet()->getStyle('F'.$row)->applyFromArray($texto_rojo);

					} elseif (isset($c['texto']) && $c['texto'] != '')
						$objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $c['texto'], PHPExcel_Cell_DataType::TYPE_STRING);
				}
			}
		} else
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No existen datos')->mergeCells('A2:G2');

		/** Ajuste */
		foreach ($columnas as $c)
			$objPHPExcel->getActiveSheet()->getColumnDimension($c['columna'])->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setSelectedCell('A2');

		/** Descarga Excel **/
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename=Referidos.xlsx');
		header('Content-Transfer-Encoding: binary');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	public function notificaciones_referidos_vencidos()
	{

		if($this->config->item('notificaciones_referidos'))
		{
			$this->load->library('email');

			/** Obtiene todos los referidos vencidos que cuentan con email del vendedor y que no han enviado notificación por email ni han sido eliminados  */
			$this->db->select("r.id, r.nombre, r.apellido_paterno, r.apellido_materno, CONCAT_WS(' ',r.nombre, r.apellido_paterno, r.apellido_materno) as cliente_nombre_completo, r.email, r.distribuidores_id, c.nombre as distribuidor_nombre, r.vendedores_id, CONCAT_WS(' ',vendedor_nombre, vendedor_paterno, vendedor_materno) as vendedor_nombre_completo, vendedor_email, r.vigencia as fecha_vigencia, r.created as fecha_referido",false);
			$this->db->from('referidos as r');
			$this->db->join('cuentas as c','r.distribuidores_id = c.id','left outer');
			$this->db->where('r.eliminado',0);
			$this->db->where('r.notificado',0);
			$this->db->where('r.vigencia < NOW()');
			$this->db->where('r.vendedor_email IS NOT NULL');

			$rs = $this->db->get()->result_object();

			if (!empty($rs))
			{
				foreach ($rs as $k => $v)
				{
					$this->email->clear(true);
					/** Persona que recibirá el correo */
					$email = $v->vendedor_email;

					$email_sax = $this->config->item('email');
					$this->email->from($email_sax[0], $email_sax[1]);
					$this->email->to($email);
					$this->email->subject("Notificación de referenciado vencido");

					/** Datos del correo */
					$datos['r'] = $v;
					$datos['titulo'] = 'Referido con ID: ' . $v->id . ' acaba de vencer';
					$mensaje = $this->load->view('email/referido_vencido', $datos, TRUE);

					$this->email->message($mensaje);
					$result = $this->email->send();

					if ($result)
					{
						$fecha = date("Y-m-d H:i:s");
						$this->db->where(array('id' => $v->id));
						$this->db->update('referidos', array('notificado' => 1, 'fecha_envio' => $fecha, 'email_envio' => $v->vendedor_email));
					}
				}
			}
		}
	}

}