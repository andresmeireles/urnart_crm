/**
 * Automatic conversion functions for change\blur actions
 */
document.addEventListener('change', function (el) {

    if (el.target.classList.contains('auto-float-convert-blur')) {
        let inputField = el.target;
        let removeFloatPoint = inputField.value.replace('.', '');
        let newValue = strToMoney(removeFloatPoint.replace(',', '.'));
        el.target.closest('div').querySelector('[converted-value]').value = newValue;
        let value = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(newValue);
        inputField.value = value;
        //return this;
    }

    if (el.target.classList.contains('sum')) {
        let div = el.target.closest('div');
        let attName = div.querySelector('[sum-target]').getAttribute('sum-target');
        let selector = `[sum-target="${attName}"]`;
        let elToSum = document.querySelectorAll(selector);
        let sum = 0;
        for (let el of elToSum) {
            let number = isNaN(parseFloat(el.value)) ? 0 : parseFloat(el.value);
            sum += number;
        }

        let val = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(sum);

        document.querySelector(attName).innerHTML = val;
    }

}, true);

$('.calendar-selector').on('change', (element) => {
    let dateInput = element.target.closest('div').querySelector(element.target.getAttribute('date-target'));
    let date = element.target.value;
    let convertedDate = date.replace(new RegExp('/', 'g'), '-');
    dateInput.value = convertedDate;
});