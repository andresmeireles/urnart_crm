/**
* sendSimpleRequest
*
* routeUrl => Rota da requisição HTTP
*/
module.exports = function (routeUrl, formData, method = 'POST' , token = null, responseFunction = null) {
	axios({
		method: `${method}`,
		url: '/'+routeUrl,
		data: formData,
		headers: {
			'auth': token
		}
	})
	.then( function (response) {
		responseFunction(response.data, response.headers['type-message']);
		return true;
	})
	.catch( (error) => {
		var notify = new noty({
			text: "{{ error }}",
			layout: 'topCenter',
                    //type: "{{ label }}",
                    theme: 'bootstrap-v4',
                    animation: {
                        open: 'animated fadeInUp', // Animate.css class names
                        close: 'animated fadeOutDown' // Animate.css class names
                    }
                }).show()
		notify.setTimeout(2500)
	});
}
