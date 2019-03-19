document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('change', (el) => {

        if (el.target.classList.contains('ct-image-picker')) {
            el.preventDefault();
            let file = el.target.files[0];
            document.querySelector('#uploadFileName').innerHTML = file.name;
        }

    })

});