document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('sendvt')) {
            el.preventDefault()
            var form = el.target.closest('form')
            
            if (!checkRequired(form)) {
                return false
            } 

            genericSend(el, 'sendvt', 'POST', function (response) {
                console.log(response)
            })
        }
    })

})