document.addEventListener('click', function (el) {
    if (el.target.id == 'createSurvey') {
        el.preventDefault();
        var formTag = document.querySelector('#formObject');
        var formData = new FormData(formTag);
        let csrfInput = document.querySelector('.token');
        sendDataWithCsrf('POST', '/report/create/survey', formData, csrfInput, function (response) {
            //notification(response.data, 'success');
        });
    }
})