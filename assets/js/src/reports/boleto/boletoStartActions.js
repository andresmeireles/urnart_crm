if (document.querySelector('#boleto-index')) {

    if (document.querySelector('.fancy-conversion')) {
        let element = document.querySelector('.fancy-conversion');
        element.setAttribute('href', 'javascript:;');
        element.setAttribute('data-fancybox', '');
        element.setAttribute('data-src', '#create-boleto');
    }

}