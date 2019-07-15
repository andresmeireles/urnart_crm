if (document.querySelector(".truckArrivalAction")) {
    document.addEventListener('click', (elm) => {
        if (elm.target.classList.contains('saveforedit')) {
            elm.preventDefault();
            simpleDialog('Você deseja salvar as informações no banco de dados?', () => {
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
                }).catch((err) => {
                    notification('Erro ao criar relatório.');
                    console.log(err);
                });
            });
        }

        if (elm.target.classList.contains('accountability-button')) {
            elm.preventDefault();
            let domFormPiece = elm.target.closest('form');
            if (!crf(domFormPiece)) {
                notification('Preencha os campos marcados com asterisco vermelho');
                return false;
            }
            let confirmMessage = elm.target.getAttribute('confirm');            
            simpleDialog(confirmMessage , () => {
                axios({
                    method: 'POST',
                    url: '/truck/accoutability',
                    data: new FormData(domFormPiece),
                }).then((response) => {
                    if (response.data.indexOf('DOCTYPE html') !== -1) {
                        notification('Relatorio salvo com sucesso', 'success');
                        setTimeout(() => {
                            window.location.href = response.request.responseURL;
                        }, 2000);    
                    }
                    notification(response.data);
                });
                //domFormPiece.submit();
            });    
        }

    });

    document.addEventListener('submit', (element) => {
        element.preventDefault();
        let form = element.target.closest('form');
        if (form.classList.contains('truckArrivalAction')) {
            if (!requiredToClose(element.target)) {
                notification('Todos os elementos nescessários precisam ser preenchidos para este envio.');
                return false;
            } 
            let confirmMessage = element.target.querySelector('[confirm]').getAttribute('confirm');
            simpleDialog(confirmMessage , () => {
                element.target.submit();
            });
        }
    });

    const requiredToClose = (element) => {
        let requiredElements = element.querySelectorAll('.close-required');
        for (let reqEl of requiredElements) {
            if (reqEl.value === '') {
                return false;
            }
        }

        return true;
    }
}