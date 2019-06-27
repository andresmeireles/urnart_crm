if (document.querySelector('.copyMachine')) {

    document.addEventListener('click', (elm) => {
        if (elm.target.classList.contains('makeClone')) {
            elm.preventDefault();
            let cloneElement = elm.target.closest('.cloneElement');
            createClone(cloneElement);
        }

        if (elm.target.classList.contains('removeElement')) {
            elm.preventDefault();
            let elements = document.querySelectorAll('.cloneElement');
            if (elements.length === 1) {
                notification('Não é possivel concluir essa ação');
                return false; 
            }
            let removeElement = elm.target.closest('.cloneElement');
            removeClonedElement(removeElement);
        }
    });

    const createClone = (cloneElement) => {
        let elementCloned = cloneElement.cloneNode(true);
        let index = cloneElement.closest('.copyMachine').querySelectorAll('.cloneElement').length;
        let newIndex = newNumberIndex(index, cloneElement);
        elementCloned.querySelectorAll('.no-clone').forEach(element => {
            element.remove();
        });
        elementCloned.querySelectorAll('input').forEach( element => {
            element.value = '';
        });
        elementCloned.querySelectorAll('select').forEach( element => {
            if (element.getAttribute('defaultClass') !== null) {
                let defaultClasses = element.getAttribute('defaultClass');
                element.removeAttribute('class');
                element.removeAttribute('style');
                element.setAttribute('class', defaultClasses);
            }
            if (element.closest('.select-field').querySelector('.selectize-control') !== null) {
                element.closest('.select-field').querySelector('.selectize-control').remove();
            }
            element.value = '';
        });
        elementCloned.querySelectorAll('[name]').forEach( element => {
            let oldName = element.getAttribute('name');
            element.setAttribute('name', `${newIndex}${oldName}`);
            element.innerHTML = '';
        });

        cloneElement.after(elementCloned);
    }

    const removeClonedElement = (cloneElement) => {
        cloneElement.remove();
    }

    const newNumberIndex = (index, domPiece) => {
        let newIndex = index++;
        let testIndexName = `${newIndex}${domPiece.querySelector('[name]').getAttribute('name')}`;
        if (document.querySelector(`[name='${testIndexName}']`)) {
            return newNumberIndex(newIndex);
        }

        return newIndex;
    }

}