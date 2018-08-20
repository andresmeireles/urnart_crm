module.exports = function (date) {

    if (isNaN(date)) {
        return ''
    }

    var day = date.getDay() + 1
    var month = date.getMonth()
    var year = date.getFullYear()
    
    return `${day}/${month}/${year}`
}