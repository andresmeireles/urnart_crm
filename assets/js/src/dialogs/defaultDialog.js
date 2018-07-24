// defaultDialog
module.exports = function (message) {
    alertify.confirm(`${message}.`,
  function(){
    alertify.success('okMsg');
  },
  function(){
    alertify.error('cancelMsg');
  });
}