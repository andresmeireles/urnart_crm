document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('#automatic')) {
        let disabledInputs = document.querySelectorAll('[autocp]')

        for (let enabledInputs of disabledInputs) {
            enabledInputs.removeAttribute('disabled')
        }
    }

    document.addEventListener('blur', function (el) {

        if (el.target.hasAttribute('removeFrom')) {
            checkAvailable(el.target)
        }

    }, true)

    document.addEventListener('focus', function (el) {

        if (el.target.hasAttribute('removeFrom')) {
            checkAvailable(el.target)
        }

        if (el.target.hasAttribute('autocp')) {
            $(el.target).autocomplete({
                lookup: eval(el.target.getAttribute('autocp')),
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