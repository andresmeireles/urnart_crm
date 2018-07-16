document.addEventListener('DOMContentLoaded', function () {
	
	document.addEventListener('click', function(el) {
		if (el.target.getAttribute('data-action')) {
			el.preventDefault();
			var modalId = el.target.getAttribute('data-action');
			modal(modalId);			
		}
	});

	document.addEventListener('click', function(el) {
		if (el.target.id == 'inputOtherVendors' || el.target.tagName == 'UL') {
			var inputVendorDiv = document.querySelector('#inputOtherVendors');
			var inputVendor = inputVendorDiv.querySelector('input');
			inputVendorDiv.classList.add('bg-light');
			inputVendor.classList.remove('d-none');
			inputVendor.focus();
		}

		if (el.target.id == 'removeAll') {
			var vendorList = document.querySelector('#vendorsList');
			var otherVendors = document.querySelector('#otherVendors');
			otherVendors.innerHTML = '';
			vendorList.innerHTML = ''
		}

		if (el.target.id == 'removeItem') {
			var liElement = el.target.parentNode;
			var removeIdOption = liElement.querySelector('span').innerText.toLowerCase();
			var idOpt = removeIdOption.replace(/\s/g, '-');
			document.querySelector('#otherVendors #'+ idOpt).remove();
			liElement.remove();
		}
	});

	document.querySelector('#inputOtherVendors input').addEventListener('keypress', function (el) {
		var input = document.querySelector('#inputOtherVendors input');
		var select = document.querySelector('#otherVendors');


		if (el.keyCode == 13) {
			if (input.value == '') {
				return false;
				el.preventDefault();
			}

			var optValue = input.value;
			var option = document.createElement('option');
			option.text = optValue;
			option.value = optValue.toLowerCase();
			option.id = (optValue.replace(/\s/g, '-')).toLowerCase();
			option.setAttribute('selected', '');

			addOnUl(optValue);
			select.add(option, null);
			hideInputField();
		} 
	});

	document.querySelector('#inputOtherVendors input').addEventListener('keydown', function (el) {
		if (el.which === 27) {
			hideInputField();
		}
	});

	document.querySelector('#inputOtherVendors input').addEventListener('blur', function (el) {
			hideInputField();
	});

	var addOnUl = function (value) {
		var ul = document.querySelector('#vendorsList');
		var addStr = `<li class="badge badge-pill badge-light"><span class="mx-1">${value}</span><span class="text-danger cursor-decoration" id="removeItem">x</span></li>`;
		ul.innerHTML += addStr;
		return true;
	}

	var hideInputField = function () {
		document.querySelector('#inputOtherVendors input').value= '';
		var inputVendorDiv = document.querySelector('#inputOtherVendors');
		var inputVendor = inputVendorDiv.querySelector('input');
		inputVendorDiv.classList.remove('bg-light');
		inputVendor.classList.add('d-none');
	}
});