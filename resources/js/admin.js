require('./bootstrap');
window.Popper = require('@popperjs/core').withDefaults;
window.$ = window.jQuery = require('jquery');
require('./popper-min.js');
require('bootstrap');
window.htmlToImage = require('html-to-image');

require('./components/admin/index.js');

require('./admin/sidebar.js');
require('./components/to-image.js');

$(document).ready(function(){
   const triggerTabList = document.querySelectorAll('#slide-nav button');
   triggerTabList.forEach(triggerEl => {
   const tabTrigger = new bootstrap.Tab(triggerEl);

   triggerEl.addEventListener('click', event => {
      event.preventDefault();
      tabTrigger.show();
   });
   });
});
