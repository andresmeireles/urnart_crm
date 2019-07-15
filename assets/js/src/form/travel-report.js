if (document.querySelector('#travel-report') ||
    document.querySelector('.truckArrivalAction')) {

    if (document.querySelector('#fill')) {
        document.addEventListener('DOMContentLoaded', () => {
            let inputs = document.querySelectorAll('input');

            for (let i of inputs) {
                i.dispatchEvent(new Event('change'));
                i.dispatchEvent(new Event('blur'));
            }
        });
    }

    document.addEventListener('click', (el) => {

        if (el.target.classList.contains('calculate')) {
            el.preventDefault();
            let calculator = document.querySelector('#calculator');
            let numbers = calculator.querySelectorAll('.hc-target');
            let result = 0;
            for (let c of numbers) {
                result += parseFloat(c.value);
            }
            $.fancybox.close();
            document.querySelector('#cx').value = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result);
            document.querySelector('#input-value-cx').value = result;
            document.querySelector('#value-cx').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result);
            runCalc();
        }

    }, true);

    document.addEventListener('change', (el) => {

        if (el.target.id === 'cx') {
            setTimeout(() => {
                let strNumber = el.target.closest('div').querySelector('[converted-value]').value;
                let result = parseFloat(strNumber);
                document.querySelector('#input-value-cx').value = result;
                document.querySelector('#value-cx').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result); 
                runCalc();
            }, 1000);
        }

    }, true);

    document.addEventListener('blur', (el) => {

        if (el.target.classList.contains('ent')) {

            if (el.target.value === '') {
                el.target.value = 0;
                el.target.dispatchEvent(new Event('change'));
                document.querySelector('#t-hidden-value').value = 0;
                document.querySelector('#t-nh-value').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(0);
            }

            setTimeout(() => {
                let entrada = document.querySelectorAll('.entrada');
                let result = 0;

                for (let e of entrada) {
                    if (isNaN(parseFloat(e.value))) {
                        continue;
                    }
                    let strValue = e.value;
                    result += parseFloat(strValue);
                }

                document.querySelector('#t-hidden-value').value = result;
                document.querySelector('#t-nh-value').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result);
                document.querySelector('#input-value-in').value = result;
                document.querySelector('#value-in').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result);
            }, 1000);

            setTimeout(() => {
                runCalc();
            }, 1100);
        }

        if (el.target.classList.contains('despesas')) {
            setTimeout(() => {
                let totalDesp = document.querySelector('.despesas-value').innerHTML;
                document.querySelector('#value-desp').innerHTML = totalDesp;
                let stringFloatFormated = totalDesp.replace('R$', '');
                noSpaceString = stringFloatFormated.replace(' ', '');
                noSpaceString = noSpaceString.replace('&nbsp;', '');
                noDotString = noSpaceString.replace('.', '');
                commaAsDotString = noDotString.replace(',', '.');
                finalString = strToMoney(commaAsDotString);
                document.querySelector('#input-value-desp').value = finalString;
                runCalc();
            }, 1000);
        }

        if (el.target.classList.contains('active-disabled')) {
            if (el.target.value === '') {
                el.target.removeAttribute('required');
                let row = el.target.closest('.travel-report');
                let hiddenInput = row.querySelector('[type="hidden"]');
                hiddenInput.value = 0;
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

            runCalc();
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

            let calculator = document.querySelector('#calculator');
            let numbers = calculator.querySelectorAll('.hc-target');
            let cresult = 0;
            for (let c of numbers) {
                cresult += parseFloat(c.value);
            }

            document.querySelector('#c-result').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(cresult);
        }

    }, true);

    const runCalc = () => {
        let cx = document.querySelector('.cxSub').value;
        let entrada = document.querySelector('.inSub').value;
        let desp = document.querySelector('.despSub').value;
        let total = parseFloat(entrada) - parseFloat(cx);
        document.querySelectorAll('.tSub').value = total;
        let result = 0;

//        result = (parseFloat(entrada) - parseFloat(cx)) - parseFloat(desp);
        let floatEntrada = parseFloat(entrada);
        let floatDesp = parseFloat(desp)
        result = (floatEntrada - floatDesp) - parseFloat(cx);

        document.querySelector('#input-value-final').value = result;
        document.querySelector("#value-total").innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total)
        document.querySelector('#value-final').innerHTML = Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result);

        writeMessage(result);
    }

    const writeMessage = (result) => {
        document.querySelector('#analytics').style.display = '';

        let message = document.querySelector('#prest-msg');
        message.setAttribute('class', '');
        let driverName = document.querySelector('#driverName').value;

        if (result === 0) {
            message.classList.add('alert', 'alert-success');
            message.innerHTML = "A prestação de contas está não tem nenhum tipo de pendencia.";
            return; 
        }

        if (result > 0) {
            message.classList.add('alert', 'alert-danger');
            message.innerHTML = `Motorista ${driverName} tem debito de ${Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result)} em despesas não explicadas.`;
            return;
        }

        if (result < 0) {
            message.classList.add('alert', 'alert-warning');
            result = Math.abs(result);
            message.innerHTML = `O valor de ${Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(result)} de ${driverName} não foi explicado.`;
            return;
        }
    }
}