document.addEventListener('DOMContentLoaded', function () {
    const supportedTypes = [
        'jpg',
        'png',
        'jpeg'
    ]

    if (document.querySelector('[type="config"]')) {
        document.addEventListener('change', function (el) {
            el.preventDefault()
            let filePath = el.target.value
            let removePath = filePath.slice(0, 12)
            let fileName = filePath.replace(removePath, '')
            let extension = fileName.split('.').pop()
            if (supportedTypes.indexOf(extension) == -1) {
                notification(`Formato ${extension} não supportado`)
                return false
            }
            document.querySelector('.custom-file-label').innerHTML = fileName
        })
    }
})