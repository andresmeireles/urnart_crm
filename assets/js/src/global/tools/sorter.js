document.addEventListener('DOMContentLoaded', function () {
    if ( document.querySelector('.sortable') ) {
        $('.sortable').tablesorter({
            sortList: [[1, 0], [2, 0]]
        });
    }
})