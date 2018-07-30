require('bootstrap')
require('imask')
require('tablesorter')

const $ = require('jquery')
const fancybox = require('@fancyapps/fancybox')
const vexjs = require('vex-js')
vexjs.registerPlugin(require('vex-dialog'))
vexjs.defaultOptions.className = 'vex-theme-default'
vexjs.dialog.buttons.YES.text = 'Sim'
vexjs.dialog.buttons.NO.text = 'Cancelar'

//globals
global.$ = global.jQuery = $
global.axios = require('axios')
global.vex = vexjs

// root folder
global.messageSend = require('./src/messageDispatcher');
global.modal = require('./src/openModal');
global.insert = require('./src/globals');
global.sendSimpleRequest = require('./src/sendSimpleRequest');

// global folder
global.simpleRequest = require('./src/global/simpleRequest');
global.simpleRequestForm = require('./src/global/simpleRequestForm');

// forms folder
global.openModal = require('./src/form/createFormModal.js');
global.printForm = require('./src/form/printForm');

// dialogs folder
global.defaultDialog = require('./src/dialog/defaultDialog');

import './src/sidebarAction.js';
import './src/globals';

import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/remand.js';
import './src/form/sendForm.js';

import './src/storage/feedstock';

import './src/register/registerEvents';
import './src/register/customer';

if (document.querySelector('.sortable')) {
	$('.sortable').tablesorter({
		sortList: [[1,0], [2,0]]
	});
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