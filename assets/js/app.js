require('bootstrap');
require('imask');

const $ = require('jquery');

global.$ = global.jQuery = $;
global.axios = require('axios');
global.messageSend = require('./src/messageDispatcher');
global.openModal = require('./src/form/createFormModal.js');
global.modal = require('./src/openModal');
global.printForm = require('./src/form/printForm');

const fancybox = require('@fancyapps/fancybox');
const print = require('jQuery.print');

import Tablesort from 'tablesort';
import './src/sidebarAction.js';
import './src/globals';
import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/remand.js';
import './src/form/sendForm.js';
import './src/storage/feedstock';

if (document.querySelector('.sortable')) {
	Tablesort(document.querySelector('.sortable'));
}

var dateMask = new IMask(
	document.querySelector('.f-date'),
	{
		mask: Date,
		lazy: false,
	}
);

var loadingDiv = document.getElementById('loading');

function showSpinner() {
  loadingDiv.style.visibility = 'visible';
}

function hideSpinner() {
  loadingDiv.style.visibility = 'hidden';
}