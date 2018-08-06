module.exports = function (number) {
    if (number == null) {
        return false
    }

    var ddd = number.substring(0, 2)
    var number = number.substring(3)

    return `(${ddd}) ${number}`

}