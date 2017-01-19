/*
 * Blackcore by reZorte
 */
// MANIPULACION DE NUMEROS CON JS
function redondeo_imv(valor)
{
	if(isNaN(valor))
		return '';
	valor=eval(valor).toFixed(2);
	
	valor=valor+'';
	var val=valor.split('.');
	val[1]=val[1].substring(0,2);
	var res=val[0]+'.'+val[1];
	return eval(res);
}

function redondear(valor, pos){
	// PARA REDONDEAR CANTIDADES (USADO EN LA CONCLUSION)
	if(pos==0)
		return valor.toFixed(2);
	var res = Math.round(valor/pos);
	res *= pos;
	return res.toFixed(2);
}

function v_num(valor)
{
	// LIMPIA EL VALOR ELIMINA COMAS ESPACIOS Y SIGNOS DE PESOS
	valor=replaceAll(valor,'&nbsp;','');
	valor=replaceAll(valor,' ','');
	valor=replaceAll(valor,'%','');
	valor=replaceAll(valor,'$','');
	valor=replaceAll(valor,',','');
	if(valor)
		valor=eval(valor);
	return valor;
}

function d_num(div_id)
{
	// OBTIENE EL VALOR NUMERICO DE UN DIV POR ID 
	var valor=$('#'+div_id).html();
	return v_num(valor);
}

function moneda(valor, decimales)
{
	if(!decimales)
		decimales=2;
	// DEVUELVE EL VALOR CON FORMATO DE MONEDA ($) CON COMAS Y DOS DECIMALES
	if(isNaN(valor) || valor=='')
		return '$ 0.00';
	valor=eval(valor).toFixed(decimales);
	return '$ '+comas(valor);
}

function fnumero(valor)
{
	// DEVUELVE EL VALOR CON FORMATO CON COMAS Y DOS DECIMALES
	if(isNaN(valor))
		return '';
	valor=eval(valor).toFixed(2);
	return comas(valor);
}

function comas(nStr)
{
	// PONE COMAS CADA 3 POSICIONES
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function replaceAll( text, busca, reemplaza ){
	  while (text.toString().indexOf(busca) != -1)
	      text = text.toString().replace(busca,reemplaza);
	  return text;
}

function get_tr(elemento)
{
	// DEVUELVE EL NUMERO DEL TR EN EL QUE SE ENCUENTRA
	var row_index = $(elemento).parent().parent().index();
	return row_index;
}

function get_td(elemento)
{
	// OBTIENE EL INDICE DE LA COLUMNA ACTUAL EN LA TABLA
	var col_index=$(elemento).parent().index();
	return col_index;
}

function get_select_value(table_id, posx, posy)
{
	// DEVUELVE EL VALOR NUMERICO DE UN SELECT DENTRO DE UN TD POR COORDENADAS DE LA TABLA ESPECIFICADA
	var valor = $('#'+table_id+' tbody tr:eq('+posy+') td:eq('+posx+')').children('select').val();
	if(valor==undefined)
		return '';
	return v_num(valor);
}

function get_input_value(table_id, posx, posy)
{
	// DEVUELVE EL VALOR NUMERICO DE UN INPUT DENTRO DE UN TD POR COORDENADAS DE LA TABLA ESPECIFICADA
	var valor = $('#'+table_id+' tbody tr:eq('+posy+') td:eq('+posx+')').children('input').val();
	if(valor==undefined)
		return '';
	return v_num(valor);
}

function get_input_content(table_id, posx, posy)
{
	// DEVUELVE EL CONTENIDO DE UN INPUT DENTRO DE UN TD POR COORDENADAS DE LA TABLA ESPECIFICADA
	var valor = $('#'+table_id+' tbody tr:eq('+posy+') td:eq('+posx+')').children('input').val();
	if(valor==undefined)
		return '';
	return valor;
}

function get_td_value(table_id, posx, posy)
{
	// DEVUELVE EL VALOR NUMERICO DE UN DIV DENTRO DE UN TD POR COORDENADAS DE LA TABLA ESPECIFICADA
	var valor = $('#'+table_id+' tbody tr:eq('+posy+') td:eq('+posx+')').children('div').html();
	if(!valor)
		var valor = $('#'+table_id+' tbody tr:eq('+posy+') td:eq('+posx+')').children('input').val();
	return v_num(valor);
}

function set_td_value(table_id,posx,posy,valor)
{
	// PONE EL VALOR RECIBIDO EN EL DIV DEL TD POR INDICE
	$('#'+table_id+' tbody tr:eq('+posy+') td:eq('+posx+')').children('div').html(valor);
}

function promedio(clase)
{
	// SACA PROMEDIO DE LOS DIVS QUE TENGAN ESA CLASE
	var count=0;
	var res=0;
	$('.'+clase).each(function(){
		res+=v_num($(this).html());
		count++;
	});
	res=(res/count).toFixed(2);
	return res;
}

function sumar(clase,input)
{
	// SACA SUMATORIA DE LOS DIVS QUE TENGAN ESA CLASE
	var res=0;
	$('.'+clase).each(function(){
		if(!input)
			var aux=v_num($(this).html());
		else
			var aux=v_num($(this).val());
		if(aux!='' && aux!=undefined)
			res+=aux;
	});
	return eval(res).toFixed(2);
}

function limite_factor(valor, min, max)
{
	// DEVUELVE EL FACTOR CON LOS LIMITES CORRESPONDIENTES APLICADOS
	lmax=FACTOR_MAX;
	if(max)
		lmax=max;
	lmin=FACTOR_MIN;
	if(min)
		lmin=min;
		
	var res=valor;
	if(valor>lmax)
		res=lmax;
	if(valor<lmin)
		res=lmin;
	
	return res.toFixed(2);
}

function dispersion_array(valores)
{
	// CALCULO DE LA DISPERSION DE LOS VALORES RECIBIDOS EN EL ARREGLO	
	var count = 0;
	var sum = 0;
	$.each(valores,function(k,v){
		var dato = v;
		sum += dato;
		count++;	
	});
	
	if(count<2)
		return 0;
	// PROMEDIO
	var promedio = (sum/count).toFixed(2);
	
	// DESVIACION ESTANDAR
	var parcial = 0;
	for(var i=0; i < count ; i++)
	{
		parcial += Math.pow(valores[i]-promedio,2);
	}	
	var desviacion = Math.sqrt(parcial/(count-1));
	var dispersion = ((desviacion/promedio)*100).toFixed(2);
	
	return dispersion;
}

function dispersion(clase)
{
	// CALCULO DE LA DISPERSION DE LOS DIVS QUE TENGAN LA CLASE RECIBIDA
	var valores = new Array();
	var count = 0;
	var sum = 0;
	$('.'+clase).each(function(){
		var dato = v_num($(this).html());
		valores[count] = dato;
		sum += dato;
		count++;
	});
	if(count==0)
		return 0;
	// PROMEDIO
	var promedio = (sum/count).toFixed(2);
	
	// DESVIACION ESTANDAR
	var parcial = 0;
	for(var i=0; i < count ; i++)
	{
		parcial += Math.pow(valores[i]-promedio,2);
	}	
	var desviacion = Math.sqrt(parcial/(count-1));
	var dispersion = ((desviacion/promedio)*100).toFixed(2);
	
	return dispersion;
}

function round(rnum, rlength) 
{ // Arguments: number to round, number of decimal places
	var newnumber = Math.round(rnum*Math.pow(10,rlength))/Math.pow(10,rlength);
	return parseFloat(newnumber);
}

function fed(edad, vida_util)
{
	// CALCULA EL FACTOR DE EDAD
	var factor_edad='';
	if(vida_util>0 && !isNaN(edad))
	{
		factor_edad=((0.1*vida_util)+(0.9*(vida_util-edad)))/vida_util;
		factor_edad=limite_factor(factor_edad);
	}
	return factor_edad;
}

function maximo(clase)
{
	// DEVUELVE EL VALOR MAXIMO DE LOS DIVS CON LA CLASE RECIBIDA
	var max=0;
	$('.'+clase).each(function(){
		var valor = v_num($(this).html());
		if(valor > max)
			max = valor;
	});
	return max;
}

function minimo(clase)
{
	// DEVUELVE EL VALOR MINIMO DE LOS DIVS CON LA CLASE RECIBIDA
	var min=9999999999999999999999999999;
	$('.'+clase).each(function(){
		var valor = v_num($(this).html());
		if(valor < min)
			min = valor;
	});
	return min;
}

// FIN MANIPULACION DE NUMEROS CON JS

function set_fecha(campo,min_date,max_date)
{
	$(campo).datepicker({
		dateFormat:'dd/mm/yy',
		altField: $(campo).prev('input'),
		altFormat: 'yy-mm-dd',
		yearRange: '1900:c+5',
		changeMonth: true,
		changeYear: true,
		showAnim: 'slideDown',
		minDate: min_date,
		maxDate: max_date
	});	
	$(campo).change(function(){
		if($(campo).val()=='')
			$(campo).prev('input').val('');
	});
	$(campo).removeClass('fecha');
}


function convertir_campos(min,max)
{
	// CAMPOS DE FECHA
	var min_date=min?min:'';
	var max_date=max?max:'';
	$('.fecha').each(function(){
		set_fecha(this,min_date,max_date);
	});	
}
/*
function set_cfechas()
{
	$('.fechas').each(function(){
		$(this).datepicker({
			dateFormat:'dd/mm/yy',
			altField: $(this).prev('input'),
			altFormat: 'yy-mm-dd',
			yearRange: '1900:c+5',
			changeMonth: true,
			changeYear: true,
			showAnim: 'slideDown'
		});	
		$(this).change(function(){
			if($(this).val()=='')
				$(this).prev('input').val('');
		});
		$(this).removeClass('fechas');
	});

}*/

function autocompletar(campo,catalogo){
	$('#'+campo).autocomplete(
		BASE_URL+'avaluos/get_autocompletar',
		{
		extraParams: {
			name: catalogo
		},
		minChars: 0,
		cacheLength: 1,
		max: 20
		//width: ancho
	});	
}	

function habilitar_si_accion(campo,valor,campo_aux)
{	
	if($('#'+campo).val()==valor)
	{
		$('#'+campo_aux).removeAttr('readonly');
		$('#'+campo_aux).removeClass('disabled');
	}	
	else
	{	
		$('#'+campo_aux).attr('readonly','readonly');
		$('#'+campo_aux).addClass('disabled');
		// $('#'+campo_aux).val('');
	}

}

function habilitar_si(campo,valor,campo_aux)
{
	// FUNCION PARA DESHABILITAR CAMPO QUE DEPENDE DE OTRO
	//habilitar_si_accion(campo,valor,campo_aux);

	$('#'+campo).change(function(){
		var tag = $(this).prop("tagName");
		var tipo = $(this).attr('type');
		var campo_valor = false;

		if((tag=='SELECT' && tipo==undefined) || (tag=='INPUT' && tipo=='text'))
			campo_valor = $('#'+campo).val();
		else if(tag=='INPUT' && tipo=='checkbox')
			campo_valor = $('#'+campo).is(':checked');

		//habilitar_si_accion(campo,valor,campo_aux);

		if(campo_valor==valor)
		{
			$('#'+campo_aux).removeAttr('readonly');
			$('#'+campo_aux).removeClass('disabled');
		}
		else
		{	
			$('#'+campo_aux).attr('readonly','readonly');
			$('#'+campo_aux).addClass('disabled');
			$('#'+campo_aux).val('');
			// $('#'+campo_aux).val('');
		}	
	});
}

function set_fancy()
{
	$('.bc_fancybox').fancybox({
		autoSize : false,
		autoHeight : true,
		width       : '80%',
		openEffect	: 'none',
		closeEffect	: 'none'
	});

	$('.bc_fancybox').each(function(){
		$(this).attr('data-fancybox-type','ajax');
	});
}

$(function(){

	//LAYOUT
	function resize()
	{
		//ANCHO
		var ancho_ventana=$(window).width();
		var ancho_menu=$('#left').width();
		var ancho=ancho_ventana-(ancho_menu)-10;
		$('#right').width(ancho);

		//ALTO
		var alto_header=$('#header').height();
		var alto_footer=$('#footer').height();
		var alto_ventana=$(window).height();
		var alto=alto_ventana-(alto_header+alto_footer+2);
		$('#left').height(alto);
		$('#right').height(alto);

	}
	$(window).bind('resize',resize);
	resize();

	/*
	 * OCULTAR MENU PRINCIPAL
	 */
	function toggle_menu()
	{
		if($("#left").width()>5)
		{
			$("#left").width(0);
			$("#right").css('marginLeft',5);
			resize();
			$.ajax({url: BASE_URL+'main/mostrar_menu/0'});
		}
		else
		{
			$("#left").width(200);
			$("#right").css('marginLeft',200);
			$.ajax({url: BASE_URL+'main/mostrar_menu/1'});
			resize();
		}
	}
	$('#menu_ocultar').click(function(e){
		e.preventDefault();
		toggle_menu();
		return false;
	});
	/*
	 *  FIN OCULTAR MENU PRINCIPAL
	 */

	// CLEAR FLASH
	$('.msg_close').on('click',function(){
		$(this).parent('p').remove();
		$.fn.colorbox.resize();
	});

	$('.msg_close_div').on('click',function(){
		$(this).parent('div').remove();
		$.fn.colorbox.resize();
	});
	// FIN CLEAR FLASH

	//MENU PRINCIPAL
	$('.menu_modulo').click(function(){
		$(this).next('ul').slideToggle('medium');
	});
	//FIN MENU PRINCIPAL

	//LIMPIAR FORM
	$.fn.clearForm = function() {
		return this.each(function() {
			var type = this.type, tag = this.tagName.toLowerCase();
			if (tag == 'form')
				return $(':input',this).clearForm();
			if (type == 'text' || type == 'password' || tag == 'textarea' || type == 'hidden')
				this.value = '';
			else if (type == 'checkbox' || type == 'radio')
				this.checked = false;
			else if (tag == 'select')
				this.selectedIndex = -1;
		});
	};
	$('.bc_clear').on('click',function(e){
		e.preventDefault();
		var form=$(this).parents('form:first');
		form.clearForm();
	});
	// FIN LIMPIAR FORM

	// SESION CON AJAX
	function sesion_ajax()
	{
		if(THIS_URL!='/main/login')
			$.ajax({
				global: false,
				url: BASE_URL+'main/sesion_ajax',
				dataType: 'json',
				data: null,
				success: function(data,text){
					if(data.logged!=1)
					{
						window.location.replace(BASE_URL+'main/login');
					}
				},
				'beforeSend': false
			});
	}

	$.ajaxSetup({
		'beforeSend': sesion_ajax
	});

	function factor_rojo(el, value)
	{
		if(value!='')
		{
			value=v_num(value);
			if(value>1.3 || value<0.6)
			{
				$(el).addClass('bgc_red');
				$(el).attr('title','El valor de este campo debe estar entre 0.6 y 1.3');
			}
			else
			{
				$(el).removeClass('bgc_red');
				$(el).removeAttr('title');
			}
		}
	}

	$('div.factor_rojo').change(function(){
		if($(this))
		{
			var valor = $(this).html();
			factor_rojo(this,valor);
		}
	});

	$('input.factor_rojo,select.factor_rojo').change(function(){
		if($(this))
		{
			var valor = $(this).val();
			factor_rojo(this,valor);
		}
	});

	$('div.factor_rojo').each(function(){
		if($(this))
		{
			var valor = $(this).html();
			factor_rojo(this,valor);
		}
	});

	$('input.factor_rojo,select.factor_rojo').each(function(){
		if($(this))
		{
			var valor = $(this).val();
			factor_rojo(this,valor);
		}
	});

	//Fancybox imagenes
	$('.imagen_fancybox').fancybox({
		autoSize : false,
		autoHeight : true,
		width       : '80%',
		openEffect	: 'none',
		closeEffect	: 'none'
	});

	$('.container').on('click','.bc_fancybox', function(e){
		e.preventDefault();
		set_fancy();
	});

	$('.imagen_fancybox').each(function(){
		$(this).attr('data-fancybox-type','image');
	});

	$('img').on('error', function(e) {
		var img = SITE_URL + 'thumbs/timthumb.php?src=img/layout/imagen_no_disponible.jpg&s=150&t='+Math.floor(new Date().getTime() / 1000);
		$(this).attr('src', img);
		if($(this).parent().attr('href')!='')
			$(this).parent().attr('href',img);
	});

	var categorias=0;
	var productos=0;
	var accesorios=0;
	var consumibles=0;
	var productosR=0;
	var accesoriosR=0;

	/* Agregar categorias */
	$('.agregarCategoria').click(function(){
		var row = $('#categorias_add div:first').clone(true);
		$('#categorias_add').append(row);
		row.show();
		$('#categorias_add .row:last div select').select2();

		categorias+=$("#categorias_add .row").height()+1;
		console.log(categorias);
		max=(categorias>productos)?categorias:productos;
		$("#categorias_add").css("height",max);
		$("#productos_add").css("height",max);

		return false;
	});
	/* Fin agregar categorias */

	/* Eliminar categorias */
	$('.eliminarCategoria').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
			categorias-=$("#categorias_add .row").height();
			max=(categorias>productos)?categorias:productos;
			$("#categorias_add").css("height",max);
			$("#productos_add").css("height",max);
		}
		return false;
	});
	/* Fin eliminar categorias */

	/* Agregar productos */
	$('.agregarProducto').click(function(){
		var row = $('#productos_add div:first').clone(true);
		$('#productos_add').append(row);
		row.show();
		$('#productos_add .row:last div select').select2();

		productos+=$("#productos_add .row").height()+1;
		max=(categorias>productos)?categorias:productos;
		$("#categorias_add").css("height",max);
		$("#productos_add").css("height",max);

		return false;
	});
	/* Fin agregar productos */

	/* Eliminar productos */
	$('.eliminarProducto').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
			productos-=$("#productos_add .row").height();
			max=(categorias>productos)?categorias:productos;
			$("#categorias_add").css("height",max);
			$("#productos_add").css("height",max);
		}
		return false;
	});
	/* Fin eliminar productos */

	/* Agregar accesorio */
	$('.agregarAccesorio').click(function(){
		var row = $('#accesorios_add div:first').clone(true);
		$('#accesorios_add').append(row);
		row.show();
		$('#accesorios_add .row:last div select').select2();

		accesorios+=$("#accesorios_add .row").height()+1;
		max=(accesorios>consumibles)?accesorios:consumibles;
		$("#accesorios_add").css("height",max);
		$("#consumibles_add").css("height",max);

		return false;
	});
	/* Fin agregar accesorio */

	/* Eliminar accesorio */
	$('.eliminarAccesorio').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
			accesorios-=$("#accesorios_add .row").height();
			max=(accesorios>consumibles)?accesorios:consumibles;
			$("#accesorios_add").css("height",max);
			$("#consumibles_add").css("height",max);
		}
		return false;
	});
	/* Fin eliminar accesorio */

	/* Agregar consumible */
	$('.agregarConsumible').click(function(){
		var row = $('#consumibles_add div:first').clone(true);
		$('#consumibles_add').append(row);
		row.show();
		$('#consumibles_add .row:last div select').select2();

		consumibles+=$("#consumibles_add .row").height()+1;
		max=(accesorios>consumibles)?accesorios:consumibles;
		$("#accesorios_add").css("height",max);
		$("#consumibles_add").css("height",max);

		return false;
	});
	/* Fin agregar consumible */

	/* Eliminar consumible */
	$('.eliminarConsumible').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
			consumibles-=$("#consumibles_add .row").height();
			max=(accesorios>consumibles)?accesorios:consumibles;
			$("#accesorios_add").css("height",max);
			$("#consumibles_add").css("height",max);
		}
		return false;
	});
	/* Fin eliminar consumible */

	/* Agregar regalos productos */
	$('.agregarRegaloProducto').click(function(){
		var row = $('#regalos_productos_add div:first').clone(true);
		$('#regalos_productos_add').append(row);
		row.show();
		$('#regalos_productos_add .row:last div select').select2();

		productosR+=$("#regalos_productos_add .row").height()+1;
		max=(productosR>accesoriosR)?productosR:accesoriosR;
		$("#regalos_productos_add").css("height",max);
		$("#regalos_accesorios_add").css("height",max);

		return false;
	});
	/* Fin agregar regalos productos */

	/* Eliminar regalos productos */
	$('.eliminarRegalosProducto').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
			productosR-=$("#regalos_productos_add .row").height();
			max=(productosR>accesoriosR)?productosR:accesoriosR;
			$("#regalos_productos_add").css("height",max);
			$("#regalos_accesorios_add").css("height",max);
		}
		return false;
	});
	/* Fin eliminar regalos productos */

	/* Agregar regalos accesorios */
	$('.agregarRegaloAccesorio').click(function(){
		var row = $('#regalos_accesorios_add div:first').clone(true);
		$('#regalos_accesorios_add').append(row);
		row.show();
		$('#regalos_accesorios_add .row:last div select').select2();

		accesoriosR+=$("#regalos_accesorios_add .row").height()+1;
		max=(productosR>accesoriosR)?productosR:accesoriosR;
		$("#regalos_productos_add").css("height",max);
		$("#regalos_accesorios_add").css("height",max);

		return false;
	});
	/* Fin agregar regalos accesorios */

	/* Eliminar accesorio */
	$('.eliminarRegaloAccesorio').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
			accesoriosR-=$("#regalos_accesorios_add .row").height();
			max=(productosR>accesoriosR)?productosR:accesoriosR;
			$("#regalos_productos_add").css("height",max);
			$("#regalos_accesorios_add").css("height",max);
		}
		return false;
	});
	/* Fin eliminar regalos accesorios */

	/* Agregar consumible */
	$('.agregarRegaloConsumible').click(function(){
		var row = $('#regalos_consumibles_add div:first').clone(true);
		$('#regalos_consumibles_add').append(row);
		row.show();
		$('#regalos_consumibles_add .row:last div select').select2();
		return false;
	});
	/* Fin agregar consumible */

	/* Eliminar consumible */
	$('.eliminarRegaloConsumible').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
		}
		return false;
	});
	/* Fin eliminar consumible */

	/* Agregar alianza */
	$('.agregarAlianza').click(function(){
		var row = $('#regalos_alianzas_add div:first').clone(true);
		$('#regalos_alianzas_add').append(row);
		row.show();
		$('#regalos_alianzas_add .row:last div select').select2();
		return false;
	});
	/* Fin agregar consumible */

	/* Eliminar alianza */
	$('.eliminarAlianza').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
		}
		return false;
	});
	/* Fin eliminar consumible */


	// Actualiza la numeración de las etiquetas en agregar cupón
	function actualizarNumeracion(){
		var i=0;
		$('.numeracion').each(function() {
			$(this).text(i);
			i++;
		});
	}

	/* Inicio agregar cupón */
	$('.agregarCupon').click(function(){
		var row = $('#elementos_agregar div:first').clone(true);
		$('#elementos_agregar').append(row);
		row.show();

		actualizarNumeracion();

		return false;
	});
	/* Fin agregar cupón */

	/* Eliminar alianza */
	$('.eliminarCupon').click(function(){
		if(confirm('¿Está usted seguro que desea borrar esta fila?'))
		{
			$(this).closest('.row').remove();
		}

		actualizarNumeracion();

		return false;
	});
	/* Fin eliminar consumible */

	$(document).on('ready',function()
	{
		actualizarNumeracion();
		$(".selectsCategoria").select2();
		$(".selectsProducto").select2();
		$(".selectsAccesorio").select2();
		$(".selectsConsumible").select2();
		$(".selectsRegaloProductos").select2();
		$(".selectsRegaloAccesorios").select2();
		$(".selectsRegaloConsumibles").select2();
		$(".selectsRegaloAlianzas").select2();

		var existe = $(this).find('.categorias_video');

		if(existe.length >= 1)
			categoria_inicializar();
	});

	$('.bc_colorbox').click(function() {
		url = this.href; // this is the url of the element event is triggered from
		$.colorbox({
			href: url,
			maxHeight:function(){return $(window).height();},
			maxWidth:function(){return $(window).width();},
			color: '#FFFFFF'
		});
		return false;
	});

    ////PARA LOS VIDEOS
    $(".bc_colorbox_player").colorbox({
        iframe:true,
        innerWidth:450,
        innerHeight:300,
        onComplete:
            function()
            {
                $('.cboxIframe').attr({ webkitAllowFullScreen : true, mozallowfullscreen : true, allowFullScreen : true });
                $('#colorbox').delay(2000).queue(function() { $(this).removeClass('animated flipInY').dequeue(); });
            }
    }).click(function(){
            $('#colorbox').addClass('animated flipInY');
        });;
});

$(window).resize(function() {
	var existe = $(document).find('.categorias_video');
	if(existe.length >= 1)
		categoria_inicializar();
});

function ofrecio_evento(callback)
{
	alertify.set({ labels: { ok: 'Sí', cancel: 'No' } });
	alertify.confirm('¿Ofreciste algún evento Miele?', function(a) {
		$('#ofrecio_evento').val(a?1:2);
		callback(null, true);
	});
}

function categoria_inicializar()
{
	$(".categorias_video").each(function(){
		// Se inicializa variable con el elemento de la clase categorias_video
		var categoria = $(this);

		// Se obtiene la posición del video
		var video_posicion = categoria.position();

		var boton_play = categoria.parent().find(".categorias_play_button");
		var boton_play_hover = categoria.parent().find(".categorias_play_button_hover");

		// Ancho y Alto de los botones
		var ancho = 57;
		var alto = 57;

		// Se modifican las posiciones del botón de play
		boton_play.css({
			'left': video_posicion.left,
			'top': video_posicion.top,
			'width': ancho,
			'height': alto,
			'position': 'absolute',
			'z-index': -1
		});

		// Se modifican las posiciones del botón de play hover
		boton_play_hover.css({
			'left': video_posicion.left,
			'top': video_posicion.top,
			'width': ancho,
			'height': alto,
			'position': 'absolute',
			'z-index': -2
		});

		// Se obtiene la mitad del alto y ancho del botón de play para colocarlo al centro
		var mitad_ancho = boton_play.width()/2;
		var mitad_alto = boton_play.height()/2;

		// Se obtienen las posiciones donde se colocará la imagen play
		var posicion_izquierda = ((categoria.width()/2)+video_posicion.left)-mitad_ancho;
		var posicion_alto = ((categoria.height()/2)+video_posicion.top)-mitad_alto;

		// Se cambia el estilo de la imagen play
		boton_play.css({
			'position': 'absolute',
			'left': posicion_izquierda,
			'top': posicion_alto,
			'z-index': 1
		});

		boton_play.mouseover(function(){
			$(this).css({
				'position': 'absolute',
				'left': posicion_izquierda,
				'top': posicion_alto,
				'z-index': -2
			});

			$(this).parent().find(boton_play_hover).css({
				'position': 'absolute',
				'left': posicion_izquierda,
				'top': posicion_alto,
				'z-index': 1
			});
		});

		//Cuando se posiciona el cursor fuera la imagen play hover
		boton_play_hover.mouseout(function(){
			$(this).parent().parent().find(boton_play).css({
				'position': 'absolute',
				'left': posicion_izquierda,
				'top': posicion_alto,
				'z-index': 1
			});

			$(this).css({
				'position': 'absolute',
				'left': posicion_izquierda,
				'top': posicion_alto,
				'z-index': -2
			});
		});
	});
};