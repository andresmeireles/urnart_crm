document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (el) {
        if (el.target.getAttribute('send')) {
            el.preventDefault()
            var link = el.target.getAttribute('send')
            var dataTarget = el.target.getAttribute('target')
            var form = document.getElementById(dataTarget)
            var data = new FormData(form)
            
            axios({
                method: 'post',
                url: link,
                data: data
            })
            .then(function (response) {
                console.log(response.data);
                })
            alert(link)

            /** 
            simpleRequestForm(link, 'post', null, data,function (response) {
                console.log(response.data);
            })
            alert(link)*/
        }
    })

    document.addEventListener('change', function (el) {
        
        if (el.target.id == 'estado') {
            var stateValue = el.target.value
            var entity = el.target.getAttribute('target')
            var url = `/register/get/criteria/${entity}`
            simpleRequest(url, 'POST', stateValue, function(response) {
                console.log(response.data)
            }, 'idUf')
        } 
               
    })
});