<?php
require_once('base.php');
class Comision extends Base {	
	
	var $table='comisiones';
	
	var $status=array(
		1 => 'PENDIENTE',
		2 => 'PAGADA',
		3 => 'CANCELADA'
	);
	
	public function __construct()
	{
		parent::__construct();    
	}
    
	public function conditions($conditions)
	{
		$usuarios_cuentas_ids=$this->base->usuarios_cuentas_ids_get();
		 
		if ($this->base->tiene_permiso('comisiones_cuenta') && empty($usuarios_cuentas_ids)) // COMISIONES DE LA CUENTA DEL USUARIO EN SESION
			$this->db->where('com.cuentas_id', $this->session->userdata('cuentas_id'));
		elseif($this->base->tiene_permiso('comisiones_cuenta') && !empty($usuarios_cuentas_ids)) //COMISIONES DE LAS CUENTAS ASIGNADAS AL USUARIO INCLUYENDO LA DE LA SESSION
			$this->db->where_in('com.cuentas_id', $usuarios_cuentas_ids);
		elseif ($this->base->tiene_permiso('comisiones_propias')) // COMISIONES PROPIAS DEL USUARIO EN SESION
			$this->db->where('com.usuarios_id', $this->session->userdata('id'));
		
		if(isset($conditions['id']))
			$this->db->like('com.id',$conditions['id']);
		 
		if(isset($conditions['folio']))
			$this->db->like('cot.folio_compra',$conditions['folio']);
		 
		if(isset($conditions['distribuidor_id']))
			$this->db->where('com.cuentas_id',$conditions['distribuidor_id']);
		 
		if(isset($conditions['nombre_vendedor']))
		{
			$this->db->like("CONCAT(cot.vendedor_nombre,' ',cot.vendedor_paterno,' ',cot.vendedor_materno)", $conditions ['nombre_vendedor']);
			$this->db->where('com.usuarios_id IS NOT NULL');
		}

		// FILTRO FECHA
		if (!empty($conditions['fecha_inicial']) || !empty($conditions['fecha_final']))
		{
			if (!empty($conditions['fecha_inicial']) && empty($conditions['fecha_final']))
				$this->db->where("DATE_FORMAT(com.created,'%Y-%m-%d') >= '{$conditions['fecha_inicial']}'");
			if (empty($conditions['fecha_inicial']) && !empty($conditions['fecha_final']))
				$this->db->where("DATE_FORMAT(com.created,'%Y-%m-%d') <= '{$conditions['fecha_final']}'");
			if (!empty($conditions['fecha_inicial']) && !empty($conditions['fecha_final']))
			{
				$this->db->where("DATE_FORMAT(com.created,'%Y-%m-%d') >= '{$conditions['fecha_inicial']}'");
				$this->db->where("DATE_FORMAT(com.created,'%Y-%m-%d') <= '{$conditions['fecha_final']}'");
			}
		}

		if(isset($conditions['status_id']))
			$this->db->where('com.status_id',$conditions['status_id']);
		
		$this->db->where('com.eliminado',0);
		$this->db->from('comisiones as com');
		$this->db->join('cotizaciones as cot','cot.id=com.cotizaciones_id','left');
	}
	
    public function find($conditions,$limit,$offset)
    {
    	$this->conditions($conditions);
    	$this->db->select("com.*,
			               cot.folio_compra as folio,
			               CONCAT_WS(' ',cot.vendedor_nombre, cot.vendedor_paterno, cot.vendedor_materno) as vendedor_nombre_completo,
			               CONCAT_WS(' ',cot.referido_vendedor_nombre, cot.referido_vendedor_paterno, cot.referido_vendedor_materno) as referido_vendedor_nombre_completo,
			               cot.referido_distribuidor_id,
			               cot.descuento_paquete_id,
			               cot.fecha_autorizacion,
			               cot.ibs",FALSE);
    	$this->db->order_by('id','DESC');

		if ($limit)
			$this->db->limit($limit, $offset);

		$r = $this->db->get()->result_object();

		$distribuidores = $this->base->lista('cuentas','id','nombre',FALSE,'nombre','ASC');
		$paquetes = $this->base->lista('paquetes','id','nombre',FALSE,'nombre','ASC');
		$status = $this->status;
		foreach ($r as &$res)
		{
			$res->distribuidor = ($res->referido_distribuidor_id)?@$distribuidores[$res->referido_distribuidor_id]:@$distribuidores[$res->cuentas_id];
			$res->vendedor = ($res->usuarios_id)?((!empty($res->referido_vendedor_nombre_completo) && $res->referido_vendedor_nombre_completo!="0 0 0")?$res->referido_vendedor_nombre_completo:$res->vendedor_nombre_completo):'';
			$res->porcentaje = num($res->porcentaje).' %';
			$res->monto = moneda($res->monto);
			$res->status = $status[$res->status_id];
			$res->ibs = (!empty($res->ibs))?$res->ibs:'NO DISPONIBLE';
			$res->paquete = (!empty($res->descuento_paquete_id))?$paquetes[$res->descuento_paquete_id]:'NO DISPONIBLE';
			$res->fecha_autorizacion = (!empty($res->fecha_autorizacion))?get_fecha($res->fecha_autorizacion):'NO DISPONIBLE';
		}
    	
    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->conditions($conditions);
    		
    	return $this->db->count_all_results();	
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
			->setTitle('Comisiones')
			->setSubject('Comisiones')
			->setDescription('Comisiones')
			->setKeywords('Comisiones')
			->setCategory('Comisiones');

		$columnas = array(
			array(
				'columna' => 'A',
				'titulo' => 'Id',
				'campo' => 'id',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'B',
				'titulo' => 'Folio cotización',
				'campo' => 'folio',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'C',
				'titulo' => 'Distribuidor',
				'campo' => 'distribuidor',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'D',
				'titulo' => 'Vendedor',
				'campo' => 'vendedor',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'E',
				'titulo' => 'Porcentaje',
				'campo' => 'porcentaje',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'F',
				'titulo' => 'Monto',
				'campo' => 'monto',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'G',
				'titulo' => 'IBS',
				'campo' => 'ibs',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'H',
				'titulo' => 'Paquete',
				'campo' => 'paquete',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'I',
				'titulo' => 'Fecha de Autorización',
				'campo' => 'fecha_autorizacion',
				'encabezado' => 'rojo_miele',
			),
			array(
				'columna' => 'J',
				'titulo' => 'Status',
				'campo' => 'status',
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

					} elseif (isset($c['texto']) && $c['texto'] != '')
						$objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $c['texto'], PHPExcel_Cell_DataType::TYPE_STRING);
				}
			}
		} else
			$objPHPExcel->getActiveSheet()->setCellValue('A2', 'No existen datos')->mergeCells('A2:J2');

		/** Ajuste */
		foreach ($columnas as $c)
			$objPHPExcel->getActiveSheet()->getColumnDimension($c['columna'])->setAutoSize(true);

		$objPHPExcel->getActiveSheet()->setSelectedCell('A2');

		/** Descarga Excel **/
		header('Content-Type: application/force-download');
		header('Content-Disposition: attachment; filename=Comisiones.xlsx');
		header('Content-Transfer-Encoding: binary');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
}