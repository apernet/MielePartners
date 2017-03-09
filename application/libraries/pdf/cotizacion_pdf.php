<?php
require_once(APPPATH.'/libraries/tcpdf/config/lang/spa.php');
require_once(APPPATH.'/libraries/tcpdf/tcpdf.php');
class COTIZACION_PDF extends TCPDF
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
	var $fecha = TRUE;
	var $telefono = TRUE;
	var $partner = TRUE;
	var $cliente = TRUE;
	var $r = TRUE;
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
    		$img_file= FCPATH.'img/layout/MieleImmerBesser.jpg';
    		if($img_file && file_exists($img_file))
			{
				$this->setJPEGQuality(100);
				$this->Image($img_file, 225, 7, 45, 13,null, null,null, FALSE, null,null,FALSE,FALSE);				
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

			$x=$existe?50:10;
			$center=$existe?125:170;
			
    		$this->SetXY($x, 8);
        	$this->SetAutoPageBreak(TRUE, 0);
        	$this->SetFont($this->textfont,'B',$this->font_size+1);
        	$this->Cell(50,3,$this->partner->razon_social,0,1,'L',null,null,1);
        	$this->Ln(1);
        
        	$this->SetX($x);
        	$this->SetFont($this->textfont,'',$this->font_size);
        	$this->Cell(50,3,$this->partner->rfc,0,0,'L',null,null,1);
        	$this->SetFont($this->textfont,'B',$this->font_size+2);
        	$this->Cell($center,0,'C O T I Z A C I Ó N',0,1,'C',null,null,1);
        	
        	if(!$this->venta_directa)
        	{
        		$this->SetX($x);
        		$this->SetFont($this->textfont,'B',$this->font_size);
	        	$this->Cell(15,3,'Sucursal :',0,0,'L',null,null,1);
	        	$this->SetFont($this->textfont,'',$this->font_size);
	        	$this->Cell(30,3,$this->partner->sucursal_fisica,0,0,'L',null,null,1);
    		}
    		else
    			$this->Cell(40,3,'',0,0,'L',null,null,1);
    		$this->SetX($existe?100:100-40);
        	$this->SetFont($this->textfont,'B',$this->font_size+2);
        	$this->Cell($center,0,$this->folio,0,1,'C',null,null,1);
        	
        	$this->SetX($x);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$direccion =$this->partner->sucursal_calle.' NO. '.$this->partner->sucursal_numero_exterior;
        	$direccion.=$this->partner->sucursal_numero_interior?' INT. '.$this->partner->sucursal_numero_interior:'';
        	$direccion.=' '.$this->partner->sucursal_asentamiento;
        	$this->Cell(50,3,$direccion,0,0,'L',null,null,1);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$this->Cell($center,0,'Si tiene alguna duda con respecto a esta información, se puede comunicar al teléfono 01-800-MIELE-00(01-800-64353-00)',0,1,'C',null,null,1);
        	
        	$this->SetX($x);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$this->Cell(45,3,$this->partner->municipio.' '.$this->partner->sucursal_estado.' '.$this->partner->sucursal_codigo_postal,0,0,'L',null,null,1);
        	$this->SetFont($this->textfont,'B',$this->font_size);
        	$this->Cell($center,0,'o envié un correo a info@miele.com.mx',0,1,'C',null,null,1);
       
        	$this->Ln(2);
        	$this->Cell(25,3.5,'Nombre del Cliente:   ', 'LT',0,'L',null,null,1);
			$this->Cell(170,3.5,$this->cliente,'T',0,'L',null,null,1);
			       
			$this->Cell(30,3.5,'Fecha:','T',0,'L',null,null,1);
			$this->Cell(0,3.5,get_fecha($this->fecha), 'TR',1,'L',null,null,1);
			
			$this->Cell(25,3.5,'Teléfono de contacto: ', 'L',0,'L',null,null,1);
			$this->Cell(170,3.5,$this->cotizacion['telefono_comprador'], '',0,'L',null,null,1);
			
			$this->Cell(30,3.5,'Estado de Entrega: ', '',0,'L',null,null,1);
			$this->Cell(0,3.5,$this->cotizacion['entrega_estado'], 'R',1,'L',null,null,1);
			
			$borde=$this->venta_directa?'':'';
			$referido_vendedor_nombre=@$this->cotizacion['referido_vendedor_nombre'].' '.@$this->cotizacion['referido_vendedor_paterno'].' '.@$this->cotizacion['referido_vendedor_materno'];
			$vendedor_nombre=@$this->cotizacion['vendedor_nombre'].' '.@$this->cotizacion['vendedor_paterno'].' '.@$this->cotizacion['vendedor_materno'];
			$this->Cell(25,3.5,'Nombre del Vendedor: ', 'L'.$borde,0,'L',null,null,1);
			$this->Cell(170,3.5,$vendedor_nombre,$borde,0,'L',null,null,1);
			$this->Cell(30,3.5,'Estado de Instalación: ', $borde,0,'L',null,null,1);
			$this->Cell(0,3.5,$this->cotizacion['instalacion_estado'], 'R'.$borde,1,'L',0,null,1);
			
			if($this->venta_directa)
			{
				$this->Cell(25,3.5,'Distribuidor Referenciador: ', 'L',0,'L',null,null,1);
				$this->Cell(170,3.5,@$this->distribuidor,'',0,'L',null,null,1);
				$this->Cell(30,3.5,'Vendedor Referenciador: ', '',0,'L',null,null,1);
				$this->Cell(0,3.5,$referido_vendedor_nombre, 'R',1,'L',0,null,1);
			}
			
			$this->Cell(195,3.5,'','LB',0,'L',null,null,1);
			$this->Cell(30,3.5,'E-mail del Cliente: ', 'B',0,'L',null,null,1);
			$this->Cell(0,3.5,$this->cotizacion['email_comprador'], 'BR',1,'L',0,null,1);
			
			$this->Ln(2);
			$this->SetFont($this->textfont,'B',$this->font_size+1.5);
			$this->SetLineStyle(array('width' => 0.4,'color'=>array(0,0,0)));
			$this->SetFillColor(255,255,255);
			$this->SetTextColor(0,0,0);
			$this->MultiCell(25, $this->celda*2,'IMAGEN',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(20, $this->celda*2,'NÚMERO MATERIAL',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(20, $this->celda*2,'MODELO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(110, $this->celda*2,'DESCRIPCIÓN',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(20, $this->celda*2,'CANTIDAD',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(23, $this->celda*2,'PRECIO UNITARIO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(23, $this->celda*2,'DESCUENTO UNITARIO',1,'C',1,0,null,null,1,1,null,1,$this->celda*2,'M',true);
			$this->MultiCell(0, $this->celda*2,'IMPORTE NETO',1,'C',1,1,null,null,1,1,null,1,$this->celda*2,'M',true);
    	}        
    }
    
    function Footer()
    {
    	if($this->footer)
    	{
	    	if (!$this->venta_directa)
	    	{   	
	    	
	    		$this->Ln(20);
	    		$y=185;
	    		$this->SetY($y);
	    		$this->SetFont($this->textfont,'B',$this->font_size);
	    		$this->Cell(0,3,'Este documento no genera ningun tipo de obligación o compromiso por parte de Miele, S.A. de C.V.',0,1,'C',null,null,1);
	    		$this->SetFont($this->textfont,'',$this->font_size);
	    		$this->Cell(170,3,'Esta cotización es informativa. Para realizar una compra, su Distribuidor Autorizado Miele deberá entregarle una ',0,0,'R',null,null,1);
	    		$this->SetFont($this->textfont,'B',$this->font_size);
	    		$this->Cell(0,3,'"ORDEN DE COMPRA"',0,1,'L',null,null,1);
	    		$this->SetFont($this->textfont,'',$this->font_size);
	    		$this->Cell(0,3,'*Todos los precios que aquí se muestran están sujetos a cambios sin previo aviso.',0,1,'C',null,null,1);
	    		
	    		$this->Cell(0,3,'',0,1,'C',null,null,1);
	    		$this->SetFont($this->textfont,'B',$this->font_size);
	    		$this->Cell(0,3,'AVISO DE PRIVACIDAD',0,1,'C',null,null,1);
	    		$this->SetFont($this->textfont,'',$this->font_size);
	    		$this->Cell(0,3,'Miele, S.A. de C.V. (Miele) protege y da legalidad a todos los datos proporcionados voluntariamente en nuestra base de datos. En caso de no querer participar en dicha base de datos, favor de notificarlo a  info@miele.com.mx   Para más información puedes consultar nuestra página web: www.miele.com.mx',0,1,'C',null,null,1);
	    		
	    	}
	    	else//if ($this->r['cotizacion']->cuentas_id==2)
    		{
    			$this->Ln(10);
    			$y=177;
    			$this->SetY($y);
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(170,3,'Esta cotización es informativa. Para realizar una compra, su Distribuidor Autorizado Miele deberá entregarle una ',0,0,'R',null,null,1);
    			$this->SetFont($this->textfont,'B',$this->font_size);
    			$this->Cell(0,3,'"ORDEN DE COMPRA"',0,1,'L',null,null,1);
    			$this->SetFont($this->textfont,'B',$this->font_size);
    			$this->Cell(0,3,'Este documento no genera ningun tipo de obligación o compromiso por parte de Miele, S.A. de C.V.',0,1,'C',null,null,1);
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(0,3,'*Todos los precios que aquí se muestran están sujetos a cambios sin previo aviso.',0,1,'C',null,null,1);

				$this->Ln(1);//$this->Cell(0,3,'',0,1,'C',null,null,1);
    			$this->SetFont($this->textfont,'B',$this->font_size);
    			$this->Cell(0,3,'AVISO DE PRIVACIDAD',0,1,'C',null,null,1);
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(0,3,'Miele, S.A. de C.V. (Miele) protege y da legalidad a todos los datos proporcionados voluntariamente en nuestra base de datos. En caso de no querer participar en dicha base de datos, favor de notificarlo a  info@miele.com.mx   Para más información puedes consultar nuestra página web: www.miele.com.mx',0,1,'C',null,null,1);
    			 
    			$this->Ln(1);
    			$this->SetFont($this->textfont,'B',$this->font_size);
    			$this->Cell(210,2,'Forma de pago:',0,0,'L',null,null,1);
    			$this->Cell(0,2,'Condiciones de pago:',0,1,'L',null,null,1);
    			$this->SetFont($this->textfont,'',$this->font_size);
    			
    			$this->Cell(8,5,($this->forma_pago==1)?'X':'',1,0,'C',null,null,1);
    			$this->Cell(55,2,'  Depósito / Transferencía',0,0,'L',null,null,1);
    			$this->Cell(8,5,($this->forma_pago==2)?'X':'',1,0,'C',null,null,1);
    			$this->Cell(55,2,'  Cheque',0,0,'L',null,null,1);
    			$this->Cell(8,5,($this->forma_pago==3)?'X':'',1,0,'C',null,null,1);
    			$this->Cell(55,2,'  Trajeta de Crédito / Débito **',0,0,'L',null,null,1);
    			$this->Cell(55,2,'',0,1,'L',null,null,1);
    			
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  Beneficiario: Miele, S.A. de C.V.',0,0,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  A nombre de: Miele, S.A. de C.V.',0,0,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  (Visa / Mastercard / American Express)',0,0,'L',null,null,1);
    			$this->Cell(10,2,($this->condicion_pago==1)?'X':'',1,0,'C',null,null,1);
    			$this->Cell(22.5,2,'  100 % ',0,0,'L',null,null,1);
    			$this->Cell(10,2,($this->condicion_pago==2)?'X':'',1,0,'C',null,null,1);
    			$this->Cell(22.5,2,'  50 % - 50 % ',0,1,'L',null,null,1);
    			
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  Banco: Banamex',0,0,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'',0,0,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'',0,0,'L',null,null,1);
    			$this->SetFont($this->textfont,'B',$this->font_size);
//    			$this->Cell(0,2,'  * Favor de enviar comprobante de pago al correo electrónico',0,1,'L',null,null,1);
    			
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'  Sucursal: '.$this->partner->sucursal,0,0,'L',null,null,1);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(55,2,'',0,0,'L',null,null,1);
    			$this->Cell(65,2,'',0,0,'L',null,null,1);
    			$this->SetFont($this->textfont,'B',$this->font_size);
//    			$this->Cell(65,2,'clientes@miele.com.mx comprobante de pago para confirmar y entrega.',0,1,'L',null,null,1);
    			
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(0,2,'  Cuenta: '.$this->partner->cuenta_bancaria,0,1,'L',null,null,1);
    			
    			$this->SetFont($this->textfont,'',$this->font_size);
    			$this->Cell(8,2,'',0,0,'L',null,null,1);
    			$this->Cell(100,2,'  Clabe: '.$this->partner->cuenta_clabe,0,0,'L',null,null,1);
    			
    			/*$this->SetFont($this->textfont,'B',$this->font_size);
    			$this->Cell(0,3,'**El pago con tarjeta genera un cargo adicional del 2%.',0,0,'L',null,null,1);
    			$this->SetY(-7);*/
    		}
    	}
	    
    	//Page number
    	$this->SetFont($this->textfont,'',$this->font_size);
    	if (empty($this->pagegroups))
    		$this->Cell(0,5,'Página '.$this->PageNo().' de '.$this->getAliasNbPages(),0,0,'R');
    	else
    		$this->Cell(0,5,'Página '.$this->getPageNumGroupAlias().' de '.$this->getPageGroupAlias(),0,0,'R');
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

		$this->Image($path, $x, $y, $new_width, $new_height,$type);
	}
}