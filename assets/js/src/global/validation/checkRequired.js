module.exports = function (form) {
    var requireds = form.querySelectorAll('.required')
    var result = true;

    if (document.querySelectorAll('small .text-danger')) {
        var removes = document.querySelectorAll('small.text-danger')
        for (var remove of removes) {
            remove.remove()
        }
    }

    for (var inputs of requireds) {
        var input = inputs.parentNode
        field = input.querySelector('.form-control')
        if (field.value == '') {
            input.insertAdjacentHTML('beforeend', `<small class="text-danger">Campo obrigatorio</small>`)
            result = false
            break
        }
    }
    
    return result
}