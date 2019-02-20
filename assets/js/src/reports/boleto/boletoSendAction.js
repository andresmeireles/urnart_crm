if (document.querySelector('#boleto')) {

    document.addEventListener('click', (element) => {

        if (element.target.hasAttribute('table-target')) {
            element.preventDefault();
            let tableInfo = document.querySelector(element.target.getAttribute('table-target'));
            let formInfo = new FormData(tableInfo);
            let csrfToken = document.querySelector('#token');
            let linkAction = document.querySelector(element.target.getAttribute('table-target')).getAttribute('action');

            sendDataWithCsrf('POST', linkAction, formInfo, csrfToken, (el) => {
                
            });
        }

    });

    document.addEventListener('change', (el) => {

        if (el.target.classList.contains('status-change')) {
            let partId = el.target.id;
            let dateInput = document.querySelector(`[date-target="#converted-${partId}"]`);
            dateInput.removeAttribute('disabled');
            let hiddenDateInput = document.querySelector(`#converted-${partId}`);
            hiddenDateInput.value = '';
            
            if (el.target.value === '1') {
                dateInput.setAttribute('required', '');
            } else {
                dateInput.removeAttribute('required');
            }
        }

    }, true);

}