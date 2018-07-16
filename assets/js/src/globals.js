document.addEventListener('DOMContentLoaded', function () {
	$('.flash').each(function (index) {
		$(this).fadeOut(3000);
	});

	if(document.querySelector('.numbers-only')) {
		alert('contruir seletor de numeros apenas');
	}

	if (document.querySelector('.numbers-float-only')) {
		alert("contruir selecionador de numeros e pontos flutuantes");
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