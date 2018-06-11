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
		if (el.target.id == 'price') {

			var prices = document.querySelectorAll('#price');
			var sumPrices = 0

			for(var c=0; c < prices.length; c++) {
				var intPrice =  prices[c].value
				intPrice = parseInt(intPrice);

				sumPrices += intPrice;
			}

			sumPrices = parseFloat(sumPrices);

			allProductsPrice.innerHTML = sumPrices;

			calculateTotalPrice(sumPrices);

			return true;
		}
	});

	var calculateTotalPrice = function (price) {

		var freight = ( parseInt(document.querySelector('#freight').value) == 'NaN' ? 0 : parseInt(document.querySelector('#freight').value) );
		var discount = (parseInt(document.querySelector('#discount').value) == 'NaN' ? 0 : parseInt(document.querySelector('#discount').value));

		totalPrice.innerHTML = (price + freight) - discount;

		return true;
	};

	var recalculate = function () { 
		var products = parseInt(document.querySelector('#allProductsPrice').innerHTML); 
		var freight = ( parseInt(document.querySelector('#freight').value) == 'NaN' ? 0 : parseInt(document.querySelector('#freight').value) );
		var discount = (parseInt(document.querySelector('#discount').value) == 'NaN' ? 0 : parseInt(document.querySelector('#discount').value));	

		totalPrice.innerHTML = (products + freight) - discount;	

		return true;
	};
})