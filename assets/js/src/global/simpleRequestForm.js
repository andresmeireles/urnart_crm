module.exports = function (url, method = 'POST', info = null, responseFunction = null) {
    axios({
        method: method,
        url: url,
        data: {
            person: info,
            ninja: 'olá'
        },
    })
        .then(function (response) {
            if (responseFunction == null) {
                return resopnse.data;
            }
            responseFunction(response);
        });
}