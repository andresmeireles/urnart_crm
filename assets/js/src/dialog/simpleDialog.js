// simpleDialog
module.exports = function (message) {
    var parsedMessage = `${message}`;

    vex.dialog.confirm({
        unsafeMessage: parsedMessage,
        callback: function (value) {
            if (value) {
                return true
            } 
        }
    });
}