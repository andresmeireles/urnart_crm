module.exports = function (url, method = 'POST', info = null, responseFunction = null) {
    axios({
        method: method,
        url: url,
        data: info,
//        headers: {"Content-type": "application/x-www-form-urlencoded"}
    })
        .then(function (response) {
            if (responseFunction == null) {
                return resopnse.data;
            }
            responseFunction(response);
        });
}