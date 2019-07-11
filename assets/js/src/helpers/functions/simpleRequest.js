/**
 * name: simpleRequest
 *
 * @param url [url para requisição]
 * @param method [tipo de verbo http para requisição, valor padrão POST]
 * @param info [parametros da requisição, valor padrão NULL]
 * @param responseFunction [função de retorno de responsta, valor padrão NULL]
 * @param dataName [chave onde serão indexadas indexadas informações do parametro "info", valor padrão "id"]
 */
module.exports = function (url, method, info = null, responseFunction = null, dataName = 'id') {
	axios({
		method: method,
		url: url,
		data: {
			[dataName]: info
		}
	})
	.then(function (response) {
		if (responseFunction === null) {
            return response.data;
		}
		responseFunction(response);
                return;
	})
	.catch( (err) => {
		var notify = new noty({
			text: `${err}`,
			layout: 'topCenter',
                    type: "error",
                    theme: 'bootstrap-v4',
                    animation: {
                        open: 'animated fadeInUp', // Animate.css class names
                        close: 'animated fadeOutDown' // Animate.css class names
                    }
                }).show();
		notify.setTimeout(2500);
	});
}
