document.addEventListener('DOMContentLoaded', function () {	
	var prod = document.querySelector('#prod');	
	var pForm = prod.innerHTML;

	var chq = document.querySelector('#chq');
	var cForm = chq.innerHTML;

	var chqForm = document.querySelector('#chq-form');
	chqForm.remove();

	document.addEventListener('click', function (el) {

		if (el.target.id == 'product-tab') {
			var chqF = document.querySelector('#chq-form');
			chqF.remove();

			var prodF = document.querySelector('#prod');
			prodF.innerHTML = pForm;
		}

		if (el.target.id == 'chq-tab') {
			var chq = document.querySelector('#chq');
			chq.innerHTML = cForm;

			var prodF = document.querySelector('#prod-form');
			prodF.remove();
		}
	});

});