document.addEventListener('change', (el) => {

    if (el.target.classList.contains('status-change')) {
        //console.log(el.target.value, typeof el.target.value);
        let partId = el.target.id;
        let hiddenDateInput = document.querySelector(`#converted-${partId}`);
        let dateInput = document.querySelector(`[date-target="#converted-${partId}"]`);

        hiddenDateInput.removeAttribute('name');
        hiddenDateInput.value = '';
        dateInput.value = '';
        
        let floatField = document.querySelector(`#porContaValue-${partId}`); 
        let porContaDate = document.querySelector(`#hiddenDate-${partId}`);

        floatField.setAttribute('type', 'hidden');
        floatField.removeAttribute('required');
        floatField.value = '';
        porContaDate.setAttribute('type', 'hidden');
        porContaDate.removeAttribute('required');
        porContaDate.value = '';
        
        let floatFieldHidden = document.querySelector(`#porContaMoney-${partId}`); 
        let porContaDateHidden = document.querySelector(`#porContaDate-${partId}`);
        
        floatFieldHidden.value = '';
        porContaDateHidden.value = '';

        switch (el.target.value) {
            case '0':
                dateInput.setAttribute('disabled', '');
                break;
            case '1':
                dateInput.removeAttribute('disabled');
                hiddenDateInput.setAttribute('name', 'boletoPaymentDate');
                dateInput.setAttribute('required', '');
                break;
            case '2':
                dateInput.removeAttribute('disabled');
                hiddenDateInput.setAttribute('name', 'boletoPaymentDate');
                dateInput.setAttribute('required', '');
                break;
            case '3':
                dateInput.removeAttribute('disabled');
                hiddenDateInput.setAttribute('name', 'boletoProvisionamentoDate');
                dateInput.setAttribute('required', '');
                break;
            case '4':
                dateInput.setAttribute('disabled', '');
                dateInput.removeAttribute('required');
                floatField.setAttribute('type', 'text');
                floatField.setAttribute('required', '');
                porContaDate.setAttribute('type', 'text');
                porContaDate.setAttribute('required', '');
                break;
            default:
                throw Error('Deu algo errado na função change.');
        }
    }

}, true);