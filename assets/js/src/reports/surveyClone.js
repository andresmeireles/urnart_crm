document.addEventListener('click', (el) => {
    if (el.target.hasAttribute('cloneAlt')) {
        el.preventDefault();
        let x = el.target.closest('#surveyClone')
        console.log('rapaz', x)
    }
})