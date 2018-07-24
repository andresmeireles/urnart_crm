// defaultDialog
module.exports = function (message) {
  vex.dialog.confirm({
    message: message,
    callback: function (value) {
      if (value) {
        console.log(value)
      }
    }
  });
}