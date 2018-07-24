document.addEventListener('DOMContentLoaded', function () {
	
	/**
	 * TO DO CONFIRMAÇÂO DE REMOOÇÃO DO ELEMENTO
	 */
	document.addEventListener('click', function (el) {
		if (el.target.classList.contains('remover')) {
			var row = el.target.closest('tr');
			var rowValue = row.querySelectorAll('td')[0].innerHTML;
			rowValue = rowValue.replace(/\s/g, '');
			var target = '/register/remove/'+row.closest('div').getAttribute('data-name');
			
			alertify.confirm('Deseja remover elemento?', 
			function(){
				alertify.success('okMsg');
			  },
			  function(){
				alertify.error('cancelMsg');
			  });
			/** 
			simpleRequest(target, 'post', rowValue, function (response) {
				var type = response.headers['type-message'];
				var info = response.data;
				var messageAlert = document.querySelector('#alert-message');
				messageAlert.innerHTML = messageSend(type, info);
				setTimeout(function () {
					$(document).find('#close-button').trigger('click');
				}, 2000);
			});
			row.remove();*/
		}
	});

});