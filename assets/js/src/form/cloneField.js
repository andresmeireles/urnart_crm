$(function () {
    var iNumber = 1;

    if (document.querySelectorAll('button [remove-btn]').length === 1) {
        document.querySelector('[remove-btn]').setAttribute('disabled', '');
    }

    /**
     * Disbale some keys
     * 
     * @param el => DOM Element
     */
    document.addEventListener('keypress', function (el) {
        /**
         * Disable ENTER key on page
         */
        if (el.keyCode === 13) {
            el.preventDefault();
            return false;
        }

        /**
         * Enable only numbers on amount input
         */
        if (el.target.getAttribute('id') === 'amount') {
            if (el.keyCode > 31 && (el.keyCode < 48 || el.keyCode > 57)) {
                el.preventDefault();
                return false;
            }
        }
    });

    /**
     * Remove elemento selecionado. 
     * Ação não funciona caso exista apenas um elemento.
     * 
     * @param el => DOM Element
     */
    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('remove-btn')) {
            if (document.querySelectorAll('#cloneableField').length === 1) {
                return false;
            }

            el.target.closest('#cloneableField').remove(true);

            if (document.querySelectorAll('button [remove-btn]').length === 1) {
                document.querySelector('[remove-btn]').setAttribute('disabled', '');
            }

            return true;
        }
    });

    /**
     * Altera o valor de um campo com alteração de checkbox
     *
     * @param el => DOM Element
     */
    document.addEventListener('click', function (el) {
        if (el.target.id === 'checkbox') {
            if (el.target.checked) {
                el.target.parentNode.querySelector('#check').value = 1;
                return true;
            }
            el.target.parentNode.querySelector('#check').value = 0;
        }
    });

    /**
     * Cria um novo elemento
     * 
     * @param el => DOM element
     */
    document.addEventListener('click', function (el) {
        // le o click descobrindo se o attributo exite no elemento clicado
        if (el.target.getAttribute('add-btn')) {

            /**
             * Busca em #cloneable fields marcados com required em branco
             * caso esta aem branco bloqueia a criação de um novo campo
             */
            let cloneArea = el.target.closest("#cloneableField");
            for (var c = 0; c < cloneArea.querySelectorAll('[required]').length; c++) {
                if (cloneArea.querySelectorAll('[required]')[c].value === '') {
                    alert('Preencher campos antes de acrescentar novo registro');
                    return false;
                }
            }

            iNumber += 1;
            el.preventDefault();

            /**
             * Caso exista apenas um registo bloqueia o botão de remoção
             */
            if (document.querySelectorAll('#cloneableField').length === 1) {
                document.querySelector('[remove-btn]').removeAttribute('disabled');
            }

            //clona o elemento
            var node = el.target.closest('#cloneableField');
            var cloneEl = node.cloneNode(true);

            //remove campos marcados com no-clone
            cloneEl.querySelectorAll('[no-clone]').forEach((el) => {
                el.remove();
                el.innerHTML = '&nbsp;';
            })

            //desmarca checkbox caso exista
            if (cloneEl.querySelector('input[type="checkbox"]')) {
                cloneEl.querySelector('input[type="checkbox"]').value = 0;
                cloneEl.querySelector('input[type="checkbox"]').checked = 0;
            }
            cloneEl = removeUnusedSelecttizeDiv(cloneEl);
            // reseta todos os campos escrevedo um nome unico
            var cloneElInputs = cloneEl.querySelectorAll('input, select');
            // var cloneElInputs = cloneEl.querySelectorAll('input');
            let cloneName = cloneEl.querySelector('[input-name]').getAttribute('input-name');
            for (var c = 0; c < cloneElInputs.length; c++) {
                cloneElInputs[c].value = '';
                cloneElInputs[c].querySelectorAll('select').forEach( element => {
                    if (element.getAttribute('defaultClass') !== null) {
                        let defaultClasses = element.getAttribute('defaultClass');
                        element.removeAttribute('class');
                        element.removeAttribute('style');
                        element.setAttribute('class', defaultClasses);
                    }
                    element.value = '';
                });

                if (cloneElInputs[c].hasAttribute('input-number') ) {
                    let newValue = parseInt(document.querySelectorAll('[input-number]')[document.querySelectorAll('[input-number]').length - 1].value) + 1;
                    iNumber = newValue;
                    cloneElInputs[c].value = newValue;
                    cloneElInputs[c].setAttribute('value', newValue);
                    continue;
                }
                if (cloneElInputs[c].classList.contains('ignore-clone')) {
                    continue;
                }
                var inputName = cloneName + iNumber + '[' + cloneElInputs[c].getAttribute('id') + ']';
                cloneElInputs[c].setAttribute('name', inputName);
            }

            cloneEl.querySelector('[input-number]').value = iNumber;

            // cria o campo novo
            node.after(cloneEl);
            checkMask(cloneEl);
            // poem o foco no novo campo
            cloneEl.querySelector('[type="text"]').focus();

            return true;
        }
    });

    const removeUnusedSelecttizeDiv = (element) => {
        let selectizedElement = element.querySelector('.selectize-control.load-models.single');
        if (selectizedElement !== null)
            selectizedElement.remove();
            return element;
    }
});