module.exports = function (function = null)
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (el) => {
        if (el.target.getAttribute('send2')) {
            var url = el.target.getAttribute('send2')
            var formName = el.target.getAttribute('target')

            var form = document.querySelector(`#${formName}`)
            var formData = new FormData(form)


        }
    })
})