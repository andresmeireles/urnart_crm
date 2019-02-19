/**
 * Automatic conversion functions for change\blur actions
 */
document.addEventListener('change', function (el) {

    if (el.target.classList.contains('auto-float-convert-blur')) {
        let inputField = el.target;
        let removeComma = inputField.value.replace('.', '');
        let newValue = strToMoney(removeComma.replace(',', '.'));
        el.target.closest('div').querySelector('[converted-value]').value = newValue;
        let value = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(newValue);
        inputField.value = value;
        return true;
    }

}, true);