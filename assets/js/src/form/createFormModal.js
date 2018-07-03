module.exports = function() {

	$.fancybox.open({
		src: '#modal',
		type: 'inline',
		opts: {
			afterLoad: function () {
				var modalWindow = document.querySelector('#modal').classList.remove('d-none');
				var total = Math.round((document.querySelectorAll('.tag').length)/11);
				var b = document.querySelector('.total').innerHTML = total;						
			},
		}
	});	
};