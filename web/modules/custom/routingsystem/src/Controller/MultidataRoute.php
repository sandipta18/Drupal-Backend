<?php

/**
 * @file
 * Takes argument from the url and displays it
 */
namespace Drupal\routingsystem\Controller;

use Drupal\Core\Controller\ControllerBase as ControllerControllerBase;

/**
 * Controller for page route
 */
class MultidataRoute extends ControllerControllerBase {

   /**
    * @param mixed $data
    *
    * @return array
    *  A render array to display the hello message
    */
   public function multiRoute($data) {
      return [
        '#type' => 'markup',
        '#markup' => t('Hi @value',
        ['@value' => $data]),
      ];
   }
}
