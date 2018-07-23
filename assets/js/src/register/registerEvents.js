document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.remover').forEach(function (el) {
		el.addEventListener('click', function (elm) {
			var row = elm.closest('tr');
			var rowValue = row.querySelectorAll('td')[0];
			var target = '/register/remove/'+row.closest('div').getAttribute('[data-name]');
			simpleRequest(target, 'post', rowValue, function (response) {
				console.log(response);
			});
			row.remove();
		});
	});
});