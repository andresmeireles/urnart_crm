if (document.querySelector('#boleto-index')) {

    if (document.querySelector('.fancy-conversion')) {
        
        let hiddenLinks = document.querySelectorAll('.fancy-conversion');

        for (let element of hiddenLinks) {
            //let element = document.querySelector('.fancy-conversion');
            let link = element.getAttribute('href');
            let hiddenLinkPart = link.replace(link.substr(0, link.lastIndexOf('/') + 1), '');
            element.setAttribute('href', 'javascript:;');
            element.setAttribute('data-fancybox', '');
            element.setAttribute('data-src', `#${hiddenLinkPart}-boleto`);
        }

    }

}