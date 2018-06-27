$( function () {
    var tagNumber = 1;
    /**
     * Desabilita botão de remoção quando existe apenas um elemento
     */
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
     * Create a new TagRow
     * 
     * Recebe uma elemento, clona-o e copia abaixo do elemento selecionado
     * e focaliza no novo elemento criado.
     * 
     * @param  {[element]}
     * @return {[boolean]}
     */
    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('add-btn')) {

            el.preventDefault();
            if (document.querySelectorAll('#tag').length == 1 ) {
                document.querySelector('[remove-btn]').removeAttribute('disabled');
            }
            var node = el.target.closest('#tag');
            var cloneEl = node.cloneNode(true);
            cloneEl.querySelector('#name').value = '';
            cloneEl.querySelector('#city').value = '';
            cloneEl.querySelector('#amount').value = '';
            cloneEl.querySelector('#checkValue').value = '0';
            cloneEl.querySelector('#check').checked = false;

            node.after(cloneEl);

            cloneEl.querySelector('#name').setAttribute('name', 'tag'+ tagNumber +'[name]');
            cloneEl.querySelector('#city').setAttribute('name', 'tag'+ tagNumber +'[city]');
            cloneEl.querySelector('#amount').setAttribute("name", "tag"+ tagNumber +"[amount]");
            cloneEl.querySelector('#checkValue').setAttribute("name", "tag"+ tagNumber +"[check]");

            cloneEl.querySelector('#name').focus();

            tagNumber += 1;
            return true;		
        }
    });

    /**
     * Remove elemento selecionado. 
     * Ação não funciona caso exista apenas um elemento.
     */
    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('remove-btn')) {
            if (document.querySelectorAll('#tag').length == 1 ) {
                return false
            }

            el.target.closest('#tag').remove(true);

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

    

});
