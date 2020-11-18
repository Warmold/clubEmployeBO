/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Metronic
import '../metronic/dist/assets/plugins/global/plugins.bundle.css';
import '../metronic/dist/assets/css/style.bundle.css';
import '../metronic/dist/assets/css/skins/header/base/light.css';
import '../metronic/dist/assets/css/skins/header/menu/light.css';
import '../metronic/dist/assets/css/skins/brand/dark.css';
import '../metronic/dist/assets/css/skins/aside/dark.css';
import '../metronic/dist/assets/css/pages/login/login-4.css';

// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.css';

import $ from 'jquery';
import PerfectScrollbar from 'perfect-scrollbar';
import Cookies from 'js-cookie';
import Sticky from 'sticky-js';
import toastr from 'toastr';

global.$ = global.jQuery = $;
global.Cookies = Cookies;
global.PerfectScrollbar = PerfectScrollbar;
global.Sticky = Sticky;
global.toastr = toastr;

window.KTUtil = require("../metronic/src/assets/js/global/components/base/util");
window.KTApp = require("../metronic/src/assets/js/global/components/base/app");

// Vendors
require('bootstrap');
require('select2');
require('./components/select2');
