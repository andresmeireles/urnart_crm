/*************************************
 ************* CHECKMASK *************
 *************************************
 ***** FUNCTION NAME = checkMask *****
 *************************************/
module.exports = function (pieceOfDom) {
	//date
    $(pieceOfDom).find('.date-mask').mask('00/00/0000')
    //cnpj
    $(pieceOfDom).find('.cnpj-mask').mask('00.000.000/0000-00')
    // cpf
    $(pieceOfDom).find('.cpf-mask').mask('000.000.000-00')
    // telefone celular com ddd

    $(pieceOfDom).find('.phone-with-ddd-br').mask('(00) 00000-0000')
    // cep
    $(pieceOfDom).find('.cep-mask').mask('00000-000')
    // money
    $(pieceOfDom).find('.money-mask').mask('000000000,00', {reverse: true})
}