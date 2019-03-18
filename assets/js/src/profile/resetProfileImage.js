document.addEventListener('DOMContentLoaded', () => {

    if (document.querySelector('#editUserData')) {
        
        document.addEventListener('click', (el) => {

            if (el.target.id === 'resetUserProfileImage') {
                el.preventDefault();
                document.querySelector('#profileImage').value = "defaultImageReset";
                document.querySelector('#uploadFileName').innerHTML = "Resetar imagem.";
                notification('Para terminar de resetar imagem atualize seus dados', 'warning');
            }
            
        });

        document.addEventListener('change', (el) => {
            if (el.target.classList.contains('custom-file-input')) {
                el.preventDefault();
                let file = el.target.files[0];
                document.querySelector('#uploadFileName').innerHTML = file.name;
            }
        });

    }

})