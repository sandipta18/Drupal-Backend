<?php

namespace Drupal\routingsystem\Controller;

use Drupal\Core\Controller\ControllerBase as ControllerControllerBase;

/**
 * Controller for page route
 */
class MultidataRoute extends ControllerControllerBase {

   /**
    * @param mixed $data
    *   Accepting dynamic parameter from url
    *
    * @return array
    *   A render array to display the hello message
    */
   public function multiRoute($data) {
      return [
        '#type'   => 'markup',
        '#markup' => t('Hi @value',[
         '@value' => $data])
      ];
   }
}
