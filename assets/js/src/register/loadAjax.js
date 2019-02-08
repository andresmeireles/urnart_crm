/* 
 * Funcções de click para busca de infromações no banco de dados.
 */
document.addEventListener('click', (el) => {

    /**
     * Busca informações no banco de dados ao clique
     */
    if (el.target.hasAttribute('load-table')) {
        let tableForConsult = el.target.getAttribute('load-table');
        let url = `/register/get/${tableForConsult}`;
        
        simpleRequest(url, 'PATCH', null, (res) => {
            let data = res.data;
            for (let info of data) {
                drawFunction(tableForConsult, info);
            }
            return;
        });
    }
});

const drawFunction = (tableDataName, tableData) => {
    switch (tableDataName) {
        case 'pessoaJuridica':
            customerDraw('pessoaJuridica', tableData);
            break;
        default:
            console.log('não tem função meu amigo.');
            break;
    }
};

const customerDraw = (target, data) => {
    let tableBody = document.querySelector(`.${target}`);
    
    let trEl = document.createElement('tr');
    
    let tdEl = document.createElement('td');
    let tdText = document.createTextNode(data.razao_social);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);
    tdEl = document.createElement('td');
    
    tdText = document.createTextNode('');
    if (typeof data.proprietarios[0].pessoa_fisica.address.municipio !== 'undefined') {
        tdText = document.createTextNode(`${checkUndefined(data.proprietarios[0].pessoa_fisica.address.municipio.nome)}/${checkUndefined(data.proprietarios[0].pessoa_fisica.address.municipio.uf)}`);
    }
    
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);
    
    tdEl = document.createElement('td');
    tdText = document.createTextNode(data.cnpj);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);
    
    return tableBody.appendChild(trEl);
};