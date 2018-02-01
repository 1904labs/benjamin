// 'use strict';

wp.customize.bind('ready', function() {

  wp.customize.bind( 'change', function ( setting ) {

    if ( setting.id.indexOf( '_settings_active' ) > -1 ) {
      var pos = setting.id.lastIndexOf('_settings_active');
      var name = setting.id.substr(0, pos);
      var val = setting.get();
      var $parentSection = wp.customize.section( 'single_settings_section' ).container.find('#accordion-section-' + name);
      $parentSection = $parentSection.prevObject;
      var elms = [$parentSection[0], $parentSection[1]];

      elms.forEach(function(elm,i,a){

        if(val == 'yes') {
          elm.classList.add('control-section-is-active');
        } else {
          elm.classList.remove('control-section-is-active');
        }
      });


    }

  });
  
});


jQuery(document).ready(function($) {

  require('./checkbox-group');
  require('./load-preview-url');
  require('./refresh-alert');

  require('./sortables');

});

window.$ = jQuery;