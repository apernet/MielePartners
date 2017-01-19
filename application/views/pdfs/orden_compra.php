<?php
	$pdf->header=TRUE;
	$pdf->footer=TRUE;
	$pdf->partner=$cuenta;
	$pdf->clave=$cuenta->clave;
	$pdf->cotizacion=$r['cotizacion'];
	$pdf->vendedor=$r['cotizacion']['vendedor_nombre'].' '.$r['cotizacion']['vendedor_paterno'].' '.$r['cotizacion']['vendedor_materno'];
	$pdf->folio_compra=$r['cotizacion']['folio_compra'];
	$pdf->fecha_entrega= $r['cotizacion']['fecha_entrega'];
	$pdf->forma_pago=$r['cotizacion']['forma_pago_id'];
	$pdf->condicion_pago=$r['cotizacion']['condiciones_pago_id'];
	$pdf->venta_directa=$venta_directa;
	$pdf->distribuidor=$this->base->value_get('cuentas',$r['cotizacion']['referido_distribuidor_id'],'nombre');

	$pdf->distribuidor_logo= FCPATH.'files/cuentas/'.$pdf->partner->id.'/distribuidor_logo.jpg';
	if($this->config->item('cloudfiles'))
	{
		$orden=$pdf->partner->distribuidor_logo_orden?'_'.$pdf->partner->distribuidor_logo_orden:'';
		$pdf->distribuidor_logo=$this->cloud_files->img_tmp('files/cuentas/'.$pdf->partner->id.'/distribuidor_logo'.$orden.'.jpg');
	}
	
	$pdf->font_size = 6;
	$pdf->SetMargins(10,25,10);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	$pdf->SetAutoPageBreak(TRUE, 10);
	$pdf->AliasNbPages();
	$pdf->startPageGroup();
	$pdf->setPageOrientation('L','','');
	
	$pdf->AddPage();
	$pdf->SetFillcolor(220,220,220);
	$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
	$y=62;
	$i=0;//Delimita el número de productos y accesorios por hoja. 
	$anx=1;
	
	$fondo=0;
	$color=0;
	
	$pdf->SetY($y);
	foreach($productos as $p)
	{
		$productos_id= $p['id'];

		$fondo=($color%2==0)?1:0;
		$str_prod=strip_tags(@$p['descripcion']);
		$descripcion_prod=str_replace("\n"," ",$str_prod);
		$file_y=$pdf->getY();
		$producto_path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/productos/{$p['id']}/{$p['img_id']}.jpg"):FCPATH."files/productos/{$p['id']}/{$p['img_id']}.jpg";

		$pdf->foto($producto_path,$file_y);
		$pdf->SetLineStyle(array('width' => 0.2,'color'=>array(0,0,0)));
		$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
		$pdf->MultiCell(25, $pdf->celda*7,$accs[$p['categorias_id']],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
		$pdf->MultiCell(20, $pdf->celda*7,@$p['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
		$pdf->MultiCell(15, $pdf->celda*7,@$p['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);

		if($p['unidad_id']==2 && !empty($r['cotizacion']['evento_estado']))
			$p['nombre'] = $p['nombre'] . ' este servicio se realizará en el estado de ' . $r['cotizacion']['evento_estado'];

		$pdf->MultiCell(100, $pdf->celda*7,@$p['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
		$pdf->MultiCell(17, $pdf->celda*7,@$p['cantidad'].' '.$p['unidad'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
		$pdf->MultiCell(17, $pdf->celda*7,moneda($p['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
		$desc = 0;
		if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
			$desc = $p['precio']*$p['descuento_cliente'];
		else
			$desc = $p['precio']*$p['descuento_distribuidor'];
		$pdf->MultiCell(17, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);

		$importe = 0;
		if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
			$importe = $p['importe_cliente'];
		else
			$importe = $p['importe_distribuidor'];

		$pdf->MultiCell(0, $pdf->celda*7,moneda($importe),'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);

		$i++;

		if($i>4)
		{
			//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
			$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
			$y=62;
			$i=0;
			$pdf->footer=FALSE;
			$pdf->AddPage();
			$pdf->SetY($y);
		}
		if(isset($p['accesorios']))
		{
			foreach($p['accesorios'] as $accesorios)
			{
				$color +=1;
				$fondo =  ($color%2==0)?1:0;
				$acessorios_id = $accesorios['accesorios_id'];
				$str_acc=strip_tags(@$accesorios['descripcion']);
				$descripcion_acc=str_replace("\n"," ",$str_acc);
				$file_y=$pdf->getY();
				$orden = $accesorios['imagen_orden']?'_'.$accesorios['imagen_orden']:'';
				$accesorio_path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/accesorios/{$acessorios_id}{$orden}.jpg"):FCPATH."files/accesorios/{$acessorios_id}.jpg";

				$pdf->foto($accesorio_path,$file_y);
				$pdf->SetLineStyle(array('width' => 0.2,'color'=>array(0,0,0)));
				$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(25, $pdf->celda*7,@$accs[$p['categorias_id']],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(20, $pdf->celda*7,@$accesorios['item'],'LR','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(15, $pdf->celda*7,@$accesorios['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(100, $pdf->celda*7,$accesorios['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(17, $pdf->celda*7, @$accesorios['cantidad'].' '.$accesorios['unidad'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$pdf->MultiCell(17, $pdf->celda*7,moneda(@$accesorios['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);

				$desc = 0;
				if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
					$desc = $accesorios['precio']*$accesorios['descuento_cliente'];
				elseif(!$accesorios['consumible'])
					$desc = $accesorios['precio']*$accesorios['descuento_distribuidor'];

				$pdf->MultiCell(17, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);

				$importe = 0;
				if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
					$importe = @$accesorios['importe_cliente'];
				else
					$importe = @$accesorios['importe_distribuidor'];

				$pdf->MultiCell(0, $pdf->celda*7,moneda($importe),'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
				$i++;

				if($i>4)
				{
					//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
					$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
					$y=62;
					$i=0;
					$pdf->footer=FALSE;
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

			$pdf->SetLineStyle(array('width' => 0.2,'color'=>array(0,0,0)));
			$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);//@$accs[$p['categorias_id']]
			$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);//@$accs[$p['categorias_id']]
			$pdf->MultiCell(20, $pdf->celda*7,@$acc->item,'LR','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);//@$acc->item
			$pdf->MultiCell(15, $pdf->celda*7,@$acc->modelo,'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(100, $pdf->celda*7,$acc->nombre,'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(17, $pdf->celda*7, @$acc->cantidad.' '.$acc->unidad,'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(17, $pdf->celda*7,moneda(@$acc->precio),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$desc=0;
			if(isset($acc->descuento_cliente) && $venta_directa && @!$r['cotizacion']['promocion_msi'] && (!$acc->consumible || ($acc->consumible && @$r['cotizacion']['cupones_id'] && @$r['cotizacion']['opcion_cupon_id']==1)) )
				$desc=($acc->precio*$acc->cantidad*$acc->descuento_cliente)/100;
			elseif(isset($acc->descuento_cliente) && !$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1)) && @!$r['cotizacion']['promocion_msi'] && (!$acc->consumible || ($acc->consumible && @$r['cotizacion']['cupones_id'] && @$r['cotizacion']['opcion_cupon_id']==1)) )
				$desc=($acc->precio*$acc->cantidad*$acc->descuento_cliente)/100;
			elseif(isset($acc->descuento_distribuidor) && !$venta_directa && in_array(@$r['cotizacion']['status_id'],array(2,3,4,5)) && !$acc->consumible && @!$r['cotizacion']['promocion_msi'])
				$desc=($acc->precio*$acc->cantidad*$acc->descuento_distribuidor)/100;
			//debug($desc);exit;
			$pdf->MultiCell(17, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);

			$importe = 0;
			if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
				$importe = @$acc->importe_cliente;
			else
				$importe = @$acc->importe_distribuidor;

			$pdf->MultiCell(0, $pdf->celda*7,moneda($importe),'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$i++;

			if($i>4)
			{
				//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
				$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
				$y=62;
				$i=0;
				$pdf->footer=FALSE;
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
			$path_regalo=$reg['descuento_cliente']?FCPATH.'img/promociones/promocion.png':FCPATH.'img/promociones/regalo.png';//debug($path_regalo);debug(file_exists($path_regalo));
			$pdf->foto($reg['path'],$file_y);
			$pdf->foto($path_regalo,$file_y,18,12,'PNG',FALSE);//exit;
			$fondo =  ($color%2==0)?1:0;
			$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(20, $pdf->celda*7,@$reg['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(15, $pdf->celda*7,@$reg['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(100, $pdf->celda*7,@$reg['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(17, $pdf->celda*7, isset($reg['cantidad'])?$reg['cantidad']:'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(17, $pdf->celda*7,moneda($reg['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$desc=0;
			$pdf->MultiCell(17, $pdf->celda*7,moneda($reg['descuento_cliente']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(0, $pdf->celda*7,isset($reg['importe_cliente'])?moneda($reg['importe_cliente']):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$i++;

			if($i>4)
			{
				//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
				$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
				$y=62;
				$i=0;
				$pdf->footer=FALSE;
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
			$pdf->MultiCell(25, $pdf->celda*7,'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(20, $pdf->celda*7,@$c_reg['item'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(15, $pdf->celda*7,@$c_reg['modelo'],'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(100, $pdf->celda*7,@$c_reg['nombre'],'L','L',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(17, $pdf->celda*7, isset($c_reg['cantidad'])?$c_reg['cantidad'].' '.$c_reg['unidad']:'','L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(17, $pdf->celda*7,moneda($c_reg['precio']),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$desc=0;
			$pdf->MultiCell(17, $pdf->celda*7,moneda($desc),'L','C',$fondo,0,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$pdf->MultiCell(0, $pdf->celda*7,isset($c_reg['importe_cliente'])?moneda($c_reg['importe_cliente']):0,'LR','C',$fondo,1,null,null,1,1,null,1,$pdf->celda*7,'M',true);
			$i++;

			if($i>4)
			{
				//Agrega una nueva hoja al ingresar 20 productos y/o accesorios
				$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
				$y=62;
				$i=0;
				$pdf->footer=FALSE;
				$pdf->AddPage();
				$pdf->SetY($y);
			}

			$color +=1;
		}
	}

	$pdf->Cell(0, $pdf->celda, '','T', 0,'L', 0);
	$pdf->SetFont($pdf->textfont,'B',$pdf->font_size);
	if($i<=4)
	{
		$pdf->footer=TRUE;
		$pdf->SetY(157);
		$pdf->Ln(5);
		$pdf->Cell(195, $pdf->celda, '',0, 0,'L', 0);
		$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.5);
		$pdf->Cell(40, $pdf->celda, 'IMPORTE TOTAL', 0, 0,'R', 0);
		$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
		$pdf->Cell(8, $pdf->celda,'$', 0, 0, 'R', 0);
		$pdf->Cell(0, $pdf->celda,num($r['cotizacion']['importe_total']), 0, 1, 'R', 0);

		$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.1);
		$pdf->Cell(195, $pdf->celda, '',0, 0,'L', 0);
		$pdf->Cell(40, $pdf->celda, 'DESCUENTO COMERCIAL', 0, 0, 'R', 0);
		$pdf->SetFont($pdf->textfont,'',$pdf->font_size);

		$descuento_comercial=0;
		if($venta_directa || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
		{
			$descuento_comercial = $r['cotizacion']['descuento_cliente'];
			$descuento_comercial = $descuento_comercial>@$r['cotizacion']['descuento_cliente_cupon']?$descuento_comercial-@$r['cotizacion']['descuento_cliente_cupon']-@$r['cotizacion']['promocion_opcional_descuento']:0;
		}
		else
		{
			$descuento_comercial = $r['cotizacion']['descuento_distribuidor']+$r['cotizacion']['envio'];//debug($r['cotizacion']);exit;
			$descuento_comercial = $descuento_comercial>@$r['cotizacion']['descuento_distribuidor_cupon']?$descuento_comercial-@$r['cotizacion']['descuento_distribuidor_cupon']-@$r['cotizacion']['promocion_opcional_descuento']:0;
		}

		$pdf->Cell(8, $pdf->celda,'- $', 0, 0, 'R', 0);
		$pdf->Cell(0, $pdf->celda,num($descuento_comercial), 0, 1,'R', 0);

		if(@$r['cotizacion']['promocion_porcentaje'])
		{
			$y=$pdf->getY();
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->setY($y);
			$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
			$pdf->Cell(40, $pdf->celda, "DESCUENTO PROMOCIÓN ({$r['cotizacion']['promocion_porcentaje']}%) ", '', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size+1);
			$pdf->Cell(8, $pdf->celda,'- $', 0, 0, 'R', 0);
			$descuento = $r['cotizacion']['promocion_porcentaje_monto'];
			$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);
		}

		if(@$r['cotizacion']['promocion_fija'])
		{
			$y=$pdf->getY();
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->setY($y);
			$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
			$pdf->Cell(40, $pdf->celda, "DESCUENTO FIJO PROMOCIÓN", '', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'- $', 0, 0, 'R', 0);
			$descuento = $r['cotizacion']['promocion_fija'];
			$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);
		}

		if(@$r['cotizacion']['promocion_opcional_descuento'])
		{
			$y=$pdf->getY();
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->setY($y);
			$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
			$pdf->Cell(40, $pdf->celda, "DESCUENTO POR PROMOCIÓN", '', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'- $', 0, 0, 'R', 0);
			$descuento = $r['cotizacion']['promocion_opcional_descuento'];
			$pdf->Cell(0, $pdf->celda, num($descuento), '', 1,'R', 0);
		}

		if(($venta_directa && @$r['cotizacion']['descuento_cliente_cupon']) || (!$venta_directa && @$r['cotizacion']['descuento_distribuidor_cupon']))
		{
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1);
			$pdf->Cell(195, $pdf->celda, '', '', 0,'L', 0);
			$pdf->Cell(40, $pdf->celda, 'DESCUENTO POR CUPÓN', '', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'- $', 0, 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda, $venta_directa?num($r['cotizacion']['descuento_cliente_cupon']):num($r['cotizacion']['descuento_distribuidor_cupon']), '', 1,'R', 0);
		}

		if($r['cotizacion']['descuento_paquete_distribuidor'] && !$venta_directa)
		{
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.1);
			$pdf->Cell(195, $pdf->celda, '',0, 0,'L', 0);
			$pdf->Cell(40, $pdf->celda, 'DESCUENTO DE: '.($r['cotizacion']['descuento_paquete_distribuidor']*100) .' % - '.$calculo['paquete_adquirido']['nombre'], '', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'- $', 0, 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda,num($r['cotizacion']['descuento_paquete_distribuidor']*$r['cotizacion']['importe_total']), '', 1,'R', 0);
		}

		if (!empty($r['cotizacion']['envio']))
		{
			$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.3);
			$pdf->Cell(30, $pdf->celda, 'CARGO DE RECUPERACIÓN', 0, 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'$', 0, 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['envio']), 0, 1,'R', 0);

			$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size-1);
			$pdf->Cell(30, $pdf->celda, '(INCLUYE ENVÍO E INSTALACIÓN)', 0, 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(0, $pdf->celda, '', 0, 1,'R', 0);
		}

		$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
		$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
		$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.3);
		$pdf->Cell(30, $pdf->celda, 'SUB-TOTAL', 'T', 0, 'R', 0);
		$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
		$pdf->Cell(8, $pdf->celda,'$', 'T', 0, 'R', 0);
		$subtotal = 0;
		if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
			$subtotal = $r['cotizacion']['subtotal_cliente'];
		else
			$subtotal = $r['cotizacion']['subtotal_distribuidor'];
		$pdf->Cell(0, $pdf->celda, num($subtotal), 'T', 1,'R', 0);

		$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
		$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
		$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.1);
		$pdf->Cell(30, $pdf->celda, 'IVA 16 %', 'B', 0, 'R', 0);
		$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
		$pdf->Cell(8, $pdf->celda,'$', 0, 0, 'R', 0);
		$iva = 0;
		if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
			$iva = $r['cotizacion']['iva_cliente'];
		else
			$iva = $r['cotizacion']['iva_distribuidor'];
		$pdf->Cell(0, $pdf->celda,num($iva), 'B', 1,'R', 0);

		$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
		$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
		$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+3);
		$pdf->Cell(30, $pdf->celda,'TOTAL',0, 0, 'R', 0);
		$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
		$pdf->Cell(8, $pdf->celda,'$', 'T', 0, 'R', 0);
		$total = 0;
		if($venta_directa  || (!$venta_directa && in_array(@$r['cotizacion']['status_id'],array(1))))
			$total = $r['cotizacion']['total_cliente'];
		else
			$total = $r['cotizacion']['total_distribuidor'];
		$pdf->Cell(0, $pdf->celda,num($total), 0, 1,'R', 0);

		if(@$r['cotizacion']['promocion_msi'] && @$r['cotizacion']['promocion_msi']==12 && $venta_directa)
		{
			$pdf->Cell(185, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size);
			$pdf->Cell(50, $pdf->celda,"Su pago a {$r['cotizacion']['promocion_msi']} meses sin intereses con AMEX",'', 0, 'R', 0, NULL, 1);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'$', 'T', 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['promocion_msi_cliente']), 'T', 1, 'R', 0);
		}
		elseif(@$r['cotizacion']['promocion_msi'] && @$r['cotizacion']['promocion_msi']==18)
		{
			$pdf->Cell(185, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size);
			$pdf->Cell(50, $pdf->celda,"Su pago a {$r['cotizacion']['promocion_msi']} meses sin intereses con Banamex",'', 0, 'R', 0, NULL, 1);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'$', 'T', 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['promocion_msi_cliente']), 'T', 1, 'R', 0);
		}

		if(($venta_directa && @$r['cotizacion']['descuento_cupon'] && $r['cotizacion']['opcion_cupon_id']==2) || (!$venta_directa && @$r['cotizacion']['descuento_cupon'] && $r['cotizacion']['opcion_cupon_id']==2))
		{
			$pdf->Cell(195, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->Cell(10, $pdf->celda, '', 0, 0,'L', 0);
			$pdf->SetFont($pdf->textfont,'B',$pdf->font_size+1.1);
			$pdf->Cell(30, $pdf->celda,'Su pago a 12 meses sin intereses por Cupón','T', 0, 'R', 0);
			$pdf->SetFont($pdf->textfont,'',$pdf->font_size);
			$pdf->Cell(8, $pdf->celda,'$', 'T', 0, 'R', 0);
			$pdf->Cell(0, $pdf->celda,num($r['cotizacion']['mensualidad_distribuidor_cupon']), 'T', 1,'R', 0);
			//$pdf->Cell(0, $pdf->celda, num($r['cotizacion']['mensualidad_cliente_cupon']), 'T', 1, 'R', 0);
		}
	}

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

	$pdf->Cell(70, $pdf->celda, '', 0, 0,'L', 0);
	$pdf->MultiCell(192, $pdf->celda*2,$html_alianza?$html_alianza:'', $borde,'R',0,1,null,null,1,1,true,1,$pdf->celda*3,'M',true);

	if($cuenta->venta_directa)
	{
		$pdf->header=FALSE;
		$pdf->AddPage();
		$pdf->footer=FALSE;
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

		$html='<b>TÉRMINOS Y CONDICIONES GENERALES PARA LA VENTA EN MÉXICO DE ELECTRODOMÉSTICOS (VÁLIDOS ÚNICAMENTE PARA LA LÍNEA DOMÉSTICA) QUE CELEBRAN POR UNA PARTE MIELE, S.A. DE C.V. (EN LO SUCESIVO, “MIELE”) Y POR LA OTRA LA PERSONA CUYO NOMBRE, DATOS GENERALES Y FIRMA QUEDEN REGISTRADOS EN LA ORDEN DE COMPRA DE QUE SE TRATE (EN LO SUCESIVO, EL “CLIENTE FINAL”), EN CONJUNTO, LAS “PARTES”, CONFORME A LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS:</b><br>
			   	<b>DECLARACIONES</b>
				<b>I.  Declara MIELE que:</b><br>
				I.1. Es una sociedad legalmente constituida de conformidad con la legislación mexicana.<br>
				I.2. Es su voluntad celebrar el presente Contrato, con base en los Términos y Condiciones aquí señalados.<br><br>
				<b>II. Declara el CLIENTE FINAL que: </b><br>
				II.1. Es una persona física o moral con capacidad jurídica para celebrar el presente Contrato. <br>
				II.2. Es su voluntad celebrar el presente Contrato, con base en los Términos y Condiciones aquí señalados.<br> <br>
				<b>CLAÚSULAS</b><br>
				<b>GENERAL.</b> Las Partes convienen que previa aceptación y firma de los Términos y Condiciones aquí establecidos el Cliente Final recibirá su Orden de Compra al momento de colocar un pedido con MIELE. <br>
				<b>PRIMERA. Órdenes de Compra.</b><br>
					1.1. Toda Orden de Compra será final y definitiva desde que es aprobada por el Cliente Final mediante la firma de los términos y condiciones aquí establecidos.<br>
					1.2. La colocación de una Orden de Compra se deberá realizar a través del “Formato Autorizado de Orden de Compra”.<br>
					1.3. Toda Orden de Compra deberá incluir: i) Fecha de elaboración; ii) Datos de Facturación del Cliente Final (Nombre o razón social, sucursal, domicilio fiscal y Registro Federal de Contribuyentes); iii) Correo electrónico para envío de factura electrónica; iv) Nombre completo del Cliente Final al que quedarán registrados los equipos; v) Fecha tentativa de entrega; vi) Dirección completa de entrega y de instalación (calle, número, colonia, código postal, ciudad y entidad federativa); viii) Detalle de Equipos (modelo, descripción, cantidad, precio de lista unitario, descuento comercial unitario, subtotal, etc.); ix) Nombre completo del vendedor responsable de la venta.<br>
					1.4. MIELE procesará la Orden de Compra previo pago efectuado por el Cliente Final del 50% del valor total de la misma por concepto de anticipo, o bien, del 100% al momento de colocar el pedido. <br>
					1.5. En caso de efectuar el pago del 50% por concepto de anticipo, el resto deberá ser pagado por lo menos diez días hábiles anteriores a la fecha de entrega deseada por el Cliente Final y ratificada por MIELE.<br>
					1.6. El precio pagado por el Cliente Final en ningún caso y por ningún motivo será reembolsable, salvo por causas imputables a MIELE como inexistencia de los bienes solicitados por el Cliente Final.  <br>
					1.7. En caso de cancelación de un Orden de Compra por parte del Cliente Final y por causas no imputables a MIELE, el Cliente Final pagará a MIELE el 50% del valor total de la Orden de Compra por concepto de penalización. En caso de que el Cliente Final haya cubierto el 100% de la Orden de Compra MIELE sólo se obligará a devolver el 50%. Cualquier cancelación deberá ser solicitada por escrito directamente a MIELE.<br>
					1.8. Las Órdenes de Compra serán procesadas al día siguiente. <br>
				<br>
				<b>SEGUNDA. Almacenaje.</b><br>
					2.1. MIELE almacenará de forma gratuita y en beneficio del Cliente Final los equipos, accesorios y consumibles contenidos en una Orden de Compra formalmente colocada hasta por un periodo de 180 días naturales. En caso de que el Cliente Final solicite una ampliación del periodo, MIELE cobrará un 1% mensual sobre el monto total de la Orden de Compra (según precios de lista, sin incluir IVA y considerando el descuento comercial que se otorga) por los meses que excedan los 180 días naturales de almacenaje gratuito.<br>
					2.2. La entrega de los equipos que hayan permanecido en almacén por más de 180 días naturales sólo podrá ser realizada previa liquidación de los cargos de almacenaje devengados. <br>
				<br>
				<b>TERCERA. Entrega e Instalación de Equipos, Accesorios y Consumibles.</b><br>
					3.1. Las Órdenes de Compra que incluyan únicamente: Máquinas de Café portátiles y/o Aspiradoras y/o accesorios y/o consumibles generarán un cargo por envío del 5% del valor total de la venta.<br>
					3.2. La entrega e instalación de equipos empotrables (incluyendo accesorios y consumibles Miele) será realizada en cualquier punto de la República Mexicana y tendrá un cargo de recuperación de MXN$1,990.00 (Un mil novecientos noventa pesos 00/100 M.N.).<br>
					3.3. El cargo de recuperación incluye: fletes y maniobras necesarias para realizar la entrega de equipos y accesorios Miele en el domicilio de entrega mencionado en la Orden de Compra, así como su instalación en el domicilio por parte del Servicio Técnico Autorizado de MIELE, de acuerdo a la cláusula 6.4 del presente Contrato.<br>
					3.4. MIELE no presta el servicio de “volado” de equipos, por lo que el cargo mencionado en el punto 3.2 no incluye este tipo de maniobras en caso de ser requeridas. La contratación de este servicio se deberá de realizar a través de un tercero y a entera responsabilidad del Cliente Final. MIELE no será responsable de la calidad del servicio prestado por terceros, ni por daños a terceros, bienes muebles (incluyendo los mismos equipos y accesorios Miele) o inmuebles ocasionados por el tercero por la prestación del servicio de “volado”.<br>
					3.5. MIELE realizará la entrega de las Órdenes de Compra en un plazo máximo de i) tres días hábiles para el caso de la Ciudad de México (Distrito Federal y Área Metropolitana); ii) diez días hábiles para destinos fuera de la Ciudad de México. En todos los casos, dichos plazos son posteriores al día siguiente de haber sido procesada la Orden de Compra y sujeto a disponibilidad de inventario. Lo anterior no aplica para equipos o artículos catalogados por MIELE como “Venta sobre Pedido” en cuyo caso el Cliente Final deberá confirmar los tiempos de entrega con el Consultor de Ventas de Miele.<br>
					3.6. La entrega de los equipos, accesorios y/o consumibles MIELE se hará dentro de sus empaques originales y en buen estado.<br>
					3.7. En la entrega de productos, MIELE solicitará la firma del Cliente Final o de la persona responsable de recibirlos en un Acuse de Recibo o Nota de Transporte. Cualquier daño o alteración física a los empaques originales de los productos deberá asentarse en dicho documento, de lo contrario, MIELE no se hará responsable de reclamaciones subsecuentes por daños y/o alteraciones a los equipos.<br>
					3.8. Cualquier cambio en la dirección y/o en la fecha de entrega deberá ser informado directamente a Miele Service Center llamando al 01 800 64353 00 o enviando un correo electrónico a info@miele.com.mx.; con una anticipación de al menos 48 horas a la fecha de entrega o instalación deseada.<br>
				<br>
				<b>CUARTA. Devoluciones y Cambios Físicos. </b><br>
					4.1 La confirmación de una Orden de Compra es una operación comercial final y definitiva, por lo que MIELE no está obligada a aceptar cambios o devoluciones no justificadas.<br>
					4.2. MIELE únicamente aceptará equipos en devolución, a través de un cambio físico del mismo modelo cuando: i) presente fallas o defectos de fabricación; ii) presente daños físicos por causas que le sean imputables; o iii) presente exactamente la misma falla o desperfecto por tercera ocasión consecutiva pesar de haber sido reparado por el Servicio Técnico Miele en un periodo menor a 12 meses. Para que la devolución y cambio físico proceda se deberá de cumplir con: i) el equipo se encuentre dentro del periodo de validez de 2 años de garantía, contados a partir de la fecha de instalación; ii) la instalación fue realizada por el Servicio Técnico Autorizado de Miele; iii) El Cliente Final deberá solicitar la devolución y el cambio físico directamente a Miele Service Center llamando al 01 800 64353 00 o enviando un correo electrónico a info@miele.com.mx; iv) El Servicio Técnico Miele realizará las pruebas y validaciones necesarias para confirmar el estado del equipo dentro de los cinco días hábiles siguientes a la fecha de solicitud. Concluida la evaluación, MIELE aceptará o rechazará la solicitud de devolución. De ser aceptada, la entrega de un nuevo equipo se realizará conforme a los tiempos indicados en el numeral 3.5.<br>
					4.3. MIELE no autoriza la devolución y el cambio físico de equipo cuando i) presente fallas, daños o desperfectos por causas no imputables a MIELE o derivados de una deficiente instalación no realizada por el Servicio Técnico Miele; ii) presente fallas o desperfectos derivados de una utilización inadecuada del equipo o a la falta de mantenimiento del mismo; o iii) requiera ajustes mínimos a ser realizados por el Servicio Técnico Miele.<br>
				<br>
				<b>QUINTA. Servicio Técnico.</b><br>
					5.1. Todos los equipos Miele deberán ser instalados obligatoriamente por el Servicio Técnico Miele.<br>
					5.2. Todo servicio de instalación deberá ser requerido a Miele Service Center llamando al 01 800 6435300 o enviando un correo electrónico a info@miele.com.mx directamente por el Cliente Final proporcionando sus datos completos (nombre, dirección, teléfono, equipos a ser instalados, cualquier otra información relevante para dicho fin). MIELE no será responsable de ningún servicio de instalación o reparación que no haya sido solicitado por los medios mencionados.<br>
					5.3. El Miele Service Center responderá las solicitudes recibidas enviando un correo electrónico de confirmación, comunicando el número de Orden de Servicio y los datos de programación del servicio: i) Nombre del Técnico Autorizado Miele; y ii) Fecha y rango de horario de visita.<br>
					5.4. Los horarios de atención del Miele Service Center son de lunes a viernes de 9:00 am a 18:00 pm y los sábados de 10:00 am a 14:00 pm. Los horarios de programación del servicio técnico son de lunes a viernes de 9:00 am (primer servicio) a 17:00 pm (último servicio) y los sábados de 10:00 am (primer servicio) a 14:00 pm (segundo servicio).<br>
					5.5. MIELE programará la visita de un Técnico Autorizado Miele i) dentro de los tres días hábiles siguientes al día en que fue recibida la solicitud de servicio cuando tenga que ser realizado en alguna de las siguientes ciudades: Ciudad de México (Distrito Federal y área Metropolitana); Guadalajara (y área Metropolitana) y Puerto Vallarta, Jalisco; Monterrey (y área Metropolitana), Nuevo León; Punta Mita, Nayarit; San José del Cabo y Cabo San Lucas, Baja California Sur; Cancún y Playa del Carmen, Quintana Roo; o ii) dentro de los seis días hábiles posteriores a la fecha de solicitud del servicio cuando tenga que ser realizado en localidades distintas a las anteriores.<br>
					';
		$pdf->writeHTML($html, true, false, true, false, '');

		$html2='
			<b>SEXTA. Instalaciones.</b><br>
				6.1. Todos los servicios de instalación deberán de ser requeridos directamente por el Cliente Final.<br>
				6.2. Las guías mecánicas de los equipos incluyen los requerimientos de espacio, medidas, especificaciones, conexiones a energía eléctrica, conexiones de agua, conexiones de gas y cualquier requerimiento adicional para que MIELE pueda realizar la instalación de los equipos adquiridos por el Cliente Final.<br>
				6.3. Las guías mecánicas se encuentran disponibles dentro del portal en línea https://shop.miele.com.mx; el Cliente Final deberá de asegurar que las especificaciones solicitadas dentro de la guía mecánica se cumplan en su cabalidad, para así lograr una instalación eficiente y sin contratiempos por parte del Servicio Técnico Autorizado de Miele.<br>
				6.4. MIELE clasifica los servicios de instalación en dos categorías: i) Instalación Menor: Servicio de instalación de uno o dos equipos (sin incluir accesorios, trim kits, paneles, puertas, manijas. etc.) con un peso individual inferior a 40 kg, contando el Cliente Final con un máximo de una visita de un Técnico Autorizado Miele sin costo adicional; e ii) Instalación Mayor: Servicio de instalación de tres o más equipos (sin incluir accesorios, trim kits, paneles, puertas, manijas. etc.) o cuando la instalación incluya algún equipo con un peso individual superior a 40 kg, contando el Cliente Final con un máximo de tres visitas de un Técnico Autorizado Miele sin costo adicional.<br>
				6.5. Cuando la instalación no pueda ser concluida por razones no imputables a Miele dentro del número máximo de visitas señaladas en el numeral anterior, cada visita adicional generará un cargo para el Cliente Final de MXN$1,490.00 (Un mil cuatrocientos noventa pesos 00/100 M.N.) incluyendo IVA más los viáticos relacionados.<br>
				6.6. En caso de que el Técnico Autorizado Miele detecte empaques abiertos, daños y/o alteraciones físicas a los equipos lo asentará en su Informe de Servicio y el Cliente Final perderá el derecho a exigir el cumplimiento de la Póliza de Garantía para cubrir los daños o defectos (ver la Cláusula séptima. Garantías).<br>
				6.7. Cuando la instalación no pueda ser realizada por alguna razón no imputable a MIELE, el Técnico Autorizado Miele levantará un “Informe de Servicio” asentando los detalles a corregir y adjuntando evidencias de dichas causas, retirándose del domicilio. El Informe de Servicio será enviado al Cliente Final el día hábil siguiente a la visita del Técnico Autorizado Miele.<br>
				6.8. El Técnico Autorizado Miele no realizará trabajo, ajuste o adecuación alguna distinta a las estrictamente requeridas para la instalación de los equipos Miele. MIELE informará al Cliente Final sobre los requerimientos de espacio, medidas, especificaciones, conexiones a energía eléctrica, de agua o de gas y cualquier otro requerimiento para realizar la instalación. El Cliente Final debe asegurarse que dichos requerimientos se encuentren listos previo a la visita de instalación del Técnico Autorizado Miele. El Técnico Autorizado Miele no podrá esperar en el domicilio de instalación a que concluyan los ajustes y/o adecuaciones necesarias para poder iniciar o terminar la instalación de equipos Miele.<br>
				6.9. Si el tablero eléctrico no está cerrado o el inmueble no cuenta con medidor de CFE, el Técnico Autorizado Miele asentará en su “Informe de Servicio” que no es recomendable realizar la instalación, debido a una mayor probabilidad de descarga eléctrica que podría resultar en daños al equipo Miele y/o al usuario del mismo. Si el Cliente Final o la persona responsable de recibir al Técnico Autorizado Miele insiste en que se realice la instalación, deberá solicitarlo por escrito, en cuyo caso, el Cliente Final perderá el derecho a exigir el cumplimiento de la Póliza de Garantía para cubrir los daños ocasionados al equipo de presentarse algún siniestro por esta causa.<br>
				6.10. Una vez concluida la instalación, el Técnico Autorizado Miele podrá dar una explicación elemental al Cliente Final sobre las funciones básicas de los equipos, más no podrá elaborar sus respuestas ni abordar preguntas específicas sobre las funciones especiales de los equipos. MIELE ha desarrollado programas de capacitación que permitirán al Cliente Final obtener respuestas mucho más concretas sobre el uso y mantenimiento de sus nuevos equipos Miele, ver la Cláusula novena. Programas de capacitación al Cliente Final.<br>
			<br>
			<b>SEPTIMA. Garantías.</b><br>
				7.1. MIELE garantiza la compra original de equipos que el Cliente Final haya adquirido en caso de cualquier defecto (materiales, mano de obra y desempeño), incluyendo todas las partes y/o componentes. La garantía sólo aplica mientras el equipo permanezca dentro del país y será nula en cualquier otro país.<br>
				7.2. El periodo de garantía para el equipo, sus accesorios, partes y/o componentes es de dos años a partir de su fecha de instalación, siempre que el equipo no haya sufrido daños entre la fecha de entrega y la fecha de instalación. MIELE no asumirá responsabilidad alguna por daño o alteración causada al equipo, sus accesorios, partes y/o componentes en el periodo comprendido entre su fecha de entrega y la fecha de instalación.<br>
				7.3. La garantía no cubre daños o defectos cuando el equipo: i) ha sido utilizado en condiciones distintas a las normales; ii) no ha sido operado con base en las instrucciones y/o manual de uso que lo acompaña; iii) ha sido instalado, alterado o reparado por terceros no autorizados por MIELE; iii) ha sido extraído de su empaque original y no cuenta con los sellos de seguridad al momento de ser instalados por un Técnico Autorizado Miele. Cualquier desperfecto o falla ocasionada por una instalación deficiente realizada por algún técnico distinto al Servicio Técnico Autorizado de Miele invalida la garantía.<br>
				7.4. El Técnico Autorizado Miele, al momento de realizar la instalación, sellará la Póliza de Garantía de cada equipo instalado con su nombre, firma y fecha de instalación.<br>
				7.5. En caso de equipos “Free-Standing”, la tienda o Distribuidor sellará la Póliza de Garantía de cada equipo vendido con su nombre, firma y fecha de venta.<br>
				7.6. MIELE desconoce cualquier otra garantía expresa o implícita en el equipo o cualquier otra garantía ofrecida por un tercero.<br>
				7.7. MIELE no asume responsabilidad alguna o cualquier otra obligación con respecto a daño a personas o bienes que resulte del uso de algún equipo Miele o sus accesorios, partes, componentes, refacciones, entre otros, cuando los mismos no hayan sido comprados en una tienda autorizada por MIELE.<br>
			<br>
			<b>OCTAVA. Descuentos Autorizados al Cliente Final.</b><br>
				8.1. MIELE otorga un descuento especial al Cliente Final, cuando este último realice la compra de todos los equipos que integran alguna de las Categorías de Cocina siguientes:
					a) Basic Kitchen Opción 1: 1 Parrilla (KM o KMR) ó 2 CombiSets (CS) más 1 Horno de convección ó 1 Horno de Vapor (H o DG o DGC). En este caso se otorgará un 5% de descuento.
					b) Basic Kitchen Opción 2: 1 Estufa dual (HR). En este caso se otorgará un 5% de descuento.
					c) Advanced Kitchen: Basic Kitchen más 1 Lavavajillas (G). En este caso se otorgará un 7% de descuento.
					d) Premium Kitchen Opción 1: Advanced Kitchen más 1 Refrigerador Mastercool (K) más 1 Congelador Mastercool (F). En este caso se otorgará un 10% de descuento.
					e) Premium Kitchen Opción 2: Advanced Kitchen más 1 BottomMount (Refrigerador / Congelador) (KF). En este caso se otorgará un 10% de descuento.
					f) Luxury Kitchen: Premium Kitchen más Cava de Vinos (KWT). En este caso se otorgará un 12% de descuento.
					<br>
				8.2. Los descuentos anteriores sólo son aplicables en la venta de todos los equipos de una misma Categoría de Cocina aun y cuando el Cliente Final adquiera algún otro equipo adicional perteneciente a una categoría distinta.<br>
				8.3. En la venta de cualquier equipo MasterCool, y en la venta de cualquier modelo de campana (ya sea de isla o de pared), MIELE podrá ofrecer al Cliente Final un descuento del 10% sobre los Precios de Lista Vigentes (sin incluir IVA) aun y cuando el Cliente Final no adquiera todos los equipos de alguna de las categorías mencionadas en el numeral 7.1. Dicho descuento no es acumulable a menos que el Cliente Final adquiera una combinación de equipos para integrar alguna de las Categorías de Cocina a que se refiere el numeral 7.1.<br>
				8.4. El descuento autorizado se calculará sobre el monto total de la venta (incluyendo accesorios, trim kits, paneles, puertas, manijas, etc.) sin incluir IVA. Esta Política de Descuentos a Cliente Final no aplica acumulando equipos de más de un usuario final distinto o acumulando equipos vendidos a un mismo usuario final en distintos periodos de venta.<br>
				8.5. Esta Política de Descuentos a Cliente Final sólo es aplicable para equipos de una misma cocina. No aplica sumando los equipos vendidos a un mismo Cliente Final, pero que serán instalados en cocinas distintas. MIELE se reserva el derecho de verificar el cumplimiento a este apartado. Esta Política de Descuentos a Cliente Final sólo es aplicable en modelos de línea que estén incluidos dentro de la Lista de Precios Vigentes publicada por MIELE y no es acumulable con otras promociones Miele, a menos que exista una comunicación formal de la Dirección Comercial de Miele.<br>
			<br>
			<b>NOVENA. Programas de capacitación al Cliente Final.</b><br>
				9.1. MIELE cuenta con sesiones de capacitación dirigidas a Clientes Finales que desean conocer más sobre el uso adecuado, mantenimiento y limpieza de sus equipos Miele. Estas sesiones de capacitación son ofrecidas por MIELE de manera gratuita y tienen lugar en Miele Experience Center.<br>
				9.2. Adicionalmente a lo establecido en el punto anterior, MIELE ofrece un programa “Concierge” de capacitación al Cliente Final directamente en su hogar. Este programa se denomina “Home Care Program” y ofrece los siguientes beneficios: i) asesoramiento personalizado por un experto sobre el uso, mantenimiento y limpieza de los equipos adquiridos por el Cliente Final; ii) ajuste de programas y funciones de los equipos, con base en las necesidades y preferencias del Cliente Final; iii) Consejos prácticos y recomendaciones para obtener mejores resultados y asegurar la vida útil de los equipos en excelente estado.<br>
				9.3. El servicio “Home Care Program” tiene un cargo adicional que depende de la Entidad Federativa en donde será realizado el servicio. Los precios del programa los puede consultar con su Consultor de Ventas.<br>
				9.4. Una vez adquirido el servicio, el Cliente Final podrá agendar el servicio con MIELE. Los datos de contacto se encuentran disponibles en www.miele.com.mx.<br>
				9.5. Es recomendable solicitar el servicio Home Care Program con al menos 5 días hábiles de anticipación, para garantizar disponibilidad de fecha y horario.<br>
			<br>
			<b>DÉCIMA. Política de Operación Comercial.</b><br>
				10.1. Los términos y condiciones aquí previstas están de acuerdo a la Política de Operación Comercial de la línea doméstica de productos Miele vigente.<br>

			<br>
			<b>DÉCIMA PRIMERA. Modificación de los Términos y Condiciones y otros.</b><br>
				11.1. MIELE se reserva el derecho de modificar a su entera discreción y en cualquier tiempo los términos y condiciones aquí establecidas, la Política de Operación Comercial a que se refiere el apartado anterior y cualquier otra información relacionada.<br>
			<br>';
		$pdf->AddPage();
		$pdf->writeHTML($html2, true, false, true, false, '');
	}
	elseif(!$cuenta->venta_directa)
	{
		$pdf->header=FALSE;
		$pdf->AddPage();
		$pdf->footer=FALSE;
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

		$html='<b>TÉRMINOS Y CONDICIONES GENERALES PARA LA VENTA EN MÉXICO DE ELECTRODOMÉSTICOS (VÁLIDOS ÚNICAMENTE PARA LA LÍNEA DOMÉSTICA) QUE CELEBRAN POR UNA PARTE MIELE, S.A. DE C.V. (EN LO SUCESIVO, “MIELE”) Y POR LA OTRA EL DISTRIBUIDOR AUTORIZADO MIELE (EN LO SUCESIVO, EL “DISTRIBUIDOR”), EN CONJUNTO, LAS “PARTES”, CONFORME A LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS:</b><br>
			   	<b>DECLARACIONES</b>
				<b>I.  Declara MIELE que:</b><br>
				I.1. Es una sociedad legalmente constituida de conformidad con la legislación mexicana.<br>
				I.2. Es su voluntad celebrar el presente Contrato, con base en los Términos y Condiciones aquí señalados.<br><br>
				<b>II. Declara el DISTRIBUIDOR que:</b><br>
				II.1. Es una persona física o moral con capacidad jurídica para celebrar el presente Contrato.<br>
				II.2. Es un Distribuidor autorizado MIELE.<br>
				II.3. Es su voluntad celebrar el presente Contrato, con base en los Términos y Condiciones aquí señalados.<br> <br>
				<b>CLAÚSULAS</b><br>
				<b>GENERAL.</b> Las Partes convienen que previa aceptación de los Términos y Condiciones aquí establecidos, el Distribuidor podrá colocar su Orden de Compra de manera automática a través del portal de MIELE.<br>
				<b>PRIMERA. Cotizaciones.</b><br>
					1.1. El Distribuidor podrá cotizar equipos, accesorios y consumibles MIELE a cualquier cliente potencial bajo sus políticas, lineamientos y procedimientos internos, en observancia de la Política Comercial de Miele.<br>
					1.2. Toda cotización deberá realizarse en Pesos Mexicanos atendiendo a la “Lista de Precios Vigentes” autorizada y comunicada por el área comercial de MIELE.<br>
					1.3. El Distribuidor tiene la posibilidad de ofrecer un descuento al usuario Final en la cotización y venta de equipos, accesorios y/o consumibles con base en la Cláusula Décimo primera. Descuentos Autorizados al Cliente Final.<br>
					1.4. El Distribuidor tiene la posibilidad de ofrecer financiamiento al Cliente Final en la venta de equipos y accesorios Miele hasta por un periodo máximo de 12 meses. Los lineamientos que regulan este financiamiento se encuentran detallados en la Cláusula Tercera.<br>
					1.5. Queda prohibida cualquier cotización a terceros dedicados a distribuir y/o vender equipos de cocina que no sean Distribuidores Autorizados Miele (Sub-Distribuidores).<br>
					1.6. Toda cotización deberá detallar los equipos, accesorios y consumibles Miele de que se trate (modelo, descripción, cantidad, precio unitario, etc.), especificar si el monto cotizado incluye IVA o no y, cuando así aplique, el descuento ofrecido al usuario final.<br>
					1.7. Cualquier descuento que se ofrezca al usuario final deberá estar dentro de los límites y conforme a los parámetros establecidos en el presente.<br>
				<br>
				<b>SEGUNDA. Órdenes de Compra.</b><br>
					2.1. Toda Orden de Compra será final y definitiva desde que es aprobada por el personal responsable del Distribuidor y enviada a MIELE.<br>
					2.2. La colocación de una Orden de Compra se deberá realizar a través del portal en línea para Distribuidores Autorizados “Miele Partners” https://www.mielepartners.com.mx.<br>
					2.3. Toda Orden de Compra deberá incluir: i) Fecha de elaboración; ii) Datos de Facturación del Distribuidor (Nombre o razón social, sucursal, domicilio fiscal y Registro Federal de Contribuyentes); iii) Correo electrónico para envío de factura electrónica; iv) Datos completos del Cliente Final al que quedarán registrados los equipos (Nombre o Razón Social, Número telefónico, correo electrónico, nombre de la persona de contacto); v) Fecha tentativa de entrega; vi) Dirección completa de entrega y de instalación (calle, número, colonia, código postal, ciudad y entidad federativa); viii) Detalle de Equipos (modelo, descripción, cantidad, precio de lista unitario, descuento comercial unitario, subtotal, etc.); ix) Nombre completo del vendedor responsable de la venta.<br>
					2.4. MIELE procesará la Orden de Compra previa liquidación del Distribuidor de la totalidad del monto de la misma y/o luego de estar al corriente con el balance que el departamento de Cuentas por Cobrar de MIELE tenga registrado. Para que MIELE considere que una orden está liquidada, los pagos deberán de estar aplicados en firme en la cuenta bancaria de MIELE, por lo que no se considerará como pagado ningún pago que se encuentre salvo buen cobro.<br>
					2.5. Las Órdenes de Compra ingresadas y confirmadas por el Distribuidor dentro del portal en línea “Miele Partners” serán procesadas por MIELE dentro del sistema ERP a más tardar el día hábil siguiente a la fecha en que fueron recibidas.<br>
					2.6. El Distribuidor podrá consultar el número de Orden de Venta generado para su orden de Compra directamente en el portal de “Miele Partners”. Respetando los tiempos mencionados en el punto anterior.<br>
				<br>
				<b>TERCERA. Venta a meses sin intereses.</b><br>
					3.1. El Distribuidor tiene autorizada la posibilidad de ofrecer un máximo de 12 meses sin intereses en la venta de equipos y accesorios Miele en cualquier monto o configuración.<br>
					3.2. El costo de dicho financiamiento será cubierto por el Distribuidor.<br>
					3.3. El financiamiento de compra no podrá combinarse con los “Descuentos Autorizados a Cliente Final” (Cláusula décimo primera). El Distribuidor tiene la posibilidad de ofrecer sólo una opción: Venta hasta 12 meses sin intereses o el descuento autorizado MIELE.<br>
					3.4. Si el Distribuidor incurre en la violación del punto anterior, el Distribuidor será penalizado por MIELE por un cargo equivalente al descuento que incorrectamente aplicó en la venta de accesorios y equipos Miele. MIELE se reserva el derecho de no entregar los equipos y accesorios hasta que el Distribuidor no haya liquidado el total de la Penalización.<br>
				<br>
				<b>CUARTA. Sub-Distribución.</b><br>
					4.1. MIELE no autoriza al Distribuidor a realizar la venta de equipos y accesorios Miele a estudios de cocina y/o terceros dedicados a distribuir y vender electrodomésticos que no sean Distribuidores Autorizados Miele.<br>
					4.2. En caso de ser contactados por un “Sub-Distribuidor” interesado en comprar equipos y accesorios Miele, el Distribuidor podrá realizar la venta siempre y cuando la operación sea realizada directamente con el Cliente Final del Sub-Distribuidor, apegándose en todo momento a las condiciones comerciales vigentes.<br>
					4.3. Si el Distribuidor incurre en la cotización y venta de equipos y accesorios Miele directamente a Sub- Distribuidores, se le suspenderá temporalmente la distribución sin responsabilidad alguna para MIELE.<br>
				<br>
				<b>QUINTA. Almacenaje.</b><br>
					5.1. MIELE almacenará de forma gratuita y en beneficio del Distribuidor los equipos, accesorios y consumibles contenidos en una Orden de Compra formalmente colocada hasta por un periodo de 180 días naturales. En caso de que el Distribuidor solicite una ampliación del periodo, MIELE cobrará un 1% mensual sobre el monto total de la Orden de Compra (según precios de lista, sin incluir IVA y considerando el descuento comercial que se otorga), por los meses que excedan los 180 días naturales de almacenaje gratuito.<br>
					5.2. La entrega de los equipos que hayan permanecido en almacén por más de 180 días naturales sólo podrá ser realizada previa liquidación de los cargos de almacenaje devengados.<br>
				<b>SEXTA. Entrega e Instalación de Equipos, Accesorios y Consumibles.</b><br>
					6.1. Las Órdenes de Compra que incluyan únicamente: Máquinas de Café portátiles y/o Aspiradoras y/o accesorios y/o consumibles generarán un cargo por envío del 5% del valor total de la venta.<br>
					6.2. La entrega e instalación de equipos empotrables (incluyendo accesorios y consumibles Miele) será realizada en cualquier punto de la República Mexicana y tendrá un cargo de recuperación de MXN$1,990.00 (Un mil novecientos noventa pesos 00/100 M.N.).<br>
					6.3. El cargo de recuperación incluye: fletes y maniobras necesarias para realizar la entrega de equipos y accesorios Miele en el domicilio de entrega mencionado en la Orden de Compra, así como su instalación en el domicilio por parte del Servicio Técnico Autorizado de MIELE, de acuerdo a la cláusula 9.4 del presente Contrato.<br>
					6.4. MIELE no presta el servicio de “volado” de equipos, por lo que el cargo mencionado en el punto 6.2 no incluye este tipo de maniobras en caso de ser requeridas. La contratación de este servicio se deberá de realizar a través de un tercero y a entera responsabilidad del Distribuidor y/o Cliente Final. MIELE no será responsable de la calidad del servicio prestado por terceros, ni por daños a terceros, bienes muebles (incluyendo los mismos equipos y accesorios Miele) o inmuebles ocasionados por el tercero por la prestación del servicio de “volado”.<br>
					6.5 El cargo adicional por los servicios de envío e instalación nunca será afectado por el Descuento Comercial que se otorga al Distribuidor.<br>
					6.6. Queda estrictamente prohibido que el Distribuidor cobre o transfiera al Cliente Final costo alguno por concepto de envío e instalación de equipos, accesorios y/o consumibles en los casos donde MIELE no está aplicando ningún cargo o costo por este concepto.<br>
					6.7. MIELE realizará la entrega de las Órdenes de Compra en un plazo máximo de i) tres días hábiles para el caso de la Ciudad de México (Distrito Federal y Área Metropolitana); ii) diez días hábiles para destinos fuera de la Ciudad de México. En todos los casos, dichos plazos son posteriores al día siguiente de haber sido procesada la Orden de Compra y sujeto a disponibilidad de inventario. Lo anterior no aplica para equipos o artículos catalogados por MIELE como “Venta sobre Pedido” en cuyo caso el Distribuidor deberá confirmar los tiempos de entrega con el Consultor de Ventas de Miele.<br>
					6.8. La entrega de los equipos, accesorios y/o consumibles MIELE se hará dentro de sus empaques originales y en buen estado.<br>
					6.9. En la entrega de productos, MIELE solicitará la firma del Distribuidor, del Cliente Final o de la persona responsable de recibirlos en un Acuse de Recibo o Nota de Transporte. Cualquier daño o alteración física a los empaques originales de los productos deberá asentarse en dicho documento, de lo contrario, MIELE no se hará responsable de reclamaciones subsecuentes por daños y/o alteraciones a los equipos.<br>
					6.10. Cualquier cambio en la dirección y/o en la fecha de entrega deberá ser informado directamente a Miele Service Center llamando al 01 800 64353 00 o enviando un correo electrónico a info@miele.com.mx.; con una anticipación de al menos 48 horas a la fecha de entrega o instalación deseada.<br>
					';
		$pdf->writeHTML($html, true, false, true, false, '');

		$html2='
			<b>SÉPTIMA. Devoluciones y Cambios Físicos.</b><br>
				7.1. La confirmación de una Orden de Compra es una operación comercial final y definitiva, por lo que MIELE no está obligada a aceptar cambios o devoluciones no justificadas.<br>
				7.2. MIELE únicamente aceptará equipos en devolución, a través de un cambio físico del mismo modelo cuando: i) presente fallas o defectos de fabricación; ii) presente daños físicos por causas que le sean imputables; o iii) presente exactamente la misma falla o desperfecto por tercera ocasión consecutiva pesar de haber sido reparado por el Servicio Técnico Miele en un periodo menor a 12 meses. Para que la devolución y cambio físico proceda se deberá de cumplir con: i) el equipo se encuentre dentro del periodo de validez de 2 años de garantía, contados a partir de la fecha de instalación; ii) la instalación fue realizada por el Servicio Técnico Autorizado de Miele; iii) El Cliente Final deberá solicitar la devolución y el cambio físico directamente a Miele Service Center llamando al 01 800 64353 00 o enviando un correo electrónico a info@miele.com.mx; iv) El Servicio Técnico Miele realizará las pruebas y validaciones necesarias para confirmar el estado del equipo dentro de los cinco días hábiles siguientes a la fecha de solicitud. Concluida la evaluación, MIELE aceptará o rechazará la solicitud de devolución. De ser aceptada, la entrega de un nuevo equipo se realizará conforme a los tiempos indicados en el numeral 6.7.<br>
				7.3. MIELE no autoriza la devolución y el cambio físico de equipo cuando i) presente fallas, daños o desperfectos por causas no imputables a MIELE o derivados de una deficiente instalación no realizada por el Servicio Técnico Miele; ii) presente fallas o desperfectos derivados de una utilización inadecuada del equipo o a la falta de mantenimiento del mismo; o iii) requiera ajustes mínimos a ser realizados por el Servicio Técnico Miele.<br>
			<br>
			<b>OCTAVA. Servicio Técnico.</b><br>
				8.1. Todos los equipos Miele deberán ser instalados obligatoriamente por el Servicio Técnico Miele.<br>
				8.2. Todo servicio de instalación deberá ser requerido a Miele Service Center llamando al 01 800 6435300 o enviando un correo electrónico a info@miele.com.mx directamente por el Cliente Final proporcionando sus datos completos (nombre, dirección, teléfono, equipos a ser instalados, cualquier otra información relevante para dicho fin). MIELE no será responsable de ningún servicio de instalación o reparación que no haya sido solicitado por los medios mencionados.<br>
				8.3. El Miele Service Center responderá las solicitudes recibidas enviando un correo electrónico de confirmación, comunicando el número de Orden de Servicio y los datos de programación del servicio: i) Nombre del Técnico Autorizado Miele; y ii) Fecha y rango de horario de visita.<br>
				8.4. Los horarios de atención del Miele Service Center son de lunes a viernes de 9:00 am a 18:00 pm y los sábados de 10:00 am a 14:00 pm. Los horarios de programación del servicio técnico son de lunes a viernes de 9:00 am (primer servicio) a 17:00 pm (último servicio) y los sábados de 10:00 am (primer servicio) a 14:00 pm (segundo servicio).<br>
				8.5. MIELE programará la visita de un Técnico Autorizado Miele i) dentro de los tres días hábiles siguientes al día en que fue recibida la solicitud de servicio cuando tenga que ser realizado en alguna de las siguientes ciudades: Ciudad de México (Distrito Federal y área Metropolitana); Guadalajara (y área Metropolitana) y Puerto Vallarta, Jalisco; Monterrey (y área Metropolitana), Nuevo León; Punta Mita, Nayarit; San José del Cabo y Cabo San Lucas, Baja California Sur; Cancún y Playa del Carmen, Quintana Roo; o ii) dentro de los seis días hábiles posteriores a la fecha de solicitud del servicio cuando tenga que ser realizado en localidades distintas a las anteriores.<br>
			<br>
			<b>NOVENA. Instalaciones.</b><br>
				9.1. Todos los servicios de instalación deberán de ser requeridos directamente por el Cliente Final. En caso de que el servicio de Instalación sea requerido por el Distribuidor deberá de proporcionar los datos completos del Cliente Final.<br>
				9.2. Las guías mecánicas de los equipos incluyen los requerimientos de espacio, medidas, especificaciones, conexiones a energía eléctrica, conexiones de agua, conexiones de gas y cualquier requerimiento adicional para que MIELE pueda realizar la instalación de los equipos adquiridos por el Cliente Final.<br>
				9.3. Las guías mecánicas se encuentran disponibles dentro del portal en línea de “Miele Partners” https://www.mielepartners.com.mx. Es responsabilidad del Distribuidor el entregar estos documentos al Cliente Final para así lograr una instalación eficiente y sin contratiempos por parte del Servicio Técnico Autorizado de Miele.<br>
				9.4. MIELE clasifica los servicios de instalación en dos categorías: i) Instalación Menor: Servicio de instalación de uno o dos equipos (sin incluir accesorios, trim kits, paneles, puertas, manijas. etc.) con un peso individual inferior a 40 kg, contando el Cliente Final con un máximo de una visita de un Técnico Autorizado Miele sin costo adicional; e ii) Instalación Mayor: Servicio de instalación de tres o más equipos (sin incluir accesorios, trim kits, paneles, puertas, manijas. etc.) o cuando la instalación incluya algún equipo con un peso individual superior a 40 kg, contando el Cliente Final con un máximo de tres visitas de un Técnico Autorizado Miele sin costo adicional.<br>
				9.5. Cuando la instalación no pueda ser concluida por razones no imputables a Miele dentro del número máximo de visitas señaladas en el numeral anterior, cada visita adicional generará un cargo para el Cliente Final de MXN$1,490.00 (Un mil cuatrocientos noventa pesos 00/100 M.N.) incluyendo IVA más los viáticos relacionados.<br>
				9.6. En caso de que el Técnico Autorizado Miele detecte empaques abiertos, daños y/o alteraciones físicas a los equipos lo asentará en su Informe de Servicio y el Cliente Final perderá el derecho a exigir el cumplimiento de la Póliza de Garantía para cubrir los daños o defectos (ver la Cláusula décima. Garantías).<br>
				9.7. Cuando la instalación no pueda ser realizada por alguna razón no imputable a MIELE, el Técnico Autorizado Miele levantará un “Informe de Servicio” asentando los detalles a corregir y adjuntando evidencias de dichas causas, retirándose del domicilio. El Informe de Servicio será enviado al Cliente Final, y en su caso al Distribuidor, el día hábil siguiente a la visita del Técnico Autorizado Miele.<br>
				9.8. El Técnico Autorizado Miele no realizará trabajo, ajuste o adecuación alguna distinta a las estrictamente requeridas para la instalación de los equipos Miele. MIELE informará al Cliente Final, y en su caso al Distribuidor, sobre los requerimientos de espacio, medidas, especificaciones, conexiones a energía eléctrica, de agua o de gas y cualquier otro requerimiento para realizar la instalación. El Cliente Final, y en su caso el Distribuidor, debe asegurarse que dichos requerimientos se encuentren listos previo a la visita de instalación del Técnico Autorizado Miele. El Técnico Autorizado Miele no podrá esperar en el domicilio de instalación a que concluyan los ajustes y/o adecuaciones necesarias para poder iniciar o terminar la instalación de equipos Miele.<br>
				9.9. Si el tablero eléctrico no está cerrado o el inmueble no cuenta con medidor de CFE, el Técnico Autorizado Miele asentará en su “Informe de Servicio” que no es recomendable realizar la instalación, debido a una mayor probabilidad de descarga eléctrica que podría resultar en daños al equipo Miele y/o al usuario del mismo. Si el Distribuidor, Cliente Final o la persona responsable de recibir al Técnico Autorizado Miele insiste en que se realice la instalación, deberá solicitarlo por escrito, en cuyo caso, el Distribuidor y/o Cliente Final perderá el derecho a exigir el cumplimiento de la Póliza de Garantía para cubrir los daños ocasionados al equipo de presentarse algún siniestro por esta causa.<br>
				9.10. Una vez concluida la instalación, el Técnico Autorizado Miele podrá dar una explicación elemental al Cliente Final sobre las funciones básicas de los equipos, más no podrá elaborar sus respuestas ni abordar preguntas específicas sobre las funciones especiales de los equipos. MIELE ha desarrollado programas de capacitación que permitirán al Cliente Final obtener respuestas mucho más concretas sobre el uso y mantenimiento de sus nuevos equipos Miele, ver la Cláusula décimo segunda, Programas de capacitación al Cliente Final.<br>
			<br>
			<b>DÉCIMA. Garantías.</b><br>
				10.1. MIELE garantiza la compra original de equipos que el Cliente Final haya adquirido al Distribuidor en caso de cualquier defecto (materiales, mano de obra y desempeño), incluyendo todas las partes y/o componentes. La garantía sólo aplica mientras el equipo permanezca dentro del país y será nula en cualquier otro país.<br>
				10.2. El periodo de garantía para el equipo, sus accesorios, partes y/o componentes es de dos años a partir de su fecha de instalación, siempre que el equipo no haya sufrido daños entre la fecha de entrega y la fecha de instalación. MIELE no asumirá responsabilidad alguna por daño o alteración causada al equipo, sus accesorios, partes y/o componentes en el periodo comprendido entre su fecha de entrega y la fecha de instalación.<br>
				10.3. La garantía no cubre daños o defectos cuando el equipo: i) ha sido utilizado en condiciones distintas a las normales; ii) no ha sido operado con base en las instrucciones y/o manual de uso que lo acompaña; iii) ha sido instalado, alterado o reparado por terceros no autorizados por MIELE; iii) ha sido extraído de su empaque original y no cuenta con los sellos de seguridad al momento de ser instalados por un Técnico Autorizado Miele. Cualquier desperfecto o falla ocasionada por una instalación deficiente realizada por algún técnico distinto al Servicio Técnico Autorizado de Miele invalida la garantía.<br>
				10.4. El Técnico Autorizado Miele, al momento de realizar la instalación, sellará la Póliza de Garantía de cada equipo instalado con su nombre, firma y fecha de instalación.<br>
				10.5. En caso de equipos “Free-Standing”, el Distribuidor sellará la Póliza de Garantía de cada equipo vendido con su nombre, firma y fecha de venta.<br>
				10.6. MIELE desconoce cualquier otra garantía expresa o implícita en el equipo o cualquier otra garantía ofrecida por un tercero.<br>
				10.7. MIELE no asume responsabilidad alguna o cualquier otra obligación con respecto a daño a personas o bienes que resulte del uso de algún equipo Miele o sus accesorios, partes, componentes, refacciones, entre otros, cuando los mismos no hayan sido comprados en una tienda autorizada por MIELE.<br>
			<br>
			<b>DÉCIMO PRIMERA. Descuentos Autorizados al Cliente Final.</b><br>
				11.1. El Distribuidor podrá otorgar un descuento especial al Cliente Final, cuando este último realice la compra de todos los equipos que integran alguna de las Categorías de Cocina siguientes:
					a) Basic Kitchen Opción 1: 1 Parrilla (KM o KMR) ó 2 CombiSets (CS) más 1 Horno de convección ó 1 Horno de Vapor (H o DG o DGC). En este caso se otorgará un 5% de descuento.
					b) Basic Kitchen Opción 2: 1 Estufa dual (HR). En este caso se otorgará un 5% de descuento.
					c) Advanced Kitchen: Basic Kitchen más 1 Lavavajillas (G). En este caso se otorgará un 7% de descuento.
					d) Premium Kitchen Opción 1: Advanced Kitchen más 1 Refrigerador Mastercool (K) más 1 Congelador Mastercool (F). En este caso se otorgará un 10% de descuento.
					e) Premium Kitchen Opción 2: Advanced Kitchen más 1 BottomMount (Refrigerador / Congelador) (KF). En este caso se otorgará un 10% de descuento.
					f) Luxury Kitchen: Premium Kitchen más Cava de Vinos (KWT). En este caso se otorgará un 12% de descuento.
				<br>
				11.2. Los descuentos anteriores sólo son aplicables en la venta de todos los equipos de una misma Categoría de Cocina aun y cuando el Cliente Final adquiera algún otro equipo adicional perteneciente a una categoría distinta.<br>';
		$pdf->AddPage();
		$pdf->writeHTML($html2, true, false, true, false, '');

		$html3='
				11.3. En la venta de cualquier equipo MasterCool, y en la venta de cualquier modelo de campana (ya sea de isla o de pared), el Distribuidor podrá ofrecer al Cliente Final un descuento del 10% sobre los Precios de Lista Vigentes (sin incluir IVA) aun y cuando el Cliente Final no adquiera todos los equipos de alguna de las categorías mencionadas en el numeral 11.1. Dicho descuento no es acumulable a menos que el Cliente Final adquiera una combinación de equipos para integrar alguna de las Categorías de Cocina a que se refiere el numeral 11.1.<br>
				11.4. El descuento autorizado se calculará sobre el monto total de la venta (incluyendo accesorios, trim kits, paneles, puertas, manijas, etc.) sin incluir IVA y deberá ser cubierto por el margen del Distribuidor (de su descuento comercial) excepto donde se indique lo contrario (para el 12% de descuento en ventas de “Luxury Kitchen” Miele reembolsará un 2% al Distribuidor directamente en su Orden de Compra). Esta Política de Descuentos a Cliente Final no aplica acumulando equipos de más de un usuario final distinto o acumulando equipos vendidos a un mismo usuario final en distintos periodos de venta.<br>
				11.5. Esta Política de Descuentos a Cliente Final sólo es aplicable para equipos de una misma cocina. No aplica sumando los equipos vendidos a un mismo Cliente Final, pero que serán instalados en cocinas distintas. Esta Política de Descuentos a Cliente Final sólo es aplicable en modelos de línea que estén incluidos dentro de la Lista de Precios Vigentes publicada por MIELE y no es acumulable con otras promociones Miele, a menos que exista una comunicación formal de la Dirección Comercial de Miele.<br>
				11.6. Al Distribuidor que incurra en la violación de esta Política de Descuentos a Cliente Final se le emitirá una Nota de Cargo por el monto de descuento que exceda al monto del descuento autorizado y se considerará la suspensión temporal de la distribución Miele. En caso de reincidencia se considerará el retiro permanente de la distribución Miele.<br>
				11.7. Al Distribuidor que proporcione prueba inequívoca (cotización en papel membretado o factura fiscal) de que otro Distribuidor está violando esta Política de Descuentos a Cliente Final, se le autorizará igualar dicho descuento, reembolsándole MIELE el monto de descuento que exceda del monto de descuento autorizado, vía Nota de Crédito a aplicarse contra su Orden de Compra.<br>
				11.8. MIELE se reserva el derecho de implementar cualquier iniciativa destinada a verificar el cumplimiento y apego a esta Política de Descuentos a Cliente Final, incluyendo de forma enunciativa más no limitativa, visitas y solicitud de cotizaciones mediante “comprador encubierto” (“Mistery Shopper”).<br>

			<br>
			<b>DÉCIMO SEGUNDA. Programas de capacitación al Cliente Final.</b><br>
				12.1. MIELE cuenta con sesiones de capacitación dirigidas a Clientes Finales que desean conocer más sobre el uso adecuado, mantenimiento y limpieza de sus equipos Miele. Estas sesiones de capacitación son ofrecidas por MIELE de manera gratuita y tienen lugar en Miele Experience Center.<br>
				12.2. Adicionalmente a lo establecido en el punto anterior, MIELE ofrece un programa “Concierge” de capacitación al Cliente Final directamente en su hogar. Este programa se denomina “Home Care Program” y ofrece los siguientes beneficios: i) asesoramiento personalizado por un experto sobre el uso, mantenimiento y limpieza de los equipos adquiridos por el Cliente Final; ii) ajuste de programas y funciones de los equipos, con base en las necesidades y preferencias del Cliente Final; iii) Consejos prácticos y recomendaciones para obtener mejores resultados y asegurar la vida útil de los equipos en excelente estado.<br>
				12.3. El servicio “Home Care Program” tiene un cargo adicional que depende de la Entidad Federativa en donde será realizado el servicio. Los precios del programa los puede consultar con su Consultor de Ventas.<br>
				12.4. El cargo del servicio “Home Care Program” nunca será afectado por el Descuento Comercial que se otorga como margen al Distribuidor.<br>
				12.5. El Distribuidor podrá ofrecer, cotizar y vender este servicio a través del portal en línea “Miele Partners”.<br>
				12.6. Una vez adquirido el servicio, el Cliente Final y/o Distribuidor podrá agendar el servicio con MIELE. Los datos de contacto se encuentran disponibles en www.miele.com.mx.<br>
				12.7. Es recomendable solicitar el servicio Home Care Program con al menos 5 días hábiles de anticipación, para garantizar disponibilidad de fecha y horario.<br>
			<br>
			<b>DÉCIMO TERCERA. Política de Operación Comercial.</b><br>
				13.1. Los términos y condiciones aquí previstos están sujetos a la Política de Operación Comercial de la línea doméstica de productos Miele vigente.<br>
			<br>
			<b>DÉCIMO CUARTA. Modificación de los Términos y Condiciones y Otros.</b><br>
				14.1. MIELE se reserva el derecho de modificar a su entera discreción y en cualquier tiempo los términos y condiciones aquí establecidos, la Política de Operación Comercial a que se refiere el apartado anterior y cualquier otra información relacionada. MIELE dará oportuno aviso sobre dichas modificaciones al Distribuidor para el caso de subsecuentes operaciones y a través del medio que MIELE estime conveniente.<br>
			<br>
			<b>DÉCIMO QUINTA. Confidencialidad.</b><br>
				15.1. La información contenida en los términos y condiciones aquí establecidos, en la Política de Operación Comercialyencualquieradesusanexosesdecarácterconfidencial,porloqueelDistribuidorlapreservará con tal carácter y se abstendrá de revelarla, transmitirla o de alguna otra forma divulgarla o proporcionarla a cualquier persona ajena sin previa autorización por escrito por parte de MIELE. Asimismo, el Distribuidor deberá asegurarse que el personal que tenga acceso a la misma se adhiera al compromiso de confidencialidad aquí previsto.<br>
				15.2. MIELE se reserva el derecho de suspender temporal o definitivamente la distribución al Distribuidor que incurra en la violación del compromiso de confidencialidad aquí referido, sin responsabilidad alguna para MIELE. <br>
				15.3. MIELE se reserva el derecho de implementar cualquier iniciativa para validar el cumplimiento del compromiso de confidencialidad, incluyendo visitas y solicitud de información mediante “comprador encubierto” (“Mistery Shopper”).<br>
			<br>
			';
		$pdf->AddPage();
		$pdf->writeHTML($html3, true, false, true, false, '');

	}
	
	//$pdf->output('I');
