document.addEventListener('DOMContentLoaded', function () {
    
    if (document.getElementById('pills-surveys')) {
        
        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('run-save-action')) {
                const targetedLiElement = el.target.closest('li');
                let survey = targetedLiElement.querySelector('.content').getAttribute('data-src');
                let data = document.querySelector(survey);
                checkIfRequiredIsSatisfied(data);
                let form = new FormData(data);
                let userName = targetedLiElement.innerText;
                alert(`Formulário do cliente ${userName} enviado com sucesso.`);
                changeButton(el.target);
                data.innerText = 'Pesquisa já realidaza';
                createNewData(userName, form);

            }

            if (el.target.hasAttribute("send-all-survey")) {
                const liElements = el.target.closest('ul').querySelectorAll('li');
                for (let liEl of liElements) {
                    liEl.querySelector('a[run-save-action]').click();
                }
                alert('envio relaizado com sucesso!');
            }
        })

    }

    const checkIfRequiredIsSatisfied = function (data) {
        var inputs = data.querySelectorAll('[required]')
        for (var input of inputs) {
            if (input.tagName === 'INPUT') {
                if (isNullOrWhiteSpace(input.value)) {
                    alert('preencher formulario completo antes de enviar.');
                    throw Error('formulário não preenchido');
                }
            }
            if (input.tagName === 'DIV') {
                let div = input.querySelectorAll('div');
                let hasError = true;
                for (var element of div) {
                    let inputCheck = element.querySelector('input');
                    if (inputCheck.checked) {
                        hasError = false;
                    }
                }
                if (hasError) {
                    alert('Marcar alternativa em todas as pergintas antes de enviar.');
                    throw Error('Radio button not marked');
                }
            }
        }
    }

    const changeButton = function (element) {
        if (!element.classList.contains('badge')) {
            iElelement = element;
            elementTarget = element.parentNode;
        }
        let elClasses = elementTarget.classList;
        elClasses.remove('badge-success');
        elClasses.remove('text-light');
        elClasses.add('badge-light');
        elClasses.add('text-dark');
        element.removeAttribute('run-save-action');
        iElelement.removeAttribute('run-save-action');
        return elClasses;
    }

    const createNewData = function (customerName, formData) {
        let newDiv = `
            
        `;
    }

});