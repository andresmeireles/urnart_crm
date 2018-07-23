document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.remover').forEach(function (el) {
		el.addEventListener('click', function (elm) {
			var row = elm.target.closest('tr');
			var rowValue = row.querySelectorAll('td')[0].innerHTML;
			rowValue = rowValue.replace(/\s/g, '');
			var target = '/register/remove/'+row.closest('div').getAttribute('data-name');
			simpleRequest(target, 'post', rowValue, function (response) {
				console.log(response);
			});
			row.remove();
		});
	});
});