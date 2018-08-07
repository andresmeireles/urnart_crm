// file to create various inputs masks
document.addEventListener('DOMContentLoaded', function () {
    //datas
    $('.date-mask').mask('00/00/0000')
    //cnpj
    $('.cnpj-mask').mask('00.000.000/0000-00')
    // cpf
    $('.cpf-mask').mask('000.000.000-00')
    // telefone celular com ddd

    $('.phone-with-ddd-br').mask('(00) 00000-0000')
    // cep
    $('.cep-mask').mask('00000-000')
})