if(document.querySelector('.numbers-only')) {
    let onlyNumberFields = document.querySelectorAll('.numbers-only');
    for (let onf of onlyNumberFields) {
        onf.addEventListener('keypress', function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault();
                return false;
            }
            return true;
        });
    }
}

if (document.querySelectorAll('.numbers-float-only')) {
    var inputs = document.querySelectorAll('.numbers-float-only');

    for (var input of inputs) {
        input.addEventListener('keypress', function (evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode == 44) {
                return true;
            }
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                evt.preventDefault();
                return false;
            }
            return true;
        });
    }
}