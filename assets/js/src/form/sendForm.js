document.addEventListener('DOMContentLoaded', function () {
	var routeUrl = document.querySelector('form');
	routeUrl = routeUrl.getAttribute('action');

	document.addEventListener('click', function (el) {
		if (el.target.id == 'submit') {
			var form = document.querySelector('form');
			let formInfo = new FormData(form);
			//el.preventDefault();
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
				var rmv = document.querySelector('[data-toggle]');
				rmv.id="show";
				console.log(form, body);
			});
		}
	});

	var createFormModal = function (body) {
		var content = `
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-custom" role="document">
		<div class="modal-content">
		<div class="modal-header">
		<h5 class="modal-title">Formulario Para Impressão</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
		</div>
		<div class="modal-body">
		`+ body +`
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
		<button type="button" class="btn btn-primary">Imprimir</button>
		</div>
		</div>
		</div>
		</div>`;

		return content;
	}
});