document.addEventListener('DOMContentLoaded', function () {
	$('.flash').each(function (index) {
		$(this).fadeOut(3000);
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

	if (document.querySelector('.numbers-float-only')) {
		document.querySelector('.numbers-float-only').addEventListener('keypress', function (evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				evt.preventDefault();
				return false;
			}
			return true;
		});	
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