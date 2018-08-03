module.exports = function (url, method = 'POST', info = null, responseFunction = null, dataName = 'id') {
	axios({
		method: method,
		url: url, 
		data: {
<<<<<<< HEAD
			id: (info || '')
		}, 
=======
			[dataName]: info
		},
>>>>>>> origin/dev
	})
	.then(function (response) {
		if (responseFunction) {
			responseFunction(response);
			return true;
		}
		console.log(response.data);		
	});
}