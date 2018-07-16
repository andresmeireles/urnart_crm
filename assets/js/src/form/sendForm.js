document.addEventListener('DOMContentLoaded', function () {
	var routeUrl = document.querySelector('form');
	routeUrl = routeUrl.getAttribute('action');

	document.addEventListener('submit', function (el) {
		var loadingDiv = document.getElementById('load');
		loadingDiv.style.visibility = 'visible';

		if (document.querySelector('#modal')) {
			document.querySelector('#modal').remove();
		}

		el.preventDefault();
		
		var form = document.querySelector('form');
		let formInfo = new FormData(form);
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

			var body = response.data;
			var data = createFormModal(body);
			var doc = document.querySelector('body');
			doc.insertAdjacentHTML('beforeend', data);
			openModal();
		});
	});

	var createFormModal = function (body) {
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
	}
});