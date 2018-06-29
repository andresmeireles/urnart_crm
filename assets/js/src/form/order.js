$(function () {

	var totalPrice = document.querySelector('#finalPrice');
	var allProductsPrice = document.querySelector('#allProductsPrice');
	
	document.addEventListener('click', function (el) {
		if (el.target.id == 'portCheck') {
			var port = document.querySelector('#port');

			if (port.getAttribute('disabled') != null) {
				port.removeAttribute('disabled');
			} else {
				port.value = '';
				port.setAttribute('disabled', 'disabled');
				recalculate();
			}
		}

		if (el.target.id == 'discountCheck') {
			var discount = document.querySelector('#discount');

			if (discount.getAttribute('disabled') != null) {
				discount.removeAttribute('disabled');
			} else {
				discount.value = '';
				discount.setAttribute('disabled', 'disabled');
				recalculate();
			}
		}
	});

	document.addEventListener('change', function (el) {

		if (el.target.id == 'price' || el.target.id == 'amount') {
			var row = el.target.parentNode.parentNode;

			var amount = ( isNaN(parseInt(row.querySelector('#amount').value)) ? 1 : (row.querySelector('#amount').value) );

			row.querySelector('#amount').value = amount;

			var qnt = parseInt(row.querySelector('#amount').value);

			var productTotalPrice = parseInt(row.querySelector('#price').value) * qnt;
			row.querySelector('#totalPrice').value = productTotalPrice;

			calculateProducts();
			recalculate();
		}

		if (el.target.id == 'freight' || el.target.id == 'discount') {
			recalculate();
		}
	});

	var calculateProducts = function () {
		var allPrices = document.querySelectorAll('#totalPrice');
		var allPricesSum = 0;

		for (var c=0; c < allPrices.length; c++) {
			var intPrice = parseInt(allPrices[c].value);
			allPricesSum += intPrice;
		}

		allProductsPrice.innerHTML = allPricesSum;
	};

	var recalculate = function () { 
		var products = ( isNaN(parseInt(allProductsPrice.innerHTML)) ? 0 : parseInt(allProductsPrice.innerHTML) ); 
		var freight = ( isNaN(parseInt(document.querySelector('#freight').value)) ? 0 : parseInt(document.querySelector('#freight').value) );
		var discount = ( isNaN(parseInt(document.querySelector('#discount').value)) ? 0 : parseInt(document.querySelector('#discount').value));

		totalPrice.innerHTML = (products + freight) - discount;	

		return true;
	};
})