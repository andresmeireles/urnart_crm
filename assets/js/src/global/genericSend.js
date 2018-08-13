module.exports = function (element, domEl, httpMethod = 'POST', func = null) {
    var btn = element.target 
    var url = btn.getAttribute(domEl)
    var formId = btn.closest('form').getAttribute('id')

    var form = document.querySelector(`#${formId}`)

    var formData = new FormData(form)

    simpleRequestForm(url, httpMethod, formData, (response) => {
        func(response)
    })
}