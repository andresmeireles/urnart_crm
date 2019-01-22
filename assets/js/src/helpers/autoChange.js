/**
 * Automatic conversion functions for change\blur actions
 */
document.addEventListener('change', function (el) {
    if (el.target.classList.contains('auto-float-convert-blur')) {
        let inputField = el.target;
        let newValue = strToMoney(inputField.value);
        let value = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(newValue);
        inputField.value = value;
        return true;
    }
}, true)

const strToMoney = (money) => {
    var onlyNumberValue = money.split("R$").pop().trim();
    if (onlyNumberValue.split(',')[1] === '00') {
        let finalValue = onlyNumberValue.split(',')[0].replace('.', '');
        return finalValue;
    }
    return onlyNumberValue;
}