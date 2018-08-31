require('bootstrap')
require('imask')
require('tablesorter')
require('jquery-mask-plugin')

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
global.tablesorter = require('tablesorter')
global.vex = vexjs
global.rot = require('rot')

// root folder
global.messageSend = require('./src/messageDispatcher');
global.modal = require('./src/openModal');
global.insert = require('./src/globals');
global.sendSimpleRequest = require('./src/sendSimpleRequest');

// global folder
global.simpleRequest = require('./src/global/simpleRequest')
global.simpleRequestForm = require('./src/global/simpleRequestForm')
global.checkMask = require('./src/global/checkMask')
global.showDate = require('./src/global/showDate')
global.genericSend = require('./src/global/genericSend')
global.cloneFieldForm = require('./src/global/cloneFieldForm')
global.getOptionText = require('./src/global/tools/getOptionText')
global.sendFl = require('./src/global/tools/sendDataFormless')

// validations
global.checkRequired = require('./src/global/validation/checkRequired')

// forms folder
global.openModal = require('./src/form/createFormModal.js');
global.printForm = require('./src/form/printForm');

// dialogs folder
global.defaultDialog = require('./src/dialog/defaultDialog');
global.simpleDialog = require('./src/dialog/simpleDialog');

//register templates
global.customerTemplate = require('./src/register/templates/customerTemplate')

import './src/sidebarAction.js'
import './src/globals'

import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/remand.js';
import './src/form/sendForm.js';

import './src/storage/feedstock'
import './src/storage/feedStockActions'

import './src/register/registerEvents';
import './src/register/customer';
import './src/register/cloneFieldCustomer';

import './src/global/masks'
import './src/global/tools/sorter'

//if (document.querySelector('.sortable')) {
//	$('.sortable').tablesorter({
//		sortList: [[1, 0], [2, 0]]
//	});
//}

if (document.querySelector('.f-date')) {
	var dateMask = new IMask(
		document.querySelector('.f-date'),
		{
			mask: Date,
			lazy: false,
		}
	);
}

var loadingDiv = document.getElementById('loading');

function showSpinner() {
  loadingDiv.style.visibility = 'visible';
};

function hideSpinner() {
  loadingDiv.style.visibility = 'hidden';
};