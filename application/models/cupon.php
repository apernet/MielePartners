<?php
require_once('base.php');
class Cupon extends Base {

	var $opciones = array(
		1 => array('Porcentaje de Descuento ( %s % )','porcentaje_descuento'),
		2 => array('Meses sin Intereses ( %s )','meses_sin_intereses')
	);

	public function __construct()
	{
		parent::__construct();    
	}
	
	private function pconditions($cond)
    {
    	if(!empty($cond['activo']))
    	{
    		if($cond['activo']==1)
    			$this->db->where('activo',1);
    		if($cond['activo']==2)
    			$this->db->where('activo',0);
    	}
    	
		if(!empty($cond['id']))
			$this->db->where('c.id',$cond['id']);

    	if(!empty($cond['alianzas_id']))
	    		$this->db->where('c.alianza_id',$cond['alianzas_id']);
    	
    	if(!empty($cond['vigencia_desde']) && empty($cond['vigencia_hasta']))
    		$this->db->where("c.vigencia_desde >= '{$cond['vigencia_desde']}'");

    	if(!empty($cond['vigencia_hasta']) && empty($cond['vigencia_desde']))
    		$this->db->where("c.vigencia_hasta <= '{$cond['vigencia_hasta']}'");
    	
    	if(!empty($cond['vigencia_desde']) && !empty($cond['vigencia_hasta']))
    	{
    		$this->db->where("c.vigencia_desde >= '{$cond['vigencia_desde']}'");
    		$this->db->where("c.vigencia_hasta <= '{$cond['vigencia_hasta']}'");
    	}
    	
    	if(!empty($cond['porcentaje_descuento']))
    		$this->db->like('c.porcentaje_descuento',$cond['porcentaje_descuento']);
  
    	if(!empty($cond['vigente']) && $cond['vigente']=="Sí")
    	{
    		$this->db->where("(c.vigencia_desde <= CURDATE() AND c.vigencia_hasta >= CURDATE())");
    	}elseif(!empty($cond['vigente']) && $cond['vigente']=="No")
    	{
    		$this->db->where("(c.vigencia_desde > CURDATE() OR c.vigencia_hasta < CURDATE())");
    	}

    }
    
    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
		$this->db->select("
	    		c.id,
	    		c.alianza_id,
	    		DATE_FORMAT(c.vigencia_desde,'%d-%m-%Y') as vigencia_desde,
	   			DATE_FORMAT(c.vigencia_hasta,'%d-%m-%Y') as vigencia_hasta,
				(c.vigencia_desde <= CURDATE() && c.vigencia_hasta >= CURDATE()) as vigente,
		    	c.porcentaje_descuento,
		    	c.meses_sin_intereses,
		    	c.numero_folios,
		    	c.activo",FALSE);
		$this->db->where('eliminado',0);
		$this->db->from('cupones as c');
    	$this->db->order_by('c.created','DESC');

		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result();

    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('cupones as c');
    	$r=$this->db->count_all_results();	
    	
    	return $r;
    }
    
    public function get_accesorios($cupon_id, $consumible=0)
    {
    	$this->db->select('accesorios_id');
    	$this->db->where('cupones_id', $cupon_id);
    	$this->db->where('consumible', $consumible);
    	$q=$this->db->get('cupones_accesorios')->result();
    	
    	$accesorios_ids=array();
    	foreach($q as $k=>$v)
    	{
    		$accesorios_ids[]=$v->accesorios_id;
    	}
    	
    	return $accesorios_ids;
    }
    
    public function get_categorias($cupon_id)
    {
    	$this->db->select('productos_categorias_id');
    	$this->db->where('cupones_id',$cupon_id);
    	$q=$this->db->get('cupones_categorias')->result();
    	
    	$categorias_ids=array();
    	foreach($q as $k=>$v)
    	{
    		$categorias_ids[]=$v->productos_categorias_id;
    	}
    	 
    	return $categorias_ids;
    }
    
    public function get_productos($cupon_id)
    {
    	$this->db->select('productos_id');
    	$this->db->where('cupones_id',$cupon_id);
    	$q=$this->db->get('cupones_productos')->result();

    	$productos_ids=array();
    	foreach($q as $k=>$v)
    	{
    		$productos_ids[]=$v->productos_id;
    	}

    	return $productos_ids;
    }

	public function get_cuentas($cupon_id)
	{
		$this->db->select('cuentas_id');
		$this->db->where('cupones_id',$cupon_id);
		$q=$this->db->get('cupones_cuentas')->result();

		$cuentas_ids=array();
		foreach($q as $k=>$v)
		{
			$cuentas_ids[]=$v->cuentas_id;
		}

		return $cuentas_ids;
	}

	public function get_imagenes($cupon_id, $ids=false)
	{
		$this->db->where('cupones_id',$cupon_id);
		$this->db->where('eliminado',0);
		$q = $this->db->get('cupones_imagenes')->result();

		if($ids)
		{
			$cupones_ids=array();
			foreach($q as $k=>$v)
			{
				$cupones_ids[]=$v->id;
			}
			return $cupones_ids;
		}

		return $q;
	}
    
    public function random_string($length = 5)
    {
    	$caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$caracteresLongitud = strlen($caracteres)-1;
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
    		$randomString .= $caracteres[rand(0, $caracteresLongitud)];
    	}
    	return $randomString;
    }
    
    public function busca_folio($folio, $cupon_id, $alianza_id)
    {
    	$this->db->from('cupones_folios');
    	$this->db->where('cupones_id',$cupon_id);
    	$this->db->where("folio LIKE '%{$folio}%'");
    	$q=$this->db->count_all_results();
    	
    	return $q;
    }
    
    public function crear_folios_cupon($cupon_id, $alianza_id, $numero_folios)
    {
    	$alianzas = elemento('alianzas',$alianza_id);
		$alianzas = str_replace(' ','',$alianzas);
		$alianzas = substr($alianzas,0,9);

		$num_digitos = strlen($numero_folios."");

    	for($i=1; $i<$numero_folios+1; $i++)
    	{
    		
    		$j = strlen($i);
			$aux='';
    		while ($j < $num_digitos)
    		{
    			$aux .= '0';
    			$j++;
    		}

    		$cad=$this->random_string();
    		$folio=$alianzas."".$cad."".$aux.$i;
    		$folio_nuevo=$this->busca_folio($cad, $cupon_id, $alianza_id);
    		
    		if($folio_nuevo==0)
    		{
		    	$data=array(
		    		'folio'=>$folio,
		    		'cupones_id'=>$cupon_id,
		    		'alianza_id'=>$alianza_id,
		    	);
		    	$this->base->guarda('cupones_folios', $data);
    		}else
    		{
    			while($folio_nuevo!=0)
    			{
	    			$cad=$this->random_string();
	    			$folio=$alianzas."".$cad."".$aux.$i;
	    			$folio_nuevo=$this->busca_folio($cad, $cupon_id, $alianza_id);
    			}
    			
    			$data=array(
    					'folio'=>$folio,
		    			'cupones_id'=>$cupon_id,
		    			'alianza_id'=>$alianza_id,
    			);
    			$this->base->guarda('cupones_folios', $data);
    		}
    	}
    }
    
    public function exportar($cupon_id)
    {
    	require_once(APPPATH.'libraries/Excel/PHPExcel.php');
    	require_once(APPPATH.'libraries/Excel/PHPExcel/IOFactory.php');
    	 
    	$objPHPExcel = new PHPExcel();
    	 
    	// Set properties
    	$objPHPExcel->getProperties()->setCreator("Blackcore")
    	->setLastModifiedBy("Blackcore")
    	->setTitle("Cupones")
    	->setSubject("Cupones")
    	->setDescription("Cupones")
    	->setKeywords("Cupones")
    	->setCategory("Cupones");
    	 
    	$worksheet = $objPHPExcel->getActiveSheet();
    	 
    	// FORMATO ENCABEZADOS
    	$encabezado_bold=array(
    			'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BE0712'),),//ROJO
    			'font'=>array('name' => 'Arial', 'bold' => TRUE, 'size' => 10, 'color'=> array('rgb' => 'FFFFFF'),),
    			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
    			'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
    	);
    	 
    	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A1', 'CUPÓN')
    	->setCellValue('B1', 'FOLIO')
    	->setCellValue('C1', 'VÁLIDO DESDE')
    	->setCellValue('D1', 'VÁLIDO HASTA')
    	->setCellValue('E1', 'PORCENTAJE DE DESCUENTO')
    	->setCellValue('F1', 'MESES SIN INTERESES');
    	 
    	$worksheet->getStyle('A1:F1')->applyFromArray($encabezado_bold);
    	 
    	$blanco=array(
    			'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFFFF'),),//BLANCO
    			'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
    			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
    			'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
    	);
    	 
    	$gris=array(
    			'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BFBFBF'),),//GRIS
    			'font'=>array('name' => 'Arial', 'bold' => FALSE, 'size' => 10, 'color'=> array('rgb' => '000000'),),
    			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
    			'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
    	);
    	 
    	$i=0;
    	$y=2;
    	
    	$this->db->select("cf.cupones_id, cf.folio, DATE_FORMAT(c.vigencia_desde,'%d-%m-%Y') as vigencia_desde, DATE_FORMAT(c.vigencia_hasta,'%d-%m-%Y') as vigencia_hasta, c.porcentaje_descuento, c.meses_sin_intereses",FALSE);
    	$this->db->from('cupones_folios as cf, cupones as c');
    	$this->db->where('cf.cupones_id', $cupon_id);
    	$this->db->where('c.id', $cupon_id);
    	$this->db->order_by('cf.id','ASC');
    	$folios_cupon=$this->db->get()->result();
    	
    	foreach($folios_cupon as $p=>$v)
    	{
    		if($i%2==0)
    			$worksheet->getStyle("A{$y}:F{$y}")->applyFromArray($blanco);
    		else
    			$worksheet->getStyle("A{$y}:F{$y}")->applyFromArray($gris);
    
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("A{$y}", $v->cupones_id, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("B{$y}", $v->folio, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->getStyle("B{$y}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("C{$y}", $v->vigencia_desde, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("D{$y}", $v->vigencia_hasta, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("E{$y}", $v->porcentaje_descuento, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("F{$y}", $v->meses_sin_intereses, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    		$y++;
    		$i++;
    	}
    	
    	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    	 
    	// Nombre de la hoja
    	$worksheet->setTitle('Cupones - Miele');
    	 
    	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    	$objPHPExcel->setActiveSheetIndex(0);
    	 
    	// Redirect output to a clientâ€™s web browser (Excel5)
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="'.'miele_cupones.xls"');
    	header('Cache-Control: max-age=0');
    	 
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    	$objWriter->save('php://output');
    	exit;
    }

	/**
	 * VALIDA QUE UN CUPON EXISTA EN LA BD Y QUE PUEDA SER APLICADO A LA ORDEN DE COMPRA (QUE NO ESTÉ USADO)
	 * RECUPERA OPCIONES DISPONIBLES DEL CUPON
	 */
	public function validar()
	{
		$res = array();

		$this->db->select('folio, cupones_id, alianza_id, fecha_envio');
		$this->db->where('folio',$_POST['folio_cupon']);
		$this->db->where("usado IS NULL OR usado = 0");

		$existe = $this->db->get('cupones_folios')->row();

		if(!empty($existe))
		{
			$res['cupones_id'] = $existe->cupones_id;

			$cuentas_ids = $this->get_cuentas($existe->cupones_id);

			$datetime1 = strtotime($existe->fecha_envio);
			$datetime2 = strtotime('now');
			$vigencia = $this->config->item('vigencia_folio_cupon');
			$diferencia = $datetime2 - $datetime1;

			$res['valido']=FALSE;

			if(in_array($this->session->userdata('cuentas_id'),$cuentas_ids) && $diferencia<=$vigencia)
			{
				$res['valido'] = $this->validar_vigencia($existe->cupones_id);

				if($res['valido'])
				{
					$res['opciones'] = $this->opciones_get($existe->cupones_id);

					$cupon = array('cupones_id' => $existe->cupones_id, 'folio_cupon' => $existe->folio);
					$this->session->set_userdata('cupon', $cupon);
				}
			}
		}
		else
			$res['valido']=FALSE;

		return $res;
	}

	/**
	 * VALIDA QUE EL CUPÓN ESTE VIGENTE Y ACTIVO
	 */
	public function validar_vigencia($cupones_id)
	{
		$this->db->where("id",$cupones_id);
		$this->db->where("activo",1);
		$this->db->where("vigencia_desde < CURDATE() AND vigencia_hasta > CURDATE()");
		$valido = $this->db->count_all_results('cupones');

		return $valido;
	}

	/**
	 * RECUPERA LAS OPCIONES CONFIGURADAS EN LOS CUPONES QUE SERÁN APLICADAS A LA ORDEN DE COMPRA
	 */
	public function opciones_get($cupones_id)
	{
		$this->db->select('porcentaje_descuento, meses_sin_intereses');
		$this->db->where('id',$cupones_id);
		$res = $this->db->get('cupones')->row_array();

		$opciones = array();
		foreach ($this->opciones as $id=>$label)
		{
			$val = (int) $res[$label[1]];
			if(!empty($val))
				$opciones[$id]['label'] = str_replace('%s',$val,$label[0]);
		}

		return $opciones;
	}

	/**
	 * OBTINE LOS IDS Y EL PORCENTAJE DE DESCUENTO DE LOS ARTICULOS PARTICIPANTES DE UN CUPÓN
	 */
	public function articulos_participantes_get($cupones_id)
	{
		$datos = array();
		$datos['cupon'] = $this->base->read('cupones',$cupones_id, TRUE);
		$datos['accesorios_cupon']=$this->Cupon->get_accesorios($cupones_id);
		$datos['consumibles_cupon']=$this->Cupon->get_accesorios($cupones_id, 1);
		$datos['categorias_cupon']=$this->Cupon->get_categorias($cupones_id);
		$datos['productos_cupon']=$this->Cupon->get_productos($cupones_id);

		return $datos;
	}

	function cupones_imagen_agregar($cupones_id,$file,$name,$cupones_imagenes_id)
	{
		$data['id']=$cupones_imagenes_id;
		$data['nombre_original']=$name;
		$data['cupones_id']=$cupones_id;

		$extension='jpg';

		$data['extension']=$extension;
		$id=$this->guarda('cupones_imagenes',$data);

		$guarda=$this->guarda_imagen($file,'files/cupones/'.$cupones_id,$id, FALSE);

		return $guarda;
	}

	public function cupones_imagenes_eliminar($id)
	{
		$this->db->where_in('id',$id);
		$this->db->update('cupones_imagenes',array('eliminado'=>1));
	}

	public function cupones_imagenes_eliminar_todas($cupones_id)
	{
		$this->db->where('cupones_id',$cupones_id);
		$this->db->update('cupones_imagenes',array('eliminado'=>1));
	}

	public function cupones_disponibles($total_cliente)
	{
		$this->db->select('id',false);
		$this->db->where('activo',1);
		$this->db->where('eliminado',0);
		$this->db->where('now()>=vigencia_desde');
		$this->db->where('now()<=vigencia_hasta');
		$cupones_vigentes = $this->db->get('cupones')->result();

		// Si no hay cupones vigentes regresa false
		if(empty($cupones_vigentes))
			return false;

		$cupones_vigentes_array = array();
		foreach($cupones_vigentes as $k=>$v)
		{
			// Obtiene los datos de los cupones
			$imagenes_cupones = $this->get_imagenes($v->id);

			if(!empty($imagenes_cupones))
			{
				foreach($imagenes_cupones as $clave=>$valor)
				{
					// Sólo se agregán cupones que entren en el rango de montos
					if($total_cliente>=$valor->monto_inicial && $total_cliente<=$valor->monto_final)
						$cupones_vigentes_array[$v->id]=$v->id;
				}
			}
		}

		// Si no hay cupones vigentes que entren en el rango de precios regresa false
		if(empty($cupones_vigentes_array))
			return false;

		$this->db->select('id, folio, cupones_id',false);
		$this->db->where_in('cupones_id',$cupones_vigentes_array);
		$this->db->where('cotizaciones_id',null);
		$this->db->where('usado',null);
		$cupones_vigentes = $this->db->get('cupones_folios')->result();

		return $cupones_vigentes;
	}

}