module.exports = function (routeUrl, formData, responseFunction = null) {
	axios({
		method: 'POST',
		url: '/'+routeUrl,
		data: formData
	})
	.then( function (response) {
		responseFunction(response.data, response.headers['type-message']);
		return true;
	})
	.catch( function (error) {
		console.log(error);
	});
}