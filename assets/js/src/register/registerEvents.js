document.addEventListener('DOMContentLoaded', function () {

	document.addEventListener('click', function (el) {
		if (el.target.classList.contains('remover')) {
			let targetTable = el.target.getAttribute('target-table');
			let idValue = el.target.getAttribute('row-id');
			
			var name = el.target.closest('tr').querySelector('td').innerHTML;
			var target = `/register/remove/${targetTable}`;
			let row = el.target.closest('tr');
			
			simpleDialog(
				`Deseja remover item o ${name.toUpperCase()} <br><small class="text-danger">Essa ação não poderá ser desfeita.</small>`,
				function () {
					res = simpleRequest(target, 'delete', idValue, function (response) {
						var type = response.headers['type-message'];
						var info = response.data;
						var messageAlert = document.querySelector('#alert-message');
						messageAlert.innerHTML = messageSend(type, info);
						setTimeout(function () {
							$(document).find('#close-button').trigger('click');
						}, 2000);
						if (type !== 'danger') {
							row.remove();
						}
					});
				}
			);

			$.fancybox.close();
		}
	});

});
