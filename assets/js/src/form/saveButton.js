if (document.querySelector('.save-button-action')) {
    document.addEventListener('mouseover', (el) => {

        if (el.target.classList.contains('save-button-action')) {
            let form = el.target.closest('form');
            form.removeAttribute('target');
        }

    }, true);

    document.addEventListener('mouseout', (el) => {

        if (el.target.classList.contains('save-button-action')) {
            let form = el.target.closest('form');
            form.setAttribute('target', '__blank');
        }

    }, true);

}