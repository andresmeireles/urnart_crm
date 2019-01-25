if (document.querySelector('#manualOrder')) {

    document.addEventListener('focus', function (el) {

        if (el.target.id === 'price' ||
            el.target.id === 'discount' ||
            el.target.id === 'freight' ||
            el.target.id === 'payment' ||
            el.target.id === 'transporter') 
        {
            el.target.value = '';
        }

    }, true);
    
    document.addEventListener('blur', function (el) {
        
        if (el.target.id === 'model') {
            let value = el.target.value.trim();
            let row = el.target.closest('#cloneableField');
            let preco = row.querySelector('#price'); 
            let qnt = row.querySelector('#amount');
            let rowPrice = row.querySelector('#totalPrice');

            if (value.length === 0) { 
                preco.value = '';
                preco.setAttribute('disabled', 'disabled');
                qnt.value = '';
                qnt.setAttribute('disabled', 'disabled');
                rowPrice.value = '';
                calculate(row);
                return;
            } 

            preco.removeAttribute('disabled');
            qnt.removeAttribute('disabled');
        }

        if (el.target.id === 'price' && 
            el.target.closest('#cloneableField').querySelector('#amount').value.trim().length !== 0 ||
            el.target.id === 'amount') 
        {
            let row = el.target.closest('#cloneableField');
            if (el.target.id === 'price' && el.target.value.trim().length === 0) {
                row.querySelector('#price').value = ''; 
                row.querySelector('#amount').value = '';
                row.querySelector('#totalPrice').value = '';
                return false;
            }
            calculate(row);
        }

        if (el.target.id === 'discount' && el.target.value === '' && document.querySelector('[toggle-disabled]').checked) {
            document.querySelector('[toggle-disabled]').dispatchEvent(new MouseEvent('click', {'view': window, 'bubbles': true, 'cancelable': true}));
        } 

    }, true);

    document.addEventListener('change', (el) => {

        if (el.target.hasAttribute('toggle-disabled')) {
            let discountValue = document.querySelector(`#${el.target.getAttribute('toggle-disabled')}`);
            if (discountValue.hasAttribute('disabled')) {
                discountValue.value = '';
                document.querySelector('#noDiscount').value = 0;
                discountValue.dispatchEvent(new Event( 'blur', {
                    'view': window,
                    'bubbles': true,
                    'cancelable': true
                }));
            }
        }

    }, true);

    const calculate = (row) => {
        let preco = row.querySelector('#price'); 
        let qnt = row.querySelector('#amount');

        if (preco.value.trim().length === 0 || qnt.value.trim().length === 0) {
            return false;
        }

        // remove unecessary symbols
        let priceValue = strToMoney(preco.value);

        let rowPrice = row.querySelector('#totalPrice');
        if (!(preco.value.trim().length === 0) && (qnt.value === '0' || qnt.value.trim().length === 0)) {
            qnt.value = 1;
        }
        rowPrice.value = parseFloat(priceValue) * parseFloat(qnt.value);

        // Show values of currency
        rowPrice.value = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(rowPrice.value);
        return true;
    }
}