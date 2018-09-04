document.addEventListener('DOMContentLoaded', function () {
    
    document.addEventListener('click', function (el) {
        if (el.target.hasAttribute('mk-clone')) {
            el.preventDefault()
            var verifcation = true

            var field = el.target.closest('[clone-area]')
            field.querySelectorAll('[required]').forEach( (el) => {
                if (el.value == '') {
                    alert('preencher antes de criar outro campo')
                    verifcation = false
                }
            })
            
            // verification
            if (!verifcation) {
                return false
            }

            var hash = Math.floor(Math.random(9) * 100000)
            var id = field.closest('[cloneField]').getAttribute('cloneField')
            var clone = field.cloneNode(true)

            //verficactions
            clone.querySelectorAll('[name]').forEach( function (el) {
                if (el.tagName == 'INPUT') {
                    //clone.querySelector(`#${el.getAttribute('id')}`).value = ''
                    clone.querySelector(`[name="${el.getAttribute('name')}"]`).value = ''
                }

                var name = el.getAttribute('name')
                name = name.substring(name.indexOf('['))
                
                var newName = id + hash + name
                el.setAttribute('name', newName)
            }) 

            field.after(clone)

            return true
        }

        if (el.target.hasAttribute('rm-clone')) {
            el.preventDefault()

            //verification
            if (el.target.closest('[cloneField]').querySelectorAll('[clone-area]').length == 1 ) {
                alert('não podes remover esse campo')
                return false
            }

            var field = el.target.closest('[clone-area]')
            field.remove()

            return true
        }
    })
})