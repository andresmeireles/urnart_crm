document.addEventListener('focus', (el) => {

    if (el.target.classList.contains('calendar-selector')) {
        $(el.target).datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            zIndex: 99993,
            autoHide: true
        });
    }

    if (el.target.hasAttribute('auto-clear')) {
        el.target.value = '';
    }

    if (el.target.classList.contains('auto-clear')) {
        el.target.value = '';
    }

}, true);