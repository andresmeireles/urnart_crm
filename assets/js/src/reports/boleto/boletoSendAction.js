if (document.querySelector('#newTitle')) {

    document.addEventListener('click', (element) => {

        if (element.target.hasAttribute('table-target')) {
            element.preventDefault();
            let tableInfo = document.querySelector(element.target.getAttribute('table-target'));
            let formInfo = new FormData(tableInfo);
            let csrfToken = document.querySelector('#token');
            let linkAction = document.querySelector(element.target.getAttribute('table-target')).getAttribute('action');

            sendDataWithCsrf('POST', linkAction, formInfo, csrfToken, (el) => {
                console.log(el);
            });
        }

    });

}