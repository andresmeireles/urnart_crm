require('bootstrap');
require('imask');

global.axios = require('axios');
const $ = require('jquery');

import './src/sidebarAction.js';
import './src/globals.js';
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