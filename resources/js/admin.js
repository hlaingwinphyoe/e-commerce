require('./bootstrap');
window.Popper = require('@popperjs/core').withDefaults;
window.$ = window.jQuery = require('jquery');

require('./components/admin/index.js');

require('./admin/sidebar.js');

$(document).ready(function(){
   //
});
