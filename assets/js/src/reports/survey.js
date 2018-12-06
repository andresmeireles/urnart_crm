document.addEventListener('DOMContentLoaded', function () {
    
    if (document.getElementById('pills-surveys')) {
        
        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('run-save-action') || el.target.classList.contains('fa-check')) {
                const targetedLiElement = el.target.closest('li');
                let survey = targetedLiElement.querySelector('.content').getAttribute('data-src');
                let data = document.querySelector(survey);
                let form = new FormData(data);
                let userName = targetedLiElement.innerText;
                for (let ze of form) {
                    console.log(ze)
                }
                alert(userName);
            }

            if (el.target.hasAttribute("send-all-survey")) {
                const liElements = el.target.closest('ul').querySelectorAll('li');
                for (let liEl of liElements) {
                    liEl.querySelector('a[run-save-action]').click();
                }
            }
        })

    }

});