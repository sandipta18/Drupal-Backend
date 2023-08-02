(function ($, Drupal, drupalSettings) {
  'use strict'
  Drupal.behaviors.sitewideBehavior = {
    attach: function (context, settings) {
      var $fieldElement = $('#edit-field-phone-number-0-value', context);
        $fieldElement.on('keyup', function () {
          var $insertedData = $fieldElement.val();
          var $phoneNumber = $insertedData.replace(/\D/g, '');
          if ($phoneNumber.length > 10) {
            $phoneNumber = $phoneNumber.substring(0, 10);
          }
          if ($phoneNumber.length === 10) {
            // Changes the phone number format from xxxxxxxxxx to
            // (xxx) xxx-xxxx
            var $formattedPhoneNumber = '(' + $phoneNumber.substring(0, 3) + ') '
            + $phoneNumber.substring(3, 6) + '-' + $phoneNumber.substring(6, 10);
            $fieldElement.val($formattedPhoneNumber);
          }
        });
    },
  }
})(jQuery, Drupal, drupalSettings);

(function ($, Drupal, drupalSettings) {
  'use strict'
  Drupal.behaviors.bundleBehaviour = {
    attach: function (context, settings) {
      var target = $('.field__item', context);
      target.on('click', function () {
        $(this).addClass("border-black");
      });
    },
  };
})(jQuery, Drupal, drupalSettings);
