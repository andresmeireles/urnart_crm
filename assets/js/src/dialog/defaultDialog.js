// defaultDialog
module.exports = function (message, name, entity) {
  var parsedMessage = message + name + ' da tabela ' + entity;
  vex.dialog.confirm({
    message: parsedMessage,
    callback: function (value) {
      if (value) {
        console.log(value)
      }
    }
  });
}