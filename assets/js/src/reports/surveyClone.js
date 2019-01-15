document.addEventListener('click', (el) => {
    if (el.target.hasAttribute('cloneAlt')) {
        el.preventDefault();
        let surveyDiv = el.target.closest('#surveyClone')
        let questNumber = surveyDiv.querySelector('[quest]').getAttribute('quest');
        runClone(surveyDiv, questNumber);
    }

    if (el.target.hasAttribute('removeAlt')) {
        let surveyDiv = el.target.closest('.form-inline');
        surveyDiv.remove();
    }

    if (el.target.hasAttribute('remove-quest')) {
        simpleDialog(`Deseja remover essa pergunta?`, () => {
            el.target.parentNode.remove();
            notification('Para persistir ação, salve as configurações na opção salvar.', 'warning');
        });
    }

})

const runClone = (elements, questNumber) => {
    let el = elements.querySelectorAll('.form-inline');
    let lastElement = el[el.length - 1];
    let alternativeNumber = el.length + 1 + '' + Math.floor(Math.random() * 100);
    lastElement.insertAdjacentHTML('afterend', 
    `<div class="form-inline py-1">
    <input type="hidden" input-number='true' value="${alternativeNumber}" quest="${questNumber}">
    <input type="text" class="form-control col-md-10" id="alternative" name="survey[questNumber][${questNumber}][alternative][${ alternativeNumber }]" />
    <button type="button" class="btn mx-1 btn-danger" removeAlt>
        <i class="fas fa-fw fa-times" removeAlt></i>
    </button>
    </div>`);
}