document.addEventListener('DOMContentLoaded', function () {
	
<<<<<<< HEAD
	/**
	 * TO DO CONFIRMAÇÂO DE REMOOÇÃO DO ELEMENTO
	 */
	document.addEventListener('click', function (el) {
		if (el.target.classList.contains('remover')) {
			var row = el.target.closest('tr');
=======
	document.addEventListener('click', function (el) {
		if (el.target.classList.contains('remover')) {
			var row = el.target.closest('tr');
			var name = row.querySelector('[name]').getAttribute('name');
			var entity = row.closest('table').getAttribute('name');
>>>>>>> origin/dev
			var rowValue = row.querySelectorAll('td')[0].innerHTML;
			rowValue = rowValue.replace(/\s/g, '');
			var target = '/register/remove/'+row.closest('div').getAttribute('data-name');
			
<<<<<<< HEAD
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

=======
			// fecha caixa de dialogo
			$.fancybox.close();
			
			defaultDialog(
				'Deseja remover item ', 
				name, 
				entity, 
				function () {
					simpleRequest(target, 'post', rowValue, function (response) {
						var type = response.headers['type-message'];
						var info = response.data;
						var messageAlert = document.querySelector('#alert-message');
						messageAlert.innerHTML = messageSend(type, info);
						setTimeout(function () {
							$(document).find('#close-button').trigger('click');
						}, 2000);
					});
					row.remove();
				}
			);
		}
		
		if (el.target.classList.contains('reload')) {
			var rowName = el.target.closest('button').id;
			var tableName = rowName.replace('reload', 'body');
			var entity = rowName.slice(0, -7);
			var url = `/register/get/${entity}`;
			
			var table = document.getElementById(tableName); 
			table.innerHTML = '';
			
			simpleRequest(url, 'post', null, function (response) {
				var data = response.data;
				var tr = '';
				for (var info of data) {
					var fields = Object.keys(info)
					
					tr += '<tr>'
					for (var field of fields) {
						if (field == 'id') {
							tr += `<td class="d-none id-number">${info[field]}</td>`
							continue
						}

						tr += `<td class="text-left" name="${info[field]}">${info[field]}</td>`
					}
					tr += `<td class="text-right"><span class="badge badge-pill badge-danger remover cursor-decoration">Remover</span></td></tr>`;
				}
				
				table.innerHTML = tr;
			});
		}
		
	});
	
>>>>>>> origin/dev
});