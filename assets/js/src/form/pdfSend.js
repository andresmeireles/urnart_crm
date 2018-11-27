document.addEventListener('mouseover', function (el) {
    if (document.querySelector('#action-form')) {
        if (el.target.id === 'send-pdf') {
            let actionName = document.getElementById('action-form').getAttribute('action');
            if (actionName.search('/pdf') !== -1) {
                return false;
            }
            let newActionName = actionName+'/pdf';
            document.getElementById('action-form').setAttribute('action', newActionName);
        }
    }
});

document.addEventListener('mouseout', function (el) {
    if (document.querySelector('#action-form')) {
        if (el.target.id === 'send-pdf') {
            let actionName = document.getElementById('action-form').getAttribute('action');
            if (actionName.search('/pdf') === -1) {
                return false;
            }
            let newActionName = actionName.substr(0, actionName.search('/pdf'));
            document.getElementById('action-form').setAttribute('action', newActionName);
        }
    }
});