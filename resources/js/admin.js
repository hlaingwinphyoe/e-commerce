require('./bootstrap');
window.Popper = require('@popperjs/core').withDefaults;
window.$ = window.jQuery = require('jquery');
window.Vue = require('vue').withDefaults;
require('./components/admin/index.js');
const app = new Vue({
    el: '#fse-admin'
});

require('./admin/sidebar.js');


$(document).ready(function(){
    "use strict";
});
