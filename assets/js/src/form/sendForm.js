document.addEventListener('DOMContentLoaded', function () {
	var routeUrl = document.querySelector('form');
	routeUrl = routeUrl.getAttribute('action');

	document.addEventListener('click', function (el) {
		if (document.querySelector('#modal')) {
			document.querySelector('#modal').remove();
		}

		if (el.target.id == 'submit') {
			var form = document.querySelector('form');
			let formInfo = new FormData(form);
			axios({
				method: 'post',
				url: routeUrl,
				data: new FormData(form)
			})
			.then(function (response) {
				var body = response.data;
				var form = createFormModal(body);
				var doc = document.querySelector('body');
				doc.innerHTML += form;
				openModal();
			});
		}
	});

	var createFormModal = function (body) {
		var content = `
		<div class="d-none py-4" id="modal">
		<div class="card">
		<div class="card-header">Formulário para impressão <span class="f-right">Nº de Paginas: <b class="total"></b></span></div>
		<div class="card-body fitable">
		<div class="card-text fix-height" id="printable">`+ body +`</div>
		</div>
		<div class="card-footer text-muted">
		<button class="btn btn-danget">Fechar</button>
		<button class="btn btn-primary" onclick="printForm()">Imprimir</button>
		</div>
		</div>
		</div>`;

		return content;
	}
});