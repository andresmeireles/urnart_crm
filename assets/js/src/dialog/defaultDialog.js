// defaultDialog
module.exports = function (message, name, entity, responseFunction = null) {
  var parsedMessage = `${message} <u class="text-danger">${name}</u> da tabela <u class="text-danger">${entity}</u> ?`;

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