module.exports = function (id = 'progress') {
    new progressbar.Line(`#${id}`, {
        strokeWidth: 4,
        easing: 'easeInOut',
        duration: 1400,
        color: '#FFEA82',
        trailColor: '#eee',
        trailWidth: 1,
        svgStyle: {width: '100%', height: '100%'}
    }).animate(1.0);
}