/**
 * 
 * @param {boolean} str 
 */
module.exports = function isNullOrWhiteSpace(str) {
    return (!str || str.length === 0 || /^\s*$/.test(str))
}