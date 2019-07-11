if (document.querySelector(".truckArrivalAction")) {
    document.addEventListener('click', (elm) => {
        if (elm.target.classList.contains('saveforedit')) {
            elm.preventDefault();
            let formElement = document.querySelector('form');
            let formData = new FormData(formElement);
            axios({
                method: 'POST',
                url: document.URL,
                data: formData
            }).then((response) => {
                setTimeout(() => {
                    window.location.href = response.request.responseURL;
                }, 100);
                notification('Relatório salvo com sucesso.', 'success');
            });
        }
    });

    document.addEventListener('submit', (element) => {
        element.preventDefault();
        let confirmMessage = element.target.querySelector('[confirm]').getAttribute('confirm');
        simpleDialog(confirmMessage , () => {
            element.target.submit();
        });
    });
}