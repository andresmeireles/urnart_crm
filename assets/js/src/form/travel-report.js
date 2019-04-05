if (document.querySelector('#travel-report')) {

    document.addEventListener('click', (el) => {

        if (el.target.classList.contains('calculate')) {
            el.preventDefault();
            let calculator = document.querySelector('#calculator');
            let numbers = calculator.querySelectorAll('.hc-target');
            let result = 0;
            for (let c of numbers) {
                result += parseFloat(c.value);
            }

            document.querySelector('#cx').value = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result);
        }

    }, true)

    document.addEventListener('blur', (el) => {

        if (el.target.classList.contains('active-disabled')) {
            if (el.target.value === '') {
                el.target.removeAttribute('required');
                let row = el.target.closest('.travel-report');
                let hiddenInput = row.querySelector('[type="hidden"]');
                hiddenInput.value = 0;
                console.log(row.querySelector('[type="hidden"]'), hiddenInput.value)
                let input = row.querySelector('.disabled');
                input.value = '';
                input.setAttribute('disabled', '');
                input.removeAttribute('required');
                const event = new Event('change');
                input.dispatchEvent(event);

                let clones = row.closest('.form-row').querySelectorAll('#cloneableField');
                if (clones.length > 1) {
                    row.closest('#cloneableField').remove();
                }
            }

            if (el.target.value !== '') {
                el.target.setAttribute('required', '');
                let row = el.target.closest('.travel-report');
                let input = row.querySelector('.disabled');
                input.removeAttribute('disabled');
                input.setAttribute('required', '');
            }
        }

        if (el.target.hasAttribute("calculator")) {
            let multiplicator = el.target.getAttribute('calculator');
            let value = el.target.value;
            if (isNaN(parseFloat(value))) {
                el.preventDefault();
                return false;
            }
            let result = parseFloat(value) * parseFloat(multiplicator);
            let row = el.target.closest('div');
            row.querySelector('.hc-target').value = result;
            row.querySelector('.c-target').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result); 
        }

    }, true);

}