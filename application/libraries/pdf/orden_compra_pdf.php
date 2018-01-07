<?php
require_once(APPPATH.'/libraries/tcpdf/config/lang/spa.php');
require_once(APPPATH.'/libraries/tcpdf/tcpdf.php');
class ORDEN_COMPRA_PDF extends TCPDF
{
     //var $xheadertext  = 'PDF created using CakePHP and TCPDF';
	var $xheadercolor = array(0,0,200); 
	var $logo = FALSE;
	var $textfont = 'helvetica';
	var $font_size = 13;
	var $celda = 3.5;// cambiar a 4
	var $doble_celda = 8;// cambiar a 8
	var $header = TRUE;
	var $footer =TRUE;
	var $footer_secundario=FALSE;
	var $fecha_compra = TRUE;
	var $fecha_entrega = TRUE;
	var $telefono = TRUE;
	var $partner = TRUE;
	var $r = TRUE;
	var $razon_social = TRUE;
	var $rfc = TRUE;
	var $estado = TRUE;
	var $municipio = TRUE;
	var $codigo_postal = TRUE;
	var $asentamiento = TRUE;
	var $calle = TRUE;
	var $numero_exterior = TRUE;
	var $numero_interior = TRUE;
	var $entrega_estado = TRUE;
	var $entrega_asentamiento = TRUE;
	var $entrega_municipio = TRUE;
	var $entrega_codigo_postal = TRUE;
	var $entrega_calle = TRUE;
	var $entrega_numero_exterior = TRUE;
	var $entrega_numero_interior = TRUE;
	var $nombre_contacto = TRUE;
	var $telefono_celular = TRUE;
    var $telefono_particular = TRUE;
    var $email = TRUE;
    var $clave = TRUE;
    var $folio_cuenta = TRUE;
    var $forma_pago=0;
    var $condicion_pago=0;
    var $cuenta_sucursal='';
    var $cuenta_bancaria='';
    var $cuenta_clave='';
    var $distribuidor_logo='';
	
	function Header()
    {
    	if($this->header)
    	{
    		$miele_logo= FCPATH.'img/layout/MieleImmerBesser.png';
    		if(file_exists($miele_logo))
			{
				$this->setJPEGQuality(100);
				$this->Image($miele_logo, 225, 7, 45, 13,null, null, 'M', FALSE, 130,null,FALSE,FALSE);				
			}
			
			$existe=TRUE;
			if(file_exists($this->distribuidor_logo) && !$this->venta_directa)
			{
				list($width, $height) = getimagesize($this->distribuidor_logo);
				
				$max_width=39;
				if($width>$height) // HORIZONTAL
				{
					$new_width=$max_width;
					$new_height=round(($height*$max_width)/$width,2);
				}
				
				$max_height=20;
				if($width<$height || $width==$height) // VERTICAL O CUADRADA
				{
					$new_height=$max_height;
					$new_width=round(($width*$max_height)/$height,2);
				}
				
				if($new_height>$max_height)
				{
					$new_height=$max_height;
					$new_width=$new_height*$width/$height;
				}
				
				if($new_width>$max_width)
				{
					$new_width=$max_width;
					$new_height=$new_width*$height/$width;
				}
				
				$x=10+(($max_width-$new_width)/2);
				$y=5+(($max_height-$new_height)/2);
				$this->Image($this->distribuidor_logo, $x, $y, $new_width, $new_height,null, null, 'M', FALSE, 130,null,FALSE,FALSE);
			}
			else 
				$existe=FALSE;
				
			$numero='';
        	if($this->numero_exterior)
        		$numero.='No. '.$this->partner->numero_exterior;
        	if($this->numero_interior)
        		$numero.=' Int. '.$this->partner->numero_interior;
			
    		$this->SetLineStyle(array('width' => 0.2));
        	$this->SetAutoPageBreak(TRUE, 0);
        	
        	$x=$existe?50:10;
        	$center=$existe?130:170;
    		/*$this->SetXY($x, 5);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$this->Cell(50,0,'Razón Social/Distrubuidor Autorizado Miele',0,1,'L',null,null,1);
        	$this->SetX($x);
        	$this->SetFont($this->textfont,'',$this->font_size);
        	$this->Cell(50,0,$this->partner->razon_social,0,0,'L',null,null,1);*/
        	$this->SetXY($x, 8);
      		$this->SetFont($this->textfont,'B',$this->font_size+4);
      		$this->Cell(50,0,'',0,0,'L',null,null,1);
    		$this->Cell($center,3,'ORDEN DE COMPRA',0,1,'C',null,null,1);
        	
        	$this->SetX($x);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$this->Cell(13,0,'Sucursal :',0,0,'L',null,null,1);
        	$this->SetFont($this->textfont,'',$this->font_size);
        	$this->Cell(37,0,$this->partner->sucursal_fisica, 0,1,'L',null,null,1);
        	
        	$this->SetX($x);
        	$this->SetFont($this->textfont,'',$this->font_size);
        	$this->Cell(50,0,$this->partner->sucursal_calle.' '.$numero.' Col.'.$this->partner->sucursal_asentamiento,0,0,'L',null,null,1);
        	$this->Cell($center,0,'Si tiene alguna duda con respecto a esta información, se puede comunicar al teléfono 01-800-MIELE-00(01-800-64353-00)',0,1,'C',null,null,1);
        	
        	$this->SetX($x);
        	$this->Cell(50,0,'C.P. '.$this->partner->sucursal_codigo_postal.' '.$this->partner->sucursal_municipio.' '.$this->partner->sucursal_estado,0,0,'L',null,null,1);
        	$this->Cell($center,0,'o envié un correo a info@miele.com.mx',0,1,'C',null,null,1);
        	$this->SetX($existe?100:100-40);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$this->Cell($center,0,$this->folio_compra,0,1,'C',null,null,1);
        	
        	$this->Ln(2);
		    $this->Cell(17,0,'Fecha de la orden: ', 0,0,'L',null,null,1);
			$this->Cell(102,0,get_fecha($this->cotizacion['modified']), 0,0,'L',null,null,1);
			$this->Cell(0,0,'', 0,1,'R',null,null,1);
			
			$this->Cell(80,0,'Datos de Facturación', 'LTR',0,'C',null,null,1);
		   	$this->Cell(10,0,'', 0,0,'LT',null,null,1);
			$this->Cell(80,0,'Datos de entrega', 'LTR',0,'C',null,null,1);
			$this->Cell(10,0,'', 0,0,'LT',null,null,1);
			$this->Cell(0,0,'Datos de instalación', 'LTR',1,'C',null,null,1);
			
			$cliente=$this->cotizacion['razon_social'];
			if(!empty($this->cotizacion['apellido_paterno']))
				$cliente.=' '.$this->cotizacion['apellido_paterno'];
			if(!empty($this->cotizacion['apellido_materno']))
				$cliente.=' '.$this->cotizacion['apellido_materno'];
			
		    $this->Cell(25,0,' Razón social:', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(55,0,$cliente, 'R',0,'L',null,null,1);
		   	$this->Cell(10,0,'', 0,0,'L',null,null,1);
		   	
		   	$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Nombre del contacto:', 'L',0,'L',null,null,1);
			$this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(55,0,$this->cotizacion['nombre_contacto'], 'R',0,'L',null,null,1);
		    $this->Cell(10,0,'', 0,0,'L',null,null,1);
		    
		    $this->SetFont($this->textfont,'B',$this->font_size);
		    $this->Cell(25,0,' Datos del Cliente Final:', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(0,0,$this->cotizacion['instalacion_nombre_contacto'], 'R',1,'L',null,null,1);
		        
		    $this->SetFont($this->textfont,'B',$this->font_size);
		    $this->Cell(25,0,' RFC:', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(55,0,@$this->cotizacion['rfc'], 'R',0,'L',null,null,1);
		    $this->Cell(10,0,'', 0,0,'L',null,null,1); 
		    
		    $this->Cell(25,0,' ', 'L',0,'L',null,null,1);
		    $this->Cell(55,0,'', 'R',0,'L',null,null,1);
		    $this->Cell(10,0,'', 0,0,'L',null,null,1);
		    $this->Cell(25,0,' ', 'L',0,'L',null,null,1);
		    $this->Cell(0,0,'','R',1,'L',null,null,1);
		    
		    $numero='';
		    if($this->cotizacion['numero_exterior'])
		    	$numero.='No. '.$this->cotizacion['numero_exterior'];
		    if($this->cotizacion['numero_interior'])
		    	$numero.=' Int. '.$this->cotizacion['numero_interior'];
		    
			$this->SetFont($this->textfont,'B',$this->font_size);
		    $this->Cell(25,0,' Dirección fiscal:', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(55,0,$this->cotizacion['calle'].' '.$numero, 'R',0,'L',null,null,1);
		  	$this->Cell(10,0,'', 0,0,'L',null,null,1);
		  	
		  	$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Teléfono de Contacto:', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(55,0,$this->cotizacion['telefono_particular'].' / '. $this->cotizacion['telefono_celular'], 'R',0,'L',null,null,1);
		    $this->Cell(10,0,'', 0,0,'L',null,null,1);
		    
		    $this->SetFont($this->textfont,'B',$this->font_size);
		    $this->Cell(25,0,' Teléfono particular:', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(0,0,$this->cotizacion['instalacion_telefono_particular'].($this->cotizacion['instalacion_telefono_celular']?' / '.$this->cotizacion['instalacion_telefono_celular']:''), 'R',1,'L',null,null,1);
			
		    $numero_entrega='';
		    if($this->cotizacion['entrega_numero_exterior'])
		     	$numero_entrega.='No. '.$this->cotizacion['entrega_numero_exterior'];
		    if($this->cotizacion['entrega_numero_interior'])
		        $numero_entrega.=' Int. '.$this->cotizacion['entrega_numero_interior'];
			      
			$this->Cell(25,0,'', 'L',0,'L',null,null,1);
		    $this->Cell(55,0,'Col. '.$this->cotizacion['asentamiento'].', '.$this->cotizacion['municipio'], 'R',0,'L',null,null,1);
		  	$this->Cell(10,0,'', 0,0,'L',null,null,1);
		  	
		  	$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Dirección de entrega: ', 'L',0,'L',null,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(55,0,$this->cotizacion['entrega_calle'].' '.$numero_entrega, 'R',0,'L',null,null,1);
		    $this->Cell(10,0,'', 0,0,'L',null,null,1);
		    
		    $instalacion_direccion=$this->cotizacion['instalacion_calle'];
		    if($this->cotizacion['instalacion_numero_exterior'])
		    	$instalacion_direccion.=' No. '.$this->cotizacion['instalacion_numero_exterior'];
		    if($this->cotizacion['entrega_numero_interior'])
		    	$instalacion_direccion.=' Int. '.$this->cotizacion['instalacion_numero_interior'];
		    
		    $this->SetFont($this->textfont,'B',$this->font_size);
		    $this->Cell(25,0,' Dirección de Instalación: ', 'L',0,'L',0,null,1);
		    $this->SetFont($this->textfont,'',$this->font_size);
		    $this->Cell(0,0,$instalacion_direccion, 'R',1,'L',null,null,1);
		    
		  	$this->Cell(25,0,' ', 'L',0,'L',null,null,1);
		  	$this->Cell(55,0,'C.P. '.$this->cotizacion['codigo_postal'].' '.$this->cotizacion['estado'], 'R',0,'L',null,null,1);
		   	$this->Cell(10,0,'', 0,0,'L',null,null,1);
		   	
		   	$this->Cell(25,0,' ', 'L',0,'L',null,null,1);
		   	$this->Cell(55,0,'Col. '.$this->cotizacion['entrega_asentamiento'].', '.$this->cotizacion['entrega_municipio'], 'R',0,'L',null,null,1);
		   	$this->Cell(10,0,'', 0,0,'L',null,null,1);
			$this->Cell(25,0,' ', 'L',0,'L',null,null,1);
		    $this->Cell(0,0,'Col. '.$this->cotizacion['instalacion_asentamiento'].', '.$this->cotizacion['instalacion_municipio'], 'R',1,'L',null,null,1);
		        
		    $this->SetFont($this->textfont,'B',$this->font_size);
		    $this->Cell(25,0,' Correo electrónico:', 'L',0,'L',null,null,1);
			$this->SetFont($this->textfont,'',$this->font_size);
			$this->Cell(55,0,strtolower($this->cotizacion['email']), 'R',0,'L',null,null,1);
		   	$this->Cell(10,0,'', 0,0,'L',null,null,1);
		   	
		   	$this->Cell(25,0,' ', 'L',0,'L',null,null,1);
		    $this->Cell(55,0,'C.P. '.$this->cotizacion['entrega_codigo_postal'].' '.$this->cotizacion['entrega_estado'], 'R',0,'L',null,null,1);
		    $this->Cell(10,0,'', 0,0,'L',null,null,1);
			$this->Cell(25,0,' ', 'L',0,'L',null,null,1);
			$this->Cell(0,0,'C.P. '.$this->cotizacion['instalacion_codigo_postal'].' '.$this->cotizacion['instalacion_estado'], 'R',1,'L',null,null,1);
		        
			$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Datos del vendedor:', 'L',0,'L',null,null,1);
			$this->SetFont($this->textfont,'',$this->font_size);
			$this->Cell(55,0,$this->vendedor, 'R',0,'L',null,null,1);
			$this->Cell(10,0,'', 0,0,'L',null,null,1);
			
			$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Correo electrónico:', 'L',0,'L',null,null,1);
			$this->SetFont($this->textfont,'',$this->font_size);
			$this->Cell(55,0,strtolower($this->cotizacion['email_comprador']), 'R',0,'L',null,null,1);
			$this->Cell(10,0,'', 0,0,'L',null,null,1);
			
			$this->Cell(25,0,'', 'L',0,'L',null,null,1);
			$this->Cell(0,0,'', 'R',1,'L',null,null,1);
			
			if($this->venta_directa && $this->cotizacion['referido_distribuidor_id'] && $this->cotizacion['referido_vendedor_nombre'])
			{
				$this->SetFont($this->textfont,'B',$this->font_size);
				$this->Cell(25,0,'Referenciado por: ', 'LB',0,'L',null,null,1);
				$this->SetFont($this->textfont,'',$this->font_size);
				$this->Cell(55,0,($this->cotizacion['referido_porcentaje_comision']?'100 % ':'50 % ').$this->distribuidor.' / '.$this->cotizacion['referido_vendedor_nombre'].' '.$this->cotizacion['referido_vendedor_paterno'].' '.$this->cotizacion['referido_vendedor_materno'], 'BR',0,'L',null,null,1);
			}
			else
				$this->Cell(80,0,'', 'LBR',0,'L',null,null,1);
			
			$this->Cell(10,0,'', 0,0,'L',null,null,1);
			$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Fecha Tentativa Entrega: ', 'LB',0,'L',null,null,1);
			$this->SetFont($this->textfont,'',$this->font_size);
			$this->Cell(55,0,get_fecha($this->cotizacion['entrega_fecha_tentativa']), 'RB',0,'L',null,null,1);
			$this->Cell(10,0,'', 0,0,'L',null,null,1);
			
			$this->SetFont($this->textfont,'B',$this->font_size);
			$this->Cell(25,0,' Fecha Tentativa Instalación: ', 'LB',0,'L',null,null,1);
			$this->SetFont($this->textfont,'',$this->font_size);
			$this->Cell(0,0,get_fecha($this->cotizacion['entrega_fecha_instalacion']), 'RB',1,'L',null,null,1);
        	
			$this->Ln(3);
			$this->SetFont($this->textfont,'B',$this->font_size+1.5);
			$this->SetLineStyle(array('width' => 0.4,'color'=>array(0,0,0)));
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0,0,0);
			$this->MultiCell(25, $this->celda*2,'IMAGEN',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(25, $this->celda*2,'GRUPO PRODUCTO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(20, $this->celda*2,'NÚMERO MATERIAL',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(15, $this->celda*2,'MODELO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(100, $this->celda*2,'DESCRIPCION',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(17, $this->celda*2,'CANTIDAD',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(17, $this->celda*2,'PRECIO UNITARIO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(17, $this->celda*2,'DESCUENTO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(0, $this->celda*2,'IMPORTE TOTAL',1,'C',1,1,null,null,1,1,null,1,$this->celda*2,'M',true);
    	}        
    }
    
 	function Footer()
    {
    	if($this->footer)
    	{
		 	if ($this->venta_directa)
	     	{
		    	$this->Ln(20);
		    	$y=160;
		    	$this->SetY($y);
		    	$this->SetFont($this->textfont,'B',$this->font_size);
	   			$this->Cell(127,2,'Forma de pago:',0,0,'L',null,null,1);
	   			$this->Cell(0,2,'Condiciones de pago:',0,1,'L',null,null,1);
	   			$this->SetFont($this->textfont,'',$this->font_size);

                if($this->partner->nombre!='Externos')
                {
                    $this->Cell(8, 5, ($this->forma_pago == 1) ? 'X' : '', 1, 0, 'C', null, null, 1);
                    $this->Cell(55, 2, '  Depósito / Transferencía', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 5, ($this->forma_pago == 2) ? 'X' : '', 1, 0, 'C', null, null, 1);
                    $this->Cell(55, 2, '  Cheque', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 2, ($this->condicion_pago == 1) ? 'X' : '', 1, 0, 'C', null, null, 1);
                    $this->Cell(22.5, 2, '  100 % ', 0, 0, 'L', null, null, 1);

                    $this->Cell(55, 2, '', 0, 1, 'L', null, null, 1);

                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  Beneficiario: Miele, S.A. de C.V.', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  A nombre de: Miele, S.A. de C.V.', 0, 1, 'L', null, null, 1);

                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  Banco: Banamex', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 2, ($this->condicion_pago == 2) ? 'X' : '', 1, 0, 'C', null, null, 1);
                    $this->Cell(22.5, 2, '  50 % - 50 % ', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '', 0, 1, 'L', null, null, 1);

                    $this->SetFont($this->textfont, '', $this->font_size);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  Sucursal: ' . $this->partner->sucursal, 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 5, ($this->forma_pago == 3) ? 'X' : '', 1, 0, 'C', null, null, 1);
                    $this->Cell(55, 2, '  Trajeta de Crédito / Débito **', 0, 0, 'L', null, null, 1);
                    $this->Cell(65, 2, '', 0, 1, 'L', null, null, 1);

                    $this->SetFont($this->textfont, '', $this->font_size);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  Cuenta: ' . $this->partner->cuenta_bancaria, 0, 0, 'L', null, null, 1);

                    $this->SetFont($this->textfont, '', $this->font_size);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  (Visa / Mastercard / American Express)', 0, 0, 'L', null, null, 1);
                    $this->SetFont($this->textfont, 'B', $this->font_size);
                    $this->Cell(55, 2, '  * Favor de enviar comprobante de depósito a', 0, 1, 'L', null, null, 1);

                    $this->SetFont($this->textfont, '', $this->font_size);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(120, 2, '  Clabe: ' . $this->partner->cuenta_clabe, 0, 0, 'L', null, null, 1);

// 	   			$this->Cell(8,2,'',0,0,'L',null,null,1);
// 	   			$this->Cell(120,2,'  Referencia: '.$this->folio_compra,0,0,'L',null,null,1);

                    $this->SetFont($this->textfont, 'B', $this->font_size);
                    $this->Cell(65, 2, 'info@miele.com.mx', 0, 1, 'L', null, null, 1);

                    $this->SetFont($this->textfont, '', $this->font_size);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(120, 2, '  Referencia: ' . $this->folio_compra, 0, 0, 'L', null, null, 1);
                    $this->SetFont($this->textfont, 'B', $this->font_size);
                    $this->Cell(65, 2, '', 0, 1, 'L', null, null, 1);
                }
                else
                {
                    $this->Cell(8, 5, 'X', 1, 0, 'C', null, null, 1);
                    $this->Cell(55, 2, '  Trajeta de Crédito / Débito **', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 5, '', 0, 0, 'C', null, null, 1);
                    $this->Cell(55, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(8, 2, 'X', 1, 0, 'C', null, null, 1);
                    $this->Cell(22.5, 2, '  100 % ', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '', 0, 1, 'L', null, null, 1);
                    $this->SetFont($this->textfont, '', $this->font_size);
                    $this->Cell(8, 2, '', 0, 0, 'L', null, null, 1);
                    $this->Cell(55, 2, '  (Visa / Mastercard / American Express)', 0, 0, 'L', null, null, 1);
                }
	   			
	   			$this->SetFont($this->textfont,'B',$this->font_size);
	   			$this->Cell(0,2,'',0,1,'L',null,null,1);
	   			$this->Cell(0,3,'',0,0,'C',null,null,1);
	    	}
	     	else//if ($this->r['cotizacion']->cuentas_id!=2)
	     	{
    			$this->Ln(20);
    			$y=160;
    			$this->SetY($y);
    			$this->SetFont($this->textfont,'B',$this->font_size);
    			$this->Cell(0,2,'Forma de pago:',0,1,'L',null,null,1);
    			
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(8,5,($this->forma_pago==1)?'X':'',1,0,'C',null,null,1);
    			$this->Cell(55,2,'  Depósito / Transferencía',0,1,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  Beneficiario: Miele, S.A. de C.V.',0,1,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  Banco: Banamex',0,1,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  Sucursal: '.$this->partner->sucursal,0,1,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(0,2,'  Cuenta: '.$this->partner->cuenta_bancaria,0,1,'L',null,null,1);
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(100,2,'  Clabe: '.$this->partner->cuenta_clabe,0,1,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(100,2,'  Referencia: '.$this->folio_compra,0,0,'L',null,null,1);
	 		}
	 		
	 		$this->SetY(-20);
	 		$this->Cell(0,3,'',0,1,'C',null,null,1);
 	    	$this->SetFont($this->textfont,'B',$this->font_size);
 	    	$this->Cell(0,3,'AVISO DE PRIVACIDAD',0,1,'C',null,null,1);
 	    	$this->SetFont($this->textfont,'',$this->font_size);
 	    	$this->Cell(0,3,'Miele, S.A. de C.V. (Miele) protege y da legalidad a todos los datos proporcionados voluntariamente en nuestra base de datos. En caso de no querer participar en dicha base de datos, favor de notificarlo a info@miele.com.mx Para más información puedes consultar nuestra página web: www.miele.com.mx',0,1,'C',null,null,1);
	 	    
			//Page number
			$this->SetFont($this->textfont,'',$this->font_size);
			if (empty($this->pagegroups)) 
				$this->Cell(0,5,'Página '.$this->PageNo().' de '.$this->getAliasNbPages(),0,0,'R');
			else 
				$this->Cell(0,5,'Página '.$this->getPageNumGroupAlias().' de '.$this->getPageGroupAlias(),0,0,'R');
    	}
    
	    if($this->footer_secundario)
	    {
	    	
	    	//Page number
	    	$this->SetFont($this->textfont,'',$this->font_size);
	    	if (empty($this->pagegroups))
	    		$this->Cell(0,5,'Página '.$this->PageNo().' de '.$this->getAliasNbPages(),0,1,'R');
	    	else
	    		$this->Cell(0,5,'Página '.$this->getPageNumGroupAlias().' de '.$this->getPageGroupAlias(),0,1,'R');
	    }
	}

	public function foto($path,$y,$mw=NULL,$mh=NULL, $type='',$center=TRUE)
	{
		if(!file_exists($path) || (file_exists($path) && filesize($path)<=0))
			$path=FCPATH.'img/layout/imagen_no_disponible.jpg';

		list($width, $height) = getimagesize($path);

		$max_width=23;
		if($mw)
			$max_width=$mw;
		if($width>$height) // HORIZONTAL
		{
			$new_width=$max_width;
			$new_height=round(($height*$max_width)/$width,2);
		}

		$max_height=20;
		if($mh)
			$max_height=$mh;
		if($width<$height || $width==$height) // VERTICAL O CUADRADA
		{
			$new_height=$max_height;
			$new_width=round(($width*$max_height)/$height,2);
		}

		if($new_height>$max_height)
		{
			$new_height=$max_height;
			$new_width=$new_height*$width/$height;
		}

		if($new_width>$max_width)
		{
			$new_width=$max_width;
			$new_height=$new_width*$height/$width;
		}

		$x=0;
		if($center)
		{
			$x=10+((25-$new_width)/2);
			$y=$y+((24.5-$new_height)/2);
		}
		else
			$x=10;

		$this->Image($path, $x, $y, $new_width, $new_height);
	}
}