if ( document.querySelector('#admin') ) {

    document.addEventListener('click', (el) => {
        
        if (el.target.hasAttribute('form-target')) {
            el.preventDefault();
            let formTag = el.target.getAttribute('form-target');
            let table = document.querySelector(`#${formTag}`);
            let formData = new FormData(table);
            let formData2 = new FormData(document.querySelector(`#${formTag}`));
            
            let cardBody = table.querySelector('.card-body');    

            simpleRequest('/api/admin/permission', 'POST', formData2, (response) => {
                cardBody.innerHTML = `<p class="alert alert-primary h3">Permissões alteradas com sucesso!</p>`;
            }, 'data');
        }

    });

}