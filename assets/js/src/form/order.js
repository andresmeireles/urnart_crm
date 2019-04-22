$(function () {

	var totalPrice = document.querySelector('#finalPrice');
	var allProductsPrice = document.querySelector('#allProductsPrice');
	let allProductsPriceFloat = document.querySelector('#hiddenAllProductsPrice');
	
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

			if (el.target.checked === true) {
				discount.removeAttribute('disabled');				
			} else {
				discount.value = '';
				document.querySelector('#hiddenDiscount').value = 0;
				discount.setAttribute('disabled', 'disabled');
				recalculate();
			}
		}
	});

	document.addEventListener('change', (el) => {

		if (el.target.id === 'price' || el.target.id === 'amount') {
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
	}, true);

	var calculateProducts = () => {
		var allPrices = document.querySelectorAll('#money');
		var allAmount = document.querySelectorAll('#amount');
		var allPricesSum = 0;
		let allAmountSum = 0;

		for (var c=0; c < allPrices.length; c++) {
			let price = parseInt(allPrices[c].value);
			let amount = parseInt(allAmount[c].value)
			let clearInput = price * amount;
			var intPrice = clearInput;
			allPricesSum += intPrice;
			allAmountSum += amount;
		}

		document.querySelector('#finalAmount').innerHTML = allAmountSum;
		allProductsPriceFloat.value = allPricesSum;
		allProductsPrice.innerHTML = money(allPricesSum);
	};

	var recalculate = function () { 
		// var products = ( isNaN(parseInt(allProductsPrice.innerHTML)) ? 0 : parseInt(allProductsPrice.innerHTML) ); 
		var products = isNaN(parseInt(allProductsPriceFloat.value)) ? 0 : parseInt(allProductsPriceFloat.value); 
		var freight = ( isNaN(parseInt(document.querySelector('#hiddenFreight').value)) ? 0 : parseInt(document.querySelector('#hiddenFreight').value) );
		var discount = ( isNaN(parseInt(document.querySelector('#hiddenDiscount').value)) ? 0 : parseInt(document.querySelector('#hiddenDiscount').value));
		console.log(products, freight, discount, document.querySelector('#freight').value);
		finalTotalPrice = (products + freight) - discount;	
		totalPrice.innerHTML = money(finalTotalPrice);	

		return true;
	};
})