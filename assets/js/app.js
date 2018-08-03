require('bootstrap')
require('imask')
require('tablesorter')
require('jquery-mask-plugin')

<<<<<<< HEAD
const $ = require('jquery');

global.$ = global.jQuery = $;
global.axios = require('axios');
global.alertify = require('alertifyjs');

=======
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
>>>>>>> origin/dev
global.messageSend = require('./src/messageDispatcher');
global.modal = require('./src/openModal');
global.insert = require('./src/globals');
global.sendSimpleRequest = require('./src/sendSimpleRequest');

<<<<<<< HEAD
global.openModal = require('./src/form/createFormModal.js');
global.printForm = require('./src/form/printForm');

=======
// global folder
>>>>>>> origin/dev
global.simpleRequest = require('./src/global/simpleRequest');
global.simpleRequestForm = require('./src/global/simpleRequestForm');

// forms folder
global.openModal = require('./src/form/createFormModal.js');
global.printForm = require('./src/form/printForm');

// dialogs folder
global.defaultDialog = require('./src/dialog/defaultDialog');

import './src/sidebarAction.js'
import './src/globals'

<<<<<<< HEAD
global.defaultDialog = require('./src/dialogs/defaultDialog')

const fancybox = require('@fancyapps/fancybox');
const print = require('jQuery.print');
=======
import './src/global/masks'
>>>>>>> origin/dev

import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/remand.js';
import './src/form/sendForm.js';

import './src/storage/feedstock';

import './src/register/registerEvents';
<<<<<<< HEAD
import './src/register/reloadTable';
import './src/dialogs/defaultDialog';
=======
import './src/register/customer';
import './src/register/cloneFieldCustomer';
>>>>>>> origin/dev

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