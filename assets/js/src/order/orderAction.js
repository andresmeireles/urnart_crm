document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('#automatic')) {
        let disabledInputs = document.querySelectorAll('[autocp]')

        for (let enabledInputs of disabledInputs) {
            enabledInputs.removeAttribute('disbaled')
        }
    }

    document.addEventListener('blur', function (el) {
        console.log(el)
    }, true)

    document.addEventListener('focus', function (el) {
        if (el.target.hasAttribute('autocp')) {
            $(el.target).autocomplete({
                lookup: eval(el.target.getAttribute('autocp')),
            })
        }
    }, true)
})