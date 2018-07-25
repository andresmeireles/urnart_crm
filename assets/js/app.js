require('bootstrap');
require('imask');
require('jspdf');

const $ = require('jquery');
const fancybox = require('@fancyapps/fancybox');
const vexjs = require('vex-js');
vexjs.registerPlugin(require('vex-dialog'))
vexjs.defaultOptions.className = 'vex-theme-os'
vexjs.dialog.buttons.YES.text = 'Sim';
vexjs.dialog.buttons.NO.text = 'Cancelar';

global.$ = global.jQuery = $;
global.axios = require('axios');

global.vex = vexjs
global.messageSend = require('./src/messageDispatcher');
global.openModal = require('./src/form/createFormModal.js');
global.modal = require('./src/openModal');
global.printForm = require('./src/form/printForm');
global.insert = require('./src/globals');
global.sendSimpleRequest = require('./src/sendSimpleRequest');
global.simpleRequest = require('./src/global/simpleRequest');
global.defaultDialog = require('./src/dialog/defaultDialog');

import Tablesort from 'tablesort';
import './src/sidebarAction.js';
import './src/globals';
import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/remand.js';
import './src/form/sendForm.js';
import './src/storage/feedstock';
import './src/register/registerEvents';

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
};

function hideSpinner() {
  loadingDiv.style.visibility = 'hidden';
};