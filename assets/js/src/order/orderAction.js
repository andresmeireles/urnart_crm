document.addEventListener('DOMContentLoaded', function () {

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