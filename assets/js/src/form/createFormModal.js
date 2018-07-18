module.exports = function() {

	$.fancybox.open({
		src: '#modal',
		type: 'inline',
		opts: {
			afterLoad: function () {
				var modalWindow = document.querySelector('#modal').classList.remove('d-none');	
			},
		}
	});	
};