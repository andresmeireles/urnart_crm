/**
 * Action of remove a manual made order.
 */
if (document.querySelector('#manual-list')) {

    document.addEventListener('click', function (el) {

        if (el.target.classList.contains('order-close')) {
            el.preventDefault();
            let route = el.target.getAttribute('href');
            let orderId = route.substr(route.lastIndexOf('/')+1);
            simpleDialog(
                `Deseja finalizar o pedido ${orderId}?<br> <small class="text-danger">Após essa ação o pedido não poderá ser editado.</small>`,
                function () {
                    simpleRequest(route, 'PUT', null, function () {   
                        setTimeout(() => {
                            el.target.closest('.elRow').querySelector('.order-edit').remove();
                            el.target.closest('.elRow').querySelector('.order-print').remove();
                            el.target.closest('.elRow').querySelector('.order-auth').remove();
                            el.target.closest('.elRow').querySelector('.order-status').innerHTML = "FECHADO";
                            el.target.remove();
                            notification(
                                `Pedido ${orderId} fechado com sucesso`,
                                'success'
                            )
                        }, 500);
                    });      
                }
            );
            return false;
        }

    }, true);

} 