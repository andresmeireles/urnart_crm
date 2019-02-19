if (document.querySelector('.disabledClick')) {
	document.querySelector('.disabledClick').addEventListener('submit', function (el) {
			el.target.setAttribute('disabled', '');
			alert('para ai');
			el.preventDefault();
			return false;
			console.log(el);
	}, true);
}