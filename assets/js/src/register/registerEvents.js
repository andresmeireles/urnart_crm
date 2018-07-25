document.addEventListener('DOMContentLoaded', function () {
	
	/**
	* TO DO - CONFIRMAÇÂO DE REMOOÇÃO DO ELEMENTO
	*/
	document.addEventListener('click', function (el) {
		if (el.target.classList.contains('remover')) {
			var row = el.target.closest('tr');
			var name = row.querySelector('[name]').getAttribute('name');
			var entity = row.closest('table').getAttribute('name');
			var rowValue = row.querySelectorAll('td')[0].innerHTML;
			rowValue = rowValue.replace(/\s/g, '');
			var target = '/register/remove/'+row.closest('div').getAttribute('data-name');
			
			$.fancybox.close();

			defaultDialog(
				'Deseja remover item ', 
				name, 
				entity, 
				function () {
					alert('ninja');
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
			console.log(table, tableName, rowName, el.target);
			table.innerHTML = '';
			
			simpleRequest(url, 'post', null, function (response) {
				var data = response.data;
				var tr = '';
				
				for (var info of data) {
					tr += `
					<tr>
					<td class="d-none id-number">
					${info.id}
					</td>
					<td class="text-left" name="${info.name}">
					${info.name}
					</td>
					<td class="text-right">
					<span class="badge badge-pill badge-danger remover cursor-decoration">Remover</span>
					</td>
					</tr>`;
				}
				
				table.innerHTML = tr;
			});
		}
		
	});
	
});