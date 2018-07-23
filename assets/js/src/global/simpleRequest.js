module.exports = function (url, method = 'POST', info = null, responseFunction = null) {
	axios({
		method: method,
		url: url, 
		data: {
			id: info
		}, 
	})
	.then(function (response) {
		if (responseFunction == null) {
			return resopnse.data;
		}
		responseFunction(response);
	});
}