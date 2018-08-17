document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('sendvt')) {
            //el.target.setAttribute('disabled', '')
            el.preventDefault()

            var form = el.target.closest('form')
            
            if (!checkRequired(form)) {
                el.target.removeAttribute('disabled')
                return false
            } 

            genericSend(el, 'sendvt', 'PUT', function (response) {
                //location.reload()
            })
        }

        if (el.target.getAttribute('del')) {
            el.preventDefault()
            var link = rot(el.target.getAttribute('del'))
            simpleDialog('Tem certeza que deseja remover esse item?', () => {
                simpleRequest(link, 'DELETE', null, null, function () {
                    console.log('apafou pâe')
                })
            })
        }

    })

})