document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('send')) {
            el.preventDefault()
            var form = document.getElementById(el.target.getAttribute('target'))
            var fieldsRequired = form.querySelectorAll('.required')
            
            fieldsRequired.forEach(function (element) {
                var name = element.getAttribute('for')
                var required = document.getElementById(name)
                var value = required.value
                
                if (value == '') {
                    required.classList.add('is-invalid')
                    required.insertAdjacentHTML('afterend', `<small class="text-danger">Campo obrigarotio</small>`)
                    return false
                }
            })
            
            var link = el.target.getAttribute('send')
            var dataTarget = el.target.getAttribute('target')
            var form = document.getElementById(dataTarget)
            var data = new FormData(form)
            
            /**
            axios({
                method: 'post',
                url: link,
                data: data
            })
            .then(function (response) {
                console.log(response.data);
            }) */

            simpleRequestForm(link, 'post', data, function (response) {
                console.log(response.data);
            })
        }
    })

    document.addEventListener('change', function (el) {
        
        if (el.target.id == 'estado') {
            var stateValue = el.target.value
            var entity = el.target.getAttribute('target')
            var url = `/register/get/criteria/${entity}`
            var select = document.querySelector('#' + entity)

            select.innerHTML = ''
            var selected = document.createElement('option')
            selected.text = 'Selecione'
            selected.setAttribute('selected', '')
            selected.setAttribute('disabled', '')
            select.add(selected)

            simpleRequest(url, 'POST', stateValue, function(response) {
                var optData = response.data
                for (var opt of optData) {
                    var option = document.createElement('option')
                    option.value = opt.id
                    option.text = opt.nome
                    select.add(option)
                } 
            }, 'idUf')
        } 
               
    })
});