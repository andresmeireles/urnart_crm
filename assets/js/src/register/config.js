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

        document.addEventListener('click', function (el) {
            if (el.target.hasAttribute('file')) {
                el.preventDefault()

                simpleDialog('Deseja resetar a imagem para forma pádrão?', function () {
                    let link = el.target.getAttribute('file')
                    let hash = el.target.getAttribute('hash')

                    simpleRequest(link, 'PUT', hash, function (response) {
                        let message = `Imagem resetado com sucesso, salve as configurações para aplicar alterações`
                        let type = 'success'
                        if (!response.data) {
                            message = 'Erro ao aplicar alterar alterações'
                            type = 'error'
                        }
                        notification(message, type)

                        return true
                    }, 'hash')
                })
            }
        })

        document.addEventListener('change', function (el) {
            if (el.target.classList.contains('custom-file-input')) {
                el.preventDefault()

                let filePath = el.target.value
                let removePath = filePath.slice(0, 12)
                let fileName = filePath.replace(removePath, '')
                let size = el.target.files[0].size
                let extension = el.target.files[0].type
                if (supportedTypes.indexOf(extension) == -1) {
                    document.querySelector('.custom-file-label').innerHTML = 'Selecione o arquivo'
                    notification(`Formato <span class="h5 text-dark">${extension}</span> não supportado`)
                    return false
                }
                if (size > 4000000) {
                    document.querySelector('.custom-file-label').innerHTML = 'Selecione o arquivo'
                    notification(`Imagem <span class="h5 text-dark">${fileName}</span> com tamanho maior que o suportado`)
                    return false
                }
                document.querySelector('.custom-file-label').innerHTML = fileName
            }
        })
    }
})