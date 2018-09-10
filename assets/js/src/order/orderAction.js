document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('#automaticOrder')) {
        let disabledInputs = document.querySelectorAll('[autocp]')

        for (let enabledInputs of disabledInputs) {
            enabledInputs.removeAttribute('disabled')
        }

        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('rm-clone')) {
                setTimeout(() => {
                   contabilize() 
                }, 100);
            }
        }, true)

        document.addEventListener('focus', function (el) {
            if (el.target.hasAttribute('autocp')) {

                if (el.target.closest('[clone-area]')) {
                    let disabledInputs = el.target.closest('[clone-area]').querySelectorAll('[disabled]')
                    
                    for (let di of disabledInputs) {
                        if (di.id === 'total') {
                            continue
                        }
                        di.removeAttribute('disabled')
                    }
                }

                $(el.target).autocomplete({
                    lookup: eval(el.target.getAttribute('autocp')),
                    triggerSelectOnValidInput: false,
                    onSelect: function (option) {
                        if (el.target.closest('[clone-area]')) {
                            let field = el.target.closest('[clone-area]')
                            field.querySelector('#cod').value = option.cod
                            field.querySelector('#prod-price').value = option.price
                            field.querySelector('#prod-qnt').value = 1
                            field.querySelector('#total').value = (1 * option.price)
                            contabilize()
                        } else {
                            let idClient = document.querySelector('#idClientName')
                            idClient.value = option.cod
                        }
                    }
                })
            }
        }, true)

        document.addEventListener('blur', function (el) {

            if (el.target.hasAttribute('autocp') && el.target.closest('[clone-area]')) {
                let disabledInputs = el.target.closest('[clone-area]').querySelectorAll('[input="text"]')

                for (let di of disabledInputs) {
                    if (di.id === 'total') {
                        continue
                    }

                    if (di.value === '') {
                        di.setAttribute('disabled', 'disabled')   
                    }
                }

                setTimeout(() => {
                    let value = el.target.value
                    let result = false

                    for (prod of product) {
                        if (prod.value == value) {
                            result = true
                            continue
                        }
                    }

                    if (!result) {
                        alert(`Produto ${value} não é um produto valido`)
                        el.stopImmediatePropagation()
                        el.preventDefault()
                        el.target.value = ''
                        return false
                    }

                }, 200)
            }

            if (el.target.id == 'prod-qnt' ) {
                let field = el.target.closest('[clone-area]')
                let price = field.querySelector('#prod-price').value
                field.querySelector('#total').value = (el.target.value * price)
                contabilize()
            }

            if (el.target.id == 'prod-price') {
                let field = el.target.closest('[clone-area]')
                let qnt = field.querySelector('#prod-qnt').value
                field.querySelector('#total').value = (el.target.value * qnt)
                contabilize()
            }

            if (el.target.id === 'freight' || el.target.id === 'discount') {
                contabilize()
            }

        }, true)
    }

    const contabilize = () => {
        let allProductsPrice = document.querySelector('#allProductsPrice')
        let allPrices = 0
        let prices = document.querySelectorAll('#total')

        let totalPrice = document.querySelector('#finalPrice')
        let freight = (document.querySelector('#freight').value === '') ? 0 : Number(document.querySelector('#freight').value)
        let discount = (document.querySelector('#discount').value === '') ? 0 : Number(document.querySelector('#discount').value)

        for (let p of prices) {
            p = Number(p.value)
            allPrices += p
        }

        allProductsPrice.innerHTML = allPrices
        totalPrice.innerHTML = (allPrices + freight) - discount
    }
})