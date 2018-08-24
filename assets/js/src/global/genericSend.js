module.exports = function (element, domEl, httpMethod = 'POST', func = null) {
    var btn = element.target 
    var url = btn.getAttribute(domEl)
    var form = btn.closest('form')
    
    var formData = new FormData(form)
    
    simpleRequestForm(url, httpMethod, formData, (response) => {
        func(response)
    })
}