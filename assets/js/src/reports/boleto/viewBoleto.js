if (document.querySelector('#show-titles')) {

    document.addEventListener('click', (el) => {

        if (el.target.hasAttribute('view')) {
            let rowData = document.querySelector(el.target.getAttribute('target'));
            let startLink = rowData.getAttribute('target');
            let idConsult = parseInt(rowData.querySelector('.id-row').innerText)
            simpleRequest(`/api${startLink}/${idConsult}`, 'GET', null, (response) => {
                drawBoletoView(response.data, `content${idConsult}`);
            })
        }

    });

    const drawBoletoView = (data, idContainer) => {
        let greatContainer = document.querySelector(`#${idContainer}`);
        greatContainer.innerHTML = '';

        let register = `
            <h3>Detalhes do titulo ${data.id}</h3><hr>
            <div class="row">
            <div class="col form-row">
                <div class="form-group col-md-6">
                    <label for="customerName">Cliente do Titulo</label>
                    <div class="form-control">${data.boleto_customer_owner}</div>
                </div>
                <div class="form-group col-md-4">
                    <label for="tittleNumber">N° do Titulo</label>
                    <div class="form-control">${data.boleto_number}</div>
                </div>
                <div class="form-group col-md-2">
                    <label for="installment">Numero da parcela</label>
                    <div class="form-control">${data.boleto_installment}</div>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col form-row">
                    <div class="form-group col">
                        <label for="vencimento">Data de Vencimento</label>
                        <div><p class="form-control">${moment(data.boleto_vencimento).format('L')}</p></div>
                    </div>
                    <div class="form-group col">
                        <label for="price">Valor do Titulo</label>
                        <div class="form-control">${new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(data.boleto_value)}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col form-row">
                    <div class="form-group col">
                        <label for="status">Cituação do Titulo</label>
                        <div><p class="form-control">${ statusType(data.boleto_status) }</p></div>
                    </div>
                    <div class="form-group col">
                        <label for="paymentDate">Data de pagamento</label>
                        <div class="form-control">${ typeof data.boleto_payment_date === 'undefined' ? 'PEDIDO NÂO PAGO' : boleto.boleto_payment_date }</div>
                    </div>
                </div>
            </div>`

            greatContainer.innerHTML = register;
    };

    const statusType = (status) => {
        let result = ''; 
        switch (status) {
            case '0':
                result = 'Não pago';
                break;
            case '1':
                result = 'Pago';
                break;
            case '2':
                result = 'Pago em atraso';
                break;
            case '3':
                result = 'Pagamento Provisionado';
                break;
            case '4':
                result = 'Pagamento Por conta';
                break
            default:
                result = 'Não Pago';
                break;
        }

        return result;
    };

};

