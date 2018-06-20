var prodForm = document.querySelector('#prod-form');

document.addEventListener('DOMContentLoaded', function () {
	var xForm = Object.assign({}, prodForm);

	document.addEventListener('click', function (el) {

		if (el.target.id == 'product-tab') {
			var prod = document.querySelector('#prod');
			prod.after(xForm);
			alert('Apertou no tab');
		}

		if (el.target.id == 'cheque-tab') {
			var prod = document.querySelector('#prod-form');
			prod.remove();
			console.log(prod);
		}
	});

});