module.exports = function (routeUrl, formData) {
	axios({
		method: 'POST',
		url: '/'+routeUrl,
		data: formData
	})
	.then( function (json) {
		alert('sucesso');
	});
}