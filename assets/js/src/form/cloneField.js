$(function () {
	var iNumber = 1;

	if (document.querySelectorAll('button [remove-btn]').length == 1) {
        document.querySelector('[remove-btn]').setAttribute('disabled', '');
    } else {
        document.querySelector('[remove-btn]').removeAttribute('disabled');
    }

    /**
     * Disbale some keys
     */
    document.addEventListener('keypress', function (el) {
        /**
         * Disable ENTER key on page
         */
        if (el.keyCode == 13) {
            el.preventDefault();
            return false;
        }

        /**
         * Enable only numbers on amount input
         */
        if (el.target.getAttribute('id') == 'amount') {
            if (el.keyCode > 31 && (el.keyCode < 48 || el.keyCode > 57)) {
                el.preventDefault();
                return false;
            } 
        }
    });

     /**
     * Remove elemento selecionado. 
     * Ação não funciona caso exista apenas um elemento.
     */
    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('remove-btn')) {
            if (document.querySelectorAll('#cloneableField').length == 1 ) {
                return false
            }

            el.target.closest('#cloneableField').remove(true);

            if (document.querySelectorAll('button [remove-btn]').length == 1) {
                document.querySelector('[remove-btn]').setAttribute('disabled', '');
            }

            return true;	
        }
    });

    /**
     * Altera o valor de um campo com alteração de checkbox
     */
    document.addEventListener('click', function (el) {
        if (el.target.id == 'check') {
            if ( el.target.checked ) {
                el.target.parentNode.querySelector('#checkValue').value = 1;
                return true;
            } 
            el.target.parentNode.querySelector('#checkValue').value = 0;
        }
    });

    /**
     * Cria um novo elemento
     */
	document.addEventListener('click', function (el) {
		// le o click descobrindo se o attributo exite no elemento clicado
		if (el.target.getAttribute('add-btn')) {

			/**
			 * Busca em #cloneable fields marcados com required em branco
			 * caso estaj aem branco bloqueia a criação de um novo campo
			 */
			for (var c=0; c < document.querySelectorAll('#cloneableField [required]').length; c++) {
				if (document.querySelectorAll('#cloneableField [required]')[c].value == '') {
					alert('Preencher campos antes de acrescentar novo registro');
					return false;
				}
			}

			iNumber += 1;
			el.preventDefault();

			/**
			 * Caso exista apenas um registo bloqueia o botão de remoção
			 */
            if (document.querySelectorAll('#cloneableField').length == 1 ) {
                document.querySelector('[remove-btn]').removeAttribute('disabled');
            }

            //clona o elemento
            var node = el.target.closest('#cloneableField');
            var cloneEl = node.cloneNode(true);

            // reseta todos os campos escrevedo um nome unico
            var cloneElInputs = cloneEl.querySelectorAll('input'); 
           	for (var c=0; c < cloneElInputs.length; c++) {	
           		var inputName = 'cloneableField'+ iNumber +'['+ cloneElInputs[c].getAttribute('id') +']';
           		cloneElInputs[c].setAttribute('name', inputName); 
           		cloneElInputs[c].value = cloneElInputs[c].defaultValue;
           	}

			cloneEl.querySelector('[input-number]').value = iNumber;
			cloneEl.querySelector('[input-number]').removeAttribute('name')           	

			// cria o campo novo
            node.after(cloneEl);

            // poem o foco no novo campo
            cloneEl.querySelector('[type="text"]').focus();

            return true;
		}
	});

});