document.addEventListener('DOMContentLoaded', function () {
    
    if (document.getElementById('pills-surveys')) {
        
        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('run-save-action')) {
                $.fancybox.close();
                const liEl = el.target.getAttribute('liTag');
                const targetedLiElement = document.querySelector(`.${liEl}`);
                let survey = targetedLiElement.querySelector('.content').getAttribute('data-src');
                let data = document.querySelector(survey);
                let formData = data.querySelector('form');
                checkIfRequiredIsSatisfied(formData);
                let form = new FormData(formData);
                let userName = targetedLiElement.innerText;
                //changeButton(el.target);
                //data.innerText = 'Pesquisa já realidaza';
                data.insertAdjacentHTML('afterBegin', `
                    <div>
                        Pesquisa já realizada
                    </div>
                `);
                formData.classList.add('d-none');
                createNewData(`pesquisa${survey.substr(1)}` ,userName, form);
                notification(`Formulário do cliente <b>${userName.toUpperCase()}</b> enviado com sucesso.`, 'success');
            }

            if (el.target.hasAttribute("send-all-survey")) {
                el.target.removeAttribute('send-all-survey');
                const liElements = el.target.closest('ul').querySelectorAll('li');
                let sucess = false;
                for (let liEl of liElements) {
                    if (liEl.querySelector('a').hasAttribute('run-save-action')) {
                        liEl.querySelector('a[run-save-action]').click();
                        sucess = true;
                    }
                }
                if (!sucess) {
                    alert('envio relaizado com sucesso!');
                    notification('envio relaizado com sucesso!');
                }
                setTimeout(() => {
                    el.target.setAttribute('send-all-survey', 'zz');
                }, 2000)
            }

            if (el.target.hasAttribute('run-save-action-server')) {
                const targetedLiElement = el.target.closest('li');
                let survey = targetedLiElement.querySelector('.content').getAttribute('data-src');
                let data = document.querySelector(survey).querySelector('form');
                checkIfRequiredIsSatisfied(data);
                let form = new FormData(data);
                let userName = targetedLiElement.innerText;
                changeButton(el.target);
                data.innerText = 'Pesquisa já realidaza';
                createNewData(`pesquisa${survey.substr(1)}` ,userName, form);
                notification(`Formulário do cliente <b>${userName.toUpperCase()}</b> enviado com sucesso.`, 'success');
            }
        })

    }

    const checkIfRequiredIsSatisfied = function (data) {
        var inputs = data.querySelectorAll('[required]')
        for (var input of inputs) {
            if (input.tagName === 'INPUT') {
                if (isNullOrWhiteSpace(input.value)) {
                    //alert('preencher formulario completo antes de enviar.');
                    const inputName = input.name;
                    let user = inputName.slice(inputName.indexOf('[') + 1, inputName.indexOf(']'));
                    notification(`preencher formulario completo de <b>${user.toUpperCase()}</b> antes de enviar.`);
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
                    //alert('Marcar alternativa em todas as pergintas antes de enviar.');
                    notification('Marcar alternativa em todas as pergintas antes de enviar.');
                    throw Error('Radio button not marked');
                }
            }
        }
    }

    const changeButton = function (element) {
        elementTarget = element;
        if (!element.classList.contains('badge')) {
            element.removeAttribute('run-save-action');
            elementTarget = element.parentNode;
        } else {
            elementTarget.querySelector('i').removeAttribute('run-save-action');
        }
        let elClasses = elementTarget.classList;
        elClasses.remove('badge-success');
        elClasses.remove('text-light');
        elClasses.add('badge-light');
        elClasses.add('text-dark');
        elementTarget.removeAttribute('run-save-action');
        return elClasses;
    }

    const createNewData = function (token, customerName, formData) {
        let title = `
            <h5><b>${customerName.toUpperCase()}</b></h5>
        `;
        let answerContent = '';
        for (let question of formData.entries()) {
            answerContent += `
            <label class="form-check-label">
                <b>${question[0].substr(question[0].lastIndexOf(']') + 1)}</b>
            </label>
            <p>${question[1]}</p>
            `;
        }
        let parentElement = document.getElementById(token);
        parentElement.innerHTML = '';
        parentElement.insertAdjacentHTML('beforeend', title);
        parentElement.insertAdjacentHTML('beforeend', answerContent);
    }

});