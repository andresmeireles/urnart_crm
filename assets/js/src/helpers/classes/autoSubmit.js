document.addEventListener('submit', (el) => {
    if (el.target.querySelectorAll('select').length !== 0) {
        let selector = el.target.querySelectorAll('select');
        for(let s of selector) {
            if (s.hasAttribute('required') && s.value.length > 2 ) {
                el.preventDefault();
                notification('todos os campos tem de estar preenchidos', 'error');
                return true;
            } 
        }
    }

    let submitButtons = document.querySelectorAll('.disable-on-submit');

    for (let i of submitButtons) {
        i.removeAttribute('type');
        setTimeout(() => {
            i.setAttribute('type', 'submit');
        }, 10000);
    }
    /* for (let i of submitButtons) {
        i.setAttribute('disabled', '');
        setTimeout(() => {
            i.removeAttribute('disabled')
        }, 5000);
    } */

}, true);