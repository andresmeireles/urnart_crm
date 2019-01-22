/**
 * catch all manual prices and calculate the final price of an order.
 */
document.addEventListener('blur', function (el) {
    
    if (el.target.id === 'amount' || el.target.id === 'price') {
        if (!checkAmount()) {
            return false;
        }
        let rows = document.querySelectorAll('#cloneableField');
        calculateAllPrices(rows);
    }

}, true);

const calculateAllPrices = (rows) => {
    //catch in a variable
    let primePrice = 0;
    rows.forEach( (el) => {
        let price   = strToMoney(el.querySelector('#price').value);
        let amount  = el.querySelector('#amount').value;
        primePrice += (price * amount);
        console.log(primePrice);
    });
}

const checkAmount = () => {
    let amounts = document.querySelectorAll('#amount');
    for (let amount of amounts ) {
        if (amount.value.trim().length === 0) {
            return false;
        }
    }
    return true;
}