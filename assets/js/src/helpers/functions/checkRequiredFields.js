module.exports = function (pieceOfFormDOM) {
    let requiredFields = pieceOfFormDOM.querySelectorAll('[required]');

    for (let reqField of requiredFields) {
        if (reqField.value === '') {
            return false;
        }
    }

    return true;
}
