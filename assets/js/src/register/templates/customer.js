module.exports = function (data, type = 'view') {
    var pessoa = data.proprietarios[0].pessoa_fisica
    var endereco = pessoa.address
    var emails = pessoa.emails
    var phone = pessoa.phones
    var states = simpleRequest('/register/get/estado', 'post', null, function (respose) {
        return Response.data
    })
    var cities = simpleRequest('/register/get/municipio', 'post', null, function (respose) {
        return Response.data
    })
    
    var string = `<div id="dynamic-created-element" class="col-md-11 py-4 d-none">
    <div class="card">
    <div class="card-header text-center">
    <span style="font-size: 25px">${ (type == 'view') ? 'Visualizar' : 'Editar'} Cliente</span>
    </div>
    <div class="card-body">`
    
    if (!type == 'view') {
        string += `<form method="post" action="/person/edit/person" id="customer-form">`
    }
    
    string += `<fieldset class="p-1 border border-light my-1">
    <legend class="bg-light">
    &nbsp;Informações da Empresa
    </legend>
    <div class="px-1">
    <div class="row">`
    
    if (type == 'view') {
        string += `<div class="form-group col-md-6">
        <label for="razaoSocial" class="required">Razão Social</label>
        ${data.razao_social}
        </div>
        <div class="form-group col-md-6">
        <label for="firstName">Nome Fantasia</label>
        ${data.nome_fantasia}
        </div>
        </div>
        </div>`
    } else {
        string += `<div class="form-group col-md-6">
        <label for="razaoSocial" class="required">Razão Social</label>
        <input type="text" class="form-control" name="customer[razaoSocial]" id="razaoSocial" placeholder="Razão Social" required="required" value="${data.razao_social}">
        </div>
        <div class="form-group col-md-6">
        <label for="firstName">Nome Fantasia</label>
        <input type="text" class="form-control" name="customer[nomeFantasia]" id="nomeFantasia" placeholder="Nome Fantasia" value="${data.nome+fantasia}">
        </div>
        </div>
        </div>`
    }
    
    string += `</fieldset>
    <fieldset class="p-1 border border-light my-1">
    <legend class="bg-light">&nbsp;Informações fiscais</legend>
    <div class="px-1">
    <div class="row">`
    
    if (type == 'view') {
        string += `<div class="form-group col-md-4">
        <label for="firstName">CNPJ</label>
        ${data.cnpj}
        </div>
        <div class="form-inline">
        <label for="situcaoCadastral">Situação Cadastral</label>
        ${data.situacao_cadastral}
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
        <label for="firstName">Inscrição Estadual</label>
        ${inscricao_estadual}
        </div>
        <div class="form-group col-md-2">
        <label for="birthData">
        <small>Data de Fundação</small>
        </label>
        ${data_de_fundacao}
        </div>
        `
    } else {
        string += `<div class="form-group col-md-4">
        <label for="firstName">CNPJ</label>
        <input type="text" class="form-control cnpj-mask" name="customer[cnpj]" id="cnpj" placeholder="Inserir apenas numeros" value=${data.cnpj}>
        </div>
        <div class="form-inline">
        <label for="situcaoCadastral">Situação Cadastral</label>
        <div class="input-group mx-1">
        <div class="input-group-prepend">
        <div class="input-group-text">
        <input type="radio" class="form-check" name="customer[situacaoCadastral]" id="contribuente" value="1" required
        ${data.situacao_cadastral == 1 ? 'selected' : ''}>
        </div>
        </div>
        <div type="text" class="form-control">contribuente</div>
        </div>
        <div class="input-group mx-1">
        <div class="input-group-prepend">
        <div class="input-group-text">
        <input type="radio" class="form-check" name="customer[situacaoCadastral]" id="contribuente" value="2" required ${data.situacao_cadastral == 2 ? 'selected' : ''}>
        </div>
        </div>
        <div type="text" class="form-control">não contribuente</div>
        </div>
        <div class="input-group mx-1">
        <div class="input-group-prepend">
        <div class="input-group-text">
        <input type="radio" class="form-check" name="customer[situacaoCadastral]" id="contribuente" value="3" required ${data.situacao_cadastral == 3 ? 'selected' : ''}>
        </div>
        </div>
        <div type="text" class="form-control">Isento</div>
        </div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
        <label for="firstName">Inscrição Estadual</label>
        <input type="text" class="form-control" name="customer[inscricaoEstadual]" id="inscricaoEstadual" placeholder="Inserir apenas numeros" value="${inscricao_estadual}">
        </div>
        <div class="form-group col-md-2">
        <label for="birthData">
        <small>Data de Fundação</small>
        </label>
        <input type="text" class="form-control numbers-only date-mask" name="customer[fondationDate]" id="birthdate" placeholder="Data" value=${data_de_fundacao}>
        </div>
        `
    }
    
    string += `</div>
    </div>
    </fieldset>
    
    <fieldset class="p-1 my-1 border border-light">
    <legend class="bg-light">
    &nbsp;Informações de Proprietários
    <span class="btn btn-primary btn-sm float-right m-1">
    <i class="fas fa-fw fa-plus"></i>
    </span>
    </legend>
    <div class="px-1">
    <div class="row">`
    
    if (type == 'view') {
        string += `<div class="form-group col-md-6">
        <label for="firstName" class="required">Primeiro Nome</label>
        ${pessoa.first_name}
        </div>
        <div class="form-group col-md-6">
        <label for="firstName">Ultimo Nome</label>
        ${pessoa.last_name}
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
        <label for="firstName">Cpf</label>
        ${pessoa.cpf}
        </div>
        <div class="form-group col-md-4">
        <label for="firstName">RG (Registro Geral)</label>
        ${pessoa.rg}
        </div>
        <div class="form-group col-md-2">
        <label for="birthData">
        <small>Data de Nascimento</small>
        </label>
        ${pessoal.birth_date}
        </div>
        <div class="form-group col-md-2">
        <label for="firstName">Genero</label>
        ${pessoa.genre == 'm' ? 'Masculno' : 'Feminino'}
        </div>` 
    } else {
        string += `<div class="form-group col-md-6">
        <label for="firstName" class="required">Primeiro Nome</label>
        <input type="text" class="form-control" name="person[firstName]" id="firstName" placeholder="Nome" required="required" value=${pessoa.first_name}>
        </div>
        <div class="form-group col-md-6">
        <label for="firstName">Ultimo Nome</label>
        <input type="text" class="form-control" name="person[lastName]" id="lastName" placeholder="Ultimo nome" value=${pessoa.last_name}>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
        <label for="firstName">Cpf</label>
        <input type="text" class="form-control cpf-mask" name="person[cpf]" id="cpf" placeholder="Inserir apenas numeros" value=${pessoa.cpf}>
        </div>
        <div class="form-group col-md-4">
        <label for="firstName">RG (Registro Geral)</label>
        <input type="text" class="form-control" name="person[rg]" id="rg" placeholder="Inserir apenas numeros" value=${pessoa.rg}>
        </div>
        <div class="form-group col-md-2">
        <label for="birthData">
        <small>Data de Nascimento</small>
        </label>
        <input type="text" class="form-control date-mask numbers-only" name="person[birthDate]" id="birthdate" placeholder="Data" value=${pessoal.birth_date}>
        </div>
        <div class="form-group col-md-2">
        <label for="firstName">Genero</label>
        <select name="person[genre]" id="genre" class="form-control">
        <option selected="selected" disabled="disabled">Selecione</option>
        <option value="m" ${pessoa.genre == 'm' ? 'selected' : '' }>Masculino</option>
        <option value="f" ${pessoa.genre == 'f' ? 'selected' : '' }>Feminino</option>
        </select>
        </div>`
    }
    
    string += `</div>
    </div>
    </fieldset>
    
    <div class="row p-2">
    <fieldset class="p-1 my-1 border border-light col-md-8">
    <legend class="bg-light">&nbsp;Endereço</legend>
    <div class="row">
    `
    if (type == 'view') {
        string += `<div class="form-group col-md-5">
        <label for="road">Rua</label>
        ${endereco.road}
        </div>
        <div class="form-group col-md-4">
        <label for="road">Bairro</label>
        ${endereco.neigborhood}
        </div>
        <div class="form-group col-md-3">
        <label for="road">Numero</label>
        ${endereco.number}
        </div>
        </div>
        <div class="row">
        <div class="form-group col">
        ${endereco.zipcode}
        </div>
        <div class="form-group col">
        <label for="estado">Estado</label>
        ${endereco.estado.nome}
        </div>
        <div class="form-group col">
        <label>Municipio</label>
        ${endereco.municipio.nome}
        </div>
        </div>`
    } else {
        string += `<div class="form-group col-md-5">
        <label for="road">Rua</label>
        <input type="text" class="form-control" name="address[road]" id="road" placeholder="Rua x" value=${endereco.road}>
        </div>
        <div class="form-group col-md-4">
        <label for="road">Bairro</label>
        <input type="text" class="form-control" name="address[neightborhood]" id="neightborhood" placeholder="Inserir aqui" value=${endereco.neigborhood}>
        </div>
        <div class="form-group col-md-3">
        <label for="road">Numero</label>
        <input type="text" class="form-control" name="address[number]" id="number" placeholder="Numero" value=${endereco.number}>
        </div>
        </div>
        <div class="row">
        <div class="form-group col">
        <input type="text" class="form-control cep-mask" name="address[cep]" id="cep" placeholder="CEP" value=${endereco.zipcode}>
        </div>
        <div class="form-group col">
        <select name="address[estado]" class="form-control" id="estado" target="municipio">`
        
        for (var state in states) {
            string += `<option value=${state.id} ${ state.id == endereco.estado.id ? 'selected' : ''}>${state.nome}</option>`
        }

        string += `</select>
        </div>
        <div class="form-group col">
        <select name="address[municipio]" class="form-control" id="municipio">`

        for (var city in cities) {
            if (city.id == endereco.municipio.id) {
                string += `<option value=${city.id} selected>${city.nome}</option>`
            }
        }

        string +=`</select>
        </div>
        </div>`
    }
    
    string += `</fieldset>
    <fieldset class="p-1 my-1 border border-light col-md-4">
    <legend class="bg-light">&nbsp;Contato</legend>
    <label for="numeber">Telefone</label>`
    
    if (type == 'view') {
        string += `<div class="form-inline my-1" cloneableField>
        <div clone-area>
        <div clone-field>
        <input type="text" class="form-control col-md-8 mx-1 phone-with-ddd-br numbers-only" id="phone" name="phone[0]" placeholder="numero com ddd">
        <button class="btn btn-primary fas fa-plus" add-btn></button>
        <button class="btn btn-danger fas fa-minus" rmv-btn></button>
        </div>
        </div>
        </div>
        <label for="numeber">Email</label>
        <div class="form-inline my-1" cloneableField>
        <div clone-area>
        <div clone-field>
        <input type="hidden" input-number='true' value="1">
        <input type="text" class="form-control col-md-8 mx-1" id="email" name="email[0]" placeholder="nome@dominio.com">
        <button class="btn btn-primary fas fa-plus" add-btn></button>
        <button class="btn btn-danger fas fa-minus" rmv-btn></button>
        </div>
        </div>
        </div>`
    } else {
        string += `<div class="form-inline my-1" cloneableField>
        <div clone-area>`
        
        for (var phones of phone) {
            string += `<div clone-field>
            <input type="text" class="form-control col-md-8 mx-1 phone-with-ddd-br numbers-only" id="phone" name="phone[0]" placeholder="numero com ddd" value=${phones.number}>
            <button class="btn btn-primary fas fa-plus" add-btn></button>
            <button class="btn btn-danger fas fa-minus" rmv-btn></button>
            </div>`
        }
        
        string += `</div>
        </div>
        <label for="numeber">Email</label>
        <div class="form-inline my-1" cloneableField>
        <div clone-area>`
        
        for (var emails of email) {
            string += `<div clone-field>
            <input type="hidden" input-number='true' value="1">
            <input type="text" class="form-control col-md-8 mx-1" id="email" name="email[0]" placeholder="nome@dominio.com" value=${emails.email}>
            <button class="btn btn-primary fas fa-plus" add-btn></button>
            <button class="btn btn-danger fas fa-minus" rmv-btn></button>
            </div>`
        }
        
        string += `</div>
        </div>`
    }
    
    string += `</fieldset>
    </div>
    </div>`
    
    if (!type == 'view') {
        string += `<div class="card-footer text-muted text-center">
        <input type="submit" class="btn btn-secondary w-50" send="/person/add/person" target="customer-form" value="Editar">
        </form>
        </div>`
    }
    
    string += `</div>
    </div>
    `
}