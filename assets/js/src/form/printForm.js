module.exports = function () {
	$.print('#printable', {
		globalStyles: false,
		mediaPrint: true,
	});

	$.fancybox.close();
}