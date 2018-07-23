module.exports = function (url, method = 'POST', data = null, responseFunction = null) {
	axios({
		method: method,
		url: url, 
		data: data 
	})
	.then(function (response) {
		if (responseFunction == null) {
			return resopnse.data;
		}
		responseFunction(response);
	});
}