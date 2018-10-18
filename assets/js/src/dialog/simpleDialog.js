/**
 * name simpleDialog
 *
 * @param message
 * @param responseFunction
 */
module.exports = function (message, responseFunction = null) {
    var parsedMessage = `${message}`;

    vex.dialog.confirm({
        unsafeMessage: parsedMessage,
        callback: function (value) {
            if (value) {
                responseFunction()
            } else {
                return false
            }
        },
    });
}