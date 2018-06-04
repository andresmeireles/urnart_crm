$( function () {

	if (document.querySelectorAll('button [remove-btn]').length == 1) {
		document.querySelector('[remove-btn]').setAttribute('disabled', '');
	} else {
		document.querySelector('[remove-btn]').removeAttribute('disabled');
	}

	document.addEventListener('click', function (el) {
		if (el.target.getAttribute('add-btn')) {
            el.preventDefault();
			if (document.querySelectorAll('#tag').length == 1 ) {
				document.querySelector('[remove-btn]').removeAttribute('disabled');
			}
			var node = el.target.closest('#tag');
            var cloneEl = node.cloneNode(true);
            cloneEl.querySelector('#name').value = '';
            cloneEl.querySelector('#city').value = '';
            cloneEl.querySelector('#amount').value = '';
            cloneEl.querySelector('#checkValue').value = '0';
            cloneEl.querySelector('#check').checked = false;
			node.after(cloneEl);

			return true;		
		}
	});

	document.addEventListener('click', function (el) {
		if (el.target.getAttribute('remove-btn')) {
			if (document.querySelectorAll('#tag').length == 1 ) {
				return false
			}

			el.target.closest('#tag').remove(true);

			if (document.querySelectorAll('button [remove-btn]').length == 1) {
				document.querySelector('[remove-btn]').setAttribute('disabled', '');
			}

			return true;	
		}
	});

    document.addEventListener('click', function (el) {
        if (el.target.id == 'check') {
            if ( el.target.checked ) {
                el.target.parentNode.querySelector('#checkValue').value = 1;
                return true;
            } 
            el.target.parentNode.querySelector('#checkValue').value = 0;
        }
    })

});
