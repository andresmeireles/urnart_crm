$(function () {
	var toggle = function (el) {
		if (window.getComputedStyle(el).display !== 'none') {
			hide(el);
			return;
		}

		show(el);
		return;
	}

	var hide = function (el) {
		el.classList.add('hide-content');
	}

	var show = function (el) {
		el.classList.remove('hide-content');
	}

	var menuButton = document.querySelector('#hideMenusNames');

	menuButton.addEventListener('click', function () {
		var el = document.querySelectorAll('#menus li span');
		for (var i = 0; i < el.length; i++) {
			toggle(el[i]);
		}
	});
});
