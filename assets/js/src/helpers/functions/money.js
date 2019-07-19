/**
 * money
 * 
 * Retorna valor float convertido em dinheiro.
 * 
 * @param {float}  floatInput valor para conversão.
 * @param {string} type       local da formatação do numero.
 * @param {string} currency   tipo de formatação para moeda. 
 *
 * @returns {String}
 */
module.exports = (floatInput, type = 'pt-BR', currency = 'BRL') => {
    numberToFormat = isNaN(floatInput) ? 0 : floatInput;
    return new Intl.NumberFormat(type, {style: 'currency', currency: currency})
    .format(numberToFormat);
}