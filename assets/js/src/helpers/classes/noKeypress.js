/**
 * Cancel all keyboard clicks
 */
if (document.querySelectorAll('.no-keypress')) {
    var inputs = document.querySelectorAll('.no-keypress');

    for (var input of inputs) {
        input.addEventListener('keydown', function (evt) {
            evt.preventDefault();
            return false;
        }, true);
    }
}