$( function () {

	var insertButton = document.querySelectorAll('.add-tag');
	var removeButton = document.querySelectorAll('.remove-tag');

	for (var c = 0; c < insertButton.length; c++) {
			insertButton[c].addEventListener('click', function () {
			alert('parabens amiginho');
		});
	}
});