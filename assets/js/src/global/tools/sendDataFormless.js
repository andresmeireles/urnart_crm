/**
 * Enviar informações para o backend.
 * 
 * sendFl - Link do para enviar requisição dentro do elemento target dentro de elemento
 * 
 * @param string sendFl 
 */
module.exports = function (sendFl) {
    //let link = sendFl.getAttribute('send-fl')
    const areaId = sendFl.getAttribute('target')
    let area = document.querySelector(`#${areaId}`)

    let items = area.querySelectorAll('[sd]')
    let dataObject = {}
    let c = 0
    for (var i of items) {

        if (i.hasAttribute('name') && i.name == 'date') {
            dataObject['date'] = i.value
            continue
        }

        let data = {}

        for (var input of i.querySelectorAll('input, select')) {    
            data[input.id] = input.value
        }
        
        dataObject[c] = data

        c++
    }

    return dataObject
}