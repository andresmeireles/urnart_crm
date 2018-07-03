require('bootstrap');
require('imask');
require('print-js');

global.axios = require('axios');
const $ = require('jquery');
global.$ = global.jQuery = $;
global.openModal = require('./src/form/createFormModal.js');
global.printForm = require('./src/form/printForm');

const fancybox = require('@fancyapps/fancybox');
const print = require('jQuery.print');

import './src/sidebarAction.js';
import './src/globals.js';
import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/remand.js';
import './src/form/sendForm.js';

var dateMask = new IMask(
	document.querySelector('.f-date'),
	{
		mask: Date,
		lazy: false,
	}
);

var printer = function (element, mode) {
	printJS(element, mode);
}