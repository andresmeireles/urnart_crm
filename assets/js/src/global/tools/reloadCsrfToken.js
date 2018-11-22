document.addEventListener('click', function (el) {
    if (el.target.hasAttribute('token')) {
        simpleRequest('/order/csrftoken', 'PATCH', null, function (response) {
            el.target.setAttribute('token', response.data);
        });
    }
});