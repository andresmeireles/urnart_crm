document.addEventListener('click', function (el) {

    if (el.target.hasAttribute('toggle-disabled')) {
        let triggerElement = document.querySelector(`#${el.target.getAttribute('toggle-disabled')}`);
        
        if (el.target.checked) {
            triggerElement.removeAttribute('disabled');
            return true;
        }        

        triggerElement.setAttribute('disabled', 'disabled');
        return true;
    }

});