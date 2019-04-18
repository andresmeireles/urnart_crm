if (document.querySelector('#romaneioForm')) {
    document.addEventListener('DOMContentLoaded', () => {
        
        let originalAction = document.querySelector('form').getAttribute('action');

        document.addEventListener('mouseover', (el) => {

            if (el.target.id === 'save-action') {
                let form = el.target.closest('form');
                form.setAttribute('action', '/forms/save');
            }

        });

        document.addEventListener('mouseout', (el) => {

            if (el.target.id === 'save-action') {
                let form = el.target.closest('form');
                form.setAttribute('action', originalAction);
            }
        
        });

    })
}