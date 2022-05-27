require('./bootstrap');
window.Popper = require('@popperjs/core').withDefaults;
window.$ = window.jQuery = require('jquery');
require('bootstrap');
window.htmlToImage = require('html-to-image');

require('./components/admin/index.js');

require('./admin/sidebar.js');
require('./components/to-image.js');

$(document).ready(function(){
   //
});
