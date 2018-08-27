document.addEventListener('DOMContentLoaded', function () {
    const previous = []
    let now = ''

    document.addEventListener('change', function (el) {
        if (el.target.id == 'produto') {
            
            now = el.target.value
            
            //criar função que compare valores
            if (previous.indexOf(now) != -1) {
                alert(`Produto ${now} ja foi incluido`)
                el.target.value = ''
                return false
            }

            const amount = el.target.parentNode.parentNode
            const input = amount.querySelector('#amount')
            //input.removeAttribute('disabled')
            input.focus()
        }

        if (el.target.id == 'produto-out') {
            now = el.target.value

            //criar função que compare valores
            if (previous.indexOf(now) != -1) {
                alert(`Produto ${now} ja foi incluido`)
                el.target.value = ''
                return false
            }

            simpleRequest(`/register/get?entity=feedstock&id=${el.target.value}`, 'GET', null, function (response) {
                let element = el.target.closest('[clone-area]').querySelectorAll('[actual-amount]')
                
                for (var e of element) {
                    e.innerHTML = response.data.feedstock_inventory.stock
                    e.value = response.data.feedstock_inventory.stock
                }
                console.log(response)
            })

            const amount = el.target.parentNode.parentNode
            const input = amount.querySelector('#amount')
            //input.removeAttribute('disabled')
            input.focus()
        }
    })

    document.addEventListener('click', function (el) {

        if (el.target.hasAttribute('mk-clone')) {
            el.preventDefault
            if (el.target.parentNode.parentNode.querySelector('#amount').value != '') {
                previous.push(now)
            }
        }

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

        if (el.target.hasAttribute('send-in')) {
            el.preventDefault()
            genericSend(el, 'send-in', 'POST', function (response) {
                window.location.reload()
                console.log('sucesso')
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
            simpleDialog('Tem certeza que deseja remover esse item?', () => {
                simpleRequest(link, 'DELETE', null, function () {
                    window.location = '/storage/feedstock'
                })
            })
        }
    })

})