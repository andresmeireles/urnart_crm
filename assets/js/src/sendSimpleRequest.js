module.exports = function (routeUrl, formData, responseFunction = null) {
	axios({
		method: 'POST',
		url: '/'+routeUrl,
		data: formData
	})
	.then( function (response) {
		responseFunction(response);
	})
	.catch( function (response) {
		alert('é tetra');
	});
}