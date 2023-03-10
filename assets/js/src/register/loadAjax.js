import { throws } from "assert";

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
            document.querySelector(`.${tableForConsult}`).innerHTML = '';
            for (let info of data) {
                drawFunction(tableForConsult, info);
            }
            return;
        });
    }
});

/**
 * drawFunction => seleciona função de desenho da tabela.
 * 
 * @param {string} tableDataName nome da tabela a ser desenhada.
 * @param {json} tableData dados a serem inclusos na tabela.
 */
const drawFunction = (tableDataName, tableData) => {
    let tableBody = document.querySelector(`.${tableDataName}`);
    let trEl = '';

    switch (tableDataName) {
        case 'pessoaJuridica':
            trEl = customerDraw(tableData);
            break;
        case 'departament':
        case 'unit':
            trEl = departamentAndUnitDraw(tableData, tableDataName);
            break;
        case 'paymentType':
            trEl = paymentTypeDraw(tableData, tableDataName);
            break;
        case 'transporter':
            trEl = transporterDraw(tableData, tableDataName);
            break;
        case 'modelName':
            trEl = modelDraw(tableData, tableDataName);
            break;
        default:
            console.log('não tem função meu amigo.');
            throw Error(`Não ha função para ação ${tableDataName.toUpperCase()}`);
    }

    return tableBody.appendChild(trEl);
};

const removeButton = (table, rowId) => {
    let removeBadge = document.createElement('i');
    removeBadge.classList.add('badge', 'badge-danger', 'badge-pill', 'cursor-decoration', 'remover');
    let txt = document.createTextNode('remove');
    removeBadge.setAttribute('target-table', table);
    removeBadge.setAttribute('row-id', rowId);
    removeBadge.appendChild(txt);

    return removeBadge;
};

/**
 * customerDraw => carrega dados da tabela de clientes.
 * 
 * @param {string} target tabela onde serão desenhados os elementos carregados do banco de dados
 * @param {json} data dados a serem inclusas no desenho da tabela
 */
const customerDraw = (data) => {    
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

    return trEl;
};


const departamentAndUnitDraw = (data, table) => {
    let trEl = document.createElement('tr');
    
    let tdEl = document.createElement('td');
    let tdText = document.createTextNode(data.name);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    let rmvBtn = removeButton(table, data.id);
    tdEl.appendChild(rmvBtn);
    trEl.appendChild(tdEl);

    return trEl;
};

const paymentTypeDraw = (data, table) => {
    let trEl = document.createElement('tr');

    let tdEl = document.createElement('td');
    let tdText = document.createTextNode(data.name);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    let plotable = data.plot ? 'Sim' : 'Não';
    tdEl = document.createElement('td');
    tdText = document.createTextNode(plotable);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    let rmvBtn = removeButton(table, data.id);
    tdEl.appendChild(rmvBtn);
    trEl.appendChild(tdEl);

    return trEl;
};

const transporterDraw = (data, table) => {
    let trEl = document.createElement('tr');

    let tdEl = document.createElement('td');
    let tdText = document.createTextNode(data.name);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    let port = data.port ? data.port : '';
    tdEl = document.createElement('td');
    tdText = document.createTextNode(port);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    let rmvBtn = removeButton(table, data.id);
    tdEl.appendChild(rmvBtn);
    trEl.appendChild(tdEl);

    return trEl;
};

const modelDraw = (data, table) => {
    let trEl = document.createElement('tr');

    let tdEl = document.createElement('td');
    let tdText = document.createTextNode(data.name);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    tdText = document.createTextNode(data.height);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    let specificity = typeof data.specificity === 'undefined' ? '' : data.specificity;
    tdText = document.createTextNode(specificity);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    tdText = document.createTextNode(data.suggested_price);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    let colorString = '';
    for (let color of data.colors) {
        colorString = colorString +  color + ', ';
    }
    let colorString2 = colorString.substr(',', colorString.lastIndexOf(','));
    tdEl = document.createElement('td');
    tdText = document.createTextNode(colorString2);
    tdEl.appendChild(tdText);
    trEl.appendChild(tdEl);

    tdEl = document.createElement('td');
    let rmvBtn = removeButton(table, data.id);
    tdEl.appendChild(rmvBtn);
    trEl.appendChild(tdEl);

    return trEl;
};