module.exports = function (selectElement) {
    const option = selectElement.querySelector(`[value="${selectElement.value}"]`)

    return option.text
}