import Axios from "axios";
import { isUndefined } from "util";

if (document.querySelector('#form-order') || document.querySelector('#automaticOrder')) {
	$(function () {
		setTimeout(() => {
			calculateProducts();
			recalculate();
			loadSelectFields();
		}, 100);

		var totalPrice = document.querySelector('#finalPrice');
		var allProductsPrice = document.querySelector('#allProductsPrice');
		let allProductsPriceFloat = document.querySelector('#hiddenAllProductsPrice');

		self.addEventListener('submit', (element) => {
			element.preventDefault();
			let submitButton = element.target.querySelector('[type="submit"]');
			submitButton.setAttribute('disabled', '');
			if (element.target.classList.contains('testPayload')) {
				sendOrder(element.target, 'create');
			}

			if (element.target.classList.contains('edit-order')) {
				sendOrder(element.target, 'edit');
			}
		});

		document.addEventListener('click', function (el) {
			if (el.target.id === 'portCheck') {
				var port = document.querySelector('#port');

				if (port.getAttribute('disabled') != null) {
					port.removeAttribute('disabled');
				} else {
					port.value = '';
					port.setAttribute('disabled', 'disabled');
					recalculate();
				}
			}

			if (el.target.id === 'discountCheck') {
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

			if (el.target.hasAttribute('remove-btn')) {
				setTimeout(() => {
					recalculate();
					calculateProducts();
				}, 133);
			}

			if (el.target.hasAttribute('add-btn')) {
				setTimeout(() => {
					loadSelectFields();
				}, 133);
			}
		});

		document.addEventListener('change', (el) => {
			if (el.target.id === 'price' || el.target.id === 'amount') {
				var row = el.target.parentNode.parentNode;
				row.querySelector('#amount').value = (isNaN(parseInt(row.querySelector('#amount').value)) ?
					1 :
					(row.querySelector('#amount').value));
				var qnt = parseInt(row.querySelector('#amount').value);
				row.querySelector('#totalPrice').value = parseInt(
					row.querySelector('#money').value
				) * qnt;

				calculateProducts();
				recalculate();
			}

			if (el.target.id === 'amount') {
				if (el.target.value < 0) {
					el.target.value = Math.abs(el.target.value);
					recalculate();
				}
			}

			if (el.target.id === 'freight' || el.target.id === 'discount') {
				recalculate();
			}

			if (el.target.classList.contains('load-models')) {
				let itemId = el.target.value;
				let cloneField = el.target.closest('#cloneableField');
				let priceField = cloneField.querySelector('#price');
				setItemValue(itemId, priceField);
			}
		}, true);

		document.addEventListener('focus', (element) => {
			if (element.target.classList.contains('load-models')) {
				findModel(element.target)
			}
		}, true);

		const calculateProducts = () => {
			var allPrices = document.querySelectorAll('#money');
			var allAmount = document.querySelectorAll('#amount');
			var allPricesSum = 0;
			let allAmountSum = 0;

			for (var c = 0; c < allPrices.length; c++) {
				let price = parseInt(allPrices[c].value);
				let amount = parseInt(allAmount[c].value);
				allPricesSum += price * amount;
				allAmountSum += amount;
			}

			document.querySelector('#finalAmount').innerHTML = isNaN(allAmountSum) ?
				0 :
				allAmountSum;
			allProductsPriceFloat.value = allPricesSum;
			allProductsPrice.innerHTML = money(allPricesSum);

			return true;
		};

		const recalculate = function () {
			var products = isNaN(parseInt(allProductsPriceFloat.value)) ? 0 : parseInt(allProductsPriceFloat.value);
			var freight = (isNaN(parseInt(document.querySelector('#hiddenFreight').value)) ?
				0 :
				parseInt(document.querySelector('#hiddenFreight').value));
			var discount = (isNaN(parseInt(document.querySelector('#hiddenDiscount').value)) ?
				0 :
				parseInt(document.querySelector('#hiddenDiscount').value));
			let finalTotalPrice = (products + freight) - discount;
			totalPrice.innerHTML = money(finalTotalPrice);

			return true;
		};

		const sendOrder = (form, type) => {
			let requestUrl = type === 'create' ?
				'/order/async/createManualOrder' :
				`/order/async/editManualOrder/${form.id}`;
			Axios({
				method: 'POST',
				url: requestUrl,
				data: new FormData(form)
			}).then((response) => {
				if (response.status === 203) {
					notification(response.data);
					submitButton.removeAttribute('disabled');
					return false;
				}
				window.location.href = '/order';
			});
		}

		const findModel = (elementTargeted) => {
			Axios({
				method: 'POST',
				url: '/get/ModelsModel',
			}).then((response) => {
				let opt = [];
				for (let res in response.data) {
					let value = {
						name: response.data[res].n,
						cod: response.data[res].v
					};
					opt.push(value);
				}
				elementTargeted.classList.remove('form-control');
				$(elementTargeted).selectize({
					sortField: 'text',
					placeholder: 'Selecione um item.',
					valueField: 'name',
					labelField: 'name',
					searchField: ['name'],
					delimiter: ',',
					onChange: function (element) {
						let cod = findElementCod(element, opt);
						let elementWithAllFields = this.$control[0].closest('#cloneableField');
						let priceField = elementWithAllFields.querySelector('#price');
						setItemValue(cod, priceField);
						let disabledFields = elementWithAllFields.querySelectorAll('[disabled]')
						for (let unf of disabledFields) {
							if (unf.classList.contains('not-disable'))
								continue;

							unf.removeAttribute('disabled');
						}
					},
					options: opt
				});
			});
		}

		const setItemValue = (itemId, priceField) => {
			let res = '';
			Axios({
				method: 'POST',
				url: '/register/getProduct/price',
				data: {
					'entity': 'ModelName',
					'id': itemId
				}
			}).then((response) => {
				priceField.value = response.data;
				priceField.dispatchEvent(new Event('change'));
			})
		}

		const findElementCod = (element, options) => {
			for (let opt of options) {
				if (opt.name === element) {
					return opt.cod;
				}
			}
		}

		const loadSelectFields = () => {
			let selectFields = document.querySelectorAll('#cloneableField select');
			for (let selectField of selectFields) {
				findModel(selectField);
			}
		}
	})
}