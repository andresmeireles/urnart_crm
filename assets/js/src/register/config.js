document.addEventListener('DOMContentLoaded', function () {
    const supportedTypes = [
        'image/jpeg',
        'image/jpg',
        'png'
    ]

    if (document.querySelector('[type="config"]')) {

        const input = document.querySelector('.filepond')
        const pond = filePond.create(input)
        filePond.setOptions({
            //instantUpload: false,
            server: './write/images'
        })

        document.addEventListener('change', function (el) {
            if (el.target.classList.contains('custom-file-input')) {
                el.preventDefault()

                let filePath = el.target.value
                let removePath = filePath.slice(0, 12)
                let fileName = filePath.replace(removePath, '')
                let extension = el.target.file[0].type
                if (supportedTypes.indexOf(extension) == -1) {
                    document.querySelector('.custom-file-label').innerHTML = 'Selecione o arquivo'
                    notification(`Formato ${extension} não supportado`)
                    return false
                }
                document.querySelector('.custom-file-label').innerHTML = fileName
            }
        })
    }
})