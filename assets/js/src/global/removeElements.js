document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.remover').forEach(function (el) {
		el.addEventListener('click', function (elm) {
			console.log(elm.target);
		});
	});
});