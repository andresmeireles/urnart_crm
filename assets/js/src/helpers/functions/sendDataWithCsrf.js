/**
 * sendDataWithCsrf
 * 
 * @param methodVerb        [string] Nome do verbo da requisição.
 * @param urlPath           [string] Rota para requisição.
 * @param dataInfo          [array]  Dados para a requisição.
 * @param csrfToken         [object] Objeto <input> com nome token e atributo value para ter valor atulizado
 * @param responseFunction  [string] Função de resposta
 */
module.exports = function (methodVerb, urlPath, dataInfo = null, csrfToken, responseFunction = null) {
    axios({
        method: methodVerb,
        url: urlPath,
        data: dataInfo,
        headers: {
            'auth': csrfToken.value
        }
    })
    .then(function (response) {
        csrfToken.value = response.headers.csrf;
        if (responseFunction === null) {
            return response;
        }
        responseFunction(response);
    })
    .catch(function (error) {
        //console.log(error);
        throw Error(error);
    })
}