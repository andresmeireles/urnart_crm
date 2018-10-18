// file to create various inputs masks
document.addEventListener('DOMContentLoaded', function () {
    //datas
    $(document).find('.date-mask').mask('00/00/0000')
    //cnpj
    $(document).find('.cnpj-mask').mask('00.000.000/0000-00')
    // cpf
    $(document).find('.cpf-mask').mask('000.000.000-00')
    // telefone celular com ddd
    $(document).find('.phone-with-ddd-br').mask('(00) 00000-0000')
    // cep
    $(document).find('.cep-mask').mask('00000-000')
    // money mask
    $(document).find('.money-mask').mask('000000000,00', {reverse: true})
})