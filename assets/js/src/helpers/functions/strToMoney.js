/**
 * Convert a specific string to money in operations tha require numbers
 */
module.exports = (money) => {
    var onlyNumberValue = money.split("R$").pop().trim();
    if (onlyNumberValue.split(',')[1] === '00') {
        let finalValue = onlyNumberValue.split(',')[0].replace('.', '');
        return parseFloat(finalValue);
    }
}