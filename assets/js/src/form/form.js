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
	}
})
