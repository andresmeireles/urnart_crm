
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
                
                for (let e of element) {
                    e.value = response.data.feedstock_inventory.stock
                    e.innerHTML = response.data.feedstock_inventory.stock
                }

            })

            const amount = el.target.parentNode.parentNode
            const input = amount.querySelector('#amount')
            //input.removeAttribute('disabled')
            input.focus()
        }
    })

    document.addEventListener('blur', function (el) {
        if (el.target.id == 'amount') {
            let val = Number(el.target.value)
            let input = el.target.closest('[clone-area]').querySelector('#actual-stock')
            let inputValue = Number(el.target.closest('[clone-area]').querySelector('.d-none[actual-amount]').value)

            if (val == 0) {
                el.target.value = ''
                return false
            }

            if (val > inputValue) {
                el.target.classList.add('is-invalid')
                var small = el.target.parentNode.querySelector('small')
                small.classList.remove('d-none')
                el.target.value = ''
                alert('Valor muito grande')
                return false
            } 

            if (el.target.classList.contains('is-invalid')) {
                el.target.classList.remove('is-invalid')
                var small = el.target.parentNode.querySelector('small')
                small.classList.add('d-none')

                input.value = (inputValue - val)
            }
        }
    }, true)

    document.addEventListener('click', function (el) {

        if (el.target.hasAttribute('mk-clone')) {
            el.preventDefault
            if (el.target.parentNode.parentNode.querySelector('#amount').value != '') {
                previous.push(now)
            }
        }

        if (el.target.getAttribute('sendvt')) {
            //el.target.setAttribute('disabled', '')
            el.preventDefault()

            var form = el.target.closest('form')
            
            if (!checkRequired(form)) {
                el.target.removeAttribute('disabled')
                return false
            } 

            genericSend(el, 'sendvt', 'POST', function (response) {
                console.log(response)
                //location.reload()
            })
        }

        if (el.target.hasAttribute('send-in')) {
            el.preventDefault()
            genericSend(el, 'send-in', 'POST', function (response) {
                
                if (response.status == 203) {
                    alert(response.data)
                    return false
                }
                
                window.location.reload()
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
