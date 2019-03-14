/**
 * name => notification
 *
 * @param text => string a ser mostrado pela notificacao
 * @param label => tipo de notificação
 * @param notyTimeout => tempo de duração da imagem
 * @param timeout
 */
module.exports = function (text, label = 'error', notyTimeout = 2500, timeout = 1000) {
    setTimeout(() => {
        var notify = new noty({
            text: `${text}`,
            layout: 'topCenter',
            type: `${label}`,
            theme: 'bootstrap-v4',
            animation: {
                open: 'animated fadeInUp', // Animate.css class names
                close: 'animated fadeOutDown' // Animate.css class names
            }
        }).show()
        notify.setTimeout(notyTimeout)
    }, timeout)
}