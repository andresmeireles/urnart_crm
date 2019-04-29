if (document.querySelector('#addProdCount')) {

    document.addEventListener('DOMContentLoaded', () => {

        let ahref = document.querySelectorAll('.modal-transform');

        for (let el of ahref) {
            let href = el.getAttribute('href');
            let addLink = href.replace(href.substring(0, href.lastIndexOf('/') + 1), '');
            el.setAttribute('data-fancybox', '');
            el.setAttribute('data-src', `#${addLink}`);
            el.setAttribute('href', 'javascript:;');
        }

    });

    document.addEventListener('click', (el) => {

        if (el.target.hasAttribute('add-btn')) {
            setTimeout(() => {
                let dateValue = document.querySelector('#date-global');
                if (dateValue.value === '') {
                    return false;
                }
                if (dateValue.value !== '') {
                    let replicateDate = document.querySelector('#replicate');
                    replicateDate.dispatchEvent(new Event('blur'));
                }
            }, 700);
        }

        if (el.target.id === 'replicate') {
            setTimeout(() => {
                let dateFields = document.querySelectorAll('#date');
                let dateToReplicate = document.querySelector('#date-global').value;
    
                for (let field of dateFields) {
                    field.value = dateToReplicate;
                }
            }, 500);
        }

    });

    document.addEventListener('focus', (el) => {
        if (el.target.id === 'name') {
            let replicateDate = document.querySelector('#replicate');
            replicateDate.dispatchEvent(new Event('blur'));
        }
        if (el.target.id === 'amount') {
            let replicateDate = document.querySelector('#replicate');
            replicateDate.dispatchEvent(new Event('blur'));
        }
    }, true);

    document.addEventListener('blur', (el) => {
        if (el.target.id === 'replicate') {
            setTimeout(() => {
                let dateFields = document.querySelectorAll('#date');
                let dateToReplicate = document.querySelector('#date-global').value;
    
                for (let field of dateFields) {
                    field.value = dateToReplicate;
                }
            }, 500);
        }

        if (el.target.id === 'amount') {
            setTimeout(() => {
                // let amount = el.target.value;
                let prod = 0;
                let calc = document.querySelector('.prod-calculator');
                let amounts = document.querySelectorAll('#amount');
                for (let am of amounts) {
                    prod += Number(am.value);
                }
                // let prod = Number(amount) + Number(calc.innerHTML);
                calc.innerHTML = prod;
            }, 166);
        }

    }, true);

    document.addEventListener('change', (el) => {

        if (el.target.id === 'replicate') {
            setTimeout(() => {
                let dateFields = document.querySelectorAll('#date');
                let dateToReplicate = document.querySelector('#date-global').value;
    
                for (let field of dateFields) {
                    field.value = dateToReplicate;
                }
            }, 500);
        }

    }, true);

}