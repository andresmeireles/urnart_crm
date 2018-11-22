document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (el) {
        if (el.target.classList.contains('reload')) {
            var element = el.target;
            var name = element.closest('div').getAttribute('data-name');
            var tableName = document.querySelector('#'+name+'-body');
            var url = `/register/get/${name}`;

            //clear table
            tableName.innerHTML = '';

            //get table data
            simpleRequest(url, 'post', null, function (response) {
                var data = response.data
                var tr = '';

                for (var info of data) {
                    tr += `<tr>
                    <td class="d-none id-number">
                        ${info.id}
                    </td>
                    <td class="text-left">
                        ${info.name}
                    </td>
                    <td class="text-right">
                        <span class="badge badge-pill badge-danger remover cursor-decoration">Remover</span>
                    </td>
                </tr>`
                }

                tableName.innerHTML = tr;
            });
        }

    });
});