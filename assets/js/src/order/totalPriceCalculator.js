/**
 * catch all manual prices and calculate the final price of an order.
 */
document.addEventListener('blur', function (el) {
    
    if (el.target.id === 'amount' || 
        el.target.id === 'price' || 
        el.target.id === 'freight' ||
        el.target.id === 'discount') 
    {
        let rows = document.querySelectorAll('#cloneableField');
        calculateAllPrices(rows);
    }

}, true);

if (document.querySelector('.manual-editing')) {
    setTimeout( () => {
        let element = document.querySelectorAll('#amount');
        for(var blurElement of element) {
            blurElement.dispatchEvent(new Event('blur'));            
        }
    }, 1000);
}

const calculateAllPrices = (rows) => {
    //catch in a variable
    let primePrice = 0;
    let primeAmount = 0;
    let freight = Number(strToMoney(document.querySelector('#freight').value));
    let discount = Number(strToMoney(document.querySelector('#discount').value));
    rows.forEach( (el) => {
        let price   = strToMoney(el.querySelector('#price').value);
        let amount  = el.querySelector('#amount').value;
        primePrice += (price * amount);
        primeAmount += isNaN(parseInt(amount)) ? 0 : parseInt(amount);
        let displayableResult = Intl.NumberFormat('pt-BR').format(primePrice) + ',00';
        document.querySelector('#allProductsPrice').innerHTML = displayableResult;
    });
    if (primePrice === 0) {
        document.querySelector('#finalPrice').innerHTML = '0,00';
        return false;
    }
    let finalPrice = primePrice + freight - discount;
    document.querySelector('#finalPrice').innerHTML = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(finalPrice);
    document.querySelector('#finalAmount').innerHTML = primeAmount; 
}