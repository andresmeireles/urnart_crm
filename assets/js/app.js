/* global global */

/**
 * Import libs.
 */

require('bootstrap');
require('imask');
require('tablesorter');
require('jquery-mask-plugin');
require('devbridge-autocomplete');

const $ = require('jquery');
const fancybox = require('@fancyapps/fancybox');
const noty = require('noty');
const moment = require('moment');
moment.locale('pt-br');

const numeralJs = require('numeral');
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
    ordinal: function (number) {
        return number === 1 ? 'er' : 'ème';
    },
    currency: {
        symbol: 'R$'
    }
});
numeralJs.locale('br');

const vexjs = require('vex-js');
vexjs.registerPlugin(require('vex-dialog'));
vexjs.defaultOptions.className = 'vex-theme-default';
vexjs.dialog.buttons.YES.text = 'Sim';
vexjs.dialog.buttons.NO.text = 'Cancelar';

const pond = require('filepond');

import '@chenfengyuan/datepicker/dist/datepicker';
import '@chenfengyuan/datepicker/i18n/datepicker.pt-BR';

import 'chart.js';
import 'chartjs-plugin-labels';

//globals
global.$ = global.jQuery = $;
global.axios = require('axios');
global.tablesorter = require('tablesorter');
global.rot = require('rot');
global.noty = noty;
global.numeral = numeralJs;
global.vex = vexjs;
global.filePond = pond;
global.moment = moment;

// root folder
global.messageSend = require('./src/messageDispatcher');
global.modal = require('./src/openModal');
global.insert = require('./src/globals');
global.sendSimpleRequest = require('./src/sendSimpleRequest');

// global folder
global.simpleRequestForm = require('./src/global/simpleRequestForm');
global.showDate = require('./src/global/showDate');
global.genericSend = require('./src/global/genericSend');
global.cloneFieldForm = require('./src/global/cloneFieldForm');

// dialogs folder
global.defaultDialog = require('./src/dialog/defaultDialog');
global.simpleDialog = require('./src/dialog/simpleDialog');

//register templates
global.customerTemplate = require('./src/register/templates/customerTemplate');

/******************* 
 ***** HELPERS *****
 *******************/

//functions
global.isNullOrWhiteSpace = require('./src/helpers/functions/isNullOrWhiteSpace');
global.strToMoney = require('./src/helpers/functions/strToMoney');
global.checkUndefined = require('./src/helpers/functions/checkUndefined');
global.notification = require('./src/helpers/functions/notification');
global.sendFl = require('./src/helpers/classes/sendDataFormless');
global.getFormLessData = require('./src/helpers/classes/sendDataFormless');
global.sendDataWithCsrf = require('./src/helpers/functions/sendDataWithCsrf');
global.checkMask = require('./src/helpers/functions/checkMask');
global.simpleRequest = require('./src/helpers/functions/simpleRequest');

// validations
global.checkRequired = require('./src/helpers/functions/checkRequired');

/***********************
 ******* IMPORTS *******
 ***********************/

import './src/sidebarAction.js';
import './src/globals';

import './src/form/createFormModal.js';
import './src/form/cloneField.js';
import './src/form/form.js';
import './src/form/pdfSend';
import './src/form/triggerMultipleForms';

import './src/storage/feedstock';
import './src/storage/feedStockActions';

import './src/register/registerEvents';
import './src/register/customer';
import './src/register/cloneFieldCustomer';
import './src/register/config';
import './src/register/loadAjax';

// reports
import './src/reports/survey';
import './src/reports/surveyClone';
import './src/reports/boleto/boletoStartActions';
import './src/reports/boleto/boletoSendAction';
import './src/reports/boleto/viewBoleto';

//global tools
import './src/global/masks';

// order import
import './src/order/orderAction';
import './src/order/manualOrder';
import './src/order/totalPriceCalculator';
import './src/order/autocompleteInputs';
import './src/order/closeOrder';
import './src/order/removeOrder';

/*********************************************** 
 ******** HELPERS FUNCTIONS AND SYMBOLS ********
 ***********************************************/

//auto classes
import './src/helpers/classes/noKeypress';
import './src/helpers/classes/sorter';
import './src/helpers/classes/disabledClick';
import './src/helpers/classes/oneTimeClick';
import './src/helpers/classes/autoChange';
import './src/helpers/classes/autoClick';
import './src/helpers/classes/autoFocus';
import './src/helpers/classes/numbersOnly';

if (document.querySelector('.f-date')) {
    var dateMask = new IMask(
            document.querySelector('.f-date'),
            {
                mask: Date,
                lazy: false,
            }
    );
}
