$(function(){	
	$('.bc_multiselect').each(function(){
		var elid=$(this).attr('id');
		$('#'+elid).multiSelect({
			keepOrder: true,
			selectableHeader: "<div class='select-multiple-header'>No seleccionados</div><input type='text' class='search-input' autocomplete='off' placeholder='Buscar'>",
			selectionHeader: "<div class='select-multiple-header'>Seleccionados</div><input type='text' class='search-input' autocomplete='off' placeholder='Buscar'>",
			selectableFooter: '<div class="select-multiple-footer"><a href="#" id="'+elid+'TodosOff">Ninguno</a></div>',
			selectionFooter: '<div class="select-multiple-footer"><a href="#" id="'+elid+'TodosOn">Todos</a></div>',
			afterInit: function(ms)
			{	
				var that = this,
				$selectableSearch = that.$selectableUl.prev(),
				$selectionSearch = that.$selectionUl.prev(),
				selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
				selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
				
			    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
			    .on('keydown', function(e)
			    		{
			    	if (e.which === 40)
			    	{
			    		that.$selectableUl.focus();
			    		return false;
			    	}
			    });
	
			    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
			    .on('keydown', function(e)
			    {
			    	if (e.which == 40)
			    	{
			    		that.$selectionUl.focus();
			    		return false;
			    	}
			    });
			  },
			  afterSelect: function(){
				  this.qs1.cache();
				  this.qs2.cache();
			  },
			  afterDeselect: function(){
				  this.qs1.cache();
				  this.qs2.cache();
			  }
			});
		$(this).removeClass('multiselect');
		$('#'+elid+'TodosOn').click(function(e){
			e.preventDefault();
			$('#'+elid).multiSelect('select_all');
			return false;
		});
		$('#'+elid+'TodosOff').click(function(e){
			e.preventDefault();
			$('#'+elid).multiSelect('deselect_all');
			return false;
		});
});
});