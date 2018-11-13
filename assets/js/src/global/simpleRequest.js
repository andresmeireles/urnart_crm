/**
 * name: simpleRequest
 *
 * @param url [url para requisição]
 * @param method [tipo de verbo http para requisição, valor padrão POST]
 * @param info [parametros da requisição, valor padrão NULL]
 * @param responseFunction [função de retorno de responsta, valor padrão NULL]
 * @param dataName [chave onde serão indexadas indexadas informações do parametro "info", valor padrão "id"]
 */
module.exports = function (url, method = 'POST', info = null, responseFunction = null, dataName = 'id') {
	axios({
		method: method,
		url: url,
		data: {
			[dataName]: info
		},
	})
	.then(function (response) {
		if (responseFunction == null) {
			return resopnse.data;
		}
		responseFunction(response);
	});
}
