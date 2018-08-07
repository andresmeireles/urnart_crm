module.exports = function (number) {

    if (number == null) {
        return false
    }
    var phone = number

    var ddd = phone.substring(0, 2)
    var phoneNumber = phone.substring(3)

    return `(${ddd}) ${phoneNumber}`

}