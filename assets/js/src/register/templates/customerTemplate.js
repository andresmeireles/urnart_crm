module.exports = function (data, type = 'view') {
    var pessoa = data.proprietarios[0].pessoa_fisica
    var endereco = pessoa.address
    var email = pessoa.emails
    var phone = pessoa.phones
    var states = simpleRequest('/register/get/estado', 'post', null, function (respose) {
        return Response.data
    })
    var cities = simpleRequest('/register/get/municipio', 'post', null, function (respose) {
        return Response.data
    })

    var birthDate = new Date(data.data_de_fundacao);
    var personBirthDate = new Date(pessoa.birth_date)
    
    var string = `<div id="dynamic-created-element" class="col-md-11 py-4 d-none">
    <div class="card">
    <div class="card-header text-center">
    <span style="font-size: 25px">${ (type == 'view') ? 'Visualizar' : 'Editar'} Cliente</span>
    </div>
    <div class="card-body">`
    
    if (type == 'edit') {
        string += `<form method="post" action="/person/edit/person" id="customer-update-form">`
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
        <div class="form-control">
        ${data.razao_social}
        </div>
        </div>
        <div class="form-group col-md-6">
        <label for="firstName">Nome Fantasia</label>
        <div class="form-control">
        ${data.nome_fantasia}
        </div>
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
        <input type="text" class="form-control" name="customer[nomeFantasia]" id="nomeFantasia" placeholder="Nome Fantasia" value="${data.nome_fantasia}">
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
        <div class="form-control">
        ${data.cnpj}
        </div>
        </div>
        <div class="form-group">
        <label for="situcaoCadastral">Situação Cadastral</label>
        <div class="form-control">
        ${data.situacao_cadastral == 1 ? 'HABILITADO' : data.situacao_cadastral == 2 ? 'NÃO HABILITADO' : 'ISENTO' }
        </div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
        <label for="firstName">Inscrição Estadual</label>
        <div class="form-control">
        ${data.inscricao_estadual}
        </div>
        </div>
        <div class="form-group col-md-2">
        <label for="birthData">
        <small>Data de Fundação</small>
        </label>
        <div class="form-control">
        ${showDate(birthDate)}
        </div>
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
        ${(data.situacao_cadastral == 1) ? 'checked' : ''}>
        </div>
        </div>
        <div type="text" class="form-control">contribuente</div>
        </div>
        <div class="input-group mx-1">
        <div class="input-group-prepend">
        <div class="input-group-text">
        <input type="radio" class="form-check" name="customer[situacaoCadastral]" id="contribuente" value="2" required ${data.situacao_cadastral == 2 ? 'checked' : ''}>
        </div>
        </div>
        <div type="text" class="form-control">não contribuente</div>
        </div>
        <div class="input-group mx-1">
        <div class="input-group-prepend">
        <div class="input-group-text">
        <input type="radio" class="form-check" name="customer[situacaoCadastral]" id="contribuente" value="3" required ${data.situacao_cadastral == 3 ? 'checked' : ''}>
        </div>
        </div>
        <div type="text" class="form-control">Isento</div>
        </div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-4">
        <label for="firstName">Inscrição Estadual</label>
        <input type="text" class="form-control" name="customer[inscricaoEstadual]" id="inscricaoEstadual" placeholder="Inserir apenas numeros" value="${data.inscricao_estadual}">
        </div>
        <div class="form-group col-md-2">
        <label for="birthData">
        <small>Data de Fundação</small>
        </label>
        <input type="text" class="form-control numbers-only date-mask" name="customer[fondationDate]" id="birthdate" placeholder="Data" value=${birthDate.getDate() + 1}/${birthDate.getMonth() + 1}/${birthDate.getFullYear()}>
        </div>
        `
    }
    
    string += `</div>
    </div>
    </fieldset>
    
    <fieldset class="p-1 my-1 border border-light">
    <legend class="bg-light">
    &nbsp;Informações de Proprietários
    </legend>
    <div class="px-1">
    <div class="row">`
    
    if (type == 'view') {
        string += `<div class="form-group col-md-6">
        <label for="firstName">Nome:</label>
        <div class="form-control">
        ${pessoa.first_name} ${pessoa.last_name}
        </div>
        </div>
        <div class="form-group col-md-6">
        <label for="firstName">Cpf</label>
        <div class="form-control">
        ${pessoa.cpf}
        </div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col-md-6">
        <label for="firstName">RG (Registro Geral)</label>
        <div class="form-control">
        ${pessoa.rg}
        </div>
        </div>
        <div class="form-group col-md-4">
        <label for="birthData">
        <small>Data de Nascimento</small>
        </label>
        <div class="form-control">
        ${showDate(personBirthDate)}
        </div>
        </div>
        <div class="form-group col-md-2">
        <label for="firstName">Genero</label>
        <div class="form-control">
        ${pessoa.genre == 'm' ? 'Masculno' : pessoa.genre == 'f' ? 'Feminino': ''}
        </div>
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
        <input type="text" class="form-control date-mask numbers-only" name="person[birthDate]" id="birthdate" placeholder="Data" value=${personBirthDate.getDate() + 1}/${personBirthDate.getMonth() + 1}/${personBirthDate.getFullYear()}>
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
        <div class="form-control">
        ${endereco.road}
        </div>
        </div>
        <div class="form-group col-md-4">
        <label for="road">Bairro</label>
        <div class="form-control">
        ${endereco.neighborhood}
        </div>
        </div>
        <div class="form-group col-md-3">
        <label for="road">Numero</label>
        <div class="form-control">
        ${endereco.number}
        </div>
        </div>
        </div>
        <div class="row">
        <div class="form-group col">
        <label for="zipCode">CEP</label>
        <div class="form-control">
        ${endereco.zipcode}
        </div>
        </div>
        <div class="form-group col">
        <label for="estado">Estado</label>
        <div class="form-control">
        ${endereco.estado != null ? endereco.estado.nome : ''}
        </div>
        </div>
        <div class="form-group col">
        <label>Municipio</label>
        <div class="form-control">
        ${endereco.municipio != null ? endereco.municipio.nome : ''}
        </div>
        </div>
        </div>`
    } else {
        string += `<div class="form-group col-md-5">
        <label for="road">Rua</label>
        <input type="text" class="form-control" name="address[road]" id="road" placeholder="Rua x" value=${endereco.road}>
        </div>
        <div class="form-group col-md-4">
        <label for="road">Bairro</label>
        <input type="text" class="form-control" name="address[neightborhood]" id="neightborhood" placeholder="Inserir aqui" value=${endereco.neighborhood}>
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
        <div clone-area>`
        
        for (var phones of phone) {
            string += `<div clone-field>
            <div class="form-control phone-with-ddd-br">
            ${phones.number}
            </div>
            </div>`
        }
        
        string += `</div>
        </div>
        <label for="numeber">Email</label>
        <div class="form-inline my-1" cloneableField>
        <div clone-area>`
        
        for (var emails of email) {
            string += `<div clone-field>
            <div class="form-control">
            ${emails.email}
            </div>
            </div>`
        }
        
        string += `</div>
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
    
    if (type == 'edit') {
        string += `<div class="card-footer text-muted text-center">
        <input type="submit" class="btn btn-secondary w-50" send-update=${data.id} target="customer-update-form" value="Editar">
        </form>
        </div>`
    }
    
    string += `</div>
    </div>
    `
    return string
}