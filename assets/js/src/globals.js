document.addEventListener('DOMContentLoaded', function () {

	/* let collapseDefault = document.querySelector('#hideMenusNames');
	collapseDefault.dispatchEvent(new Event('click', {
		'view': window,
		'bubbles': true,
		'cancelable': true
	})); */
	
	//add single elements
	document.querySelectorAll('.sender').forEach( function (el) {
		el.addEventListener('click', function (el) {
			el.preventDefault();
			var url = el.target.getAttribute('data-sender');
			var formId = '#'+el.target.closest('form').id;
			var form = document.querySelector(formId);
			var formData = new FormData(form);
			var method = el.target.getAttribute('method');
			var token = el.target.getAttribute('token');
			sendSimpleRequest(url, formData, method, token, function (message, type) {
				var messageAlert = document.querySelector('#alert-message');
				messageAlert.innerHTML = messageSend(type, message);
				if (type == 'success') {
					form.reset();
					var name = el.target.closest('form').getAttribute('target');
					$('#'+name+'-reload').trigger('click');
					$.fancybox.close();
					location.reload();
				}
				location.reload();
				setTimeout(function () {
					$(document).find('#close-button').trigger('click');
					$.fancybox.close();
				}, 3000);
				return true;
			});
			return false;
		});
	});

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
