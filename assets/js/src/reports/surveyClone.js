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
    
    //create a new question.
    if (el.target.hasAttribute('n-qtn')) {
        el.preventDefault();
        let element = el.target.closest('div');
        newQuestion(element);
    }
    
})

document.addEventListener('change', function (el) {
    el.preventDefault();
    if (el.target.value === 'radio') {
        let element = el.target.closest('.type');
        let alternativesField = element.querySelector('.editable');
        let index = el.target.closest('[q-num]').getAttribute('q-num');            
            alternativesField.innerHTML = `
            <legend>Alterntivas</legend>
            <div id="surveyClone">
                <input type="hidden" input-number='true' value="1" quest="${ index }">
                <div class="form-inline py-1">
                    <input type="text" class="form-control col-md-10" id="alternatives" name="survey[survey_question][${index}][alternatives][1]" value="" />
                    <button type="button" class="btn mx-1 btn-danger" removeAlt>
                        <i class="fas fa-fw fa-times" removeAlt></i>
                    </button>
                </div>
                
                <button type="button" class="btn btn-success my-2" cloneAlt>
                    <span class="fas fa-fw fa-plus" cloneAlt></span>
                </button>
            </div>
            `;
        } else if (el.target.value === 'text') {
            let element = el.target.closest('.type');
            let alternativesField = element.querySelector('.editable');
            let index = el.target.closest('[q-num]').getAttribute('q-num');   
            alternativesField.innerHTML = `
            <input type="hidden" class="default-alternative" name="survey[survey_question][${ index }][alternatives]" value="[]">
            `;
        }
    }
);

const newQuestion = (element) => {
    let index = document.querySelectorAll('.quest-number').length + 1;
    element.insertAdjacentHTML('afterend', 
    `<div  class="border border-dark my-2 p-2">
        <input type="hidden" class="quest-number" value="${index}">
        <div class="f-right badge badge-pill badge-danger cursor-decoration" remove-quest>excluir a pergunta</div>
        <div class="form-group">
            <label class="form-label"><b>Pergunta</b></label>
            <input type="text" class="form-control" name="survey[survey_question][${ index }][text]" value="" required />
        </div>
        <div class="form-group my-4 type" q-num="${index}"> 
            <label class="form-label"><b>Tipo de questionário</b></label>
            <select class="form-control" name="survey[survey_question][${ index }][type]" required>
                <option value="radio">Escolha</option>
                <option value="text" selected>Texto</option>
            </select>
            <div class="form-group p-1 editable">
                <input type="hidden" class="default-alternative" name="survey[survey_question][${ index }][alternatives]" value="[]">
            </div>
        </div>    
    </div>`
    );
}

const runClone = (elements, questNumber) => {
    let el = elements.querySelectorAll('.form-inline');
    let lastElement = el[el.length - 1];
    let alternativeNumber = el.length + 1 + '' + Math.floor(Math.random() * 100);
    lastElement.insertAdjacentHTML('afterend', 
    `<div class="form-inline py-1">
    <input type="hidden" input-number='true' value="${alternativeNumber}" quest="${questNumber}">
    <input type="text" class="form-control col-md-10" id="alternative" name="survey[survey_question][${questNumber}][alternatives][${ alternativeNumber }]" />
    <button type="button" class="btn mx-1 btn-danger" removeAlt>
    <i class="fas fa-fw fa-times" removeAlt></i>
    </button>
    </div>`);
}