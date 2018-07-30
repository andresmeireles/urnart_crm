//file with single input
document.addEventListener('DOMContentLoaded', function () {

    if (document.querySelector('[cloneableField]')) {

        checkEnable();

        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('add-btn')) {
                el.preventDefault()
                var appendAfter = el.target.closest('[clone-area]')
                var field = el.target.closest('[clone-field]')
                console.log(field, appendAfter)
                var cloneField = field.cloneNode(true)
                
                cloneField.querySelectorAll('input').forEach(function (el) {
                    if (el.type == 'hidden') {
                        el.value = el.value
                    }
                    el.value = ''
                })

                field.after(cloneField)

                checkEnable()
            } 
        })

    }

    function checkEnable (eement) {
        document.querySelectorAll('[clone-area]').forEach(function (el) {
            if (el.querySelectorAll('[rmv-btn]').length == 1) {
                el.querySelector('.btn-danger').setAttribute('disabled', 'disabled')
            } else {
                el.querySelectorAll('.btn-danger').forEach( function (el) {
                    el.removeAttribute('disabled')
                })
            }
        })
    }

});