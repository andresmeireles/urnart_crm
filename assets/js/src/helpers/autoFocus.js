document.addEventListener('focus', (el) => {

    if (el.target.classList.contains('calendar-selector')) {
        $(el.target).datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    }

}, true)