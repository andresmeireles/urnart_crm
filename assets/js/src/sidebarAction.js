$(function () {
	const toggle = (el, div) => {
		if (window.getComputedStyle(el).display !== 'none') {
			hide(el, div);
			return;
		}

		show(el, div);
		return;
	}

	const hide = (el, div) => {
		div.classList.add('col-md-0');
		div.classList.remove('col-md-2');
		el.classList.add('hide-content');
	}

	const show = (el, div) => {
		div.classList.add('col-md-2');
		div.classList.remove('col-md-0');
		el.classList.remove('hide-content');
	}

	var menuButton = document.querySelector('#hideMenusNames');

	menuButton.addEventListener('click', function () {
		var el = document.querySelectorAll('#menus li span');
		let div = document.querySelector('#collapsible-sidebar');
		for (var i = 0; i < el.length; i++) {
			toggle(el[i], div);
		}
	});
});
