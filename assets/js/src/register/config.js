document.addEventListener('DOMContentLoaded', function () {
    const supportedTypes = [
        'jpg',
        'png',
        'jpeg'
    ]

    if (document.querySelector('[type="config"]')) {

        const input = document.querySelector('.filepond')
        const pond = filePond.create(input)
        filePond.setOptions({
            instantUpload: false,
            server: './configuration'
        })

        document.addEventListener('change', function (el) {
            if (el.target.classList.contains('custom-file-upload')) {
                el.preventDefault()
                let filePath = el.target.value
                let removePath = filePath.slice(0, 12)
                let fileName = filePath.replace(removePath, '')
                let extension = fileName.split('.').pop()
                if (supportedTypes.indexOf(extension) == -1) {
                    document.querySelector('.custom-file-label').innerHTML = ''
                    notification(`Formato ${extension} não supportado`)
                    return false
                }
                document.querySelector('.custom-file-label').innerHTML = fileName
            }
        })
    }
})