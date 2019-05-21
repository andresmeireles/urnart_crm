document.addEventListener('click', function(el) {
	if (document.querySelector('#update') !== null || document.querySelector('#add-prod') !== null) {
		let formId = document.querySelector('form').getAttribute('id');
		
		if (el.target.getAttribute('data-action')) {
			el.preventDefault();
			var modalId = el.target.getAttribute('data-action');
			modal(modalId);			
		}
		
		if (el.target.id == 'inputOtherVendors' ||  el.target.tagName == 'UL') {
			//var inputVendorDiv = document.querySelector('#inputOtherVendors');
			var inputDiv = el.target.getAttribute('data-target');
			var input = document.querySelector(`#${formId} #${inputDiv}`);
			//inputDiv.classList.add('bg-light');
			input.classList.remove('d-none');
			input.focus();
		}
		

		if (el.target.id == 'removeAll' && document.querySelector('#colorsList') !== null) {
			var vendorList = document.querySelector('#colorsList');
			var otherVendors = document.querySelector('#colorsGrid');
			otherVendors.innerHTML = '';
			vendorList.innerHTML = ''
		}

		if (el.target.id == 'removeAll' && document.querySelector('#vendorsList') !== null) {
			var vendorList = document.querySelector('#vendorsList');
			var otherVendors = document.querySelector('#otherVendors');
			otherVendors.innerHTML = '';
			vendorList.innerHTML = ''
		}
		
		if (el.target.id == 'removeItem') { 
			var target = el.target.getAttribute('data-target');
			var liElement = el.target.parentNode;
			let liName = liElement.getAttribute('name');
			var option = document.querySelector(`form#${formId} select#${target}List`).querySelector(`#${liName}`)
			option.remove()
			liElement.remove()
		}
		
		if (el.target.hasAttribute('required')) {
			var input = el.target.parentNode
			if (input.querySelector('small.text-danger')) {
				var remover = input.querySelector('small.text-danger')
				remover.remove()
			}
		}
	}
});

document.addEventListener('keyup', function (el) {
	if (document.querySelector('.feedstock')) {
		let formId = document.querySelector('form').getAttribute('id');

		if (el.target.id == 'inputOther' || el.target.id == 'colors') {
			if (el.which === 27) {
				hideInputField(el.target.id);
			}
			
			var input = el.target.value;
			var select = document.querySelector(`#${formId}	#${el.target.id}List`);
			
			if (el.keyCode == 13) {
				if (input.value == '') {
					return false;
				}
				
				var optValue = input;
				var option = document.createElement('option');
				option.text = optValue;
				option.value = optValue.toLowerCase();
				option.id = (optValue.replace(/\s/g, '-')).toLowerCase();
				option.setAttribute('selected', '');
				
				addOnUl(optValue, el.target.id);
				select.add(option, null);
				hideInputField(el.target.id);
			}
		}
	}
})

document.addEventListener('blur', function (el) {
	if (el.target == 'inputOtherVendors') {
		hideInputField(el.target.id)
	}
})

document.addEventListener('change', function (el) {
	
	if (el.target.id == 'select') {
		var form = el.target.closest('form')
		
		if (el.target.value == 'other') {
			var select = form.querySelector('#periocid')
			select.querySelector('select').removeAttribute('name')
			select.querySelector('select').removeAttribute('required')
			select.querySelector('label').classList.remove('required')
			
			var customInput = form.querySelector('#customPeriocid')
			customInput.querySelector('label').classList.add('required')
			customInput.querySelector('input').setAttribute('name', 'periocid');
			customInput.querySelector('input').setAttribute('required', '');
			customInput.classList.remove('d-none')
		}
		
		if (el.target.value != 'other') {
			var customInput = form.querySelector('#customPeriocid');
			if (!customInput.classList.contains('d-none')) {
				customInput.classList.add('d-none');
				customInput.querySelector('label').classList.remove('required')
				customInput.querySelector('input').removeAttribute('name');
				customInput.querySelector('input').removeAttribute('required');
				customInput.querySelector('input').value = '';
				
				var select = form.querySelector('#periocid')
				select.querySelector('select').setAttribute('name', 'periocid')
				select.querySelector('select').setAttribute('required', '')
				select.querySelector('label').classList.add('required')
			}
		}
	}
})

var addOnUl = function (value, id) {
	let formId = document.querySelector('form').getAttribute('id');
	var ul = document.querySelector(`#${formId} #${id}Grid`);
	var addStr = `<li class="badge badge-pill badge-light" name="${value.toLowerCase()}"><span class="mx-1">${value}</span><span class="text-danger cursor-decoration" data-target="${id}" id="removeItem">x</span></li>`;
	ul.innerHTML += addStr;
	return true;
}

var hideInputField = function (id) {
	let formId = document.querySelector('form').getAttribute('id');
	var inputVendorDiv = document.querySelector(`#${formId} #${id}`);
	inputVendorDiv.classList.remove('bg-light')
	inputVendorDiv.classList.add('d-none')
	inputVendorDiv.value= ''
}