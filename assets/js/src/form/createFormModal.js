module.exports = function(id) {

	$.fancybox.open({
		src: '#'+id,
		type: 'inline',
		opts: {
			afterLoad: function () {
				var modalWindow = document.querySelector('#'+id).classList.remove('d-none');	
			},
			afterClose: function () {
				var modalWindow = document.querySelector('#' + id).classList.add('d-none')				
			}
		}
	});	
};