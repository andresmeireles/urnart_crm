# Funções customizadas de customizadas.

Existem algumas funções feitas. Elas tem varias funções simples.

## Auto Change 

#### auto-float-convert-blur

Inserindo a classe `auto-float-convert-blur` em uma tag `input` o valor será convertido para real no input.
O valor em int é enviado para uma tag com o attributo `converted-value`.

##### Exemplo
`<input type="hidden" converted-value name="nomeDaTag">
 <input type="text" class="form-control numbers-float-only auto-float-convert-blur">`

#### calendar-selector

Inserindo a classe `calendar-selector` em uma tag input. Ao ter seu valor alterado esse valor será inserido em `input` e o valor sera convertido e posto de formatado em um campo com `id` que será procurado pelo valor _string_ no atributo `date-target`.

##### Exemplo
`<input type="hidden" id="converted-date">
 <input type="text" class="form-control no-keypress calendar-selector" date-target="#converted-date" >`
 **Para evitar bugs com estes campos é bom inserir a classe _no-keypress_ no mesmo input de _calendar-selector_**

## Auto Focus

#### calendar-selector

Inserindo a class `calendar-selector` em uma tag `input` então será aberto um calendarío para seleção de data. Basta apertar na data desejada e o valor será copiado para o campo `input`.

Para melhorar o uso desta função é aconselhado por a tag `no-keypress` para bloquear a inserção de quaisquer digitos se não que será inserido ao selecionar a data do calendario.

##### Exemplo
`<input type="text" class="form-control no-keypress calendar-selector">`

#### auto-clear
**TAG** : `input`, `textarea`
**Função** : Ao dar focus no campo com a classe. O valor do campo é limpo.

## Classes de ajuda

Algumas classes tem propositos especificos.

### no-keypress
**TAG** : `input`, `textarea`
**Função** : Neste `input` nenhuma tecla irá functionar.

### numbers-only
**TAG** : `input`, `textarea`
**Funções** : Neste `input` apenas numeros irão funcionar.

## numbers-float-only
**TAG** : `input`, `textarea`
**Função** : Neste `input`apenas teclas de numeros e a virgula **[** *,* **]**