var Direccion={
		
	set:function(pre)
	{
		if(!pre)
			pre='';
		
		$('#'+pre+'estado').change(function(){
				var municipio_actual = '';
				if($('#'+pre+'municipio').val()!='')
					municipio_actual = $('#'+pre+'municipio').val();
				Direccion.get_municipios(pre, municipio_actual);
		});

		$('#'+pre+'municipio').change(function(){
			$('#'+pre+'asentamiento').attr('value','');
		});
		
		$('#'+pre+'asentamiento').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: BASE_URL+'sepomex/get_colonias',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        estado: function() { return $('#'+pre+'estado').val(); },
                        municipio: function() { return $('#'+pre+'municipio').val(); },
                        codigo_postal: function() { return $('#'+pre+'codigo_postal').val(); }
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minChars: 0,
            width: 160,
            cacheLength:1	
		});
		
		$('#'+pre+'cp_search').click(function(e){
			e.preventDefault();
			$.post(
					BASE_URL+'sepomex/get_codigo_postal',
					{
						estado: function() { return $('#'+pre+'estado').val(); },
						municipio: function() { return $('#'+pre+'municipio').val(); },
						asentamiento: function() { return $('#'+pre+'asentamiento').val(); }
					},
					function(data){
						$('#'+pre+'codigo_postal').attr('value',data.cp);
				},'json');
		});

		
		$('#'+pre+'dir_search').click(function(e){
			e.preventDefault();
			$('#'+pre+'estado').attr('value','');
			$('#'+pre+'municipio').empty();
			$('#'+pre+'municipio').append('<option value="" selected="selected">Cargando...</option>');
			$('#'+pre+'asentamiento').attr('value','');
			$.post(
					BASE_URL+'sepomex/get_direccion',
					{codigo_postal: function() { return $('#'+pre+'codigo_postal').val(); }},
					function(data){
						$('#'+pre+'estado').val(data.estado);
						if(pre=='entrega_')
							$('#'+pre+'estado').trigger('change');
						if(pre=='instalacion_')
							$('#'+pre+'estado').trigger('change');
						Direccion.get_municipios(pre,data.municipio);
						if(data.asentamiento)
							$('#'+pre+'asentamiento').val(data.asentamiento);
				},'json');
		});
		
						
	},
	get_municipios:function(pre,actual)
	{
		$('#'+pre+'asentamiento').attr('value','');
		$('#'+pre+'municipio').empty();
		$('#'+pre+'municipio').append('<option value="" selected="selected">Cargando...</option>');
		$.post(
			BASE_URL+'sepomex/get_municipios',
			{estado: function() { return $('#'+pre+'estado').val(); }},
			function(data){
				$('#'+pre+'municipio').empty();
				$('#'+pre+'municipio').append('<option value="" selected="selected"> </option>');
				$.each(data, function(i,item){
					if(item==actual)
						$('#'+pre+'municipio').append('<option value="'+item+'" selected="selected">'+item+'</option>');
					else
					$('#'+pre+'municipio').append('<option value="'+item+'">'+item+'</option>');
					
				});
				return true;
		},'json');
	}
};