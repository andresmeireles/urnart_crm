import './src/sidebarAction.js';
import './src/globals.js';
import './src/form/cloneField.js';
import './src/form/remand.js';

require('bootstrap');
require('imask');

const $ = require('jquery');

var dateMask = new IMask(
	document.querySelector('.f-date'),
	{
		mask: Date,
		lazy: false,
	}
);