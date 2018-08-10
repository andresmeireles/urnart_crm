document.addEventListener('DOMContentLoaded', function () {

    document.addEventListener('click', function (el) {
        
        if (el.target.getAttribute('send')) {
            el.preventDefault()
            var form = document.getElementById(el.target.getAttribute('target'))
            var fieldsRequired = form.querySelectorAll('.required')
            var response = true
            
            fieldsRequired.forEach(function (element) {
                var name = element.getAttribute('for')
                var required = document.getElementById(name)
                var value = required.value
                
                if (value == '') {
                    el.preventDefault()
                    required.classList.add('is-invalid')
                    required.insertAdjacentHTML('afterend', `<small class="text-danger">Campo obrigarotio</small>`)
                    response = false;
                }
            })
            
            if (!response) {
                return false
            }
            
            var link = el.target.getAttribute('send')
            var dataTarget = el.target.getAttribute('target')
            var form = document.getElementById(dataTarget)
            var data = new FormData(form)
            
            simpleRequestForm(link, 'post', data, function () {
                location.reload()
            })
        }
        
        if (el.target.getAttribute('send-update')) {
            el.preventDefault()
            var doc = document.getElementById('dynamic-created-element')
            var id = el.target.getAttribute('send-update')
            
            var form = doc.querySelector(`#${el.target.getAttribute('target')}`)
            var fieldsRequired = form.querySelectorAll('.required')
            var response = true
            
            fieldsRequired.forEach((element) => {
                var name = element.getAttribute('for')
                var required = doc.querySelector(`#${name}`)
                var value = required.value
                if (value == '') {
                    el.preventDefault()
                    required.classList.add('is-invalid')
                    required.insertAdjacentHTML('afterend', `<small class="text-danger">Campo obrigarotio</small>`)
                    response = false;
                }
            })
            
            if (!response) {
                return false
            }
            
            var link = `/person/action/${id}`
            var data = new FormData(form)
            
            simpleRequestForm(link, 'POST', data, function (response) {
                window.location = `/person/customer`
            })
        }
        
        if (el.target.getAttribute('view')) {
            drawForm(el, 'pessoaJuridica')
            return false
        }
        
        if (el.target.getAttribute('edit')) {
            el.preventDefault()
            var id = el.target.getAttribute('edit')
            drawForm(el, 'pessoaJuridica', 'edit')
        }
        
        if (el.target.getAttribute('remove')) {
            el.preventDefault()
            var id = el.target.getAttribute('remove')
            simpleDialog('Tem certeza que deseja remover esse item? Essa ação não pode ser desfeita!', function () {
                var url = `/person/action/${id}`
                
                simpleRequestForm(url, 'DELETE', null, function () {
                    window.location = `/person/customer`
                })
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
    
    function drawForm(element, entity, type = 'view') {
        var id = element.target.getAttribute(type)
        simpleRequestForm(`/register/get?entity=${entity}&id=${id}`, 'GET', null, function (response) {
            var data = customerTemplate(response.data, type)
            document.querySelector('body').insertAdjacentHTML('beforeend', data)
            
            $.fancybox.open({
                src: '#dynamic-created-element',
                type: 'inline',
                opts: {
                    afterLoad: function () {
                        var el = document.getElementById('dynamic-created-element')
                        el.classList.remove('d-none')
                    },
                    afterClose: function () {
                        var el = document.getElementById('dynamic-created-element')
                        el.remove()
                    }
                }
            })
            
            checkMask(document)
        })
    }
    
});