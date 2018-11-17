document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('#automaticOrder') || document.querySelector('tbody#orders')) {
        let disabledInputs = document.querySelectorAll('[autocp], [formCp]')

        for (let enabledInputs of disabledInputs) {
            enabledInputs.removeAttribute('disabled')
        }

        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('rm-clone')) {
                setTimeout(() => {
                   contabilize()
                }, 100);
            }

            if (el.target.hasAttribute('reserve')) {
              let queryLink = el.target.getAttribute('reserve')
              window.location = `/order/action/reserve?${queryLink}`
            }

            if (el.target.hasAttribute('reopen')) {
                let queryLink = el.target.getAttribute('reopen')
                window.location = `/order/action/order/radiate?${queryLink}`
            }

            if (el.target.hasAttribute('close')) {
                let queryLink = el.target.getAttribute('close')
                simpleDialog("Tem certeza que deseja fechar esse pedido?<br><i class='text-danger'>(essa ação não poderá ser desfeita)</i>", () => {
                    window.location = `/order/action/order/close?${queryLink}`
                })
            }

            if (el.target.hasAttribute('del')) {
              el.preventDefault()
              var link = rot(el.target.getAttribute('del'))
              simpleDialog('Tem certeza que deseja remover esse item?', () => {
                  simpleRequest(link, 'DELETE', null, function (response) {
                      window.location = response.headers['redirect-route']
                  })
              })
            }

            if (el.target.hasAttribute('print')) {
                el.preventDefault()
                let value = el.target.getAttribute('print')
                let link = el.target.href
                if (value == '') {
                    return false
                }
                window.open(`${link}?${value}`)
            }

        }, true)

        document.addEventListener('focus', function (el) {

            if (el.target.hasAttribute('transcp')) {
                $(el.target).autocomplete({
                    lookup: eval(el.target.getAttribute('transcp')),
                    triggerSelectOnValidInput: false,
                    onSelect: function (option) {
                        let cod = Number(option.cod)
                        if (Number.isInteger(cod)) {
                            document.querySelector('#transp').value = option.cod
                            document.querySelector('#port').value = option.port
                            return true
                        }
                    }
                })
            }

            if (el.target.id === 'formPg') {
                $(el.target).autocomplete({
                    lookup: eval(el.target.getAttribute('formCp')),
                    triggerSelectOnValidInput: false,
                    onSelect: function (option) {
                        let cod = Number(option.cod)
                        if (Number.isInteger(cod)) {
                            document.querySelector('#formPgNumber').value = cod;
                        }
                        return true
                    }
                })
            }

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
                            field.querySelector('#prod-price').value = numeral(option.price).format('0.00')
                            //field.querySelector('#stock').value = option.amount
                            field.querySelector('#prod-qnt').value = 1
                            field.querySelector('#total').value = numeral((1 * option.price)).format('0.00')
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

            if (el.target.hasAttribute('transcp')) {
                setTimeout(() => {
                    let value = el.target.value
                    let result = false
                    let items = eval(el.target.getAttribute('transcp'))
                    for (item of items) {
                        if (item.value == value) {
                            result = true
                            continue
                        }
                    }
                    if (!result) {
                        document.querySelector('#transp').value = ''
                    }
                }, 200)
            }

            if (el.target.hasAttribute('autocp') && el.target.closest('[clone-area]')) {
                let items = eval(el.target.getAttribute('autocp'))
                isValid(el, items, 200)
                let disabledInputs = el.target.closest('[clone-area]').querySelectorAll('[input="text"]')
                for (let di of disabledInputs) {
                    if (di.id === 'total') {
                        continue
                    }
                    if (di.value === '') {
                        di.setAttribute('disabled', 'disabled')
                    }
                }
            }

            if (el.target.id == 'prod-qnt' ) {
              let field = el.target.closest('[clone-area]')
              //let stock = field.querySelector('#stock').value
              let value = field.querySelector('#prod-price').value
              value = Number(value.replace(',','.'))
              let qnt = Number(el.target.value)
              //field.querySelector('#total').innerHTML = numeral((value * qnt)).format('0.00')
              field.querySelector('#total').value = numeral((value * qnt)).format('0.00')
              contabilize()
              contabilizeInstallmentValue()
            }

            if (el.target.id == 'prod-price') {
                let field = el.target.closest('[clone-area]')
                let qnt = Number(field.querySelector('#prod-qnt').value)
                let value = el.target.value
                    value = Number(value.replace(',','.'))
                field.querySelector('#prod-price').value = numeral(value).format('0.00')
                field.querySelector('#total').value = numeral((value * qnt)).format('0.00')
                contabilize()
                contabilizeInstallmentValue()
            }

            if (el.target.id === 'freight' || el.target.id == 'inst' || el.target.id === 'discount') {
                contabilize()
                contabilizeInstallmentValue()
            }

            if (el.target.id === 'formPg') {
                let paymentTypeName = el.target.value;
                let items = window[el.target.getAttribute('formCp')];
                let paymentType = document.querySelector('#formPgNumber');
                let installment = document.querySelector('#inst');
                let isPlotable = false;
                isValid(el, items, 200);
                paymentType.value = '';
                installment.value = '';
                for (let payment of items) {
                    if (payment.value === paymentTypeName) {
                        document.querySelector('#formPgNumber').value = payment.cod;
                        isPlotable = payment.plot;
                        break;
                    }
                }
                if (isPlotable) {
                    installment.removeAttribute('disabled');
                } else {
                    installment.value = 1;
                    installment.setAttribute('disabled', '');
                    contabilize();
                    contabilizeInstallmentValue();
                }
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
            let price = p.value;
            price = price.replace('.','');
            price = price.replace(',','.');
            allPrices += Number(price);
        }
        allProductsPrice.innerHTML = numeral(allPrices).format('0.00')
        totalPrice.innerHTML = numeral((allPrices + freight) - discount).format('0.00')
    }

    const contabilizeInstallmentValue = () => {
        setTimeout(() => {
            type = document.querySelector('#formPg').value
            if (type == 'A vista' || type == '') {
                document.querySelector('#installmentPrice').innerHTML = numeral(value).format('0.00')
                return true
            }
            let totalPrice = (document.querySelector('#allProductsPrice').innerHTML == '0,00' && document.querySelector('#allProductsPrice').innerHTML == '') ? 0 : document.querySelector('#allProductsPrice').innerHTML
            totaflPrice = totalPrice.replace('.', '')
            totalPrice = totalPrice.replace(',', '.')
            totalPrice = Number(totalPrice)
            let discount = (document.querySelector('#discount').value == 0 && document.querySelector('#discount').value == '') ? 0 : Number(document.querySelector('#discount').value)
            let installment = (document.querySelector('#inst').value == '' ? 1 : Number(document.querySelector('#inst').value))
            let value = (totalPrice - discount) / installment
            document.querySelector('#installmentPrice').innerHTML = numeral(value).format('0.00')
        }, 200);
    }

    const isValid = (el, items, seconds) => {
        setTimeout(() => {
            let value = el.target.value
            let errorValue = null;
            let result = false
            for (let item of items) {
                if (item.value == value) {
                    result = true;
                    continue;
                }
                errorValue = item.value;
            }
            if (!result) {
                alert(`Produto/Item ${errorValue} não é um produto valido`)
                el.stopImmediatePropagation()
                el.preventDefault()
                el.target.value = ''
                return false
            }
        }, seconds)
    }
})
