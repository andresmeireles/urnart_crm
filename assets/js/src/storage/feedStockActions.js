document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('sendvt')) {
            el.target.setAttribute('disabled', '')
            el.preventDefault()

            var form = el.target.closest('form')
            
            if (!checkRequired(form)) {
                el.target.removeAttribute('disabled')
                return false
            } 

            genericSend(el, 'sendvt', 'POST', function (response) {
                location.reload()
            })
        }

        if (el.target.hasAttribute('target-attr')) {
            var target = el.target.getAttribute('target-attr')
            var input = document.querySelector(`[target="${target}"]`)
            
            if (input.hasAttribute('disabled')) {
                input.removeAttribute('disabled')
            } else {
                input.setAttribute('disabled', '')
            }
        }

        if (el.target.hasAttribute('del')) {
            el.preventDefault()
            var link = rot(el.target.getAttribute('del'))
            alert(link)
            simpleDialog('Tem certeza que deseja remover esse item?', () => {
                simpleRequest(link, 'DELETE', null, function () {
                    window.location = '/storage/feedstock'
                })
            })
        }
    })

    document.addEventListener('change', function (el) {
        if (el.target.id == 'produto') {
            const amount = el.target.parentNode
            const input = amount.querySelector('#amount')
            input.removeAttribute('disabled')
            input.focus()
        }
    })

})