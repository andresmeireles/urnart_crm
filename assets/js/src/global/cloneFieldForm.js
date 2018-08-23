document.addEventListener('DOMContentLoaded', function () {
    
    document.addEventListener('click', function (el) {
        if (el.target.hasAttribute('mk-clone')) {
            el.preventDefault()

            var field = el.target.closest('[clone-area]')
            var id = field.closest('[cloneField]').getAttribute('cloneField')

            var clone = field.cloneNode(true)

            field.after(clone)
        }
    })
})