document.addEventListener('click', function (el) {
    if (el.target.classList.contains('oneTimeClick')) {
        el.target.disabled = true;
        el.target.innerText = 'cadastrando...';
    }
});