<?php
require_once('base.php');
class Producto extends Base {	
	
	var $table='productos';
	var $acciones_fotos=array(
		1=>'Eliminar'
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
    	if(!empty($cond['nombre']))
    		$this->db->like('nombre',$cond['nombre']);
  
    	if(!empty($cond['modelo']))
    		$this->db->like('modelo',$cond['modelo']);
    	
    	if(!empty($cond['categorias_id']))
    		$this->db->where('categorias_id',$cond['categorias_id']);	
    		
    	if(!empty($cond['id']))
    		$this->db->where('id',$cond['id']);
    		
    	if(!empty($cond['item']))
    		$this->db->like('item',$cond['item']);
    
    	// CONDICION PARA TRAER VARIOS POR ID (UTILIZADA EN COTIZACIONES)
    	if(isset($cond['ids']) && !empty($cond['ids']))
    		$this->db->where_in('p.id',$cond['ids']);
    	
    	// CONDICION NO TRAER LOS QUE ESTAN EN LA SESION (UTILIZADA EN PAGOS Y EN FACTURACION)
    	if(isset($cond['no_ids']) && !empty($cond['no_ids']))
    		$this->db->where_not_in('p.id',$cond['no_ids']);	   
   
    }
    
    public function find($cond,$limit,$offset)
    {
    	
    	$this->pconditions($cond);
		$this->db->select("
	    		p.id,
	    		p.modelo,
	    		p.nombre,
	   			p.categorias_id,
		    	p.descripcion,
		    	p.precio,
		    	p.tipo_moneda_id,
		    	p.costo,
		    	p.item,
		    	p.activo",FALSE);
		$this->db->where('eliminado',0);
		$this->db->from('productos as p');
    	$this->db->order_by('p.id','DESC');

		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result();
		
		foreach($r as &$p)
		{
			$p->img_id=$this->get_imagen_principal($p->id);
			$p->accesorios=$this->get_accesorios_producto($p->id);
		}
	
    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('productos as p');
    	$r=$this->db->count_all_results();	
    	
    	return $r;
    }
    
 	public function get_fotografias($productos_id,$limit,$offset)
    { 
    	$this->db->where('eliminado',0);
    	$this->db->where('productos_id',$productos_id);
    	$this->db->order_by('orden','ASC');
    	
    	if($limit)
			$this->db->limit($limit,$offset);
    	
    	$q=$this->db->get('fotografias_productos')->result_object();
    	return $q;
    }
    
    public function count_fotografias($productos_id)
    {
    	$this->db->where('eliminado',0);
    	$this->db->where('productos_id',$productos_id);
    	return $this->db->count_all_results('fotografias_productos');
    }
    
	function agregar_foto($productos_id,$file,$es_imagen)
    {
    	$this->db->where('productos_id',$productos_id);
    	$this->db->select('orden');
    	$this->db->order_by('orden','DESC');
    	$q=$this->db->get('fotografias_productos',1)->row();
    	
    	$orden=1;
    	if(!empty($q))
    		$orden=$q->orden+1;
    	$data['orden']=$orden;
    	$data['productos_id']=$productos_id;
    	
    	if($es_imagen)
    		$extension='jpg';
    	else 
    		$extension='pdf';
    	
    	$data['extension']=$extension;
    	$id=$this->guarda('fotografias_productos',$data);
    	
    	if($es_imagen)
    		$guarda=$this->guarda_imagen($file,'files/productos/'.$productos_id,$id,$this->config->item('avaluo_image_size'));
    	else
    		$guarda=$this->guarda_archivo($file,'files/productos/'.$productos_id,$id.'.pdf');
    	
    	
    	if(!$guarda)
    	{
    		$data=array();
    		$this->db->where('id',$id);
			$data['eliminado']=1;
			$this->db->update('productos_fotos',$data);
			return FALSE;
    	}else{
    	  	 return $id;
    	} 	 
    }
    
    
	public function get_acciones_fotos()
    {
    	$acciones_fotos=$this->acciones_fotos;	
    	return $acciones_fotos;
    }
    
 	public function fotos_eliminar($id)
    {
    	$data['id']=$id;
    	$data['eliminado']=1;
    	return parent::guarda('fotografias_productos', $data);	
    }
    
    public function get_imagen_principal($productos_id)
    {
    	$this->db->where('productos_id',$productos_id);
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->where('tipos_id',1);
    	$this->db->order_by('orden','ASC');
    	$this->db->select('id');
    	$this->db->limit(1);
    	$r=$this->db->get('fotografias_productos')->row();
    	if(!empty($r))
    		return $r->id;
    	return FALSE;
    }
    
    public function get_accesorios_producto($productos_id,$obligatorio=FALSE)
    {
    	// OBTIENE LOS ACCESORIOS OBLIGATORIOS U OPCIONALES DE UN PRODUCTO SEGUN LOS TIPOS DE ACCESORIOS QUE TENGA CONFIGURADOS
    	$this->db->select('tipos_accesorios_id');
    	$this->db->where('eliminado',0);
    	$this->db->where('productos_id',$productos_id);
    	
    	if($obligatorio)
    		$this->db->where('obligatorio_id', 1);
    	else
    		$this->db->where('obligatorio_id != 1');
    	
    	$r=$this->db->get('productos_tipos_accesorios')->result();
    	$accesorios=array();
    	foreach($r as $ro)
    	{
    		$this->db->where('eliminado',0);
    		$this->db->where('activo',1);
    		$this->db->where('tipos_accesorios_id',$ro->tipos_accesorios_id);
    		$acc=$this->db->get('accesorios')->result();
    		
    		if(!empty($acc))
    		{
    			foreach($acc as $a)
    				$accesorios[]=$a;
    		}
    	}
    	
    	return $accesorios;
    }
    
    /*public function get_accesorios_obligatorios($productos_id) {
        $this->db->select('tipos_accesorios_id');
        $this->db->where('eliminado',0);
        $this->db->where('obligatorio_id', 1);
        $this->db->where('productos_id',$productos_id);
        $r=$this->db->get('productos_tipos_accesorios')->result();
         
        $accesorios=array();
        foreach($r as &$ro)
        {
            $this->db->where('eliminado',0);
            $this->db->where('activo',1);
            $this->db->where('tipos_accesorios_id',$ro->tipos_accesorios_id);
            $accesorios[]=$this->db->get('accesorios')->row();
        }
         
        return $accesorios;
    }*/
    
    public function get_productos($categorias_id,$limit,$page)
    {
    	if(!$limit)
    		$limit=$this->config->item('front_por_pagina');
    	if(!$page)
    		$page=1;	
    		
    	//$this->db->limit($limit,($page-1)*$limit);
    	$this->db->select('id, modelo, categorias_id,precio,nombre');
    	$this->db->where('categorias_id',$categorias_id);
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->order_by('id','DESC');
    	$r=$this->db->get('productos')->result();
    	foreach($r as &$p)
    	{
    		$p->img_id=$this->get_imagen_principal($p->id);
    	}
    	return $r;
    }
    
    public function get_tipos_accesorios($productos_id)
    {
    	$this->db->select('tipos_accesorios_id,obligatorio_id');
   	  	$this->db->where('productos_id',$productos_id);
    	$this->db->where('eliminado',0);
    	$r=$this->db->get('productos_tipos_accesorios')->result();
    	
    	return $r;
    }
    
    public function productos_accesorios_eliminar($productos_id)
 	{
 		$data=array();
 		$data['eliminado']=1;
   	  	$this->db->where('productos_id', $productos_id);
		$this->db->update('productos_tipos_accesorios', $data); 
    
    	return TRUE;
    }
 
    public function get_num_pages($categorias_id,$limit)
    {
    	if(!$limit)
    		$limit=$this->config->item('front_por_pagina');
    	$this->db->where('categorias_id',$categorias_id);
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);	
    	$r=$this->db->count_all_results('productos');
    	$p=ceil($r/$limit);
    	return $p;
    }

    public function exportar($productos)
    {
    	require_once(APPPATH.'libraries/Excel/PHPExcel.php');
    	require_once(APPPATH.'libraries/Excel/PHPExcel/IOFactory.php');
    	
    	$objPHPExcel = new PHPExcel();
    	
    	// Set properties
    	$objPHPExcel->getProperties()->setCreator("Blackcore")
    		->setLastModifiedBy("Blackcore")
	    	->setTitle("Productos")
	    	->setSubject("Productos")
	    	->setDescription("Productos")
	    	->setKeywords("productos")
	    	->setCategory("Productos");
    	
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
    		->setCellValue('A1', 'SKU')
    		->setCellValue('B1', 'NOMBRE')
			->setCellValue('C1', 'MODELO')
    		->setCellValue('D1', 'PRECIO')
    		->setCellValue('E1', 'DESCRIPCIÓN');
    	
    	$worksheet->getStyle('A1:E1')->applyFromArray($encabezado_bold);
    	
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
    	
    	$i=0;
    	$y=2;
    	foreach($productos as $p=>$v)
    	{
    		if($i%2==0)
    			$worksheet->getStyle("A{$y}:E{$y}")->applyFromArray($blanco);
    		else
    			$worksheet->getStyle("A{$y}:E{$y}")->applyFromArray($gris);
    		
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("A{$y}", $v->item, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("B{$y}", $v->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("C{$y}", $v->modelo, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("D{$y}", $v->precio, PHPExcel_Cell_DataType::TYPE_STRING);
    		$objPHPExcel->getActiveSheet()->setCellValueExplicit("E{$y}", $v->descripcion, PHPExcel_Cell_DataType::TYPE_STRING);
    		$y++;
    		$i++;
    	}
    	
    	$worksheet->getColumnDimension('A')->setAutoSize(true);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(75);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    	$worksheet->getColumnDimension('E')->setAutoSize(true);
    	
    	// Nombre de la hoja
    	$worksheet->setTitle('Productos - Miele');
    	
    	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
    	$objPHPExcel->setActiveSheetIndex(0);
    	
    	// Redirect output to a clientâ€™s web browser (Excel5)
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment;filename="'.'miele_productos.xls"');
    	header('Cache-Control: max-age=0');
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
    	$objWriter->save('php://output');
    	exit;
    }
}