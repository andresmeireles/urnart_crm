if (document.querySelector('#manualOrder')) {

    document.addEventListener('focus', (el) => {

        if (el.target.hasAttribute('autocp')) {
            $(el.target).autocomplete({
                lookup: eval(el.target.getAttribute('autocp')),
                minChars: 0,
                onSelect: function (opt) {
                    switch (el.target.id) {
                        case 'payment':
                            paymentComplete(opt);
                            break;
                        case 'transporter':
                            transporterComplete(opt);
                            break;
                        default:
                            alert('hum?, tem algo errado.');
                            break;
                    }
                }
            })
        }

    }, true);

    const paymentComplete = (opt) => {
        let cod = Number(opt.cod)
        if (Number.isInteger(cod)) {
            document.querySelector('#paymentType').value = cod;
        }
        return true
    }

    const transporterComplete = (opt) => {
        let cod = Number(opt.cod)
        if (Number.isInteger(cod)) {
            document.querySelector('#transporter-number').value = cod;
            document.querySelector('#port').value = opt.port;

        }
        return true
    }
}