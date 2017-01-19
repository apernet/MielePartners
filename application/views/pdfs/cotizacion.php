<?php 
	$cliente=$r['cotizacion']['nombre_comprador'];
	if(!empty($r['cotizacion']['paterno_comprador']))
		$cliente.=' '.$r['cotizacion']['paterno_comprador'];
	if(!empty($r['cotizacion']['materno_comprador']))
		$cliente.=' '.$r['cotizacion']['materno_comprador'];

	$pdf->cliente=$cliente;		
	$pdf->partner=$cuenta;
	$pdf->fecha=$r['cotizacion']['fecha'];
	//$pdf->telefono=$r['cotizacion']['telefono_comprador'];
	$pdf->folio=$r['cotizacion']['folio_cuentas'];
	$pdf->entrega_estado=$r['cotizacion']['entrega_estado'];
	$pdf->venta_directa = $venta_directa;
	$pdf->cotizacion=$r['cotizacion'];
	$pdf->vendedor=$r['cotizacion']['vendedor_nombre'].' '.$r['cotizacion']['vendedor_paterno'].' '.$r['cotizacion']['vendedor_materno'];
	$pdf->distribuidor=$this->base->value_get('cuentas',$r['cotizacion']['referido_distribuidor_id'],'nombre');
	
	$pdf->distribuidor_logo= FCPATH.'files/cuentas/'.$pdf->partner->id.'/distribuidor_logo.jpg';
	if($this->config->item('cloudfiles'))
	{
		$orden=$pdf->partner->distribuidor_logo_orden?'_'.$pdf->partner->distribuidor_logo_orden:'';
		$pdf->distribuidor_logo=$this->cloud_files->img_tmp('files/cuentas/'.$pdf->partner->id.'/distribuidor_logo'.$orden.'.jpg');
	}

	$pdf->font_size = 6;
	$pdf->SetMargins(10,28,10);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->SetAutoPageBreak(TRUE, 10);
	$pdf->AliasNbPages();
	$pdf->startPageGroup();
	$pdf->setPageOrientation('L','','');

	$pdf->header=TRUE;
	$pdf->footer=TRUE;
	
	$pdf->AddPage();
	$pdf->SetFillcolor(220,220,220);
	$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
	$pdf->SetLineStyle(array('width' => 0.2,'color'=>array(0,0,0)));
	
	$y=50;
	$y+=$venta_directa?$pdf->celda:0;
	$i=0;
	$anx=1;
	$desc=0;
	$desc_gral=0;
	$imp=0;
	$imp_total=0;
	$fondo=0;
	$color=0;
	if(!empty($productos) || !empty($accesorios_individuales))
	{
		$num_lines=0;
		foreach($productos as $p)
		{
			$num_lines++;
			if(isset($p['accesorios']))
			{
				foreach($p['accesorios'] as $accesorios)
					$num_lines++;	
			}
		}

		foreach($accesorios_individuales as $acc)
			$num_lines++;

		if(isset($regalos) && !empty($regalos))
		{
			foreach($regalos as $reg)
				$num_lines++;
		}

		if(isset($cupones_regalos) && !empty($cupones_regalos))
		{
			foreach($cupones_regalos as $c_reg)
				$num_lines++;
		}

		$pdf->SetY($y);
		$descuento_general =0;
		foreach($productos as $p)
		{
			$productos_id= $p['id'];
			$descuento_general += $p['descuento_cliente'];//$calculo['descuentos_cliente'][$productos_id]['cliente'];

			$fondo = ($color%2==0)?1:0;
			$str_prod=strip_tags(@$p['descripcion']);
			$descripcion_prod=str_replace("\n"," ",$str_prod);
			$file_y=$pdf->getY();

			$producto_path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/productos/{$p['id']}/{$p['img_id']}.jpg"):FCPATH."files/productos/{$p['id']}/{$p['img_id']}.jpg";
			$pdf->foto($producto_path,$file_y);
			$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(20, $pdf->celda*7,@$p['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(20, $pdf->celda*7,@$p['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);

			if($p['unidad_id']==2 && !empty($r['cotizacion']['evento_estado']))
				$p['nombre'] = $p['nombre'] . ' este servicio se realizará en el estado de ' . $r['cotizacion']['evento_estado'];

			$pdf->MultiCell(110, $pdf->celda*7,$p['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(20, $pdf->celda*7,$p['cantidad']?$p['cantidad'].' '.$p['unidad']:'0'.' '.$p['unidad'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(23, $pdf->celda*7,moneda($p['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			
			$imp=$p['cantidad']*$p['precio'];
			//if (isset($calculo['descuentos_cliente'][$productos_id]['cliente']))
			if($p['descuento_cliente'])
				$desc=$p['precio']*$p['descuento_cliente'];//$calculo['descuentos_cliente'][$productos_id]['cliente'];
			else 
				$desc=0;
			
			$pdf->MultiCell(23, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(0, $pdf->celda*7,isset($p['importe_cliente'])?moneda($p['importe_cliente']):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$i++;
				
			if(($num_lines==5 && $i>3) || ($num_lines>5 && $i>4))
			{
				//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
				$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
				$y=50;
				$y+=$venta_directa?$pdf->celda:0;
				$i=0;
				$pdf->AddPage();
				$pdf->SetY($y);
			}
			
			$desc=$desc*$p['cantidad'];
			$desc_gral+=$desc;
			$imp_total+=$imp;

			if(isset($p['accesorios']))
			{
				foreach($p['accesorios'] as $accesorios)
				{
					$acessorios_id = $accesorios['accesorios_id'];
					$color +=1;
					$fondo =  ($color%2==0)?1:0;
					$str_prod=strip_tags(@$accesorios['nombre']);
					$descripcion=str_replace("\n"," ",$str_prod);
					$unidad = ' '.elemento('Unidades',$accesorios['unidad_id']);

					$file_y=$pdf->getY();
					$orden = $accesorios['imagen_orden']?'_'.$accesorios['imagen_orden']:'';
					$accesorio_path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/accesorios/{$acessorios_id}{$orden}.jpg"):FCPATH."files/accesorios/{$acessorios_id}.jpg";
					$pdf->foto($accesorio_path,$file_y);

					$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$pdf->MultiCell(20, $pdf->celda*7,@$accesorios['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$pdf->MultiCell(20, $pdf->celda*7,@$accesorios['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$pdf->MultiCell(110, $pdf->celda*7,$descripcion,'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$pdf->MultiCell(20, $pdf->celda*7, isset($accesorios['cantidad'])?$accesorios['cantidad'].' '.$accesorios['unidad']:'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$pdf->MultiCell(23, $pdf->celda*7,moneda($accesorios['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					
					$imp=$accesorios['cantidad']*$accesorios['precio'];
					if(isset($p['accesorios'][$acessorios_id]['descuento_cliente']))
						$desc=$accesorios['precio']*$accesorios['cantidad']*$p['accesorios'][$acessorios_id]['descuento_cliente'];//$calculo['descuentos_cliente'][$productos_id]['cliente'];
					else $desc=0;
					
					$pdf->MultiCell(23, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					//$pdf->MultiCell(0, $pdf->celda*7,isset($calculo['importe_cliente_acc'][$acessorios_id])?moneda($calculo['importe_cliente_acc'][$acessorios_id]):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$pdf->MultiCell(0, $pdf->celda*7,isset($accesorios['importe_cliente'])?moneda($accesorios['importe_cliente']):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
					$desc=$desc*$accesorios['cantidad'];
					$desc_gral+=$desc;
					$imp_total+=$imp;
					
					$i++;
					if(($num_lines==5 && $i>3) || ($num_lines>5 && $i>4))
					{
						//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
						$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
						$y=50;
						$y+=$venta_directa?$pdf->celda:0;
						$i=0;
						$pdf->AddPage();
						$pdf->SetY($y);
					}
				}
			}
			$color++;
		}

        if(isset($accesorios_individuales) && !empty($accesorios_individuales))
        {
            foreach($accesorios_individuales as $acc)
            {
				$file_y=$pdf->getY();
				$orden = $acc->imagen_orden?'_'.$acc->imagen_orden:'';
				$accesorio_individual_path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/accesorios/{$acc->accesorios_id}{$orden}.jpg"):FCPATH."files/accesorios/{$acc->accesorios_id}.jpg";

				$pdf->foto($accesorio_individual_path,$file_y);
                $fondo =  ($color%2==0)?1:0;
                $pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
                $pdf->MultiCell(20, $pdf->celda*7,@$acc->item,'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
                $pdf->MultiCell(20, $pdf->celda*7,@$acc->modelo,'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
                $pdf->MultiCell(110, $pdf->celda*7,@$acc->nombre,'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
                $pdf->MultiCell(20, $pdf->celda*7, isset($acc->cantidad)?$acc->cantidad.' '.$acc->unidad:'0'.' '.$acc->unidad,'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
                $pdf->MultiCell(23, $pdf->celda*7,moneda($acc->precio),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$desc=0;
				if(isset($acc->descuento_cliente) && @!$r['cotizacion']['promocion_msi'] && (!$acc->consumible || ($acc->consumible && @$r['cotizacion']['cupones_id'] && @$r['cotizacion']['opcion_cupon_id']==1)))
					$desc=($acc->precio*$acc->cantidad*$acc->descuento_cliente)/100;//$calculo['descuentos_cliente'][$productos_id]['cliente'];
                $pdf->MultiCell(23, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
                $pdf->MultiCell(0, $pdf->celda*7,isset($acc->importe_cliente)?moneda($acc->importe_cliente):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);

                $i++;

				if(($num_lines==5 && $i>3) || ($num_lines>5 && $i>4))
                {
                    //Agrega una nueva hoja al ingresar 20 productos y/o accesorios
                    $pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
                    $y=50;
                    $y+=$venta_directa?$pdf->celda:0;
                    $i=0;
                    $pdf->AddPage();
                    $pdf->SetY($y);
                }

                $color +=1;
            }
        }

		if(isset($regalos) && !empty($regalos))
		{
			foreach($regalos as $reg)
			{
				$file_y=$pdf->getY();
				$path_regalo=$reg['descuento_cliente']?FCPATH.'img/promociones/promocion.png':FCPATH.'img/promociones/regalo.png';
				$pdf->foto($reg['path'],$file_y);
				$pdf->foto($path_regalo,$file_y,18,12,'PNG',FALSE);//exit;
				$fondo =  ($color%2==0)?1:0;
				$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7,@$reg['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7,@$reg['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(110, $pdf->celda*7,@$reg['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7, isset($reg['cantidad'])?$reg['cantidad']:'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(23, $pdf->celda*7,moneda($reg['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(23, $pdf->celda*7,moneda($reg['descuento_cliente']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(0, $pdf->celda*7,isset($reg['importe_cliente'])?moneda($reg['importe_cliente']):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);

				$i++;

				if(($num_lines==5 && $i>3) || ($num_lines>5 && $i>4))
				{
					//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
					$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
					$y=50;
					$y+=$venta_directa?$pdf->celda:0;
					$i=0;
					$pdf->AddPage();
					$pdf->SetY($y);
				}

				$color +=1;
			}
		}

		if(isset($cupones_regalos) && !empty($cupones_regalos))
		{
			foreach($cupones_regalos as $c_reg)
			{
				$file_y=$pdf->getY();
				$path_regalo=FCPATH.'img/promociones/regalo.png';
				$producto_path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/productos/{$c_reg['id']}/{$c_reg['img_id']}.jpg"):FCPATH."files/productos/{$c_reg['id']}/{$c_reg['img_id']}.jpg";
				$pdf->foto($producto_path,$file_y);
				$pdf->foto($path_regalo,$file_y,18,12,'PNG',FALSE);//exit;
				$fondo =  ($color%2==0)?1:0;
				$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7,@$c_reg['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7,@$c_reg['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(110, $pdf->celda*7,@$c_reg['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7, isset($c_reg['cantidad'])?$c_reg['cantidad'].' '.$c_reg['unidad']:'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(23, $pdf->celda*7,moneda($c_reg['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$desc=0;
				$pdf->MultiCell(23, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(0, $pdf->celda*7,isset($c_reg['importe_cliente'])?moneda($c_reg['importe_cliente']):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);

				$i++;

				if(($num_lines==5 && $i>3) || ($num_lines>5 && $i>4))
				{
					//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
					$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
					$y=50;
					$y+=$venta_directa?$pdf->celda:0;
					$i=0;
					$pdf->AddPage();
					$pdf->SetY($y);
				}

				$color +=1;
			}
		}

		$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
		$pdf->Ln(5);

		$html_alianza="";
		if(@$alianzas)
		{
			foreach($alianzas as $a)
			{

				$alianza = elemento('promociones_alianzas',$a['alianzas_id']);
				if($r['cotizacion']['recibo_pago_cdn'])
					$html_alianza.="Regalo adquirido por ALIANZA <b>{$alianza}</b>, Para reclamar su regalo, deberá presentar el siguiente <b>CÓDIGO: {$a['codigo']}</b> directamente con el proveedor.<br>";
				else
					$html_alianza.="Regalo adquirido por ALIANZA <b>{$alianza}</b>. Para adquirir su códgio de reclamación debe realizar el pago de su compra.";
			}
		}

		if($i<=4 || $num_lines==4)
		{
			$html='<p align="justified" style="line-height: 6px">*Los precios de la cotizaci&oacute;n pudieran variar durante el proceso de pre-compra, se considerarán los precios finales en el momento de la autorización de la orden de compra.<br>
						*Los precios est&aacute;n sujetos a cambios o modificaciones de acuerdo a la pol&iacute;tica comercial vigente.<br>';
			$borde=1;
			if($num_clases && $r['cotizacion']['observaciones'])
			{
				$html.='<b>AVISOS:</b><br>En agradecimiento a su interés, Miele lo invita a vivir alguna de las Experiencias Gastronómicas o de Enología. Para hacer válida ésta cortesía,
						favor de comunicarse al teléfono: 01800-MIELE00 (01800-6435300) indicando su número de cotización.<br>';
					
				$html.='<b>OBSERVACIONES:</b><br>'.$r['cotizacion']['observaciones'];
				$html.=$html_alianza;
				$html.='</p>';
			}
			elseif($num_clases && !$r['cotizacion']['observaciones'])
			{
				$html.='<b>AVISOS:</b><br>En agradecimiento a su interés, Miele lo invita a vivir alguna de las Experiencias Gastronómicas o de Enología. Para hacer válida ésta cortesía,
						favor de comunicarse al teléfono: 01800-MIELE00 (01800-6435300) indicando su número de cotización.';
				$html.=$html_alianza;
				$html.='</p>';
			}
			elseif(!$num_clases && $r['cotizacion']['observaciones'])
			{
				$html.='<b>OBSERVACIONES:</b><br>'.$r['cotizacion']['observaciones'];
				$html.=$html_alianza;
				$html.='</p>';
			}
			else
			{
				$html.=$html_alianza;
				$html.='</p>';
			}

			$pdf->MultiCell(195, $pdf->celda*2,$html?$html:'', $borde,'L',0,0,null,null,1,1,true,1,$pdf->celda*5,'M',true);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->Cell(45, $pdf->celda, 'IMPORTE TOTAL', 0, 0,'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
			$pdf->Cell(5, $pdf->celda,'$', 0, 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda,num($r['cotizacion']['importe_total']), 0, 1, 'R', 0);
			
			$y=$pdf->getY();
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->setY($y);
			$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(35, $pdf->celda, 'DESCUENTO', '', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
			$pdf->Cell(5, $pdf->celda,'- $', 0, 0, 'R', 0);
			$descuento = $r['cotizacion']['descuento_cliente']>@$r['cotizacion']['descuento_cliente_cupon']?$r['cotizacion']['descuento_cliente']-@$r['cotizacion']['descuento_cliente_cupon']-@$r['cotizacion']['promocion_opcional_descuento']:0;
			$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);

			if(@$r['cotizacion']['promocion_porcentaje'])
			{
				$y=$pdf->getY();
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
				$pdf->setY($y);
				$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
				$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->Cell(35, $pdf->celda, "DESCUENTO PROMOCIÓN ({$r['cotizacion']['promocion_porcentaje']}%) ", '', 0, 'R', 0);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'- $', 0, 0, 'R', 0);
				$descuento = $r['cotizacion']['promocion_porcentaje_monto'];
				$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);
			}

			if(@$r['cotizacion']['promocion_fija'])
			{
				$y=$pdf->getY();
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
				$pdf->setY($y);
				$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
				$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->Cell(35, $pdf->celda, "DESCUENTO FIJO PROMOCIÓN", '', 0, 'R', 0);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'- $', 0, 0, 'R', 0);
				$descuento = $r['cotizacion']['promocion_fija'];
				$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);
			}

			if(@$r['cotizacion']['promocion_opcional_descuento'])
			{
				$y=$pdf->getY();
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
				$pdf->setY($y);
				$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
				$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->Cell(35, $pdf->celda, "DESCUENTO POR PROMOCIÓN", '', 0, 'R', 0, NULL, 1);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'- $', 0, 0, 'R', 0);
				$descuento = $r['cotizacion']['promocion_opcional_descuento'];
				$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);
			}

			if(@$r['cotizacion']['descuento_cliente_cupon'])
			{
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
				$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
				$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->Cell(35, $pdf->celda, 'DESCUENTO POR CUPÓN', '', 0, 'R', 0);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'- $', 0, 0, 'R', 0);
				$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['descuento_cliente_cupon']), '', 1,'R', 0);
			}

			if (!empty($r['cotizacion']['envio']))
			{
				$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
				$pdf->Cell(35, $pdf->celda, 'CARGO DE RECUPERACIÓN', '', 0, 'R', 0);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'$', 0, 0, 'R', 0);
				$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['envio']), '', 1,'R', 0);

				$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size-1);
				$pdf->Cell(35, $pdf->celda, '(INCLUYE ENVÍO E INSTALACIÓN)', '', 0, 'R', 0);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'', 0, 0, 'R', 0);
				$pdf->Cell(0, $pdf->celda, '', '', 1,'R', 0);
			}
			
			$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->Cell(35, $pdf->celda, 'SUBTOTAL','T', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
			$pdf->Cell(5, $pdf->celda,'$', 'T', 0, 'R', 0);
			//$pdf->Cell(0, $pdf->celda, num($calculo['subtotal_cliente']), 'T', 1,'R', 0);
			$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['subtotal_cliente']), 'T', 1,'R', 0);
			
			$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->Cell(35, $pdf->celda, 'IVA 16 %', 'B', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
			$pdf->Cell(5, $pdf->celda,'$', 0, 0, 'R', 0);
			//$pdf->Cell(0, $pdf->celda, num($calculo['iva_cliente']), 'B', 1,'R', 0);
			$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['iva_cliente']), 'B', 1,'R', 0);

			$borde =0;
			if(@$r['cotizacion']['descuento_cupon'] && $r['cotizacion']['opcion_cupon_id']==2)
				$borde ='B';
			$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->Cell(35, $pdf->celda,'TOTAL*',$borde, 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
			$pdf->Cell(5, $pdf->celda,'$', 'T', 0, 'R', 0);
			//$pdf->Cell(0, $pdf->celda, num($calculo['total_cliente']), 0, 1,'R', 0);
			$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['total_cliente']), 0, 1,'R', 0);

			if(@$r['cotizacion']['promocion_msi'])
			{
				$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size-0.5);
				$pdf->Cell(45, $pdf->celda,"Su pago a {$r['cotizacion']['promocion_msi']} meses sin intereses por promoción",'', 0, 'R', 0, NULL, 1);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'$', 'T', 0, 'R', 0);
				$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['promocion_msi_cliente']), 'T', 1, 'R', 0);
			}

			if(@$r['cotizacion']['descuento_cupon'] && $r['cotizacion']['opcion_cupon_id']==2)
			{
				$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
				$pdf->SetFont($pdf->textfont,'B',$pdf->font_size-0.5);
				$pdf->Cell(45, $pdf->celda,'Su pago a 12 meses sin intereses por Cupón','', 0, 'R', 0, NULL, 1);
				$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
				$pdf->Cell(5, $pdf->celda,'$', 'T', 0, 'R', 0);
				$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['mensualidad_cliente_cupon']), 'T', 1, 'R', 0);
			}
		}
	}