document.addEventListener('focus', (el) => {

    if (el.target.classList.contains('calendar-selector-month')) {
        $(el.target).datepicker({
            format: 'mm/yyyy',
            language: 'pt-BR',
            zIndex: 99993,
            autoHide: true
        });
    }

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

    if(el.target.classList.contains('numbers-only')) {
        el.target.addEventListener('keypress', function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault();

                return false;
            }

            return true;
        });
    }
    
    if (el.target.classList.contains('numbers-float-only')) {
        el.target.addEventListener('keypress', function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 44) {
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault();

                return false;
            }

            return true;
        });
    }

}, true);