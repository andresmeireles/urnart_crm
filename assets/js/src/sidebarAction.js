document.addEventListener('DOMContentLoaded', () => {
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
		div.classList.remove('col-2');
		div.classList.remove('mx-2');
		//div.classList.remove('sidebar-fixed-width');
		div.querySelector('#sidebar-icons').classList.add('my-4');
		div.classList.add('px-2');
		el.classList.add('hide-content');
		document.querySelector('#content').classList.add('mx-5');
		let sideIcons = document.querySelectorAll('.side-icons');
		for (let icon of sideIcons) {
			icon.classList.add('fa-2x');
			icon.classList.remove('mx-2');
		}
	}

	const show = (el, div) => {
		//div.classList.add('sidebar-fixed-width');
		div.classList.remove('col-md-0');
		div.classList.add('mx-2');
		div.classList.add('col-2');
		div.classList.remove('px-2');
		el.classList.remove('hide-content');
		div.querySelector('#sidebar-icons').classList.remove('my-4');
		document.querySelector('#content').classList.remove('mx-5');
		let sideIcons = document.querySelectorAll('.side-icons');
		for (let icon of sideIcons) {
			icon.classList.remove('fa-2x');
			icon.classList.add('mx-2');
		}
	}

	//var menuButton = document.querySelector('#hideMenusNames');

	document.addEventListener('click', (el) => {
		if (el.target.id === 'hideMenusNames') {
			var el = document.querySelectorAll('#menus li span');
			let div = document.querySelector('#collapsible-sidebar');
			for (var i = 0; i < el.length; i++) {
				toggle(el[i], div);
			}
		}
	});
});