// defaultDialog
module.exports = function (message, name, entity, responseFunction = null) {
  var parsedMessage = message + name + ' da tabela ' + entity + '?';
  vex.dialog.confirm({
    unsafeMessage: parsedMessage,
    callback: function (value) {
      if (value) {
        responseFunction()
      } else {
        return false
      }
    }
  });
}