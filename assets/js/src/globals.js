module.exports = function (formAdd) {
	alert()
	var requiredFormInputs = document.querySelectorAll('#'+formAdd+' [required]');
	for (var input of requiredFormInputs) {
		console.log(input);
	}
}

document.addEventListener('DOMContentLoaded', function () {
	$('.flash').each(function (index) {
		$(this).fadeOut(3000);
	});

	document.querySelectorAll('.sender').forEach( function (el) {
		el.addEventListener('click', function (el) {
			var url = el.target.getAttribute('data-sender');
			var formId = '#'+el.target.closest('form').id;
			var formData = new FormData(formId);
			sendSimpleRequest(el.target.getAttribute('data-sender'), formData);
			//el.preventDefault();
			return true;
		});  
	});

	if(document.querySelector('.numbers-only')) {
		document.querySelector('.numbers-only').addEventListener('keypress', function (evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				evt.preventDefault();
				return false;
			}
			return true;
		});
	}

	if (document.querySelectorAll('.numbers-float-only')) {
		var inputs = document.querySelectorAll('.numbers-float-only');

		for (var input of inputs) {
			input.addEventListener('keypress', function (evt) {
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				
				if (charCode == 44) {
					return true;
				}

				if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					evt.preventDefault();
					return false;
				}

				return true;
			});
		}	
	}

	if (document.addEventListener('click', function (el) {
		if (el.target.id == 'checkBox') {
			if (document.querySelector('#checkValue').value == 0) {
				document.querySelector('#checkValue').value = 1;
			} else {
				document.querySelector('#checkValue').value = 0;
			}
		}
	}));
});