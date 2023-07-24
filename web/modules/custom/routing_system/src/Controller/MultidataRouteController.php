<?php

namespace Drupal\routing_system\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for page route.
 */
class MultidataRouteController extends ControllerBase {

  /**
   * Returns the overview page.
   *
   * @param int $data
   *   Accepting dynamic parameter from url.
   *
   * @return array
   *   A render array to display the hello message.
   */
  public function multiRoute($data) {
    return [
      '#type'   => 'markup',
      '#markup' => $this->t('It is node @value', [
        '@value' => $data,
      ]),
    ];
  }

}
