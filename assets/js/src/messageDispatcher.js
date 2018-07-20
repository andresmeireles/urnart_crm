module.exports = function(type, message) {
	return `
	<div class="alert alert-error alert-${type} text-center flash" id="alert-rmv">
	<b>${message}</b>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">&times;</span>
	</button>
	</div>
	`;
}