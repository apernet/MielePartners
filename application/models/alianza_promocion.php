<?php
require_once('base.php');
class Alianza_promocion extends Base {

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
		if(!empty($cond['id']))
			$this->db->where('ap.id',$cond['id']);

    	if(!empty($cond['nombre']))
	    	$this->db->like('ap.nombre',$cond['nombre']);

		if(!empty($cond['numero_folios']))
			$this->db->where('ap.numero_folios',$cond['numero_folios']);

		if(!empty($cond['activo']))
		{
			if($cond['activo']==1)
				$this->db->where('ap.activo',1);
			if($cond['activo']==2)
				$this->db->where('ap.activo',0);
		}
    }
    
    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
		$this->db->select("
	    		ap.id,
	    		ap.nombre,
		    	ap.numero_folios,
		    	ap.activo",FALSE);
		$this->db->where('eliminado',0);
		$this->db->from('alianzas_promociones as ap');
    	$this->db->order_by('ap.id','DESC');

		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result();

    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('alianzas_promociones as ap');
    	$r=$this->db->count_all_results();	
    	
    	return $r;
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
    
    public function busca_folio($folio, $alianzas_promociones_id)
    {
    	$this->db->from('alianzas_folios');
    	$this->db->where('alianzas_id',$alianzas_promociones_id);
    	$this->db->where("folio LIKE '%{$folio}%'");
    	$q=$this->db->count_all_results();
    	
    	return $q;
    }
    
    public function crear_folios_alianza($alianzas_promociones_id, $prefijo, $numero_folios)
    {
		$num_digitos=strlen($numero_folios."");
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
    		$folio=$prefijo."".$cad."".$aux.$i;
    		$folio_nuevo=$this->busca_folio($cad, $alianzas_promociones_id);
    		
    		if($folio_nuevo==0)
    		{
		    	$data=array(
		    		'folio'=>$folio,
		    		'alianzas_id'=>$alianzas_promociones_id,
		    	);
		    	$this->base->guarda('alianzas_folios', $data);
    		}else
    		{
    			while($folio_nuevo!=0)
    			{
	    			$cad=$this->random_string();
	    			$folio=$prefijo."".$cad."".$aux.$i;
	    			$folio_nuevo=$this->busca_folio($cad, $alianzas_promociones_id);
    			}
    			
    			$data=array(
    					'folio'=>$folio,
		    			'alianzas_id'=>$alianzas_promociones_id,
    			);
    			$this->base->guarda('alianzas_folios', $data);
    		}
    	}
    }
    
    public function exportar($alianzas_promociones_id)
    {
    	require_once(APPPATH.'libraries/Excel/PHPExcel.php');
    	require_once(APPPATH.'libraries/Excel/PHPExcel/IOFactory.php');
    	 
    	$objPHPExcel = new PHPExcel();
    	 
    	// Set properties
    	$objPHPExcel->getProperties()->setCreator("Blackcore")
    	->setLastModifiedBy("Blackcore")
    	->setTitle("Alianzas_promociones")
    	->setSubject("Alianzas_promociones")
    	->setDescription("Alianzas_promociones")
    	->setKeywords("Alianzas_promociones")
    	->setCategory("Alianzas_promociones");
    	 
    	$worksheet = $objPHPExcel->getActiveSheet();
    	 
    	// FORMATO ENCABEZADOS
    	$encabezado_bold=array(
    			'fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'BE0712'),),//ROJO
    			'font'=>array('name' => 'Arial', 'bold' => TRUE, 'size' => 10, 'color'=> array('rgb' => 'FFFFFF'),),
    			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
    			'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => 'FFFFFF'),)),
    	);
    	 
    	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A1', 'ALIANZA')
    	->setCellValue('B1', 'FOLIO');
    	 
    	$worksheet->getStyle('A1:B1')->applyFromArray($encabezado_bold);
    	 
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
    	
    	$this->db->select("ap.nombre, af.folio",FALSE);
    	$this->db->from('alianzas_promociones as ap, alianzas_folios as af');
    	$this->db->where('ap.id', $alianzas_promociones_id);
    	$this->db->where('af.alianzas_id', $alianzas_promociones_id);
    	$this->db->order_by('af.id','ASC');
    	$folios_alianza=$this->db->get()->result();

		$nombre_alianza=$folios_alianza[0]->nombre;

    	foreach($folios_alianza as $p=>$v)
    	{
    		if($i%2==0)
    			$worksheet->getStyle("A{$y}:B{$y}")->applyFromArray($blanco);
    		else
    			$worksheet->getStyle("A{$y}:B{$y}")->applyFromArray($gris);
    
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("A{$y}", $v->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("B{$y}", $v->folio, PHPExcel_Cell_DataType::TYPE_STRING);
    		$y++;
    		$i++;
    	}
    	
    	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
    	 
    	// Nombre de la hoja
    	$worksheet->setTitle('Alianza folios');
    	 
    	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    	$objPHPExcel->setActiveSheetIndex(0);

    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="'.$nombre_alianza.'.xls"');
    	header('Cache-Control: max-age=0');
    	 
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
    	$objWriter->save('php://output');
    	exit;
    }

	public function get_alianzas()
	{
		$this->db->select('DISTINCT nombre',false);
		$this->db->where('eliminado',0);
		$r = $this->db->get('alianzas_promociones')->result();

		return $r;
	}

	public function get_alianzas_disponibles($nombres=FALSE)
	{
		$this->db->select('DISTINCT nombre, id, numero_folios',false);
		$this->db->where('activo',1);
		$this->db->where('eliminado',0);
		$r = $this->db->get('alianzas_promociones')->result();

		$i=0;
		$res=array();
		foreach($r as $d)
		{
			$total_usados = $this->base->get_dato('sum(usado)','alianzas_folios',array('alianzas_id'=>$d->id));
			$disponibles = $d->numero_folios - $total_usados;

			if($disponibles>0 && !$nombres)
			{
				$res[$i] = new stdClass();
				$res[$i]->id = $d->id;
				$res[$i]->nombre = $d->nombre;
				$i++;
			}elseif($disponibles>0 && $nombres)
			{
				$res[$d->id] = $d->nombre;
			}
		}

		return $res;
	}

}