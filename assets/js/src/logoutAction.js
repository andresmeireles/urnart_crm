document.addEventListener('click', (el) => {
    if (el.target.classList.contains('logout')) {
        simpleRequest('/user', 'PATCH', null, (response) => {
            console.log(response);
            alert(response.data);
        });
        alert('xof');
        el.preventDefault();
        return false;
    }
});