require('bootstrap')
require('imask')
require('tablesorter')
require('jquery-mask-plugin')
require('devbridge-autocomplete')

const $ = require('jquery')
const fancybox = require('@fancyapps/fancybox')
const noty = require('noty')

const numeralJs = require('numeral')
numeralJs.register('locale', 'br', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
    abbreviations: {
        thousand: 'k',
        million: 'm',
        billion: 'b',
        trillion: 't'
    },
    ordinal : function (number) {
        return number === 1 ? 'er' : 'ème';
    },
    currency: {
        symbol: 'R$'
    }
});
numeralJs.locale('br');

const vexjs = require('vex-js')
vexjs.registerPlugin(require('vex-dialog'))
vexjs.defaultOptions.className = 'vex-theme-default'
vexjs.dialog.buttons.YES.text = 'Sim'
vexjs.dialog.buttons.NO.text = 'Cancelar'

const pond = require('filepond')

//globals
global.$ = global.jQuery = $
global.axios = require('axios')
global.tablesorter = require('tablesorter')
global.rot = require('rot')
global.noty = noty
global.numeral = numeralJs
global.vex = vexjs
global.filePond = pond

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
global.getFormLessData = require('./src/global/tools/sendDataFormless')
global.notification = require('./src/global/tools/notification')

// validations
global.checkRequired = require('./src/global/validation/checkRequired')

// dialogs folder
global.defaultDialog = require('./src/dialog/defaultDialog')
global.simpleDialog = require('./src/dialog/simpleDialog')

//register templates
global.customerTemplate = require('./src/register/templates/customerTemplate')

import './src/sidebarAction.js'
import './src/globals'

import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/form.js'

import './src/storage/feedstock'
import './src/storage/feedStockActions'

import './src/register/registerEvents';
import './src/register/customer';
import './src/register/cloneFieldCustomer';
import './src/register/config';

import './src/global/masks';

//global tools
import './src/global/tools/sorter';
import './src/global/tools/progress';
import './src/global/tools/disabledClick';

// order import
import './src/order/orderAction'

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
