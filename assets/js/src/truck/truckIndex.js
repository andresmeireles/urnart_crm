if (document.querySelector('#truckShowReports')) {
    document.addEventListener('click', (element) => {
        if (element.target.classList.contains('generatorReports')) {
            let clickableElements = element.target.closest('div');
            let reportsToPrint = clickableElements.querySelectorAll('.generatePrintReport');

            for (let item of reportsToPrint) {
                console.log(`Você apertou no elemento com link ${item.innerHTML}`);
                item.dispatchEvent(new MouseEvent('click', {
                        "view": window,
                        "bubbles":true,
                        "cancelable":true
                }));
            }
        }
    }, true);
}