module.exports = function (formAdd) {
	var requiredFormInputs = document.querySelectorAll('#'+formAdd+' [required]');
	for (var input of requiredFormInputs) {
		console.log(input);
	}
}


document.addEventListener('DOMContentLoaded', function () {
	
	//add single elements
	document.querySelectorAll('.sender').forEach( function (el) {
		el.addEventListener('click', function (el) {
			el.preventDefault();
			var formId = '#'+el.target.closest('form').id;
			var form = document.querySelector(formId);
			var formData = new FormData(form);			
			sendSimpleRequest(el.target.getAttribute('data-sender'), formData, function (message, type) {
				var messageAlert = document.querySelector('#alert-message');
				messageAlert.innerHTML = messageSend(type, message);
				
				if (type == 'success') {
					form.reset();	
<<<<<<< HEAD
					var name = '#'+el.target.closest('form').getAttribute('target')+'-reloader';
					$(document).find(name).trigger('click');	
					$.fancybox.close();
=======
					var name = el.target.closest('form').getAttribute('target');
					$('#'+name+'-reload').trigger('click');
					$.fancybox.close();

>>>>>>> origin/dev
				}

				setTimeout(function () {
					$(document).find('#close-button').trigger('click');
				}, 3000);
				return true;
			});
			return false;
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