if (document.querySelector('#addUser')) {

    document.addEventListener('change', (el) => {
        let pass1 = document.querySelector('#pass');
        let pass2 = document.querySelector('#repass');

        if (el.target.id === 'pass') {
            if (!lengthPass(pass1.value)) {
                pass1.classList.add('is-invalid');
                pass1.classList.remove('is-valid');
                return true;
            }
            pass1.classList.remove('is-invalid');
            pass1.classList.add('is-valid');
        }

        if (el.target.id === 'repass') {
            if (!samePassVerification(pass1.value, pass2.value)) {
                pass2.classList.add('is-invalid');
                pass2.classList.remove('is-valid');
                return true;
            }
            pass2.classList.remove('is-invalid');
            pass2.classList.add('is-valid');
        }

    }, true);

}

const samePassVerification = (p1, p2) => {
    if (p1 === p2) {
        return true; 
    }

    return false;
}

/**
 * @type {String} [password] - Senha
 * 
 * @returns {Boolean} true or false
 */
const lengthPass = (pass) => {
    if (pass.length > 8) {
        return true; 
    }

    return false;
}