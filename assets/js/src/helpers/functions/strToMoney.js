/**
 * Convert a specific string to money in operations tha require numbers
 */
module.exports = (str) => {
    if (str === '') {
        return 0;
    }
    var onlyNumberValue = str.split("R$").pop().trim();
    if (onlyNumberValue.split(',')[1] === '00') {
        let finalValue = onlyNumberValue.split(',')[0].replace('.', '');
        console.log('passou aqui também');
        return parseFloat(finalValue);
    }
    onlyNumberValue.replace(',', '.');
    console.log('eis aqui');
    return onlyNumberValue;
}