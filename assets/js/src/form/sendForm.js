document.addEventListener('DOMContentLoaded', function () {

	document.addEventListener('click', function (el) {
		if (el.target.id == 'send-form') {
			el.preventDefault();
			
			var newWindowPages = ['freight-letter', 'receipt'];

			var routeUrl = document.querySelector('form');
			routeUrl = routeUrl.getAttribute('action');	

			var loadingDiv = document.getElementById('load');
			loadingDiv.style.visibility = 'visible';

			if (document.querySelector('#modal')) {
				document.querySelector('#modal').remove();
			}
			var form = document.querySelector('form');
			axios({
				method: 'post',
				url: routeUrl,
				data: new FormData(form)
			})
			.then(function (response) {
				loadingDiv.style.visibility = 'hidden';

				if ((typeof response.data) == 'object') {
					var message = messageSend('warning', response.data);
					document.querySelector('#message').innerHTML += message;
					return false;
				}

				var routeName = routeUrl.substring(7);

				if (newWindowPages.includes(routeName)) {
					window.open('/form/bill.html', '_blank');
					return true;
				}

				var data = createFormModal(response.data);
				var doc = document.querySelector('body');
				doc.insertAdjacentHTML('beforeend', data);
				openModal('result');
			});
		}
	});

	var createFormModal = function (body) {
		var content = `
		<div class="d-none py-4" id="result">
		<div class="card fix-height widthable">
		<div class="card-header">Formulário para impressão</div>
		<div class="card-body" id="printable">
		<object data="/form/bill.pdf" type="application/pdf" width="100%" height="380em">
		<embed src="/form/bill.pdf" type="application/pdf">
		  	<a href="https://sumanbogati.github.io/tiny.pdf">test.pdf</a>
		</object>
		</div>
		<div class="card-footer text-muted">
		<button class="btn btn-danget" onclick="$.fancybox.destroy()">Fechar</button>
		<button class="btn btn-primary" onclick="printForm()">Imprimir</button>
		</div>
		</div>
		</div>`;
		return content;
	/**
		var content = `
		<div class="d-none py-4" id="modal">
		<div class="card">
		<div class="card-header">Formulário para impressão</div>
		<div class="card-body fitable">
		<div class="card-text fix-height" id="printable">`+ body +`</div>
		</div>
		<div class="card-footer text-muted">
		<button class="btn btn-danget" onclick="$.fancybox.destroy()">Fechar</button>
		<button class="btn btn-primary" onclick="printForm()">Imprimir</button>
		</div>
		</div>
		</div>`;
		return content;
		*/
	}
});