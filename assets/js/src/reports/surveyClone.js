document.addEventListener('click', (el) => {
    if (el.target.hasAttribute('cloneAlt')) {
        el.preventDefault();
        let surveyDiv = el.target.closest('#surveyClone')
        let elements = surveyDiv.querySelectorAll('.form-inline');
        let lastElement = elements[elements.length - 1];
        console.log('rapaz', lastElement);
    }
})