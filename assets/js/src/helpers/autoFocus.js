document.addEventListener('focus', (el) => {

    if (el.target.classList.contains('calendar-selector')) {
        $(el.target).pickadate();
    }

}, true)