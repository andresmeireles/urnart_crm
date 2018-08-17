module.exports = function (url, method = 'POST', info = null, responseFunction = null) {
    if (method == 'PUT') {
        var obj = {}
        for (var [key,value] of info.entries()) {
            obj[key] = value
        }

        info = obj
    }

    axios({
        method: method,
        url: url,
        data: info
    })
    .then(function (response) {
        if (responseFunction == null) {
            return resopnse.data;
        }
        responseFunction(response);
    });
}