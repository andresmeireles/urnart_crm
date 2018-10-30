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
	.catch( function (error) {
		console.log(error);
	});
}