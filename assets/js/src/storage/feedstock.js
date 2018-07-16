document.addEventListener('DOMContentLoaded', function () {
	document.addEventListener('click', function(el) {
		if (el.target.getAttribute('data-action')) {
			el.preventDefault();
			var modalId = el.target.getAttribute('data-action');
			modal(modalId);			
		}
	});
});