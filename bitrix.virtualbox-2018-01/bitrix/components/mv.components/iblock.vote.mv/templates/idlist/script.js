(function(window){

	if (window.JCCatalogVoteRating)
		return;

	window.JCCatalogVoteRating = function(arParams)
	{				
		this.check = null;

		if (typeof arParams === 'object')
		{
			this.save = false;					// рейтинг не сохранен
			this.rating = -1;					// значение реийтинга основного товара
			this.params = arParams;				// обект с вкусами
			this.voteList = new Array();		// масив голосования за 'вкуси'
			this.newDivIdList = new Array();	// переменная для создания блока с 'інпутами' для голосования за 'вкус'
			this.comment = '';					// коментарий к основному товару
			this.init();						// инициализация окна голосования
		}
		if(window.jQuery) {	console.log('window.JCCatalogVoteRating:');	console.log(this);}
	};

	window.JCCatalogVoteRating.prototype = {
		
		init: function() 
		{		
			var self = this;			

			// вибираем рейтинг для основного товара
			$("#" + this.params.ID_RATING).change(function(e){				
				self.setRating(this);				
				self.enadleDisableButtonSave();
			});
			// сохраняем рейтинг, отправляем дание на сервер
			$("#" + this.params.ID_SAVE_BUTTON).click(function(){
				self.saveRating();
			});			
			// закриваем модальное окно, и обнуляем информацию введеную пользователем
			$('#' + this.params.VKUS_DATA.DATA.modalID).on('hide.bs.modal', function () {
				self.closeModalWindow();
			});
			// устанавливаем значение - 'чекбокса'
			$('#' + this.params.ID_CONTAINER + ' input:checkbox').click(function (e) {
				self.check = e.target.checked;
				self.enadleDisableButtonSave();
			});
			// вибираем новий вкус для голосования
			$('#' + this.params.ID_VKUS).change(function (e) {
				self.addNewVote(e.target);
				self.enadleDisableButtonSave();
			});
		},

		saveRating: function(){
			console.log('---saveRating:');
			//console.log(this);			
			var i, 
				n = 0,
				arr = new Array();

			if (!this.save)
			{
				// коментарий
				this.comment = $('#'+ this.params.ID_CONTAINER +' textarea')[0].value;

				for (i in this.voteList){					
					if( this.voteList[i].RATING >= 0 ) {
						arr[n] = {
							'ELEMENT_ID': this.voteList[i].ELEMENT_ID,
							'IBLOCK_ID': this.voteList[i].IBLOCK_ID,
							'RATING' : this.voteList[i].RATING,
						}
						n++;
					}
				}
				// обект для отправки на сервер
				postData = {
					'AJAX_CALL': this.params.AJAX_PARAMS.AJAX_CALL,
					'PAGE_PARAMS': {
						'ELEMENT_ID': this.params.AJAX_PARAMS.PAGE_PARAMS.ELEMENT_ID 
						},
					'SESSION_PARAMS': this.params.AJAX_PARAMS.SESSION_PARAMS,
					'rating': this.rating,
					'sessid':  this.params.SESSION_ID,
					'vote': "Y",
					'vote_id': this.params.ELEMENT_ID,
					'vote_list': arr,
						/* -- Example: --
						'vote_list': {
							'1793': {'IBLOCK_ID':9, 'ELEMENT_ID':1793, 'RATING':1},
							'1792': {'IBLOCK_ID':9, 'ELEMENT_ID':1792, 'RATING':1}
						}				
						/**/
					'comment': this.comment
				};				
				
				console.log('postData:');
				console.log(postData);				
								
				if ( this.rating && this.check )
				$.post(this.params.URL, postData)
				  	.done(function(d) {						
				    	console.log(d);
				    	this.save = true;
				  	})
				  	.fail(function(d) {
				  		console.log('Error:');
				    	console.log(d);
				  	});			
				/**/
			}
		},

		closeModalWindow: function(){			
			console.log('---closeModalWindow:');
			//console.log(this);
			//console.log(e);
			var i;

			// set base settings
			this.check = null;
			this.rating = -1;			
			// очищаем масив голосования
			this.voteList = new Array(); 
			// сбрачиваем select на первое значение
			$("#" + this.params.ID_RATING).prop("selectedIndex", 0); 	// variant 1
			$("#" + this.params.ID_VKUS)[0].selectedIndex = 0;			// variant 2			
			// отключаем кнопку сохранения
			this.enadleDisableButtonSave();
			// удаляем все 'диви' что в контейнере и обнуляем масив с - id для етих 'дивов'
			for (i = 0; i < this.newDivIdList.length; i++){
				$("#"+this.newDivIdList[i]).remove();
			}
			this.newDivIdList = new Array();
			// откриваем все 'оптиони'
			$('#' + this.params.ID_VKUS + ' option').each(function(e){				
				$(this).show();
			});
			// отключаем 'чекбокс'
			$('#' + this.params.ID_CONTAINER + ' input:checkbox')[0].checked = false; 			
		},

		setRating: function(e){
			console.log('---setRating:');
			//console.log(this);

			// сохраняем значение рейтинга для основного товара
			this.rating = $(e).val();			
			this.voteList[this.params.ELEMENT_ID] = { 
				'ELEMENT_ID': this.params.ELEMENT_ID,
				'IBLOCK_ID': this.params.IBLOCK_ID,
				'RATING' : this.rating,
				'selectedOptionId': 0
			}
			console.log(this.voteList);
		},

		enadleDisableButtonSave: function() {
			// console.log(this);
			// + включаем кнопку для сохранения
			if( this.check && this.rating >= 0)	
			{
				$("#"+this.params.ID_SAVE_BUTTON).removeClass("pvs-modal-footer-button-disable").addClass("pvs-modal-footer-button");
				$("#"+this.params.ID_SAVE_BUTTON).removeAttr('disabled');
			} 
			// - виключаем кнопку для сохранения рейтинга
			else 
			{
				$("#"+this.params.ID_SAVE_BUTTON).removeClass("pvs-modal-footer-button" ).addClass("pvs-modal-footer-button-disable");
				$("#"+this.params.ID_SAVE_BUTTON).attr('disabled', 'disabled');
			}
		},

		addNewVote: function(e){
			var self = this;

			var i, 
				j, 
				vkusName,
				vkusVkus,
				text = '',
				voteVkusContainer = '';

			var newDivId = null,
				newGroupName = null;


			var selectId = $(e).attr('id');	 // id - селекта для вкусов	
			var selectedOptionId = $('#'+selectId).find(":selected").attr('id'); // текущий id 'оптиона'

			$('#' + selectedOptionId).hide();	// спрятать вибраний вкус 
			$("#" + selectId).prop("selectedIndex", 0); // ставим активним первий 'оптион'

			var vkus = this.params.VKUS_DATA.VKUS;	// обект вкусов 
			//console.log(vkus);

			for (i in vkus) // проходим все вкуси что есть 
			{				
				if( selectedOptionId == vkus[i].ID ) // если id вкуса совпадает с id 'оптиона' то запоминаем вкуси
				{
					for (j = 0; j < vkus[i]['ELEMENT_LIST_ID'].length; j++) 
					{
						this.voteList[ vkus[i]['ELEMENT_LIST_ID'][j] ] = { 
									'ELEMENT_ID': vkus[i]['ELEMENT_LIST_ID'][j],
									'IBLOCK_ID': vkus[i].IBLOCK_ID,
									'RATING' : -1,
									'selectedOptionId': selectedOptionId
									}						
					}
					vkusName = vkus[i].NAME; 	// запоминеєм имя 
					vkusVkus = vkus[i].VALUE; 	// запоминаем вкус
				}
			}

			newDivId = 'div_' + selectedOptionId; // текущий 'див' 
			this.newDivIdList.push(newDivId); // добавляєм id нового 'дива' к списку виведених на екран
			newGroupName = 'group_' + selectedOptionId; // имя групи 'radio' для текущего контейнера
			

			for (i = 0; i < 10; i++) // 'инпути' для голосования для текущего вкуса
			{
				inputId = 'i_' + selectedOptionId + '_' + i;
				text = text + "<span>"+(1+i)+"<br><input data-option='"+selectedOptionId+"' type='radio' value='"+i+"' name='"+newGroupName+"'></span>"
			}

			// формируем 'див' для голосования за конкретний вкус
			voteVkusContainer = "<div id='"+newDivId+"' class='pvs-modal-vkus-container'>" +
									"<div class='pvs-modal-vkus-label'>" +
										"<span>"+vkusName+' - '+vkusVkus+"</span>"+
										"<a data-id='"+newDivId+"' data-option='"+selectedOptionId+"'>Удалить</a>" +
									"</div>" +
								"<div class='pvs-modal-vkus-check'>" +
									"<form>" +
	    								"<fieldset id='"+newGroupName+"'>" + 
	    									text + 
	    								"</fieldset></form></div></div>";

	    	// добавляем 'див' на страницу перед 'селектом'
			$("#" + selectId).before(voteVkusContainer);
			// назначаем обработчик лдя удаления 'дива' и очистки масивов
			$('#'+ newDivId + ' a').click(this.deleteVoteVkusContainer.bind(self));
			// назначаем обработчики собитий для голосования за конкретний вкус
			$('#'+ newDivId + ' input').each( function(){
				$(this).click(self.setRatingForCurentVkus.bind(self));
			});

			console.log(this.voteList);
		},

		deleteVoteVkusContainer: function(e){
			var i;
			var e = e.target;
			var opionId = $(e).attr('data-option');
			var divId = $(e).attr('data-id');
			
			// удаляем 'вкус' из масива голосования
			for (i in this.voteList){
				//console.log(this.voteList[i]);
				if (opionId == this.voteList[i].selectedOptionId) { delete this.voteList[i]; }
			}
			// удаляем 'див' из масива
			for (i = 0; i < this.newDivIdList.length; i++)
				if (divId == this.newDivIdList[i]) this.newDivIdList.splice(i, 1);						
			// удаляем 'див' из контейнера
			$("#" + divId).remove();
			// включаем скритий 'оптион'
			$('#' + opionId).show();			

			console.log(this.voteList);
		},

		setRatingForCurentVkus: function(e){
			var i;
			var e = e.target;
			var opionId = $(e).attr('data-option');			

			console.log('--->>');
			console.log(opionId);
			console.log($(e).val());

			for (i in this.voteList){			
				if (opionId == this.voteList[i].selectedOptionId) { 
					this.voteList[i].RATING = $(e).val();
				}
			}

			console.log(this.voteList);
		}

	}	

})(window);
