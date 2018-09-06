document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('#automatic')) {
        let disabledInputs = document.querySelectorAll('[autocp]')

        for (let enabledInputs of disabledInputs) {
            enabledInputs.removeAttribute('disabled')
        }
    }

    document.addEventListener('blur', function (el) {

        if (el.target.hasAttribute('removeFrom')) {
            let inputValue = el.target.value
            let variable = eval(el.target.getAttribute('removeFrom'))
            var response = false

            for (let v of variable) {
                if (v.value == inputValue) {
                    response = true
                }
            }

            if (!response && el.target.value !== '') {
                el.stopImmediatePropagation()
                el.preventDefault()
                el.target.value = ''
                el.target.focus()
                alert(`Produto ${el.target.value} não é um produto valido`)
                return false
            }
            
        }

    }, true)
/
    document.addEventListener('focus', function (el) {

        if (el.target.hasAttribute('removeFrom')) {
            checkAvailable(el.target)
        }

        if (el.target.hasAttribute('autocp')) {
            $(el.target).autocomplete({
                lookup: eval(el.target.getAttribute('autocp')),
                triggerSelectOnValidInput: false,
                onSelect: function (sugestion) {
                    if (sugestion.selected == true) {
                        alert('não faz isso não meu amigo')
                    }
                }
            })
        }
    }, true)


    const checkAvailable = (target) => {
        let variable = eval(target.getAttribute('removeFrom'))
        let inputs = document.querySelectorAll('[cloneField] input[removeFrom]')
        let listOfValues = []

        for (i of inputs) {
            listOfValues.push(i.value)
        }

        for (let v of variable) {

            if (listOfValues.indexOf(v.value) !== -1) {
                v.selected = true
                continue
            }

            v.selected = false
        }
    }
})