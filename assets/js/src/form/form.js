document.addEventListener('DOMContentLoaded', function () {
	if (document.querySelector('#type-form')) {
		document.addEventListener('click', function(el) {
			if (el.target.id == 'exportPdf') {
				el.preventDefault()
				let formTag = document.querySelector('form')
				let reportName = document.querySelector('#exportPdf').getAttribute('name') 
				let formData = new FormData(formTag)				
				simpleRequestForm(`/forms/pdf/${reportName}`, 'POST', formData)
			}
		})

		document.addEventListener('click', function (el) {
			if (el.target.hasAttribute('form-name')) {
                let fornName = el.target.getAttribute('form-name')
                document.querySelector('form').setAttribute('action', `/forms/${fornName}`)
            }
			if (el.target.hasAttribute('onclick')) {
				el.preventDefault()
			}
		})
	}
})
