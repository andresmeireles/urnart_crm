if (document.querySelector('#truckOrder')) {
    document.addEventListener('DOMContentLoaded', () => {
        autocompleteFunction();
        var keys = [];
        document.addEventListener('click', (element) => {
            if (element.target.hasAttribute('add-btn')) {
                setTimeout(() => {
                    autocompleteFunction();
                    var visiableTextInputFields = document.querySelectorAll('.insertOrder');
                    for (let inputField of visiableTextInputFields) {
                        inputField.removeAttribute('name');
                    }
                }, 1000);
            }   
        })
        document.addEventListener('keyup', (element) => {
            if (element.target.classList.contains('insertOrder')) {
                keys[[element.keyCode]] = true;
                if (keys[17] && keys[40]) {
                    element.target.dispatchEvent(new Event('focus'));
                }
            }
        }, true);
    });
    const autocompleteFunction = () => {
        $('.insertOrder').autocomplete({
            lookup: orders,
            minChars: 0,
            triggerSelectOnValidInput: true,
            onSelect: function (suggestion) {
                this.value = '';
                let selectedOptions = document.querySelectorAll('.insertOrder');
                if (selectedOptions.length > 1 ) {
                    selectedOptions.forEach((element) => {
                        if (element.value === suggestion.value) {
                            throw new Error('same options is not allowed');
                        }
                    });
                }
                this.closest('div').querySelector('.inserter').value = suggestion.data;
                this.value = suggestion.value;
            }
        });
    }
}
