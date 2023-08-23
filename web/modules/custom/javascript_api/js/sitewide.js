(function ($, Drupal, drupalSettings) {
  'use Strict';
  Drupal.behaviors.sitewideBehavior = {
    attach: function (context, settings) {
      $(document).ready(function () {
        console.log("hello");
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
