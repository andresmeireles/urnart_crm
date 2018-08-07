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